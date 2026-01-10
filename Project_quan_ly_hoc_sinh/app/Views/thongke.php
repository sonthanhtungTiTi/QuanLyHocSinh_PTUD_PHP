<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Card thống kê số liệu */
    .stat-card {
        border: none;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        position: absolute;
        top: 15px;
        right: 15px;
        font-size: 3.5rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 5px;
    }

    /* Bộ lọc */
    .filter-wrapper {
        background: #fff;
        border-radius: 12px;
        padding: 10px 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
    }

    .form-select-clean {
        background-color: #f8fafc;
        border: 1px solid transparent;
        border-radius: 8px;
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
    }

    .form-select-clean:focus {
        background-color: #fff;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    /* Container biểu đồ */
    .chart-box {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        height: 100%;
        border: 1px solid #f1f5f9;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>

<div class="container-fluid px-4 mt-4">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-primary mb-1"><i class="bi bi-bar-chart-line-fill me-2"></i>THỐNG KÊ BÁO CÁO</h4>
            <p class="text-muted small mb-0">Tổng quan tình hình nhà trường</p>
        </div>

        <div class="filter-wrapper">
            <form method="GET" action="index.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="act" value="thongkebaocao">

                <div class="d-flex align-items-center text-muted small me-2">
                    <i class="bi bi-funnel-fill me-1"></i> Bộ lọc:
                </div>

                <select name="nam_id" class="form-select form-select-sm form-select-clean" style="width: 160px;" onchange="this.form.submit()">
                    <?php while ($n = $dsNamHoc->fetch_assoc()): ?>
                        <option value="<?php echo $n['id']; ?>" <?php echo ($filter_Nam == $n['id']) ? 'selected' : ''; ?>>
                            <?php echo $n['ten_nam']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="hk" class="form-select form-select-sm form-select-clean" style="width: 120px;" onchange="this.form.submit()">
                    <option value="HK1" <?php echo ($filter_HK == 'HK1') ? 'selected' : ''; ?>>Học Kỳ 1</option>
                    <option value="HK2" <?php echo ($filter_HK == 'HK2') ? 'selected' : ''; ?>>Học Kỳ 2</option>
                </select>

                <select name="lop_id" class="form-select form-select-sm form-select-clean" style="width: 150px;" onchange="this.form.submit()">
                    <option value="all" <?php echo ($filter_Lop == 'all') ? 'selected' : ''; ?>>Toàn Trường</option>
                    <?php if ($dsLop): $dsLop->data_seek(0);
                        while ($l = $dsLop->fetch_assoc()): ?>
                            <option value="<?php echo $l['id']; ?>" <?php echo ($filter_Lop == $l['id']) ? 'selected' : ''; ?>>
                                Lớp <?php echo $l['ten_lop']; ?>
                            </option>
                    <?php endwhile;
                    endif; ?>
                </select>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="card-body p-4">
                    <h6 class="text-secondary text-uppercase fw-bold small ls-1 mb-3">Tổng Học Sinh</h6>
                    <div class="stat-value text-primary"><?php echo $tongQuan['hs']; ?></div>
                    <div class="text-muted small"><i class="bi bi-people-fill me-1"></i> Học sinh đang theo học</div>
                    <i class="bi bi-mortarboard-fill stat-icon text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="card-body p-4">
                    <h6 class="text-secondary text-uppercase fw-bold small ls-1 mb-3">Tổng Giáo Viên</h6>
                    <div class="stat-value text-success"><?php echo $tongQuan['gv']; ?></div>
                    <div class="text-muted small"><i class="bi bi-person-badge-fill me-1"></i> Cán bộ giảng dạy</div>
                    <i class="bi bi-briefcase-fill stat-icon text-success"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card">
                <div class="card-body p-4">
                    <h6 class="text-secondary text-uppercase fw-bold small ls-1 mb-3">Tổng Lớp Học</h6>
                    <div class="stat-value text-warning"><?php echo $tongQuan['lop']; ?></div>
                    <div class="text-muted small"><i class="bi bi-shop me-1"></i> Lớp học hoạt động</div>
                    <i class="bi bi-bank2 stat-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-6">
            <div class="chart-box">
                <div class="chart-header">
                    <h6 class="fw-bold text-dark m-0">TỶ LỆ HỌC LỰC</h6>
                    <span class="badge bg-light text-secondary border"><?php echo ($filter_Lop == 'all') ? 'Toàn trường' : 'Theo lớp'; ?></span>
                </div>

                <div class="chart-container d-flex justify-content-center">
                    <?php if (array_sum($dataHL) > 0): ?>
                        <canvas id="chartHocLuc"></canvas>
                    <?php else: ?>
                        <div class="text-center align-self-center text-muted">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="mb-3 opacity-25" alt="Empty">
                            <p class="small">Chưa có dữ liệu xếp loại</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="chart-box">
                <div class="chart-header">
                    <h6 class="fw-bold text-dark m-0">TỶ LỆ HẠNH KIỂM</h6>
                    <span class="badge bg-light text-secondary border"><?php echo ($filter_Lop == 'all') ? 'Toàn trường' : 'Theo lớp'; ?></span>
                </div>

                <div class="chart-container">
                    <?php if (array_sum($dataHKiem) > 0): ?>
                        <canvas id="chartHanhKiem"></canvas>
                    <?php else: ?>
                        <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="60" class="mb-3 opacity-25" alt="Empty">
                            <p class="small">Chưa có dữ liệu hạnh kiểm</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Dữ liệu từ PHP
    const dataHL = <?php echo json_encode(array_values($dataHL)); ?>;
    const dataHK = <?php echo json_encode(array_values($dataHKiem)); ?>;

    // Config chung Chart.js
    Chart.defaults.font.family = "'Plus Jakarta Sans', 'Segoe UI', sans-serif";
    Chart.defaults.color = '#64748b';
    Chart.defaults.scale.grid.color = '#f1f5f9';

    // 1. BIỂU ĐỒ HỌC LỰC (Doughnut)
    if (document.getElementById('chartHocLuc') && dataHL.reduce((a, b) => a + b, 0) > 0) {
        new Chart(document.getElementById('chartHocLuc'), {
            type: 'doughnut',
            data: {
                labels: ['Giỏi', 'Khá', 'Trung Bình', 'Yếu', 'Kém'],
                datasets: [{
                    data: dataHL,
                    backgroundColor: [
                        '#10b981', // Giỏi - Xanh lá
                        '#3b82f6', // Khá - Xanh dương
                        '#f59e0b', // TB - Vàng cam
                        '#f97316', // Yếu - Cam
                        '#ef4444' // Kém - Đỏ
                    ],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                cutout: '70%', // Lỗ rỗng ở giữa lớn hơn cho đẹp
                layout: {
                    padding: 10
                }
            }
        });
    }

    // 2. BIỂU ĐỒ HẠNH KIỂM (Bar)
    if (document.getElementById('chartHanhKiem') && dataHK.reduce((a, b) => a + b, 0) > 0) {
        new Chart(document.getElementById('chartHanhKiem'), {
            type: 'bar',
            data: {
                labels: ['Tốt', 'Khá', 'Trung Bình', 'Yếu'],
                datasets: [{
                    label: 'Số lượng HS',
                    data: dataHK,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderRadius: 6, // Bo góc cột
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            borderDash: [4, 4]
                        } // Kẻ ngang nét đứt
                    },
                    x: {
                        grid: {
                            display: false
                        } // Ẩn kẻ dọc
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }, // Ẩn chú thích vì màu cột đã rõ
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8
                    }
                }
            }
        });
    }
</script>