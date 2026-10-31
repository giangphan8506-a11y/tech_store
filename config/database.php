<?php
    // PDO - PHP Data Objects

    define('DATABASE_SERVER', 'localhost');
    define('DATABASE_USER', 'root');
    define('DATABASE_PASSWORD', '');
    define('DATABASE_NAME', 'tech_store');

    // $connection = null;

    try {
        $connection = new PDO(
            "mysql:host=" . DATABASE_SERVER .
            ";dbname=" . DATABASE_NAME .
            ";charset=utf8mb4",
            DATABASE_USER,
            DATABASE_PASSWORD
        );
        // cấu hình sử lí lỗi
        $connection->setAttribute( //-> dùng để truy cập phương thức or thộc tính của một đối tượng
            PDO::ATTR_ERRMODE, // :: là toán tủ truy cập thành phân của lớp
            PDO::ERRMODE_EXCEPTION // phát sinh ngoại lệ khi gặp lỗi để try/catch có thể bt và sử lí 
        );
        //  cấu hình lấy dữ liệu
        $connection->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE, // thuộc tính cấu hình chế độ lấy dữ liệu mặc định
            PDO::FETCH_ASSOC // yêu cầu dữ liệu được trả về dưới dạng mảng kết hợp [], sử dụng tên cột làm khóa.
        );

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage()); //die làm kết thúc quá trình thực thi , và xuất thông điệp
    }
?>