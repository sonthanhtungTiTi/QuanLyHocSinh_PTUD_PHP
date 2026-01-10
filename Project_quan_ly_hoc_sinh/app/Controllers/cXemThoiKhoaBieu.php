<?php
require_once("app/Models/mThoiKhoaBieu.php");
require_once("app/Models/mXemDiem.php"); // Tận dụng model này để lấy thông tin HS
require_once("app/Models/mNamHoc.php");

class cXemThoiKhoaBieu {
    private $model;
    private $modelHS;
    private $modelNam;

    public function __construct() {
        $this->model = new mThoiKhoaBieu();
        $this->modelHS = new mXemDiem(); // Model này có hàm getHocSinhByUserId
        $this->modelNam = new mNamHoc();
    }

    public function hienThiGiaoDien() {
        // 1. Check quyền Học sinh (Role 4)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 4) {
             echo "<script>alert('Bạn không có quyền truy cập!'); window.location.href='index.php';</script>"; return;
        }

        // 2. Lấy thông tin Học sinh
        $hs = $this->modelHS->getHocSinhByUserId($_SESSION['user_id']);
        if(!$hs) { 
            echo "<div class='alert alert-danger m-5'>Lỗi: Tài khoản chưa liên kết hồ sơ học sinh.</div>"; return; 
        }

        $namHienTai = $this->modelNam->getNamHocHienTai();
        $selectedHK = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';

        // 3. Lấy TKB của Lớp mà học sinh đang học
        // Hàm getTKB trả về mảng: $tkbData[Thứ][Tiết] = ID Môn
        $tkbData = $this->model->getTKB($hs['lop_id'], $namHienTai, $selectedHK);

        include("app/Views/xemtkb_hs.php");
    }
}
?>