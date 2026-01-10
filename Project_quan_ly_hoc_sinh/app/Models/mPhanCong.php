<?php
require_once("config/ketnoi.php");

class mPhanCong
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy tất cả môn học
    public function getAllMonHoc()
    {
        return $this->conn->query("SELECT * FROM mon_hoc ORDER BY id ASC");
    }

    // 2. Lấy danh sách GV (kèm tên môn để lọc)
    public function getAllGiaoVien()
    {
        $sql = "SELECT gv.*, mh.ten_mon as ten_chuyen_mon 
                FROM giao_vien gv 
                LEFT JOIN mon_hoc mh ON gv.mon_hoc_id = mh.id
                WHERE gv.trang_thai = 'hoatdong' 
                ORDER BY gv.mon_hoc_id ASC, gv.ho_ten ASC";
        return $this->conn->query($sql);
    }

    // 3. Lấy dữ liệu phân công CỦA 1 MÔN cho TẤT CẢ LỚP (Logic mới)
    // Trả về mảng: [id_lop => id_gv]
    public function getPhanCongByMon($namHocId, $monHocId)
    {
        $sql = "SELECT * FROM phan_cong WHERE nam_hoc_id = $namHocId AND mon_hoc_id = $monHocId";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[$row['lop_id']] = $row['giao_vien_id'];
            }
        }
        return $data;
    }

    // 4. Lưu phân công (Giữ nguyên)
    public function savePhanCong($namHocId, $lopId, $monHocId, $giaoVienId)
    {
        $sql = "INSERT INTO phan_cong (nam_hoc_id, lop_id, mon_hoc_id, giao_vien_id) 
                VALUES ($namHocId, $lopId, $monHocId, $giaoVienId)
                ON DUPLICATE KEY UPDATE giao_vien_id = $giaoVienId";
        return $this->conn->query($sql);
    }

    // 5. Xóa phân công (Giữ nguyên)
    public function deletePhanCong($namHocId, $lopId, $monHocId)
    {
        $sql = "DELETE FROM phan_cong WHERE nam_hoc_id = $namHocId AND lop_id = $lopId AND mon_hoc_id = $monHocId";
        return $this->conn->query($sql);
    }
}
