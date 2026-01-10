<?php
require_once("app/Models/mThongKe.php");
require_once("app/Models/mNamHoc.php");

class cThongKe {
    private $model;
    private $modelNamHoc;

    public function __construct() {
        $this->model = new mThongKe();
        $this->modelNamHoc = new mNamHoc();
    }

    public function hienThiGiaoDien() {
        // Check quyền (Admin & BGH)
        if (!isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
             echo "<script>alert('Không có quyền!'); window.location.href='index.php';</script>"; return;
        }

        // 1. LẤY THAM SỐ BỘ LỌC TỪ URL (Nếu không có thì lấy mặc định)
        $namHienTai = $this->modelNamHoc->getNamHocHienTai();
        
        $filter_Nam = isset($_GET['nam_id']) ? $_GET['nam_id'] : $namHienTai;
        $filter_HK  = isset($_GET['hk']) ? $_GET['hk'] : 'HK1';
        $filter_Lop = isset($_GET['lop_id']) ? $_GET['lop_id'] : 'all'; // 'all' là chọn tất cả

        // 2. LẤY DỮ LIỆU CHO DROPDOWN
        $dsNamHoc = $this->model->getAllNamHoc();
        $dsLop    = $this->model->getAllLop($filter_Nam);

        // 3. LẤY SỐ LIỆU THỐNG KÊ
        $tongQuan = $this->model->getTongSoLuong($filter_Nam);

        // Xử lý Học lực
        $rawHL = $this->model->getThongKeHocLuc($filter_Nam, $filter_HK, $filter_Lop);
        $dataHL = ['Gioi'=>0, 'Kha'=>0, 'TB'=>0, 'Yeu'=>0, 'Kem'=>0];
        if($rawHL) while($r = $rawHL->fetch_assoc()) if(isset($dataHL[$r['hoc_luc']])) $dataHL[$r['hoc_luc']] = $r['so_luong'];

        // Xử lý Hạnh kiểm
        $rawHK = $this->model->getThongKeHanhKiem($filter_Nam, $filter_HK, $filter_Lop);
        $dataHKiem = ['Tot'=>0, 'Kha'=>0, 'TB'=>0, 'Yeu'=>0];
        if($rawHK) while($r = $rawHK->fetch_assoc()) if(isset($dataHKiem[$r['hanh_kiem']])) $dataHKiem[$r['hanh_kiem']] = $r['so_luong'];

        include("app/Views/thongke.php");
    }
}
?>