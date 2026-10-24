
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

    $loi = '';
    $thanhCong = '';

    $tenDanhMuc = '';
    $moTa = '';
    $maDanhMucSua = 0;

    // Kiem tra danh muc can sua
    if (isset($_GET['sua'])) {
        $maDanhMucSua = filter_input(
            INPUT_GET,
            'sua',
            FILTER_VALIDATE_INT
        );

        if (!$maDanhMucSua || $maDanhMucSua <= 0) {
            header('Location: categories.php');
            exit;
        }

        $sqlTim = "
            SELECT *
            FROM DanhMuc
            WHERE MaDanhMuc = :maDanhMuc
        ";

        $stmtTim = $connection->prepare($sqlTim);
        $stmtTim->execute([
            ':maDanhMuc' => $maDanhMucSua
        ]);

        $danhMucSua = $stmtTim->fetch();

        if (!$danhMucSua) {
            header('Location: categories.php');
            exit;
        }

        $tenDanhMuc = $danhMucSua['TenDanhMuc'];
        $moTa = $danhMucSua['MoTa'] ?? '';
    }

    // Xu ly them hoac cap nhat danh muc
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hanhDong = $_POST['hanh_dong'] ?? '';
        $tenDanhMuc = trim($_POST['ten_danh_muc'] ?? '');
        $moTa = trim($_POST['mo_ta'] ?? '');

        $maDanhMucSua = filter_var(
            $_POST['ma_danh_muc'] ?? '',
            FILTER_VALIDATE_INT
        );

        if ($hanhDong !== 'them' && $hanhDong !== 'sua') {
            $loi = 'Thao tác không hợp lệ.';
        } elseif ($tenDanhMuc === '') {
            $loi = 'Vui lòng nhập tên danh mục.';
        } elseif (strlen($tenDanhMuc) > 255) {
            $loi = 'Tên danh mục không được vượt quá 255 ký tự.';
        } elseif (strlen($moTa) > 5000) {
            $loi = 'Mô tả danh mục quá dài.';
        } elseif (
            $hanhDong === 'sua' &&
            (!$maDanhMucSua || $maDanhMucSua <= 0)
        ) {
            $loi = 'Mã danh mục không hợp lệ.';
        }

        // Kiem tra danh muc co ton tai khi sua
        if ($loi === '' && $hanhDong === 'sua') {
            $sqlKiemTra = "
                SELECT MaDanhMuc
                FROM DanhMuc
                WHERE MaDanhMuc = :maDanhMuc
            ";

            $stmtKiemTra = $connection->prepare($sqlKiemTra);
            $stmtKiemTra->execute([
                ':maDanhMuc' => $maDanhMucSua
            ]);

            if (!$stmtKiemTra->fetch()) {
                $loi = 'Danh mục không tồn tại.';
            }
        }

        // Kiem tra ten danh muc bi trung
        if ($loi === '') {
            $sqlTrung = "
                SELECT MaDanhMuc
                FROM DanhMuc
                WHERE TenDanhMuc = :tenDanhMuc
            ";

            if ($hanhDong === 'sua') {
                $sqlTrung .= "
                    AND MaDanhMuc <> :maDanhMuc
                ";
            }

            $stmtTrung = $connection->prepare($sqlTrung);

            $duLieuTrung = [
                ':tenDanhMuc' => $tenDanhMuc
            ];

            if ($hanhDong === 'sua') {
                $duLieuTrung[':maDanhMuc'] = $maDanhMucSua;
            }

            $stmtTrung->execute($duLieuTrung);

            if ($stmtTrung->fetch()) {
                $loi = 'Tên danh mục này đã tồn tại.';
            }
        }

        // Them danh muc
        if ($loi === '' && $hanhDong === 'them') {
            try {
                $sqlThem = "
                    INSERT INTO DanhMuc (TenDanhMuc, MoTa)
                    VALUES (:tenDanhMuc, :moTa)
                ";

                $stmtThem = $connection->prepare($sqlThem);
                $stmtThem->execute([
                    ':tenDanhMuc' => $tenDanhMuc,
                    ':moTa' => $moTa
                ]);

                $thanhCong = 'Thêm danh mục thành công!';
                $tenDanhMuc = '';
                $moTa = '';
                $maDanhMucSua = 0;
            } catch (PDOException $e) {
                $loi = 'Không thể thêm danh mục. Vui lòng thử lại.';
            }
        }

        // Cap nhat danh muc
        if ($loi === '' && $hanhDong === 'sua') {
            try {
                $sqlCapNhat = "
                    UPDATE DanhMuc
                    SET TenDanhMuc = :tenDanhMuc,
                        MoTa = :moTa
                    WHERE MaDanhMuc = :maDanhMuc
                ";

                $stmtCapNhat = $connection->prepare($sqlCapNhat);
                $stmtCapNhat->execute([
                    ':tenDanhMuc' => $tenDanhMuc,
                    ':moTa' => $moTa,
                    ':maDanhMuc' => $maDanhMucSua
                ]);

                $thanhCong = 'Cập nhật danh mục thành công!';
            } catch (PDOException $e) {
                $loi = 'Không thể cập nhật danh mục. Vui lòng thử lại.';
            }
        }
    }

    // Lay danh sach danh muc va so san pham moi danh muc
    $sqlDanhSach = "
        SELECT
            d.MaDanhMuc,
            d.TenDanhMuc,
            d.MoTa,
            COUNT(s.MaSanPham) AS SoSanPham
        FROM DanhMuc d
        LEFT JOIN SanPham s
            ON d.MaDanhMuc = s.MaDanhMuc
        GROUP BY
            d.MaDanhMuc,
            d.TenDanhMuc,
            d.MoTa
        ORDER BY d.MaDanhMuc DESC
    ";

    $stmtDanhSach = $connection->query($sqlDanhSach);
    $danhSachDanhMuc = $stmtDanhSach->fetchAll();

    $baseUrl = '/tech_store';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý danh mục - Tech Store</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="<?php echo $baseUrl; ?>/assets/css/style.css"
    >
</head>

<body class="admin-create-product-page">

    <div class="container py-4">

        <div class="admin-create-heading">
            <div>
                <h1>Quản lý danh mục</h1>
                <p>Thêm mới và cập nhật danh mục sản phẩm.</p>
            </div>

            <a href="products.php" class="btn btn-outline-secondary">
                ← Quản lý sản phẩm
            </a>
        </div>

        <?php if ($loi !== '') { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($loi); ?>
            </div>
        <?php } ?>

        <?php if ($thanhCong !== '') { ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($thanhCong); ?>
            </div>
        <?php } ?>

        <div class="admin-create-card mb-4">

            <h2 class="h4 mb-4">
                <?php
                    echo $maDanhMucSua > 0
                        ? 'Sửa danh mục'
                        : 'Thêm danh mục mới';
                ?>
            </h2>

            <form action="categories.php" method="POST">

                <input
                    type="hidden"
                    name="hanh_dong"
                    value="<?php echo $maDanhMucSua > 0 ? 'sua' : 'them'; ?>"
                >

                <input
                    type="hidden"
                    name="ma_danh_muc"
                    value="<?php echo (int) $maDanhMucSua; ?>"
                >

                <div class="mb-3">
                    <label for="ten_danh_muc" class="form-label">
                        Tên danh mục *
                    </label>

                    <input
                        type="text"
                        id="ten_danh_muc"
                        name="ten_danh_muc"
                        class="form-control"
                        maxlength="255"
                        value="<?php echo htmlspecialchars($tenDanhMuc); ?>"
                        required
                    >
                </div>

                <div class="mb-4">
                    <label for="mo_ta" class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        id="mo_ta"
                        name="mo_ta"
                        class="form-control"
                        rows="3"
                        maxlength="5000"
                    ><?php echo htmlspecialchars($moTa); ?></textarea>
                </div>

                <div class="admin-create-actions">
                    <?php if ($maDanhMucSua > 0) { ?>
                        <a href="categories.php"
                           class="btn btn-outline-secondary">
                            Hủy sửa
                        </a>
                    <?php } ?>

                    <button type="submit" class="btn btn-primary">
                        <?php
                            echo $maDanhMucSua > 0
                                ? 'Lưu thay đổi'
                                : 'Thêm danh mục';
                        ?>
                    </button>
                </div>

            </form>
        </div>

        <div class="admin-create-card">

            <h2 class="h4 mb-4">Danh sách danh mục</h2>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th>Số sản phẩm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($danhSachDanhMuc) > 0) { ?>

                            <?php foreach ($danhSachDanhMuc as $danhMuc) { ?>
                                <tr>
                                    <td>
                                        <?php
                                            echo (int) $danhMuc['MaDanhMuc'];
                                        ?>
                                    </td>

                                    <td>
                                        <strong>
                                            <?php
                                                echo htmlspecialchars(
                                                    $danhMuc['TenDanhMuc']
                                                );
                                            ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?php
                                            echo htmlspecialchars(
                                                $danhMuc['MoTa'] ?? ''
                                            );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                            echo (int) $danhMuc['SoSanPham'];
                                        ?>
                                    </td>

                                    <td>
                                        <a
                                            href="categories.php?sua=<?php
                                                echo (int) $danhMuc['MaDanhMuc'];
                                            ?>"
                                            class="btn btn-sm btn-warning"
                                        >
                                            Sửa
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>

                        <?php } else { ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    Chưa có danh mục nào.
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</body>
</html>
