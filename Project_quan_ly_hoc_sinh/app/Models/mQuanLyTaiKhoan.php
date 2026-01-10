<?php
require_once("config/ketnoi.php");

class mQuanLyTaiKhoan {
    private $conn;

    public function __construct() {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    public function getAll($keyword = null, $roleId = null) {
        $sql = "SELECT tk.*, vt.ten_vai_tro,
                       CASE 
                           WHEN tk.vai_tro_id = 3 THEN gv.ho_ten 
                           WHEN tk.vai_tro_id = 4 THEN hs.ho_ten 
                           ELSE 'Quản trị viên' 
                       END as ho_ten_that
                FROM tai_khoan tk
                JOIN vai_tro vt ON tk.vai_tro_id = vt.id
                LEFT JOIN giao_vien gv ON tk.id = gv.tai_khoan_id
                LEFT JOIN hoc_sinh hs ON tk.id = hs.tai_khoan_id
                WHERE 1=1";

        if ($keyword) $sql .= " AND tk.username LIKE '%$keyword%'";
        if ($roleId) $sql .= " AND tk.vai_tro_id = $roleId";

        $sql .= " ORDER BY tk.id DESC";
        return $this->conn->query($sql);
    }

    public function createAccount($username, $password, $roleId) {
        $check = $this->conn->query("SELECT id FROM tai_khoan WHERE username = '$username'");
        if ($check && $check->num_rows > 0) return false;

        // DÙNG MD5 ĐỂ ĐỒNG BỘ VỚI CODE CỦA BẠN
        $hashPass = md5($password); 
        
        $sql = "INSERT INTO tai_khoan (username, password, vai_tro_id, trang_thai) 
                VALUES ('$username', '$hashPass', $roleId, 1)";
        return $this->conn->query($sql);
    }

    public function resetPassword($id, $newPass) {
        // DÙNG MD5 ĐỂ ĐỒNG BỘ
        $hashPass = md5($newPass);
        $sql = "UPDATE tai_khoan SET password = '$hashPass' WHERE id = $id";
        return $this->conn->query($sql);
    }

    public function toggleStatus($id, $currentStatus) {
        $newStatus = ($currentStatus == 1) ? 0 : 1;
        $sql = "UPDATE tai_khoan SET trang_thai = $newStatus WHERE id = $id";
        return $this->conn->query($sql);
    }
}
?>