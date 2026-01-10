<?php
require_once("config/ketnoi.php");

class mGiaoVien
{
    private $conn;

    public function __construct()
    {
        $p = new clsKetNoi();
        $this->conn = $p->moKetNoi();
    }

    // Sửa lại hàm getAll để hỗ trợ tìm kiếm và lọc
    public function getAll($keyword = null, $monHocId = null)
    {
        $sql = "SELECT gv.*, mh.ten_mon 
                FROM giao_vien gv 
                LEFT JOIN mon_hoc mh ON gv.mon_hoc_id = mh.id 
                WHERE 1=1"; // Kỹ thuật 1=1 để dễ nối chuỗi AND phía sau

        // 1. Nếu có từ khóa tìm kiếm (Mã hoặc Tên)
        if ($keyword != null && $keyword != '') {
            $keyword = mysqli_real_escape_string($this->conn, $keyword);
            $sql .= " AND (gv.ho_ten LIKE '%$keyword%' OR gv.ma_gv LIKE '%$keyword%')";
        }

        // 2. Nếu có lọc theo môn học
        if ($monHocId != null && $monHocId != '') {
            $monHocId = intval($monHocId);
            $sql .= " AND gv.mon_hoc_id = $monHocId";
        }

        $sql .= " ORDER BY gv.id DESC";

        return $this->conn->query($sql);
    }

    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM giao_vien WHERE id = $id";
        return $this->conn->query($sql)->fetch_assoc();
    }

    // --- CẬP NHẬT HÀM INSERT ĐẦY ĐỦ ---
    public function insert($ma_gv, $ho_ten, $sdt, $ngay_sinh, $dia_chi, $email, $mon_hoc_id)
    {
        // Xử lý dữ liệu văn bản để tránh lỗi SQL khi có dấu nháy đơn '
        $dia_chi = mysqli_real_escape_string($this->conn, $dia_chi);
        $ho_ten = mysqli_real_escape_string($this->conn, $ho_ten);

        $this->conn->begin_transaction();
        try {
            // 1. Tạo Tài khoản
            $passHash = md5('123456');
            $sqlTK = "INSERT INTO tai_khoan (username, password, vai_tro_id, trang_thai) 
                      VALUES ('$ma_gv', '$passHash', 3, 1)";

            if (!$this->conn->query($sqlTK)) throw new Exception($this->conn->error);
            $tai_khoan_id = $this->conn->insert_id;

            // 2. Tạo Hồ sơ Giáo viên (Đã thêm dia_chi)
            $sqlGV = "INSERT INTO giao_vien (ma_gv, ho_ten, sdt, ngay_sinh, dia_chi, email, mon_hoc_id, tai_khoan_id, trang_thai) 
                      VALUES ('$ma_gv', '$ho_ten', '$sdt', '$ngay_sinh', '$dia_chi', '$email', $mon_hoc_id, $tai_khoan_id, 'hoatdong')";

            if (!$this->conn->query($sqlGV)) throw new Exception($this->conn->error);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    // 2. [CẬP NHẬT] Hàm Update (Sửa cả Mã GV và Username)
    public function update($id, $ma_gv, $ho_ten, $sdt, $ngay_sinh, $dia_chi, $email, $mon_hoc_id)
    {
        // Lấy ID tài khoản trước
        $gv = $this->getById($id);
        $taiKhoanId = $gv['tai_khoan_id'];

        $dia_chi = mysqli_real_escape_string($this->conn, $dia_chi);
        $ho_ten = mysqli_real_escape_string($this->conn, $ho_ten);

        // Bắt đầu Transaction để đảm bảo tính toàn vẹn
        $this->conn->begin_transaction();
        try {
            // Bước 1: Update bảng Giao Viên
            $sqlGV = "UPDATE giao_vien 
                      SET ma_gv='$ma_gv', ho_ten='$ho_ten', sdt='$sdt', ngay_sinh='$ngay_sinh', 
                          dia_chi='$dia_chi', email='$email', mon_hoc_id=$mon_hoc_id 
                      WHERE id=$id";
            if (!$this->conn->query($sqlGV)) throw new Exception("Lỗi update GV");

            // Bước 2: Update bảng Tài Khoản (Đổi username theo mã GV mới)
            $sqlTK = "UPDATE tai_khoan SET username='$ma_gv' WHERE id=$taiKhoanId";
            if (!$this->conn->query($sqlTK)) throw new Exception("Lỗi update Tài khoản");

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function delete($id)
    {
        $gv = $this->getById($id);
        if (!$gv) return false;
        $tkID = $gv['tai_khoan_id'];

        $this->conn->begin_transaction();
        try {
            $this->conn->query("UPDATE tai_khoan SET trang_thai = 0 WHERE id = $tkID");
            $this->conn->query("UPDATE giao_vien SET trang_thai = 'nghiviec' WHERE id = $id");
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
    // HÀM MỚI: Lấy ID môn học dựa vào tên môn (Dùng cho Import Excel)
    public function getMonHocIdByTen($tenMon)
    {
        $tenMon = mysqli_real_escape_string($this->conn, trim($tenMon));
        $sql = "SELECT id FROM mon_hoc WHERE ten_mon LIKE '$tenMon' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        }
        return null; // Không tìm thấy môn
    }
    // Hàm tìm thông tin GV dựa trên ID tài khoản đăng nhập
    public function getByUserId($userId)
    {
        $userId = intval($userId);
        // Tìm dòng trong bảng giao_vien có tai_khoan_id trùng khớp
        $sql = "SELECT * FROM giao_vien WHERE tai_khoan_id = $userId LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null; // Không tìm thấy
    }

    // ===> THÊM HÀM NÀY: Lấy danh sách lớp được phân công dạy
    // Lấy danh sách lớp được phân công
    public function getLopPhanCong($gvId, $namId)
    {
        $sql = "SELECT pc.*, l.ten_lop, k.ten_khoi, mh.ten_mon
                FROM phan_cong pc
                JOIN lop l ON pc.lop_id = l.id
                JOIN khoi k ON l.khoi_id = k.id
                JOIN mon_hoc mh ON pc.mon_hoc_id = mh.id
                WHERE pc.giao_vien_id = $gvId AND pc.nam_hoc_id = $namId
                ORDER BY l.ten_lop ASC";
        return $this->conn->query($sql);
    }

    // ===> THÊM HÀM NÀY VÀO CUỐI CLASS mGiaoVien <===
    public function getGiaoVienByUserId($userId)
    {
        $userId = intval($userId);

        // [CẬP NHẬT] Thêm LEFT JOIN mon_hoc để lấy cột 'ten_mon'
        $sql = "SELECT gv.*, mh.ten_mon 
                FROM giao_vien gv
                LEFT JOIN mon_hoc mh ON gv.mon_hoc_id = mh.id
                WHERE gv.tai_khoan_id = $userId";

        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    // ===> THÊM HÀM NÀY: Lấy danh sách HS lớp chủ nhiệm (Sắp xếp theo Tên A-Z)
    public function getHocSinhLopChuNhiem($gvId, $namId)
    {
        // 1. Tìm lớp do GV này chủ nhiệm trước
        $sqlLop = "SELECT id, ten_lop FROM lop WHERE gvcn_id = $gvId AND nam_hoc_id = $namId";
        $resLop = $this->conn->query($sqlLop);

        if ($resLop && $resLop->num_rows > 0) {
            $lop = $resLop->fetch_assoc();
            $lopId = $lop['id'];

            // 2. Lấy danh sách học sinh
            // ORDER BY SUBSTRING_INDEX(ho_ten, ' ', -1) : Sắp xếp theo Tên (từ cuối cùng)
            $sqlHS = "SELECT * FROM hoc_sinh 
                      WHERE lop_id = $lopId AND trang_thai = 'danghoc' 
                      ORDER BY SUBSTRING_INDEX(ho_ten, ' ', -1) ASC, ho_ten ASC";

            $resultHS = $this->conn->query($sqlHS);

            return ['lop' => $lop, 'hoc_sinh' => $resultHS];
        }
        return null; // Không chủ nhiệm lớp nào
    }
    // ===> [MỚI] Hàm cập nhật thông tin cá nhân (Dành riêng cho GV tự sửa)
    public function updateProfileCaNhan($id, $sdt, $email, $dia_chi)
    {
        $dia_chi = mysqli_real_escape_string($this->conn, $dia_chi);
        // Chỉ cho phép update 3 trường này
        $sql = "UPDATE giao_vien 
                SET sdt = '$sdt', email = '$email', dia_chi = '$dia_chi' 
                WHERE id = $id";
        return $this->conn->query($sql);
    }

    // 1. Hàm kiểm tra trùng mã (dùng khi Sửa)
    public function checkDuplicateMaGV($newMaGV, $excludeId)
    {
        $sql = "SELECT id FROM giao_vien WHERE ma_gv = '$newMaGV' AND id != $excludeId";
        $rs = $this->conn->query($sql);
        return ($rs && $rs->num_rows > 0);
    }
    // ===> [MỚI] Hàm Khôi phục Giáo viên (Undo Delete)
    public function restore($id)
    {
        $gv = $this->getById($id);
        if (!$gv) return false;
        $tkID = $gv['tai_khoan_id'];

        $this->conn->begin_transaction();
        try {
            // 1. Mở lại tài khoản (trang_thai = 1)
            $this->conn->query("UPDATE tai_khoan SET trang_thai = 1 WHERE id = $tkID");

            // 2. Chuyển trạng thái giáo viên về 'hoatdong'
            $this->conn->query("UPDATE giao_vien SET trang_thai = 'hoatdong' WHERE id = $id");

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
