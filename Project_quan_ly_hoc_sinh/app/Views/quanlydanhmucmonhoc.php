<?php
// DANH SÁCH MÔN CHUẨN (Đã cập nhật GDPT 2018)
$monChuan = [
    "Toán học",
    "Ngữ văn",
    "Tiếng Anh",
    "Lịch sử",
    "Địa lí",
    "Vật lí",
    "Hóa học",
    "Sinh học",
    "Tin học",
    "Công nghệ",
    "Giáo dục công dân",
    "Giáo dục thể chất",
    "Giáo dục quốc phòng và an ninh",
    "Hoạt động trải nghiệm, hướng nghiệp",
    "Âm nhạc",
    "Mỹ thuật",
    "Tiếng Nhật",
    "Tiếng Hàn",
    "Tiếng Pháp",
    "Tiếng Trung"
];
?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold text-primary">
                    <i class="<?php echo $editData ? 'bi-pencil-square' : 'bi-plus-circle'; ?> me-2"></i>
                    <?php echo $editData ? 'Cập Nhật Môn Học' : 'Thêm Môn Học Mới'; ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="">
                    <?php if ($editData): ?>
                        <input type="hidden" name="id_mon" value="<?php echo $editData['id']; ?>">
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Tên môn học</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-book"></i></span>
                            <input type="text" name="ten_mon" class="form-control form-control-clean border-start-0"
                                list="dsMonChuan" placeholder="Chọn hoặc nhập..."
                                value="<?php echo $editData ? $editData['ten_mon'] : ''; ?>" required autocomplete="off">
                            <datalist id="dsMonChuan">
                                <?php foreach ($monChuan as $mon): ?>
                                    <option value="<?php echo $mon; ?>">
                                    <?php endforeach; ?>
                            </datalist>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="btnLuu" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                            <i class="bi bi-save me-1"></i> <?php echo $editData ? 'Lưu Thay Đổi' : 'Thêm Mới'; ?>
                        </button>
                        <?php if ($editData): ?>
                            <a href="index.php?act=quanlydanhmucmonhoc" class="btn btn-light rounded-pill py-2 text-secondary fw-bold">Hủy bỏ</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Danh Sách Môn Học</h5>
                <span class="badge bg-light text-secondary rounded-pill px-3 border">
                    Tổng: <?php echo $dsMonHoc ? $dsMonHoc->num_rows : 0; ?>
                </span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary text-uppercase small">ID</th>
                                <th class="py-3 text-secondary text-uppercase small">Tên Môn Học</th>
                                <th class="py-3 text-secondary text-uppercase small text-center">Trạng Thái</th>
                                <th class="pe-4 py-3 text-end text-secondary text-uppercase small">Hành động</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php if ($dsMonHoc && $dsMonHoc->num_rows > 0): ?>
                                <?php while ($row = $dsMonHoc->fetch_assoc()):
                                    $isActive = ($row['trang_thai'] == 1);
                                ?>
                                    <tr class="<?php echo $isActive ? '' : 'bg-light opacity-75'; ?>">
                                        <td class="ps-4 fw-bold text-muted">#<?php echo $row['id']; ?></td>
                                        <td>
                                            <span class="fw-bold <?php echo $isActive ? 'text-dark' : 'text-decoration-line-through text-muted'; ?>">
                                                <?php echo $row['ten_mon']; ?>
                                            </span>
                                            <?php if (in_array($row['ten_mon'], $monChuan) && $isActive): ?>
                                                <i class="bi bi-check-circle-fill text-success ms-2 small" title="Tên chuẩn"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($isActive): ?>
                                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Đang dạy</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">Đã khóa</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <?php if ($isActive): ?>
                                                <a href="index.php?act=quanlydanhmucmonhoc&edit_id=<?php echo $row['id']; ?>"
                                                    class="btn btn-sm btn-light text-primary rounded-circle shadow-sm me-1" title="Sửa tên">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <a href="index.php?act=quanlydanhmucmonhoc&toggle_id=<?php echo $row['id']; ?>&status=1"
                                                    class="btn btn-sm btn-light text-warning rounded-circle shadow-sm"
                                                    onclick="return confirm('Bạn muốn tạm ngưng môn <?php echo $row['ten_mon']; ?>? (Giáo viên sẽ không thấy môn này để chọn nữa)');"
                                                    title="Ngưng hoạt động">
                                                    <i class="bi bi-lock-fill"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="index.php?act=quanlydanhmucmonhoc&toggle_id=<?php echo $row['id']; ?>&status=0"
                                                    class="btn btn-sm btn-success text-white rounded-circle shadow-sm"
                                                    title="Kích hoạt lại">
                                                    <i class="bi bi-unlock-fill"></i>
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Chưa có dữ liệu.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>