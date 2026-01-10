<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden text-center h-100">
            <div class="bg-dark pt-5 pb-5 position-relative" style="background: linear-gradient(to right, #2b5876, #4e4376);">
                <div class="position-absolute top-0 end-0 m-3 text-white opacity-50">
                    <i class="bi bi-qr-code-scan fs-3"></i>
                </div>
            </div>

            <div class="position-relative" style="margin-top: -60px;">
                <div class="d-inline-block p-1 bg-white rounded-circle shadow-lg">
                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 120px; height: 120px; font-size: 3rem;">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
            </div>

            <div class="card-body pt-3 pb-4">
                <h4 class="fw-bold mb-1"><?php echo $gv['ho_ten']; ?></h4>
                <div class="text-muted small mb-3"><?php echo $gv['ma_gv']; ?></div>

                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-4">
                    <i class="bi bi-briefcase-fill me-1"></i> Giáo Viên
                </span>

                <div class="d-flex justify-content-center gap-2 mb-4">
                    <div class="text-start px-4 py-2 bg-light rounded-3 w-100 mx-3">
                        <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">Chuyên môn</small>
                        <div class="fw-bold text-dark d-flex align-items-center mt-1">
                            <i class="bi bi-book-half text-warning me-2"></i>
                            <?php echo ($gv['ten_mon']) ? $gv['ten_mon'] : "Chưa phân tổ"; ?>
                        </div>
                    </div>
                </div>

                <div class="border-top pt-3 mx-3">
                    <div class="row text-center">
                        <div class="col">
                            <small class="text-muted d-block">Ngày sinh</small>
                            <span class="fw-bold text-dark"><?php echo date('d/m/Y', strtotime($gv['ngay_sinh'])); ?></span>
                        </div>
                        <div class="col border-start">
                            <small class="text-muted d-block">Trạng thái</small>
                            <span class="fw-bold text-success">Hoạt động</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-sliders me-2 text-primary"></i> Cập nhật thông tin</h5>
                <small class="text-muted">Quản lý thông tin liên lạc cá nhân của bạn</small>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="">

                    <div class="alert alert-primary bg-primary bg-opacity-10 border-0 d-flex align-items-center rounded-3 mb-4" role="alert">
                        <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                        <div class="small text-dark opacity-75">
                            Hệ thống chỉ cho phép giáo viên tự cập nhật <strong>SĐT, Email và Địa chỉ</strong>. Các thông tin hành chính khác vui lòng liên hệ Admin.
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Số điện thoại</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted fs-6"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="sdt" class="form-control bg-light border-start-0 fs-6"
                                    value="<?php echo $gv['sdt']; ?>" placeholder="Nhập SĐT..." required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Email cá nhân</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-end-0 text-muted fs-6"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 fs-6"
                                    value="<?php echo $gv['email']; ?>" placeholder="name@example.com" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold text-secondary small text-uppercase">Địa chỉ liên hệ</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted pt-2"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="dia_chi" class="form-control bg-light border-start-0" rows="3" required><?php echo $gv['dia_chi']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4 border-light">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="index.php?act=doimatkhau" class="text-decoration-none fw-bold text-secondary">
                            <i class="bi bi-shield-lock me-1"></i> Đổi mật khẩu đăng nhập
                        </a>
                        <button type="submit" name="btnCapNhat" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
                            <i class="bi bi-check2-all me-2"></i> Lưu Thay Đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>