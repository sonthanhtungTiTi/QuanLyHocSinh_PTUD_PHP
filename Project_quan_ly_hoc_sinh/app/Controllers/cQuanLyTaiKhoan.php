<?php
require_once("app/Models/mQuanLyTaiKhoan.php");

class cQuanLyTaiKhoan {
    private $model;

    public function __construct() {
        $this->model = new mQuanLyTaiKhoan();
    }

    public function hienThiDanhSach() {
        // Chỉ Admin mới được vào
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
             header("Location: index.php"); exit();
        }

        // 1. Tạo tài khoản Admin/BGH
        if (isset($_POST['btnThemTK'])) {
            $user = trim($_POST['username']);
            $pass = trim($_POST['password']);
            $role = $_POST['role_id'];
            if ($this->model->createAccount($user, $pass, $role)) {
                echo "<script>alert('Tạo tài khoản thành công!'); window.location.href='index.php?act=quanlytaikhoan';</script>";
            } else {
                echo "<script>alert('Lỗi: Tên đăng nhập đã tồn tại!');</script>";
            }
        }

        // 2. Reset mật khẩu
        if (isset($_POST['btnResetPass'])) {
            $id = $_POST['tk_id'];
            $newPass = $_POST['new_pass'];
            if(empty($newPass)) $newPass = '123456';
            
            if ($this->model->resetPassword($id, $newPass)) {
                echo "<script>alert('Đã reset mật khẩu thành công!'); window.location.href='index.php?act=quanlytaikhoan';</script>";
            }
        }

        // 3. Khóa/Mở khóa
        if (isset($_POST['btnToggle'])) {
            $id = $_POST['tk_id'];
            $stt = $_POST['current_status'];
            if ($id == $_SESSION['user_id']) {
                echo "<script>alert('Không thể tự khóa chính mình!');</script>";
            } else {
                $this->model->toggleStatus($id, $stt);
                echo "<script>window.location.href='index.php?act=quanlytaikhoan';</script>";
            }
        }

        $keyword = isset($_GET['search']) ? $_GET['search'] : null;
        $roleFilter = isset($_GET['role']) ? $_GET['role'] : null;
        $dsTK = $this->model->getAll($keyword, $roleFilter);
        
        include("app/Views/quanlytaikhoan.php");
    }
}
?>