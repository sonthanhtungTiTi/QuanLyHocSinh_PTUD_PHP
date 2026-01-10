<style>
    /* Input kiểu mới */
    .form-select-clean {
        background-color: #f8f9fa;
        border: 1px solid transparent;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.2s;
        font-weight: 500;
    }

    .form-select-clean:focus {
        background-color: #fff;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
    }

    /* Highlight cho ô chọn môn */
    .select-highlight {
        background-color: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* Badge tên lớp */
    .badge-class {
        background-color: #eff6ff;
        color: #3b82f6;
        padding: 8px 12px;
        border-radius: 8px;
        font-weight: 700;
    }
</style>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <div class="mb-4">
                    <h4 class="fw-bold text-primary mb-1 text-uppercase">
                        <i class="bi bi-briefcase-fill me-2"></i> Phân Công Giảng Dạy
                    </h4>
                    <p class="text-muted small mb-0">Phân công giáo viên bộ môn cho từng lớp theo học kỳ/năm học.</p>
                </div>

                <form method="GET" action="index.php" class="row g-3 align-items-end">
                    <input type="hidden" name="act" value="quanlyphancong">

                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-secondary">1. Năm Học</label>
                        <select name="nam_hoc" class="form-select form-select-clean" onchange="this.form.submit()">
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

                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-secondary">2. Lọc theo Khối</label>
                        <select name="khoi" class="form-select form-select-clean" onchange="this.form.submit()">
                            <option value="">-- Tất cả khối --</option>
                            <option value="10" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 10) ? 'selected' : ''; ?>>Khối 10</option>
                            <option value="11" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 11) ? 'selected' : ''; ?>>Khối 11</option>
                            <option value="12" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 12) ? 'selected' : ''; ?>>Khối 12</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold text-primary">3. Chọn Môn học cần phân công</label>
                        <select name="mon_id" class="form-select form-select-clean select-highlight text-primary fw-bold" onchange="this.form.submit()">
                            <option value="">-- Vui lòng chọn môn --</option>
                            <?php
                            if ($dsMonHoc) {
                                $dsMonHoc->data_seek(0);
                                while ($mh = $dsMonHoc->fetch_assoc()) {
                                    $sel = ($selectedMon == $mh['id']) ? 'selected' : '';
                                    echo "<option value='{$mh['id']}' $sel>{$mh['ten_mon']}</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <?php if ($selectedMon && $dsLop && $dsLop->num_rows > 0): ?>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-journal-bookmark-fill text-warning me-2"></i>
                        Đang phân công môn:
                        <span class="text-primary text-uppercase">
                            <?php
                            $dsMonHoc->data_seek(0);
                            while ($m = $dsMonHoc->fetch_assoc()) if ($m['id'] == $selectedMon) echo $m['ten_mon'];
                            ?>
                        </span>
                    </h6>

                    <button type="button" onclick="autoAssignRoundRobin()" class="btn btn-warning btn-sm rounded-pill fw-bold text-dark px-3 shadow-sm">
                        <i class="bi bi-arrow-repeat me-1"></i> Chia đều giáo viên (Tự động)
                    </button>
                </div>

                <div class="card-body p-0">
                    <form method="POST" action="">
                        <input type="hidden" name="nam_hoc_id" value="<?php echo $selectedNamHoc; ?>">
                        <input type="hidden" name="mon_id" value="<?php echo $selectedMon; ?>">
                        <input type="hidden" name="khoi_current" value="<?php echo isset($_GET['khoi']) ? $_GET['khoi'] : ''; ?>">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th width="5%" class="text-center py-3">STT</th>
                                        <th width="10%">Khối</th>
                                        <th width="15%">Lớp</th>
                                        <th>Giáo Viên Giảng Dạy</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // 1. Lọc danh sách GV đúng chuyên môn
                                    $arrGVChuyenMon = [];
                                    if ($dsGV) {
                                        $dsGV->data_seek(0);
                                        while ($g = $dsGV->fetch_assoc()) {
                                            if ($g['mon_hoc_id'] == $selectedMon) {
                                                $arrGVChuyenMon[] = $g;
                                            }
                                        }
                                    }

                                    // 2. Duyệt qua lớp
                                    $stt = 1;
                                    $dsLop->data_seek(0);
                                    while ($lop = $dsLop->fetch_assoc()):
                                        $lopId = $lop['id'];
                                        $currentGV = isset($dataDaPhanCong[$lopId]) ? $dataDaPhanCong[$lopId] : '';
                                    ?>
                                        <tr>
                                            <td class="text-center text-muted"><?php echo $stt++; ?></td>
                                            <td><span class="badge bg-light text-dark border"><?php echo $lop['ten_khoi']; ?></span></td>
                                            <td><span class="badge-class"><?php echo $lop['ten_lop']; ?></span></td>
                                            <td>
                                                <select name="phan_cong[<?php echo $lopId; ?>]" class="form-select select-gv border-0 bg-light">
                                                    <option value="" class="text-muted">-- Chưa phân công --</option>
                                                    <?php foreach ($arrGVChuyenMon as $gv): ?>
                                                        <?php $isSelect = ($currentGV == $gv['id']) ? 'selected' : ''; ?>
                                                        <option value="<?php echo $gv['id']; ?>" <?php echo $isSelect; ?>>
                                                            <?php echo $gv['ho_ten']; ?> (<?php echo $gv['ma_gv']; ?>)
                                                        </option>
                                                    <?php endforeach; ?>

                                                    <?php if (empty($arrGVChuyenMon)): ?>
                                                        <option disabled>Chưa có GV thuộc tổ bộ môn này</option>
                                                    <?php endif; ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="fixed-bottom bg-white border-top shadow-lg py-3" style="z-index: 100;">
                            <div class="container-fluid px-4 d-flex justify-content-end align-items-center">
                                <span class="text-muted small me-3 d-none d-md-block">
                                    <i class="bi bi-info-circle me-1"></i> Hệ thống sẽ lưu giáo viên bộ môn cho các lớp đã chọn.
                                </span>
                                <button type="submit" name="btnLuuPhanCong" class="btn btn-success rounded-pill fw-bold px-5 py-2 shadow-sm hover-up">
                                    <i class="bi bi-check-circle-fill me-2"></i> LƯU PHÂN CÔNG
                                </button>
                            </div>
                        </div>

                        <div style="height: 80px;"></div>

                    </form>
                </div>
            </div>

        <?php elseif ($selectedNamHoc && $selectedMon && (!$dsLop || $dsLop->num_rows == 0)): ?>
            <div class="alert alert-light border border-info text-center py-5 rounded-4 shadow-sm">
                <i class="bi bi-inbox fs-1 text-info opacity-50"></i>
                <p class="mt-3 text-muted">Không tìm thấy lớp học nào trong khối này.</p>
            </div>
        <?php elseif (!$selectedMon): ?>
            <div class="d-flex flex-column align-items-center justify-content-center py-5 text-muted border border-dashed rounded-4 bg-light">
                <i class="bi bi-arrow-up-circle fs-1 mb-3 text-primary opacity-50"></i>
                <h5 class="fw-normal">Vui lòng chọn <strong>Môn học</strong> ở trên để bắt đầu phân công.</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function autoAssignRoundRobin() {
        let selects = document.querySelectorAll('.select-gv');

        // Lấy danh sách ID giáo viên khả dụng từ dropdown đầu tiên
        if (selects.length === 0) return;

        let availableTeacherIds = [];
        let firstSelect = selects[0];
        for (let i = 1; i < firstSelect.options.length; i++) {
            if (!firstSelect.options[i].disabled && firstSelect.options[i].value !== "") {
                availableTeacherIds.push(firstSelect.options[i].value);
            }
        }

        if (availableTeacherIds.length === 0) {
            alert("Không có giáo viên chuyên môn nào để phân công!");
            return;
        }

        // Thuật toán chia đều (Round Robin)
        let teacherIndex = 0;

        selects.forEach(select => {
            // Chỉ điền vào ô trống
            if (select.value === "") {
                select.value = availableTeacherIds[teacherIndex];

                // Hiệu ứng visual báo hiệu vừa chọn
                select.classList.add('bg-warning', 'bg-opacity-10');
                setTimeout(() => select.classList.remove('bg-warning', 'bg-opacity-10'), 1000);

                teacherIndex++;
                if (teacherIndex >= availableTeacherIds.length) {
                    teacherIndex = 0;
                }
            }
        });

         alert("Đã chia đều các lớp cho giáo viên trong tổ!"); // Có thể bỏ alert cho đỡ phiền
    }
</script>