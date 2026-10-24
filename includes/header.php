
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $baseUrl = '/tech_store';
    $pageTitle = $pageTitle ?? 'Tech Store';
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

<body>

<!-- THANH THONG BAO -->
<div class="topbar">
    Miễn phí giao hàng cho đơn hàng từ 2.000.000đ
    <span>|</span>
    Hỗ trợ khách hàng: 0343301930 / 0372843890
</div>

<!-- HEADER -->
<header class="main-header">
    <div class="container header-content">

        <a href="<?php echo $baseUrl; ?>/index.php" class="logo">
            TECH<span>STORE</span>
        </a>

        <form action="<?php echo $baseUrl; ?>/products.php"
              method="GET"
              class="search-form">

            <input
                type="text"
                name="q"
                placeholder="Tìm điện thoại, laptop, phụ kiện..."
            >

            <button type="submit">Tìm kiếm</button>
        </form>

        <div class="header-account">
            <?php if (isset($_SESSION['nguoi_dung'])) { ?>

                <span>
                    Xin chào,
                    <?php echo htmlspecialchars(
                        $_SESSION['nguoi_dung']['HoTen']
                    ); ?>
                </span>

                <a href="<?php echo $baseUrl; ?>/my-orders.php">
                    Đơn hàng
                </a>

                <?php if (
                    $_SESSION['nguoi_dung']['VaiTro'] === 'admin'
                ) { ?>
                    <a href="<?php echo $baseUrl; ?>/admin/index.php">
                        Quản trị
                    </a>
                <?php } ?>

                <a href="<?php echo $baseUrl; ?>/logout.php">
                    Đăng xuất
                </a>

            <?php } else { ?>

                <a href="<?php echo $baseUrl; ?>/login.php">
                    Đăng nhập
                </a>

                <a href="<?php echo $baseUrl; ?>/register.php">
                    Đăng ký
                </a>

            <?php } ?>
        </div>

    </div>
</header>


<!-- NAVIGATION -->
<nav class="main-nav">
    <div class="container">

        <!-- NUT MENU TREN DIEN THOAI -->
        <button
            class="menu-toggle"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainMenu"
            aria-expanded="false"
            aria-controls="mainMenu"
        >
            <span class="menu-icon">&#9776;</span>
            <span>Danh mục</span>
        </button>

        <!-- CAC CHUC NANG -->
        <div class="collapse main-menu" id="mainMenu">

            <a href="<?php echo $baseUrl; ?>/index.php">
                Trang chủ
            </a>

            <a href="<?php echo $baseUrl; ?>/products.php">
                Sản phẩm
            </a>

            <a href="<?php echo $baseUrl; ?>/products.php?danh_muc=1">
                Điện thoại
            </a>

            <a href="<?php echo $baseUrl; ?>/products.php?danh_muc=2">
                Laptop
            </a>

            <a href="<?php echo $baseUrl; ?>/products.php?danh_muc=3">
                Máy tính bảng
            </a>

            <a href="<?php echo $baseUrl; ?>/products.php?danh_muc=4">
                Phụ kiện
            </a>

            <a href="<?php echo $baseUrl; ?>/about.php">
                Giới thiệu
            </a>

            <a href="<?php echo $baseUrl; ?>/contact.php">
                Liên hệ
            </a>

        </div>

    </div>
</nav>


<!-- CHU CHAY -->
<div class="announcement">
    <div class="announcement-text">
        TECH STORE - SẢN PHẨM CHÍNH HÃNG -
        GIÁ TỐT MỖI NGÀY - ƯU ĐÃI HẤP DẪN -
        TECH STORE - SẢN PHẨM CHÍNH HÃNG -
        GIÁ TỐT MỖI NGÀY - ƯU ĐÃI HẤP DẪN -
    </div>
</div>

<!-- NOI DUNG TRANG BAT DAU -->
<main class="container main-container">
