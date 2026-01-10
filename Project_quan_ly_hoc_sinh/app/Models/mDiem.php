<?php
require_once("config/ketnoi.php");

class mDiem
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy danh sách Lớp & Môn mà Giáo viên này được phân công
    public function getLopPhuTrach($giaoVienId, $namHocId)
    {
        $sql = "SELECT pc.*, l.ten_lop, m.ten_mon 
                FROM phan_cong pc
                JOIN lop l ON pc.lop_id = l.id
                JOIN mon_hoc m ON pc.mon_hoc_id = m.id
                WHERE pc.giao_vien_id = $giaoVienId AND pc.nam_hoc_id = $namHocId
                ORDER BY l.ten_lop ASC";
        return $this->conn->query($sql);
    }

    // 2. Lấy Bảng điểm (CẬP NHẬT LẤY CỘT MỚI)
    public function getBangDiem($lopId, $monId, $namHocId, $hocKy)
    {
        // Lấy diem_mieng_1, diem_mieng_2, diem_15p_1, diem_15p_2
        $sql = "SELECT hs.id as hs_id, hs.ma_hs, hs.ho_ten, 
                       d.diem_mieng_1, d.diem_mieng_2, 
                       d.diem_15p_1, d.diem_15p_2, 
                       d.diem_1tiet, d.diem_thi, d.diem_tb
                FROM hoc_sinh hs
                LEFT JOIN diem d ON hs.id = d.hoc_sinh_id 
                                 AND d.mon_hoc_id = $monId 
                                 AND d.nam_hoc_id = $namHocId 
                                 AND d.hoc_ky = '$hocKy'
                WHERE hs.lop_id = $lopId AND hs.trang_thai = 'danghoc'
                ORDER BY hs.ho_ten ASC";
        return $this->conn->query($sql);
    }

    // 3. Lưu điểm (CẬP NHẬT THAM SỐ)
    public function saveDiem($hsId, $monId, $namId, $hk, $m1, $m2, $d15_1, $d15_2, $d1t, $dthi, $dtb)
    {
        // Xử lý NULL
        $m1 = ($m1 === "") ? "NULL" : $m1;
        $m2 = ($m2 === "") ? "NULL" : $m2;
        $d15_1 = ($d15_1 === "") ? "NULL" : $d15_1;
        $d15_2 = ($d15_2 === "") ? "NULL" : $d15_2;
        $d1t = ($d1t === "") ? "NULL" : $d1t;
        $dthi = ($dthi === "") ? "NULL" : $dthi;
        $dtb = ($dtb === "") ? "NULL" : $dtb;

        $sql = "INSERT INTO diem (hoc_sinh_id, mon_hoc_id, nam_hoc_id, hoc_ky, 
                                  diem_mieng_1, diem_mieng_2, diem_15p_1, diem_15p_2, 
                                  diem_1tiet, diem_thi, diem_tb) 
                VALUES ($hsId, $monId, $namId, '$hk', $m1, $m2, $d15_1, $d15_2, $d1t, $dthi, $dtb)
                ON DUPLICATE KEY UPDATE 
                    diem_mieng_1 = VALUES(diem_mieng_1),
                    diem_mieng_2 = VALUES(diem_mieng_2),
                    diem_15p_1 = VALUES(diem_15p_1),
                    diem_15p_2 = VALUES(diem_15p_2),
                    diem_1tiet = VALUES(diem_1tiet),
                    diem_thi = VALUES(diem_thi),
                    diem_tb = VALUES(diem_tb)";

        return $this->conn->query($sql);
    }

    // SỬA LẠI HÀM NÀY: Dùng JOIN để lấy đúng lớp
    public function getDiemTongKetHK1($lopId, $monId, $namHocId)
    {
        // Chúng ta phải JOIN bảng diem với bảng hoc_sinh
        // Để lọc ra những điểm thuộc về các học sinh của lớp ($lopId)
        $sql = "SELECT d.hoc_sinh_id, d.diem_tb 
                FROM diem d
                JOIN hoc_sinh hs ON d.hoc_sinh_id = hs.id
                WHERE hs.lop_id = $lopId 
                  AND d.mon_hoc_id = $monId 
                  AND d.nam_hoc_id = $namHocId 
                  AND d.hoc_ky = 'HK1'";

        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Key là ID học sinh, Value là điểm TB
                $data[$row['hoc_sinh_id']] = $row['diem_tb'];
            }
        }
        return $data;
    }
    // HÀM MỚI: Lấy tên Lớp, Môn, Năm để đặt tên file Excel
    public function getThongTinExport($lopId, $monId, $namHocId)
    {
        $sql = "SELECT l.ten_lop, m.ten_mon, n.ten_nam 
                FROM lop l, mon_hoc m, nam_hoc n 
                WHERE l.id = $lopId AND m.id = $monId AND n.id = $namHocId";
        return $this->conn->query($sql)->fetch_assoc();
    }
}
