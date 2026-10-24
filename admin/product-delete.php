
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

    // Chi cho phep xoa bang POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: products.php');
        exit;
    }

    $maSanPham = filter_input(
        INPUT_POST,
        'ma_san_pham',
        FILTER_VALIDATE_INT
    );

    if (!$maSanPham || $maSanPham <= 0) {
        header('Location: products.php?ket_qua=khong_hop_le');
        exit;
    }

    try {
        // Lay thong tin san pham
        $sql = "
            SELECT HinhAnh
            FROM SanPham
            WHERE MaSanPham = :maSanPham
        ";

        $stmt = $connection->prepare($sql);
        $stmt->execute([
            ':maSanPham' => $maSanPham
        ]);

        $sanPham = $stmt->fetch();

        if (!$sanPham) {
            header('Location: products.php?ket_qua=khong_ton_tai');
            exit;
        }

        // Xoa san pham
        $sqlXoa = "
            DELETE FROM SanPham
            WHERE MaSanPham = :maSanPham
        ";

        $stmtXoa = $connection->prepare($sqlXoa);
        $stmtXoa->execute([
            ':maSanPham' => $maSanPham
        ]);

        // Chi xoa anh neu khong con san pham nao su dung
        $hinhAnh = basename($sanPham['HinhAnh'] ?? '');

        if ($hinhAnh !== '') {
            $sqlAnh = "
                SELECT COUNT(*)
                FROM SanPham
                WHERE HinhAnh = :hinhAnh
            ";

            $stmtAnh = $connection->prepare($sqlAnh);
            $stmtAnh->execute([
                ':hinhAnh' => $sanPham['HinhAnh']
            ]);

            $soSanPhamDungAnh = (int) $stmtAnh->fetchColumn();

            if ($soSanPhamDungAnh === 0) {
                $duongDanAnh = __DIR__
                    . '/../assets/images/'
                    . $hinhAnh;

                if (is_file($duongDanAnh)) {
                    unlink($duongDanAnh);
                }
            }
        }

        header('Location: products.php?ket_qua=xoa_thanh_cong');
        exit;

    } catch (PDOException $e) {
        // San pham da co trong don hang thi khong duoc xoa
        if ($e->getCode() === '23000') {
            header('Location: products.php?ket_qua=dang_duoc_su_dung');
        } else {
            header('Location: products.php?ket_qua=that_bai');
        }

        exit;
    }
?>
