<style>
    /* Avatar tròn cho học sinh */
    .avatar-sm {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border-radius: 50%;
        font-size: 1rem;
        flex-shrink: 0;
    }

    /* Input Form kiểu mới (Soft Input) */
    .form-control-clean,
    .form-select-clean {
        background-color: #f8f9fa;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
        font-weight: 500;
    }

    .form-control-clean:focus,
    .form-select-clean:focus {
        background-color: #fff;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    /* Badges màu Pastel */
    .badge-soft-primary {
        background-color: #eff6ff;
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.1);
    }

    .badge-soft-success {
        background-color: #ecfdf5;
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.1);
    }

    .badge-soft-warning {
        background-color: #fffbeb;
        color: #d97706;
        border: 1px solid rgba(217, 119, 6, 0.1);
    }

    .badge-soft-danger {
        background-color: #fef2f2;
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.1);
    }

    /* Action buttons hover effect */
    .btn-action {
        transition: transform 0.2s;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }
</style>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-person-plus-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <?php echo isset($editData) ? 'Cập Nhật Hồ Sơ' : 'Thêm Học Sinh Mới'; ?>
                        </h5>
                        <small class="text-muted">Năm học: <strong><?php echo $selectedNamHoc; ?></strong></small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="index.php?act=quanlyhocsinh&nam_hoc=<?php echo $selectedNamHoc; ?>" id="formHocSinh">
                    <input type="hidden" name="id_hs" value="<?php echo isset($editData) ? $editData['id'] : ''; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Mã Học Sinh <span class="text-danger">*</span></label>
                        <input type="text" name="ma_hs" id="ma_hs" class="form-control form-control-clean text-uppercase fw-bold"
                            value="<?php echo isset($editData) ? $editData['ma_hs'] : ''; ?>"
                            placeholder="VD: HS2024001">
                        <small id="error_ma_hs" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" id="ho_ten" class="form-control form-control-clean text-capitalize"
                            value="<?php echo isset($editData) ? $editData['ho_ten'] : ''; ?>"
                            placeholder="Nguyễn Văn A">
                        <small id="error_ho_ten" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-7 mb-3">
                            <label class="form-label fw-bold small text-secondary">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control form-control-clean"
                                value="<?php echo isset($editData) ? $editData['ngay_sinh'] : ''; ?>">
                            <small id="error_ngay_sinh" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                        </div>

                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-bold small text-secondary">Giới tính</label>
                            <select name="gioi_tinh" class="form-select form-select-clean">
                                <option value="Nam" <?php echo (isset($editData) && $editData['gioi_tinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                                <option value="Nữ" <?php echo (isset($editData) && $editData['gioi_tinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Lớp học (<?php echo $selectedNamHoc; ?>) <span class="text-danger">*</span></label>
                        <select name="lop_id" class="form-select form-select-clean" required>
                            <option value="">-- Chọn lớp --</option>
                            <?php
                            if ($dsLop && $dsLop->num_rows > 0) {
                                $dsLop->data_seek(0);
                                while ($lop = $dsLop->fetch_assoc()) {
                                    $sel = (isset($editData) && $editData['lop_id'] == $lop['id']) ? 'selected' : '';
                                    echo "<option value='{$lop['id']}' $sel>Lớp {$lop['ten_lop']}</option>";
                                }
                            } else {
                                echo "<option disabled>Chưa có lớp nào trong năm học này</option>";
                            }
                            ?>
                        </select>
                        <?php if (!$dsLop || $dsLop->num_rows == 0): ?>
                            <div class="form-text text-danger small mt-2">
                                <i class="bi bi-exclamation-circle-fill"></i> Bạn cần tạo Lớp học ở menu "Quản lý Lớp" trước.
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">Địa chỉ</label>
                        <textarea name="dia_chi" class="form-control form-control-clean" rows="2" placeholder="Số nhà, đường, phường/xã..."><?php echo isset($editData) ? $editData['dia_chi'] : ''; ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="btnLuuHS" id="btnLuuHS" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> <?php echo isset($editData) ? 'Lưu Cập Nhật' : 'Lưu Hồ Sơ'; ?>
                        </button>

                        <?php if (isset($editData)): ?>
                            <a href="index.php?act=quanlyhocsinh&nam_hoc=<?php echo $selectedNamHoc; ?>" class="btn btn-light rounded-pill text-secondary fw-bold">
                                <i class="bi bi-x-lg me-1"></i> Hủy bỏ
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <div class="row g-2 align-items-center">

                    <div class="col-md-4">
                        <form method="GET" action="index.php" id="formNamHoc">
                            <input type="hidden" name="act" value="quanlyhocsinh">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-0"><i class="bi bi-calendar-event text-primary"></i></span>
                                <select name="nam_hoc" class="form-select form-select-clean border-0 bg-light fw-bold text-primary" onchange="document.getElementById('formNamHoc').submit()">
                                    <?php
                                    if ($dsNamHoc) {
                                        $dsNamHoc->data_seek(0);
                                        while ($nh = $dsNamHoc->fetch_assoc()) {
                                            $sel = ($selectedNamHoc == $nh['id']) ? 'selected' : '';
                                            echo "<option value='{$nh['id']}' $sel>{$nh['ten_nam']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-8 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <form method="POST" action="">
                                <button type="submit" name="btnDownloadMauHS" class="btn btn-light rounded-pill shadow-sm text-secondary fw-bold btn-sm py-2 px-3">
                                    <i class="bi bi-download me-2"></i> File mẫu
                                </button>
                            </form>
                            <button type="button" class="btn btn-success rounded-pill shadow-sm fw-bold btn-sm py-2 px-3" data-bs-toggle="modal" data-bs-target="#importHSModal">
                                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Import CSV
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-light">
            <div class="card-body p-3">
                <form method="GET" action="index.php" class="row g-2">
                    <input type="hidden" name="act" value="quanlyhocsinh">
                    <input type="hidden" name="nam_hoc" value="<?php echo $selectedNamHoc; ?>">

                    <div class="col-md-4">
                        <select name="lop" class="form-select form-select-clean rounded-pill bg-white shadow-sm" onchange="this.form.submit()">
                            <option value="">-- Tất cả các lớp --</option>
                            <?php
                            if ($dsLop) {
                                $dsLop->data_seek(0);
                                while ($lop = $dsLop->fetch_assoc()) {
                                    $sel = (isset($_GET['lop']) && $_GET['lop'] == $lop['id']) ? 'selected' : '';
                                    echo "<option value='{$lop['id']}' $sel>Lớp {$lop['ten_lop']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <div class="position-relative">
                            <input type="text" name="search" class="form-control form-control-clean rounded-pill ps-5 bg-white shadow-sm"
                                placeholder="Tìm kiếm theo tên hoặc mã số..."
                                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            <button class="btn position-absolute top-50 start-0 translate-middle-y ms-2 text-muted border-0" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-0">
                    Danh sách học sinh <span class="badge bg-light text-dark border ms-2"><?php echo $dsHS ? $dsHS->num_rows : 0; ?></span>
                </h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Học Sinh</th>
                            <th>Thông tin</th>
                            <th>Lớp</th>
                            <th>Trạng Thái</th>
                            <th>Địa chỉ</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($dsHS && $dsHS->num_rows > 0): ?>
                            <?php while ($row = $dsHS->fetch_assoc()): ?>
                                <tr class="<?php echo ($row['trang_thai'] == 'nghihoc') ? 'table-light opacity-75' : ''; ?>">
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 text-primary me-3">
                                                <?php echo substr($row['ho_ten'], 0, 1); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['ho_ten']; ?></div>
                                                <div class="small text-muted font-monospace"><?php echo $row['ma_hs']; ?></div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column small gap-1">
                                            <span class="text-dark">
                                                <?php echo ($row['gioi_tinh'] == 'Nam') ? 'Nam' : 'Nữ'; ?>
                                            </span>
                                            <span class="text-muted">
                                                <?php echo ($row['ngay_sinh']) ? date('d/m/Y', strtotime($row['ngay_sinh'])) : '--'; ?>
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge badge-soft-success rounded-pill px-3 py-2 fw-normal">
                                            <?php echo $row['ten_lop']; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($row['trang_thai'] == 'danghoc'): ?>
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                                <i class="bi bi-check-circle-fill me-1"></i> Đang học
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1">
                                                <i class="bi bi-x-circle-fill me-1"></i> Đã nghỉ
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td style="max-width: 150px;">
                                        <div class="text-truncate text-muted small" title="<?php echo $row['dia_chi']; ?>">
                                            <?php echo $row['dia_chi']; ?>
                                        </div>
                                    </td>

                                    <td class="text-end pe-4">
                                        <a href="index.php?act=quanlyhocsinh&edit_id=<?php echo $row['id']; ?>&nam_hoc=<?php echo $selectedNamHoc; ?>"
                                            class="btn btn-light btn-sm btn-action rounded-circle shadow-sm text-warning me-1" title="Sửa">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <?php if ($row['trang_thai'] == 'danghoc'): ?>
                                            <a href="index.php?act=quanlyhocsinh&delete_id=<?php echo $row['id']; ?>&nam_hoc=<?php echo $selectedNamHoc; ?>"
                                                class="btn btn-light btn-sm btn-action rounded-circle shadow-sm text-danger"
                                                onclick="return confirm('CẢNH BÁO: Chuyển học sinh [<?php echo $row['ho_ten']; ?>] sang trạng thái Nghỉ Học?')" title="Nghỉ học">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?act=quanlyhocsinh&restore_id=<?php echo $row['id']; ?>&nam_hoc=<?php echo $selectedNamHoc; ?>"
                                                class="btn btn-light btn-sm btn-action rounded-circle shadow-sm text-success"
                                                onclick="return confirm('Bạn muốn khôi phục học sinh [<?php echo $row['ho_ten']; ?>] quay lại lớp?')" title="Khôi phục">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="importHSModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title fw-bold"><i class="bi bi-file-earmark-spreadsheet me-2"></i> Nhập Học sinh từ CSV</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="alert alert-light border border-success border-opacity-25 bg-success bg-opacity-10 small text-dark mb-4 rounded-3">
                        <strong><i class="bi bi-info-circle me-1"></i> Quy định File Import:</strong><br>
                        - Định dạng: <strong>.csv (UTF-8)</strong><br>
                        - Dòng 1: Tiêu đề (bỏ qua).<br>
                        - Thứ tự: <strong>Mã HS, Họ Tên, Ngày sinh, Giới tính, Địa chỉ, Tên Lớp</strong><br>
                        - Tên Lớp phải chính xác (VD: 10A1).
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-secondary">Chọn file CSV:</label>
                        <input type="file" class="form-control form-control-clean" name="file_csv" accept=".csv" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill fw-bold text-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="btnImportCSV" class="btn btn-success rounded-pill fw-bold px-4 shadow-sm">
                        <i class="bi bi-upload me-2"></i> Tiến hành Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputMaHS = document.getElementById('ma_hs');
        const inputHoTen = document.getElementById('ho_ten');
        const inputNgaySinh = document.getElementById('ngay_sinh');
        const btnLuu = document.getElementById('btnLuuHS');

        function showError(input, message) {
            const errorTag = document.getElementById('error_' + input.id);
            errorTag.innerText = message;
            errorTag.style.display = 'block';
            input.classList.add('is-invalid');
            checkFormValidity();
        }

        function clearError(input) {
            const errorTag = document.getElementById('error_' + input.id);
            errorTag.style.display = 'none';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            checkFormValidity();
        }

        function checkFormValidity() {
            const errors = document.querySelectorAll('.is-invalid');
            btnLuu.disabled = (errors.length > 0);
        }

        // 1. Validate Mã HS (HS + Số)
        if (inputMaHS) {
            inputMaHS.addEventListener('input', function() {
                let val = this.value.toUpperCase();
                this.value = val;
                const regex = /^HS[0-9]+$/;

                if (val.length === 0) showError(this, "Mã HS không được để trống.");
                else if (!regex.test(val)) showError(this, "Mã không hợp lệ! Phải là 'HS' + số.");
                else clearError(this);
            });
        }

        // 2. Validate Họ Tên
        if (inputHoTen) {
            inputHoTen.addEventListener('input', function() {
                let words = this.value.split(" ");
                for (let i = 0; i < words.length; i++) {
                    if (words[i].length > 0) words[i] = words[i][0].toUpperCase() + words[i].substr(1);
                }
                let val = this.value;
                const regexSpecial = /[0-9!@#$%^&*(),.?":{}|<>]/;

                if (val.trim().length < 2) showError(this, "Tên quá ngắn.");
                else if (regexSpecial.test(val)) showError(this, "Tên không được chứa số/ký tự đặc biệt.");
                else clearError(this);
            });
        }

        // 3. Validate Ngày sinh
        if (inputNgaySinh) {
            inputNgaySinh.addEventListener('change', function() {
                if (!this.value) return;
                const birthDate = new Date(this.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                if (today < new Date(today.getFullYear(), birthDate.getMonth(), birthDate.getDate())) {
                    age--;
                }
                if (age < 14) showError(this, "Học sinh phải từ 14 tuổi trở lên.");
                else clearError(this);
            });
        }
    });
</script>