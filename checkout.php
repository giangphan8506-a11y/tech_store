<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/config/database.php';

    $pageTitle = 'Thanh toán - Tech Store';
    $loi = [];

    // Kiem tra dang nhap
    if (!isset($_SESSION['nguoi_dung'])) {
        $_SESSION['thong_bao'] = 'Vui lòng đăng nhập trước khi đặt hàng.';
        header('Location: login.php');
        exit;
    }

    $maNguoiDung = (int) $_SESSION['nguoi_dung']['MaNguoiDung'];

    // Lay ma san pham va so luong tu URL
    $maSanPham = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $soLuong = filter_input(INPUT_GET, 'so_luong', FILTER_VALIDATE_INT);

    // Neu form duoc gui bang POST thi lay du lieu tu POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $maSanPham = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $soLuong = filter_input(INPUT_POST, 'so_luong', FILTER_VALIDATE_INT);
    }

    if (!$maSanPham || $maSanPham < 1 || !$soLuong || $soLuong < 1) {
        header('Location: products.php');
        exit;
    }

    // Lay thong tin san pham tu database
    $sql = "
        SELECT MaSanPham, TenSanPham, DonGia, SoLuongTon, HinhAnh
        FROM SanPham
        WHERE MaSanPham = :maSanPham
    ";

    $stmt = $connection->prepare($sql);
    $stmt->execute([
        ':maSanPham' => $maSanPham
    ]);

    $sanPham = $stmt->fetch();

    if (!$sanPham) {
        header('Location: products.php');
        exit;
    }

    // Kiem tra so luong ton kho
    if ($soLuong > (int) $sanPham['SoLuongTon']) {
        $loi[] = 'Số lượng mua vượt quá số lượng sản phẩm còn trong kho.';
    }

    $tongTien = (float) $sanPham['DonGia'] * $soLuong;

    // Thong tin mac dinh
    $tenNguoiNhan = $_SESSION['nguoi_dung']['HoTen'] ?? '';
    $soDienThoai = $_SESSION['nguoi_dung']['SoDienThoai'] ?? '';
    $diaChi = $_SESSION['nguoi_dung']['DiaChi'] ?? '';
    $ghiChu = '';

    // Xu ly dat hang
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($loi)) {

        $tenNguoiNhan = trim($_POST['ten_nguoi_nhan'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $diaChi = trim($_POST['dia_chi'] ?? '');
        $ghiChu = trim($_POST['ghi_chu'] ?? '');

        if ($tenNguoiNhan === '') {
            $loi[] = 'Vui lòng nhập tên người nhận.';
        }

        if (!preg_match('/^[0-9]{10,11}$/', $soDienThoai)) {
            $loi[] = 'Số điện thoại phải có 10 hoặc 11 chữ số.';
        }

        if ($diaChi === '') {
            $loi[] = 'Vui lòng nhập địa chỉ giao hàng.';
        }

        if (mb_strlen($tenNguoiNhan) > 100) {
            $loi[] = 'Tên người nhận không được vượt quá 100 ký tự.';
        }

        if (mb_strlen($diaChi) > 255) {
            $loi[] = 'Địa chỉ không được vượt quá 255 ký tự.';
        }

        if (mb_strlen($ghiChu) > 1000) {
            $loi[] = 'Ghi chú không được vượt quá 1000 ký tự.';
        }

        if (empty($loi)) {
            try {
                // Bat dau transaction
                $connection->beginTransaction();

                // Khoa dong san pham de kiem tra ton kho
                $sqlKiemTra = "
                    SELECT TenSanPham, DonGia, SoLuongTon
                    FROM SanPham
                    WHERE MaSanPham = :maSanPham
                    FOR UPDATE
                ";

                $stmtKiemTra = $connection->prepare($sqlKiemTra);
                $stmtKiemTra->execute([
                    ':maSanPham' => $maSanPham
                ]);

                $sanPhamMoi = $stmtKiemTra->fetch();

                if (!$sanPhamMoi || $soLuong > (int) $sanPhamMoi['SoLuongTon']) {
                    throw new Exception('Sản phẩm không đủ số lượng trong kho.');
                }

                // Tinh lai tong tien tu gia trong database
                $tongTien = (float) $sanPhamMoi['DonGia'] * $soLuong;

                // Tao don hang
                $sqlDonHang = "
                    INSERT INTO DonHang (
                        MaNguoiDung,
                        TenNguoiNhan,
                        SoDienThoai,
                        DiaChiGiaoHang,
                        GhiChu,
                        TongTien
                    )
                    VALUES (
                        :maNguoiDung,
                        :tenNguoiNhan,
                        :soDienThoai,
                        :diaChi,
                        :ghiChu,
                        :tongTien
                    )
                ";

                $stmtDonHang = $connection->prepare($sqlDonHang);
                $stmtDonHang->execute([
                    ':maNguoiDung' => $maNguoiDung,
                    ':tenNguoiNhan' => $tenNguoiNhan,
                    ':soDienThoai' => $soDienThoai,
                    ':diaChi' => $diaChi,
                    ':ghiChu' => $ghiChu,
                    ':tongTien' => $tongTien
                ]);

                $maDonHang = $connection->lastInsertId();

                // Tao chi tiet don hang
                $sqlChiTiet = "
                    INSERT INTO ChiTietDonHang (
                        MaDonHang,
                        MaSanPham,
                        SoLuong,
                        DonGiaLucMua,
                        ThanhTien
                    )
                    VALUES (
                        :maDonHang,
                        :maSanPham,
                        :soLuong,
                        :donGia,
                        :thanhTien
                    )
                ";

                $stmtChiTiet = $connection->prepare($sqlChiTiet);
                $stmtChiTiet->execute([
                    ':maDonHang' => $maDonHang,
                    ':maSanPham' => $maSanPham,
                    ':soLuong' => $soLuong,
                    ':donGia' => $sanPhamMoi['DonGia'],
                    ':thanhTien' => $tongTien
                ]);

                // Cap nhat so luong ton kho
                $sqlCapNhat = "
                    UPDATE SanPham
                    SET SoLuongTon = SoLuongTon - :soLuong
                    WHERE MaSanPham = :maSanPham
                ";

                $stmtCapNhat = $connection->prepare($sqlCapNhat);
                $stmtCapNhat->execute([
                    ':soLuong' => $soLuong,
                    ':maSanPham' => $maSanPham
                ]);

                // Hoan tat transaction
                $connection->commit();

                // Luu ma don hang de hien thi thong bao
                $_SESSION['ma_don_hang_vua_dat'] = $maDonHang;

                header('Location: my-orders.php');
                exit;

            } catch (Exception $e) {
                if ($connection->inTransaction()) {
                    $connection->rollBack();
                }

                $loi[] = $e instanceof PDOException
                    ? 'Không thể tạo đơn hàng. Vui lòng thử lại.'
                    : $e->getMessage();
            }
        }
    }

    require_once __DIR__ . '/includes/header.php';
?>

<h2 class="section-title">Thanh toán đơn hàng</h2>

<?php if (!empty($loi)) { ?>
<div class="checkout-errors">
    <?php foreach ($loi as $thongBaoLoi) { ?>
    <p><?php echo htmlspecialchars($thongBaoLoi); ?></p>
    <?php } ?>
</div>
<?php } ?>

<div class="checkout-layout">

    <!-- THONG TIN SAN PHAM -->
    <section class="checkout-product">
        <h3>Sản phẩm đặt mua</h3>

        <div class="checkout-product-info">
            <?php if (!empty($sanPham['HinhAnh'])) { ?>
            <img src="/tech_store/assets/images/products/<?php echo rawurlencode(basename($sanPham['HinhAnh'])); ?>"
                alt="<?php echo htmlspecialchars($sanPham['TenSanPham']); ?>">
            <?php } ?>

            <div>
                <h4><?php echo htmlspecialchars($sanPham['TenSanPham']); ?></h4>

                <p>
                    Đơn giá:
                    <strong>
                        <?php echo number_format((float) $sanPham['DonGia'], 0, ',', '.'); ?>đ
                    </strong>
                </p>

                <p>Số lượng: <?php echo (int) $soLuong; ?></p>

                <p class="checkout-total">
                    Tổng tiền:
                    <?php echo number_format($tongTien, 0, ',', '.'); ?>đ
                </p>
            </div>
        </div>
    </section>

    <!-- FORM THONG TIN GIAO HANG -->
    <section class="checkout-form-container">
        <h3>Thông tin giao hàng</h3>

        <form method="POST"
            action="checkout.php?id=<?php echo (int) $maSanPham; ?>&so_luong=<?php echo (int) $soLuong; ?>">

            <input type="hidden" name="id" value="<?php echo (int) $maSanPham; ?>">

            <input type="hidden" name="so_luong" value="<?php echo (int) $soLuong; ?>">

            <div class="form-group">
                <label for="ten_nguoi_nhan">Họ tên người nhận</label>
                <input type="text" id="ten_nguoi_nhan" name="ten_nguoi_nhan" maxlength="100"
                    value="<?php echo htmlspecialchars($tenNguoiNhan); ?>" required>
            </div>

            <div class="form-group">
                <label for="so_dien_thoai">Số điện thoại</label>
                <input type="tel" id="so_dien_thoai" name="so_dien_thoai" maxlength="11"
                    value="<?php echo htmlspecialchars($soDienThoai); ?>" required>
            </div>

            <div class="form-group">
                <label for="dia_chi">Địa chỉ giao hàng</label>
                <textarea id="dia_chi" name="dia_chi" rows="3" maxlength="255"
                    required><?php echo htmlspecialchars($diaChi); ?></textarea>
            </div>

            <div class="form-group">
                <label for="ghi_chu">Ghi chú (không bắt buộc)</label>
                <textarea id="ghi_chu" name="ghi_chu" rows="3"
                    maxlength="1000"><?php echo htmlspecialchars($ghiChu); ?></textarea>
            </div>

            <button type="submit" class="btn-primary checkout-submit"
                <?php echo ((int) $sanPham['SoLuongTon'] < $soLuong) ? 'disabled' : ''; ?>>
                Xác nhận đặt hàng
            </button>

            <a href="product-detail.php?id=<?php echo (int) $maSanPham; ?>" class="back-to-products">
                Quay lại sản phẩm
            </a>
        </form>
    </section>

</div>

<?php
    require_once __DIR__ . '/includes/footer.php';
?>