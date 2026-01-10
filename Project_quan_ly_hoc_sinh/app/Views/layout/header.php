<?php
// Kiểm tra act hiện tại để active menu
$act = isset($_GET['act']) ? $_GET['act'] : 'trangchu';
$role_id = isset($_SESSION['role_id']) ? $_SESSION['role_id'] : 0;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Trường Học</title>
    <link href="public/vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0 fw-bold text-uppercase"><i class="bi bi-mortarboard-fill"></i> QL Trường Học</h4>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="index.php?act=trangchu" class="<?php echo ($act == 'trangchu') ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> <span>Tổng Quan</span>
                </a>
            </li>

            <?php if ($role_id == 1): ?>
                <div class="menu-title">Hệ Thống</div>
                <li><a href="index.php?act=quanlynamhoc" class="<?php echo ($act == 'quanlynamhoc') ? 'active' : ''; ?>"><i class="bi bi-calendar-event"></i> Quản lý năm học</a></li>
                <li><a href="index.php?act=quanlydanhmucmonhoc" class="<?php echo ($act == 'quanlymonhoc') ? 'active' : ''; ?>"><i class="bi bi-book"></i> Quản lý môn học</a></li>
                <li><a href="index.php?act=quanlytaikhoan" class="<?php echo ($act == 'quanlytaikhoan') ? 'active' : ''; ?>"><i class="bi bi-shield-lock"></i> Quản lý tài khoản</a></li>

                <div class="menu-title">Quản Lý</div>
                <li><a href="index.php?act=quanlydanhmucgiaovien" class="<?php echo ($act == 'quanlydanhmucgiaovien') ? 'active' : ''; ?>"><i class="bi bi-person-video3"></i> Quản lý giáo viên</a></li>
                <li><a href="index.php?act=quanlyhocsinh" class="<?php echo ($act == 'quanlyhocsinh') ? 'active' : ''; ?>"><i class="bi bi-people"></i> Quản lý học sinh</a></li>
                <li><a href="index.php?act=quanlylop" class="<?php echo ($act == 'quanlylop') ? 'active' : ''; ?>"><i class="bi bi-shop"></i> Quản lý lớp học</a></li>
                <li><a href="index.php?act=quanlytkb" class="<?php echo ($act == 'quanlytkb') ? 'active' : ''; ?>"><i class="bi bi-table"></i> Quản lý thời khóa biểu</a></li>
            <?php endif; ?>

            <?php if ($role_id == 2): ?>
                <div class="menu-title">Chuyên Môn</div>
                <li><a href="index.php?act=phanconggvcn" class="<?php echo ($act == 'phanconggvcn') ? 'active' : ''; ?>"><i class="bi bi-person-badge"></i> Phân công chủ nhiệm</a></li>
                <li><a href="index.php?act=quanlyphancong" class="<?php echo ($act == 'quanlyphancong') ? 'active' : ''; ?>"><i class="bi bi-briefcase"></i> Phân công chuyên môn</a></li>
                <li><a href="index.php?act=thongkebaocao" class="<?php echo ($act == 'thongkebaocao') ? 'active' : ''; ?>"><i class="bi bi-bar-chart-line"></i> Báo cáo thống kê</a></li>
            <?php endif; ?>

            <?php if ($role_id == 3): ?>
                <div class="menu-title">Giảng Dạy</div>
                <li><a href="index.php?act=xemlichday" class="<?php echo ($act == 'xemlichday') ? 'active' : ''; ?>"><i class="bi bi-calendar-check"></i> Xem lịch dạy</a></li>
                <li><a href="index.php?act=xemdanhsachlopday" class="<?php echo ($act == 'xemdanhsachlopday') ? 'active' : ''; ?>"><i class="bi bi-list-task"></i> Xem danh sách lớp dạy</a></li>
                <li><a href="index.php?act=nhapdiem" class="<?php echo ($act == 'nhapdiem') ? 'active' : ''; ?>"><i class="bi bi-pencil-fill"></i> Nhập Điểm</a></li>

                <div class="menu-title">Chủ Nhiệm</div>
                <li><a href="index.php?act=xemlopchunhiem" class="<?php echo ($act == 'xemlopchunhiem') ? 'active' : ''; ?>"><i class="bi bi-person-lines-fill"></i> Lớp Chủ Nhiệm</a></li>
                <li><a href="index.php?act=duyetdon" class="<?php echo ($act == 'duyetdon') ? 'active' : ''; ?>"><i class="bi bi-envelope-paper"></i> Duyệt đơn xin nghĩ</a></li>
                <li><a href="index.php?act=tongket" class="<?php echo ($act == 'tongket') ? 'active' : ''; ?>"><i class="bi bi-award"></i> Tổng kết điểm hạnh kiểm học lực</a></li>

                <div class="menu-title">Cá Nhân</div>
                <li><a href="index.php?act=hosogv" class="<?php echo ($act == 'hosogv') ? 'active' : ''; ?>"><i class="bi bi-person-circle"></i> Hồ Sơ Của Tôi</a></li>
            <?php endif; ?>

            <?php if ($role_id == 4): ?>
                <div class="menu-title">Học Tập</div>
                <li><a href="index.php?act=xemthoikhoabieu" class="<?php echo ($act == 'xemthoikhoabieu') ? 'active' : ''; ?>"><i class="bi bi-calendar-week"></i> Thời Khóa Biểu</a></li>
                <li><a href="index.php?act=xemdiem" class="<?php echo ($act == 'xemdiem') ? 'active' : ''; ?>"><i class="bi bi-journal-bookmark"></i> Kết quả học tập</a></li>
                <li><a href="index.php?act=xinnghi" class="<?php echo ($act == 'xinnghi') ? 'active' : ''; ?>"><i class="bi bi-send"></i> Xin Nghỉ Phép</a></li>
                <li><a href="index.php?act=hosocanhan" class="<?php echo ($act == 'hosocanhan') ? 'active' : ''; ?>"><i class="bi bi-person-vcard"></i> Hồ Sơ Cá Nhân</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <h5 class="mb-0 fw-bold text-secondary text-uppercase">
                    <?php
                    // Hiển thị tên chức năng hiện tại cho đẹp
                    switch ($act) {
                        case 'quanlyhocsinh':
                            echo 'Quản Lý Học Sinh';
                            break;
                        case 'quanlydanhmucgiaovien':
                            echo 'Quản Lý Giáo Viên';
                            break;
                        case 'quanlytkb':
                            echo 'Xếp Thời Khóa Biểu';
                            break;
                        case 'nhapdiem':
                            echo 'Quản Lý Điểm Số';
                            break;
                        default:
                            echo 'Dashboard';
                    }
                    ?>
                </h5>
            </div>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 38px; height: 38px;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span class="fw-bold d-none d-md-block">
                        <?php echo isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : $_SESSION['username']; ?>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item" href="index.php?act=doimatkhau"><i class="bi bi-key me-2"></i> Đổi mật khẩu</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger fw-bold" href="index.php?act=dangxuat"><i class="bi bi-box-arrow-right me-2"></i> Đăng xuất</a></li>
                </ul>
            </div>
        </div>

        <div class="container-fluid p-0">