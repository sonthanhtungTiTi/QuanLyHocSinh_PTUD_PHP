<?php
require_once("app/Models/mTaiKhoan.php");

class cTaiKhoan
{
    private $model;

    public function __construct()
    {
        $this->model = new mTaiKhoan();
    }

    public function hienThiGiaoDien()
    {
        // Bắt buộc phải đăng nhập mới được đổi pass
        if (!isset($_SESSION['user_id'])) {
            echo "<script>window.location.href='index.php';</script>";
            return;
        }

        $error = "";
        $success = "";

        if (isset($_POST['btnDoiMatKhau'])) {
            $old_pass = $_POST['old_pass'];
            $new_pass = $_POST['new_pass'];
            $confirm_pass = $_POST['confirm_pass'];
            $userId = $_SESSION['user_id'];

            // 1. Validate cơ bản
            if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
                $error = "Vui lòng nhập đầy đủ thông tin.";
            }
            // 2. Kiểm tra xác nhận mật khẩu
            elseif ($new_pass != $confirm_pass) {
                $error = "Mật khẩu mới và Xác nhận mật khẩu không khớp.";
            }
            // 3. Kiểm tra mật khẩu cũ
            elseif (!$this->model->checkMatKhauCu($userId, $old_pass)) {
                $error = "Mật khẩu cũ không chính xác.";
            } else {
                // 4. Thực hiện đổi
                if ($this->model->updateMatKhau($userId, $new_pass)) {
                    $success = "Đổi mật khẩu thành công!";
                } else {
                    $error = "Lỗi hệ thống, vui lòng thử lại sau.";
                }
            }
        }

        include("app/Views/doimatkhau.php");
    }
}
