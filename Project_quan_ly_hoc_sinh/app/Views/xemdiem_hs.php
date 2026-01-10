<style>
    /* --- CARD THÔNG TIN HỌC SINH --- */
    .student-card {
        background: linear-gradient(to right, #ffffff, #f8faff);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        margin-bottom: 25px;
    }

    .avatar-box {
        width: 50px;
        height: 50px;
        background: #3b82f6;
        color: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }

    /* --- BẢNG ĐIỂM RÕ RÀNG (ZEBRA STYLE) --- */
    .table-clear {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #cbd5e1;
        /* Viền bao ngoài */
    }

    /* Header */
    .table-clear thead th {
        background-color: #f1f5f9;
        color: #334155;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 12px 5px;
        text-align: center;
        vertical-align: middle;
        border-bottom: 2px solid #cbd5e1;
        border-right: 1px solid #e2e8f0;
    }

    /* Body - Dòng dữ liệu */
    .table-clear tbody td {
        padding: 12px 8px;
        /* Tăng padding để dòng cao hơn */
        vertical-align: middle;
        color: #1e293b;
        font-size: 0.95rem;
        border-bottom: 1px solid #e2e8f0;
        border-right: 1px dashed #e2e8f0;
        /* Kẻ dọc nét đứt mờ giữa các điểm */
    }

    /* Hiệu ứng Zebra: Dòng chẵn có màu nền khác dòng lẻ */
    .table-clear tbody tr:nth-child(even) {
        background-color: #f8fafc;
    }

    /* Hiệu ứng Hover: Khi di chuột vào dòng nào, dòng đó đậm lên */
    .table-clear tbody tr:hover {
        background-color: #e0f2fe !important;
        /* Xanh nhạt */
        transition: background-color 0.1s;
    }

    /* Cột Tên Môn */
    .td-mon {
        text-align: left;
        font-weight: 600;
        padding-left: 15px !important;
        border-right: 2px solid #cbd5e1 !important;
        /* Vách ngăn đậm sau tên môn */
    }

    /* Nhóm điểm quan trọng (Có vách ngăn đậm hơn chút) */
    .border-group-end {
        border-right: 2px solid #e2e8f0 !important;
    }

    /* Cột ĐTB */
    .td-dtb {
        background-color: #fff7ed;
        /* Vàng kem nhạt */
        color: #c2410c;
        font-weight: 800;
        text-align: center;
        border-left: 2px solid #fed7aa !important;
    }

    /* Ghi đè màu nền ĐTB khi dòng chẵn */
    .table-clear tbody tr:nth-child(even) .td-dtb {
        background-color: #ffedd5;
    }

    /* Tổng kết cuối trang */
    .summary-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        height: 100%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
    }

    .lbl {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        display: block;
        margin-bottom: 5px;
    }

    .val {
        font-size: 1.5rem;
        font-weight: 800;
        color: #334155;
    }
</style>

<div class="container mt-4 mb-5">

    <div class="student-card">
        <div class="d-flex align-items-center">
            <div class="avatar-box me-3"><?php echo substr($hs['ho_ten'], 0, 1); ?></div>
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1 text-dark"><?php echo $hs['ho_ten']; ?></h5>
                <div class="text-muted small">
                    <span class="me-3">MSSV: <strong><?php echo $hs['ma_hs']; ?></strong></span>
                    <span>Lớp: <strong><?php echo $hs['ten_lop']; ?></strong></span>
                </div>
            </div>

            <div class="btn-group">
                <a href="index.php?act=xemdiem&hk=HK1" class="btn btn-sm fw-bold <?php echo ($selectedHK == 'HK1') ? 'btn-primary' : 'btn-outline-secondary border-0 bg-white'; ?>">HK1</a>
                <a href="index.php?act=xemdiem&hk=HK2" class="btn btn-sm fw-bold <?php echo ($selectedHK == 'HK2') ? 'btn-primary' : 'btn-outline-secondary border-0 bg-white'; ?>">HK2</a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="m-0 fw-bold text-primary text-uppercase">
                <i class="bi bi-list-ol me-2"></i> Bảng điểm chi tiết
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table-clear">
                <thead>
                    <tr>
                        <th style="width: 50px;">STT</th>
                        <th style="min-width: 180px;" class="text-start ps-3 border-group-end">Môn Học</th>

                        <th colspan="2" class="border-group-end">Miệng </th>
                        <th colspan="2" class="border-group-end">15 Phút </th>

                        <th class="border-group-end" style="width: 80px;">1 Tiết )</th>
                        <th style="width: 80px;">Thi HK </th>
                        <th class="td-dtb" style="width: 80px;">ĐTB</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($dsDiem && $dsDiem->num_rows > 0):
                        $stt = 1;
                        while ($row = $dsDiem->fetch_assoc()):
                            $dtb = $row['diem_tb'];
                            $dtbDisplay = ($dtb !== NULL && $dtb !== '') ? $dtb : '-';

                            // Tô màu ĐTB nếu yếu hoặc giỏi
                            $colorDTB = 'text-dark';
                            if ($dtb !== NULL && $dtb !== '') {
                                if ($dtb < 5.0) $colorDTB = 'text-danger';
                                elseif ($dtb >= 8.0) $colorDTB = 'text-primary';
                            }
                    ?>
                            <tr>
                                <td class="text-center text-muted font-monospace"><?php echo $stt++; ?></td>

                                <td class="td-mon">
                                    <?php echo $row['ten_mon']; ?>
                                </td>

                                <td class="text-center"><?php echo ($row['diem_mieng_1']) ?: '-'; ?></td>
                                <td class="text-center border-group-end"><?php echo ($row['diem_mieng_2']) ?: '-'; ?></td>

                                <td class="text-center"><?php echo ($row['diem_15p_1']) ?: '-'; ?></td>
                                <td class="text-center border-group-end"><?php echo ($row['diem_15p_2']) ?: '-'; ?></td>

                                <td class="text-center fw-bold text-secondary border-group-end">
                                    <?php echo ($row['diem_1tiet']) ?: '-'; ?>
                                </td>

                                <td class="text-center fw-bold text-dark">
                                    <?php echo ($row['diem_thi']) ?: '-'; ?>
                                </td>

                                <td class="td-dtb <?php echo $colorDTB; ?>">
                                    <?php echo $dtbDisplay; ?>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                Chưa có dữ liệu điểm.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($tongKet): ?>
        <h6 class="fw-bold text-secondary mb-3 ps-1">TỔNG KẾT HỌC KỲ</h6>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <div class="summary-item border-primary border-opacity-25 bg-primary bg-opacity-10">
                    <span class="lbl text-primary">ĐTB Chung</span>
                    <span class="val text-primary"><?php echo $tongKet['dtb_tat_ca_mon']; ?></span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <span class="lbl">Học Lực</span>
                    <span class="val text-success">
                        <?php
                        $hlMap = ['Gioi' => 'Giỏi', 'Kha' => 'Khá', 'TB' => 'TB', 'Yeu' => 'Yếu', 'Kem' => 'Kém'];
                        echo isset($hlMap[$tongKet['hoc_luc']]) ? $hlMap[$tongKet['hoc_luc']] : $tongKet['hoc_luc'];
                        ?>
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <span class="lbl">Hạnh Kiểm</span>
                    <span class="val text-info">
                        <?php
                        $hkMap = ['Tot' => 'Tốt', 'Kha' => 'Khá', 'TB' => 'TB', 'Yeu' => 'Yếu'];
                        echo isset($hkMap[$tongKet['hanh_kiem']]) ? $hkMap[$tongKet['hanh_kiem']] : $tongKet['hanh_kiem'];
                        ?>
                    </span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-item">
                    <span class="lbl">Danh Hiệu</span>
                    <span class="val text-warning" style="font-size: 1.1rem;">
                        <?php
                        if ($tongKet['danh_hieu'] == 'Hoc sinh Gioi') echo '<i class="bi bi-star-fill"></i> HS Giỏi';
                        elseif ($tongKet['danh_hieu'] == 'Hoc sinh Tien tien') echo 'HS Tiên Tiến';
                        else echo '-';
                        ?>
                    </span>
                </div>
            </div>
        </div>

        <?php if (!empty($tongKet['nhan_xet'])): ?>
            <div class="mt-3 p-3 bg-white border rounded-3 text-center shadow-sm">
                <i class="bi bi-chat-quote fs-4 text-muted mb-2 d-block"></i>
                <p class="mb-0 fst-italic text-dark">"<?php echo $tongKet['nhan_xet']; ?>"</p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>