<style>
    /* Card năm học */
    .year-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
    }
    .year-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1) !important;
        border-color: #e2e8f0;
    }
    
    /* Highlight năm đang hoạt động */
    .year-card.active-year {
        border-left: 4px solid #10b981;
        background-color: #f0fdf4;
    }
    
    /* Badge trạng thái */
    .badge-soft-success { background-color: #ecfdf5; color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); }
    .badge-soft-secondary { background-color: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; }
</style>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-calendar-plus-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">Thêm Năm Học</h5>
                        <small class="text-muted">Khởi tạo năm học mới cho hệ thống</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="alert alert-light border-start border-4 border-info bg-info bg-opacity-10 small text-dark mb-4 rounded-3">
                        <i class="bi bi-info-circle-fill me-1 text-info"></i>
                        Hệ thống sẽ tự động tạo tên năm học (VD: 2024-2025) dựa trên năm bắt đầu bạn chọn.
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary text-uppercase">Chọn Năm Bắt Đầu</label>
                        <select name="nam_bat_dau" class="form-select form-select-clean form-select-lg fw-bold text-primary shadow-sm" style="background-color: #f8f9fa; border: 1px solid transparent;">
                            <?php
                            $namHienTai = date('Y');
                            // Hiển thị từ năm ngoái đến 3 năm tới
                            for ($i = $namHienTai - 1; $i <= $namHienTai + 3; $i++) {
                                $namKetThuc = $i + 1;
                                $selected = ($i == $namHienTai) ? 'selected' : '';
                                echo "<option value='$i' $selected>Năm học $i - $namKetThuc</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" name="btnThemNam" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-sm hover-up">
                        <i class="bi bi-plus-lg me-2"></i>  Tạo năm học mới
                    </button>
                </form>
                
                <hr class="my-4 border-light">
                
                <div class="d-flex align-items-start gap-2 text-muted small">
                    <i class="bi bi-exclamation-triangle mt-1"></i>
                    <span><strong>Lưu ý:</strong> Chỉ có duy nhất 1 năm học được phép ở trạng thái <strong>Đang hoạt động</strong> tại một thời điểm.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-light">
            <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-0">Lịch sử năm học</h6>
            </div>
            
            <div class="card-body px-4 pb-4 pt-2">
                <div class="row g-3">
                    <?php if ($listNamHoc && $listNamHoc->num_rows > 0): ?>
                        <?php while ($row = $listNamHoc->fetch_assoc()): ?>
                            <div class="col-12">
                                <div class="card year-card shadow-sm rounded-3 p-3 <?php echo ($row['trang_thai'] == 1) ? 'active-year' : 'bg-white'; ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" 
                                                 style="width: 45px; height: 45px; background: <?php echo ($row['trang_thai'] == 1) ? '#dcfce7' : '#f1f5f9'; ?>;">
                                                <i class="bi bi-calendar-check fs-5 <?php echo ($row['trang_thai'] == 1) ? 'text-success' : 'text-secondary'; ?>"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold text-dark mb-0"><?php echo $row['ten_nam']; ?></h5>
                                                <small class="text-muted">ID: <?php echo $row['id']; ?></small>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-3">
                                            <?php if ($row['trang_thai'] == 1): ?>
                                                <span class="badge badge-soft-success rounded-pill px-3 py-2 fw-normal d-flex align-items-center">
                                                    <span class="spinner-grow spinner-grow-sm me-2" role="status" aria-hidden="true"></span>
                                                    Đang hoạt động
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge-soft-secondary rounded-pill px-3 py-2 fw-normal">
                                                    <i class="bi bi-lock-fill me-1"></i> Đã khóa
                                                </span>
                                                
                                                <form method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="id_active" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" name="btnActive" class="btn btn-sm btn-outline-primary rounded-pill fw-bold px-3 ms-2" title="Kích hoạt năm học này">
                                                        Kích hoạt
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <div class="text-muted opacity-50">
                                <i class="bi bi-calendar-x fs-1"></i>
                                <p class="mt-2">Chưa có dữ liệu năm học.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>