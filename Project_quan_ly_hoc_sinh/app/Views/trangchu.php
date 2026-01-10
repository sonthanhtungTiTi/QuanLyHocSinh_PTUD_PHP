<?php
// 1. Gọi các Model cần thiết để lấy số liệu thống kê nhanh (nếu là Admin/BGH)
require_once "app/Models/mNamHoc.php";
require_once "app/Models/mThongKe.php";

$mNam = new mNamHoc();
$mThongKe = new mThongKe();

$namHienTaiId = $mNam->getNamHocHienTai();
$role_id = $_SESSION['role_id'];
$user_fullname = isset($_SESSION['user_fullname']) ? $_SESSION['user_fullname'] : $_SESSION['username'];

// Chỉ lấy số liệu thống kê nếu là Admin (1) hoặc BGH (2)
$statData = null;
if ($role_id == 1 || $role_id == 2) {
    $statData = $mThongKe->getTongSoLuong($namHienTaiId);
}
?>

<style>
    /* Banner chào mừng */
    .welcome-banner {
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
        /* Màu tối sang trọng đồng bộ sidebar */
        border-radius: 16px;
        color: white;
        padding: 30px;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .welcome-banner::after {
        content: "";
        position: absolute;
        right: -20px;
        bottom: -50px;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    /* Card chức năng (Dashboard Item) */
    .dash-item {
        display: block;
        background: #fff;
        border-radius: 16px;
        padding: 25px 20px;
        text-decoration: none;
        color: #475569;
        border: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .dash-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #e2e8f0;
        color: #2563eb;
        /* Màu xanh active */
    }

    /* Icon trong card */
    .dash-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
        transition: all 0.3s;
    }

    .dash-item:hover .dash-icon {
        transform: scale(1.1);
    }

    /* Màu sắc riêng cho từng nhóm chức năng */
    .bg-soft-primary {
        background: #eff6ff;
        color: #3b82f6;
    }

    .bg-soft-success {
        background: #ecfdf5;
        color: #10b981;
    }

    .bg-soft-warning {
        background: #fffbeb;
        color: #f59e0b;
    }

    .bg-soft-danger {
        background: #fef2f2;
        color: #ef4444;
    }

    .bg-soft-info {
        background: #f0f9ff;
        color: #0ea5e9;
    }

    .bg-soft-purple {
        background: #f3e8ff;
        color: #a855f7;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="welcome-banner d-flex align-items-center justify-content-between">
            <div>
                <h3 class="fw-bold mb-1">Xin chào, <?php echo $user_fullname; ?>! 👋</h3>
                <p class="mb-0 opacity-75">
                    Hôm nay là <?php echo "Thứ " . (date('N') + 1) . ", ngày " . date('d/m/Y'); ?>.
                    Chúc bạn một ngày làm việc hiệu quả.
                </p>
            </div>
            <?php if (($role_id == 1 || $role_id == 2) && $statData): ?>
                <div class="d-none d-lg-flex gap-4 border-start border-light border-opacity-25 ps-4 ms-4">
                    <div class="text-center">
                        <h3 class="fw-bold mb-0"><?php echo $statData['hs']; ?></h3>
                        <small class="opacity-75">Học sinh</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold mb-0"><?php echo $statData['gv']; ?></h3>
                        <small class="opacity-75">Giáo viên</small>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold mb-0"><?php echo $statData['lop']; ?></h3>
                        <small class="opacity-75">Lớp học</small>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row g-4">

    <?php if ($role_id == 1): // === ADMIN (QUẢN TRỊ VIÊN) === 
    ?>

        <div class="col-12">
            <h6 class="text-uppercase text-secondary fw-bold small ls-1">Hệ Thống</h6>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=quanlynamhoc" class="dash-item">
                <div class="dash-icon bg-soft-primary"><i class="bi bi-calendar-event"></i></div>
                <h5 class="fw-bold mb-1">Năm Học</h5>
                <small class="text-muted">Cấu hình niên khóa</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=quanlydanhmucmonhoc" class="dash-item">
                <div class="dash-icon bg-soft-primary"><i class="bi bi-book"></i></div>
                <h5 class="fw-bold mb-1">Môn Học</h5>
                <small class="text-muted">Quản lý danh mục môn</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=quanlytaikhoan" class="dash-item">
                <div class="dash-icon bg-soft-danger"><i class="bi bi-shield-lock"></i></div>
                <h5 class="fw-bold mb-1">Tài Khoản</h5>
                <small class="text-muted">Cấp quyền người dùng</small>
            </a>
        </div>

        <div class="col-12 mt-4">
            <h6 class="text-uppercase text-secondary fw-bold small ls-1">Quản Lý Danh Mục</h6>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=quanlydanhmucgiaovien" class="dash-item">
                <div class="dash-icon bg-soft-success"><i class="bi bi-person-video3"></i></div>
                <h5 class="fw-bold mb-1">Giáo Viên</h5>
                <small class="text-muted">Hồ sơ nhân sự</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=quanlyhocsinh" class="dash-item">
                <div class="dash-icon bg-soft-success"><i class="bi bi-people"></i></div>
                <h5 class="fw-bold mb-1">Học Sinh</h5>
                <small class="text-muted">Hồ sơ & Tra cứu</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=quanlylop" class="dash-item">
                <div class="dash-icon bg-soft-warning"><i class="bi bi-shop"></i></div>
                <h5 class="fw-bold mb-1">Lớp Học</h5>
                <small class="text-muted">Danh sách lớp</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=quanlytkb" class="dash-item">
                <div class="dash-icon bg-soft-purple"><i class="bi bi-table"></i></div>
                <h5 class="fw-bold mb-1">Thời Khóa Biểu</h5>
                <small class="text-muted">Xếp lịch học tập</small>
            </a>
        </div>

    <?php elseif ($role_id == 2): // === BAN GIÁM HIỆU === 
    ?>

        <div class="col-12">
            <h6 class="text-uppercase text-secondary fw-bold small ls-1">Chức năng quản lý</h6>
        </div>

        <div class="col-md-4">
            <a href="index.php?act=phanconggvcn" class="dash-item">
                <div class="dash-icon bg-soft-warning"><i class="bi bi-person-badge"></i></div>
                <h5 class="fw-bold mb-1">Phân Công Chủ Nhiệm</h5>
                <small class="text-muted">Gán GVCN cho các lớp</small>
            </a>
        </div>
        <div class="col-md-4">
            <a href="index.php?act=quanlyphancong" class="dash-item">
                <div class="dash-icon bg-soft-primary"><i class="bi bi-briefcase"></i></div>
                <h5 class="fw-bold mb-1">Phân Công Giảng Dạy</h5>
                <small class="text-muted">Phân công chuyên môn</small>
            </a>
        </div>
        <div class="col-md-4">
            <a href="index.php?act=thongke" class="dash-item">
                <div class="dash-icon bg-soft-danger"><i class="bi bi-bar-chart-line"></i></div>
                <h5 class="fw-bold mb-1">Báo Cáo Thống Kê</h5>
                <small class="text-muted">Biểu đồ & Số liệu</small>
            </a>
        </div>

    <?php elseif ($role_id == 3): // === GIÁO VIÊN === 
    ?>

        <div class="col-12">
            <h6 class="text-uppercase text-secondary fw-bold small ls-1">Hoạt động giảng dạy</h6>
        </div>

        <div class="col-6 col-md-4">
            <a href="index.php?act=xemlichday" class="dash-item">
                <div class="dash-icon bg-soft-primary"><i class="bi bi-calendar-check"></i></div>
                <h5 class="fw-bold mb-1">Lịch Dạy</h5>
                <small class="text-muted">Xem TKB cá nhân</small>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="index.php?act=xemdanhsachlopday" class="dash-item">
                <div class="dash-icon bg-soft-info"><i class="bi bi-list-task"></i></div>
                <h5 class="fw-bold mb-1">DS Lớp Dạy</h5>
                <small class="text-muted">Các lớp được phân công</small>
            </a>
        </div>
        <div class="col-6 col-md-4">
            <a href="index.php?act=nhapdiem" class="dash-item">
                <div class="dash-icon bg-soft-success"><i class="bi bi-pencil-fill"></i></div>
                <h5 class="fw-bold mb-1">Nhập Điểm</h5>
                <small class="text-muted">Quản lý điểm số</small>
            </a>
        </div>

        <div class="col-12 mt-4">
            <h6 class="text-uppercase text-secondary fw-bold small ls-1">Công tác chủ nhiệm</h6>
        </div>

        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=xemlopchunhiem" class="dash-item">
                <div class="dash-icon bg-soft-warning"><i class="bi bi-person-lines-fill"></i></div>
                <h5 class="fw-bold mb-1">Lớp Chủ Nhiệm</h5>
                <small class="text-muted">Thông tin học sinh</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=duyetdon" class="dash-item">
                <div class="dash-icon bg-soft-danger"><i class="bi bi-envelope-paper"></i></div>
                <h5 class="fw-bold mb-1">Duyệt Đơn Nghỉ</h5>
                <small class="text-muted">Xử lý phép tắc</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=tongket" class="dash-item">
                <div class="dash-icon bg-soft-purple"><i class="bi bi-award"></i></div>
                <h5 class="fw-bold mb-1">Tổng Kết</h5>
                <small class="text-muted">Hạnh kiểm & Danh hiệu</small>
            </a>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <a href="index.php?act=hosogv" class="dash-item">
                <div class="dash-icon bg-soft-info"><i class="bi bi-person-circle"></i></div>
                <h5 class="fw-bold mb-1">Hồ Sơ Của Tôi</h5>
                <small class="text-muted">Thông tin cá nhân</small>
            </a>
        </div>

    <?php elseif ($role_id == 4): // === HỌC SINH === 
    ?>

        <div class="col-12">
            <h6 class="text-uppercase text-secondary fw-bold small ls-1">Góc học tập</h6>
        </div>

        <div class="col-6 col-md-6 col-xl-3">
            <a href="index.php?act=xemthoikhoabieu" class="dash-item">
                <div class="dash-icon bg-soft-primary"><i class="bi bi-calendar-week"></i></div>
                <h5 class="fw-bold mb-1">Thời Khóa Biểu</h5>
                <small class="text-muted">Lịch học trong tuần</small>
            </a>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <a href="index.php?act=xemdiem" class="dash-item">
                <div class="dash-icon bg-soft-success"><i class="bi bi-journal-bookmark"></i></div>
                <h5 class="fw-bold mb-1">Xem Điểm</h5>
                <small class="text-muted">Kết quả học tập</small>
            </a>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <a href="index.php?act=xinnghi" class="dash-item">
                <div class="dash-icon bg-soft-warning"><i class="bi bi-send"></i></div>
                <h5 class="fw-bold mb-1">Xin Nghỉ Phép</h5>
                <small class="text-muted">Gửi đơn trực tuyến</small>
            </a>
        </div>
        <div class="col-6 col-md-6 col-xl-3">
            <a href="index.php?act=hosocanhan" class="dash-item">
                <div class="dash-icon bg-soft-info"><i class="bi bi-person-vcard"></i></div>
                <h5 class="fw-bold mb-1">Hồ Sơ Cá Nhân</h5>
                <small class="text-muted">Thông tin liên hệ</small>
            </a>
        </div>

    <?php endif; ?>

</div>