<style>
    /* Card từng lớp học */
    .class-card {
        background: #fff;
        border-radius: 16px;
        padding: 1.25rem;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
        border-color: #e2e8f0;
    }

    /* Badge Khối (Màu pastel dịu mắt) */
    .badge-grade {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .bg-grade-10 {
        background: #e0f2fe;
        color: #0284c7;
    }

    /* Xanh dương nhạt */
    .bg-grade-11 {
        background: #fef3c7;
        color: #d97706;
    }

    /* Vàng cam nhạt */
    .bg-grade-12 {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Đỏ hồng nhạt */

    /* Dropdown chọn GV */
    .select-teacher {
        background-color: #f8f9fa;
        border: 1px solid transparent;
        padding: 0.6rem 1rem;
        border-radius: 10px;
        font-weight: 500;
        color: #334155;
        cursor: pointer;
        transition: all 0.2s;
    }

    .select-teacher:hover,
    .select-teacher:focus {
        background-color: #fff;
        border-color: #cbd5e1;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
</style>

<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                            <i class="bi bi-person-badge-fill fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">Phân Công Chủ Nhiệm</h4>
                            <p class="mb-0 text-muted small">Quản lý giáo viên chủ nhiệm (GVCN) cho từng lớp.</p>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 align-items-center bg-light p-2 rounded-pill">
                        <form method="GET" action="index.php" class="d-flex gap-2">
                            <input type="hidden" name="act" value="phanconggvcn">

                            <select name="nam_hoc" class="form-select border-0 bg-white rounded-pill px-3 fw-bold text-primary shadow-sm" style="min-width: 150px;" onchange="this.form.submit()">
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

                            <select name="khoi" class="form-select border-0 bg-white rounded-pill px-3 shadow-sm" style="min-width: 140px;" onchange="this.form.submit()">
                                <option value="">-- Tất cả Khối --</option>
                                <option value="10" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 10) ? 'selected' : ''; ?>>Khối 10</option>
                                <option value="11" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 11) ? 'selected' : ''; ?>>Khối 11</option>
                                <option value="12" <?php echo (isset($_GET['khoi']) && $_GET['khoi'] == 12) ? 'selected' : ''; ?>>Khối 12</option>
                            </select>
                        </form>

                        <?php if ($dsLop && $dsLop->num_rows > 0): ?>
                            <div class="border-start mx-2 h-50"></div>
                            <button type="button" onclick="autoAssignGVCN()" class="btn btn-warning rounded-pill fw-bold shadow-sm px-3 d-flex align-items-center">
                                <i class="bi bi-magic me-2"></i> Tự động
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <form method="POST" action="">
            <input type="hidden" name="nam_hoc_id" value="<?php echo $selectedNamHoc; ?>">
            <input type="hidden" name="khoi_current" value="<?php echo isset($_GET['khoi']) ? $_GET['khoi'] : ''; ?>">

            <div class="row g-3 pb-5 mb-5"> <?php
                                            // Chuẩn bị danh sách GV hoạt động
                                            $arrGV = [];
                                            if ($dsGV) {
                                                $dsGV->data_seek(0);
                                                while ($g = $dsGV->fetch_assoc()) {
                                                    if ($g['trang_thai'] == 'hoatdong') $arrGV[] = $g;
                                                }
                                            }

                                            if ($dsLop && $dsLop->num_rows > 0):
                                                $stt = 1;
                                                while ($lop = $dsLop->fetch_assoc()):
                                                    // Xác định màu badge theo tên khối
                                                    $bgClass = 'bg-light text-dark';
                                                    $tenKhoiShort = str_replace('Khối ', '', $lop['ten_khoi']);
                                                    if (strpos($lop['ten_khoi'], '10') !== false) $bgClass = 'bg-grade-10';
                                                    if (strpos($lop['ten_khoi'], '11') !== false) $bgClass = 'bg-grade-11';
                                                    if (strpos($lop['ten_khoi'], '12') !== false) $bgClass = 'bg-grade-12';
                                            ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="class-card">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="badge-grade <?php echo $bgClass; ?> me-3">
                                        <?php echo $tenKhoiShort; ?>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-0"><?php echo $lop['ten_lop']; ?></h5>
                                        <small class="text-muted">ID Lớp: <?php echo $lop['id']; ?></small>
                                    </div>
                                </div>

                                <div>
                                    <label class="form-label small fw-bold text-secondary text-uppercase mb-1">Giáo viên chủ nhiệm</label>
                                    <select name="gvcn[<?php echo $lop['id']; ?>]" class="form-select select-teacher gvcn-select">
                                        <option value="" class="text-muted">-- Chưa phân công --</option>
                                        <?php foreach ($arrGV as $gv): ?>
                                            <?php $selected = ($lop['gvcn_id'] == $gv['id']) ? 'selected' : ''; ?>
                                            <option value="<?php echo $gv['id']; ?>" <?php echo $selected; ?>>
                                                <?php echo $gv['ho_ten']; ?> (<?php echo $gv['ma_gv']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                                            else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="text-muted opacity-50 mb-3">
                            <i class="bi bi-inbox fs-1"></i>
                        </div>
                        <h5 class="text-secondary fw-normal">Không tìm thấy lớp học nào</h5>
                        <p class="text-muted small">Vui lòng kiểm tra lại bộ lọc Năm học hoặc Khối.</p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($dsLop && $dsLop->num_rows > 0): ?>
                <div class="fixed-bottom bg-white border-top shadow-lg py-3" style="z-index: 1000;">
                    <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
                        <div class="d-none d-md-block text-muted small">
                            <i class="bi bi-info-circle-fill text-info me-1"></i>
                            Hệ thống sẽ tự động kiểm tra trùng lặp giáo viên khi bạn thay đổi lựa chọn.
                        </div>
                        <button type="submit" name="btnLuuGVCN" class="btn btn-primary rounded-pill fw-bold px-5 py-2 shadow-sm hover-up">
                            <i class="bi bi-check-circle-fill me-2"></i> LƯU PHÂN CÔNG
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
    // 1. Logic Độc quyền (Exclusive): Đã chọn người này thì ẩn ở dòng khác
    function updateOptions() {
        const selects = document.querySelectorAll('.gvcn-select');
        let selectedValues = [];

        // Thu thập các ID đã chọn
        selects.forEach(sel => {
            if (sel.value !== "") selectedValues.push(sel.value);
        });

        // Vô hiệu hóa option trùng
        selects.forEach(sel => {
            let options = sel.querySelectorAll('option');
            options.forEach(opt => {
                if (opt.value === "") return;

                // Nếu ID nằm trong danh sách đã chọn VÀ không phải là của chính select này
                if (selectedValues.includes(opt.value) && opt.value !== sel.value) {
                    opt.disabled = true;
                    if (!opt.innerText.includes("⛔")) {
                        opt.innerText += " ⛔ (Đã chọn)";
                        opt.style.color = "#ef4444"; // Đỏ nhạt
                    }
                } else {
                    opt.disabled = false;
                    opt.innerText = opt.innerText.replace(" ⛔ (Đã chọn)", "");
                    opt.style.color = "";
                }
            });
        });
    }

    // Gắn sự kiện change để chạy logic Độc quyền
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.gvcn-select').forEach(sel => {
            sel.addEventListener('change', updateOptions);
        });
        updateOptions(); // Chạy lần đầu
    });

    // 2. Logic Phân công Tự động (Auto Assign)
    function autoAssignGVCN() {
        const selects = document.querySelectorAll('.gvcn-select');
        let usedIds = new Set();
        selects.forEach(s => {
            if (s.value !== "") usedIds.add(s.value);
        });

        selects.forEach(select => {
            if (select.value === "") {
                let availableOptions = [];
                for (let i = 1; i < select.options.length; i++) {
                    let opt = select.options[i];
                    if (!opt.disabled && !usedIds.has(opt.value)) {
                        availableOptions.push(opt.value);
                    }
                }

                if (availableOptions.length > 0) {
                    let randomIdx = Math.floor(Math.random() * availableOptions.length);
                    let chosenId = availableOptions[randomIdx];
                    select.value = chosenId;
                    usedIds.add(chosenId);

                    // Hiệu ứng visual báo hiệu vừa chọn
                    select.classList.add('border-warning', 'bg-warning', 'bg-opacity-10');
                    setTimeout(() => {
                        select.classList.remove('border-warning', 'bg-warning', 'bg-opacity-10');
                    }, 2000);
                }
            }
        });
        updateOptions();
    }
</script>