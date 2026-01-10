<?php
require_once("app/Models/mTongKet.php");
require_once("app/Models/mNamHoc.php");
require_once("app/Models/mGiaoVien.php");

class cTongKet
{
    private $model;
    private $modelNamHoc;
    private $modelGV;

    public function __construct()
    {
        $this->model = new mTongKet();
        $this->modelNamHoc = new mNamHoc();
        $this->modelGV = new mGiaoVien();
    }

    public function hienThiGiaoDien()
    {
        if (!isset($_SESSION['user_id'])) {
            echo "<script>alert('Vui lòng đăng nhập!'); window.location.href='index.php';</script>";
            return;
        }

        $userId = $_SESSION['user_id'];
        $gv = $this->modelGV->getByUserId($userId);

        if (!$gv) {
            echo "Không tìm thấy thông tin giáo viên.";
            return;
        }

        $currentGVId = $gv['id'];
        $namHocHienTai = $this->modelNamHoc->getNamHocHienTai();
        $selectedHK = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';

        // Lấy danh sách lớp chủ nhiệm
        $dsLopCN = $this->model->getLopChuNhiem($currentGVId, $namHocHienTai);

        // Nếu có chọn lớp
        $selectedLop = isset($_GET['lop_id']) ? $_GET['lop_id'] : null;

        // CHỨC NĂNG 1: LƯU ĐÁNH GIÁ (CHỈ LƯU HẠNH KIỂM & NHẬN XÉT)
        // =================================================================
        if (isset($_POST['btnLuuTongKet'])) {
            $nam_id = $_POST['nam_hoc_id'];
            $hk = $_POST['hoc_ky'];
            $lop_id = $_POST['lop_id'];

            if (isset($_POST['tongket'])) {
                foreach ($_POST['tongket'] as $hs_id => $row) {
                    $hk_val = isset($row['hanh_kiem']) ? $row['hanh_kiem'] : '';
                    $nx_val = isset($row['nhan_xet']) ? $row['nhan_xet'] : '';

                    // Chỉ gọi hàm lưu hạnh kiểm
                    $this->model->saveHanhKiem($hs_id, $nam_id, $hk, $hk_val, $nx_val);
                }
            }

            echo "<script>alert('Đã lưu Hạnh kiểm và Nhận xét thành công!'); window.location.href='index.php?act=tongket&lop_id=$lop_id&hk=$hk';</script>";
        }
        // XỬ LÝ TỰ ĐỘNG XẾP LOẠI (PHIÊN BẢN FIX: LƯU TRƯỚC - TÍNH SAU)
        // =================================================================
        if (isset($_POST['btnTuDongXepLoai'])) {
            $nam_id = $_POST['nam_hoc_id'];
            $hk = $_POST['hoc_ky'];
            $lop_id = $_POST['lop_id'];

            // BƯỚC 1: LƯU DỮ LIỆU TỪ FORM VÀO DATABASE TRƯỚC
            // (Để đảm bảo những gì giáo viên vừa chọn được ghi nhận)
            if (isset($_POST['tongket'])) {
                foreach ($_POST['tongket'] as $hs_id => $row) {
                    $hk_val = isset($row['hanh_kiem']) ? $row['hanh_kiem'] : '';
                    $nx_val = isset($row['nhan_xet']) ? $row['nhan_xet'] : '';

                    // Gọi Model lưu hạnh kiểm ngay lập tức
                    $this->model->saveHanhKiem($hs_id, $nam_id, $hk, $hk_val, $nx_val);
                }
            }

            // BƯỚC 2: LẤY DỮ LIỆU MỚI NHẤT TỪ DB ĐỂ TÍNH TOÁN
            $dataLop = $this->model->getBangDiemTongHop($lop_id, $nam_id, $hk);
            $dsMon = $this->model->getAllMonHoc();
            $monIds = [];
            while ($m = $dsMon->fetch_assoc()) $monIds[] = $m['id'];

            $countSuccess = 0;
            $countError = 0;

            foreach ($dataLop as $row) {
                $hs = $row['info'];
                $diem = $row['diem'];
                $tk = $row['tong_ket']; // Lúc này biến này đã chứa Hạnh kiểm vừa lưu ở Bước 1

                // --- LOGIC XẾP LOẠI ---

                // Kiểm tra lại lần nữa cho chắc
                $hanhKiem = isset($tk['hanh_kiem']) ? $tk['hanh_kiem'] : '';

                // Nếu vẫn không có hạnh kiểm (Giáo viên quên chọn ở form) -> Bỏ qua
                if (empty($hanhKiem)) {
                    $countError++;
                    continue;
                }

                // Tính ĐTB
                $tongDiem = 0;
                $soMon = 0;
                $minDiem = 10;
                foreach ($monIds as $mid) {
                    if (isset($diem[$mid]) && is_numeric($diem[$mid])) {
                        $val = floatval($diem[$mid]);
                        $tongDiem += $val;
                        $soMon++;
                        if ($val < $minDiem) $minDiem = $val;
                    }
                }

                if ($soMon == 0) continue;
                $dtbChung = round($tongDiem / $soMon, 1);

                // Xét Học lực
                $hocLuc = 'Kem';
                if ($dtbChung >= 8.0 && $minDiem >= 6.5) $hocLuc = 'Gioi';
                elseif ($dtbChung >= 6.5 && $minDiem >= 5.0) $hocLuc = 'Kha';
                elseif ($dtbChung >= 5.0 && $minDiem >= 3.5) $hocLuc = 'TB';
                elseif ($dtbChung >= 3.5 && $minDiem >= 2.0) $hocLuc = 'Yeu';

                // Xét Danh hiệu
                $danhHieu = '';
                if ($hocLuc == 'Gioi' && $hanhKiem == 'Tot') {
                    $danhHieu = 'Hoc sinh Gioi';
                } elseif (($hocLuc == 'Gioi' || $hocLuc == 'Kha') && ($hanhKiem == 'Tot' || $hanhKiem == 'Kha')) {
                    $danhHieu = 'Hoc sinh Tien tien';
                }

                // Lưu kết quả xếp loại
                $this->model->saveXepLoai($hs['id'], $nam_id, $hk, $dtbChung, $hocLuc, $danhHieu);
                $countSuccess++;
            }

            // Thông báo
            if ($countError > 0) {
                echo "<script>alert('Đã lưu và xếp loại cho $countSuccess HS. Có $countError HS chưa chọn Hạnh kiểm nên chưa xếp loại.'); window.location.href='index.php?act=tongket&lop_id=$lop_id&hk=$hk';</script>";
            } else {
                echo "<script>alert('Thành công! Đã lưu hạnh kiểm và xếp loại toàn bộ lớp.'); window.location.href='index.php?act=tongket&lop_id=$lop_id&hk=$hk';</script>";
            }
        }
        // --- DATA HIỂN THỊ ---
        $dsMonHoc = $this->model->getAllMonHoc();

        $bangDiemTongHop = [];

        if ($selectedLop) {
            // Chỉ lấy bảng điểm của HK hiện tại, không quan tâm HK1 hay Cả năm nữa
            $bangDiemTongHop = $this->model->getBangDiemTongHop($selectedLop, $namHocHienTai, $selectedHK);
        }

        include("app/Views/tongket.php");
    }
}
