<?php
require_once("app/Models/mPhanCong.php");
require_once("app/Models/mLop.php");
require_once("app/Models/mNamHoc.php");

class cPhanCong
{
    private $model;
    private $modelLop;
    private $modelNamHoc;

    public function __construct()
    {
        $this->model = new mPhanCong();
        $this->modelLop = new mLop();
        $this->modelNamHoc = new mNamHoc();
    }

    public function hienThiGiaoDien()
    {
        $namHocHienTai = $this->modelNamHoc->getNamHocHienTai();
        $selectedNamHoc = isset($_GET['nam_hoc']) ? $_GET['nam_hoc'] : $namHocHienTai;

        // Thay vì chọn Lớp, giờ ta chọn MÔN và KHỐI
        $selectedMon = isset($_GET['mon_id']) ? $_GET['mon_id'] : null;
        $selectedKhoi = isset($_GET['khoi']) ? $_GET['khoi'] : null;

        // --- XỬ LÝ LƯU ---
        if (isset($_POST['btnLuuPhanCong'])) {
            $nam_hoc_post = $_POST['nam_hoc_id'];
            $mon_post = $_POST['mon_id'];
            $khoi_post = $_POST['khoi_current']; // Để redirect đúng trang

            // Mảng assignment: [lop_id => gv_id]
            $assignments = $_POST['phan_cong'];

            foreach ($assignments as $lop_id => $gv_id) {
                if ($gv_id != "") {
                    $this->model->savePhanCong($nam_hoc_post, $lop_id, $mon_post, $gv_id);
                } else {
                    $this->model->deletePhanCong($nam_hoc_post, $lop_id, $mon_post);
                }
            }

            // Tạo link redirect giữ nguyên bộ lọc
            $link = "index.php?act=quanlyphancong&nam_hoc=$nam_hoc_post&mon_id=$mon_post";
            if ($khoi_post) $link .= "&khoi=$khoi_post";

            echo "<script>alert('Đã lưu phân công bộ môn!'); window.location.href='$link';</script>";
        }

        // --- DỮ LIỆU HIỂN THỊ ---
        $dsNamHoc = $this->modelNamHoc->getAllNamHoc();
        $dsMonHoc = $this->model->getAllMonHoc(); // Dropdown chọn môn

        // Lấy danh sách lớp (có lọc theo Khối nếu chọn)
        $dsLop = $this->modelLop->getAll(null, $selectedNamHoc, $selectedKhoi);

        $dsGV = null;
        $dataDaPhanCong = [];

        // Chỉ khi chọn Môn thì mới load dữ liệu phân công
        if ($selectedMon) {
            $dsGV = $this->model->getAllGiaoVien();
            $dataDaPhanCong = $this->model->getPhanCongByMon($selectedNamHoc, $selectedMon);
        }

        include("app/Views/quanlyphancong.php");
    }
}
