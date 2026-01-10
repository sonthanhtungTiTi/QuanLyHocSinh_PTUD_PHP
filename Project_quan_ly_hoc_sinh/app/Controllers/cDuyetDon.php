<?php
require_once("app/Models/mDonXinPhep.php");
require_once("app/Models/mGiaoVien.php"); // Sử dụng đúng tên file model của bạn

class cDuyetDon
{
    private $model;
    private $modelGV;

    public function __construct()
    {
        $this->model = new mDonXinPhep();
        $this->modelGV = new mGiaoVien();
    }

    public function hienThiGiaoDien()
    {
        // Check quyền GV (Role 3)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header("Location: index.php");
            exit();
        }
        $gv = $this->modelGV->getGiaoVienByUserId($_SESSION['user_id']);
        if (!$gv) {
            echo "Lỗi giáo viên.";
            return;
        }

        // ===> SỬA ĐOẠN NÀY <===
        // Kiểm tra nếu người dùng bấm nút có name="btnXuLy"
        if (isset($_POST['btnXuLy'])) {
            $donId = $_POST['don_id'];
            $phanHoi = $_POST['phan_hoi'];

            // Lấy giá trị trực tiếp từ nút bấm (value="1" hoặc value="2")
            $trangThai = $_POST['btnXuLy'];

            $this->model->capNhatTrangThai($donId, $trangThai, $phanHoi);
            echo "<script>alert('Đã xử lý đơn thành công!'); window.location.href='index.php?act=duyetdon';</script>";
        }
        // =======================

        $dsDon = $this->model->getDonCanDuyet($gv['id']);
        include("app/Views/duyetdon_gv.php");
    }
}
