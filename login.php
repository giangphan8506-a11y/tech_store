
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/config/database.php';

    $pageTitle = 'Đăng nhập - Tech Store';
    $thongBao = '';
    $loi = '';

    // Hien thi thong bao tu trang khac chuyen den
    if (isset($_SESSION['thong_bao'])) {
        $thongBao = $_SESSION['thong_bao'];
        unset($_SESSION['thong_bao']);
    }

    // Xu ly dang nhap
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $matKhau = $_POST['mat_khau'] ?? '';

        if ($email === '' || $matKhau === '') {
            $loi = 'Vui lòng nhập đầy đủ email và mật khẩu.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loi = 'Email không hợp lệ.';
        } else {
            try {
                $sql = "
                    SELECT *
                    FROM NguoiDung
                    WHERE Email = :email
                    LIMIT 1
                ";

                $stmt = $connection->prepare($sql);
                $stmt->execute([
                    ':email' => $email
                ]);

                $nguoiDung = $stmt->fetch();

                if ($nguoiDung && password_verify(
                    $matKhau,
                    $nguoiDung['MatKhau']
                )) {
                    // Tao session moi sau khi dang nhap
                    session_regenerate_id(true);

                    $_SESSION['nguoi_dung'] = [
                        'MaNguoiDung' => $nguoiDung['MaNguoiDung'],
                        'HoTen' => $nguoiDung['HoTen'],
                        'Email' => $nguoiDung['Email'],
                        'SoDienThoai' => $nguoiDung['SoDienThoai'],
                        'DiaChi' => $nguoiDung['DiaChi'],
                        'VaiTro' => $nguoiDung['VaiTro']
                    ];

                    // Chuyen trang theo vai tro
                    if ($nguoiDung['VaiTro'] === 'admin') {
                        header('Location: admin/index.php');
                    } else {
                        header('Location: index.php');
                    }

                    exit;
                } else {
                    $loi = 'Email hoặc mật khẩu không chính xác.';
                }
            } catch (PDOException $e) {
                $loi = 'Đã xảy ra lỗi. Vui lòng thử lại sau.';
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
        <div class="auth-card">

            <div class="auth-heading">
                <h1>Chào mừng trở lại!</h1>
                <p>Đăng nhập để tiếp tục mua sắm tại Tech Store.</p>
            </div>

            <?php if ($thongBao !== '') { ?>
                <div class="alert alert-success" role="alert">
                    <?php echo htmlspecialchars($thongBao); ?>
                </div>
            <?php } ?>

            <?php if ($loi !== '') { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($loi); ?>
                </div>
            <?php } ?>

            <form action="login.php" method="POST">

                <div class="auth-form-group">
                    <label for="email">Địa chỉ email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="Nhập email của bạn"
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        autocomplete="email"
                        required
                    >
                </div>

                <div class="auth-form-group">
                    <label for="mat_khau">Mật khẩu</label>

                    <input
                        type="password"
                        id="mat_khau"
                        name="mat_khau"
                        class="form-control"
                        placeholder="Nhập mật khẩu"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="auth-submit">
                    Đăng nhập
                </button>

            </form>

            <p class="auth-switch">
                Chưa có tài khoản?
                <a href="register.php">Đăng ký ngay</a>
            </p>

        </div>
    </main>

    <footer class="auth-footer">
        © 2026 Tech Store. All rights reserved.
    </footer>

</body>
</html>
