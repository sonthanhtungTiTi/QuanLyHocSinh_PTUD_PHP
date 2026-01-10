<?php
require_once("app/Models/mLop.php");
require_once("app/Models/mNamHoc.php");
require_once("app/Models/mGiaoVien.php"); // <--- MỚI THÊM
class cLop
{
    private $model;
    private $modelNamHoc;
    private $modelGV; // <--- MỚI THÊM

    public function __construct()
    {
        $this->model = new mLop();
        $this->modelNamHoc = new mNamHoc();
        $this->modelGV = new mGiaoVien(); // <--- MỚI THÊM
    }

    public function hienThiDanhSach()
    {
        $editData = null;

        // ==================================================================
        // 1. XỬ LÝ FORM LƯU LỚP (Thêm mới hoặc Cập nhật)
        // ==================================================================
        if (isset($_POST['btnLuuLop'])) {
            $nam_hoc_id = $_POST['nam_hoc_id'];
            $khoi_id = $_POST['khoi_id'];

            // Xử lý tên lớp (Ghép Prefix)
            $suffix = trim($_POST['ten_lop_suffix']);
            $suffix = strtoupper($suffix); // Viết hoa
            $ten_lop_full = $khoi_id . $suffix; // VD: 10 + A1 = 10A1

            // ===> [MỚI] KIỂM TRA TRÙNG TÊN LỚP <===
            $id_check = !empty($_POST['id_lop']) ? $_POST['id_lop'] : null;

            if ($this->model->checkTonTai($ten_lop_full, $nam_hoc_id, $id_check)) {
                // Nếu trùng thì báo lỗi và Dừng lại ngay
                echo "<script>alert('LỖI: Lớp $ten_lop_full đã tồn tại trong năm học này rồi!'); window.history.back();</script>";
                return; // Quan trọng: Dừng hàm lại, không chạy tiếp đoạn dưới
            }
            // ========================================

            if (!empty($_POST['id_lop'])) {
                // Gọi hàm update cũ
                if ($this->model->update($_POST['id_lop'], $ten_lop_full, $nam_hoc_id, $khoi_id)) {
                    echo "<script>alert('Cập nhật thành công!'); window.location.href='index.php?act=quanlylop';</script>";
                } else {
                    echo "<script>alert('Lỗi hệ thống!');</script>";
                }
            } else {
                // Gọi hàm insert cũ
                if ($this->model->insert($ten_lop_full, $nam_hoc_id, $khoi_id)) {
                    echo "<script>alert('Thêm mới thành công!'); window.location.href='index.php?act=quanlylop';</script>";
                } else {
                    echo "<script>alert('Lỗi hệ thống!');</script>";
                }
            }
        }

        // ==================================================================
        // 2. XỬ LÝ XÓA LỚP
        // ==================================================================
        if (isset($_GET['delete_id'])) {
            if ($this->model->delete($_GET['delete_id'])) {
                echo "<script>alert('Xóa thành công!'); window.location.href='index.php?act=quanlylop';</script>";
            } else {
                echo "<script>alert('Không thể xóa: Lớp đã có dữ liệu!'); window.location.href='index.php?act=quanlylop';</script>";
            }
        }

        // ==================================================================
        // 3. CHUẨN BỊ DỮ LIỆU ĐỂ HIỂN THỊ RA VIEW
        // ==================================================================

        // --- CHUẨN BỊ DATA VIEW ---
        if (isset($_GET['edit_id'])) {
            $editData = $this->model->getById($_GET['edit_id']);
        }
        // [CẬP NHẬT] Lấy năm học hiện tại làm mặc định
        $namHocHienTai = $this->modelNamHoc->getNamHocHienTai();

        // Nếu trên URL có chọn năm thì lấy, nếu không thì lấy năm hiện tại
        $filter_nam = isset($_GET['nam_hoc']) ? $_GET['nam_hoc'] : $namHocHienTai;

        $filter_khoi = isset($_GET['khoi']) ? $_GET['khoi'] : null;
        $keyword = isset($_GET['search']) ? $_GET['search'] : null;

        $dsLop = $this->model->getAll($keyword, $filter_nam, $filter_khoi);
        $dsNamHoc = $this->modelNamHoc->getAllNamHoc();

        // Lấy danh sách Giáo viên để chọn làm chủ nhiệm
        $dsGV = $this->modelGV->getAll(); // <--- MỚI THÊM

        include("app/Views/quanlylop.php");
    }
}
