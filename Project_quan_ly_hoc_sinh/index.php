<?php
session_start();
ob_start();

// Hiển thị thông báo (nếu có)
if (isset($_SESSION['message'])) {
    echo '<script>alert("' . $_SESSION['message'] . '")</script>';
    unset($_SESSION['message']);
}

// 1. LẤY ACT
$act = isset($_GET['act']) ? $_GET['act'] : 'trangchu';

// 2. CHECK LOGIN
if (!isset($_SESSION['is_login']) && $act != 'dangnhap') {
    header("Location: index.php?act=dangnhap");
    exit();
}

$role_id = isset($_SESSION['role_id']) ? $_SESSION['role_id'] : 0;

// 3. LOAD HEADER (Trừ trang đăng nhập)
if ($act != 'dangnhap') {
    include "app/Views/layout/header.php";
}

// 4. ROUTER (GIỮ NGUYÊN LOGIC CŨ CỦA BẠN, CHỈ COPY LẠI)
switch ($act) {
    case 'dangnhap':
        require_once "app/Controllers/cDangNhap.php";
        $c = new cDangNhap();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') $c->xuLyDangNhap();
        else $c->hienThiDangNhap();
        break;

    case 'trangchu':
        // Gọi file giao diện Dashboard mới thiết kế
        include "app/Views/trangchu.php";
        break;

    // --- COPY LẠI TOÀN BỘ CÁC CASE KHÁC TỪ FILE CŨ VÀO ĐÂY ---
    // (Tôi viết ví dụ vài cái, bạn copy nốt phần còn lại nhé)




    case 'quanlytkb':
        require_once "app/Controllers/cQuanLyTKB.php";
        $c = new cQuanLyTKB();
        $c->hienThiGiaoDien();
        break;



    // --- KHU VỰC CHUNG (AI CŨNG VÀO ĐƯỢC) ---



    case 'doimatkhau':
        require_once "app/Controllers/cTaiKhoan.php";
        $c = new cTaiKhoan();
        $c->hienThiGiaoDien();
        break;

    case 'dangxuat':
        // Xử lý đăng xuất luôn cho tiện
        session_destroy();
        echo "<script>alert('Đã đăng xuất!'); window.location.href='index.php';</script>";
        break;

    // --- KHU VỰC ADMIN (ROLE = 1) ---

    case 'quanlynamhoc':
        // 1. Check quyền: Chỉ Admin (Role ID = 1) mới được vào
        if ($role_id != 1) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }

        // 2. Gọi Controller
        require_once "app/Controllers/cNamHoc.php";
        $c = new cNamHoc();
        $c->hienThiDanhSach();
        break;

    case 'quanlydanhmucmonhoc':
        // 1. Check quyền Admin
        if ($role_id != 1) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }

        // 2. Gọi Controller thật
        require_once "app/Controllers/cMonHoc.php";
        $c = new cMonHoc();
        $c->hienThiDanhSach();
        break;

    // Trong switch($act) của index.php

    case 'quanlydanhmucgiaovien':
        if ($role_id != 1) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }

        require_once "app/Controllers/cGiaoVien.php";
        $c = new cGiaoVien();
        $c->hienThiDanhSach();
        break;

    // Trong switch($act) - Khu vực ADMIN
    case 'quanlylop':
        if ($role_id != 1) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }
        require_once "app/Controllers/cLop.php";
        $c = new cLop();
        $c->hienThiDanhSach();
        break;

    // Trong switch($act) - Khu vực Admin
    case 'quanlyhocsinh':
        if ($role_id != 1) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }
        require_once "app/Controllers/cHocSinh.php";
        $c = new cHocSinh();
        $c->hienThiDanhSach();
        break;
    case 'phanlophocsinh':
        if ($role_id != 1) {
            echo "<h3 class='text-danger text-center'>Không có quyền!</h3>";
            break;
        }
        // Gọi Controller Phân lớp (Dev 3 làm)
        break;
    case 'quanlytkb':
        // Admin (Role 1)
        require_once "app/Controllers/cQuanLyTKB.php";
        $c = new cQuanLyTKB();
        $c->hienThiGiaoDien();
        break;

    case 'quanlytaikhoan':
        require_once "app/Controllers/cQuanLyTaiKhoan.php";
        $c = new cQuanLyTaiKhoan();
        $c->hienThiDanhSach();
        break;
    // --- KHU VỰC BGH (ROLE = 2) ---

    case 'thongkebaocao':
        // Chỉ Admin và BGH được xem
        require_once "app/Controllers/cThongKe.php";
        $c = new cThongKe();
        $c->hienThiGiaoDien();
        break;
    // --- KHU VỰC GIÁO VIÊN (ROLE = 3) ---
    // Trong switch($act)
    case 'quanlyphancong':
        // Cho phép Admin và BGH
        if ($role_id != 1 && $role_id != 2) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }
        require_once "app/Controllers/cPhanCong.php";
        $c = new cPhanCong();
        $c->hienThiGiaoDien();
        break;
    // Trong switch($act)
    case 'phanconggvcn':
        // Cho phép Admin và BGH
        if ($role_id != 1 && $role_id != 2) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }
        require_once "app/Controllers/cPhanCongGVCN.php";
        $c = new cPhanCongGVCN();
        $c->hienThiGiaoDien();
        break;


    // ...
    case 'nhapdiem':
        // Chỉ Giáo viên (Role 3) hoặc Admin (Role 1 - để test) được vào
        // BGH (Role 2) chỉ được xem (chúng ta sẽ làm chức năng xem điểm riêng sau)
        if ($role_id != 1 && $role_id != 3) {
            echo "<script>alert('Chức năng dành cho Giáo viên!'); window.location.href='index.php?act=trangchu';</script>";
            break;
        }
        require_once "app/Controllers/cDiem.php";
        $c = new cDiem();
        $c->hienThiGiaoDien();
        break;
    // ... 


    case 'tongket':

        if ($role_id != 1 && $role_id != 3) {
            echo "<script>alert('Không có quyền truy cập!'); window.location.href='index.php';</script>";
            break;
        }
        require_once "app/Controllers/cTongKet.php";
        $c = new cTongKet();
        $c->hienThiGiaoDien();
        break;

    case 'xemlichday':
        require_once "app/Controllers/cGiangDay.php";
        $c = new cGiangDay();
        $c->xemLichDay();
        break;

    case 'xemdanhsachlopday':
        require_once "app/Controllers/cGiangDay.php";
        $c = new cGiangDay();
        $c->xemDanhSachLop();
        break;

    case 'duyetdon':
        require_once "app/Controllers/cDuyetDon.php";
        $c = new cDuyetDon();
        $c->hienThiGiaoDien();
        break;

    case 'xemlopchunhiem':
        require_once "app/Controllers/cGiangDay.php";
        $c = new cGiangDay();
        $c->xemLopChuNhiem();
        break;

    case 'xembangdiem':
        // Chỉ GV (Role 3) được xem
        if ($role_id != 3) {
            break;
        }
        require_once "app/Controllers/cDiem.php"; // Dùng cDiem của bạn
        $c = new cDiem();
        $c->xemBangDiemChiTiet(); // Gọi hàm mới vừa thêm
        break;

    case 'hosogv':
        require_once "app/Controllers/cHoSoGiaoVien.php";
        $c = new cHoSoGiaoVien();
        $c->hienThiGiaoDien();
        break;
    // --- KHU VỰC HỌC SINH (ROLE = 4) ---

    case 'xinnghi':
        require_once "app/Controllers/cXinPhep.php";
        $c = new cXinPhep();
        $c->hienThiGiaoDien();
        break;


    case 'xemthoikhoabieu':
        // Kiểm tra quyền: Chỉ cho Học sinh (Role 4)
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 4) {
            // Nếu là GV muốn xem thì điều hướng sang trang khác (sắp làm)
            echo "<script>alert('Chức năng này dành cho Học sinh!'); window.location.href='index.php';</script>";
            break;
        }
        require_once "app/Controllers/cXemThoiKhoaBieu.php";
        $c = new cXemThoiKhoaBieu();
        $c->hienThiGiaoDien();
        break;
    case 'xemdiem':
        // Chỉ dành cho Học sinh (Role 4)
        if ($role_id != 4) {
            echo "<script>alert('Chức năng dành cho học sinh!'); window.location.href='index.php';</script>";
            break;
        }
        require_once "app/Controllers/cXemDiem.php";
        $c = new cXemDiem();
        $c->hienThiGiaoDien();
        break;
    case 'hosocanhan':
        require_once "app/Controllers/cHoSoCaNhan.php";
        $c = new cHoSoCaNhan();
        $c->hienThiGiaoDien();
        break;
    // --- MẶC ĐỊNH ---
    default:
        echo "<div class='alert alert-warning text-center'>Chức năng <b>$act</b> chưa được phát triển hoặc đường dẫn sai!</div>";
        break;
}

// 5. LOAD FOOTER (Trừ trang đăng nhập)
if ($act != 'dangnhap') {
    include "app/Views/layout/footer.php";
}
