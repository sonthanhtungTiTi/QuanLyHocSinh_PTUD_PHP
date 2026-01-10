<?php
require_once("app/Models/mMonHoc.php");

class cMonHoc
{
    private $model;

    public function __construct()
    {
        $this->model = new mMonHoc();
    }

    public function hienThiDanhSach()
    {
        $editData = null;

        // --- XỬ LÝ LƯU (THÊM / SỬA) ---
        if (isset($_POST['btnLuu'])) {
            $tenMon = trim($_POST['ten_mon']);
            $heSo = 1;

            if (empty($tenMon)) {
                echo "<script>alert('Vui lòng nhập tên môn học!'); window.history.back();</script>";
            } else {
                $id = isset($_POST['id_mon']) ? $_POST['id_mon'] : null;

                // Kiểm tra trùng tên
                if ($this->model->checkTonTai($tenMon, $id)) {
                    echo "<script>alert('Lỗi: Tên môn học \"$tenMon\" đã tồn tại!'); window.history.back();</script>";
                } else {
                    $msg = "";

                    if ($id) {
                        // Trường hợp SỬA
                        if ($this->model->update($id, $tenMon, $heSo)) {
                            $msg = "Cập nhật tên môn học thành công!";
                        } else {
                            $msg = "Lỗi hệ thống khi cập nhật!";
                        }
                    } else {
                        // Trường hợp THÊM MỚI
                        if ($this->model->insert($tenMon, $heSo)) {
                            $msg = "Thêm mới môn học thành công!";
                        } else {
                            $msg = "Lỗi hệ thống khi thêm mới!";
                        }
                    }

                    // ===> QUAN TRỌNG: Hiện Alert trước khi chuyển trang <===
                    echo "<script>
                            alert('$msg'); 
                            window.location.href='index.php?act=quanlydanhmucmonhoc';
                          </script>";
                }
            }
        }

        // --- XỬ LÝ ĐỔI TRẠNG THÁI (ẨN/HIỆN) ---
        if (isset($_GET['toggle_id']) && isset($_GET['status'])) {
            $id = $_GET['toggle_id'];
            $status = $_GET['status'];

            if ($this->model->toggleStatus($id, $status)) {
                // Tùy chỉnh thông báo cho rõ ràng hơn
                $msgStatus = ($status == 1) ? "Đã tạm ngưng môn học!" : "Đã kích hoạt lại môn học!";
                echo "<script>alert('$msgStatus'); window.location.href='index.php?act=quanlydanhmucmonhoc';</script>";
            } else {
                echo "<script>alert('Lỗi hệ thống!');</script>";
            }
        }

        // --- LẤY DỮ LIỆU SỬA ---
        if (isset($_GET['edit_id'])) {
            $editData = $this->model->getById($_GET['edit_id']);
        }

        // Lấy danh sách hiển thị
        $dsMonHoc = $this->model->getAll();

        include("app/Views/quanlydanhmucmonhoc.php");
    }
}
