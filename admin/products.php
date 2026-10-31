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

    $pageTitle = 'Quản lý sản phẩm - Tech Store';

    // Lay tu khoa tim kiem
    $tuKhoa = trim($_GET['q'] ?? '');

    // Xu ly thong bao sau khi xoa san pham
    $ketQua = $_GET['ket_qua'] ?? '';

    $thongBao = [
        'xoa_thanh_cong' => 'Xóa sản phẩm thành công!',
        'dang_duoc_su_dung' => 'Không thể xóa vì sản phẩm đã có trong đơn hàng.',
        'khong_ton_tai' => 'Sản phẩm không tồn tại.',
        'khong_hop_le' => 'Mã sản phẩm không hợp lệ.',
        'that_bai' => 'Đã xảy ra lỗi khi xóa sản phẩm.'
    ];

    $loaiThongBao = $ketQua === 'xoa_thanh_cong'
        ? 'success'
        : 'danger';

    // Truy van san pham
    $sql = "
        SELECT
            SanPham.*,
            DanhMuc.TenDanhMuc
        FROM SanPham
        LEFT JOIN DanhMuc
            ON SanPham.MaDanhMuc = DanhMuc.MaDanhMuc
    ";

    if ($tuKhoa !== '') {
        $sql .= "
            WHERE SanPham.TenSanPham LIKE :tuKhoa
        ";
    }

    $sql .= " ORDER BY SanPham.MaSanPham DESC";

    $stmt = $connection->prepare($sql);

    if ($tuKhoa !== '') {
        $stmt->execute([
            ':tuKhoa' => '%' . $tuKhoa . '%'
        ]);
    } else {
        $stmt->execute();
    }

    $danhSachSanPham = $stmt->fetchAll();

    $baseUrl = '/tech_store';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/assets/css/style.css">
</head>

<body class="admin-products-page">

    <div class="container py-4">

        <div class="admin-products-heading">
            <div>
                <h1>Quản lý sản phẩm</h1>
                <p>Thêm mới, tìm kiếm và chỉnh sửa sản phẩm.</p>
            </div>

            <a href="index.php" class="btn btn-outline-secondary">
                ← Trang quản trị
            </a>
        </div>

        <?php if (isset($thongBao[$ketQua])) { ?>
        <div class="alert alert-<?php echo $loaiThongBao; ?>" role="alert">
            <?php echo htmlspecialchars($thongBao[$ketQua]); ?>
        </div>
        <?php } ?>

        <div class="admin-products-toolbar">

            <form action="products.php" method="GET" class="admin-products-search">
                <input type="text" name="q" class="form-control" placeholder="Nhập tên sản phẩm cần tìm..."
                    value="<?php echo htmlspecialchars($tuKhoa); ?>">

                <button type="submit" class="btn btn-primary">
                    Tìm kiếm
                </button>

                <?php if ($tuKhoa !== '') { ?>
                <a href="products.php" class="btn btn-outline-secondary">
                    Xóa tìm kiếm
                </a>
                <?php } ?>
            </form>

            <a href="product-create.php" class="btn btn-success">
                + Thêm sản phẩm
            </a>

        </div>

        <div class="admin-products-count">
            Tìm thấy
            <strong><?php echo count($danhSachSanPham); ?></strong>
            sản phẩm
        </div>

        <div class="table-responsive admin-products-table">
            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Mã SP</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-center">Tồn kho</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (count($danhSachSanPham) > 0) { ?>

                    <?php foreach ($danhSachSanPham as $sanPham) { ?>

                    <tr>
                        <td>
                            <?php echo (int) $sanPham['MaSanPham']; ?>
                        </td>

                        <td>
                            <div class="admin-product-image">
                                <?php if (!empty($sanPham['HinhAnh'])) { ?>
                                <img src="../assets/images/products/<?php echo rawurlencode(basename($sanPham['HinhAnh'])); ?>"
                                    alt="<?php echo htmlspecialchars($sanPham['TenSanPham']); ?>">
                                <?php } else { ?>
                                <span>Chưa có ảnh</span>
                                <?php } ?>
                            </div>
                        </td>

                        <td>
                            <strong>
                                <?php
                                            echo htmlspecialchars(
                                                $sanPham['TenSanPham']
                                            );
                                        ?>
                            </strong>
                        </td>

                        <td>
                            <?php
                                        echo htmlspecialchars(
                                            $sanPham['TenDanhMuc']
                                            ?? 'Chưa phân loại'
                                        );
                                    ?>
                        </td>

                        <td class="text-end">
                            <?php
                                        echo number_format(
                                            (float) $sanPham['DonGia'],
                                            0,
                                            ',',
                                            '.'
                                        );
                                    ?>đ
                        </td>

                        <td class="text-center">
                            <?php echo (int) $sanPham['SoLuongTon']; ?>
                        </td>

                        <td>
                            <div class="admin-product-actions d-flex gap-2">

                                <a href="product-edit.php?id=<?php
                                                echo (int) $sanPham['MaSanPham'];
                                            ?>" class="btn btn-sm btn-warning">
                                    Sửa
                                </a>

                                <form action="product-delete.php" method="POST"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này không?');">
                                    <input type="hidden" name="ma_san_pham" value="<?php
                                                    echo (int) $sanPham['MaSanPham'];
                                                ?>">

                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Xóa
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>

                    <?php } ?>

                    <?php } else { ?>

                    <tr>
                        <td colspan="7" class="text-center py-4">
                            Không tìm thấy sản phẩm nào.
                        </td>
                    </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>

    </div>

</body>

</html>