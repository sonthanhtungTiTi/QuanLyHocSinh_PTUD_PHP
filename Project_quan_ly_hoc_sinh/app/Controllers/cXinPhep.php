<?php
require_once("app/Models/mDonXinPhep.php");
require_once("app/Models/mXemDiem.php");

class cXinPhep
{
    private $model;
    private $modelHS;

    public function __construct()
    {
        $this->model = new mDonXinPhep();
        $this->modelHS = new mXemDiem();
    }

    public function hienThiGiaoDien()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 4) {
            header("Location: index.php");
            exit();
        }
        $hs = $this->modelHS->getHocSinhByUserId($_SESSION['user_id']);
        if (!$hs) {
            echo "Lỗi HS.";
            return;
        }

        $errorMsg = "";

        // ===> XỬ LÝ GỬI ĐƠN KÈM FILE <===
        if (isset($_POST['btnGuiDon'])) {
            $tuNgay = $_POST['tu_ngay'];
            $denNgay = $_POST['den_ngay'];
            $lyDo = $_POST['ly_do'];

            // 1. Đặt múi giờ Việt Nam để hàm date() lấy đúng ngày hôm nay
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $ngayHienTai = date('Y-m-d');
            // ===> [MỚI] KIỂM TRA LOGIC NGÀY THÁNG <===
            if (strtotime($tuNgay) > strtotime($denNgay)) {
                $errorMsg = "Lỗi: 'Đến ngày' không thể nhỏ hơn 'Từ ngày'.";
            } elseif ($tuNgay < $ngayHienTai) {
                $errorMsg = "Lỗi: Không thể xin nghỉ cho ngày trong quá khứ (Bạn đang chọn ngày " . date('d/m/Y', strtotime($tuNgay)) . ").";
            } else {
                $hinhAnhPath = NULL; // Mặc định không có ảnh

                // 1. Kiểm tra có file upload không và có lỗi không
                if (isset($_FILES['file_minh_chung']) && $_FILES['file_minh_chung']['error'] == 0) {

                    $allowed = array('jpg', 'jpeg', 'png', 'gif'); // Chỉ cho phép ảnh
                    $fileName = $_FILES['file_minh_chung']['name'];
                    $fileTmp = $_FILES['file_minh_chung']['tmp_name'];
                    $fileSize = $_FILES['file_minh_chung']['size'];

                    // Lấy đuôi file
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    // Kiểm tra định dạng và kích thước (ví dụ max 5MB)
                    if (in_array($fileExt, $allowed) && $fileSize < 5242880) {
                        // Tạo tên file mới duy nhất để tránh trùng lặp (VD: minhchung_167888999_hs1.jpg)
                        $newFileName = 'minhchung_' . time() . '_' . $hs['id'] . '.' . $fileExt;
                        $uploadDir = 'public/uploads/minhchung/';
                        $destPath = $uploadDir . $newFileName;

                        // Di chuyển file từ thư mục tạm sang thư mục đích
                        if (move_uploaded_file($fileTmp, $destPath)) {
                            $hinhAnhPath = $destPath; // Lưu đường dẫn thành công để ghi vào DB
                        } else {
                            $errorMsg = "Lỗi khi lưu file ảnh lên server.";
                        }
                    } else {
                        $errorMsg = "Chỉ cho phép file ảnh (jpg, png) và dung lượng dưới 5MB.";
                    }
                } else {
                    $errorMsg = "Vui lòng chọn ảnh minh chứng (Đơn thuốc hoặc đơn tay có chữ ký).";
                }

                // 2. Nếu không có lỗi upload thì mới gọi Model lưu vào DB
                if (empty($errorMsg) && $hinhAnhPath) {
                    if ($this->model->taoDon($hs['id'], $tuNgay, $denNgay, $lyDo, $hinhAnhPath)) {
                        echo "<script>alert('Gửi đơn thành công!'); window.location.href='index.php?act=xinnghi';</script>";
                    } else {
                        $errorMsg = "Lỗi cơ sở dữ liệu.";
                    }
                }
            }
        }

        $dsDon = $this->model->getLichSuDon($hs['id']);
        include("app/Views/xinnghi_hs.php");
    }
}
