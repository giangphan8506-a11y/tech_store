
<?php
    $pageTitle = 'Giới thiệu - Tech Store';

    require_once __DIR__ . '/config/database.php';
    require_once __DIR__ . '/includes/header.php';
?>

<main class="about-page">

    <!-- HERO -->
    <section class="about-hero">
        <div class="about-container">
            <div class="about-hero-content">
                <span class="about-eyebrow">VỀ CHÚNG TÔI</span>

                <h1>
                    Công nghệ tốt hơn,
                    <span>trải nghiệm tốt hơn.</span>
                </h1>

                <p>
                    Tech Store là cửa hàng công nghệ hướng đến việc mang
                    những sản phẩm phù hợp đến gần hơn với khách hàng,
                    cùng trải nghiệm mua sắm đơn giản và thuận tiện.
                </p>

                <a href="products.php" class="about-btn">
                    Khám phá sản phẩm
                    <span>→</span>
                </a>
            </div>

            <div class="about-hero-visual">
                <div class="about-visual-card">
                    <span class="about-visual-label">TECH STORE</span>

                    <div class="about-visual-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                    <h2>Technology<br>for everyday life.</h2>

                    <p>
                        Khám phá thế giới công nghệ theo cách của bạn.
                    </p>

                    <div class="about-visual-line"></div>
                </div>

                <div class="about-floating-badge">
                    <span class="about-badge-dot"></span>
                    Khám phá công nghệ
                </div>
            </div>
        </div>
    </section>

    <!-- INTRODUCTION -->
    <section class="about-introduction">
        <div class="about-container about-intro-grid">

            <div class="about-section-heading">
                <span class="about-eyebrow">CÂU CHUYỆN CỦA CHÚNG TÔI</span>

                <h1>
                    Công nghệ không chỉ là sản phẩm,
                    mà còn là <span>giải pháp.</span>
                </h1>
            </div>

            <div class="about-intro-text">
                <p>
                    Trong cuộc sống hiện đại, công nghệ đóng vai trò quan
                    trọng trong học tập, công việc và giải trí. Tech Store
                    được xây dựng với mong muốn giúp khách hàng dễ dàng
                    tìm kiếm và lựa chọn những sản phẩm công nghệ phù hợp
                    với nhu cầu của mình.
                </p>

                <p>
                    Thông qua website, khách hàng có thể tìm kiếm sản phẩm,
                    xem thông tin chi tiết, tham khảo giá bán và đặt hàng
                    thuận tiện. Chúng tôi hướng đến một trải nghiệm mua sắm
                    rõ ràng, dễ sử dụng và thân thiện với người dùng.
                </p>

                <a href="contact.php" class="about-text-link">
                    Liên hệ với chúng tôi <span>→</span>
                </a>
            </div>

        </div>
    </section>

    <!-- VALUES -->
    <section class="about-values">
        <div class="about-container">

            <div class="about-centered-heading">
                <span class="about-eyebrow">GIÁ TRỊ HƯỚNG ĐẾN</span>

                <h1>Điều Tech Store quan tâm</h1>

                <p>
                    Tập trung vào những giá trị thiết thực để mang lại
                    trải nghiệm mua sắm thuận tiện cho khách hàng.
                </p>
            </div>

            <div class="about-values-grid">

                <article class="about-value-card">
                    <div class="about-value-icon">
                        <span>01</span>
                    </div>

                    <h3>Sản phẩm phù hợp</h3>

                    <p>
                        Cung cấp thông tin sản phẩm rõ ràng để khách hàng
                        dễ dàng tìm hiểu và lựa chọn theo nhu cầu.
                    </p>
                </article>

                <article class="about-value-card">
                    <div class="about-value-icon">
                        <span>02</span>
                    </div>

                    <h3>Trải nghiệm thuận tiện</h3>

                    <p>
                        Xây dựng giao diện trực quan, giúp việc tìm kiếm,
                        xem sản phẩm và đặt hàng trở nên đơn giản.
                    </p>
                </article>

                <article class="about-value-card">
                    <div class="about-value-icon">
                        <span>03</span>
                    </div>

                    <h3>Khách hàng là trọng tâm</h3>

                    <p>
                        Hướng đến việc hỗ trợ khách hàng tiếp cận thông tin
                        và lựa chọn sản phẩm một cách dễ dàng.
                    </p>
                </article>

            </div>
        </div>
    </section>

    <!-- SHOPPING EXPERIENCE -->
    <section class="about-experience">
        <div class="about-container about-experience-grid">

            <div class="about-experience-content">
                <span class="about-eyebrow">MUA SẮM CÙNG TECH STORE</span>

                <h2>
                    Tìm sản phẩm bạn cần,
                    <span>theo cách đơn giản hơn.</span>
                </h2>

                <p>
                    Khám phá danh mục sản phẩm, tìm kiếm thiết bị phù hợp
                    và theo dõi đơn hàng ngay trên website.
                </p>

                <a href="products.php" class="about-btn">
                    Xem sản phẩm
                    <span>→</span>
                </a>
            </div>

            <div class="about-experience-list">

                <div class="about-experience-item">
                    <span class="about-check">✓</span>

                    <div>
                        <h3>Dễ dàng tìm kiếm</h3>
                        <p>Tìm sản phẩm theo tên và danh mục.</p>
                    </div>
                </div>

                <div class="about-experience-item">
                    <span class="about-check">✓</span>

                    <div>
                        <h3>Thông tin rõ ràng</h3>
                        <p>Xem giá bán, mô tả và tình trạng tồn kho.</p>
                    </div>
                </div>

                <div class="about-experience-item">
                    <span class="about-check">✓</span>

                    <div>
                        <h3>Theo dõi đơn hàng</h3>
                        <p>Kiểm tra thông tin và trạng thái đơn hàng của bạn.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="about-cta">
        <div class="about-container">
            <div class="about-cta-content">
                <span class="about-eyebrow">BẮT ĐẦU KHÁM PHÁ</span>

                <h2>Sẵn sàng tìm thiết bị phù hợp với bạn?</h2>

                <p>
                    Ghé thăm cửa hàng trực tuyến và khám phá các sản phẩm
                    công nghệ đang có tại Tech Store.
                </p>

                <div class="about-cta-actions">
                    <a href="products.php" class="about-btn">
                        Mua sắm ngay <span>→</span>
                    </a>

                    <a href="contact.php" class="about-secondary-btn">
                        Liên hệ
                    </a>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
    require_once __DIR__ . '/includes/footer.php';
?>
