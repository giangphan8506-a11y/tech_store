
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $pageTitle = 'Liên hệ - Tech Store';

    $loi = [];
    $hoTen = '';
    $email = '';
    $tieuDe = '';
    $noiDung = '';

    $thongBao = $_SESSION['thong_bao_lien_he'] ?? '';
    unset($_SESSION['thong_bao_lien_he']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $hoTen = trim($_POST['ho_ten'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $tieuDe = trim($_POST['tieu_de'] ?? '');
        $noiDung = trim($_POST['noi_dung'] ?? '');

        if ($hoTen === '' || mb_strlen($hoTen) > 100) {
            $loi[] = 'Họ tên không được để trống và tối đa 100 ký tự.';
        }

        if (
            $email === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($email) > 255
        ) {
            $loi[] = 'Vui lòng nhập địa chỉ email hợp lệ.';
        }

        if ($tieuDe === '' || mb_strlen($tieuDe) > 150) {
            $loi[] = 'Tiêu đề không được để trống và tối đa 150 ký tự.';
        }

        if ($noiDung === '' || mb_strlen($noiDung) > 3000) {
            $loi[] = 'Nội dung không được để trống và tối đa 3000 ký tự.';
        }

        if (empty($loi)) {
            // Hiện tại form mới kiểm tra dữ liệu.
            // Chưa lưu vào database hoặc gửi email.

            $_SESSION['thong_bao_lien_he'] =
                'Thông tin hợp lệ! Form hiện chưa được kết nối để lưu hoặc gửi email.';

            header('Location: contact.php');
            exit;
        }
    }

    require_once __DIR__ . '/includes/header.php';
?>

<main class="contact-page">

    <!-- HERO -->
    <section class="contact-hero">
        <div class="contact-container">

            <span class="contact-eyebrow">CONTACT TECH STORE</span>

            <h1>
                Hãy kết nối với
                <span>chúng tôi.</span>
            </h1>

            <p>
                Bạn có câu hỏi, góp ý hoặc cần hỗ trợ?
                Hãy để lại lời nhắn để chúng tôi biết bạn đang cần gì.
            </p>

        </div>
    </section>

    <!-- CONTACT CONTENT -->
    <section class="contact-main">
        <div class="contact-container contact-grid">

            <!-- LEFT: INFORMATION -->
            <div class="contact-information">

                <span class="contact-eyebrow">THÔNG TIN LIÊN HỆ</span>

                <h2>
                    Chúng tôi luôn sẵn sàng
                    lắng nghe bạn.
                </h2>

                <p class="contact-description">
                    Hãy sử dụng biểu mẫu bên cạnh để gửi câu hỏi hoặc góp ý.
                    Bạn cũng có thể kết nối với Tech Store qua các nền tảng
                    trực tuyến khi những liên kết được cập nhật.
                </p>

                <div class="contact-info-list">

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <span>@</span>
                        </div>

                        <div>
                            <h3>Email</h3>
                            <p>Thông tin email sẽ được cập nhật.</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <span>⌖</span>
                        </div>

                        <div>
                            <h3>Địa chỉ</h3>
                            <p>Thông tin địa chỉ sẽ được cập nhật.</p>
                        </div>
                    </div>

                </div>

                <!-- SOCIAL LINKS -->
                <div class="contact-social-section">

                    <h3>Kết nối với chúng tôi</h3>

                    <p>
                        Theo dõi các nền tảng của Tech Store.
                    </p>


                    <div class="contact-social-list">

                        <!-- GITHUB -->
                        <a
                            href="https://github.com/giangphan8506-a11y"
                            class="contact-social-item"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span class="contact-social-icon">
                                <img
                                    src="https://cdn.simpleicons.org/github/10375c"
                                    alt="GitHub"
                                >
                            </span>
                            <div>
                                <strong>GitHub</strong>
                                <span>Xem các dự án của tôi</span>
                            </div>
                        </a>

                        <!-- FACEBOOK -->
                        <a
                            href="https://www.facebook.com/share/1WFFK8UtwP/"
                            class="contact-social-item"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span class="contact-social-icon">
                                <img
                                    src="https://cdn.simpleicons.org/facebook/1877F2"
                                    alt="Facebook"
                                >
                            </span>
                            <div>
                                <strong>Facebook</strong>
                                <span>Kết nối với tôi</span>
                            </div>
                        </a>

                        <!-- INSTAGRAM -->
                        <a
                            href="https://www.instagram.com/p_zian856._?psln=MW53NWN2cWd0aWxleQ=="
                            class="contact-social-item"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <span class="contact-social-icon">
                                <img
                                    src="https://cdn.simpleicons.org/instagram/E4405F"
                                    alt="Instagram"
                                >
                            </span>
                            <div>
                                <strong>Instagram</strong>
                                <span>Theo dõi tôi</span>
                            </div>
                        </a>

                    </div>

                    <!--
                    Khi có link thật, thay phần tử div tương ứng bằng a.
                    Ví dụ:
                    <a href="https://github.com/USERNAME"
                       target="_blank"
                       rel="noopener noreferrer">
                    -->

                </div>

            </div>

            <!-- RIGHT: CONTACT FORM -->
            <div class="contact-form-card">

                <div class="contact-form-heading">
                    <h2>Gửi lời nhắn</h2>

                    <p>
                        Điền thông tin bên dưới để bắt đầu.
                    </p>
                </div>

                <?php if ($thongBao !== '') { ?>
                    <div class="contact-alert contact-alert-info" role="status">
                        <?php echo htmlspecialchars($thongBao); ?>
                    </div>
                <?php } ?>

                <?php if (!empty($loi)) { ?>
                    <div class="contact-alert contact-alert-error" role="alert">
                        <ul>
                            <?php foreach ($loi as $thongDiep) { ?>
                                <li>
                                    <?php echo htmlspecialchars($thongDiep); ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <form action="contact.php" method="POST">

                    <div class="contact-form-group">
                        <label for="ho_ten">Họ và tên</label>

                        <input
                            type="text"
                            id="ho_ten"
                            name="ho_ten"
                            placeholder="Nhập họ và tên của bạn"
                            maxlength="100"
                            value="<?php echo htmlspecialchars($hoTen); ?>"
                            required
                        >
                    </div>

                    <div class="contact-form-group">
                        <label for="email">Email</label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="example@email.com"
                            maxlength="255"
                            value="<?php echo htmlspecialchars($email); ?>"
                            required
                        >
                    </div>

                    <div class="contact-form-group">
                        <label for="tieu_de">Tiêu đề</label>

                        <input
                            type="text"
                            id="tieu_de"
                            name="tieu_de"
                            placeholder="Bạn muốn liên hệ về vấn đề gì?"
                            maxlength="150"
                            value="<?php echo htmlspecialchars($tieuDe); ?>"
                            required
                        >
                    </div>

                    <div class="contact-form-group">
                        <label for="noi_dung">Nội dung</label>

                        <textarea
                            id="noi_dung"
                            name="noi_dung"
                            rows="5"
                            maxlength="3000"
                            placeholder="Nhập nội dung bạn muốn gửi..."
                            required
                        ><?php echo htmlspecialchars($noiDung); ?></textarea>
                    </div>

                    <button type="submit" class="contact-submit-btn">
                        Gửi lời nhắn
                        <span>→</span>
                    </button>

                    <p class="contact-form-note">
                        Lưu ý: biểu mẫu hiện mới kiểm tra dữ liệu,
                        chưa lưu thông tin hoặc gửi email.
                    </p>

                </form>

            </div>

        </div>
    </section>

    <!-- BOTTOM CTA -->
    <section class="contact-bottom">
        <div class="contact-container">

            <div>
                <span class="contact-eyebrow">TECH STORE</span>

                <h2>Khám phá các sản phẩm công nghệ.</h2>

                <p>
                    Tìm kiếm thiết bị phù hợp với nhu cầu của bạn.
                </p>
            </div>

            <a href="products.php" class="contact-bottom-btn">
                Xem sản phẩm <span>→</span>
            </a>

        </div>
    </section>

</main>

<?php
    require_once __DIR__ . '/includes/footer.php';
?>
