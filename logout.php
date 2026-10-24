
<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Xoa thong tin dang nhap
    $_SESSION = [];

    // Huy session
    session_destroy();

    // Quay ve trang chu
    header('Location: index.php');
    exit;
?>
