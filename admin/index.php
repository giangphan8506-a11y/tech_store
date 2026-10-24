
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

    // 1. Dem tong so san pham
    $sqlSanPham = "SELECT COUNT(*) FROM SanPham";
    $tongSanPham = (int) $connection->query($sqlSanPham)->fetchColumn();

    // 2. Dem tong so danh muc
    $sqlDanhMuc = "SELECT COUNT(*) FROM DanhMuc";
    $tongDanhMuc = (int) $connection->query($sqlDanhMuc)->fetchColumn();

    // 3. Dem tong so don hang
    $sqlDonHang = "SELECT COUNT(*) FROM DonHang";
    $tongDonHang = (int) $connection->query($sqlDonHang)->fetchColumn();

    // 4. Tinh doanh thu tu cac don hang da giao
    $sqlDoanhThu = "
        SELECT COALESCE(SUM(TongTien), 0)
        FROM DonHang
        WHERE TrangThai = 'delivered'
    ";

    $tongDoanhThu = $connection->query($sqlDoanhThu)->fetchColumn();

    // 5. Dem so don hang dang cho xu ly
    $sqlChoXuLy = "
        SELECT COUNT(*)
        FROM DonHang
        WHERE TrangThai = 'pending'
    ";

    $tongChoXuLy = (int) $connection->query($sqlChoXuLy)->fetchColumn();

    // 6. Lay 5 don hang moi nhat
    $sqlDonHangMoi = "
        SELECT
            d.MaDonHang,
            d.TenNguoiNhan,
            d.TongTien,
            d.TrangThai,
            d.NgayDat
        FROM DonHang d
        ORDER BY d.NgayDat DESC, d.MaDonHang DESC
        LIMIT 5
    ";

    $stmtDonHangMoi = $connection->query($sqlDonHangMoi);
    $danhSachDonHangMoi = $stmtDonHangMoi->fetchAll();

    // Chuyen ma trang thai thanh tieng Viet
    $tenTrangThai = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'shipping' => 'Đang giao',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã hủy'
    ];

    $baseUrl = '/tech_store';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Tech Store</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="<?php echo $baseUrl; ?>/assets/css/style.css"
    >

    <style>
        .dashboard-page {
            min-height: 100vh;
            background: #f5f3ee;
            padding: 30px 0 50px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-header h1 {
            color: #10375c;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .dashboard-header p {
            color: #777;
            margin-bottom: 0;
        }

        .dashboard-user {
            color: #10375c;
            font-weight: 600;
        }

        .dashboard-stat {
            height: 100%;
            padding: 24px;
            background: #fff;
            border: 1px solid #e8e6e1;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .dashboard-stat-title {
            color: #777;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .dashboard-stat-number {
            color: #10375c;
            font-size: 28px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .dashboard-stat-link {
            display: inline-block;
            margin-top: 14px;
            color: #10375c;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .dashboard-stat-link:hover {
            color: #b58d45;
        }

        .dashboard-section {
            margin-top: 30px;
            padding: 24px;
            background: #fff;
            border: 1px solid #e8e6e1;
            border-radius: 12px;
        }

        .dashboard-section h2 {
            color: #10375c;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .dashboard-table th {
            color: #555;
            font-size: 13px;
            white-space: nowrap;
        }

        .dashboard-table td {
            vertical-align: middle;
            font-size: 14px;
        }

        .dashboard-status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .dashboard-status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .dashboard-status-confirmed {
            background: #cfe2ff;
            color: #084298;
        }

        .dashboard-status-shipping {
            background: #d1ecf1;
            color: #0c5460;
        }

        .dashboard-status-delivered {
            background: #d1e7dd;
            color: #0f5132;
        }

        .dashboard-status-cancelled {
            background: #f8d7da;
            color: #842029;
        }

        .dashboard-quick-link {
            display: block;
            padding: 18px;
            color: #10375c;
            background: #fff;
            border: 1px solid #e8e6e1;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .dashboard-quick-link:hover {
            border-color: #ffb900;
            background: #fffaf0;
            transform: translateY(-2px);
        }

        @media (max-width: 576px) {
            .dashboard-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .dashboard-header h1 {
                font-size: 25px;
            }

            .dashboard-stat-number {
                font-size: 24px;
            }

            .dashboard-section {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<div class="dashboard-page">
    <div class="container">

        <!-- Tieu de dashboard -->
        <div class="dashboard-header">
            <div>
                <h1>Dashboard Admin</h1>
                <p>Tổng quan hoạt động cửa hàng Tech Store.</p>
            </div>

            <div>
                <div class="dashboard-user mb-2">
                    Xin chào,
                    <?php
                        echo htmlspecialchars(
                            $_SESSION['nguoi_dung']['HoTen']
                        );
                    ?>
                </div>

                <a href="../index.php"
                   class="btn btn-outline-secondary btn-sm">
                    Xem website
                </a>

                <a href="../logout.php"
                   class="btn btn-danger btn-sm">
                    Đăng xuất
                </a>
            </div>
        </div>

        <!-- Cac the thong ke -->
        <div class="row g-3">

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat">
                    <div class="dashboard-stat-title">
                        Tổng sản phẩm
                    </div>

                    <div class="dashboard-stat-number">
                        <?php echo number_format($tongSanPham); ?>
                    </div>

                    <a href="products.php" class="dashboard-stat-link">
                        Quản lý sản phẩm →
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat">
                    <div class="dashboard-stat-title">
                        Tổng danh mục
                    </div>

                    <div class="dashboard-stat-number">
                        <?php echo number_format($tongDanhMuc); ?>
                    </div>

                    <a href="categories.php" class="dashboard-stat-link">
                        Quản lý danh mục →
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat">
                    <div class="dashboard-stat-title">
                        Tổng đơn hàng
                    </div>

                    <div class="dashboard-stat-number">
                        <?php echo number_format($tongDonHang); ?>
                    </div>

                    <a href="orders.php" class="dashboard-stat-link">
                        Quản lý đơn hàng →
                    </a>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="dashboard-stat">
                    <div class="dashboard-stat-title">
                        Đơn chờ xác nhận
                    </div>

                    <div class="dashboard-stat-number">
                        <?php echo number_format($tongChoXuLy); ?>
                    </div>

                    <a href="orders.php" class="dashboard-stat-link">
                        Xử lý đơn hàng →
                    </a>
                </div>
            </div>

        </div>

        <!-- Doanh thu -->
        <div class="dashboard-section">
            <div class="dashboard-stat-title">
                TỔNG DOANH THU TỪ ĐƠN HÀNG ĐÃ GIAO
            </div>

            <div class="dashboard-stat-number">
                <?php echo number_format((float) $tongDoanhThu, 0, ',', '.'); ?>
                VNĐ
            </div>

            <p class="text-muted mt-2 mb-0">
                Chỉ tính các đơn hàng có trạng thái đã giao.
            </p>
        </div>

        <!-- Don hang moi nhat -->
        <div class="dashboard-section">
            <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                <h2 class="mb-0">5 đơn hàng mới nhất</h2>

                <a href="orders.php"
                   class="btn btn-outline-primary btn-sm">
                    Xem tất cả
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover dashboard-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Người nhận</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($danhSachDonHangMoi) > 0) { ?>

                            <?php foreach ($danhSachDonHangMoi as $donHang) { ?>
                                <tr>
                                    <td>
                                        #<?php
                                            echo (int) $donHang['MaDonHang'];
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                            echo htmlspecialchars(
                                                $donHang['TenNguoiNhan']
                                            );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                            echo date(
                                                'd/m/Y H:i',
                                                strtotime($donHang['NgayDat'])
                                            );
                                        ?>
                                    </td>

                                    <td class="fw-semibold">
                                        <?php
                                            echo number_format(
                                                (float) $donHang['TongTien'],
                                                0,
                                                ',',
                                                '.'
                                            );
                                        ?> VNĐ
                                    </td>

                                    <td>
                                        <span class="dashboard-status dashboard-status-<?php
                                            echo htmlspecialchars(
                                                $donHang['TrangThai']
                                            );
                                        ?>">
                                            <?php
                                                echo htmlspecialchars(
                                                    $tenTrangThai[
                                                        $donHang['TrangThai']
                                                    ] ?? $donHang['TrangThai']
                                                );
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } else { ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    Chưa có đơn hàng nào.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Truy cap nhanh -->
        <div class="dashboard-section">
            <h2>Truy cập nhanh</h2>

            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <a href="products.php" class="dashboard-quick-link">
                        Quản lý sản phẩm →
                    </a>
                </div>

                <div class="col-12 col-md-4">
                    <a href="categories.php" class="dashboard-quick-link">
                        Quản lý danh mục →
                    </a>
                </div>

                <div class="col-12 col-md-4">
                    <a href="orders.php" class="dashboard-quick-link">
                        Quản lý đơn hàng →
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
