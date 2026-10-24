
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/config/database.php';

    $pageTitle = 'Đăng ký - Tech Store';
    $loi = '';

    // Xu ly dang ky
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hoTen = trim($_POST['ho_ten'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $soDienThoai = trim($_POST['so_dien_thoai'] ?? '');
        $diaChi = trim($_POST['dia_chi'] ?? '');
        $matKhau = $_POST['mat_khau'] ?? '';
        $xacNhanMatKhau = $_POST['xac_nhan_mat_khau'] ?? '';

        if (
            $hoTen === '' ||
            $email === '' ||
            $matKhau === '' ||
            $xacNhanMatKhau === ''
        ) {
            $loi = 'Vui lòng nhập đầy đủ các thông tin bắt buộc.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loi = 'Địa chỉ email không hợp lệ.';
        } elseif (
            $soDienThoai !== '' &&
            !preg_match('/^[0-9]{10,11}$/', $soDienThoai)
        ) {
            $loi = 'Số điện thoại phải gồm 10 hoặc 11 chữ số.';
        } elseif (strlen($matKhau) < 8) {
            $loi = 'Mật khẩu phải có ít nhất 8 ký tự.';
        } elseif ($matKhau !== $xacNhanMatKhau) {
            $loi = 'Mật khẩu xác nhận không khớp.';
        } else {
            try {
                // Kiem tra email da ton tai chua
                $sqlKiemTra = "
                    SELECT MaNguoiDung
                    FROM NguoiDung
                    WHERE Email = :email
                    LIMIT 1
                ";

                $stmtKiemTra = $connection->prepare($sqlKiemTra);
                $stmtKiemTra->execute([
                    ':email' => $email
                ]);

                if ($stmtKiemTra->fetch()) {
                    $loi = 'Email này đã được đăng ký.';
                } else {
                    // Ma hoa mat khau truoc khi luu
                    $matKhauMaHoa = password_hash(
                        $matKhau,
                        PASSWORD_DEFAULT
                    );

                    $sqlThem = "
                        INSERT INTO NguoiDung
                            (HoTen, Email, MatKhau, SoDienThoai, DiaChi)
                        VALUES
                            (:hoTen, :email, :matKhau, :soDienThoai, :diaChi)
                    ";

                    $stmtThem = $connection->prepare($sqlThem);

                    $stmtThem->execute([
                        ':hoTen' => $hoTen,
                        ':email' => $email,
                        ':matKhau' => $matKhauMaHoa,
                        ':soDienThoai' => $soDienThoai,
                        ':diaChi' => $diaChi
                    ]);

                    $_SESSION['thong_bao'] =
                        'Đăng ký thành công! Vui lòng đăng nhập.';

                    header('Location: login.php');
                    exit;
                }
            } catch (PDOException $e) {
                $loi = 'Không thể tạo tài khoản lúc này. Vui lòng thử lại.';
            }
        }
    }
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

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="auth-page">

    <div class="auth-top">
        <a href="index.php" class="auth-logo">
            TECH<span>STORE</span>
        </a>

        <a href="index.php" class="auth-home">
            ← Về trang chủ
        </a>
    </div>

    <main class="auth-container">
        <div class="auth-card auth-card-register">

            <div class="auth-heading">
                <h1>Tạo tài khoản</h1>
                <p>Đăng ký thành viên để mua sắm tại Tech Store.</p>
            </div>

            <?php if ($loi !== '') { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($loi); ?>
                </div>
            <?php } ?>

            <form action="register.php" method="POST">

                <div class="auth-form-group">
                    <label for="ho_ten">Họ và tên *</label>

                    <input
                        type="text"
                        id="ho_ten"
                        name="ho_ten"
                        class="form-control"
                        placeholder="Nhập họ và tên"
                        value="<?php echo htmlspecialchars($_POST['ho_ten'] ?? ''); ?>"
                        autocomplete="name"
                        required
                    >
                </div>

                <div class="auth-form-group">
                    <label for="email">Địa chỉ email *</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="example@email.com"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="auth-form-group">
                    <label for="so_dien_thoai">Số điện thoại</label>

                    <input
                        type="tel"
                        id="so_dien_thoai"
                        name="so_dien_thoai"
                        class="form-control"
                        placeholder="Nhập số điện thoại"
                        value="<?php echo htmlspecialchars($_POST['so_dien_thoai'] ?? ''); ?>"
                        autocomplete="tel"
                    >
                </div>

                <div class="auth-form-group">
                    <label for="dia_chi">Địa chỉ</label>

                    <input
                        type="text"
                        id="dia_chi"
                        name="dia_chi"
                        class="form-control"
                        placeholder="Nhập địa chỉ giao hàng"
                        value="<?php echo htmlspecialchars($_POST['dia_chi'] ?? ''); ?>"
                        autocomplete="street-address"
                    >
                </div>

                <div class="auth-form-group">
                    <label for="mat_khau">Mật khẩu *</label>

                    <input
                        type="password"
                        id="mat_khau"
                        name="mat_khau"
                        class="form-control"
                        placeholder="Ít nhất 8 ký tự"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <div class="auth-form-group">
                    <label for="xac_nhan_mat_khau">
                        Xác nhận mật khẩu *
                    </label>

                    <input
                        type="password"
                        id="xac_nhan_mat_khau"
                        name="xac_nhan_mat_khau"
                        class="form-control"
                        placeholder="Nhập lại mật khẩu"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <button type="submit" class="auth-submit">
                    Tạo tài khoản
                </button>

            </form>

            <p class="auth-switch">
                Đã có tài khoản?
                <a href="login.php">Đăng nhập</a>
            </p>

        </div>
    </main>

    <footer class="auth-footer">
        © 2026 Tech Store. All rights reserved.
    </footer>

</body>
</html>
