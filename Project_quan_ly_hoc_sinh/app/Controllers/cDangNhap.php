<?php
// app/Controllers/cDangNhap.php
require_once("app/Models/mDangNhap.php");

class cDangNhap
{
    private $model;

    public function __construct()
    {
        $this->model = new mDangNhap();
    }

    // Hàm 1: Chỉ hiển thị form
    public function hienThiDangNhap()
    {
        include("app/Views/dangnhap.php");
    }

    // Hàm 2: Xử lý logic khi bấm nút
    public function xuLyDangNhap()
    {
        if (isset($_POST['btnDangNhap'])) {
            $user = trim($_POST['username']);
            $pass = trim($_POST['password']);

            if (empty($user) || empty($pass)) {
                echo "<script>alert('Vui lòng nhập đầy đủ thông tin!');</script>";
                $this->hienThiDangNhap();
                return;
            }

            // Gọi Model kiểm tra
            $result = $this->model->kiemTraDangNhap($user, $pass);

            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // --- ĐĂNG NHẬP THÀNH CÔNG ---

                // 1. Lưu thông tin cơ bản
                $_SESSION['is_login'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role_id'] = $row['vai_tro_id']; // Cần đảm bảo cột này đúng tên trong DB (vai_tro_id)
                $_SESSION['role_name'] = $row['ten_vai_tro'];

                // 2. ===> [MỚI] LẤY HỌ TÊN THẬT <===
                $realName = $row['username']; // Mặc định lấy username nếu ko tìm thấy tên thật

                // Nếu là Giáo viên (Giả sử ID vai trò là 3)
                if ($row['vai_tro_id'] == 3) {
                    $tenGV = $this->model->getHoTenGiaoVien($row['id']);
                    if ($tenGV) $realName = $tenGV;
                }
                // Nếu là Học sinh (Giả sử ID vai trò là 4)
                elseif ($row['vai_tro_id'] == 4) {
                    $tenHS = $this->model->getHoTenHocSinh($row['id']);
                    if ($tenHS) $realName = $tenHS;
                }
                // Nếu là Admin (ID 1)
                elseif ($row['vai_tro_id'] == 1) {
                    $realName = "Quản trị viên";
                }

                // 3. LƯU TÊN THẬT VÀO SESSION ĐỂ DÙNG Ở CHỖ KHÁC
                $_SESSION['user_fullname'] = $realName;

                // Thông báo và chuyển hướng
                echo "<script>
                        alert('Xin chào: " . $realName . "');
                        window.location.href = 'index.php?act=trangchu';
                      </script>";
            } else {
                // --- ĐĂNG NHẬP THẤT BẠI ---
                echo "<script>alert('Sai tên đăng nhập hoặc mật khẩu!');</script>";
                $this->hienThiDangNhap();
            }
        }
    }
}
