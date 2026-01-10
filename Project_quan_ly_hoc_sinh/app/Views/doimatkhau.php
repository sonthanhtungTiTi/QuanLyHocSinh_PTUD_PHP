<style>
    /* Card Container */
    .auth-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
    }

    .auth-header {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        padding: 30px 20px;
        text-align: center;
        color: white;
    }

    /* Input Clean */
    .form-floating-custom>.form-control {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding-left: 45px;
        height: 50px;
        font-size: 0.95rem;
    }

    .form-floating-custom>.form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.1rem;
        z-index: 5;
    }

    /* Button */
    .btn-submit {
        background: #4f46e5;
        border: none;
        padding: 12px;
        border-radius: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #4338ca;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">

            <div class="auth-card">
                <div class="auth-header">
                    <div class="bg-white bg-opacity-25 rounded-circle d-inline-flex p-3 mb-3">
                        <i class="bi bi-shield-lock-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-uppercase mb-0">Đổi Mật Khẩu</h5>
                    <p class="small text-white-50 mb-0 mt-1">Bảo mật tài khoản của bạn</p>
                </div>

                <div class="card-body p-4 p-md-5">

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger bg-danger bg-opacity-10 border-danger text-danger border-0 rounded-3 d-flex align-items-center mb-4">
                            <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                            <small class="fw-bold"><?php echo $error; ?></small>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success bg-success bg-opacity-10 border-success text-success border-0 rounded-3 d-flex align-items-center mb-4">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                            <small class="fw-bold"><?php echo $success; ?></small>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">

                        <div class="mb-3 position-relative">
                            <i class="bi bi-key-fill input-icon"></i>
                            <div class="form-floating-custom">
                                <input type="password" name="old_pass" class="form-control" placeholder="Mật khẩu hiện tại" required>
                            </div>
                        </div>

                        <div class="mb-3 position-relative">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <div class="form-floating-custom">
                                <input type="password" name="new_pass" class="form-control" placeholder="Mật khẩu mới" required minlength="6">
                            </div>
                        </div>

                        <div class="mb-4 position-relative">
                            <i class="bi bi-check-circle-fill input-icon"></i>
                            <div class="form-floating-custom">
                                <input type="password" name="confirm_pass" class="form-control" placeholder="Nhập lại mật khẩu mới" required>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" name="btnDoiMatKhau" class="btn btn-primary btn-submit text-white shadow-sm">
                                Cập nhật ngay
                            </button>

                            <a href="index.php" class="btn btn-light text-secondary fw-bold rounded-3">
                                <i class="bi bi-arrow-left me-1"></i> Quay về trang chủ
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        

        </div>
    </div>
</div>