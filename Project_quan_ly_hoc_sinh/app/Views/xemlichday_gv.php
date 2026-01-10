<style>
    /* --- BỐ CỤC TOÀN MÀN HÌNH (NO SCROLL) --- */
    .compact-container {
        height: 85vh;
        /* Chiều cao cố định để vừa màn hình laptop */
        display: flex;
        flex-direction: column;
        overflow: hidden;
        /* Chặn thanh cuộn */
    }

    /* Header nhỏ gọn */
    .compact-header {
        flex: 0 0 auto;
        padding-bottom: 10px;
    }

    /* Phần bao quanh bảng */
    .table-wrapper {
        flex: 1 1 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        position: relative;
    }

    /* Bảng ép full chiều cao */
    .table-fit {
        width: 100%;
        height: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        /* Các cột bằng nhau */
    }

    /* Header Bảng */
    .table-fit th {
        background-color: #f8fafc;
        color: #64748b;
        font-size: 0.8rem;
        text-transform: uppercase;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px solid #f1f5f9;
        height: 45px;
    }

    /* Ô dữ liệu */
    .table-fit td {
        border-right: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        text-align: center;
        vertical-align: middle;
        padding: 0;
        height: 9%;
        /* Chia đều cho ~11 dòng (10 tiết + header) */
        transition: background 0.2s;
    }

    /* Cột Tiết */
    .col-tiet {
        width: 60px;
        font-weight: 700;
        color: #94a3b8;
        background-color: #fff;
        font-size: 0.85rem;
        border-right: 2px solid #e2e8f0 !important;
    }

    /* Dòng ngăn cách Sáng/Chiều (Mảnh) */
    .row-separator td {
        height: 25px !important;
        background-color: #fff7ed !important;
        /* Cam rất nhạt */
        color: #c2410c;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        border-top: 1px solid #ffedd5;
        border-bottom: 1px solid #ffedd5;
    }

    /* --- TRẠNG THÁI Ô --- */
    /* Ô có lịch dạy */
    .cell-active {
        background-color: #f0f9ff;
        cursor: default;
    }

    .cell-active:hover {
        background-color: #e0f2fe;
    }

    .class-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        color: #0284c7;
        font-weight: 700;
        font-size: 0.95rem;
        border: 1px solid #bae6fd;
        border-radius: 8px;
        padding: 6px 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    }

    /* Ô trống */
    .cell-empty {
        color: #e2e8f0;
        font-size: 1.5rem;
        font-weight: 300;
    }

    .cell-empty:hover {
        background-color: #fcfcfc;
    }

    /* Avatar nhỏ trên Header */
    .avatar-mini {
        width: 35px;
        height: 35px;
        background: #6366f1;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        margin-right: 10px;
    }
</style>

<div class="container-fluid mt-3 compact-container">

    <div class="compact-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="avatar-mini shadow-sm">
                <?php echo substr($gv['ho_ten'], 0, 1); ?>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0 text-uppercase" style="letter-spacing: 0.5px;">Lịch Giảng Dạy</h5>
                <small class="text-muted">Giáo viên: <strong><?php echo $gv['ho_ten']; ?></strong></small>
            </div>
        </div>

        <div class="bg-white p-1 rounded-pill border shadow-sm">
            <a href="index.php?act=xemlichday&hk=HK1"
                class="btn btn-sm rounded-pill px-3 fw-bold <?php echo ($selectedHK == 'HK1') ? 'btn-primary' : 'text-muted bg-transparent'; ?>">
                Học Kỳ 1
            </a>
            <a href="index.php?act=xemlichday&hk=HK2"
                class="btn btn-sm rounded-pill px-3 fw-bold <?php echo ($selectedHK == 'HK2') ? 'btn-primary' : 'text-muted bg-transparent'; ?>">
                Học Kỳ 2
            </a>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table-fit">
            <thead>
                <tr>
                    <th style="width: 60px;">Tiết</th>
                    <?php for ($t = 2; $t <= 7; $t++): ?>
                        <th class="<?php echo ($t == 7) ? 'text-danger' : ''; ?>">Thứ <?php echo $t; ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>

                <?php for ($tiet = 1; $tiet <= 5; $tiet++): ?>
                    <tr>
                        <td class="col-tiet"><?php echo $tiet; ?></td>
                        <?php for ($thu = 2; $thu <= 7; $thu++):
                            $tenLop = isset($lichDayData[$thu][$tiet]) ? $lichDayData[$thu][$tiet] : '';
                        ?>
                            <td class="<?php echo ($tenLop) ? 'cell-active' : 'cell-empty'; ?>">
                                <?php if ($tenLop): ?>
                                    <span class="class-badge">Lớp <?php echo $tenLop; ?></span>
                                <?php else: ?>
                                    +
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>

                <tr class="row-separator">
                    <td colspan="7">
                        <i class="bi bi-sun-fill text-warning me-2"></i>Buổi chiều <i class="bi bi-moon-stars-fill text-primary ms-2"></i>
                    </td>
                </tr>

                <?php for ($tiet = 6; $tiet <= 10; $tiet++): ?>
                    <tr>
                        <td class="col-tiet"><?php echo $tiet; ?></td>
                        <?php for ($thu = 2; $thu <= 7; $thu++):
                            $tenLop = isset($lichDayData[$thu][$tiet]) ? $lichDayData[$thu][$tiet] : '';
                        ?>
                            <td class="<?php echo ($tenLop) ? 'cell-active' : 'cell-empty'; ?>">
                                <?php if ($tenLop): ?>
                                    <span class="class-badge">Lớp <?php echo $tenLop; ?></span>
                                <?php else: ?>
                                    +
                                <?php endif; ?>
                            </td>
                        <?php endfor; ?>
                    </tr>
                <?php endfor; ?>

            </tbody>
        </table>
    </div>
</div>