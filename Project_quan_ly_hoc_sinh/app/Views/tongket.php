<style>
    /* Sticky cho bảng lớn */
    .table-container {
        max-height: 75vh;
        overflow: auto;
        position: relative;
    }

    /* Cố định Header */
    .table-tongket thead th {
        position: sticky;
        top: 0;
        z-index: 20;
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
    }

    /* Cố định cột STT và Tên */
    .sticky-col {
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 10;
        border-right: 2px solid #e2e8f0 !important;
    }

    .sticky-col-2 {
        position: sticky;
        left: 50px;
        /* Sau cột STT */
        background-color: #fff;
        z-index: 10;
        border-right: 2px solid #e2e8f0 !important;
    }

    /* Header của cột cố định phải nổi lên trên */
    .table-tongket thead th.sticky-col,
    .table-tongket thead th.sticky-col-2 {
        z-index: 30;
    }

    /* Style chung */
    .table-tongket th,
    .table-tongket td {
        vertical-align: middle;
        white-space: nowrap;
        /* Không xuống dòng */
        padding: 8px 12px;
    }

    /* Select Hạnh Kiểm gọn */
    .select-hk {
        border: none;
        background: transparent;
        font-weight: 700;
        text-align: center;
        width: 100%;
        cursor: pointer;
    }

    .select-hk:focus {
        background: #f8fafc;
        outline: none;
    }
</style>

<div class="container-fluid mt-4">
    <div class="row g-4 h-100">
        <div class="col-lg-3 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h6 class="fw-bold text-uppercase text-secondary small ls-1 mb-0">
                        <i class="bi bi-list-task me-2"></i>Lớp Chủ Nhiệm
                    </h6>
                </div>
                <div class="list-group list-group-flush p-2">
                    <?php if ($dsLopCN && $dsLopCN->num_rows > 0): ?>
                        <?php while ($lop = $dsLopCN->fetch_assoc()): ?>
                            <?php
                            $isActive = ($selectedLop == $lop['id']);
                            $activeClass = $isActive ? 'bg-primary text-white shadow-sm fw-bold' : 'text-dark hover-bg-light';
                            ?>
                            <a href="index.php?act=tongket&lop_id=<?php echo $lop['id']; ?>&hk=<?php echo $selectedHK; ?>"
                                class="list-group-item list-group-item-action border-0 rounded-3 mb-1 py-3 px-3 d-flex align-items-center justify-content-between <?php echo $activeClass; ?>">
                                <span><i class="bi bi-people-fill me-2 opacity-75"></i> Lớp <?php echo $lop['ten_lop']; ?></span>
                                <?php if ($isActive): ?><i class="bi bi-chevron-right small"></i><?php endif; ?>
                            </a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-emoji-frown fs-1 opacity-50"></i>
                            <p class="small mt-2">Bạn chưa được phân công chủ nhiệm lớp nào.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-xl-10">
            <?php if ($selectedLop): ?>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                    <div class="card-header bg-white border-0 py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="fw-bold text-primary mb-0 d-flex align-items-center">
                                <i class="bi bi-journal-bookmark-fill me-2"></i> TỔNG KẾT & ĐÁNH GIÁ
                            </h5>
                            <small class="text-muted">Xem và xếp loại học lực, hạnh kiểm cho học sinh.</small>
                        </div>

                        <div class="d-flex gap-2 bg-light p-1 rounded-pill">
                            <a href="index.php?act=tongket&lop_id=<?php echo $selectedLop; ?>&hk=HK1"
                                class="btn btn-sm rounded-pill px-4 fw-bold <?php echo ($selectedHK == 'HK1') ? 'bg-white shadow-sm text-primary' : 'text-muted'; ?>">
                                Học Kỳ 1
                            </a>
                            <a href="index.php?act=tongket&lop_id=<?php echo $selectedLop; ?>&hk=HK2"
                                class="btn btn-sm rounded-pill px-4 fw-bold <?php echo ($selectedHK == 'HK2') ? 'bg-white shadow-sm text-primary' : 'text-muted'; ?>">
                                Học Kỳ 2
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0 d-flex flex-column">
                        <form method="POST" action="" class="d-flex flex-column h-100">
                            <input type="hidden" name="nam_hoc_id" value="<?php echo $namHocHienTai; ?>">
                            <input type="hidden" name="lop_id" value="<?php echo $selectedLop; ?>">
                            <input type="hidden" name="hoc_ky" value="<?php echo $selectedHK; ?>">

                            <div class="table-container flex-grow-1">
                                <table class="table table-bordered table-hover mb-0 table-tongket">
                                    <thead class="bg-light text-secondary small text-uppercase">
                                        <tr>
                                            <th class="sticky-col text-center" width="50px">STT</th>
                                            <th class="sticky-col-2 text-start ps-3" style="min-width: 200px;">Họ và Tên</th>

                                            <?php
                                            $arrMon = [];
                                            if ($dsMonHoc) {
                                                $dsMonHoc->data_seek(0);
                                                while ($m = $dsMonHoc->fetch_assoc()) {
                                                    $arrMon[] = $m;
                                                    echo "<th class='text-center bg-white text-dark' style='min-width: 60px;'>{$m['ten_mon']}</th>";
                                                }
                                            }
                                            ?>

                                            <th class="text-center bg-warning bg-opacity-10 text-dark fw-bold border-start border-2">ĐTB</th>
                                            <th class="text-center bg-success bg-opacity-10 text-success fw-bold">Học Lực</th>
                                            <th class="text-center bg-danger bg-opacity-10 text-danger fw-bold">Danh Hiệu</th>
                                            <th class="text-center bg-light text-dark" style="min-width: 120px;">Hạnh Kiểm</th>
                                            <th class="text-center bg-light text-dark" style="min-width: 250px;">Nhận Xét</th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white">
                                        <?php if (!empty($bangDiemTongHop)):
                                            $stt = 1;
                                            foreach ($bangDiemTongHop as $row):
                                                $hs = $row['info'];
                                                $diem = $row['diem'];
                                                $tk = $row['tong_ket'];
                                                $hsId = $hs['id'];

                                                $tongDiem = 0;
                                                $soMon = 0;
                                        ?>
                                                <tr>
                                                    <td class="sticky-col text-center fw-bold text-muted bg-light"><?php echo $stt++; ?></td>
                                                    <td class="sticky-col-2 fw-bold text-dark bg-white border-end"><?php echo $hs['ho_ten']; ?></td>

                                                    <?php foreach ($arrMon as $m):
                                                        $d = isset($diem[$m['id']]) ? $diem[$m['id']] : '';
                                                        if (is_numeric($d)) {
                                                            $tongDiem += floatval($d);
                                                            $soMon++;
                                                        }
                                                        $classDiem = ($d != '' && $d < 5) ? 'text-danger fw-bold' : 'text-secondary';
                                                    ?>
                                                        <td class="text-center <?php echo $classDiem; ?>"><?php echo ($d != '') ? $d : '-'; ?></td>
                                                    <?php endforeach; ?>

                                                    <?php
                                                    $dtbShow = isset($tk['dtb_tat_ca_mon']) ? $tk['dtb_tat_ca_mon'] : (($soMon > 0) ? round($tongDiem / $soMon, 1) : '--');
                                                    ?>
                                                    <td class="text-center fw-bold bg-warning bg-opacity-10 text-dark border-start border-2">
                                                        <?php echo $dtbShow; ?>
                                                    </td>

                                                    <?php
                                                    $hl = isset($tk['hoc_luc']) ? $tk['hoc_luc'] : '';
                                                    $badgeClass = 'bg-secondary';
                                                    $hlText = '--';

                                                    if ($hl == 'Gioi') {
                                                        $badgeClass = 'bg-success';
                                                        $hlText = 'Giỏi';
                                                    } elseif ($hl == 'Kha') {
                                                        $badgeClass = 'bg-primary';
                                                        $hlText = 'Khá';
                                                    } elseif ($hl == 'TB') {
                                                        $badgeClass = 'bg-warning text-dark';
                                                        $hlText = 'TB';
                                                    } elseif ($hl == 'Yeu') {
                                                        $badgeClass = 'bg-orange text-white';
                                                        $hlText = 'Yếu';
                                                    } // Cần CSS orange
                                                    elseif ($hl == 'Kem') {
                                                        $badgeClass = 'bg-danger';
                                                        $hlText = 'Kém';
                                                    }
                                                    ?>
                                                    <td class="text-center"><span class="badge <?php echo $badgeClass; ?> rounded-pill px-3"><?php echo $hlText; ?></span></td>

                                                    <td class="text-center">
                                                        <?php
                                                        $dh = isset($tk['danh_hieu']) ? $tk['danh_hieu'] : '';
                                                        if ($dh == 'Hoc sinh Gioi') echo '<span class="badge bg-danger rounded-pill"><i class="bi bi-trophy-fill me-1"></i> HS Giỏi</span>';
                                                        elseif ($dh == 'Hoc sinh Tien tien') echo '<span class="badge bg-info text-dark rounded-pill">HS Tiên tiến</span>';
                                                        ?>
                                                    </td>

                                                    <td>
                                                        <select name="tongket[<?php echo $hsId; ?>][hanh_kiem]" class="select-hk">
                                                            <option value="" class="text-muted">-</option>
                                                            <option value="Tot" <?php echo (isset($tk['hanh_kiem']) && $tk['hanh_kiem'] == 'Tot') ? 'selected' : ''; ?> class="text-success fw-bold">Tốt</option>
                                                            <option value="Kha" <?php echo (isset($tk['hanh_kiem']) && $tk['hanh_kiem'] == 'Kha') ? 'selected' : ''; ?> class="text-primary fw-bold">Khá</option>
                                                            <option value="TB" <?php echo (isset($tk['hanh_kiem']) && $tk['hanh_kiem'] == 'TB') ? 'selected' : ''; ?> class="text-warning fw-bold">TB</option>
                                                            <option value="Yeu" <?php echo (isset($tk['hanh_kiem']) && $tk['hanh_kiem'] == 'Yeu') ? 'selected' : ''; ?> class="text-danger fw-bold">Yếu</option>
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="text" name="tongket[<?php echo $hsId; ?>][nhan_xet]"
                                                            class="form-control form-control-sm border-0 bg-transparent"
                                                            placeholder="Nhập nhận xét..."
                                                            value="<?php echo isset($tk['nhan_xet']) ? $tk['nhan_xet'] : ''; ?>">
                                                    </td>
                                                </tr>
                                            <?php endforeach;
                                        else: ?>
                                            <tr>
                                                <td colspan="100" class="text-center py-5 text-muted">
                                                    <i class="bi bi-inbox fs-1 opacity-25"></i>
                                                    <p class="mt-2">Chưa có dữ liệu học sinh trong lớp này.</p>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer bg-white border-top py-3 px-4 d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Lưu ý: Chọn Hạnh Kiểm trước khi bấm Tự Động Xếp Loại.
                                </small>

                                <div class="d-flex gap-2">
                                    <button type="submit" name="btnTuDongXepLoai" class="btn btn-outline-primary fw-bold shadow-sm rounded-pill px-4"
                                        onclick="return confirm('Hệ thống sẽ tính điểm TB và xếp loại dựa trên Hạnh Kiểm hiện tại. Tiếp tục?');">
                                        <i class="bi bi-calculator me-1"></i> Tự động xếp loại
                                    </button>

                                    <button type="submit" name="btnLuuTongKet" class="btn btn-success fw-bold shadow-sm rounded-pill px-5 hover-up">
                                        <i class="bi bi-check2-circle me-1"></i> LƯU KẾT QUẢ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="d-flex flex-column align-items-center justify-content-center h-100 text-muted border-2 border-dashed border-secondary border-opacity-10 rounded-4 bg-light py-5">
                    <i class="bi bi-arrow-left-square fs-1 mb-3 text-secondary opacity-25"></i>
                    <h5 class="fw-normal">Vui lòng chọn <strong>Lớp chủ nhiệm</strong> ở danh sách bên trái.</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>