<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../config/database.php';

    // Kiểm tra quyền quản trị
    if (
        !isset($_SESSION['nguoi_dung']) ||
        $_SESSION['nguoi_dung']['VaiTro'] !== 'admin'
    ) {
        header('Location: ../login.php');
        exit;
    }

    $pageTitle = 'Quản lý đơn hàng - Tech Store';
    $thongBao = '';
    $loi = '';

    $danhSachTrangThai = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy'
    ];

    // Cập nhật trạng thái đơn hàng và hoàn kho khi hủy đơn
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $maDonHang = filter_input(
            INPUT_POST,
            'ma_don_hang',
            FILTER_VALIDATE_INT
        );

        $trangThaiMoi = $_POST['trang_thai'] ?? '';

        if (
            !$maDonHang ||
            $maDonHang < 1 ||
            !is_string($trangThaiMoi) ||
            !array_key_exists($trangThaiMoi, $danhSachTrangThai)
        ) {
            $loi = 'Thông tin cập nhật không hợp lệ.';
        } else {
            try {
                // Bắt đầu transaction
                $connection->beginTransaction();

                // Khóa đơn hàng để tránh cập nhật đồng thời
                $sqlKiemTra = "
                    SELECT TrangThai
                    FROM DonHang
                    WHERE MaDonHang = :maDonHang
                    FOR UPDATE
                ";

                $stmtKiemTra = $connection->prepare($sqlKiemTra);
                $stmtKiemTra->execute([
                    ':maDonHang' => $maDonHang
                ]);

                $donHangCanSua = $stmtKiemTra->fetch();

                if (!$donHangCanSua) {
                    $connection->rollBack();
                    $loi = 'Không tìm thấy đơn hàng.';
                } else {
                    $trangThaiCu = $donHangCanSua['TrangThai'];

                    // Quy định các bước chuyển trạng thái hợp lệ
                    $cacTrangThaiDuocPhep = [
                        'pending'   => ['confirmed', 'cancelled'],
                        'confirmed' => ['shipping', 'cancelled'],
                        'shipping'  => ['delivered', 'cancelled'],
                        'delivered' => [],
                        'cancelled' => []
                    ];

                    // Nếu trạng thái không đổi thì không xử lý thêm
                    if ($trangThaiMoi === $trangThaiCu) {
                        $connection->commit();

                        $_SESSION['thong_bao_admin'] =
                            'Trạng thái đơn hàng không thay đổi.';

                        header('Location: orders.php');
                        exit;
                    }

                    // Kiểm tra quy tắc chuyển trạng thái
                    if (!in_array(
                        $trangThaiMoi,
                        $cacTrangThaiDuocPhep[$trangThaiCu] ?? [],
                        true
                    )) {
                        $connection->rollBack();

                        $loi = 'Không thể chuyển đơn hàng từ trạng thái '
                            . ($danhSachTrangThai[$trangThaiCu] ?? $trangThaiCu)
                            . ' sang '
                            . $danhSachTrangThai[$trangThaiMoi]
                            . '.';
                    } else {
                        // Hoàn kho khi đơn được chuyển sang trạng thái hủy
                        if (
                            $trangThaiMoi === 'cancelled' &&
                            in_array(
                                $trangThaiCu,
                                ['pending', 'confirmed', 'shipping'],
                                true
                            )
                        ) {
                            // Lấy danh sách sản phẩm thuộc đơn hàng
                            $sqlLayChiTiet = "
                                SELECT MaSanPham, SoLuong
                                FROM ChiTietDonHang
                                WHERE MaDonHang = :maDonHang
                                FOR UPDATE
                            ";

                            $stmtLayChiTiet = $connection->prepare(
                                $sqlLayChiTiet
                            );

                            $stmtLayChiTiet->execute([
                                ':maDonHang' => $maDonHang
                            ]);

                            $danhSachChiTietHoanKho =
                                $stmtLayChiTiet->fetchAll();

                            // Cộng lại số lượng sản phẩm trong kho
                            $sqlHoanKho = "
                                UPDATE SanPham
                                SET SoLuongTon = SoLuongTon + :soLuong
                                WHERE MaSanPham = :maSanPham
                            ";

                            $stmtHoanKho = $connection->prepare(
                                $sqlHoanKho
                            );

                            foreach (
                                $danhSachChiTietHoanKho
                                as $chiTietHoanKho
                            ) {
                                $stmtHoanKho->execute([
                                    ':soLuong' => (int) $chiTietHoanKho['SoLuong'],
                                    ':maSanPham' => (int) $chiTietHoanKho['MaSanPham']
                                ]);

                                if ($stmtHoanKho->rowCount() !== 1) {
                                    throw new RuntimeException(
                                        'Không thể hoàn lại tồn kho cho sản phẩm.'
                                    );
                                }
                            }
                        }

                        // Cập nhật trạng thái đơn hàng
                        $sqlCapNhat = "
                            UPDATE DonHang
                            SET TrangThai = :trangThai
                            WHERE MaDonHang = :maDonHang
                        ";

                        $stmtCapNhat = $connection->prepare($sqlCapNhat);

                        $stmtCapNhat->execute([
                            ':trangThai' => $trangThaiMoi,
                            ':maDonHang' => $maDonHang
                        ]);

                        // Lưu cả việc cập nhật trạng thái và hoàn kho
                        $connection->commit();

                        $_SESSION['thong_bao_admin'] =
                            $trangThaiMoi === 'cancelled'
                                ? 'Đã hủy đơn hàng và hoàn lại tồn kho thành công.'
                                : 'Cập nhật trạng thái đơn hàng thành công.';

                        // Chuyển trang để tránh gửi lại form
                        header('Location: orders.php');
                        exit;
                    }
                }
            } catch (Throwable $e) {
                if ($connection->inTransaction()) {
                    $connection->rollBack();
                }

                // Không hiển thị lỗi SQL nội bộ cho người dùng
                $loi = 'Không thể cập nhật đơn hàng. Vui lòng thử lại.';
            }
        }
    }

    // Hiển thị thông báo sau khi chuyển trang
    if (isset($_SESSION['thong_bao_admin'])) {
        $thongBao = $_SESSION['thong_bao_admin'];
        unset($_SESSION['thong_bao_admin']);
    }

    // Lấy danh sách đơn hàng và thông tin khách hàng
    $sqlDonHang = "
        SELECT
            DonHang.*,
            NguoiDung.Email
        FROM DonHang
        INNER JOIN NguoiDung
            ON DonHang.MaNguoiDung = NguoiDung.MaNguoiDung
        ORDER BY DonHang.NgayDat DESC, DonHang.MaDonHang DESC
    ";

    $stmtDonHang = $connection->query($sqlDonHang);
    $danhSachDonHang = $stmtDonHang->fetchAll();

    // Truy vấn chi tiết sản phẩm
    $sqlChiTiet = "
        SELECT
            ChiTietDonHang.*,
            SanPham.TenSanPham
        FROM ChiTietDonHang
        INNER JOIN SanPham
            ON ChiTietDonHang.MaSanPham = SanPham.MaSanPham
        WHERE ChiTietDonHang.MaDonHang = :maDonHang
        ORDER BY ChiTietDonHang.MaChiTiet ASC
    ";

    $stmtChiTiet = $connection->prepare($sqlChiTiet);

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

    <link
        rel="stylesheet"
        href="<?php echo $baseUrl; ?>/assets/css/style.css"
    >
</head>

<body class="admin-orders-page">

    <div class="container py-4">

        <div class="admin-orders-heading">
            <div>
                <h1>Quản lý đơn hàng</h1>
                <p>Theo dõi và cập nhật đơn hàng của khách hàng.</p>
            </div>

            <a href="index.php" class="btn btn-outline-secondary">
                ← Trang quản trị
            </a>
        </div>

        <?php if ($thongBao !== '') { ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($thongBao); ?>
            </div>
        <?php } ?>

        <?php if ($loi !== '') { ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($loi); ?>
            </div>
        <?php } ?>

        <?php if (count($danhSachDonHang) > 0) { ?>

            <?php foreach ($danhSachDonHang as $donHang) { ?>

                <?php
                    $stmtChiTiet->execute([
                        ':maDonHang' => $donHang['MaDonHang']
                    ]);

                    $danhSachChiTiet = $stmtChiTiet->fetchAll();

                    $trangThaiHienTai = $donHang['TrangThai'];

                    $tenTrangThai =
                        $danhSachTrangThai[$trangThaiHienTai]
                        ?? 'Không xác định';
                ?>

                <div class="admin-order-card">

                    <div class="admin-order-header">
                        <div>
                            <h3>
                                Đơn hàng #<?php
                                    echo (int) $donHang['MaDonHang'];
                                ?>
                            </h3>

                            <p>
                                Ngày đặt:
                                <?php
                                    echo date(
                                        'd/m/Y H:i',
                                        strtotime($donHang['NgayDat'])
                                    );
                                ?>
                            </p>
                        </div>

                        <span class="admin-order-status">
                            <?php echo htmlspecialchars($tenTrangThai); ?>
                        </span>
                    </div>

                    <div class="admin-order-info">
                        <p>
                            <strong>Khách hàng:</strong>
                            <?php
                                echo htmlspecialchars(
                                    $donHang['TenNguoiNhan']
                                );
                            ?>
                        </p>

                        <p>
                            <strong>Email tài khoản:</strong>
                            <?php
                                echo htmlspecialchars($donHang['Email']);
                            ?>
                        </p>

                        <p>
                            <strong>Số điện thoại:</strong>
                            <?php
                                echo htmlspecialchars(
                                    $donHang['SoDienThoai']
                                );
                            ?>
                        </p>

                        <p>
                            <strong>Địa chỉ giao hàng:</strong>
                            <?php
                                echo htmlspecialchars(
                                    $donHang['DiaChiGiaoHang']
                                );
                            ?>
                        </p>

                        <?php if (!empty($donHang['GhiChu'])) { ?>
                            <p>
                                <strong>Ghi chú:</strong>
                                <?php
                                    echo htmlspecialchars($donHang['GhiChu']);
                                ?>
                            </p>
                        <?php } ?>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($danhSachChiTiet as $chiTiet) { ?>
                                    <tr>
                                        <td>
                                            <?php
                                                echo htmlspecialchars(
                                                    $chiTiet['TenSanPham']
                                                );
                                            ?>
                                        </td>

                                        <td class="text-center">
                                            <?php
                                                echo (int) $chiTiet['SoLuong'];
                                            ?>
                                        </td>

                                        <td class="text-end">
                                            <?php
                                                echo number_format(
                                                    (float) $chiTiet['DonGiaLucMua'],
                                                    0,
                                                    ',',
                                                    '.'
                                                );
                                            ?>đ
                                        </td>

                                        <td class="text-end">
                                            <?php
                                                echo number_format(
                                                    (float) $chiTiet['ThanhTien'],
                                                    0,
                                                    ',',
                                                    '.'
                                                );
                                            ?>đ
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="admin-order-footer">
                        <div class="admin-order-total">
                            Tổng tiền:
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

                        <form
                            action="orders.php"
                            method="POST"
                            class="admin-order-update"
                        >

                            <input
                                type="hidden"
                                name="ma_don_hang"
                                value="<?php
                                    echo (int) $donHang['MaDonHang'];
                                ?>"
                            >

                            <label for="trang_thai_<?php
                                echo (int) $donHang['MaDonHang'];
                            ?>">
                                Trạng thái:
                            </label>

                            <select
                                id="trang_thai_<?php
                                    echo (int) $donHang['MaDonHang'];
                                ?>"
                                name="trang_thai"
                                class="form-select"
                            >
                                <?php foreach ($danhSachTrangThai as $ma => $ten) { ?>
                                    <option
                                        value="<?php echo htmlspecialchars($ma); ?>"
                                        <?php
                                            echo $trangThaiHienTai === $ma
                                                ? 'selected'
                                                : '';
                                        ?>
                                    >
                                        <?php echo htmlspecialchars($ten); ?>
                                    </option>
                                <?php } ?>
                            </select>

                            <button type="submit" class="btn btn-primary">
                                Cập nhật
                            </button>
                        </form>
                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <div class="alert alert-info">
                Hiện chưa có đơn hàng nào.
            </div>

        <?php } ?>

    </div>

</body>
</html>