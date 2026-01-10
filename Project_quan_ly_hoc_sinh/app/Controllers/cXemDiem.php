<?php
// File: app/Controllers/cXemDiem.php

// SỬA DÒNG NÀY: Gọi đúng file mXemDiem.php
require_once("app/Models/mXemDiem.php"); 
require_once("app/Models/mNamHoc.php");

class cXemDiem {
    private $model;
    private $modelNamHoc;

    public function __construct() {
        // SỬA DÒNG NÀY: Khởi tạo đúng class mXemDiem
        $this->model = new mXemDiem(); 
        $this->modelNamHoc = new mNamHoc();
    }

    public function hienThiGiaoDien() {
        if (!isset($_SESSION['user_id'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='index.php';</script>"; return;
        }

        // Lấy thông tin
        $hs = $this->model->getHocSinhByUserId($_SESSION['user_id']);
        
        if (!$hs) {
            echo "<div class='alert alert-danger container mt-4'>
                    <h4>Lỗi xác thực</h4>
                    <p>Tài khoản của bạn (ID: {$_SESSION['user_id']}) chưa được liên kết với hồ sơ Học sinh nào.</p>
                    <p>Vui lòng liên hệ Admin để cập nhật 'tai_khoan_id' trong bảng 'hoc_sinh'.</p>
                  </div>";
            return;
        }

        $namHocHienTai = $this->modelNamHoc->getNamHocHienTai();
        $selectedHK = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';

        // Lấy điểm
        $dsDiem = $this->model->getBangDiemCaNhan($hs['id'], $namHocHienTai, $selectedHK);
        $tongKet = $this->model->getTongKetCaNhan($hs['id'], $namHocHienTai, $selectedHK);

        include("app/Views/xemdiem_hs.php");
    }
}
?>