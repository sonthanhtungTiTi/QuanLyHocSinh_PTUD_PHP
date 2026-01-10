<style>
    /* Card chính */
    .schedule-card {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #fff;
    }

    /* Header thông tin */
    .student-info-bar {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 20px 25px;
        border-bottom: 1px solid #bae6fd;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    /* Bảng TKB */
    .table-schedule {
        width: 100%;
        border-collapse: collapse;
    }

    .table-schedule th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        text-align: center;
        padding: 12px;
        border-bottom: 2px solid #e2e8f0;
        border-right: 1px solid #f1f5f9;
    }

    .table-schedule td {
        border-right: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        text-align: center;
        vertical-align: middle;
        padding: 8px;
        height: 80px;
        /* Chiều cao cố định cho ô đẹp hơn */
        width: 16%;
        /* Chia đều 6 cột (1 cột tiết + 5 cột thứ) */
    }

    /* Cột Tiết */
    .col-period {
        width: 60px;
        font-weight: 800;
        color: #94a3b8;
        background-color: #fff;
        border-right: 2px solid #e2e8f0 !important;
        font-size: 0.85rem;
    }

    /* Ô môn học */
    .subject-box {
        background-color: #fff;
        border-radius: 8px;
        padding: 5px;
        transition: all 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .subject-box:hover {
        background-color: #f0f9ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .subject-name {
        font-weight: 700;
        color: #0369a1;
        font-size: 0.95rem;
        margin-bottom: 4px;
        display: block;
    }

    .teacher-name {
        font-size: 0.75rem;
        color: #64748b;
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 12px;
        display: inline-block;
    }

    /* Dòng ngăn cách buổi */
    .row-separator td {
        background-color: #fff7ed !important;
        /* Cam nhạt */
        color: #c2410c;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        height: 30px !important;
        padding: 0 !important;
        border-top: 1px solid #ffedd5;
        border-bottom: 1px solid #ffedd5;
    }

    /* Button HK */
    .btn-hk {
        background: white;
        border: 1px solid #bae6fd;
        color: #0ea5e9;
        font-weight: 600;
        border-radius: 20px;
        padding: 5px 20px;
        transition: all 0.2s;
    }

    .btn-hk.active {
        background: #0ea5e9;
        color: white;
        box-shadow: 0 4px 10px rgba(14, 165, 233, 0.3);
        border-color: #0ea5e9;
    }

    .btn-hk:hover:not(.active) {
        background: #e0f2fe;
    }
</style>

<div class="container mt-4 mb-5">

    <div class="schedule-card">
        <div class="student-info-bar">
            <div class="d-flex align-items-center">
                <div class="bg-white p-3 rounded-circle shadow-sm me-3 text-primary">
                    <i class="bi bi-calendar-date fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-1 text-uppercase">Thời Khóa Biểu</h5>
                    <div class="text-muted small">
                        Học sinh: <strong class="text-dark"><?php echo $hs['ho_ten']; ?></strong>
                        <span class="mx-2">•</span>
                        Lớp: <strong class="text-dark"><?php echo $hs['ten_lop']; ?></strong>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="index.php?act=xemthoikhoabieu&hk=HK1" class="btn btn-sm btn-hk <?php echo ($selectedHK == 'HK1') ? 'active' : ''; ?>">Học Kỳ 1</a>
                <a href="index.php?act=xemthoikhoabieu&hk=HK2" class="btn btn-sm btn-hk <?php echo ($selectedHK == 'HK2') ? 'active' : ''; ?>">Học Kỳ 2</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-schedule">
                <thead>
                    <tr>
                        <th width="60px">Tiết</th>
                        <?php for ($t = 2; $t <= 6; $t++): ?>
                            <th>Thứ <?php echo $t; ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Vòng lặp 10 tiết
                    for ($tiet = 1; $tiet <= 10; $tiet++):

                        // Dòng ngăn cách buổi chiều
                        if ($tiet == 6):
                    ?>
                            <tr class="row-separator">
                                <td colspan="6">
                                    <i class="bi bi-sun-fill me-2 text-warning"></i> Buổi Chiều <i class="bi bi-moon-stars-fill ms-2 text-primary"></i>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td class="col-period"><?php echo $tiet; ?></td>

                            <?php for ($thu = 2; $thu <= 6; $thu++):
                                // Lấy dữ liệu ô này
                                $cellData = isset($tkbData[$thu][$tiet]) ? $tkbData[$thu][$tiet] : null;
                            ?>
                                <td>
                                    <?php if ($cellData): ?>
                                        <div class="subject-box">
                                            <span class="subject-name"><?php echo $cellData['ten_mon']; ?></span>

                                            <?php if ($cellData['ten_gv']): ?>
                                                <div>
                                                    <span class="teacher-name text-truncate" style="max-width: 120px;" title="<?php echo $cellData['ten_gv']; ?>">
                                                        <?php echo $cellData['ten_gv']; ?>
                                                    </span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted opacity-25 fw-light" style="font-size: 1.5rem;">+</span>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($tkbData)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 opacity-25 mb-2 d-block"></i>
                <em>Chưa có dữ liệu thời khóa biểu cho học kỳ này.</em>
            </div>
        <?php endif; ?>
    </div>
</div>