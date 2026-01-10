<?php
require_once("config/ketnoi.php");

class mThoiKhoaBieu
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy dữ liệu TKB để hiển thị
    // [CẬP NHẬT] Lấy dữ liệu TKB kèm Tên Giáo Viên
    // [CẬP NHẬT] Lấy dữ liệu TKB kèm Tên Giáo Viên VÀ Tên Môn Học
    // [CẬP NHẬT LẠI] Hàm getTKB - Lấy đầy đủ ID và Tên
    public function getTKB($lopId, $namId, $hk)
    {
        // Câu SQL này đã đúng, lấy đủ thông tin
        $sql = "SELECT tkb.*, gv.ho_ten as ten_gv, mh.ten_mon
                FROM thoi_khoa_bieu tkb
                LEFT JOIN giao_vien gv ON tkb.giao_vien_id = gv.id
                LEFT JOIN mon_hoc mh ON tkb.mon_hoc_id = mh.id
                WHERE tkb.lop_id = $lopId AND tkb.nam_hoc_id = $namId AND tkb.hoc_ky = '$hk'";

        $result = $this->conn->query($sql);

        $tkbData = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // [SỬA TẠI ĐÂY] Thêm dòng 'mon_id' vào mảng
                $tkbData[$row['thu']][$row['tiet']] = [
                    'mon_id'  => $row['mon_hoc_id'], // <--- QUAN TRỌNG: View cần cái này để selected dropdown
                    'ten_mon' => $row['ten_mon'],    // Dùng để hiển thị tên
                    'ten_gv'  => $row['ten_gv']      // Dùng để hiển thị GV
                ];
            }
        }
        return $tkbData;
    }
    // 2. Tra cứu GV từ Phân công
    public function getGiaoVienPhanCong($lopId, $monId, $namId)
    {
        $sql = "SELECT giao_vien_id FROM phan_cong 
                WHERE lop_id = $lopId AND mon_hoc_id = $monId AND nam_hoc_id = $namId";
        $res = $this->conn->query($sql);
        if ($res && $row = $res->fetch_assoc()) return $row['giao_vien_id'];
        return null;
    }

    // 3. Kiểm tra trùng lịch (Có loại trừ lớp hiện tại)
    public function checkTrungLich($namId, $hk, $thu, $tiet, $gvId, $lopIdDangXep)
    {
        $sql = "SELECT l.ten_lop 
                FROM thoi_khoa_bieu t
                JOIN lop l ON t.lop_id = l.id
                WHERE t.nam_hoc_id = $namId AND t.hoc_ky = '$hk'
                AND t.thu = $thu AND t.tiet = $tiet 
                AND t.giao_vien_id = $gvId
                AND t.lop_id != $lopIdDangXep";

        $res = $this->conn->query($sql);
        if ($res && $row = $res->fetch_assoc()) return $row['ten_lop'];
        return false;
    }

    // 4. Lưu Tiết học
    public function saveTietHoc($namId, $hk, $lopId, $thu, $tiet, $monId, $gvId)
    {
        if ($monId == '') {
            $monId = 'NULL';
            $gvId = 'NULL';
        }

        $sql = "INSERT INTO thoi_khoa_bieu (nam_hoc_id, hoc_ky, lop_id, thu, tiet, mon_hoc_id, giao_vien_id) 
                VALUES ($namId, '$hk', $lopId, $thu, $tiet, $monId, $gvId)
                ON DUPLICATE KEY UPDATE 
                    mon_hoc_id = VALUES(mon_hoc_id),
                    giao_vien_id = VALUES(giao_vien_id)";
        return $this->conn->query($sql);
    }

    // 5. Xóa TKB cũ (Cho Auto Schedule)
    public function xoaTKBHienTai($namId, $hk)
    {
        $sql = "DELETE FROM thoi_khoa_bieu WHERE nam_hoc_id = $namId AND hoc_ky = '$hk'";
        return $this->conn->query($sql);
    }

    // 6. Lấy danh sách phân công (Cho Auto Schedule)
    public function layDanhSachPhanCong($namId)
    {
        $sql = "SELECT pc.*, mh.so_tiet 
                FROM phan_cong pc
                JOIN mon_hoc mh ON pc.mon_hoc_id = mh.id
                WHERE pc.nam_hoc_id = $namId
                ORDER BY mh.so_tiet DESC";
        return $this->conn->query($sql);
    }

    // 7. Check Slot Hợp lệ (Cho Auto Schedule)
    public function checkSlotHopLe($namId, $hk, $lopId, $gvId, $thu, $tiet)
    {
        // Lớp bận?
        $r1 = $this->conn->query("SELECT id FROM thoi_khoa_bieu WHERE nam_hoc_id=$namId AND hoc_ky='$hk' AND lop_id=$lopId AND thu=$thu AND tiet=$tiet");
        if ($r1 && $r1->num_rows > 0) return false;

        // GV bận?
        $r2 = $this->conn->query("SELECT id FROM thoi_khoa_bieu WHERE nam_hoc_id=$namId AND hoc_ky='$hk' AND giao_vien_id=$gvId AND thu=$thu AND tiet=$tiet");
        if ($r2 && $r2->num_rows > 0) return false;

        return true;
    }

    // ===> ĐÂY LÀ HÀM BẠN ĐANG THIẾU <===
    public function getTenMonHoc($monId)
    {
        if (!$monId) return "";
        $sql = "SELECT ten_mon FROM mon_hoc WHERE id = $monId";
        $res = $this->conn->query($sql);
        if ($res && $r = $res->fetch_assoc()) {
            return $r['ten_mon'];
        }
        return "";
    }
    // ===> THÊM HÀM NÀY: Lấy lịch dạy của Giáo viên
    // Trả về: $data[Thứ][Tiết] = "Tên Lớp (Môn)"
    public function getTKB_GiaoVien($gvId, $namId, $hk)
    {
        // Lấy lịch dựa trên gvId
        $sql = "SELECT tkb.*, l.ten_lop 
                FROM thoi_khoa_bieu tkb
                JOIN lop l ON tkb.lop_id = l.id
                WHERE tkb.giao_vien_id = $gvId 
                AND tkb.nam_hoc_id = $namId 
                AND tkb.hoc_ky = '$hk'";

        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // Key của mảng là [Thứ][Tiết] -> Giá trị là Tên Lớp
                $data[$row['thu']][$row['tiet']] = $row['ten_lop'];
            }
        }
        return $data;
    }
}
