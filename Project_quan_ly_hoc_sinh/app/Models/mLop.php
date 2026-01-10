<?php
require_once("config/ketnoi.php");

class mLop
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy danh sách lớp (Kèm thông tin Năm học, Khối, GVCN)
    public function getAll($keyword = null, $namHocId = null, $khoiId = null)
    {
        $sql = "SELECT l.*, nh.ten_nam, k.ten_khoi, gv.ho_ten as ten_gvcn 
                FROM lop l
                JOIN nam_hoc nh ON l.nam_hoc_id = nh.id
                JOIN khoi k ON l.khoi_id = k.id
                LEFT JOIN giao_vien gv ON l.gvcn_id = gv.id
                WHERE 1=1";

        if ($keyword) {
            $keyword = mysqli_real_escape_string($this->conn, $keyword);
            $sql .= " AND l.ten_lop LIKE '%$keyword%'";
        }
        if ($namHocId) {
            $sql .= " AND l.nam_hoc_id = $namHocId";
        }
        if ($khoiId) {
            $sql .= " AND l.khoi_id = $khoiId";
        }

        $sql .= " ORDER BY nh.id DESC, k.id ASC, l.ten_lop ASC";
        return $this->conn->query($sql);
    }

    // 2. Lấy chi tiết 1 lớp
    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM lop WHERE id = $id";
        return $this->conn->query($sql)->fetch_assoc();
    }
    // ===> [MỚI] Hàm kiểm tra trùng tên lớp trong cùng năm học
    public function checkTonTai($tenLop, $namHocId, $excludeId = null)
    {
        $tenLop = mysqli_real_escape_string($this->conn, $tenLop);
        $namHocId = intval($namHocId);

        // Tìm xem có lớp nào tên như vậy trong năm đó không
        $sql = "SELECT id FROM lop WHERE ten_lop = '$tenLop' AND nam_hoc_id = $namHocId";

        // Nếu đang sửa (Update), thì phải trừ chính nó ra (không tính là trùng với chính mình)
        if ($excludeId) {
            $excludeId = intval($excludeId);
            $sql .= " AND id != $excludeId";
        }

        $result = $this->conn->query($sql);
        // Trả về TRUE nếu đã tồn tại, FALSE nếu chưa có
        return ($result && $result->num_rows > 0);
    }
    // 3. Thêm lớp mới
    // Sửa lại INSERT: Bỏ tham số gvcn_id. Mặc định là NULL.
    public function insert($ten_lop, $nam_hoc_id, $khoi_id)
    {
        $ten_lop = mysqli_real_escape_string($this->conn, $ten_lop);
        $sql = "INSERT INTO lop (ten_lop, nam_hoc_id, khoi_id, gvcn_id) 
                VALUES ('$ten_lop', $nam_hoc_id, $khoi_id, NULL)";
        return $this->conn->query($sql);
    }

    // Sửa lại UPDATE: Bỏ tham số gvcn_id (Chỉ sửa thông tin lớp, không sửa GVCN ở đây)
    public function update($id, $ten_lop, $nam_hoc_id, $khoi_id)
    {
        $ten_lop = mysqli_real_escape_string($this->conn, $ten_lop);
        $sql = "UPDATE lop SET ten_lop='$ten_lop', nam_hoc_id=$nam_hoc_id, khoi_id=$khoi_id WHERE id=$id";
        return $this->conn->query($sql);
    }

    // --- HÀM MỚI: Dành riêng cho chức năng Phân công GVCN ---
    public function updateGVCN($lop_id, $gvcn_id)
    {
        $lop_id = intval($lop_id);
        $valGVCN = ($gvcn_id != "") ? intval($gvcn_id) : "NULL";
        $sql = "UPDATE lop SET gvcn_id = $valGVCN WHERE id = $lop_id";
        return $this->conn->query($sql);
    }

    // 5. Xóa lớp
    public function delete($id)
    {
        // Thực tế nên check xem lớp có học sinh chưa mới cho xóa
        // Ở đây ta xóa thẳng, DB sẽ báo lỗi foreign key nếu có HS
        $sql = "DELETE FROM lop WHERE id = $id";
        return $this->conn->query($sql);
    }
    // ===> THÊM HÀM NÀY VÀO: Lấy danh sách lớp theo năm học
    public function getAllLop($namHocId)
    {
        // Nếu $namHocId null hoặc rỗng thì không lấy gì cả để tránh lỗi
        if (empty($namHocId)) return null;

        $sql = "SELECT * FROM lop WHERE nam_hoc_id = $namHocId ORDER BY ten_lop ASC";
        return $this->conn->query($sql);
    }
}
