<?php
// app/Controllers/cNamHoc.php
require_once("app/Models/mNamHoc.php");

class cNamHoc
{
    private $model;

    public function __construct()
    {
        $this->model = new mNamHoc();
    }

    // Hàm chính: Hiển thị và xử lý logic chung
    public function hienThiDanhSach()
    {
        // 1. Xử lý Thêm mới (CODE ĐÃ SỬA LOGIC CHẶN TRÙNG)
        if (isset($_POST['btnThemNam'])) {
            $namBatDau = intval($_POST['nam_bat_dau']);
            $namKetThuc = $namBatDau + 1;
            $tenNam = $namBatDau . "-" . $namKetThuc;

            // BƯỚC KIỂM TRA QUAN TRỌNG
            if ($this->model->checkNamHocTonTai($tenNam)) {
                // Nếu hàm trả về true -> Đã có -> Báo lỗi
                echo "<script>alert('Lỗi: Năm học $tenNam đã tồn tại trong hệ thống!');</script>";
            } else {
                // Nếu hàm trả về false -> Chưa có -> Thêm mới
                if ($this->model->themNamHoc($tenNam)) {
                    echo "<script>alert('Thêm năm học $tenNam thành công!'); window.location.href='index.php?act=quanlynamhoc';</script>";
                } else {
                    echo "<script>alert('Lỗi hệ thống khi thêm năm học!');</script>";
                }
            }
        }   

        // 2. Xử lý Kích hoạt (Set Active)
        if (isset($_POST['btnActive'])) {
            $id = $_POST['id_active'];
            $this->model->setNamHocHienTai($id);
            echo "<script>alert('Đã đổi năm học hiện tại!');</script>";
        }

        // 3. Xử lý Xóa
        // if (isset($_GET['delete_id'])) {
        //     $id = $_GET['delete_id'];
        //     // Code bảo vệ: Không cho xóa năm đang kích hoạt (tránh lỗi hệ thống)
        //     // Ở đây tôi làm nhanh, bạn có thể thêm logic check sau
        //     if ($this->model->xoaNamHoc($id)) {
        //         echo "<script>alert('Xóa thành công!'); window.location.href='index.php?act=quanlynamhoc';</script>";
        //     }
        // }

        // 4. Lấy dữ liệu mới nhất để hiển thị ra View
        $listNamHoc = $this->model->getAllNamHoc();
        include("app/Views/quanlynamhoc.php");
    }
}
