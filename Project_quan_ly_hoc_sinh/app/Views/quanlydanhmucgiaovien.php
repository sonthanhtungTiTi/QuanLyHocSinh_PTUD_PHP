<style>
    /* Avatar trong bảng */
    .avatar-sm {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border-radius: 50%;
        font-size: 1rem;
    }

    /* Input Form kiểu mới */
    .form-control-clean,
    .form-select-clean {
        background-color: #f8f9fa;
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 0.7rem 1rem;
        transition: all 0.2s;
    }

    .form-control-clean:focus,
    .form-select-clean:focus {
        background-color: #fff;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    /* Badges */
    .badge-soft-success {
        background-color: #ecfdf5;
        color: #10b981;
    }

    .badge-soft-secondary {
        background-color: #f1f5f9;
        color: #64748b;
    }

    .badge-soft-primary {
        background-color: #eff6ff;
        color: #3b82f6;
    }
</style>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-person-plus-fill fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <?php echo isset($editData) ? 'Cập Nhật Hồ Sơ' : 'Thêm Giáo Viên'; ?>
                        </h5>
                        <small class="text-muted">Nhập thông tin chi tiết bên dưới</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="" id="formGiaoVien">
                    <input type="hidden" name="id_gv" value="<?php echo isset($editData) ? $editData['id'] : ''; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Mã GV (Username) <span class="text-danger">*</span></label>
                        <input type="text" name="ma_gv" id="ma_gv" class="form-control form-control-clean text-uppercase fw-bold"
                            value="<?php echo isset($editData) ? $editData['ma_gv'] : ''; ?>"
                            placeholder="VD: GV001" <?php echo isset($editData) ? 'readonly' : ''; ?>> <small id="error_ma_gv" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Họ và Tên <span class="text-danger">*</span></label>
                        <input type="text" name="ho_ten" id="ho_ten" class="form-control form-control-clean text-capitalize"
                            value="<?php echo isset($editData) ? $editData['ho_ten'] : ''; ?>"
                            placeholder="Nhập họ tên đầy đủ">
                        <small id="error_ho_ten" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-secondary">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="sdt" id="sdt" class="form-control form-control-clean"
                                value="<?php echo isset($editData) ? $editData['sdt'] : ''; ?>"
                                placeholder="09xxxxxxxx" maxlength="10">
                            <small id="error_sdt" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-secondary">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" name="ngay_sinh" id="ngay_sinh" class="form-control form-control-clean"
                                value="<?php echo isset($editData) ? $editData['ngay_sinh'] : ''; ?>">
                            <small id="error_ngay_sinh" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control form-control-clean"
                            value="<?php echo isset($editData) ? $editData['email'] : ''; ?>"
                            placeholder="example@email.com">
                        <small id="error_email" class="text-danger fw-bold mt-1 d-block" style="display:none; font-size: 0.8rem;"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Chuyên môn (Tổ) <span class="text-danger">*</span></label>
                        <select name="mon_hoc_id" class="form-select form-select-clean" required>
                            <option value="">-- Chọn môn giảng dạy --</option>
                            <?php
                            if ($dsMon && $dsMon->num_rows > 0) {
                                $dsMon->data_seek(0);
                                while ($m = $dsMon->fetch_assoc()) {
                                    $sel = (isset($editData) && $editData['mon_hoc_id'] == $m['id']) ? 'selected' : '';
                                    echo "<option value='{$m['id']}' $sel>{$m['ten_mon']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">Địa chỉ</label>
                        <textarea name="dia_chi" class="form-control form-control-clean" rows="2" placeholder="Nhập địa chỉ..."><?php echo isset($editData) ? $editData['dia_chi'] : ''; ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="btnLuuGV" id="btnLuuGV" class="btn btn-primary rounded-pill shadow-sm fw-bold py-2">
                            <i class="bi bi-check-lg me-1"></i> <?php echo isset($editData) ? 'Lưu Cập Nhật' : 'Tạo Giáo Viên'; ?>
                        </button>

                        <?php if (isset($editData)): ?>
                            <a href="index.php?act=quanlydanhmucgiaovien" class="btn btn-light rounded-pill text-secondary fw-bold">
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
                    <div class="col-md-8">
                        <form method="GET" action="index.php" class="d-flex gap-2">
                            <input type="hidden" name="act" value="quanlydanhmucgiaovien">

                            <div class="position-relative flex-grow-1">
                                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" class="form-control form-control-clean ps-5 rounded-pill"
                                    placeholder="Tìm tên, mã GV..."
                                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>

                            <select name="filter_mon" class="form-select form-select-clean rounded-pill" style="max-width: 180px;" onchange="this.form.submit()">
                                <option value="">Tất cả môn</option>
                                <?php
                                if ($dsMon && $dsMon->num_rows > 0) {
                                    $dsMon->data_seek(0);
                                    while ($m = $dsMon->fetch_assoc()) {
                                        $selected = (isset($_GET['filter_mon']) && $_GET['filter_mon'] == $m['id']) ? 'selected' : '';
                                        echo "<option value='{$m['id']}' $selected>{$m['ten_mon']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </form>
                    </div>

                    <div class="col-md-4 text-end">
                        <div class="dropdown">
                            <button class="btn btn-light rounded-pill fw-bold shadow-sm dropdown-toggle text-secondary" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill me-1"></i> Tác vụ
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3">
                                <li>
                                    <form method="POST" action="">
                                        <button type="submit" name="btnDownloadMau" class="dropdown-item py-2">
                                            <i class="bi bi-download me-2 text-primary"></i> Tải file mẫu CSV
                                        </button>
                                    </form>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <button type="button" class="dropdown-item py-2" data-bs-toggle="modal" data-bs-target="#importModal">
                                        <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i> Nhập từ Excel/CSV
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-0">Danh sách nhân sự</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4">Giáo Viên</th>
                            <th>Liên hệ</th>
                            <th>Chuyên Môn</th>
                            <th>Trạng Thái</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($dsGV && $dsGV->num_rows > 0): ?>
                            <?php while ($row = $dsGV->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary bg-opacity-10 text-primary me-3">
                                                <?php echo substr($row['ho_ten'], 0, 1); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo $row['ho_ten']; ?></div>
                                                <div class="small text-muted font-monospace"><?php echo $row['ma_gv']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column small">
                                            <span class="text-dark mb-1"><i class="bi bi-telephone me-2 text-muted"></i><?php echo $row['sdt'] ? $row['sdt'] : '---'; ?></span>
                                            <span class="text-muted"><i class="bi bi-envelope me-2"></i><?php echo $row['email'] ? $row['email'] : '---'; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-primary rounded-pill px-3 py-2 fw-normal">
                                            <?php echo $row['ten_mon']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($row['trang_thai'] == 'hoatdong'): ?>
                                            <span class="badge badge-soft-success rounded-pill px-3 py-2 fw-normal"><i class="bi bi-check-circle me-1"></i> Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge badge-soft-secondary rounded-pill px-3 py-2 fw-normal"><i class="bi bi-slash-circle me-1"></i> Đã nghỉ</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="index.php?act=quanlydanhmucgiaovien&edit_id=<?php echo $row['id']; ?>" class="btn btn-light btn-sm rounded-circle shadow-sm text-warning me-1" title="Sửa">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <?php if ($row['trang_thai'] == 'hoatdong'): ?>
                                            <a href="index.php?act=quanlydanhmucgiaovien&delete_id=<?php echo $row['id']; ?>"
                                                class="btn btn-light btn-sm rounded-circle shadow-sm text-danger"
                                                onclick="return confirm('CẢNH BÁO: Giáo viên này sẽ bị chuyển sang trạng thái Nghỉ Việc và Khóa Tài Khoản?')"
                                                title="Xóa/Nghỉ việc">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="index.php?act=quanlydanhmucgiaovien&restore_id=<?php echo $row['id']; ?>"
                                                class="btn btn-light btn-sm rounded-circle shadow-sm text-success"
                                                onclick="return confirm('Bạn muốn khôi phục hoạt động cho giáo viên này?')"
                                                title="Khôi phục hoạt động">
                                                <i class="bi bi-arrow-counterclockwise"></i> </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted opacity-50">
                                        <i class="bi bi-inbox fs-1"></i>
                                        <p class="mt-2">Chưa có dữ liệu giáo viên.</p>
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

<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-success text-white">
                <h6 class="modal-title fw-bold"><i class="bi bi-file-earmark-spreadsheet me-2"></i> Nhập Giáo viên từ Excel</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="alert alert-light border border-success border-opacity-25 bg-success bg-opacity-10 small text-dark mb-4 rounded-3">
                        <strong><i class="bi bi-info-circle me-1"></i> Quy định File:</strong><br>
                        1. Định dạng: <strong>.csv (UTF-8)</strong>.<br>
                        2. Cột: Mã GV, Họ Tên, SĐT, Ngày Sinh, Địa chỉ, Email, Tên Môn.<br>
                        3. Tên môn phải khớp với danh mục.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-secondary">Chọn file CSV:</label>
                        <input type="file" class="form-control form-control-clean" name="file_csv" accept=".csv" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill fw-bold text-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="btnImportCSV" class="btn btn-success rounded-pill fw-bold px-4 shadow-sm">Tiến hành Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Lấy các element
        const inputMaGV = document.getElementById('ma_gv');
        const inputHoTen = document.getElementById('ho_ten');
        const inputSDT = document.getElementById('sdt');
        const inputEmail = document.getElementById('email');
        const inputNgaySinh = document.getElementById('ngay_sinh');
        const btnLuu = document.getElementById('btnLuuGV');

        // Hàm hiển thị lỗi
        function showError(input, message) {
            const errorTag = document.getElementById('error_' + input.id);
            errorTag.innerText = message;
            errorTag.style.display = 'block';
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            checkFormValidity();
        }

        // Hàm xóa lỗi (Xanh lá)
        function clearError(input) {
            const errorTag = document.getElementById('error_' + input.id);
            errorTag.style.display = 'none';
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
            checkFormValidity();
        }

        // Hàm Reset trạng thái (Không xanh không đỏ - dùng khi mới nhập)
        function resetState(input) {
            const errorTag = document.getElementById('error_' + input.id);
            errorTag.style.display = 'none';
            input.classList.remove('is-invalid');
            input.classList.remove('is-valid');
            checkFormValidity();
        }

        // Kiểm tra tổng thể để Bật/Tắt nút Lưu
        function checkFormValidity() {
            const errors = document.querySelectorAll('.is-invalid');
            // Nếu có class 'is-invalid' thì khóa nút. Ngược lại mở nút.
            // Lưu ý: Logic này chỉ chặn khi có lỗi hiển thị. 
            // Còn validate phía Server (PHP) vẫn là chốt chặn cuối cùng.
            btnLuu.disabled = (errors.length > 0);
        }

        // --- 1. XỬ LÝ MÃ GV (Username) ---
        if (inputMaGV) {
            // Khi đang gõ: Chỉ Auto UpperCase, chưa báo lỗi vội
            inputMaGV.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
                // Nếu đang có lỗi mà người dùng sửa đúng định dạng -> Xóa lỗi ngay
                if (/^GV[0-9]+$/.test(this.value)) {
                    clearError(this);
                }
            });
            // Khi bấm ra ngoài (Blur): Mới kiểm tra nghiêm ngặt
            inputMaGV.addEventListener('blur', function() {
                const val = this.value.trim();
                if (val.length === 0) {
                    showError(this, "Mã GV không được để trống.");
                } else if (!/^GV[0-9]+$/.test(val)) {
                    showError(this, "Mã GV không hợp lệ (Phải là GV + số)");
                } else {
                    clearError(this);
                }
            });
        }

        // --- 2. XỬ LÝ HỌ TÊN (UX: Nhập xong mới báo lỗi) ---
        if (inputHoTen) {
            // Khi đang gõ: Nếu thấy dài > 4 ký tự -> Tự động xóa lỗi (nếu đang bị báo lỗi)
            inputHoTen.addEventListener('input', function() {
                if (this.value.trim().length >= 5) {
                    clearError(this);
                } else {
                    // Nếu chưa đủ dài, chỉ reset về trạng thái bình thường chứ đừng báo lỗi đỏ lòm
                    if (this.classList.contains('is-invalid')) {
                        // Giữ nguyên lỗi cũ nếu đang sửa
                    } else {
                        resetState(this);
                    }
                }
            });

            // Khi click chuột ra ngoài: Mới bắt lỗi "Quá ngắn"
            inputHoTen.addEventListener('blur', function() {
                if (this.value.trim().length < 5) {
                    showError(this, "Họ tên quá ngắn (tối thiểu 5 ký tự).");
                } else {
                    clearError(this);
                }
            });
        }

        // --- 3. XỬ LÝ SỐ ĐIỆN THOẠI ---
        if (inputSDT) {
            inputSDT.addEventListener('input', function() {
                // Chỉ cho nhập số
                this.value = this.value.replace(/[^0-9]/g, '');

                // Nếu đang gõ mà thấy đúng 10 số và bắt đầu bằng 0 -> Xanh luôn
                if (/^0[0-9]{9}$/.test(this.value)) {
                    clearError(this);
                }
            });

            // Khi click ra ngoài mới báo lỗi định dạng
            inputSDT.addEventListener('blur', function() {
                if (!/^0[0-9]{9}$/.test(this.value)) {
                    showError(this, "SĐT phải đủ 10 số và bắt đầu bằng số 0.");
                } else {
                    clearError(this);
                }
            });
        }

        // --- 4. XỬ LÝ EMAIL (Cơ bản) ---
        if (inputEmail) {
            inputEmail.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(this.value)) {
                    showError(this, "Email không hợp lệ.");
                } else {
                    clearError(this);
                }
            });
            inputEmail.addEventListener('input', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (emailRegex.test(this.value)) {
                    clearError(this);
                }
            });
        }
    });
</script>