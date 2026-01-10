<?php
require_once("app/Models/mThoiKhoaBieu.php");
require_once("app/Models/mGiaoVien.php"); // Đảm bảo tên file model đúng
require_once("app/Models/mNamHoc.php");

class cGiangDay
{
    private $modelTKB;
    private $modelGV;
    private $modelNam;

    public function __construct()
    {
        $this->modelTKB = new mThoiKhoaBieu();
        $this->modelGV = new mGiaoVien(); // Đảm bảo tên class model đúng
        $this->modelNam = new mNamHoc();
    }

    // 1. CHỨC NĂNG XEM LỊCH DẠY
    public function xemLichDay()
    {
        // Kiểm tra quyền
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header("Location: index.php");
            exit();
        }

        // Lấy thông tin GV
        $gv = $this->modelGV->getGiaoVienByUserId($_SESSION['user_id']);

        if (!$gv) {
            echo "<div class='alert alert-danger m-5'>Lỗi: Không tìm thấy hồ sơ giáo viên. Vui lòng liên hệ Admin.</div>";
            return;
        }

        $namHienTai = $this->modelNam->getNamHocHienTai();
        $selectedHK = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';

        // Lấy lịch dạy
        $lichDayData = $this->modelTKB->getTKB_GiaoVien($gv['id'], $namHienTai, $selectedHK);

        include("app/Views/xemlichday_gv.php");
    }

    // 2. CHỨC NĂNG XEM DANH SÁCH LỚP DẠY (Đây là hàm bạn đang bị thiếu)
    public function xemDanhSachLop()
    {
        // Kiểm tra quyền
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header("Location: index.php");
            exit();
        }

        $gv = $this->modelGV->getGiaoVienByUserId($_SESSION['user_id']);

        if (!$gv) {
            echo "<div class='alert alert-danger m-5'>Lỗi: Không tìm thấy hồ sơ giáo viên.</div>";
            return;
        }

        $namHienTai = $this->modelNam->getNamHocHienTai();

        // Gọi model lấy danh sách lớp
        // LƯU Ý: Phải chắc chắn file mGiaoVien.php có hàm getLopPhanCong nhé
        $dsLop = $this->modelGV->getLopPhanCong($gv['id'], $namHienTai);

        include("app/Views/xemdanhsachlop_gv.php");
    }

    // ===> THÊM HÀM NÀY: Xem lớp chủ nhiệm & Xuất file
    public function xemLopChuNhiem()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header("Location: index.php");
            exit();
        }

        $gv = $this->modelGV->getGiaoVienByUserId($_SESSION['user_id']);
        $namHienTai = $this->modelNam->getNamHocHienTai();

        // Lấy dữ liệu
        $data = $this->modelGV->getHocSinhLopChuNhiem($gv['id'], $namHienTai);

        // --- XỬ LÝ XUẤT FILE CSV ---
        if (isset($_POST['btnExport']) && $data) {
            $this->xuatFileExcel($data['lop']['ten_lop'], $data['hoc_sinh']);
            exit(); // Dừng chương trình sau khi tải file
        }
        // ---------------------------

        include("app/Views/xemlopcn_gv.php");
    }

    // Hàm hỗ trợ Xuất CSV
    // Hàm hỗ trợ Xuất Excel (CSV)
    private function xuatFileExcel($tenLop, $dsHS)
    {
        // ===> [QUAN TRỌNG] Xóa sạch mọi HTML (Header, Menu...) đã được tạo ra trước đó
        if (ob_get_level()) {
            ob_end_clean();
        }
        // =========================================================================

        // 1. Đặt tên file
        $filename = "Danh_sach_lop_" . $this->slugify($tenLop) . "_" . date('Ymd') . ".csv";

        // 2. Cấu hình Header
        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        // 3. Mở output stream
        $output = fopen('php://output', 'w');

        // 4. Thêm BOM để Excel hiển thị tiếng Việt
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // 5. Viết tiêu đề
        fputcsv($output, ['STT', 'Mã Học Sinh', 'Họ và Tên', 'Ngày Sinh', 'Giới Tính', 'Địa Chỉ', 'Ghi Chú']);

        // 6. Viết dữ liệu
        if ($dsHS && $dsHS->num_rows > 0) {
            $stt = 1;
            $dsHS->data_seek(0);

            while ($row = $dsHS->fetch_assoc()) {
                $gioiTinh = (strtolower($row['gioi_tinh']) == 'nam') ? 'Nam' : 'Nữ';
                $ngaySinh = date('d/m/Y', strtotime($row['ngay_sinh']));

                fputcsv($output, [
                    $stt++,
                    $row['ma_hs'],
                    $row['ho_ten'],
                    $ngaySinh,
                    $gioiTinh,
                    $row['dia_chi'],
                    ''
                ]);
            }
        }

        fclose($output);

        // ===> Dừng ngay lập tức để không in thêm HTML Footer
        exit();
    }

    // Hàm phụ để tạo tên file đẹp (Tiếng Việt có dấu -> Không dấu)
    private function slugify($str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '_', $str);
        return $str;
    }

    // ===> [MỚI] HÀM XUẤT EXCEL CHO BẢNG ĐIỂM <===
    private function xuatExcelBangDiem($tenLop, $hk, $dsDiem)
    {
        if (ob_get_level()) ob_end_clean(); // Xóa sạch HTML thừa

        $filename = "Bang_Diem_" . $this->slugify($tenLop) . "_" . $hk . "_" . date('Ymd') . ".csv";

        header('Content-Encoding: UTF-8');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8

        // Header cột
        fputcsv($output, ['STT', 'Mã HS', 'Họ và Tên', 'Miệng 1', 'Miệng 2', '15 Phút 1', '15 Phút 2', '1 Tiết', 'Thi CK', 'ĐTB Môn']);

        if ($dsDiem && $dsDiem->num_rows > 0) {
            $stt = 1;
            $dsDiem->data_seek(0);
            while ($row = $dsDiem->fetch_assoc()) {
                fputcsv($output, [
                    $stt++,
                    "\t" . $row['ma_hs'], // Thêm \t để giữ số 0
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
}
