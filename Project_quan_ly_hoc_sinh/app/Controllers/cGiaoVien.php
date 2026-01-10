<?php
require_once("app/Models/mGiaoVien.php");
require_once("app/Models/mMonHoc.php");

class cGiaoVien
{
    private $model;
    private $modelMon;

    public function __construct()
    {
        $this->model = new mGiaoVien();
        $this->modelMon = new mMonHoc();
    }

    public function hienThiDanhSach()
    {
        // ===> [CẬP NHẬT] XỬ LÝ TẢI FILE MẪU CSV CHUẨN <===
        if (isset($_POST['btnDownloadMau'])) {
            // 1. Xóa buffer hệ thống (Tránh lỗi file bị chèn HTML lạ)
            if (ob_get_level()) ob_end_clean();

            $filename = "Mau_Import_GiaoVien.csv";

            // 2. Cấu hình Header để trình duyệt hiểu đây là file CSV tải về
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);

            // 3. Mở luồng ghi dữ liệu
            $output = fopen('php://output', 'w');

            // 4. [QUAN TRỌNG] Thêm BOM (Byte Order Mark) để Excel mở tiếng Việt không bị lỗi font
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // 5. Ghi dòng TIÊU ĐỀ (Header)
            // Lưu ý: Tên cột không dấu để tránh lỗi khi đọc code
            fputcsv($output, ['MaGV', 'HoTen', 'SDT', 'NgaySinh', 'DiaChi', 'Email', 'TenMon']);

            // 6. Ghi dòng MẪU SỐ 1 (Ví dụ Giáo viên Toán)
            fputcsv($output, [
                'GV101',                // Mã GV
                'Nguyen Van An',        // Họ tên
                '0901234567',           // SĐT (Nên dùng đầu số 09 cho chuẩn)
                '1990-05-20',           // Ngày sinh (Định dạng YYYY-MM-DD chuẩn SQL)
                'Quan 1, TP.HCM',       // Địa chỉ
                'an.nguyen@email.com',  // Email
                'Toán'                  // Tên môn (Phải khớp chính xác với CSDL)
            ]);

            // 7. Ghi dòng MẪU SỐ 2 (Ví dụ Giáo viên Văn - Phụ nữ)
            fputcsv($output, [
                'GV102',
                'Tran Thi Bich',
                '0987654321',
                '1995-12-15',
                'Quan 3, TP.HCM',
                'bich.tran@email.com',
                'Ngữ Văn'               // Tên môn khác để test đa dạng
            ]);

            // 8. Đóng file và dừng chương trình ngay lập tức
            fclose($output);
            exit();
        }
        $editData = null;

        if (isset($_POST['btnLuuGV'])) {
            // Lấy dữ liệu
            $id_gv = isset($_POST['id_gv']) ? $_POST['id_gv'] : ''; // Lấy ID trước
            $ma_gv = strtoupper(trim($_POST['ma_gv']));
            $ho_ten = mb_convert_case(trim($_POST['ho_ten']), MB_CASE_TITLE, "UTF-8");
            $sdt = trim($_POST['sdt']);
            $ngay_sinh = $_POST['ngay_sinh'];
            $dia_chi = trim($_POST['dia_chi']);
            $email = trim($_POST['email']);
            $mon_hoc_id = $_POST['mon_hoc_id'];

            $errors = [];
            // 1. Check Mã GV (Luôn check định dạng)
            if (!preg_match('/^GV[0-9]+$/', $ma_gv)) {
                $errors[] = "Mã GV không hợp lệ (Phải là GV + số).";
            }

            // 2. Check Tên
            if (mb_strlen($ho_ten) < 5) $errors[] = "Họ tên quá ngắn.";
            if (strpos($ho_ten, ' ') === false) $errors[] = "Vui lòng nhập đầy đủ Họ và Tên.";

            // Check tuổi
            $birthDate = new DateTime($ngay_sinh);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
            if ($age < 20) $errors[] = "Giáo viên phải >= 20 tuổi.";

            // --- XỬ LÝ LỖI ---
            if (!empty($errors)) {
                $msg = implode("\\n", $errors);
                echo "<script>alert('LỖI DỮ LIỆU:\\n$msg'); window.history.back();</script>";
                return;
            }

            // --- THỰC HIỆN LƯU ---
            if (!empty($id_gv)) {
                // [QUAN TRỌNG] Kiểm tra trùng mã GV (trừ chính mình ra)
                if ($this->model->checkDuplicateMaGV($ma_gv, $id_gv)) {
                    echo "<script>alert('Lỗi: Mã GV $ma_gv đã được sử dụng bởi người khác!'); window.history.back();</script>";
                    return;
                }

                // GỌI HÀM UPDATE MỚI (Truyền thêm ma_gv)
                if ($this->model->update($id_gv, $ma_gv, $ho_ten, $sdt, $ngay_sinh, $dia_chi, $email, $mon_hoc_id)) {
                    echo "<script>alert('Cập nhật thành công!'); window.location.href='index.php?act=quanlydanhmucgiaovien';</script>";
                } else {
                    echo "<script>alert('Lỗi cập nhật hệ thống!');</script>";
                }
            } else {
                // INSERT
                if ($this->model->insert($ma_gv, $ho_ten, $sdt, $ngay_sinh, $dia_chi, $email, $mon_hoc_id)) {
                    echo "<script>alert('Thêm mới thành công!'); window.location.href='index.php?act=quanlydanhmucgiaovien';</script>";
                } else {
                    echo "<script>alert('Lỗi: Mã GV đã tồn tại!');</script>";
                }
            }
        }


        // Xử lý xóa
        if (isset($_GET['delete_id'])) {
            if ($this->model->delete($_GET['delete_id'])) {
                echo "<script>alert('Đã chuyển trạng thái sang Nghỉ việc!'); window.location.href='index.php?act=quanlydanhmucgiaovien';</script>";
            }
        }
        // ===> [THÊM ĐOẠN NÀY] Xử lý Khôi phục <===
        if (isset($_GET['restore_id'])) {
            if ($this->model->restore($_GET['restore_id'])) {
                echo "<script>alert('Đã khôi phục hoạt động cho giáo viên thành công!'); window.location.href='index.php?act=quanlydanhmucgiaovien';</script>";
            } else {
                echo "<script>alert('Lỗi khôi phục!');</script>";
            }
        }
        // Xử lý lấy dữ liệu sửa
        if (isset($_GET['edit_id'])) {
            $editData = $this->model->getById($_GET['edit_id']);
        }

        // --- LOGIC MỚI: XỬ LÝ IMPORT CSV ---
        // --- LOGIC MỚI: XỬ LÝ IMPORT CSV (Đã Fix lỗi trang trắng) ---
        if (isset($_POST['btnImportCSV'])) {
            if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
                $file = $_FILES['file_csv']['tmp_name'];

                // Mở file
                $handle = fopen($file, "r");
                if (!$handle) {
                    echo "<script>alert('Không thể mở file!'); window.history.back();</script>";
                    exit();
                }

                // Bỏ qua dòng Header
                fgetcsv($handle);

                $successCount = 0;
                $failCount = 0;
                $errors = [];

                while (($row = fgetcsv($handle)) !== FALSE) {
                    // Kiểm tra số cột tối thiểu (tránh dòng trống cuối file gây lỗi)
                    if (count($row) < 7) continue;

                    // Lấy dữ liệu và dọn dẹp (Trim)
                    $ma_gv = trim($row[0]);
                    $ho_ten = trim($row[1]);
                    $sdt = trim($row[2]);
                    $ngay_sinh = trim($row[3]);
                    $dia_chi = trim($row[4]);
                    $email = trim($row[5]);
                    $ten_mon = trim($row[6]); // Tên môn nhập từ Excel

                    // Nếu mã GV rỗng thì bỏ qua
                    if (empty($ma_gv)) continue;

                    // 1. Tìm ID môn học dựa trên Tên Môn
                    $mon_hoc_id = $this->model->getMonHocIdByTen($ten_mon);

                    if ($mon_hoc_id) {
                        // 2. Nếu tìm thấy môn -> Gọi hàm Insert
                        // Kiểm tra xem Mã GV đã tồn tại chưa để báo lỗi chính xác
                        if ($this->model->checkDuplicateMaGV($ma_gv, 0)) {
                            $failCount++;
                            $errors[] = "Dòng $ma_gv: Mã giáo viên đã tồn tại.";
                        } else {
                            if ($this->model->insert($ma_gv, $ho_ten, $sdt, $ngay_sinh, $dia_chi, $email, $mon_hoc_id)) {
                                $successCount++;
                            } else {
                                $failCount++;
                                $errors[] = "Dòng $ma_gv: Lỗi hệ thống khi lưu.";
                            }
                        }
                    } else {
                        // 3. Nếu KHÔNG tìm thấy môn
                        $failCount++;
                        // Dùng htmlspecialchars để tránh lỗi XSS hoặc lỗi JS nếu tên môn có dấu nháy
                        $safe_ten_mon = htmlspecialchars($ten_mon, ENT_QUOTES);
                        $errors[] = "Dòng $ma_gv: Không tìm thấy môn học '$safe_ten_mon' trong hệ thống.";
                    }
                }

                fclose($handle);

                // --- TẠO THÔNG BÁO KẾT QUẢ (Dùng JSON_ENCODE để an toàn tuyệt đối) ---
                $msg = "Import hoàn tất!\n- Thành công: $successCount\n- Thất bại: $failCount";
                if (count($errors) > 0) {
                    // Chỉ lấy tối đa 3 lỗi đầu tiên để hiển thị cho gọn
                    $limit_errors = array_slice($errors, 0, 3);
                    $msg .= "\n\nChi tiết lỗi:\n" . implode("\n", $limit_errors);
                    if (count($errors) > 3) $msg .= "\n... và " . (count($errors) - 3) . " lỗi khác.";
                }

                // Chuyển chuỗi PHP sang chuỗi JSON an toàn cho JS
                $jsonMsg = json_encode($msg);

                echo "<script>
                        alert($jsonMsg); 
                        window.location.href='index.php?act=quanlydanhmucgiaovien';
                      </script>";
                exit(); // Dừng code PHP để trình duyệt chạy script chuyển trang
            } else {
                echo "<script>alert('Vui lòng chọn file CSV hợp lệ!'); window.history.back();</script>";
                exit();
            }
        }
        // --- ĐOẠN CUỐI HÀM: LẤY DANH SÁCH HIỂN THỊ ---

        // 1. Lấy tham số tìm kiếm từ URL (nếu có)
        $keyword = isset($_GET['search']) ? trim($_GET['search']) : null;
        $filterMon = isset($_GET['filter_mon']) ? $_GET['filter_mon'] : null;

        // 2. Gọi Model với tham số lọc
        $dsGV = $this->model->getAll($keyword, $filterMon);

        // 3. Lấy danh sách môn (để đổ vào dropdown lọc)
        $dsMon = $this->modelMon->getAll();

        include("app/Views/quanlydanhmucgiaovien.php");
    }
}
