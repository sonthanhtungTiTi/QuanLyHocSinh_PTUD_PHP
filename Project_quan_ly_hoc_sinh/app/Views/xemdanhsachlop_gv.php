<style>
    /* Card lớp học */
    .class-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        border-color: #e2e8f0;
    }

    /* Dải màu bên trái card để phân biệt khối */
    .border-left-10 { border-left: 5px solid #0ea5e9; } /* Khối 10: Xanh dương */
    .border-left-11 { border-left: 5px solid #f59e0b; } /* Khối 11: Vàng cam */
    .border-left-12 { border-left: 5px solid #ef4444; } /* Khối 12: Đỏ */

    /* Badge môn học */
    .badge-subject {
        background-color: #eff6ff;
        color: #1d4ed8;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    /* Nút hành động */
    .btn-action-group {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    .btn-action {
        flex: 1;
        border-radius: 8px;
        font-weight: 600;
        padding: 8px;
        font-size: 0.9rem;
    }
</style>

<div class="container mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1">
                <i class="bi bi-journal-bookmark-fill me-2"></i>Lớp Giảng Dạy
            </h4>
            <p class="text-muted small mb-0">Danh sách các lớp được phân công trong năm học hiện tại.</p>
        </div>
        
        <div class="bg-white p-1 rounded-pill shadow-sm border d-none d-md-flex">
            <span class="px-3 py-1 text-muted small fw-bold">Tổng số: <?php echo ($dsLop) ? $dsLop->num_rows : 0; ?> lớp</span>
        </div>
    </div>

    <div class="row g-4">
        <?php if ($dsLop && $dsLop->num_rows > 0): 
            $dsLop->data_seek(0);
            while ($row = $dsLop->fetch_assoc()):
                // Xác định màu sắc theo khối
                $khoiClass = 'border-left-10'; // Mặc định
                if (strpos($row['ten_khoi'], '11') !== false) $khoiClass = 'border-left-11';
                if (strpos($row['ten_khoi'], '12') !== false) $khoiClass = 'border-left-12';
        ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="class-card <?php echo $khoiClass; ?> p-4">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="text-uppercase text-muted small fw-bold tracking-wide">
                                <?php echo $row['ten_khoi']; ?>
                            </span>
                            <h3 class="fw-bold text-dark mb-0 mt-1">Lớp <?php echo $row['ten_lop']; ?></h3>
                        </div>
                        <div class="bg-light rounded-circle p-2 text-secondary">
                            <i class="bi bi-people-fill fs-5"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="badge-subject">
                            <i class="bi bi-book me-1"></i> <?php echo $row['ten_mon']; ?>
                        </span>
                    </div>

                    <div class="btn-action-group">
                        <a href="index.php?act=nhapdiem&lop_id=<?php echo $row['lop_id']; ?>&mon_id=<?php echo $row['mon_hoc_id']; ?>" 
                           class="btn btn-primary btn-action shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> Nhập điểm
                        </a>
                        <a href="index.php?act=xembangdiem&lop_id=<?php echo $row['lop_id']; ?>&mon_id=<?php echo $row['mon_hoc_id']; ?>&ten_lop=<?php echo urlencode($row['ten_lop']); ?>" 
                           class="btn btn-outline-info btn-action shadow-sm text-info hover-text-white">
                            <i class="bi bi-eye me-1"></i> Xem
                        </a>
                    </div>

                </div>
            </div>
        <?php endwhile; else: ?>
            
            <div class="col-12">
                <div class="text-center py-5 bg-white rounded-4 shadow-sm border border-dashed">
                    <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                        <i class="bi bi-calendar-x fs-1 text-muted opacity-50"></i>
                    </div>
                    <h5 class="fw-bold text-secondary">Chưa có lớp phân công</h5>
                    <p class="text-muted small mb-0">Hiện tại bạn chưa được phân công giảng dạy lớp nào.</p>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>