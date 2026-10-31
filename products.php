<?php
    require_once __DIR__ . '/config/database.php';
    //__dir__ tương ứng với thư mục: C:\xampp\htdocs\tech_store
    $pageTitle = 'Sản phẩm - Tech Store';

    // Lay tu khoa tim kiem
    $tuKhoa = trim($_GET['q'] ?? '');
    // Toán tử ?? được gọi là toán tử null coalescing. Nó có nghĩa là: nếu giá trị bên trái tồn tại và khác null, sử dụng giá trị đó; nếu không, sử dụng giá trị bên phải.
   
    // Lay ma danh muc và đọc dữ liệu đầu vào và có thể kiểm tra dữ liệu đó theo bộ lọc được chỉ định.
    $maDanhMuc = filter_input(INPUT_GET, 'danh_muc', FILTER_VALIDATE_INT);

    if (!$maDanhMuc || $maDanhMuc < 1) {
        $maDanhMuc = null;
    }

    // Lay danh sach danh muc
    $sqlDanhMuc = "SELECT * FROM DanhMuc ORDER BY MaDanhMuc";
    $stmtDanhMuc = $connection->query($sqlDanhMuc);
    $danhSachDanhMuc = $stmtDanhMuc->fetchAll();

    // Tao cau lenh tim kiem san pham
    $sql = "
        SELECT SanPham.*, DanhMuc.TenDanhMuc
        FROM SanPham
        LEFT JOIN DanhMuc
            ON SanPham.MaDanhMuc = DanhMuc.MaDanhMuc
        WHERE 1 = 1
    ";

    $thamSo = [];

    if ($tuKhoa !== '') { //!!== so sánh cả kiểu dữ liệu
        $sql .= " AND SanPham.TenSanPham LIKE :tuKhoa";
        $thamSo[':tuKhoa'] = '%' . $tuKhoa . '%';
    }

    if ($maDanhMuc !== null) {
        $sql .= " AND SanPham.MaDanhMuc = :maDanhMuc";
        $thamSo[':maDanhMuc'] = $maDanhMuc;
    }

    // Dem tong so san pham
    $sqlDem = "
        SELECT COUNT(*)
        FROM SanPham
        WHERE 1 = 1
    ";

    $thamSoDem = [];

    if ($tuKhoa !== '') {
        $sqlDem .= " AND TenSanPham LIKE :tuKhoa";
        $thamSoDem[':tuKhoa'] = '%' . $tuKhoa . '%';
    }

    if ($maDanhMuc !== null) {
        $sqlDem .= " AND MaDanhMuc = :maDanhMuc";
        $thamSoDem[':maDanhMuc'] = $maDanhMuc;
    }

    $stmtDem = $connection->prepare($sqlDem);
    $stmtDem->execute($thamSoDem);
    $tongSanPham = (int) $stmtDem->fetchColumn();

    // Phan trang
    $sanPhamMoiTrang = 12;
    $tongTrang = max(1, (int) ceil($tongSanPham / $sanPhamMoiTrang)); // ceil làm tròn tới số nguyên tiếp theo 2.08 -> 3

    $trangHienTai = filter_input(INPUT_GET, 'trang', FILTER_VALIDATE_INT);

    if (!$trangHienTai || $trangHienTai < 1) {
        $trangHienTai = 1;
    }

    $trangHienTai = min($trangHienTai, $tongTrang); // ràng buộc k cho chỉnh lên thnah url
    $viTriBatDau = ($trangHienTai - 1) * $sanPhamMoiTrang; //trang 1 offset bắt đầu từ 0 trang 1 từ 12 để ko lấy trùng sản phẩm

    $sql .= "
        ORDER BY SanPham.NgayTao DESC, SanPham.MaSanPham DESC
        LIMIT $sanPhamMoiTrang OFFSET $viTriBatDau
    ";

    $stmt = $connection->prepare($sql);
    $stmt->execute($thamSo);
    $danhSachSanPham = $stmt->fetchAll();

    // Xac dinh tieu de trang
    $tenDanhMucHienTai = 'Tất cả sản phẩm';

    if ($maDanhMuc !== null) {
        foreach ($danhSachDanhMuc as $danhMuc) {
            if ((int) $danhMuc['MaDanhMuc'] === $maDanhMuc) {
                $tenDanhMucHienTai = $danhMuc['TenDanhMuc'];
                break;
            }
        }
    }

    require_once __DIR__ . '/includes/header.php';
?>

<div class="row g-4">

    <!-- SIDEBAR DANH MUC -->
    <aside class="col-md-3">
        <div class="sidebar">
            <h3>Danh mục sản phẩm</h3>

            <a href="products.php">
                Tất cả sản phẩm
            </a>

            <?php foreach ($danhSachDanhMuc as $danhMuc) { ?>
            <a href="products.php?danh_muc=<?php echo (int) $danhMuc['MaDanhMuc']; ?>">
                <?php echo htmlspecialchars($danhMuc['TenDanhMuc']); ?>
            </a>
            <?php } ?>
        </div>
    </aside>

    <!-- DANH SACH SAN PHAM -->
    <section class="col-md-9">

        <h2 class="section-title">
            <?php echo htmlspecialchars($tenDanhMucHienTai); ?>
        </h2>

        <!-- FORM TIM KIEM -->
        <form action="products.php" method="GET" class="product-search-form">
            <input type="text" name="q" placeholder="Nhập tên sản phẩm cần tìm..."
                value="<?php echo htmlspecialchars($tuKhoa); ?>">

            <?php if ($maDanhMuc !== null) { ?>
            <input type="hidden" name="danh_muc" value="<?php echo $maDanhMuc; ?>">
            <?php } ?>

            <button type="submit">Tìm kiếm</button>
        </form>

        <p class="product-count">
            Tìm thấy <?php echo $tongSanPham; ?> sản phẩm
        </p>

        <div class="row g-3">

            <?php if (count($danhSachSanPham) > 0) { ?>

            <?php foreach ($danhSachSanPham as $sanPham) { ?>
            <div class="col-6 col-lg-4">
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
                <div class="empty-products">
                    <h3>Không tìm thấy sản phẩm</h3>
                    <p>Thử nhập từ khóa khác hoặc chọn danh mục khác nhé.</p>
                    <a href="products.php" class="btn-primary">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
            <?php } ?>

        </div>

        <!-- PHAN TRANG -->
        <?php if ($tongTrang > 1) { ?>
        <nav class="pagination-container" aria-label="Phân trang">
            <?php for ($i = 1; $i <= $tongTrang; $i++) { ?>
            <?php
                        $thamSoTrang = [];

                        if ($tuKhoa !== '') {
                            $thamSoTrang['q'] = $tuKhoa;
                        }

                        if ($maDanhMuc !== null) {
                            $thamSoTrang['danh_muc'] = $maDanhMuc;
                        }

                        $thamSoTrang['trang'] = $i;

                        $urlTrang = 'products.php?' . http_build_query($thamSoTrang);
                    ?>

            <a href="<?php echo htmlspecialchars($urlTrang); ?>"
                class="<?php echo $i === $trangHienTai ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
            <?php } ?>
        </nav>
        <?php } ?>

    </section>
</div>

<?php
    require_once __DIR__ . '/includes/footer.php';
?>