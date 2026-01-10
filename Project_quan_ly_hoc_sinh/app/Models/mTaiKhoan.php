<?php
require_once("config/ketnoi.php");

class mTaiKhoan
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Kiểm tra mật khẩu cũ
    public function checkMatKhauCu($userId, $passInput)
    {
        // --- QUAN TRỌNG: Mã hóa MD5 trước khi so sánh ---
        $passInput = md5($passInput);

        // Chống SQL Injection
        $passInput = mysqli_real_escape_string($this->conn, $passInput);

        $sql = "SELECT * FROM tai_khoan WHERE id = $userId AND password = '$passInput'";
        $result = $this->conn->query($sql);

        return ($result && $result->num_rows > 0);
    }

    // 2. Cập nhật mật khẩu mới
    public function updateMatKhau($userId, $newPass)
    {
        // --- QUAN TRỌNG: Mã hóa MD5 trước khi lưu ---
        // Nếu không mã hóa chỗ này, lần sau đăng nhập sẽ bị lỗi vì login đang dùng MD5
        $newPass = md5($newPass);

        $newPass = mysqli_real_escape_string($this->conn, $newPass);

        $sql = "UPDATE tai_khoan SET password = '$newPass' WHERE id = $userId";
        return $this->conn->query($sql);
    }
}
