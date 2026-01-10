<?php
// File: app/Models/mXemDiem.php
require_once("config/ketnoi.php");

class mXemDiem
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy thông tin học sinh dựa trên ID Tài khoản
    public function getHocSinhByUserId($userId)
    {
        // JOIN bảng lop và giao_vien để lấy tên lớp và tên GVCN
        $sql = "SELECT hs.*, l.ten_lop, gv.ho_ten as ten_gvcn 
                FROM hoc_sinh hs
                JOIN lop l ON hs.lop_id = l.id
                LEFT JOIN giao_vien gv ON l.gvcn_id = gv.id
                WHERE hs.tai_khoan_id = $userId";

        $res = $this->conn->query($sql);
        if ($res) return $res->fetch_assoc();
        return null;
    }

    // 2. Lấy điểm chi tiết của cá nhân học sinh
    public function getBangDiemCaNhan($hsId, $namHocId, $hocKy)
    {
        // LEFT JOIN để lấy tên môn học, kể cả khi chưa có điểm
        $sql = "SELECT m.ten_mon, d.*
                FROM mon_hoc m
                LEFT JOIN diem d ON m.id = d.mon_hoc_id 
                                 AND d.hoc_sinh_id = $hsId 
                                 AND d.nam_hoc_id = $namHocId 
                                 AND d.hoc_ky = '$hocKy'
                ORDER BY m.id ASC";
        return $this->conn->query($sql);
    }

    // 3. Lấy kết quả tổng kết (Hạnh kiểm, Học lực...)
    public function getTongKetCaNhan($hsId, $namHocId, $hocKy)
    {
        $sql = "SELECT * FROM tong_ket 
                WHERE hoc_sinh_id = $hsId AND nam_hoc_id = $namHocId AND hoc_ky = '$hocKy'";

        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
}
