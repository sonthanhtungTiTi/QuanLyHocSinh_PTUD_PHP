<?php
require_once("app/Models/mLop.php");
require_once("app/Models/mGiaoVien.php");
require_once("app/Models/mNamHoc.php");

class cPhanCongGVCN
{
    private $modelLop;
    private $modelGV;
    private $modelNamHoc;

    public function __construct()
    {
        $this->modelLop = new mLop();
        $this->modelGV = new mGiaoVien();
        $this->modelNamHoc = new mNamHoc();
    }

    public function hienThiGiaoDien()
    {
        $namHocHienTai = $this->modelNamHoc->getNamHocHienTai();
        $selectedNamHoc = isset($_GET['nam_hoc']) ? $_GET['nam_hoc'] : $namHocHienTai;

        // Lấy tham số lọc Khối
        $selectedKhoi = isset($_GET['khoi']) ? $_GET['khoi'] : null;

        // --- XỬ LÝ LƯU PHÂN CÔNG ---
        if (isset($_POST['btnLuuGVCN'])) {
            $nam_hoc_post = $_POST['nam_hoc_id'];
            $assignments = $_POST['gvcn']; // Mảng: [id_lop => id_gv]
            $khoi_post = $_POST['khoi_current']; // Để giữ lại filter khi redirect

            // 1. KIỂM TRA TRÙNG LẶP (Server-side Validation)
            // Lọc ra các ID giáo viên đã chọn (bỏ qua giá trị rỗng)
            $selectedGVs = array_filter($assignments, function ($value) {
                return !empty($value);
            });

            // Đếm số lần xuất hiện của mỗi GV
            $counts = array_count_values($selectedGVs);

            $hasError = false;
            foreach ($counts as $gv_id => $count) {
                if ($count > 1) {
                    $hasError = true;
                    // Lấy tên GV để báo lỗi cho chi tiết (Optional)
                    echo "<script>alert('LỖI: Một giáo viên không thể chủ nhiệm nhiều lớp cùng lúc! Vui lòng kiểm tra lại.'); window.history.back();</script>";
                    return; // Dừng hình ngay
                }
            }

            // 2. NẾU KHÔNG LỖI -> LƯU
            if (!$hasError) {
                foreach ($assignments as $lop_id => $gv_id) {
                    $this->modelLop->updateGVCN($lop_id, $gv_id);
                }
                // Redirect giữ nguyên bộ lọc
                $link = "index.php?act=phanconggvcn&nam_hoc=$nam_hoc_post";
                if ($khoi_post) $link .= "&khoi=$khoi_post";

                echo "<script>alert('Cập nhật GVCN thành công!'); window.location.href='$link';</script>";
            }
        }

        // --- LẤY DỮ LIỆU HIỂN THỊ ---
        $dsNamHoc = $this->modelNamHoc->getAllNamHoc();

        // Gọi hàm getAll của mLop có hỗ trợ lọc theo Khối ($selectedKhoi)
        // Tham số: ($keyword, $namHocId, $khoiId)
        $dsLop = $this->modelLop->getAll(null, $selectedNamHoc, $selectedKhoi);

        $dsGV = $this->modelGV->getAll();

        include("app/Views/phanconggvcn.php");
    }
}
