<style>
    /* Card form xin nghỉ */
    .leave-form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        padding: 20px;
        color: white;
    }

    /* Input Clean */
    .form-control-clean {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 10px 15px;
        transition: all 0.2s;
    }

    .form-control-clean:focus {
        background-color: #fff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Lịch sử dạng Timeline */
    .history-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        height: 100%;
    }

    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 25px;
        border-left: 2px solid #e2e8f0;
    }

    .timeline-item:last-child {
        margin-bottom: 0;
        border-left-color: transparent;
    }

    .timeline-dot {
        position: absolute;
        left: -6px;
        top: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #cbd5e1;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #cbd5e1;
    }

    .timeline-item.active .timeline-dot {
        background: #3b82f6;
        box-shadow: 0 0 0 2px #bfdbfe;
    }

    .timeline-date {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Card con trong timeline */
    .request-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 15px;
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }

    .request-card:hover {
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #e2e8f0;
        transform: translateY(-2px);
    }

    /* Ảnh thumbnail */
    .img-thumb {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .img-thumb:hover {
        transform: scale(1.1);
    }
</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1"><i class="bi bi-send-check-fill me-2"></i>XIN PHÉP NGHỈ HỌC</h4>
            <p class="text-muted small mb-0">Gửi đơn xin phép trực tuyến tới GVCN.</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill">
                <i class="bi bi-person-circle me-1"></i> <?php echo $hs['ho_ten']; ?>
            </span>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-4 mb-4">
            <div class="leave-form-card">
                <div class="form-header">
                    <h5 class="fw-bold mb-1"><i class="bi bi-pencil-fill me-2 opacity-75"></i>Soạn đơn mới</h5>
                    <small class="opacity-75">Điền đầy đủ thông tin bên dưới</small>
                </div>

                <div class="card-body p-4">
                    <?php if (isset($errorMsg) && !empty($errorMsg)): ?>
                        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small rounded-3 mb-3">
                            <i class="bi bi-exclamation-circle-fill me-1"></i> <?php echo $errorMsg; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-bold small text-secondary">Từ ngày</label>
                                <input type="date" name="tu_ngay" id="tu_ngay" class="form-control form-control-clean" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold small text-secondary">Đến ngày</label>
                                <input type="date" name="den_ngay" id="den_ngay" class="form-control form-control-clean" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Lý do nghỉ</label>
                            <textarea name="ly_do" class="form-control form-control-clean" rows="4" placeholder="VD: Em bị ốm sốt, gia đình có việc bận..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-danger">Ảnh minh chứng (Bắt buộc)</label>
                            <input type="file" name="file_minh_chung" class="form-control form-control-clean" accept="image/*" required>
                            <div class="form-text small mt-2">
                                <i class="bi bi-info-circle me-1"></i> Chụp ảnh đơn thuốc hoặc đơn viết tay của phụ huynh.
                            </div>
                        </div>

                        <button type="submit" name="btnGuiDon" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm hover-up">
                            <i class="bi bi-send-fill me-2"></i> GỬI ĐƠN NGAY
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="history-card shadow-sm">
                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-uppercase text-secondary mb-0 ls-1">Lịch sử gửi đơn</h6>
                    <span class="badge bg-light text-dark border"><?php echo ($dsDon) ? $dsDon->num_rows : 0; ?></span>
                </div>

                <div class="card-body px-4 pb-4 pt-2">
                    <?php if ($dsDon && $dsDon->num_rows > 0): ?>
                        <div class="mt-2">
                            <?php while ($row = $dsDon->fetch_assoc()):
                                // Status Config
                                $statusClass = '';
                                $statusIcon = '';
                                $statusText = '';
                                $timelineClass = '';
                                if ($row['trang_thai'] == 0) {
                                    $statusClass = 'bg-warning bg-opacity-10 text-warning border-warning';
                                    $statusIcon = 'bi-hourglass-split';
                                    $statusText = 'Chờ duyệt';
                                    $timelineClass = 'active'; // Mới nhất thường là chờ duyệt
                                } elseif ($row['trang_thai'] == 1) {
                                    $statusClass = 'bg-success bg-opacity-10 text-success border-success';
                                    $statusIcon = 'bi-check-circle-fill';
                                    $statusText = 'Đã duyệt';
                                } else {
                                    $statusClass = 'bg-danger bg-opacity-10 text-danger border-danger';
                                    $statusIcon = 'bi-x-circle-fill';
                                    $statusText = 'Từ chối';
                                }
                            ?>
                                <div class="timeline-item <?php echo $timelineClass; ?>">
                                    <div class="timeline-dot"></div>

                                    <div class="timeline-date">
                                        <?php echo date('d/m/Y H:i', strtotime($row['ngay_gui'])); ?>
                                    </div>

                                    <div class="request-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="badge <?php echo $statusClass; ?> rounded-pill px-3 mb-2 border">
                                                    <i class="bi <?php echo $statusIcon; ?> me-1"></i> <?php echo $statusText; ?>
                                                </span>
                                                <div class="text-dark fw-bold">
                                                    Nghỉ từ: <span class="text-primary"><?php echo date('d/m', strtotime($row['tu_ngay'])); ?></span>
                                                    <i class="bi bi-arrow-right mx-1 text-muted small"></i>
                                                    <span class="text-primary"><?php echo date('d/m', strtotime($row['den_ngay'])); ?></span>
                                                </div>
                                            </div>

                                            <?php if ($row['minh_chung'] && file_exists($row['minh_chung'])): ?>
                                                <a href="<?php echo $row['minh_chung']; ?>" target="_blank">
                                                    <img src="<?php echo $row['minh_chung']; ?>" class="img-thumb shadow-sm" alt="Minh chứng">
                                                </a>
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted border img-thumb">
                                                    <i class="bi bi-image-alt"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <p class="text-muted small mb-0 fst-italic border-start border-3 ps-3">
                                            "<?php echo $row['ly_do']; ?>"
                                        </p>

                                        <?php if ($row['gv_phan_hoi']): ?>
                                            <div class="mt-3 pt-2 border-top">
                                                <small class="fw-bold text-dark"><i class="bi bi-chat-quote-fill me-1 text-secondary"></i> GVCN Phản hồi:</small>
                                                <span class="text-secondary small ms-1"><?php echo $row['gv_phan_hoi']; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-block p-4 mb-3 text-secondary">
                                <i class="bi bi-inbox fs-1 opacity-25"></i>
                            </div>
                            <p class="text-muted small">Bạn chưa gửi đơn xin nghỉ nào.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tuNgayInput = document.getElementById('tu_ngay');
        const denNgayInput = document.getElementById('den_ngay');

        // ===> KHẮC PHỤC LỖI MÚI GIỜ <===
        // Thay vì dùng toISOString() (UTC), ta tự ghép chuỗi theo giờ máy tính
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0 nên phải +1
        const day = String(date.getDate()).padStart(2, '0');

        // Chuỗi ngày hôm nay: YYYY-MM-DD
        const today = `${year}-${month}-${day}`;

        // Thiết lập min = hôm nay
        tuNgayInput.min = today;
        denNgayInput.min = today;

        // Logic khi chọn "Từ ngày"
        tuNgayInput.addEventListener('change', function() {
            const selectedDate = this.value;

            // Cập nhật min cho đến ngày
            denNgayInput.min = selectedDate;

            // Nếu đến ngày đang chọn < từ ngày -> Reset
            if (denNgayInput.value && denNgayInput.value < selectedDate) {
                denNgayInput.value = selectedDate;
            }
        });
    });
</script>