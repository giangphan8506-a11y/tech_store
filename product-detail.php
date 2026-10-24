
<?php
    require_once __DIR__ . '/config/database.php';

    $pageTitle = 'Chi tiết sản phẩm - Tech Store';

    // Lay ma san pham tu URL
    $maSanPham = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$maSanPham || $maSanPham < 1) {
        header('Location: products.php');
        exit;
    }

    // Lay thong tin san pham
    $sql = "
        SELECT SanPham.*, DanhMuc.TenDanhMuc
        FROM SanPham
        LEFT JOIN DanhMuc
            ON SanPham.MaDanhMuc = DanhMuc.MaDanhMuc
        WHERE SanPham.MaSanPham = :maSanPham
    ";

    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':maSanPham' => $maSanPham
    ]);

    $sanPham = $stmt->fetch();

    // Neu khong tim thay san pham
    if (!$sanPham) {
        http_response_code(404);
        $pageTitle = 'Không tìm thấy sản phẩm - Tech Store';
    }

    require_once __DIR__ . '/includes/header.php';
?>

<?php if (!$sanPham) { ?>

    <div class="empty-products">
        <h2>Không tìm thấy sản phẩm</h2>
        <p>Sản phẩm không tồn tại hoặc đã bị xóa.</p>
        <a href="products.php" class="btn-primary">
            Quay lại danh sách sản phẩm
        </a>
    </div>

<?php } else { ?>

    <!-- DUONG DAN -->
    <div class="breadcrumb-product">
        <a href="index.php">Trang chủ</a>
        <span>/</span>
        <a href="products.php">Sản phẩm</a>
        <span>/</span>
        <span><?php echo htmlspecialchars($sanPham['TenSanPham']); ?></span>
    </div>

    <!-- CHI TIET SAN PHAM -->
    <div class="product-detail">

        <!-- HINH ANH -->
        <div class="product-detail-image">
            <?php if (!empty($sanPham['HinhAnh'])) { ?>
                <img
                    src="assets/images/<?php echo rawurlencode(basename($sanPham['HinhAnh'])); ?>"
                    alt="<?php echo htmlspecialchars($sanPham['TenSanPham']); ?>"
                >
            <?php } else { ?>
                <div class="product-image-placeholder">
                    Chưa có hình ảnh
                </div>
            <?php } ?>
        </div>

        <!-- THONG TIN -->
        <div class="product-detail-info">

            <p class="product-category">
                <?php echo htmlspecialchars($sanPham['TenDanhMuc'] ?? 'Chưa phân loại'); ?>
            </p>

            <h1>
                <?php echo htmlspecialchars($sanPham['TenSanPham']); ?>
            </h1>

            <p class="product-detail-price">
                <?php echo number_format((float) $sanPham['DonGia'], 0, ',', '.'); ?>đ
            </p>

            <div class="product-stock">
                <?php if ((int) $sanPham['SoLuongTon'] > 0) { ?>
                    <span class="stock-available">Còn hàng</span>
                    <span>
                        (<?php echo (int) $sanPham['SoLuongTon']; ?> sản phẩm)
                    </span>
                <?php } else { ?>
                    <span class="stock-unavailable">Hết hàng</span>
                <?php } ?>
            </div>

            <hr>

            <h3>Mô tả sản phẩm</h3>

            <p class="product-description">
                <?php
                    if (!empty($sanPham['MoTa'])) {
                        echo nl2br(htmlspecialchars($sanPham['MoTa']));
                    } else {
                        echo 'Thông tin sản phẩm đang được cập nhật.';
                    }
                ?>
            </p>

            <?php if ((int) $sanPham['SoLuongTon'] > 0) { ?>

                <!-- FORM MUA HANG -->
                <form action="checkout.php" method="GET" class="buy-form">

                    <input
                        type="hidden"
                        name="id"
                        value="<?php echo (int) $sanPham['MaSanPham']; ?>"
                    >

                    <label for="soLuong">Số lượng:</label>

                    <input
                        type="number"
                        id="soLuong"
                        name="so_luong"
                        value="1"
                        min="1"
                        max="<?php echo (int) $sanPham['SoLuongTon']; ?>"
                        required
                    >

                    <button type="submit" class="btn-buy">
                        Mua ngay
                    </button>

                </form>

            <?php } else { ?>

                <button class="btn-buy" disabled>
                    Sản phẩm đã hết hàng
                </button>

            <?php } ?>

            <a href="products.php" class="back-to-products">
                ← Tiếp tục xem sản phẩm
            </a>

        </div>
    </div>

<?php } ?>

<?php
    require_once __DIR__ . '/includes/footer.php';
?>
