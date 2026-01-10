<?php
require_once("app/Models/mDiem.php");
require_once("app/Models/mNamHoc.php");
require_once("app/Models/mGiaoVien.php");
// HÀM PHỤ TRỢ: Chuyển tiếng Việt có dấu sang không dấu (để đặt tên file an toàn)
function vn_to_str($str)
{
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    return str_replace(' ', '_', $str); // Thay khoảng trắng bằng gạch dưới
}
class cDiem
{
    private $model;
    private $modelNamHoc;
    private $modelGV;

    public function __construct()
    {
        $this->model = new mDiem();
        $this->modelNamHoc = new mNamHoc();
        $this->modelGV = new mGiaoVien();
    }

    public function hienThiGiaoDien()
    {
        // 1. KIỂM TRA ĐĂNG NHẬP
        // Lưu ý: Phải dùng đúng tên biến Session mà cDangNhap.php đã tạo
        if (!isset($_SESSION['user_id'])) {
            echo "<script>alert('Vui lòng đăng nhập để sử dụng chức năng này!'); window.location.href='index.php?act=dangnhap';</script>";
            return;
        }

        // Lấy ID tài khoản đang đăng nhập
        $userId = $_SESSION['user_id'];
        // 1. XỬ LÝ XUẤT FILE (CÓ TÊN ĐẦY ĐỦ + DANH SÁCH HỌC SINH)
        // =================================================================
        if (isset($_POST['btnExportExcel'])) {
            $lid = $_POST['lop_id'];
            $mid = $_POST['mon_id'];
            $nid = $_POST['nam_hoc_id'];
            $hk = $_POST['hoc_ky'];

            if (ob_get_level()) ob_end_clean();

            // 1. Lấy thông tin để đặt tên file
            $info = $this->model->getThongTinExport($lid, $mid, $nid);

            // Tạo tên file: Diem_10A1_Toan_HK1_2023-2024.csv
            $fileName = "Diem_" . vn_to_str($info['ten_lop']) . "_"
                . vn_to_str($info['ten_mon']) . "_"
                . $hk . "_"
                . vn_to_str($info['ten_nam']) . ".csv";

            // 2. Lấy danh sách học sinh để điền sẵn vào file
            $dataHS = $this->model->getBangDiem($lid, $mid, $nid, $hk);

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $fileName);

            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            // HEADER: Thêm cột "HoVaTen" cho giáo viên dễ nhìn (Import sẽ bỏ qua cột này)
            fputcsv($output, ['MaHS', 'HoVaTen', 'Mieng1', 'Mieng2', '15p1', '15p2', 'Giuaky', 'Cuoiky']);

            if ($dataHS) {
                while ($row = $dataHS->fetch_assoc()) {
                    // Điền sẵn Mã HS, Tên HS và Điểm cũ (nếu có)
                    fputcsv($output, [
                        $row['ma_hs'],
                        $row['ho_ten'], // Cột này chỉ để hiển thị
                        $row['diem_mieng_1'],
                        $row['diem_mieng_2'],
                        $row['diem_15p_1'],
                        $row['diem_15p_2'],
                        $row['diem_1tiet'],
                        $row['diem_thi']
                    ]);
                }
            }
            fclose($output);
            exit();
        }

        // =================================================================
        // 2. XỬ LÝ NHẬP FILE (IMPORT - UPDATE LOGIC MỚI)
        // =================================================================
        if (isset($_POST['btnImportExcel'])) {
            $lid = $_POST['lop_id'];
            $mid = $_POST['mon_id'];
            $nid = $_POST['nam_hoc_id'];
            $hk = $_POST['hoc_ky'];

            if (isset($_FILES['file_csv']) && $_FILES['file_csv']['error'] == 0) {
                $file = $_FILES['file_csv']['tmp_name'];
                $handle = fopen($file, "r");
                fgetcsv($handle); // Bỏ dòng tiêu đề

                // Map MaHS -> ID
                $dsHS = $this->model->getBangDiem($lid, $mid, $nid, $hk);
                $mapMaToId = [];
                if ($dsHS) while ($r = $dsHS->fetch_assoc()) $mapMaToId[$r['ma_hs']] = $r['hs_id'];

                $count = 0;
                while (($row = fgetcsv($handle)) !== FALSE) {
                    if (count($row) < 2) continue;

                    // CẤU TRÚC FILE MỚI: 
                    // [0]=MaHS, [1]=HoTen (Bỏ qua), [2]=M1, [3]=M2, [4]=15p1, [5]=15p2, [6]=GK, [7]=CK

                    $ma_hs = trim($row[0]);

                    if (isset($mapMaToId[$ma_hs])) {
                        $hs_id = $mapMaToId[$ma_hs];

                        // Lấy điểm (Chú ý index tăng lên 1 do có thêm cột HoTen)
                        $m1     = isset($row[2]) ? $row[2] : "";
                        $m2     = isset($row[3]) ? $row[3] : "";
                        $d15_1  = isset($row[4]) ? $row[4] : "";
                        $d15_2  = isset($row[5]) ? $row[5] : "";
                        $gk     = isset($row[6]) ? $row[6] : "";
                        $ck     = isset($row[7]) ? $row[7] : "";

                        // Tính TB
                        $dtb = "";
                        if ($gk !== "" && $ck !== "") {
                            $tong = 0;
                            $heso = 0;
                            if (is_numeric($m1)) {
                                $tong += floatval($m1);
                                $heso++;
                            }
                            if (is_numeric($m2)) {
                                $tong += floatval($m2);
                                $heso++;
                            }
                            if (is_numeric($d15_1)) {
                                $tong += floatval($d15_1);
                                $heso++;
                            }
                            if (is_numeric($d15_2)) {
                                $tong += floatval($d15_2);
                                $heso++;
                            }
                            $tong += (floatval($gk) * 2);
                            $heso += 2;
                            $tong += (floatval($ck) * 3);
                            $heso += 3;
                            if ($heso > 0) $dtb = round($tong / $heso, 1);
                        }

                        $this->model->saveDiem($hs_id, $mid, $nid, $hk, $m1, $m2, $d15_1, $d15_2, $gk, $ck, $dtb);
                        $count++;
                    }
                }
                fclose($handle);
                echo "<script>alert('Đã import thành công $count học sinh!'); window.location.href='index.php?act=nhapdiem&lop_id=$lid&mon_id=$mid&hk=$hk';</script>";
            } else {
                echo "<script>alert('Lỗi file!'); window.history.back();</script>";
            }
        }
        // 2. TÌM THÔNG TIN GIÁO VIÊN TỪ TÀI KHOẢN
        // (Nếu là Admin đăng nhập thì sẽ không tìm thấy, vì Admin không có trong bảng giao_vien)
        $gv = $this->modelGV->getByUserId($userId);

        if (!$gv) {
            // Trường hợp này xảy ra khi:
            // 1. Bạn đăng nhập bằng Admin (Role 1) -> Admin không phải GV nên không có lớp.
            // 2. Tài khoản này là GV nhưng chưa được tạo hồ sơ trong bảng giao_vien.
            echo "<div class='alert alert-danger container mt-5'>
                    <h3>Lỗi xác thực giáo viên</h3>
                    <p>Tài khoản này (ID: $userId) không liên kết với hồ sơ Giáo viên nào.</p>
                    <p>Nếu bạn là Admin, vui lòng tạo một tài khoản Giáo viên khác để test chức năng nhập điểm.</p>
                    <a href='index.php?act=trangchu' class='btn btn-primary'>Quay về trang chủ</a>
                   </div>";
            return;
        }

        $currentGVId = $gv['id'];

        // 3. LOGIC CHÍNH (Giữ nguyên như cũ)
        $namHocHienTai = $this->modelNamHoc->getNamHocHienTai();

        $selectedLop = isset($_GET['lop_id']) ? $_GET['lop_id'] : null;
        $selectedMon = isset($_GET['mon_id']) ? $_GET['mon_id'] : null;
        $selectedHK  = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';

        // --- XỬ LÝ LƯU ĐIỂM ---
        if (isset($_POST['btnLuuDiem'])) {
            $nam_hoc_post = $_POST['nam_hoc_id'];
            $lop_post = $_POST['lop_id'];
            $mon_post = $_POST['mon_id'];
            $hk_post = $_POST['hoc_ky'];

            $diemData = $_POST['diem'];

            foreach ($diemData as $hs_id => $row) {
                // Lấy 6 cột điểm từ form
                $m1 = $row['mieng1'];
                $m2 = $row['mieng2'];
                $d15_1 = $row['15p1'];
                $d15_2 = $row['15p2'];
                $d_giuaky = $row['1tiet'];
                $d_cuoiky = $row['thi'];

                // 1. VALIDATE SỐ ÂM & LỚN HƠN 10 (Server-side)
                $allScores = [$m1, $m2, $d15_1, $d15_2, $d_giuaky, $d_cuoiky];
                foreach ($allScores as $sc) {
                    if ($sc !== "") {
                        if (floatval($sc) < 0 || floatval($sc) > 10) {
                            echo "<script>alert('Lỗi: Điểm số phải từ 0 đến 10. Vui lòng kiểm tra lại!'); window.history.back();</script>";
                            return; // Dừng ngay lập tức
                        }
                    }
                }

                // 2. TÍNH ĐIỂM TRUNG BÌNH MÔN (TBM)
                $dtb = "";
                // Chỉ tính khi ĐÃ CÓ GK và CK
                if ($d_giuaky !== "" && $d_cuoiky !== "") {
                    $tong_diem = 0;
                    $tong_he_so = 0;

                    // Nhóm HS 1 (4 cột)
                    if ($m1 !== "") {
                        $tong_diem += floatval($m1);
                        $tong_he_so++;
                    }
                    if ($m2 !== "") {
                        $tong_diem += floatval($m2);
                        $tong_he_so++;
                    }
                    if ($d15_1 !== "") {
                        $tong_diem += floatval($d15_1);
                        $tong_he_so++;
                    }
                    if ($d15_2 !== "") {
                        $tong_diem += floatval($d15_2);
                        $tong_he_so++;
                    }

                    // Nhóm HS 2
                    $tong_diem += (floatval($d_giuaky) * 2);
                    $tong_he_so += 2;

                    // Nhóm HS 3
                    $tong_diem += (floatval($d_cuoiky) * 3);
                    $tong_he_so += 3;

                    if ($tong_he_so > 0) {
                        $dtb = round($tong_diem / $tong_he_so, 1);
                    }
                }

                // 3. LƯU
                $this->model->saveDiem($hs_id, $mon_post, $nam_hoc_post, $hk_post, $m1, $m2, $d15_1, $d15_2, $d_giuaky, $d_cuoiky, $dtb);
            }

            echo "<script>alert('Lưu thành công!'); window.location.href='index.php?act=nhapdiem&lop_id=$lop_post&mon_id=$mon_post&hk=$hk_post';</script>";
        }

        // --- LẤY DỮ LIỆU HIỂN THỊ ---
        $dsLopPhuTrach = $this->model->getLopPhuTrach($currentGVId, $namHocHienTai);

        $dsHocSinh = null;
        $diemHK1 = [];

        if ($selectedLop && $selectedMon) {
            $dsHocSinh = $this->model->getBangDiem($selectedLop, $selectedMon, $namHocHienTai, $selectedHK);
            if ($selectedHK == 'HK2') {
                $diemHK1 = $this->model->getDiemTongKetHK1($selectedLop, $selectedMon, $namHocHienTai);
            }
        }
        include("app/Views/nhapdiem.php");
    }

    // ===> THÊM HÀM NÀY VÀO CUỐI CLASS cDiem <===
    public function xemBangDiemChiTiet()
    {
        // 1. Check quyền
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header("Location: index.php");
            exit();
        }

        $lopId = isset($_GET['lop_id']) ? $_GET['lop_id'] : null;
        $monId = isset($_GET['mon_id']) ? $_GET['mon_id'] : null;
        $tenLop = isset($_GET['ten_lop']) ? $_GET['ten_lop'] : 'Lop';
        $hk = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';

        if (!$lopId || !$monId) {
            echo "<script>alert('Thiếu thông tin lớp hoặc môn!'); window.history.back();</script>";
            return;
        }

        $namHienTai = $this->modelNamHoc->getNamHocHienTai();

        // 2. Tái sử dụng hàm getBangDiem có sẵn trong mDiem
        $dsDiem = $this->model->getBangDiem($lopId, $monId, $namHienTai, $hk);

        // 3. Xử lý Xuất Excel (Copy logic từ hàm hienThiGiaoDien qua nhưng tinh chỉnh gọn hơn)
        if (isset($_POST['btnExportOnly'])) {
            // Xóa buffer để file không bị lỗi HTML
            if (ob_get_level()) ob_end_clean();

            $filename = "Bang_Diem_" . vn_to_str($tenLop) . "_" . $hk . "_" . date('Ymd') . ".csv";

            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);

            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

            // Header cột hiển thị đẹp
            fputcsv($output, ['STT', 'Mã HS', 'Họ và Tên', 'Miệng 1', 'Miệng 2', '15 Phút 1', '15 Phút 2', 'Giữa kỳ', 'Cuối kỳ', 'ĐTB Môn']);

            if ($dsDiem && $dsDiem->num_rows > 0) {
                $stt = 1;
                $dsDiem->data_seek(0); // Reset con trỏ dữ liệu
                while ($row = $dsDiem->fetch_assoc()) {
                    fputcsv($output, [
                        $stt++,
                        "\t" . $row['ma_hs'], // Thêm tab để giữ số 0
                        $row['ho_ten'],
                        $row['diem_mieng_1'],
                        $row['diem_mieng_2'],
                        $row['diem_15p_1'],
                        $row['diem_15p_2'],
                        $row['diem_1tiet'],
                        $row['diem_thi'],
                        $row['diem_tb']
                    ]);
                }
            }
            fclose($output);
            exit();
        }

        // 4. Gọi View hiển thị
        include("app/Views/xembangdiem_gv.php");
    }
}
