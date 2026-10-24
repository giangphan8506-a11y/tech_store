
<?php
    $baseUrl = '/tech_store';
?>

</main>

<!-- FOOTER -->
<footer class="main-footer">
    <div class="container">

        <div class="row g-4">

            <div class="col-md-4">
                <h3 class="footer-logo">
                    TECH<span>STORE</span>
                </h3>

                <p>
                    Cửa hàng công nghệ cung cấp điện thoại,
                    laptop, máy tính bảng và phụ kiện.
                </p>
            </div>

            <div class="col-md-4">
                <h5>Thông tin</h5>

                <a href="<?php echo $baseUrl; ?>/about.php">
                    Giới thiệu
                </a>

                <a href="<?php echo $baseUrl; ?>/products.php">
                    Sản phẩm
                </a>

                <a href="<?php echo $baseUrl; ?>/contact.php">
                    Liên hệ
                </a>
            </div>

            <div class="col-md-4">
                <h5>Hỗ trợ khách hàng</h5>

                <p>Hotline: 0343301930 / 0372843890</p>
                <p>Email: giang_dth245745@student.agu.edu.vn</p>
                <p>Địa chỉ: Long Xuyên, An Giang, Việt Nam</p>
            </div>

        </div>

        <hr>

        <p class="copyright">
            © 2026 Tech Store. All rights reserved.
        </p>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo $baseUrl; ?>/assets/js/main.js"></script>

</body>
</html>
