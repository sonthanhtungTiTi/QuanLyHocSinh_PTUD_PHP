<?php
require_once("config/ketnoi.php");

class mHocSinh
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // 1. Lấy danh sách Học sinh (Kết hợp tìm kiếm và lọc lớp)
    // SỬA HÀM getAll: Thêm tham số $namHocId
    // 1. Cập nhật hàm getAll: BỎ 'WHERE hs.trang_thai = danghoc'
    public function getAll($keyword = null, $lopId = null, $namHocId = null)
    {
        // Chỉ lọc điều kiện chung, không lọc trạng thái cứng
        $sql = "SELECT hs.*, l.ten_lop, l.nam_hoc_id 
                FROM hoc_sinh hs 
                LEFT JOIN lop l ON hs.lop_id = l.id 
                WHERE 1=1"; // Giữ 1=1 để dễ nối chuỗi

        // Lọc theo Năm học
        if ($namHocId != null) {
            $sql .= " AND l.nam_hoc_id = " . intval($namHocId);
        }

        // Tìm kiếm
        if ($keyword != null && $keyword != '') {
            $keyword = mysqli_real_escape_string($this->conn, $keyword);
            $sql .= " AND (hs.ho_ten LIKE '%$keyword%' OR hs.ma_hs LIKE '%$keyword%')";
        }

        // Lọc theo Lớp
        if ($lopId != null && $lopId != '') {
            $sql .= " AND hs.lop_id = " . intval($lopId);
        }

        // Sắp xếp: Ưu tiên Đang học lên trước, sau đó tới Tên
        $sql .= " ORDER BY hs.trang_thai ASC, l.ten_lop ASC, hs.ma_hs ASC";

        return $this->conn->query($sql);
    }
    // 2. Lấy thông tin 1 học sinh để sửa
    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM hoc_sinh WHERE id = $id";
        return $this->conn->query($sql)->fetch_assoc();
    }

    // 3. Helper: Lấy ID lớp từ Tên lớp (Dùng cho Import CSV)
    // [CẬP NHẬT] Tìm lớp theo Tên VÀ Năm học
    public function getLopIdByTen($tenLop, $namHocId)
    {
        $tenLop = mysqli_real_escape_string($this->conn, trim($tenLop));
        $namHocId = intval($namHocId); // Đảm bảo là số nguyên

        // Thêm điều kiện AND nam_hoc_id = ...
        $sql = "SELECT id FROM lop 
                WHERE ten_lop LIKE '$tenLop' 
                AND nam_hoc_id = $namHocId 
                LIMIT 1";

        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
        return null; // Không tìm thấy lớp trong năm học này
    }

    // 4. Thêm mới Học sinh (Transaction: Tạo TK -> Tạo Hồ sơ)
    // [CẬP NHẬT] Hàm Insert - Hiển thị lỗi chi tiết để debug
    public function insert($ma_hs, $ho_ten, $ngay_sinh, $gioi_tinh, $dia_chi, $lop_id)
    {
        // 1. Validate dữ liệu đầu vào cơ bản trước khi gọi SQL
        if (empty($lop_id)) {
            echo "<script>alert('Lỗi: Chưa chọn Lớp học (LopID bị rỗng).');</script>";
            return false;
        }

        $ho_ten = mysqli_real_escape_string($this->conn, $ho_ten);
        $dia_chi = mysqli_real_escape_string($this->conn, $dia_chi);
        $ma_hs = mysqli_real_escape_string($this->conn, $ma_hs);

        $this->conn->begin_transaction();
        try {
            // Bước 1: Tạo tài khoản
            $passHash = md5('123456');
            $sqlTK = "INSERT INTO tai_khoan (username, password, vai_tro_id, trang_thai) 
                      VALUES ('$ma_hs', '$passHash', 4, 1)";

            if (!$this->conn->query($sqlTK)) {
                throw new Exception("Lỗi tạo Tài khoản: " . $this->conn->error);
            }
            $tai_khoan_id = $this->conn->insert_id;

            // Bước 2: Tạo hồ sơ học sinh (Đủ 6 trường dữ liệu)
            $sqlHS = "INSERT INTO hoc_sinh (ma_hs, ho_ten, ngay_sinh, gioi_tinh, dia_chi, lop_id, tai_khoan_id, trang_thai) 
                      VALUES ('$ma_hs', '$ho_ten', '$ngay_sinh', '$gioi_tinh', '$dia_chi', $lop_id, $tai_khoan_id, 'danghoc')";

            if (!$this->conn->query($sqlHS)) {
                throw new Exception("Lỗi tạo Học sinh: " . $this->conn->error);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();

            $loi = $e->getMessage();

            // Kiểm tra nếu lỗi chứa từ khóa "Duplicate entry" (Trùng lặp)
            if (strpos($loi, 'Duplicate entry') !== false) {
                echo "<script>alert('LỖI: Mã học sinh (Tài khoản) này đã tồn tại trong hệ thống!');</script>";
            } else {
                // Các lỗi khác thì in chi tiết để debug
                echo "<script>alert('LỖI HỆ THỐNG: " . addslashes($loi) . "');</script>";
            }

            return false;
        }
    }


    // 5. Cập nhật thông tin (ĐÃ SỬA LẠI CHO CHẮC CHẮN)
    // [CẬP NHẬT] Hàm Update (Sửa cả Mã HS và Username)
    public function update($id, $ma_hs, $ho_ten, $ngay_sinh, $gioi_tinh, $dia_chi, $lop_id)
    {
        $id = intval($id);

        // Lấy thông tin cũ để biết tai_khoan_id
        $oldInfo = $this->getById($id);
        $taiKhoanId = $oldInfo['tai_khoan_id'];

        $ho_ten = mysqli_real_escape_string($this->conn, $ho_ten);
        $dia_chi = mysqli_real_escape_string($this->conn, $dia_chi);
        $ma_hs = mysqli_real_escape_string($this->conn, $ma_hs);

        $this->conn->begin_transaction();
        try {
            // 1. Update bảng Học Sinh
            $sqlHS = "UPDATE hoc_sinh 
                      SET ma_hs='$ma_hs', ho_ten='$ho_ten', ngay_sinh='$ngay_sinh', 
                          gioi_tinh='$gioi_tinh', dia_chi='$dia_chi', lop_id=$lop_id 
                      WHERE id=$id";
            if (!$this->conn->query($sqlHS)) throw new Exception("Lỗi update HS");

            // 2. Update bảng Tài Khoản (Đổi username theo mã HS mới)
            $sqlTK = "UPDATE tai_khoan SET username='$ma_hs' WHERE id=$taiKhoanId";
            if (!$this->conn->query($sqlTK)) throw new Exception("Lỗi update Tài khoản");

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    // 6. Xóa học sinh (Chuyển trạng thái nghỉ học & khóa tài khoản)
    public function delete($id)
    {
        $hs = $this->getById($id);
        if (!$hs) return false;
        $tkID = $hs['tai_khoan_id'];

        $this->conn->begin_transaction();
        try {
            $this->conn->query("UPDATE tai_khoan SET trang_thai = 0 WHERE id = $tkID");
            $this->conn->query("UPDATE hoc_sinh SET trang_thai = 'nghihoc' WHERE id = $id");
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // 2. THÊM HÀM RESTORE (Khôi phục học sinh) vào cuối class
    public function restore($id)
    {
        $hs = $this->getById($id);
        if (!$hs) return false;
        $tkID = $hs['tai_khoan_id'];

        $this->conn->begin_transaction();
        try {
            // Mở lại tài khoản
            $this->conn->query("UPDATE tai_khoan SET trang_thai = 1 WHERE id = $tkID");
            // Chuyển trạng thái về đang học
            $this->conn->query("UPDATE hoc_sinh SET trang_thai = 'danghoc' WHERE id = $id");

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    // [MỚI] Kiểm tra trùng Mã HS (trừ chính nó)
    public function checkDuplicateMaHS($newMaHS, $excludeId)
    {
        $newMaHS = mysqli_real_escape_string($this->conn, $newMaHS);
        $sql = "SELECT id FROM hoc_sinh WHERE ma_hs = '$newMaHS' AND id != $excludeId";
        $rs = $this->conn->query($sql);
        return ($rs && $rs->num_rows > 0);
    }
}
