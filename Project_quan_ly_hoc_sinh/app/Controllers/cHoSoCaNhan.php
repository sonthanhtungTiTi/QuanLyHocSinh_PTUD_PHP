<?php
require_once("app/Models/mXemDiem.php");

class cHoSoCaNhan {
    private $model;

    public function __construct() {
        $this->model = new mXemDiem();
    }

    public function hienThiGiaoDien() {
        // Check quyền HS
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 4) {
             header("Location: index.php"); exit();
        }

        $hs = $this->model->getHocSinhByUserId($_SESSION['user_id']);
        
        if(!$hs) {
            echo "Không tìm thấy hồ sơ."; return;
        }

        include("app/Views/hoso_hs.php");
    }
}
?>