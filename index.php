<?php
    require_once __DIR__ . '/config/database.php';

    $pageTitle = 'Trang chủ - Tech Store';

    // Lay danh sach danh muc
    $sqlDanhMuc = "SELECT * FROM DanhMuc";
    $stmtDanhMuc = $connection->query($sqlDanhMuc);
    $danhSachDanhMuc = $stmtDanhMuc->fetchAll();

    // Lay 8 san pham moi nhat
    $sqlSanPham = "
        SELECT SanPham.*, DanhMuc.TenDanhMuc
        FROM SanPham
        LEFT JOIN DanhMuc
            ON SanPham.MaDanhMuc = DanhMuc.MaDanhMuc
        ORDER BY SanPham.NgayTao DESC, SanPham.MaSanPham DESC
        LIMIT 8
    ";

    $stmtSanPham = $connection->query($sqlSanPham);
    $danhSachSanPham = $stmtSanPham->fetchAll();

    require_once __DIR__ . '/includes/header.php';
?>

<!-- BANNER -->
<section class="hero-banner">
    <div class="hero-content">
        <h1>Khám phá công nghệ mới cùng Tech Store</h1>

        <p>
            Điện thoại, laptop và phụ kiện chính hãng.
            Giá tốt mỗi ngày, mua sắm dễ dàng.
        </p>

        <a href="products.php" class="btn">
            Khám phá ngay
        </a>
    </div>
</section>

<!-- DANH MUC SAN PHAM -->
<section class="mb-5">
    <h2 class="section-title">Danh mục sản phẩm</h2>

    <div class="row g-3">
        <?php foreach ($danhSachDanhMuc as $danhMuc) { ?>
        <div class="col-6 col-md-3">
            <a href="products.php?danh_muc=<?php echo (int) $danhMuc['MaDanhMuc']; ?>" class="category-card">
                <h3>
                    <?php echo htmlspecialchars($danhMuc['TenDanhMuc']); ?>
                </h3>

                <p>
                    <?php echo htmlspecialchars($danhMuc['MoTa'] ?? ''); ?>
                </p>

                <span>Xem sản phẩm →</span>
            </a>
        </div>
        <?php } ?>
    </div>
</section>

<!-- SAN PHAM MOI NHAT -->
<section class="mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="section-title mb-0">Sản phẩm mới nhất</h2>

        <a href="products.php" class="view-all">
            Xem tất cả →
        </a>
    </div>

    <div class="row g-3">
        <?php if (count($danhSachSanPham) > 0) { ?>

        <?php foreach ($danhSachSanPham as $sanPham) { ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="product-card">

                <a href="product-detail.php?id=<?php echo (int) $sanPham['MaSanPham']; ?>">
                    <?php if (!empty($sanPham['HinhAnh'])) { ?>
                    <img src="/tech_store/assets/images/products/<?php echo rawurlencode(basename($sanPham['HinhAnh'])); ?>"
                        alt="<?php echo htmlspecialchars($sanPham['TenSanPham']); ?>">
                    <?php } else { ?>
                    <div class="product-image-placeholder">
                        Chưa có hình ảnh
                    </div>
                    <?php } ?>
                </a>

                <div class="product-info">
                    <p class="product-category">
                        <?php echo htmlspecialchars($sanPham['TenDanhMuc'] ?? 'Chưa phân loại'); ?>
                    </p>

                    <h3>
                        <a href="product-detail.php?id=<?php echo (int) $sanPham['MaSanPham']; ?>">
                            <?php echo htmlspecialchars($sanPham['TenSanPham']); ?>
                        </a>
                    </h3>

                    <p class="product-price">
                        <?php echo number_format((float) $sanPham['DonGia'], 0, ',', '.'); ?>đ
                    </p>

                    <?php if ((int) $sanPham['SoLuongTon'] > 0) { ?>
                    <a href="product-detail.php?id=<?php echo (int) $sanPham['MaSanPham']; ?>" class="btn-buy">
                        Mua ngay
                    </a>
                    <?php } else { ?>
                    <button class="btn-buy" disabled>
                        Hết hàng
                    </button>
                    <?php } ?>
                </div>

            </div>
        </div>
        <?php } ?>

        <?php } else { ?>
        <div class="col-12">
            <p>Hiện chưa có sản phẩm nào.</p>
        </div>
        <?php } ?>
    </div>
</section>

<?php
    require_once __DIR__ . '/includes/footer.php';
?>