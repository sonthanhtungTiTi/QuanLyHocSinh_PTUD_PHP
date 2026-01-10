<?php
require_once("config/ketnoi.php");

class mThongKe
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // Lấy danh sách Năm học (để đổ vào dropdown)
    public function getAllNamHoc()
    {
        return $this->conn->query("SELECT * FROM nam_hoc ORDER BY id DESC");
    }

    // Lấy danh sách Lớp (để đổ vào dropdown)
    public function getAllLop($namHocId)
    {
        return $this->conn->query("SELECT * FROM lop WHERE nam_hoc_id = $namHocId");
    }

    /// 1. Đếm tổng quan (Có xử lý trường hợp chưa có năm học)
    public function getTongSoLuong($namHocId)
    {
        $data = [];

        // Đếm Học sinh (Chỉ đếm HS đang học, không phụ thuộc năm)
        $sqlHS = "SELECT COUNT(*) as t FROM hoc_sinh WHERE trang_thai = 'danghoc'";
        $resHS = $this->conn->query($sqlHS);
        $data['hs'] = $resHS ? $resHS->fetch_assoc()['t'] : 0;

        // Đếm Giáo viên (Chỉ đếm GV đang hoạt động)
        $sqlGV = "SELECT COUNT(*) as t FROM giao_vien WHERE trang_thai = 'hoatdong'";
        $resGV = $this->conn->query($sqlGV);
        $data['gv'] = $resGV ? $resGV->fetch_assoc()['t'] : 0;

        // Đếm Lớp (Phụ thuộc vào Năm học ID)
        // [FIX LỖI] Nếu $namHocId bị NULL (do chưa tạo năm), gán số lượng lớp = 0 luôn
        if ($namHocId != null && $namHocId != '') {
            $namHocId = intval($namHocId); // Ép kiểu số cho an toàn
            $sqlLop = "SELECT COUNT(*) as t FROM lop WHERE nam_hoc_id = $namHocId";
            $resLop = $this->conn->query($sqlLop);
            $data['lop'] = $resLop ? $resLop->fetch_assoc()['t'] : 0;
        } else {
            $data['lop'] = 0;
        }

        return $data;
    }

    // 2. Thống kê Học Lực (Có lọc theo Lớp)
    public function getThongKeHocLuc($namHocId, $hocKy, $lopId = null)
    {
        $sql = "SELECT tk.hoc_luc, COUNT(*) as so_luong 
                FROM tong_ket tk
                JOIN hoc_sinh hs ON tk.hoc_sinh_id = hs.id
                WHERE tk.nam_hoc_id = $namHocId AND tk.hoc_ky = '$hocKy'";

        // Nếu có chọn lớp cụ thể thì thêm điều kiện AND
        if ($lopId != null && $lopId != 'all') {
            $sql .= " AND hs.lop_id = $lopId";
        }

        $sql .= " GROUP BY tk.hoc_luc";
        return $this->conn->query($sql);
    }

    // 3. Thống kê Hạnh Kiểm (Có lọc theo Lớp)
    public function getThongKeHanhKiem($namHocId, $hocKy, $lopId = null)
    {
        $sql = "SELECT tk.hanh_kiem, COUNT(*) as so_luong 
                FROM tong_ket tk
                JOIN hoc_sinh hs ON tk.hoc_sinh_id = hs.id
                WHERE tk.nam_hoc_id = $namHocId AND tk.hoc_ky = '$hocKy'";

        if ($lopId != null && $lopId != 'all') {
            $sql .= " AND hs.lop_id = $lopId";
        }

        $sql .= " GROUP BY tk.hanh_kiem";
        return $this->conn->query($sql);
    }
}
