<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0 overflow-hidden rounded-4">
            <div style="height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>

            <div class="card-body p-0 position-relative">
                <div class="row px-4 pb-4">
                    <div class="col-md-3 d-flex flex-column align-items-center" style="margin-top: -75px;">
                        <div class="bg-white p-1 rounded-circle shadow-lg">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold display-4"
                                style="width: 140px; height: 140px;">
                                <?php echo substr($hs['ho_ten'], 0, 1); ?>
                            </div>
                        </div>
                        <h4 class="mt-3 fw-bold text-dark text-center mb-0"><?php echo $hs['ho_ten']; ?></h4>
                        <span class="badge bg-primary bg-opacity-10 text-primary mt-2 px-3 py-2 rounded-pill">
                            <?php echo $hs['ma_hs']; ?>
                        </span>
                    </div>

                    <div class="col-md-9 d-flex align-items-end justify-content-between flex-wrap pt-3 pt-md-0">
                        <div class="d-flex gap-4 mb-3 mb-md-0 text-secondary">
                            <div>
                                <i class="bi bi-cake2 me-1"></i> <?php echo date('d/m/Y', strtotime($hs['ngay_sinh'])); ?>
                            </div>
                            <div>
                                <i class="bi bi-gender-ambiguous me-1"></i> <?php echo $hs['gioi_tinh']; ?>
                            </div>
                            <div>
                                <?php if ($hs['trang_thai'] == 'danghoc'): ?>
                                    <i class="bi bi-circle-fill text-success small me-1"></i> Đang học
                                <?php else: ?>
                                    <i class="bi bi-circle-fill text-danger small me-1"></i> Nghỉ học
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <a href="index.php?act=doimatkhau" class="btn btn-outline-dark rounded-pill px-4 fw-bold">
                                <i class="bi bi-key-fill me-2"></i> Đổi mật khẩu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <h6 class="fw-bold text-uppercase text-muted small spacing-1">Lớp học hiện tại</h6>
            </div>
            <div class="card-body px-4">
                <div class="d-flex align-items-center mb-4 mt-2">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                        <i class="bi bi-backpack-fill fs-3"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Lớp</div>
                        <h3 class="fw-bold text-dark mb-0"><?php echo $hs['ten_lop']; ?></h3>
                    </div>
                </div>

                <hr class="border-light">

                <div class="mb-2">
                    <label class="small text-muted fw-bold mb-1">Giáo viên chủ nhiệm</label>
                    <div class="d-flex align-items-center bg-light p-3 rounded-3">
                        <div class="bg-white rounded-circle p-2 text-primary shadow-sm me-3">
                            <i class="bi bi-person-video3"></i>
                        </div>
                        <div class="fw-bold">
                            <?php echo ($hs['ten_gvcn']) ? $hs['ten_gvcn'] : '<span class="text-muted fw-normal fst-italic">Chưa phân công</span>'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between">
                <h6 class="fw-bold text-uppercase text-muted small spacing-1">Thông tin liên hệ</h6>
            </div>
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item border-0 px-0 py-3">
                        <div class="row">
                            <div class="col-sm-4 text-muted"><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Địa chỉ thường trú</div>
                            <div class="col-sm-8 fw-medium text-dark"><?php echo $hs['dia_chi']; ?></div>
                        </div>
                    </li>
                    <li class="list-group-item border-0 px-0 py-3 bg-light rounded-3 px-3 mt-2">
                        <div class="row align-items-center">
                            <div class="col-sm-12 text-center text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Nếu có sai sót về thông tin cá nhân, vui lòng liên hệ trực tiếp với <strong>Giáo viên chủ nhiệm</strong> để được hỗ trợ điều chỉnh.
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>