<style>
    /* --- CẤU HÌNH GIAO DIỆN TOÀN MÀN HÌNH (COMPACT VIEW) --- */

    /* Container chính chiếm chiều cao màn hình trừ đi phần header (nếu có) */
    .compact-container {
        height: 85vh;
        /* Điều chỉnh số này nếu muốn bảng cao hơn/thấp hơn */
        display: flex;
        flex-direction: column;
        overflow: hidden;
        /* Chặn thanh cuộn */
    }

    /* Phần Filter (Bộ lọc) siêu gọn */
    .compact-header {
        flex: 0 0 auto;
        padding-bottom: 10px;
    }

    /* Phần bao quanh bảng -> Tự động chiếm hết khoảng trống còn lại */
    .compact-table-wrapper {
        flex: 1 1 auto;
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    /* Bảng ép full chiều cao */
    .table-fit {
        width: 100%;
        height: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        /* Cố định chiều rộng cột */
    }

    .table-fit th {
        background-color: #f8f9fa;
        color: #495057;
        font-size: 0.85rem;
        text-transform: uppercase;
        text-align: center;
        border: 1px solid #dee2e6;
        height: 40px;
        /* Chiều cao header cố định */
    }

    .table-fit td {
        border: 1px solid #dee2e6;
        padding: 0;
        /* Xóa padding để tận dụng diện tích */
        vertical-align: middle;
        height: 8%;
        /* Chia đều chiều cao cho các tiết (~10 tiết + ngăn cách) */
    }

    /* Cột Tiết */
    .col-tiet {
        width: 60px;
        background-color: #f8f9fa;
        font-weight: bold;
        color: #6c757d;
        text-align: center;
        font-size: 0.8rem;
    }

    /* Dòng ngăn cách buổi */
    .row-separator td {
        height: 25px !important;
        background-color: #fff3cd !important;
        /* Màu vàng nhạt */
        color: #856404;
        font-size: 0.75rem;
        font-weight: bold;
        text-align: center;
        border: 1px solid #ffeeba;
    }

    /* --- Ô DỮ LIỆU THÔNG MINH (VISUAL CELL) --- */
    .cell-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        transition: background 0.2s;
    }

    .cell-wrapper:hover {
        background-color: #e9ecef;
        /* Highlight khi di chuột */
    }

    /* Select ẩn: Phủ lên toàn bộ ô nhưng trong suốt */
    .tkb-select-hidden {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        /* Ẩn đi */
        cursor: pointer;
        z-index: 10;
    }

    /* Phần hiển thị nội dung (Visual) */
    .cell-display {
        text-align: center;
        pointer-events: none;
        /* Để click xuyên qua vào select */
        width: 100%;
        padding: 0 5px;
    }

    .subj-text {
        font-weight: 700;
        color: #0d6efd;
        /* Màu xanh primary */
        font-size: 0.9rem;
        display: block;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .teacher-text {
        font-size: 0.7rem;
        color: #6c757d;
        margin-top: 2px;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Trạng thái trống */
    .cell-empty .subj-text {
        color: #dee2e6;
        /* Màu xám nhạt */
        font-size: 1.5rem;
        font-weight: 300;
    }

    .cell-empty:hover {
        border: 1px dashed #adb5bd;
    }
</style>

<div class="container-fluid mt-3 compact-container">

    <div class="compact-header">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="fw-bold text-primary m-0"><i class="bi bi-calendar-week"></i> QUẢN LÝ THỜI KHÓA BIỂU</h4>

            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-danger btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAutoTKB">
                    <i class="bi bi-robot"></i> XẾP TỰ ĐỘNG
                </button>
                <?php if ($selectedLop): ?>
                    <button type="button" onclick="document.getElementById('formLuuTKB').submit();" class="btn btn-success btn-sm fw-bold px-4 shadow-sm">
                        <i class="bi bi-save"></i> LƯU DỮ LIỆU
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-2">
                <form method="GET" action="index.php" class="row g-2 align-items-center">
                    <input type="hidden" name="act" value="quanlytkb">

                    <div class="col-auto">
                        <span class="fw-bold text-secondary small">Bộ lọc:</span>
                    </div>
                    <div class="col-md-3">
                        <select name="lop_id" class="form-select form-select-sm fw-bold text-primary" onchange="this.form.submit()">
                            <option value="">-- Chọn lớp --</option>
                            <?php
                            if ($dsLop) {
                                $dsLop->data_seek(0);
                                while ($l = $dsLop->fetch_assoc()):
                            ?>
                                    <option value="<?php echo $l['id']; ?>" <?php echo ($selectedLop == $l['id']) ? 'selected' : ''; ?>>
                                        Lớp <?php echo $l['ten_lop']; ?>
                                    </option>
                            <?php endwhile;
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="hk" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="HK1" <?php echo ($selectedHK == 'HK1') ? 'selected' : ''; ?>>Học Kỳ 1</option>
                            <option value="HK2" <?php echo ($selectedHK == 'HK2') ? 'selected' : ''; ?>>Học Kỳ 2</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="compact-table-wrapper">
        <?php if ($selectedLop): ?>
            <form method="POST" action="" id="formLuuTKB" style="height: 100%;">
                <input type="hidden" name="lop_id" value="<?php echo $selectedLop; ?>">
                <input type="hidden" name="hk" value="<?php echo $selectedHK; ?>">
                <input type="hidden" name="btnLuuTKB" value="1">

                <table class="table-fit">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Tiết</th>
                            <?php for ($t = 2; $t <= 6; $t++): ?>
                                <th>Thứ <?php echo $t; ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for ($tiet = 1; $tiet <= 10; $tiet++):
                            // LOGIC GỐC: Dòng ngăn cách
                            if ($tiet == 6) echo "<tr class='row-separator'><td colspan='6'>--- BUỔI CHIỀU ---</td></tr>";
                        ?>
                            <tr>
                                <td class="col-tiet"><?php echo $tiet; ?></td>

                                <?php for ($thu = 2; $thu <= 6; $thu++):
                                    // LOGIC GỐC: Lấy dữ liệu
                                    $cellData = isset($dataTKB[$thu][$tiet]) ? $dataTKB[$thu][$tiet] : null;

                                    // Xử lý dữ liệu an toàn (Logic của bạn)
                                    $valMonId = '';
                                    $tenGV = '';
                                    if (is_array($cellData)) {
                                        $valMonId = isset($cellData['mon_id']) ? $cellData['mon_id'] : '';
                                        $tenGV    = isset($cellData['ten_gv']) ? $cellData['ten_gv'] : '';
                                    } else {
                                        $valMonId = $cellData;
                                    }

                                    // Tìm tên môn để hiển thị (Visual)
                                    $displayMon = "+"; // Mặc định là dấu cộng nhạt
                                    $wrapperClass = "cell-empty";

                                    foreach ($arrMon as $m) {
                                        if ($m['id'] == $valMonId) {
                                            $displayMon = $m['ten_mon'];
                                            $wrapperClass = ""; // Có dữ liệu
                                            break;
                                        }
                                    }
                                ?>
                                    <td>
                                        <div class="cell-wrapper <?php echo $wrapperClass; ?>">
                                            <select name="tkb[<?php echo $thu; ?>][<?php echo $tiet; ?>]" class="tkb-select-hidden" onchange="updateCellDisplay(this)">
                                                <option value="">-</option>
                                                <?php foreach ($arrMon as $m): ?>
                                                    <option value="<?php echo $m['id']; ?>" <?php echo ($valMonId == $m['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $m['ten_mon']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                            <div class="cell-display">
                                                <span class="subj-text"><?php echo $displayMon; ?></span>
                                                <?php if ($valMonId != ''): ?>
                                                    <span class="teacher-text">
                                                        <?php if ($tenGV): ?>
                                                            <?php echo "Gv:", $tenGV; ?>
                                                        <?php else: ?>
                                                            <span class="text-danger fw-bold">! Chưa GV</span>
                                                        <?php endif; ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                <?php endfor; ?>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </form>
        <?php else: ?>
            <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted bg-light">
                <i class="bi bi-arrow-up-circle fs-1 mb-3 opacity-25"></i>
                <h5>Vui lòng chọn Lớp học ở trên để xem TKB</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalAutoTKB" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">⚠️ CẢNH BÁO: Xếp Tự Động</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Hệ thống sẽ thực hiện các bước sau:</p>
                    <ol>
                        <li><strong>XÓA TOÀN BỘ</strong> Thời khóa biểu hiện tại của Học kỳ bạn chọn.</li>
                        <li>Lấy dữ liệu từ bảng <strong>Phân công</strong>.</li>
                        <li>Tự động sắp xếp lịch học cho tất cả các lớp sao cho không bị trùng GV.</li>
                    </ol>
                    <p class="text-danger fw-bold">Hành động này không thể hoàn tác!</p>

                    <div class="mb-3">
                        <label class="form-label">Chọn Học kỳ áp dụng:</label>
                        <select name="hk_auto" class="form-select">
                            <option value="HK1">Học Kỳ 1</option>
                            <option value="HK2">Học Kỳ 2</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="btnXepTuDong" class="btn btn-danger fw-bold">
                        <i class="bi bi-lightning-charge-fill"></i> CHẤP NHẬN & CHẠY
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateCellDisplay(selectElement) {
        const wrapper = selectElement.parentElement;
        const displayDiv = wrapper.querySelector('.cell-display');
        const subjSpan = displayDiv.querySelector('.subj-text');

        // Tìm span teacher (có thể không tồn tại nếu ô trống ban đầu)
        let teacherSpan = displayDiv.querySelector('.teacher-text');

        const selectedText = selectElement.options[selectElement.selectedIndex].text;
        const selectedValue = selectElement.value;

        if (selectedValue === "") {
            // Trường hợp xóa môn (chọn "-")
            subjSpan.innerText = "+";
            wrapper.classList.add('cell-empty');
            if (teacherSpan) teacherSpan.style.display = 'none';
        } else {
            // Trường hợp chọn môn
            subjSpan.innerText = selectedText;
            wrapper.classList.remove('cell-empty');

            // Xử lý hiển thị tên GV
            // Vì Client không biết tên GV ngay lập tức (cần query server), ta hiện placeholder
            if (!teacherSpan) {
                teacherSpan = document.createElement('span');
                teacherSpan.className = 'teacher-text';
                displayDiv.appendChild(teacherSpan);
            }
            teacherSpan.style.display = 'block';
            teacherSpan.innerText = "..."; // Báo hiệu chờ Lưu
            teacherSpan.style.color = '#adb5bd';
        }
    }
</script>