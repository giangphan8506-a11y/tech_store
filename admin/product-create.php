
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../config/database.php';

    // Kiem tra quyen admin
    if (
        !isset($_SESSION['nguoi_dung']) ||
        $_SESSION['nguoi_dung']['VaiTro'] !== 'admin'
    ) {
        header('Location: ../login.php');
        exit;
    }

    $pageTitle = 'Thêm sản phẩm - Tech Store';
    $loi = '';

    // Lay danh sach danh muc
    $sqlDanhMuc = "SELECT * FROM DanhMuc ORDER BY MaDanhMuc";
    $stmtDanhMuc = $connection->query($sqlDanhMuc);
    $danhSachDanhMuc = $stmtDanhMuc->fetchAll();

    // Khoi tao gia tri mac dinh cho form
    $tenSanPham = '';
    $maDanhMuc = '';
    $moTa = '';
    $donGia = '';
    $soLuongTon = '';

    // Xu ly khi admin bam nut them san pham
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $tenSanPham = trim($_POST['ten_san_pham'] ?? '');
        $maDanhMuc = $_POST['ma_danh_muc'] ?? '';
        $moTa = trim($_POST['mo_ta'] ?? '');
        $donGia = trim($_POST['don_gia'] ?? '');
        $soLuongTon = trim($_POST['so_luong_ton'] ?? '');

        // Kiem tra ten san pham
        if ($tenSanPham === '') {
            $loi = 'Vui lòng nhập tên sản phẩm.';
        } elseif (strlen($tenSanPham) > 255) {
            $loi = 'Tên sản phẩm không được vượt quá 255 ký tự.';
        } elseif (
            filter_var($maDanhMuc, FILTER_VALIDATE_INT) === false ||
            (int) $maDanhMuc <= 0
        ) {
            $loi = 'Vui lòng chọn danh mục hợp lệ.';
        } elseif (
            $donGia === '' ||
            !is_numeric($donGia) ||
            (float) $donGia < 0
        ) {
            $loi = 'Đơn giá phải là số không âm.';
        } elseif (
            filter_var($soLuongTon, FILTER_VALIDATE_INT) === false ||
            (int) $soLuongTon < 0
        ) {
            $loi = 'Số lượng tồn phải là số nguyên không âm.';
        } elseif (strlen($moTa) > 10000) {
            $loi = 'Mô tả sản phẩm quá dài.';
        } else {
            // Kiem tra danh muc co ton tai khong
            $sqlKiemTra = "
                SELECT MaDanhMuc
                FROM DanhMuc
                WHERE MaDanhMuc = :maDanhMuc
            ";

            $stmtKiemTra = $connection->prepare($sqlKiemTra);
            $stmtKiemTra->execute([
                ':maDanhMuc' => (int) $maDanhMuc
            ]);

            if (!$stmtKiemTra->fetch()) {
                $loi = 'Danh mục đã chọn không tồn tại.';
            }
        }

        // Xu ly anh neu admin chon anh
        $tenHinhAnh = '';
        $duongDanAnh = '';

        if ($loi === '' && isset($_FILES['hinh_anh'])) {
            $tepAnh = $_FILES['hinh_anh'];

            if ($tepAnh['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($tepAnh['error'] !== UPLOAD_ERR_OK) {
                    $loi = 'Tải ảnh lên thất bại. Vui lòng thử lại.';
                } elseif ($tepAnh['size'] > 3 * 1024 * 1024) {
                    $loi = 'Ảnh không được lớn hơn 3 MB.';
                } else {
                    // Kiem tra dinh dang anh bang MIME type
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->file($tepAnh['tmp_name']);

                    $dinhDangAnh = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp'
                    ];

                    if (!isset($dinhDangAnh[$mimeType])) {
                        $loi = 'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.';
                    } else {
                        $tenHinhAnh = bin2hex(random_bytes(16))
                            . '.'
                            . $dinhDangAnh[$mimeType];

                        $thuMucAnh = __DIR__ . '/../assets/images/';

                        if (!is_dir($thuMucAnh)) {
                            $loi = 'Không tìm thấy thư mục lưu ảnh.';
                        } else {
                            $duongDanAnh = $thuMucAnh . $tenHinhAnh;

                            if (!move_uploaded_file(
                                $tepAnh['tmp_name'],
                                $duongDanAnh
                            )) {
                                $loi = 'Không thể lưu ảnh vào thư mục.';
                                $duongDanAnh = '';
                            }
                        }
                    }
                }
            }
        }

        // Luu san pham vao database
        if ($loi === '') {
            try {
                $sqlThem = "
                    INSERT INTO SanPham
                        (
                            MaDanhMuc,
                            TenSanPham,
                            MoTa,
                            DonGia,
                            SoLuongTon,
                            HinhAnh
                        )
                    VALUES
                        (
                            :maDanhMuc,
                            :tenSanPham,
                            :moTa,
                            :donGia,
                            :soLuongTon,
                            :hinhAnh
                        )
                ";

                $stmtThem = $connection->prepare($sqlThem);

                $stmtThem->execute([
                    ':maDanhMuc' => (int) $maDanhMuc,
                    ':tenSanPham' => $tenSanPham,
                    ':moTa' => $moTa,
                    ':donGia' => $donGia,
                    ':soLuongTon' => (int) $soLuongTon,
                    ':hinhAnh' => $tenHinhAnh
                ]);

                $_SESSION['thong_bao_admin'] =
                    'Thêm sản phẩm thành công!';

                header('Location: products.php');
                exit;
            } catch (PDOException $e) {
                // Xoa anh neu them san pham vao database that bai
                if ($duongDanAnh !== '' && is_file($duongDanAnh)) {
                    unlink($duongDanAnh);
                }

                $loi = 'Không thể thêm sản phẩm. Vui lòng kiểm tra dữ liệu.';
            }
        }
    }

    $baseUrl = '/tech_store';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet"
          href="<?php echo $baseUrl; ?>/assets/css/style.css">
</head>

<body class="admin-create-product-page">

    <div class="container py-4">

        <div class="admin-create-heading">
            <div>
                <h1>Thêm sản phẩm mới</h1>
                <p>Nhập thông tin sản phẩm vào hệ thống.</p>
            </div>

            <a href="products.php" class="btn btn-outline-secondary">
                ← Danh sách sản phẩm
            </a>
        </div>

        <div class="admin-create-card">

            <?php if ($loi !== '') { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($loi); ?>
                </div>
            <?php } ?>

            <form action="product-create.php"
                  method="POST"
                  enctype="multipart/form-data">

                <div class="mb-3">
                    <label for="ten_san_pham" class="form-label">
                        Tên sản phẩm *
                    </label>

                    <input
                        type="text"
                        id="ten_san_pham"
                        name="ten_san_pham"
                        class="form-control"
                        maxlength="255"
                        value="<?php echo htmlspecialchars($tenSanPham); ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="ma_danh_muc" class="form-label">
                        Danh mục *
                    </label>

                    <select
                        id="ma_danh_muc"
                        name="ma_danh_muc"
                        class="form-select"
                        required
                    >
                        <option value="">-- Chọn danh mục --</option>

                        <?php foreach ($danhSachDanhMuc as $danhMuc) { ?>
                            <option
                                value="<?php echo (int) $danhMuc['MaDanhMuc']; ?>"
                                <?php
                                    echo (string) $maDanhMuc ===
                                        (string) $danhMuc['MaDanhMuc']
                                        ? 'selected'
                                        : '';
                                ?>
                            >
                                <?php
                                    echo htmlspecialchars(
                                        $danhMuc['TenDanhMuc']
                                    );
                                ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="mo_ta" class="form-label">
                        Mô tả sản phẩm
                    </label>

                    <textarea
                        id="mo_ta"
                        name="mo_ta"
                        class="form-control"
                        rows="5"
                        maxlength="10000"
                    ><?php echo htmlspecialchars($moTa); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="don_gia" class="form-label">
                            Đơn giá (VNĐ) *
                        </label>

                        <input
                            type="number"
                            id="don_gia"
                            name="don_gia"
                            class="form-control"
                            min="0"
                            step="0.01"
                            value="<?php echo htmlspecialchars($donGia); ?>"
                            required
                        >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="so_luong_ton" class="form-label">
                            Số lượng tồn kho *
                        </label>

                        <input
                            type="number"
                            id="so_luong_ton"
                            name="so_luong_ton"
                            class="form-control"
                            min="0"
                            step="1"
                            value="<?php echo htmlspecialchars($soLuongTon); ?>"
                            required
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label for="hinh_anh" class="form-label">
                        Hình ảnh sản phẩm
                    </label>

                    <input
                        type="file"
                        id="hinh_anh"
                        name="hinh_anh"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >

                    <div class="form-text">
                        Chấp nhận JPG, PNG, WEBP. Dung lượng tối đa 3 MB.
                        Có thể bỏ trống nếu chưa có ảnh.
                    </div>
                </div>

                <div class="admin-create-actions">
                    <a href="products.php"
                       class="btn btn-outline-secondary">
                        Hủy
                    </a>

                    <button type="submit" class="btn btn-success">
                        Thêm sản phẩm
                    </button>
                </div>

            </form>

        </div>

    </div>

</body>
</html>
