<style>
    /* --- BẢNG ĐIỂM SỬA LỖI --- */
    
    /* Container bảng */
    .table-responsive {
        border-radius: 0 0 12px 12px;
        overflow-x: auto; /* Cho phép cuộn ngang nếu màn hình nhỏ */
    }

    .table-view-score {
        width: 100%;
        min-width: 1000px; /* Đảm bảo bảng không bị co dúm */
        border-collapse: collapse;
    }

    /* Header chuẩn, bỏ Sticky để tránh lỗi lệch */
    .table-view-score thead th {
        background-color: #f1f5f9;
        color: #334155;
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 700;
        text-align: center;
        vertical-align: middle;
        border: 1px solid #e2e8f0;
        padding: 10px 5px;
    }

    /* Các ô dữ liệu */
    .table-view-score tbody td {
        vertical-align: middle;
        padding: 8px 5px;
        border: 1px solid #f1f5f9;
        color: #334155;
        font-weight: 500;
        text-align: center;
        font-size: 0.9rem;
    }

    /* Highlight cột ĐTB */
    .col-average {
        background-color: #f0f9ff !important;
        color: #0284c7;
        font-weight: 800;
        border-left: 2px solid #e2e8f0 !important;
    }

    /* Điểm kém */
    .score-bad { color: #dc2626; font-weight: 700; }
    /* Điểm giỏi */
    .score-good { color: #16a34a; font-weight: 700; }
    
    /* Canh lề tên học sinh */
    .td-name {
        text-align: left !important;
        padding-left: 15px !important;
        font-weight: 600;
        color: #1e293b;
    }
</style>

<div class="container mt-4">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        
        <div class="card-header bg-white border-0 py-4 px-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-circle me-3">
                        <i class="bi bi-table fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-1">BẢNG ĐIỂM CHI TIẾT</h5>
                        <div class="d-flex align-items-center gap-3 text-muted small">
                            <span><i class="bi bi-people-fill me-1"></i> Lớp: <strong class="text-dark"><?php echo $tenLop; ?></strong></span>
                            <span class="border-start ps-3"><i class="bi bi-journal-bookmark-fill me-1"></i> Học kỳ: <strong class="text-primary"><?php echo $hk; ?></strong></span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <form method="GET" action="" class="d-flex">
                        <input type="hidden" name="act" value="xembangdiem">
                        <input type="hidden" name="lop_id" value="<?php echo $lopId; ?>">
                        <input type="hidden" name="mon_id" value="<?php echo $monId; ?>">
                        <input type="hidden" name="ten_lop" value="<?php echo $tenLop; ?>">
                        
                        <div class="bg-light p-1 rounded-pill d-inline-flex border">
                            <button type="submit" name="hk" value="HK1" 
                                class="btn btn-sm rounded-pill px-4 fw-bold <?php echo ($hk == 'HK1') ? 'btn-white shadow-sm text-primary' : 'text-muted'; ?>">
                                HK1
                            </button>
                            <button type="submit" name="hk" value="HK2" 
                                class="btn btn-sm rounded-pill px-4 fw-bold <?php echo ($hk == 'HK2') ? 'btn-white shadow-sm text-primary' : 'text-muted'; ?>">
                                HK2
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="">
                        <button type="submit" name="btnExportOnly" class="btn btn-success rounded-pill fw-bold shadow-sm px-4 btn-sm py-2 ms-2">
                            <i class="bi bi-file-earmark-excel-fill me-2"></i> Xuất Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-view-score">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 50px;">STT</th>
                            <th rowspan="2" style="width: 100px;">Mã HS</th>
                            <th rowspan="2" style="min-width: 200px;">Họ và Tên</th>
                            
                            <th colspan="2">Miệng (HS1)</th>
                            <th colspan="2">15 Phút (HS1)</th>
                            
                            <th rowspan="2" style="width: 80px;">1 Tiết<br>(HS2)</th>
                            <th rowspan="2" style="width: 80px;">Cuối Kỳ<br>(HS3)</th>
                            <th rowspan="2" class="col-average" style="width: 80px;">ĐTB</th>
                        </tr>
                        <tr>
                            <th style="width: 50px;">L1</th>
                            <th style="width: 50px;">L2</th>
                            <th style="width: 50px;">L1</th>
                            <th style="width: 50px;">L2</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($dsDiem && $dsDiem->num_rows > 0):
                            $stt = 1;
                            $dsDiem->data_seek(0); // Reset pointer
                            while ($row = $dsDiem->fetch_assoc()):
                                // Xử lý hiển thị màu điểm
                                $dtb = $row['diem_tb'];
                                $classDTB = '';
                                if ($dtb !== NULL && $dtb !== '') {
                                    if ($dtb < 5.0) $classDTB = 'score-bad';
                                    elseif ($dtb >= 8.0) $classDTB = 'score-good';
                                }
                        ?>
                            <tr>
                                <td class="text-muted fw-bold"><?php echo $stt++; ?></td>
                                <td class="font-monospace text-secondary small"><?php echo $row['ma_hs']; ?></td>
                                <td class="td-name"><?php echo $row['ho_ten']; ?></td>

                                <td><?php echo $row['diem_mieng_1']; ?></td>
                                <td><?php echo $row['diem_mieng_2']; ?></td>

                                <td><?php echo $row['diem_15p_1']; ?></td>
                                <td><?php echo $row['diem_15p_2']; ?></td>

                                <td class="fw-bold text-dark bg-light"><?php echo $row['diem_1tiet']; ?></td>
                                <td class="fw-bold text-primary bg-light"><?php echo $row['diem_thi']; ?></td>

                                <td class="col-average <?php echo $classDTB; ?>">
                                    <?php echo ($dtb !== NULL && $dtb !== '') ? $dtb : '-'; ?>
                                </td>
                            </tr>
                        <?php endwhile; 
                        else: ?>
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-inbox fs-1 opacity-25 mb-2"></i>
                                        <p>Chưa có dữ liệu điểm cho lớp này.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top-0 py-3 px-4 text-end">
            <a href="index.php?act=xemdanhsachlopday" class="btn btn-light rounded-pill shadow-sm text-secondary fw-bold px-4 hover-up">
                <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách lớp
            </a>
        </div>
    </div>
</div>