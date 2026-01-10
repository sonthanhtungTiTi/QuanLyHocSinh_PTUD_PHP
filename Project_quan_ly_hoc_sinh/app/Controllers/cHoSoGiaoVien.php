<?php
require_once("app/Models/mGiaoVien.php");

class cHoSoGiaoVien {
    private $model;

    public function __construct() {
        $this->model = new mGiaoVien();
    }

    public function hienThiGiaoDien() {
        // 1. Check quyền: Phải đăng nhập và là GV (Role 3)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
             header("Location: index.php"); exit();
        }

        // 2. Lấy thông tin GV từ Session User ID
        $gv = $this->model->getGiaoVienByUserId($_SESSION['user_id']);
        
        if (!$gv) {
            echo "Lỗi: Không tìm thấy hồ sơ giáo viên."; return;
        }

        // 3. Xử lý cập nhật thông tin
        if (isset($_POST['btnCapNhat'])) {
            $sdt = $_POST['sdt'];
            $email = $_POST['email'];
            $dia_chi = $_POST['dia_chi'];

            if ($this->model->updateProfileCaNhan($gv['id'], $sdt, $email, $dia_chi)) {
                echo "<script>alert('Cập nhật hồ sơ thành công!'); window.location.href='index.php?act=hosogv';</script>";
            } else {
                echo "<script>alert('Lỗi hệ thống!');</script>";
            }
        }

        // Load View
        include("app/Views/hosogv.php");
    }
}
?>