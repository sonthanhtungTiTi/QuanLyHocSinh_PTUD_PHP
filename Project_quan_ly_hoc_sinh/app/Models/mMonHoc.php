<?php
require_once("config/ketnoi.php");

class mMonHoc
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy TẤT CẢ môn (Dùng cho trang Quản lý Admin - thấy cả môn ẩn)
    public function getAll()
    {
        $sql = "SELECT * FROM mon_hoc ORDER BY trang_thai DESC, id ASC";
        
        return $this->conn->query($sql);
    }

    // 2. [MỚI] Chỉ lấy môn ĐANG HOẠT ĐỘNG (Dùng cho Dropdown nhập điểm, phân công...)
    public function getAllActive()
    {
        $sql = "SELECT * FROM mon_hoc WHERE trang_thai = 1 ORDER BY id ASC";
        return $this->conn->query($sql);
    }

    // 3. Lấy theo ID
    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM mon_hoc WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function checkTonTai($tenMon, $excludeId = null)
    {
        $tenMon = mysqli_real_escape_string($this->conn, $tenMon);
        $sql = "SELECT id FROM mon_hoc WHERE ten_mon = '$tenMon'";
        if ($excludeId) {
            $excludeId = intval($excludeId);
            $sql .= " AND id != $excludeId";
        }
        $result = $this->conn->query($sql);
        return ($result && $result->num_rows > 0);
    }

    // 4. Insert (Mặc định trạng thái = 1)
    public function insert($tenMon, $heSo = 1)
    {
        $tenMon = mysqli_real_escape_string($this->conn, $tenMon);
        $sql = "INSERT INTO mon_hoc (ten_mon, he_so, trang_thai) VALUES ('$tenMon', $heSo, 1)";
        return $this->conn->query($sql);
    }

    public function update($id, $tenMon, $heSo = 1)
    {
        $tenMon = mysqli_real_escape_string($this->conn, $tenMon);
        $id = intval($id);
        $sql = "UPDATE mon_hoc SET ten_mon = '$tenMon', he_so = $heSo WHERE id = $id";
        return $this->conn->query($sql);
    }

    // 5. [THAY ĐỔI QUAN TRỌNG] Thay vì Delete, ta dùng Toggle Status
    // Nếu đang 1 -> thành 0. Nếu đang 0 -> thành 1.
    public function toggleStatus($id, $currentStatus)
    {
        $id = intval($id);
        $newStatus = ($currentStatus == 1) ? 0 : 1;
        $sql = "UPDATE mon_hoc SET trang_thai = $newStatus WHERE id = $id";
        return $this->conn->query($sql);
    }
}
