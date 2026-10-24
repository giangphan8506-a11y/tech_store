
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/config/database.php';

    // Chi cho phep nguoi dung da dang nhap
    if (!isset($_SESSION['nguoi_dung'])) {
        $_SESSION['thong_bao'] = 'Vui lòng đăng nhập để xem đơn hàng.';
        header('Location: login.php');
        exit;
    }

    $pageTitle = 'Đơn hàng của tôi - Tech Store';

    $maNguoiDung = (int) $_SESSION['nguoi_dung']['MaNguoiDung'];

    // Lay danh sach don hang cua nguoi dung hien tai
    $sql = "
        SELECT *
        FROM DonHang
        WHERE MaNguoiDung = :maNguoiDung
        ORDER BY NgayDat DESC, MaDonHang DESC
    ";

    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':maNguoiDung' => $maNguoiDung
    ]);

    $danhSachDonHang = $stmt->fetchAll();

    // Lay chi tiet san pham cua tung don hang
    $sqlChiTiet = "
        SELECT
            ChiTietDonHang.*,
            SanPham.TenSanPham,
            SanPham.HinhAnh
        FROM ChiTietDonHang
        INNER JOIN SanPham
            ON ChiTietDonHang.MaSanPham = SanPham.MaSanPham
        WHERE ChiTietDonHang.MaDonHang = :maDonHang
        ORDER BY ChiTietDonHang.MaChiTiet ASC
    ";

    $stmtChiTiet = $connection->prepare($sqlChiTiet);

    require_once __DIR__ . '/includes/header.php';
?>

<section class="my-orders-page">

    <div class="orders-heading">
        <div>
            <h1>Đơn hàng của tôi</h1>
            <p>Theo dõi các đơn hàng bạn đã đặt tại Tech Store.</p>
        </div>

        <a href="products.php" class="orders-back">
            Tiếp tục mua sắm
        </a>
    </div>

    <?php if (isset($_SESSION['ma_don_hang_vua_dat'])) { ?>
        <div class="alert alert-success">
            Đặt hàng thành công!
            Mã đơn hàng:
            <strong>
                #<?php echo (int) $_SESSION['ma_don_hang_vua_dat']; ?>
            </strong>
        </div>

        <?php unset($_SESSION['ma_don_hang_vua_dat']); ?>
    <?php } ?>

    <?php if (count($danhSachDonHang) > 0) { ?>

        <?php foreach ($danhSachDonHang as $donHang) { ?>

            <?php
                $stmtChiTiet->execute([
                    ':maDonHang' => $donHang['MaDonHang']
                ]);

                $danhSachChiTiet = $stmtChiTiet->fetchAll();

                $trangThai = [
                    'pending' => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'shipping' => 'Đang giao hàng',
                    'delivered' => 'Đã giao hàng',
                    'cancelled' => 'Đã hủy'
                ];

                $trangThaiHienThi =
                    $trangThai[$donHang['TrangThai']] ?? 'Không xác định';
            ?>

            <div class="order-card">

                <div class="order-card-header">
                    <div>
                        <span class="order-label">Mã đơn hàng</span>
                        <h3>
                            #<?php echo (int) $donHang['MaDonHang']; ?>
                        </h3>
                    </div>

                    <span class="order-status status-<?php
                        echo htmlspecialchars($donHang['TrangThai']);
                    ?>">
                        <?php echo htmlspecialchars($trangThaiHienThi); ?>
                    </span>
                </div>

                <div class="order-meta">
                    <p>
                        <strong>Ngày đặt:</strong>
                        <?php
                            echo date(
                                'd/m/Y H:i',
                                strtotime($donHang['NgayDat'])
                            );
                        ?>
                    </p>

                    <p>
                        <strong>Người nhận:</strong>
                        <?php
                            echo htmlspecialchars($donHang['TenNguoiNhan']);
                        ?>
                    </p>

                    <p>
                        <strong>Số điện thoại:</strong>
                        <?php
                            echo htmlspecialchars($donHang['SoDienThoai']);
                        ?>
                    </p>

                    <p>
                        <strong>Địa chỉ giao hàng:</strong>
                        <?php
                            echo htmlspecialchars($donHang['DiaChiGiaoHang']);
                        ?>
                    </p>
                </div>

                <div class="order-products">

                    <?php foreach ($danhSachChiTiet as $chiTiet) { ?>

                        <div class="order-product">

                            <div class="order-product-image">
                                <?php if (!empty($chiTiet['HinhAnh'])) { ?>
                                    <img
                                        src="assets/images/<?php
                                            echo rawurlencode(
                                                basename($chiTiet['HinhAnh'])
                                            );
                                        ?>"
                                        alt="<?php
                                            echo htmlspecialchars(
                                                $chiTiet['TenSanPham']
                                            );
                                        ?>"
                                        loading="lazy"
                                    >
                                <?php } else { ?>
                                    <span>Chưa có ảnh</span>
                                <?php } ?>
                            </div>

                            <div class="order-product-info">
                                <h4>
                                    <?php
                                        echo htmlspecialchars(
                                            $chiTiet['TenSanPham']
                                        );
                                    ?>
                                </h4>

                                <p>
                                    Số lượng:
                                    <?php echo (int) $chiTiet['SoLuong']; ?>
                                </p>

                                <p>
                                    Đơn giá:
                                    <?php
                                        echo number_format(
                                            (float) $chiTiet['DonGiaLucMua'],
                                            0,
                                            ',',
                                            '.'
                                        );
                                    ?>đ
                                </p>
                            </div>

                            <div class="order-product-total">
                                <?php
                                    echo number_format(
                                        (float) $chiTiet['ThanhTien'],
                                        0,
                                        ',',
                                        '.'
                                    );
                                ?>đ
                            </div>

                        </div>

                    <?php } ?>

                </div>

                <?php if (!empty($donHang['GhiChu'])) { ?>
                    <p class="order-note">
                        <strong>Ghi chú:</strong>
                        <?php echo htmlspecialchars($donHang['GhiChu']); ?>
                    </p>
                <?php } ?>

                <div class="order-card-footer">
                    <span>Tổng tiền đơn hàng</span>

                    <strong>
                        <?php
                            echo number_format(
                                (float) $donHang['TongTien'],
                                0,
                                ',',
                                '.'
                            );
                        ?>đ
                    </strong>
                </div>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="orders-empty">
            <h2>Bạn chưa có đơn hàng nào</h2>
            <p>Hãy khám phá các sản phẩm tại Tech Store nhé!</p>

            <a href="products.php" class="orders-back">
                Khám phá sản phẩm
            </a>
        </div>

    <?php } ?>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
