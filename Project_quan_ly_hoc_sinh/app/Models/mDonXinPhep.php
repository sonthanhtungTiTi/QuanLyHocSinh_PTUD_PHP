<?php
require_once("config/ketnoi.php");

class mDonXinPhep
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // [CẬP NHẬT] 1. [HỌC SINH] Tạo đơn xin phép CÓ ẢNH MINH CHỨNG
    // Thêm tham số $hinhAnhPath (Đường dẫn file ảnh đã upload thành công)
    public function taoDon($hsId, $tuNgay, $denNgay, $lyDo, $hinhAnhPath)
    {
        $lyDo = mysqli_real_escape_string($this->conn, $lyDo);
        $hinhAnhPath = mysqli_real_escape_string($this->conn, $hinhAnhPath); // Chống SQL Injection cho đường dẫn

        $sql = "INSERT INTO don_xin_phep (hoc_sinh_id, tu_ngay, den_ngay, ly_do, minh_chung, trang_thai) 
                VALUES ($hsId, '$tuNgay', '$denNgay', '$lyDo', '$hinhAnhPath', 0)";
        return $this->conn->query($sql);
    }

    // 2. [HỌC SINH] Lấy lịch sử đơn đã gửi
    public function getLichSuDon($hsId)
    {
        $sql = "SELECT * FROM don_xin_phep WHERE hoc_sinh_id = $hsId ORDER BY ngay_gui DESC";
        return $this->conn->query($sql);
    }

    // 3. [GVCN] Lấy danh sách đơn cần duyệt của lớp mình chủ nhiệm
    public function getDonCanDuyet($gvId)
    {
        // JOIN 3 bảng: don_xin_phep -> hoc_sinh -> lop
        // Logic: Chỉ lấy đơn của HS thuộc lớp mà GV này làm Chủ nhiệm (gvcn_id = $gvId)
        $sql = "SELECT d.*, hs.ma_hs, hs.ho_ten, l.ten_lop
                FROM don_xin_phep d
                JOIN hoc_sinh hs ON d.hoc_sinh_id = hs.id
                JOIN lop l ON hs.lop_id = l.id
                WHERE l.gvcn_id = $gvId
                ORDER BY d.trang_thai ASC, d.ngay_gui DESC";
        // Ưu tiên đơn chờ duyệt (0) lên đầu
        return $this->conn->query($sql);
    }

    // 4. [GVCN] Cập nhật trạng thái đơn
    public function capNhatTrangThai($donId, $trangThai, $phanHoi)
    {
        $phanHoi = mysqli_real_escape_string($this->conn, $phanHoi);
        $sql = "UPDATE don_xin_phep 
                SET trang_thai = $trangThai, gv_phan_hoi = '$phanHoi' 
                WHERE id = $donId";
        return $this->conn->query($sql);
    }
}
