<style>
    /* Card bao quanh */
    .card-box {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    /* Bảng chuẩn, căn chỉnh tốt */
    .table-balanced thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
        padding: 15px;
        white-space: nowrap;
        /* Không xuống dòng tiêu đề */
    }

    .table-balanced tbody td {
        padding: 15px;
        vertical-align: middle !important;
        /* Quan trọng: Giúp không bị lệch */
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
    }

    /* Avatar học sinh */
    .avatar-cell {
        width: 40px;
        height: 40px;
        background-color: #eff6ff;
        color: #3b82f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 12px;
        border: 1px solid #dbeafe;
    }

    /* Khối thông tin thời gian */
    .time-box {
        background: #fffbeb;
        color: #b45309;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 5px;
        border: 1px solid #fcd34d;
    }

    /* Badge trạng thái */
    .status-badge {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        min-width: 100px;
        /* Độ rộng tối thiểu để thẳng hàng */
    }
</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="bi bi-check-circle-fill me-2"></i>Duyệt Đơn Xin Phép
        </h4>
        <div class="text-muted small">
            Danh sách đơn cần xử lý: <strong><?php echo ($dsDon) ? $dsDon->num_rows : 0; ?></strong>
        </div>
    </div>

    <div class="card-box">
        <div class="table-responsive">
            <table class="table table-hover table-balanced mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="50px">STT</th>
                        <th width="250px">Học sinh</th>
                        <th>Nội dung xin nghỉ</th>
                        <th class="text-center" width="100px">Ảnh</th>
                        <th class="text-center" width="140px">Trạng thái</th>
                        <th class="text-center" width="120px">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($dsDon && $dsDon->num_rows > 0):
                        $stt = 1;
                        while ($row = $dsDon->fetch_assoc()):
                            $trangThai = $row['trang_thai'];
                            $modalId = "modalXuLy_" . $row['id'];
                            $modalAnhId = "modalAnh_" . $row['id'];
                    ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?php echo $stt++; ?></td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-cell">
                                            <?php echo substr($row['ho_ten'], 0, 1); ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?php echo $row['ho_ten']; ?></div>
                                            <div class="small text-muted">MS: <?php echo $row['ma_hs']; ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="time-box">
                                        <?php echo date('d/m', strtotime($row['tu_ngay'])); ?>
                                        <i class="bi bi-arrow-right mx-1"></i>
                                        <?php echo date('d/m', strtotime($row['den_ngay'])); ?>
                                    </div>
                                    <div class="fst-italic text-secondary small">
                                        "<?php echo $row['ly_do']; ?>"
                                    </div>
                                    <?php if ($row['gv_phan_hoi']): ?>
                                        <div class="mt-1 text-primary small fw-bold">
                                            <i class="bi bi-reply-fill"></i> <?php echo $row['gv_phan_hoi']; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($row['minh_chung'] && file_exists($row['minh_chung'])): ?>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#<?php echo $modalAnhId; ?>">
                                            <img src="<?php echo $row['minh_chung']; ?>"
                                                class="rounded border shadow-sm"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        </a>

                                        <div class="modal fade" id="<?php echo $modalAnhId; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content bg-transparent border-0">
                                                    <div class="modal-body text-center p-0 position-relative">
                                                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
                                                        <img src="<?php echo $row['minh_chung']; ?>" class="img-fluid rounded shadow" style="max-height: 80vh;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted opacity-25">-</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($trangThai == 0): ?>
                                        <span class="status-badge bg-warning bg-opacity-10 text-warning border border-warning">Chờ duyệt</span>
                                    <?php elseif ($trangThai == 1): ?>
                                        <span class="status-badge bg-success bg-opacity-10 text-success border border-success">Đồng ý</span>
                                    <?php else: ?>
                                        <span class="status-badge bg-danger bg-opacity-10 text-danger border border-danger">Từ chối</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php if ($trangThai == 0): ?>
                                        <button class="btn btn-primary btn-sm fw-bold px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#<?php echo $modalId; ?>">
                                            Duyệt
                                        </button>

                                        <div class="modal fade" id="<?php echo $modalId; ?>" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h6 class="modal-title fw-bold text-uppercase">Xử lý đơn: <?php echo $row['ho_ten']; ?></h6>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form method="POST" action="">
                                                        <div class="modal-body text-start p-4">
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold text-muted text-uppercase">Phản hồi lại học sinh</label>
                                                                <textarea name="phan_hoi" class="form-control" rows="3" placeholder="Nhập lời nhắn..."></textarea>
                                                            </div>
                                                            <input type="hidden" name="don_id" value="<?php echo $row['id']; ?>">

                                                            <div class="d-flex gap-2 pt-2 border-top mt-4">
                                                                <button type="submit" name="btnXuLy" value="2" class="btn btn-outline-danger flex-grow-1 fw-bold">
                                                                    <i class="bi bi-x-circle me-1"></i> Từ chối
                                                                </button>
                                                                <button type="submit" name="btnXuLy" value="1" class="btn btn-success flex-grow-1 fw-bold">
                                                                    <i class="bi bi-check-circle me-1"></i> Đồng ý
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-success small"><i class="bi bi-check-all"></i> Xong</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 opacity-25 d-block mb-2"></i>
                                Không có đơn xin phép nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>