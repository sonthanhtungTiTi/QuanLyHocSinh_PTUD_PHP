<style>
    /* Tùy chỉnh Scrollbar mỏng đẹp */
    .custom-scroll::-webkit-scrollbar {
        width: 5px;
        height: 5px;
    }

    .custom-scroll::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }

    /* Hiệu ứng Item danh sách lớp */
    .class-item {
        border-radius: 12px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        margin-bottom: 8px;
    }

    .class-item:hover {
        background-color: #fff;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .class-item.active {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white !important;
        box-shadow: 0 8px 20px -4px rgba(67, 97, 238, 0.4);
    }

    .class-item.active small {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .class-item.active h6 {
        color: white !important;
    }

    /* Ô nhập điểm: Trong suốt, bo tròn */
    .inp-diem {
        background: #f8f9fa;
        border: 1px solid transparent;
        border-radius: 8px;
        font-weight: 600;
        color: #333;
        transition: all 0.2s;
    }

    .inp-diem:focus {
        background: #fff;
        border-color: #4361ee;
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        transform: scale(1.05);
    }

    /* Các cột màu nền nhẹ */
    .bg-soft-warning {
        background-color: #fffbeb !important;
    }

    .bg-soft-info {
        background-color: #eff6ff !important;
    }

    .bg-soft-success {
        background-color: #ecfdf5 !important;
    }
</style>

<div class="row g-4 h-100">
    <div class="col-lg-3 d-flex flex-column" style="height: calc(100vh - 100px);">
        <div class="mb-3 px-2">
            <h6 class="fw-bold text-uppercase text-muted small ls-1">Danh sách lớp dạy</h6>
        </div>

        <div class="custom-scroll overflow-auto pe-2 pb-5">
            <?php if ($dsLopPhuTrach && $dsLopPhuTrach->num_rows > 0): ?>
                <?php while ($pc = $dsLopPhuTrach->fetch_assoc()): ?>
                    <?php
                    $isActive = ($selectedLop == $pc['lop_id'] && $selectedMon == $pc['mon_hoc_id']);
                    $link = "index.php?act=nhapdiem&lop_id={$pc['lop_id']}&mon_id={$pc['mon_hoc_id']}&hk=$selectedHK";
                    ?>
                    <a href="<?php echo $link; ?>" class="d-block text-decoration-none class-item p-3 <?php echo $isActive ? 'active' : 'bg-white text-secondary'; ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">Lớp <?php echo $pc['ten_lop']; ?></h6>
                                <small class="text-muted"><i class="bi bi-journal-bookmark me-1"></i> <?php echo $pc['ten_mon']; ?></small>
                            </div>
                            <?php if ($isActive): ?>
                                <i class="bi bi-check-circle-fill fs-5"></i>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-emoji-frown fs-1 opacity-50"></i>
                    <p class="small mt-2">Chưa có lớp nào</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-9">
        <?php if ($selectedLop && $selectedMon): ?>
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="background: #fff;">

                <div class="card-header bg-white border-0 py-3 px-4 mt-2 d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-0">Bảng Điểm</h4>
                        <div class="d-flex align-items-center gap-3 mt-1">
                            <span class="badge bg-light text-secondary border rounded-pill px-3">
                                <i class="bi bi-calendar3 me-1"></i> 2024-2025
                            </span>
                            <span class="badge bg-light text-primary border rounded-pill px-3">
                                <i class="bi bi-bookmark-star-fill me-1"></i> <?php echo $selectedHK; ?>
                            </span>
                        </div>
                    </div>

                    <div class="d-flex gap-2 bg-light p-1 rounded-pill">
                        <a href="index.php?act=nhapdiem&lop_id=<?php echo $selectedLop; ?>&mon_id=<?php echo $selectedMon; ?>&hk=HK1"
                            class="btn btn-sm rounded-pill px-4 <?php echo ($selectedHK == 'HK1') ? 'bg-white shadow-sm text-primary fw-bold' : 'text-muted'; ?>">HK1</a>
                        <a href="index.php?act=nhapdiem&lop_id=<?php echo $selectedLop; ?>&mon_id=<?php echo $selectedMon; ?>&hk=HK2"
                            class="btn btn-sm rounded-pill px-4 <?php echo ($selectedHK == 'HK2') ? 'bg-white shadow-sm text-primary fw-bold' : 'text-muted'; ?>">HK2</a>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px;" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3">
                            <li>
                                <form method="POST" action="index.php?act=nhapdiem">
                                    <input type="hidden" name="lop_id" value="<?php echo $selectedLop; ?>">
                                    <input type="hidden" name="mon_id" value="<?php echo $selectedMon; ?>">
                                    <input type="hidden" name="nam_hoc_id" value="<?php echo $namHocHienTai; ?>">
                                    <input type="hidden" name="hoc_ky" value="<?php echo $selectedHK; ?>">
                                    <button type="submit" name="btnExportExcel" class="dropdown-item py-2">
                                        <i class="bi bi-download me-2"></i> Tải bảng mẫu
                                    </button>
                                </form>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item py-2 text-success" data-bs-toggle="modal" data-bs-target="#importDiemModal">
                                    <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> Nhập từ Excel
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="modal fade" id="importDiemModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg rounded-4">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title fw-bold"><i class="bi bi-cloud-upload me-2"></i> Nhập điểm nhanh</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" enctype="multipart/form-data">
                                <div class="modal-body p-4">
                                    <input type="hidden" name="lop_id" value="<?php echo $selectedLop; ?>">
                                    <input type="hidden" name="mon_id" value="<?php echo $selectedMon; ?>">
                                    <input type="hidden" name="nam_hoc_id" value="<?php echo $namHocHienTai; ?>">
                                    <input type="hidden" name="hoc_ky" value="<?php echo $selectedHK; ?>">

                                    <div class="text-center mb-4">
                                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 d-inline-block mb-3">
                                            <i class="bi bi-filetype-csv fs-1"></i>
                                        </div>
                                        <p class="text-muted small">Tải lên file CSV bảng điểm để cập nhật hàng loạt.<br>Vui lòng không thay đổi mã học sinh.</p>
                                    </div>

                                    <label class="form-label fw-bold small text-uppercase text-secondary">Chọn file:</label>
                                    <input type="file" name="file_csv" class="form-control" accept=".csv" required>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" name="btnImportExcel" class="btn btn-success fw-bold rounded-pill px-4">Xác nhận nhập</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0 d-flex flex-column h-100">
                    <form method="POST" action="" class="d-flex flex-column h-100">
                        <input type="hidden" name="nam_hoc_id" value="<?php echo $namHocHienTai; ?>">
                        <input type="hidden" name="lop_id" value="<?php echo $selectedLop; ?>">
                        <input type="hidden" name="mon_id" value="<?php echo $selectedMon; ?>">
                        <input type="hidden" name="hoc_ky" value="<?php echo $selectedHK; ?>">

                        <div class="table-responsive custom-scroll flex-grow-1 px-2">
                            <table class="table table-borderless align-middle mb-0" style="min-width: 1000px; border-collapse: separate; border-spacing: 0 4px;">
                                <thead class="text-center sticky-top top-0 bg-white" style="z-index: 5;">
                                    <tr class="text-muted small text-uppercase">
                                        <th width="30px">STT</th>
                                       
                                        <th width="200px" class="text-start">Họ tên</th>
                                        <th colspan="2">Miệng</th>
                                        <th colspan="2">15 Phút</th>
                                        <th width="80px" class="text-warning">1 Tiết</th>
                                        <th width="80px" class="text-info">Cuối Kỳ</th>
                                        <th width="80px" class="fw-bold text-primary">TBM</th>
                                        <?php if ($selectedHK == 'HK2'): ?>
                                            <th width="80px" class="fw-bold text-success">Cả Năm</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($dsHocSinh && $dsHocSinh->num_rows > 0):
                                        $stt = 1;
                                        while ($hs = $dsHocSinh->fetch_assoc()):
                                            $hsId = $hs['hs_id'];
                                            $dtb_hk_nay = $hs['diem_tb'];
                                            // ... Logic tính Cả Năm giữ nguyên ...
                                            $dtb_ca_nam = "--";
                                            if ($selectedHK == 'HK2') {
                                                $val_hk1 = isset($diemHK1[$hsId]) ? $diemHK1[$hsId] : null;
                                                if (is_numeric($val_hk1) && is_numeric($dtb_hk_nay)) {
                                                    $dtb_ca_nam = round((floatval($val_hk1) + floatval($dtb_hk_nay) * 2) / 3, 1);
                                                }
                                            }
                                    ?>
                                            <tr class="bg-light rounded-3 shadow-sm hover-shadow" style="transition: transform 0.2s;">
                                                <td class="text-center rounded-start-3 text-muted"><?php echo $stt++; ?></td>
                                                
                                                <td class="fw-bold text-dark"><?php echo $hs['ho_ten']; ?></td>

                                                <td class="p-1"><input type="number" step="0.1" min="0" max="10" name="diem[<?php echo $hsId; ?>][mieng1]" value="<?php echo $hs['diem_mieng_1']; ?>" class="form-control form-control-sm text-center inp-diem"></td>
                                                <td class="p-1"><input type="number" step="0.1" min="0" max="10" name="diem[<?php echo $hsId; ?>][mieng2]" value="<?php echo $hs['diem_mieng_2']; ?>" class="form-control form-control-sm text-center inp-diem"></td>
                                                <td class="p-1"><input type="number" step="0.1" min="0" max="10" name="diem[<?php echo $hsId; ?>][15p1]" value="<?php echo $hs['diem_15p_1']; ?>" class="form-control form-control-sm text-center inp-diem"></td>
                                                <td class="p-1"><input type="number" step="0.1" min="0" max="10" name="diem[<?php echo $hsId; ?>][15p2]" value="<?php echo $hs['diem_15p_2']; ?>" class="form-control form-control-sm text-center inp-diem"></td>

                                                <td class="p-1 bg-soft-warning"><input type="number" step="0.1" min="0" max="10" name="diem[<?php echo $hsId; ?>][1tiet]" value="<?php echo $hs['diem_1tiet']; ?>" class="form-control form-control-sm text-center fw-bold inp-diem bg-transparent"></td>
                                                <td class="p-1 bg-soft-info"><input type="number" step="0.1" min="0" max="10" name="diem[<?php echo $hsId; ?>][thi]" value="<?php echo $hs['diem_thi']; ?>" class="form-control form-control-sm text-center fw-bold inp-diem bg-transparent"></td>

                                                <td class="text-center fw-bold text-primary bg-white">
                                                    <?php echo ($dtb_hk_nay != "") ? $dtb_hk_nay : "--"; ?>
                                                </td>

                                                <?php if ($selectedHK == 'HK2'): ?>
                                                    <td class="text-center rounded-end-3 fw-bold text-success bg-soft-success">
                                                        <?php echo $dtb_ca_nam; ?>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="position-absolute bottom-0 end-0 p-4" style="z-index: 10;">
                            <button type="submit" name="btnLuuDiem" class="btn btn-primary rounded-pill shadow-lg py-3 px-4 fw-bold d-flex align-items-center hover-up">
                                <i class="bi bi-cloud-arrow-up-fill fs-5 me-2"></i> Lưu Bảng Điểm
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column align-items-center justify-content-center h-100 rounded-4 border-2 border-dashed border-secondary border-opacity-10 text-muted">
                <i class="bi bi-hand-index-thumb fs-1 mb-3 text-primary opacity-50"></i>
                <h5 class="fw-normal">Chọn một lớp để bắt đầu</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.querySelectorAll('.inp-diem').forEach(inp => {
        inp.addEventListener('keydown', function(e) {
            // Chặn phím: -, +, e
            if (['-', '+', 'e', 'E'].includes(e.key)) {
                e.preventDefault();
            }
        });

        // Kiểm tra ngay khi nhập (Paste số âm cũng bị chặn)
        inp.addEventListener('input', function() {
            let val = parseFloat(this.value);
            if (val < 0) this.value = 0;
            if (val > 10) this.value = 10;
        });

        // Tự bôi đen khi click (UX tốt cho nhập nhanh)
        inp.addEventListener('focus', function() {
            this.select();
        });
    });
</script>