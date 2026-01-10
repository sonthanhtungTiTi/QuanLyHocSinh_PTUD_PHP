<style>
    /* Input Group đẹp hơn */
    .input-group-text-soft {
        background-color: #f1f5f9;
        border: 1px solid transparent;
        color: #64748b;
        font-weight: 700;
        border-radius: 10px 0 0 10px;
    }

    .form-control-clean {
        background-color: #f8f9fa;
        border: 1px solid transparent;
        border-radius: 10px;
        padding: 0.7rem 1rem;
        transition: all 0.2s;
    }

    .form-control-clean:focus {
        background-color: #fff;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    /* Chỉnh lại bo góc cho input group */
    .input-group .form-control-clean {
        border-radius: 0 10px 10px 0 !important;
    }
</style>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                        <i class="bi bi-shop-window fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">
                            <?php echo isset($editData) ? 'Sửa Thông Tin Lớp' : 'Tạo Lớp Mới'; ?>
                        </h5>
                        <small class="text-muted">Quản lý lớp học</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="index.php?act=quanlylop">
                    <input type="hidden" name="id_lop" value="<?php echo isset($editData) ? $editData['id'] : ''; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary text-uppercase">Năm Học <span class="text-danger">*</span></label>
                        <select name="nam_hoc_id" class="form-select form-select-clean" required>
                            <?php
                            if ($dsNamHoc) {
                                $dsNamHoc->data_seek(0);
                                while ($nh = $dsNamHoc->fetch_assoc()) {
                                    $selected = '';
                                    if (isset($editData)) {
                                        if ($editData['nam_hoc_id'] == $nh['id']) $selected = 'selected';
                                    } else {
                                        if ($nh['trang_thai'] == 1) $selected = 'selected';
                                    }
                                    echo "<option value='{$nh['id']}' $selected>{$nh['ten_nam']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary text-uppercase">Khối Lớp <span class="text-danger">*</span></label>
                        <select name="khoi_id" id="select_khoi" class="form-select form-select-clean" required onchange="updatePrefix()">
                            <option value="10" <?php echo (isset($editData) && $editData['khoi_id'] == 10) ? 'selected' : ''; ?>>Khối 10</option>
                            <option value="11" <?php echo (isset($editData) && $editData['khoi_id'] == 11) ? 'selected' : ''; ?>>Khối 11</option>
                            <option value="12" <?php echo (isset($editData) && $editData['khoi_id'] == 12) ? 'selected' : ''; ?>>Khối 12</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary text-uppercase">Tên Lớp <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text input-group-text-soft" id="prefix_lop">10</span>
                            <input type="text" name="ten_lop_suffix" class="form-control form-control-clean fw-bold text-primary" required
                                value="<?php
                                        if (isset($editData)) {
                                            echo substr($editData['ten_lop'], strlen($editData['khoi_id']));
                                        }
                                        ?>"
                                placeholder="A1, A2..." style="text-transform: uppercase;">
                        </div>
                        <div class="form-text small text-muted fst-italic mt-2">
                            <i class="bi bi-info-circle me-1"></i> Tên đầy đủ sẽ là: <span id="preview_lop" class="fw-bold text-dark">10...</span>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="btnLuuLop" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Lưu Lớp Học
                        </button>

                        <?php if (isset($editData)): ?>
                            <a href="index.php?act=quanlylop" class="btn btn-light rounded-pill text-secondary fw-bold">
                                <i class="bi bi-x-lg me-1"></i> Hủy bỏ
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updatePrefix() {
            var khoi = document.getElementById('select_khoi').value;
            document.getElementById('prefix_lop').innerText = khoi;
            document.getElementById('preview_lop').innerText = khoi + "...";
        }
        window.onload = function() {
            updatePrefix();
        };
    </script>

    <div class="col-lg-8">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form method="GET" action="index.php" class="row g-2 align-items-center">
                    <input type="hidden" name="act" value="quanlylop">

                    <div class="col-md-4">
                        <select name="nam_hoc" class="form-select form-select-clean rounded-pill bg-white shadow-sm" onchange="this.form.submit()">
                            <option value="">-- Tất cả năm học --</option>
                            <?php
                            if ($dsNamHoc) {
                                $dsNamHoc->data_seek(0);
                                while ($nh = $dsNamHoc->fetch_assoc()) {
                                    // So sánh với biến $filter_nam (đã được xử lý trong Controller)
                                    $sel = ($filter_nam == $nh['id']) ? 'selected' : '';
                                    echo "<option value='{$nh['id']}' $sel>{$nh['ten_nam']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="khoi" class="form-select form-select-clean rounded-pill bg-white shadow-sm" onchange="this.form.submit()">
                            <option value="">-- Tất cả khối --</option>
                            <option value="10" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 10) ? 'selected' : ''; ?>>Khối 10</option>
                            <option value="11" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 11) ? 'selected' : ''; ?>>Khối 11</option>
                            <option value="12" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 12) ? 'selected' : ''; ?>>Khối 12</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <div class="position-relative">
                            <input type="text" name="search" class="form-control form-control-clean rounded-pill ps-5 bg-white shadow-sm"
                                placeholder="Tìm tên lớp..."
                                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                            <button class="btn position-absolute top-50 start-0 translate-middle-y ms-2 text-muted border-0" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
            <div class="card-header bg-white border-0 py-3 px-4">
                <h6 class="fw-bold text-uppercase text-muted small ls-1 mb-0">Danh sách lớp học</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="ps-4">Năm Học</th>
                            <th>Khối</th>
                            <th>Tên Lớp</th>
                            <th>GV Chủ Nhiệm</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($dsLop && $dsLop->num_rows > 0): ?>
                            <?php while ($row = $dsLop->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 text-muted"><?php echo $row['ten_nam']; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['ten_khoi']; ?></span></td>
                                    <td>
                                        <span class="fw-bold fs-5 text-dark"><?php echo $row['ten_lop']; ?></span>
                                    </td>
                                    <td>
                                        <?php if ($row['ten_gvcn']): ?>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success bg-opacity-10 text-success rounded-circle p-1 me-2"><i class="bi bi-check"></i></div>
                                                <span class="text-dark fw-medium"><?php echo $row['ten_gvcn']; ?></span>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3">
                                                Chưa phân công
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="index.php?act=quanlylop&edit_id=<?php echo $row['id']; ?>" class="btn btn-light btn-sm rounded-circle shadow-sm text-warning me-1" title="Sửa">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="index.php?act=quanlylop&delete_id=<?php echo $row['id']; ?>"
                                            class="btn btn-light btn-sm rounded-circle shadow-sm text-danger"
                                            onclick="return confirm('Xóa lớp <?php echo $row['ten_lop']; ?>? Lưu ý: Không thể xóa nếu lớp đã có học sinh!')" title="Xóa">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted opacity-50">
                                        <i class="bi bi-inbox fs-1"></i>
                                        <p class="mt-2">Chưa có lớp nào.</p>
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