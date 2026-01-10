<?php
require_once("app/Models/mHocSinh.php");
require_once("app/Models/mLop.php");
require_once("app/Models/mNamHoc.php"); // Gọi thêm model Năm học
class cHocSinh
{
    private $model;
    private $modelLop;
    private $modelNamHoc;

    public function __construct()
    {
        $this->model = new mHocSinh();
        $this->modelLop = new mLop();
        $this->modelNamHoc = new mNamHoc();
    }

    public function hienThiDanhSach()
    {
        // ===> [MỚI] XỬ LÝ TẢI FILE MẪU CSV ĐỘNG <===
        if (isset($_POST['btnDownloadMauHS'])) {
            // 1. Xóa buffer đầu ra để ngăn mã HTML bị lẫn vào file CSV
            if (ob_get_level()) ob_end_clean();

            $filename = "Mau_Import_HocSinh.csv";

            // 2. Thiết lập header để trình duyệt tải file về
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);

            // 3. Mở luồng đầu ra
            $output = fopen('php://output', 'w');

            // 4. Thêm BOM để Excel hiển thị đúng tiếng Việt
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // 5. Ghi dòng tiêu đề
            fputcsv($output, ['MaHS', 'HoTen', 'NgaySinh', 'GioiTinh', 'DiaChi', 'TenLop']);

            // 6. Ghi dữ liệu mẫu
            fputcsv($output, ['HS202401', 'Tran Van B', '2008-05-20', 'Nam', 'Quan 1, TP HCM', '10A1']);
            fputcsv($output, ['HS202402', 'Nguyen Thi C', '2008-08-15', 'Nu', 'Quan 3, TP HCM', '10A2']);

            fclose($output);
            exit(); // Dừng thực thi ngay lập tức
        }
        // ==========================================
        $editData = null;

        // --- XÁC ĐỊNH NĂM HỌC ĐANG LÀM VIỆC ---
        // Nếu URL có ?nam_hoc=... thì lấy, không thì lấy năm hiện tại (Active)
        $namHocHienTai = $this->modelNamHoc->getNamHocHienTai();
        $selectedNamHoc = isset($_GET['nam_hoc']) ? $_GET['nam_hoc'] : $namHocHienTai;

        // --- 1. XỬ LÝ IMPORT CSV ---
        // --- 1. XỬ LÝ IMPORT CSV (Đã Fix lỗi không hiện thông báo) ---
        if (isset($_POST['btnImportCSV'])) {
            // Kiểm tra file có tồn tại và không lỗi
            if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
                $file = $_FILES['file_csv']['tmp_name'];

                $handle = fopen($file, "r");
                if ($handle === FALSE) {
                    echo "<script>alert('Không thể đọc file!'); window.history.back();</script>";
                    exit();
                }

                // Bỏ qua dòng tiêu đề
                fgetcsv($handle);

                $success = 0;
                $fail = 0;
                $errors = [];

                while (($row = fgetcsv($handle)) !== FALSE) {
                    // CSV: [0]Mã, [1]Tên, [2]NgàySinh, [3]GiớiTinh, [4]ĐịaChi, [5]TênLớp
                    // Kiểm tra số cột tối thiểu để tránh lỗi offset
                    if (count($row) < 6) continue;

                    $ma_hs = trim($row[0]);
                    $ho_ten = trim($row[1]);
                    $ngay_sinh = trim($row[2]); // YYYY-MM-DD
                    $gioi_tinh = trim($row[3]);
                    $dia_chi = trim($row[4]);
                    $ten_lop = trim($row[5]); // VD: 10A1

                    if (empty($ma_hs)) continue; // Bỏ qua dòng trống

                    // Tìm ID lớp dựa vào Tên lớp
                    $lop_id = $this->model->getLopIdByTen($ten_lop, $selectedNamHoc);

                    if ($lop_id) {
                        // Kiểm tra trùng mã trước khi thêm để đếm lỗi chính xác
                        if ($this->model->checkDuplicateMaHS($ma_hs, 0)) {
                            $fail++;
                            $errors[] = "$ma_hs: Mã học sinh đã tồn tại.";
                        } else {
                            if ($this->model->insert($ma_hs, $ho_ten, $ngay_sinh, $gioi_tinh, $dia_chi, $lop_id)) {
                                $success++;
                            } else {
                                $fail++;
                                $safe_ten_lop = htmlspecialchars($ten_lop);
                                // Thông báo lỗi rõ ràng hơn
                                $errors[] = "$ma_hs: Không tìm thấy lớp '$safe_ten_lop' trong năm học hiện tại.";
                            }
                        }
                    } else {
                        $fail++;
                        $safe_ten_lop = htmlspecialchars($ten_lop); // Tránh lỗi JS nếu tên lớp có ký tự lạ
                        $errors[] = "$ma_hs: Không tìm thấy lớp '$safe_ten_lop'.";
                    }
                }
                fclose($handle);

                // --- HIỂN THỊ KẾT QUẢ ---
                $msg = "Import hoàn tất!\n- Thành công: $success\n- Thất bại: $fail";

                // Lấy tối đa 3 lỗi đầu tiên để hiển thị cho gọn
                if (count($errors) > 0) {
                    $limit_errors = array_slice($errors, 0, 3);
                    $msg .= "\n\nChi tiết lỗi:\n" . implode("\n", $limit_errors);
                    if (count($errors) > 3) $msg .= "\n... và " . (count($errors) - 3) . " lỗi khác.";
                }

                // [QUAN TRỌNG] Dùng json_encode để chuỗi an toàn tuyệt đối với Javascript
                $jsonMsg = json_encode($msg);

                echo "<script>
                        alert($jsonMsg); 
                        window.location.href='index.php?act=quanlyhocsinh';
                      </script>";

                // [CỰC KỲ QUAN TRỌNG] Dừng PHP lại ngay để trình duyệt chạy Alert
                exit();
            } else {
                echo "<script>alert('Vui lòng chọn file CSV hợp lệ!'); window.history.back();</script>";
                exit();
            }
        }

        // --- 2. XỬ LÝ LƯU (THÊM/SỬA THỦ CÔNG) ---
        // --- XỬ LÝ LƯU (THÊM / SỬA) ---
        // --- XỬ LÝ LƯU (THÊM / SỬA) ---
        if (isset($_POST['btnLuuHS'])) {
            $id_hs = isset($_POST['id_hs']) ? $_POST['id_hs'] : '';

            // 1. Lấy dữ liệu (Sử dụng isset để tránh lỗi Warning)
            $ma_hs     = isset($_POST['ma_hs']) ? strtoupper(trim($_POST['ma_hs'])) : '';
            $ho_ten    = isset($_POST['ho_ten']) ? mb_convert_case(trim($_POST['ho_ten']), MB_CASE_TITLE, "UTF-8") : '';
            $ngay_sinh = isset($_POST['ngay_sinh']) ? $_POST['ngay_sinh'] : '';

            // [QUAN TRỌNG] Lấy đủ 3 trường còn thiếu
            $gioi_tinh = isset($_POST['gioi_tinh']) ? $_POST['gioi_tinh'] : 'Nam';
            $dia_chi   = isset($_POST['dia_chi']) ? trim($_POST['dia_chi']) : '';
            $lop_id    = isset($_POST['lop_id']) ? $_POST['lop_id'] : '';

            $redirectUrl = "index.php?act=quanlyhocsinh&nam_hoc=$selectedNamHoc";
            $errors = [];

            // --- VALIDATION ---
            if (empty($ma_hs)) $errors[] = "Chưa nhập Mã Học Sinh.";
            if (empty($ho_ten)) $errors[] = "Chưa nhập Họ tên.";

            // [CHECK LỚP] Nếu không có lớp -> Báo lỗi ngay
            if (empty($lop_id)) {
                $errors[] = "Vui lòng chọn Lớp học cho học sinh này.";
            }

            if (!preg_match('/^HS[0-9]+$/', $ma_hs)) {
                $errors[] = "Mã HS không hợp lệ (Phải là HS + số).";
            }

            // Nếu có lỗi nhập liệu -> Dừng
            if (!empty($errors)) {
                $msg = implode("\\n- ", $errors);
                echo "<script>alert('Vui lòng kiểm tra:\\n- $msg'); window.history.back();</script>";
                return;
            }

            // --- GỌI MODEL ---
            if (!empty($id_hs)) {
                // UPDATE
                if ($this->model->checkDuplicateMaHS($ma_hs, $id_hs)) {
                    echo "<script>alert('Lỗi: Mã HS $ma_hs đã tồn tại!'); window.history.back();</script>";
                    return;
                }
                if ($this->model->update($id_hs, $ma_hs, $ho_ten, $ngay_sinh, $gioi_tinh, $dia_chi, $lop_id)) {
                    echo "<script>alert('Cập nhật thành công!'); window.location.href='$redirectUrl';</script>";
                }
            } else {
                // INSERT (Truyền đủ 6 tham số)
                if ($this->model->insert($ma_hs, $ho_ten, $ngay_sinh, $gioi_tinh, $dia_chi, $lop_id)) {
                    echo "<script>alert('Thêm mới thành công!'); window.location.href='$redirectUrl';</script>";
                }
                // Không cần else echo lỗi ở đây nữa vì Model đã echo lỗi chi tiết rồi
            }
        }

        // --- XỬ LÝ XÓA ---
        if (isset($_GET['delete_id'])) {
            $redirectUrl = "index.php?act=quanlyhocsinh&nam_hoc=$selectedNamHoc";
            if ($this->model->delete($_GET['delete_id'])) {
                echo "<script>alert('Đã xóa học sinh!'); window.location.href='$redirectUrl';</script>";
            }
        }

        // --- [MỚI] XỬ LÝ KHÔI PHỤC ---
        if (isset($_GET['restore_id'])) {
            $redirectUrl = "index.php?act=quanlyhocsinh&nam_hoc=$selectedNamHoc";
            if ($this->model->restore($_GET['restore_id'])) {
                echo "<script>alert('Đã khôi phục học sinh thành công!'); window.location.href='$redirectUrl';</script>";
            } else {
                echo "<script>alert('Lỗi khôi phục!'); window.location.href='$redirectUrl';</script>";
            }
        }
        // --- 4. CHUẨN BỊ DỮ LIỆU VIEW ---
        // --- CHUẨN BỊ DỮ LIỆU HIỂN THỊ ---
        if (isset($_GET['edit_id'])) {
            $editData = $this->model->getById($_GET['edit_id']);
        }

        $keyword = isset($_GET['search']) ? $_GET['search'] : null;
        $filter_lop = isset($_GET['lop']) ? $_GET['lop'] : null;

        $dsNamHoc = $this->modelNamHoc->getAllNamHoc();
        $dsLop = $this->modelLop->getAll(null, $selectedNamHoc, null);
        $dsHS = $this->model->getAll($keyword, $filter_lop, $selectedNamHoc);

        include("app/Views/quanlyhocsinh.php");
    }
}
