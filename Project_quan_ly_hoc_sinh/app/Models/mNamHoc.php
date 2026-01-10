<?php
// app/Models/mNamHoc.php
require_once("config/ketnoi.php");

class mNamHoc
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy danh sách năm học
    public function getAllNamHoc()
    {
        $sql = "SELECT * FROM nam_hoc ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    // 2. Thêm năm học mới
    public function themNamHoc($tenNam)
    {
        $tenNam = mysqli_real_escape_string($this->conn, $tenNam);
        // Mặc định thêm mới thì trạng thái là 0 (Chưa kích hoạt)
        $sql = "INSERT INTO nam_hoc (ten_nam, trang_thai) VALUES ('$tenNam', 0)";
        return $this->conn->query($sql);
    }

    // 3. Xóa năm học
    public function xoaNamHoc($id)
    {
        $sql = "DELETE FROM nam_hoc WHERE id = $id";
        return $this->conn->query($sql);
    }

    // 4. Kích hoạt năm học (Quan trọng)
    public function setNamHocHienTai($id)
    {
        // Bước 1: Reset toàn bộ về 0
        $sqlReset = "UPDATE nam_hoc SET trang_thai = 0";
        $this->conn->query($sqlReset);

        // Bước 2: Set năm được chọn lên 1
        $sqlActive = "UPDATE nam_hoc SET trang_thai = 1 WHERE id = $id";
        return $this->conn->query($sqlActive);
    }
    // HÀM MỚI: Lấy ID của năm học đang kích hoạt (trang_thai = 1)
    public function getNamHocHienTai()
    {
        $sql = "SELECT id FROM nam_hoc WHERE trang_thai = 1 LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
        // Nếu không có năm nào active, lấy năm mới nhất
        $sql2 = "SELECT id FROM nam_hoc ORDER BY id DESC LIMIT 1";
        $result2 = $this->conn->query($sql2);
        return ($result2 && $result2->num_rows > 0) ? $result2->fetch_assoc()['id'] : null;
    }
    // ===> THÊM HÀM NÀY VÀO MODEL <===
    // Kiểm tra xem tên năm học đã tồn tại chưa
    public function checkNamHocTonTai($tenNam)
    {
        $tenNam = mysqli_real_escape_string($this->conn, $tenNam);
        $sql = "SELECT id FROM nam_hoc WHERE ten_nam = '$tenNam'";
        $result = $this->conn->query($sql);

        // Nếu số dòng tìm thấy > 0 nghĩa là đã tồn tại
        if ($result && $result->num_rows > 0) {
            return true;
        }
        return false;
    }
}
