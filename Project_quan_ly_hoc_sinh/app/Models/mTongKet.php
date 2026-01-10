<?php
require_once("config/ketnoi.php");

class mTongKet
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy danh sách lớp mà giáo viên này CHỦ NHIỆM
    public function getLopChuNhiem($gvId, $namHocId)
    {
        $sql = "SELECT * FROM lop WHERE gvcn_id = $gvId AND nam_hoc_id = $namHocId";
        return $this->conn->query($sql);
    }

    // 2. Lấy danh sách Môn học (để vẽ tiêu đề cột)
    public function getAllMonHoc()
    {
        return $this->conn->query("SELECT * FROM mon_hoc ORDER BY id ASC");
    }

    // 3. Lấy Bảng điểm tổng hợp (Kỹ thuật Pivot mảng trong PHP)
    // Thay vì Pivot bằng SQL phức tạp, ta lấy hết điểm về rồi xử lý bằng PHP cho linh hoạt
    public function getBangDiemTongHop($lopId, $namHocId, $hocKy)
    {
        // Lấy danh sách học sinh
        $sqlHS = "SELECT id, ma_hs, ho_ten FROM hoc_sinh WHERE lop_id = $lopId AND trang_thai='danghoc' ORDER BY ho_ten ASC";
        $resHS = $this->conn->query($sqlHS);

        $data = []; // Mảng chứa kết quả cuối cùng

        if ($resHS) {
            while ($hs = $resHS->fetch_assoc()) {
                $hsId = $hs['id'];

                // Lấy thông tin Hạnh kiểm, Học lực đã lưu (nếu có)
                $sqlTK = "SELECT * FROM tong_ket WHERE hoc_sinh_id = $hsId AND nam_hoc_id = $namHocId AND hoc_ky = '$hocKy'";
                $tk = $this->conn->query($sqlTK)->fetch_assoc();

                // Cấu trúc dữ liệu cho 1 học sinh
                $studentData = [
                    'info' => $hs,      // Thông tin cá nhân
                    'diem' => [],       // Điểm các môn (Key là ID môn)
                    'tong_ket' => $tk   // Hạnh kiểm, Học lực...
                ];

                // Lấy điểm tất cả các môn của HS này trong kỳ này
                $sqlDiem = "SELECT mon_hoc_id, diem_tb FROM diem 
                            WHERE hoc_sinh_id = $hsId AND nam_hoc_id = $namHocId AND hoc_ky = '$hocKy'";
                $resDiem = $this->conn->query($sqlDiem);

                if ($resDiem) {
                    while ($d = $resDiem->fetch_assoc()) {
                        $studentData['diem'][$d['mon_hoc_id']] = $d['diem_tb'];
                    }
                }

                $data[] = $studentData;
            }
        }
        return $data;
    }

    // 4. Lưu Hạnh Kiểm
    public function saveHanhKiem($hsId, $namId, $hk, $hanhKiem, $nhanXet)
    {
        $nhanXet = mysqli_real_escape_string($this->conn, $nhanXet);

        $sql = "INSERT INTO tong_ket (hoc_sinh_id, nam_hoc_id, hoc_ky, hanh_kiem, nhan_xet) 
                VALUES ($hsId, $namId, '$hk', '$hanhKiem', '$nhanXet')
                ON DUPLICATE KEY UPDATE 
                    hanh_kiem = VALUES(hanh_kiem),
                    nhan_xet = VALUES(nhan_xet)";
        return $this->conn->query($sql);
    }
    // HÀM MỚI: Lấy thông tin tổng kết của HK1 (để tính cho HK2)
    // Trả về mảng: [hoc_sinh_id => dtb_tat_ca_mon]
    public function getTongKetHK1($lopId, $namHocId)
    {
        $sql = "SELECT hoc_sinh_id, dtb_tat_ca_mon 
                FROM tong_ket 
                WHERE nam_hoc_id = $namHocId AND hoc_ky = 'HK1'
                AND hoc_sinh_id IN (SELECT id FROM hoc_sinh WHERE lop_id = $lopId)";

        $result = $this->conn->query($sql);
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[$row['hoc_sinh_id']] = $row['dtb_tat_ca_mon'];
            }
        }
        return $data;
    }
    // HÀM: Lưu kết quả xếp loại (ĐTB, Học Lực, Danh Hiệu)
    public function saveXepLoai($hsId, $namId, $hk, $dtb, $hocLuc, $danhHieu)
    {
        $sql = "INSERT INTO tong_ket (hoc_sinh_id, nam_hoc_id, hoc_ky, dtb_tat_ca_mon, hoc_luc, danh_hieu) 
                VALUES ($hsId, $namId, '$hk', $dtb, '$hocLuc', '$danhHieu')
                ON DUPLICATE KEY UPDATE 
                    dtb_tat_ca_mon = VALUES(dtb_tat_ca_mon),
                    hoc_luc = VALUES(hoc_luc),
                    danh_hieu = VALUES(danh_hieu)";
        return $this->conn->query($sql);
    }
}
