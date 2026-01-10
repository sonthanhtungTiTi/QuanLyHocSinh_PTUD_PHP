<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Hệ Thống Quản Lý Trường Học</title>

    <link href="public/vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #2c3e50 0%, #4ca1af 100%);
            /* Màu nền Gradient chuyên nghiệp */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: #fff;
            padding: 40px 30px 20px;
            text-align: center;
        }

        .logo-icon {
            font-size: 3.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .login-body {
            padding: 20px 40px 40px;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 12px 15px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #4ca1af;
            box-shadow: 0 0 0 0.2rem rgba(76, 161, 175, 0.25);
        }

        .btn-login {
            background: #2c3e50;
            border: none;
            padding: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: #34495e;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44, 62, 80, 0.3);
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <h4 class="fw-bold text-uppercase text-dark mb-1">Đăng Nhập</h4>
            <small class="text-muted">Hệ thống quản lý trường học</small>
        </div>

        <div class="login-body">
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger py-2 small d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?act=dangnhap">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Tên đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" id="username" name="username"
                            placeholder="Nhập tài khoản" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control border-start-0 ps-0" id="password" name="password"
                            placeholder="Nhập mật khẩu..." required>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" name="btnDangNhap" class="btn btn-primary btn-login text-uppercase">
                        Đăng nhập hệ thống
                    </button>
                </div>
            </form>
        </div>
        <div class="text-center bg-light py-3 border-top">
            <small class="text-muted" style="font-size: 0.75rem;">&copy;Sinh viên vip pro - 2025 School Management System</small>
        </div>
    </div>

</body>

</html>