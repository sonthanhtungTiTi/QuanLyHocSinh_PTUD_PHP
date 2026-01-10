<style>
    /* Card Container */
    .class-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: none;
        overflow: hidden;
    }

    /* Header */
    .card-header-custom {
        background: linear-gradient(to right, #ffffff, #f0fdf4);
        border-bottom: 1px solid #dcfce7;
        padding: 20px 25px;
    }

    /* Bảng danh sách */
    .table-custom th {
        background-color: #f8fafc;
        color: #166534;
        /* Xanh lá đậm */
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding: 15px 10px;
    }

    .table-custom td {
        vertical-align: middle;
        padding: 12px 10px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Hover hàng */
    .table-custom tbody tr:hover td {
        background-color: #f0fdf4;
    }

    /* Mã HS Badge */
    .badge-code {
        background-color: #f1f5f9;
        color: #475569;
        font-family: 'Consolas', monospace;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }

    /* Badge Giới tính */
    .badge-gender {
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .gender-male {
        background-color: #e0f2fe;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }

    .gender-female {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
</style>

<div class="container mt-4">
    <div class="class-card">

        <div class="card-header-custom border-start border-4 border-success">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold text-success mb-1">
                        <i class="bi bi-people-fill me-2"></i> DANH SÁCH LỚP CHỦ NHIỆM
                    </h4>
                    <?php if (isset($data) && $data): ?>
                        <div class="text-muted small">
                            Lớp: <span class="fw-bold text-dark fs-5 ms-1"><?php echo $data['lop']['ten_lop']; ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (isset($data) && $data): ?>
                    <form method="POST" action="">
                        <button type="submit" name="btnExport" class="btn btn-success fw-bold shadow-sm rounded-pill px-4">
                            <i class="bi bi-file-earmark-excel me-2"></i> Xuất Excel
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <div class="card-body p-0">
            <?php if (isset($data) && $data): ?>
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" width="60px">STT</th>
                                <th width="15%">Mã HS</th>
                                <th width="25%">Họ và Tên</th>
                                <th class="text-center" width="15%">Ngày sinh</th>
                                <th class="text-center" width="10%">Giới tính</th>
                                <th>Địa chỉ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ds = $data['hoc_sinh'];
                            if ($ds && $ds->num_rows > 0):
                                $stt = 1;
                                $ds->data_seek(0);
                                while ($row = $ds->fetch_assoc()):
                            ?>
                                    <tr>
                                        <td class="text-center fw-bold text-muted"><?php echo $stt++; ?></td>

                                        <td>
                                            <span class="badge-code">
                                                <?php echo $row['ma_hs']; ?>
                                            </span>
                                        </td>

                                        <td class="fw-bold text-dark">
                                            <?php echo $row['ho_ten']; ?>
                                        </td>

                                        <td class="text-center text-secondary">
                                            <?php echo date('d/m/Y', strtotime($row['ngay_sinh'])); ?>
                                        </td>

                                        <td class="text-center">
                                            <?php if (strtolower($row['gioi_tinh']) == 'nam'): ?>
                                                <span class="badge-gender gender-male">Nam</span>
                                            <?php else: ?>
                                                <span class="badge-gender gender-female">Nữ</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-muted small text-truncate" style="max-width: 250px;">
                                            <i class="bi bi-geo-alt me-1 opacity-50"></i> <?php echo $row['dia_chi']; ?>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 opacity-25"></i>
                                        <p class="mt-2">Lớp chưa có học sinh nào.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 bg-light border-top text-end">
                    <span class="text-muted small fst-italic">
                        Tổng số học sinh: <strong class="text-dark"><?php echo $ds->num_rows; ?></strong> em.
                    </span>
                </div>

            <?php else: ?>
                <div class="p-5 text-center">
                    <div class="alert alert-warning d-inline-block text-start shadow-sm border-0" style="max-width: 500px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-2 me-3 text-warning"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Thông báo</h6>
                                <span>Bạn chưa được phân công chủ nhiệm lớp nào trong năm học này.</span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>