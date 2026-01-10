<?php
// app/Models/mDangNhap.php
require_once("config/ketnoi.php");

class mDangNhap
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    public function kiemTraDangNhap($username, $password)
    {
        // Sử dụng MD5 để mã hóa mật khẩu người dùng nhập vào rồi so sánh với DB
        $passHash = md5($password);

        // Join bảng vai_tro để lấy luôn tên vai trò (Admin/GiaoVien...)
        $sql = "SELECT tk.*, vt.ten_vai_tro 
                FROM tai_khoan tk 
                JOIN vai_tro vt ON tk.vai_tro_id = vt.id 
                WHERE tk.username = '$username' AND tk.password = '$passHash' AND tk.trang_thai = 1 
                LIMIT 1";

        $result = $this->conn->query($sql);
        return $result;
    }

    // Hàm đóng kết nối nếu cần (tùy chọn)
    public function dongKetNoi()
    {
        $this->conn->close();
    }

    // ===> THÊM 2 HÀM NÀY VÀO CUỐI CLASS mDangNhap <===
    // ===> THÊM 2 HÀM MỚI NÀY VÀO DƯỚI <===

    // Lấy tên Giáo viên theo ID tài khoản
    public function getHoTenGiaoVien($taiKhoanId)
    {
        $sql = "SELECT ho_ten FROM giao_vien WHERE tai_khoan_id = $taiKhoanId";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return $row['ho_ten'];
        }
        return null;
    }

    // Lấy tên Học sinh theo ID tài khoản
    public function getHoTenHocSinh($taiKhoanId)
    {
        $sql = "SELECT ho_ten FROM hoc_sinh WHERE tai_khoan_id = $taiKhoanId";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return $row['ho_ten'];
        }
        return null;
    }
}
