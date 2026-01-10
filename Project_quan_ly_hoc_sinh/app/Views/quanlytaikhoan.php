<style>
    /* Custom Badges cho Vai trò */
    .badge-role-admin {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Đỏ nhạt */
    .badge-role-bgh {
        background-color: #ffedd5;
        color: #9a3412;
        border: 1px solid #fed7aa;
    }

    /* Cam nhạt */
    .badge-role-gv {
        background-color: #e0f2fe;
        color: #075985;
        border: 1px solid #bae6fd;
    }

    /* Xanh dương nhạt */
    .badge-role-hs {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* Xanh lá nhạt */

    /* Input Clean */
    .form-control-clean,
    .form-select-clean {
        background-color: #f8f9fa;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.7rem 1rem;
        transition: all 0.2s;
    }

    .form-control-clean:focus,
    .form-select-clean:focus {
        background-color: #fff;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }
</style>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                            <i class="bi bi-shield-lock-fill fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold text-dark mb-1">Quản Lý Tài Khoản</h4>
                            <small class="text-muted">Quản lý người dùng, phân quyền và trạng thái hoạt động</small>
                        </div>
                    </div>

                    <button class="btn btn-primary rounded-pill fw-bold shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#modalAddAdmin">
                        <i class="bi bi-person-plus-fill me-2"></i> Thêm Admin/BGH
                    </button>
                </div>

                <div class="bg-light p-3 rounded-4">
                    <form method="GET" action="" class="row g-2 align-items-center">
                        <input type="hidden" name="act" value="quanlytaikhoan">

                        <div class="col-md-5">
                            <div class="position-relative">
                                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control form-control-clean ps-5 rounded-pill"
                                    placeholder="Tìm theo tên đăng nhập..."
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <select name="role" class="form-select form-select-clean rounded-pill">
                                <option value="">-- Tất cả vai trò --</option>
                                <option value="1" <?php echo (isset($_GET['role']) && $_GET['role'] == 1) ? 'selected' : ''; ?>>Quản trị viên (Admin)</option>
                                <option value="2" <?php echo (isset($_GET['role']) && $_GET['role'] == 2) ? 'selected' : ''; ?>>Ban Giám Hiệu (BGH)</option>
                                <option value="3" <?php echo (isset($_GET['role']) && $_GET['role'] == 3) ? 'selected' : ''; ?>>Giáo viên</option>
                                <option value="4" <?php echo (isset($_GET['role']) && $_GET['role'] == 4) ? 'selected' : ''; ?>>Học sinh</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-dark w-100 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-funnel-fill me-1"></i> Lọc dữ liệu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-0">Danh sách tài khoản hệ thống</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th width="5%" class="text-center py-3">ID</th>
                            <th width="20%">Tên đăng nhập</th>
                            <th width="25%">Họ tên người dùng</th>
                            <th width="15%" class="text-center">Vai trò</th>
                            <th width="15%" class="text-center">Trạng thái</th>
                            <th width="20%" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($dsTK && $dsTK->num_rows > 0):
                            while ($row = $dsTK->fetch_assoc()):
                        ?>
                                <tr>
                                    <td class="text-center text-muted fw-bold"><?php echo $row['id']; ?></td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-2 text-secondary">
                                                <i class="bi bi-person-badge"></i>
                                            </div>
                                            <span class="fw-bold text-primary font-monospace"><?php echo $row['username']; ?></span>
                                        </div>
                                    </td>

                                    <td>
                                        <?php if ($row['ho_ten_that']): ?>
                                            <span class="fw-bold text-dark"><?php echo $row['ho_ten_that']; ?></span>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic small bg-light px-2 py-1 rounded">-- Chưa cập nhật --</span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <?php
                                        switch ($row['vai_tro_id']) {
                                            case 1:
                                                echo '<span class="badge badge-role-admin rounded-pill px-3 py-2">Admin</span>';
                                                break;
                                            case 2:
                                                echo '<span class="badge badge-role-bgh rounded-pill px-3 py-2">BGH</span>';
                                                break;
                                            case 3:
                                                echo '<span class="badge badge-role-gv rounded-pill px-3 py-2">Giáo viên</span>';
                                                break;
                                            case 4:
                                                echo '<span class="badge badge-role-hs rounded-pill px-3 py-2">Học sinh</span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-secondary rounded-pill px-3 py-2">Khác</span>';
                                        }
                                        ?>
                                    </td>

                                    <td class="text-center">
                                        <?php if ($row['trang_thai'] == 1): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3">
                                                <i class="bi bi-dot"></i> Hoạt động
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-3">
                                                <i class="bi bi-lock-fill"></i> Đã khóa
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <form method="POST">
                                                <input type="hidden" name="tk_id" value="<?php echo $row['id']; ?>">
                                                <input type="hidden" name="current_status" value="<?php echo $row['trang_thai']; ?>">

                                                <?php if ($row['trang_thai'] == 1): ?>
                                                    <button type="submit" name="btnToggle" class="btn btn-sm btn-light text-secondary rounded-circle shadow-sm"
                                                        title="Khóa tài khoản" onclick="return confirm('Khóa tài khoản [<?php echo $row['username']; ?>]?');">
                                                        <i class="bi bi-lock"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="submit" name="btnToggle" class="btn btn-sm btn-success rounded-circle shadow-sm"
                                                        title="Mở khóa tài khoản" onclick="return confirm('Mở khóa cho [<?php echo $row['username']; ?>]?');">
                                                        <i class="bi bi-unlock-fill"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </form>

                                            <button class="btn btn-sm btn-warning rounded-pill shadow-sm fw-bold text-dark px-3"
                                                data-bs-toggle="modal" data-bs-target="#modalReset<?php echo $row['id']; ?>"
                                                title="Cấp lại mật khẩu">
                                                <i class="bi bi-key-fill"></i> Reset
                                            </button>
                                        </div>

                                        <div class="modal fade" id="modalReset<?php echo $row['id']; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow-lg rounded-4">
                                                    <div class="modal-header bg-warning border-0">
                                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-arrow-counterclockwise"></i> Cấp lại mật khẩu</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="modal-body text-start p-4">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <div class="bg-light p-2 rounded me-3"><i class="bi bi-person-fill text-muted fs-4"></i></div>
                                                                <div>
                                                                    <small class="text-muted text-uppercase fw-bold">Tài khoản</small>
                                                                    <div class="fs-5 fw-bold text-primary"><?php echo $row['username']; ?></div>
                                                                </div>
                                                            </div>

                                                            <label class="form-label fw-bold">Mật khẩu mới:</label>
                                                            <input type="text" name="new_pass" class="form-control form-control-clean font-monospace text-center fs-5 text-danger" placeholder="123456">
                                                            <div class="form-text text-center mt-2"><i class="bi bi-info-circle"></i> Để trống sẽ mặc định là <b>123456</b></div>
                                                            <input type="hidden" name="tk_id" value="<?php echo $row['id']; ?>">
                                                        </div>
                                                        <div class="modal-footer border-0 bg-light rounded-bottom-4">
                                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Đóng</button>
                                                            <button type="submit" name="btnResetPass" class="btn btn-warning rounded-pill fw-bold px-4 shadow-sm">Xác nhận Đổi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile;
                        else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted opacity-50">
                                        <i class="bi bi-search fs-1"></i>
                                        <p class="mt-2">Không tìm thấy tài khoản nào phù hợp.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAddAdmin" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-shield-plus"></i> Thêm Quản Trị Viên</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <div class="alert alert-primary bg-primary bg-opacity-10 border-0 small text-dark mb-4 rounded-3">
                        <i class="bi bi-info-circle-fill text-primary me-1"></i>
                        Chức năng này chỉ dùng để tạo <strong>Admin</strong> hoặc <strong>BGH</strong>. <br>
                        Giáo viên & Học sinh vui lòng tạo ở menu quản lý tương ứng.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary text-uppercase">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control form-control-clean" required placeholder="VD: admin2, hieutruong...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary text-uppercase">Mật khẩu</label>
                        <input type="password" name="password" class="form-control form-control-clean" required placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary text-uppercase">Phân quyền</label>
                        <select name="role_id" class="form-select form-select-clean" required>
                            <option value="1">Quản trị viên (Admin)</option>
                            <option value="2">Ban Giám Hiệu (BGH)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="btnThemTK" class="btn btn-primary rounded-pill fw-bold px-4 shadow-sm">
                        <i class="bi bi-check2-circle me-1"></i> Tạo tài khoản
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>