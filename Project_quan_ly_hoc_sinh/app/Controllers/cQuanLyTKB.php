<?php
require_once("app/Models/mThoiKhoaBieu.php");
require_once("app/Models/mNamHoc.php");
require_once("app/Models/mLop.php");
require_once("app/Models/mMonHoc.php");

class cQuanLyTKB
{
    private $model;
    private $modelNam;
    private $modelLop;
    private $modelMon;

    public function __construct()
    {
        $this->model = new mThoiKhoaBieu();
        $this->modelNam = new mNamHoc();
        $this->modelLop = new mLop();
        $this->modelMon = new mMonHoc();
    }

    public function hienThiGiaoDien()
    {
        // 1. Check Quyền Admin
        if (!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
            echo "<script>alert('Bạn không có quyền!'); window.location.href='index.php';</script>";
            return;
        }

        $namHienTai = $this->modelNam->getNamHocHienTai();

        // Lấy tham số filter
        $selectedLop = isset($_GET['lop_id']) ? $_GET['lop_id'] : null;
        $selectedHK = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';

        // Lấy dữ liệu dropdown
        $dsLop = $this->modelLop->getAllLop($namHienTai);
        $dsMon = $this->modelMon->getAllActive();

        // Chuyển danh sách môn thành mảng
        $arrMon = [];
        if ($dsMon) {
            while ($m = $dsMon->fetch_assoc()) $arrMon[] = $m;
        }

        // =================================================================
        // XỬ LÝ LƯU THỜI KHÓA BIỂU (LOGIC CHECK TRÙNG)
        // =================================================================
        if (isset($_POST['btnLuuTKB'])) {
            $lop_id_save = $_POST['lop_id'];
            $hk_save = $_POST['hk'];
            $tkb_input = $_POST['tkb']; // Mảng 2 chiều từ Form

            $errors = []; // Mảng chứa các lỗi trùng lịch
            $countSaved = 0;

            foreach ($tkb_input as $thu => $cac_tiet) {
                foreach ($cac_tiet as $tiet => $mon_id) {

                    // CASE 1: XÓA TIẾT (Chọn rỗng)
                    if (empty($mon_id)) {
                        $this->model->saveTietHoc($namHienTai, $hk_save, $lop_id_save, $thu, $tiet, '', '');
                        continue;
                    }

                    // CASE 2: CÓ CHỌN MÔN -> Bắt đầu kiểm tra

                    // Bước A: Tìm xem ai dạy môn này ở lớp này? (Tra bảng phan_cong)
                    $gvId = $this->model->getGiaoVienPhanCong($lop_id_save, $mon_id, $namHienTai);

                    if (!$gvId) {
                        // Chưa phân công ai dạy môn này cho lớp này
                        // Lấy tên môn để báo lỗi cho rõ
                        $tenMon = "";
                        foreach ($arrMon as $m) if ($m['id'] == $mon_id) $tenMon = $m['ten_mon'];

                        $errors[] = "⚠️ Thứ $thu - Tiết $tiet ($tenMon): Chưa được phân công giáo viên giảng dạy.";
                        continue; // Bỏ qua không lưu ô này
                    }

                    // Bước B: Kiểm tra Giáo viên này có đang bận ở lớp khác không?
                    $tenLopTrung = $this->model->checkTrungLich($namHienTai, $hk_save, $thu, $tiet, $gvId, $lop_id_save);

                    if ($tenLopTrung) {
                        // Bị trùng -> Báo lỗi
                        $errors[] = "⛔ Thứ $thu - Tiết $tiet: GV dạy môn này đang bận dạy tại lớp $tenLopTrung.";
                    } else {
                        // Bước C: Không trùng -> Lưu
                        $this->model->saveTietHoc($namHienTai, $hk_save, $lop_id_save, $thu, $tiet, $mon_id, $gvId);
                        $countSaved++;
                    }
                }
            }

            // Kết thúc vòng lặp, hiển thị kết quả
            if (!empty($errors)) {
                $msg = implode("\\n", $errors);
                echo "<script>alert('Đã lưu các tiết hợp lệ.\\n\\nTUY NHIÊN CÓ CÁC LỖI SAU KHÔNG LƯU ĐƯỢC:\\n$msg'); window.location.href='index.php?act=quanlytkb&lop_id=$lop_id_save&hk=$hk_save';</script>";
            } else {
                echo "<script>alert('Lưu thành công toàn bộ Thời khóa biểu!'); window.location.href='index.php?act=quanlytkb&lop_id=$lop_id_save&hk=$hk_save';</script>";
            }
        }
        // [MỚI] XỬ LÝ XẾP TỰ ĐỘNG (AUTO-SCHEDULER)
        // =================================================================
        if (isset($_POST['btnXepTuDong'])) {
            $hk_auto = $_POST['hk_auto'];

            // 1. Xóa TKB cũ (Cảnh báo: Sẽ mất hết dữ liệu cũ của kỳ này)
            $this->model->xoaTKBHienTai($namHienTai, $hk_auto);

            // 2. Lấy danh sách cần xếp (Từ bảng Phân Công)
            $dsPhanCong = $this->model->layDanhSachPhanCong($namHienTai);

            // Chuyển thành mảng để dễ xử lý
            $tasks = [];
            while ($pc = $dsPhanCong->fetch_assoc()) {
                $tasks[] = $pc;
            }

            // Trộn ngẫu nhiên danh sách để mỗi lần xếp ra kết quả khác nhau một chút (tự nhiên hơn)
            shuffle($tasks);

            $countSuccess = 0;
            $countFail = 0;

            // 3. Bắt đầu thuật toán tham lam
            foreach ($tasks as $task) {
                $lopId = $task['lop_id'];
                $monId = $task['mon_hoc_id'];
                $gvId  = $task['giao_vien_id'];
                $soTietCanXep = $task['so_tiet']; // Ví dụ: Toán cần 4 tiết

                // Lặp để xếp đủ số tiết
                for ($i = 0; $i < $soTietCanXep; $i++) {
                    $isAssigned = false;

                    // Duyệt qua các ngày (Thứ 2 -> Thứ 6/7)
                    // Mẹo: Random thứ bắt đầu để môn học rải đều, không bị dồn vào Thứ 2
                    $startThu = rand(2, 6);

                    for ($offset = 0; $offset < 5; $offset++) { // Lặp đủ 6 ngày
                        $thu = ($startThu + $offset);
                        if ($thu > 6) $thu -= 5; // Quay vòng 2-6

                        // Duyệt qua các tiết (1->5: Sáng, 6->10: Chiều)
                        // Giả sử: Lớp Khối 10,11,12 học Sáng (1-5).
                        // Để đơn giản, ta duyệt full 1->10, hoặc bạn có thể custom theo Khối.
                        for ($tiet = 1; $tiet <= 10; $tiet++) {

                            // Kiểm tra ô này có trống cho cả GV và Lớp không?
                            if ($this->model->checkSlotHopLe($namHienTai, $hk_auto, $lopId, $gvId, $thu, $tiet)) {
                                // OK -> Xếp luôn
                                $this->model->saveTietHoc($namHienTai, $hk_auto, $lopId, $thu, $tiet, $monId, $gvId);
                                $isAssigned = true;
                                $countSuccess++;
                                break; // Đã xếp xong tiết này, thoát vòng lặp tiết
                            }
                        }
                        if ($isAssigned) break; // Đã xếp xong tiết này, thoát vòng lặp thứ
                    }

                    if (!$isAssigned) $countFail++; // Không tìm được chỗ trống
                }
            }

            echo "<script>alert('Đã chạy xong xếp tự động!\\n- Số tiết xếp thành công: $countSuccess\\n- Số tiết không tìm được chỗ (bị kẹt): $countFail'); window.location.href='index.php?act=quanlytkb&hk=$hk_auto';</script>";
        }
        // Lấy dữ liệu TKB hiện tại để hiển thị lại ra bảng
        $dataTKB = [];
        if ($selectedLop) {
            $dataTKB = $this->model->getTKB($selectedLop, $namHienTai, $selectedHK);
        }

        include("app/Views/quanlytkb.php");
    }
}
