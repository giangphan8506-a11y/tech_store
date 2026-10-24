-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2026 at 12:10 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"tech_store\",\"table\":\"nguoidung\"},{\"db\":\"tech_store\",\"table\":\"SanPham\"},{\"db\":\"\",\"table\":\"ChiTietDonHang\"},{\"db\":\"tech_store\",\"table\":\"sanpham\"},{\"db\":\"tech_store\",\"table\":\"donhang\"},{\"db\":\"tech_store\",\"table\":\"danhmuc\"},{\"db\":\"tech_store\",\"table\":\"chitietdonhang\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-10-24 08:30:35', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `tech_store`
--
CREATE DATABASE IF NOT EXISTS `tech_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tech_store`;

-- --------------------------------------------------------

--
-- Table structure for table `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `MaChiTiet` int(11) NOT NULL,
  `MaDonHang` int(11) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL,
  `DonGiaLucMua` decimal(15,2) NOT NULL,
  `ThanhTien` decimal(15,2) NOT NULL
) ;

--
-- Dumping data for table `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`MaChiTiet`, `MaDonHang`, `MaSanPham`, `SoLuong`, `DonGiaLucMua`, `ThanhTien`) VALUES
(1, 1, 42, 2, 450000.00, 900000.00),
(2, 2, 37, 1, 2290000.00, 2290000.00);

-- --------------------------------------------------------

--
-- Table structure for table `danhmuc`
--

CREATE TABLE `danhmuc` (
  `MaDanhMuc` int(11) NOT NULL,
  `TenDanhMuc` varchar(100) NOT NULL,
  `MoTa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `danhmuc`
--

INSERT INTO `danhmuc` (`MaDanhMuc`, `TenDanhMuc`, `MoTa`) VALUES
(1, 'Điện thoại', 'Điện thoại thông minh'),
(2, 'Laptop', 'Laptop học tập và làm việc'),
(3, 'Máy tính bảng', 'Máy tính bảng'),
(4, 'Phụ kiện', 'Phụ kiện công nghệ');

-- --------------------------------------------------------

--
-- Table structure for table `donhang`
--

CREATE TABLE `donhang` (
  `MaDonHang` int(11) NOT NULL,
  `MaNguoiDung` int(11) NOT NULL,
  `TenNguoiNhan` varchar(100) NOT NULL,
  `SoDienThoai` varchar(15) NOT NULL,
  `DiaChiGiaoHang` varchar(255) NOT NULL,
  `GhiChu` text DEFAULT NULL,
  `TongTien` decimal(15,2) NOT NULL DEFAULT 0.00,
  `TrangThai` enum('pending','confirmed','shipping','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `NgayDat` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `donhang`
--

INSERT INTO `donhang` (`MaDonHang`, `MaNguoiDung`, `TenNguoiNhan`, `SoDienThoai`, `DiaChiGiaoHang`, `GhiChu`, `TongTien`, `TrangThai`, `NgayDat`) VALUES
(1, 1, 'Phan Thanh Giang', '0343301930', 'An Giang, Long Xuyên', '', 900000.00, 'delivered', '2026-10-24 15:07:02'),
(2, 2, 'Phanphila Prixa', '0377647200', 'An Giang, Long Xuyên', '', 2290000.00, 'confirmed', '2026-10-24 15:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `nguoidung`
--

CREATE TABLE `nguoidung` (
  `MaNguoiDung` int(11) NOT NULL,
  `HoTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `MatKhau` varchar(255) NOT NULL,
  `SoDienThoai` varchar(15) DEFAULT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `VaiTro` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nguoidung`
--

INSERT INTO `nguoidung` (`MaNguoiDung`, `HoTen`, `Email`, `MatKhau`, `SoDienThoai`, `DiaChi`, `VaiTro`, `NgayTao`) VALUES
(1, 'Phan Thanh Giang', 'giangphan8506@gmail.com', '$2y$10$zucFZdo7X2F1FmCjh6PXMOXpWELIPHn1.yap3m.jxl/UezATtkSGO', '0343301930', 'An Giang, Long Xuyên', 'admin', '2026-10-24 15:05:06'),
(2, 'Phanphila Prixa', 'prixa@gmail.com', '$2y$10$Xx92PzX02eOQFwYsXH53l.kGkyFGJsaqy9TC09xqeywGY7yEHIH9q', '0377647200', 'An Giang, Long Xuyên', 'customer', '2026-10-24 15:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `sanpham`
--

CREATE TABLE `sanpham` (
  `MaSanPham` int(11) NOT NULL,
  `MaDanhMuc` int(11) NOT NULL,
  `TenSanPham` varchar(150) NOT NULL,
  `MoTa` text DEFAULT NULL,
  `DonGia` decimal(15,2) NOT NULL DEFAULT 0.00,
  `SoLuongTon` int(11) NOT NULL DEFAULT 0,
  `HinhAnh` varchar(255) DEFAULT NULL,
  `NgayTao` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `sanpham`
--

INSERT INTO `sanpham` (`MaSanPham`, `MaDanhMuc`, `TenSanPham`, `MoTa`, `DonGia`, `SoLuongTon`, `HinhAnh`, `NgayTao`) VALUES
(2, 1, 'iPhone 16 Pro', 'Dien thoai Apple iPhone 16 Pro', 25990000.00, 8, 'iphone16-pro.jpg', '2026-10-24 14:12:01'),
(3, 1, 'iPhone 15 Plus', 'Dien thoai Apple iPhone 15 Plus', 18990000.00, 7, 'iphone15-plus.jpg', '2026-10-24 14:12:01'),
(4, 1, 'Samsung Galaxy S24', 'Dien thoai Samsung Galaxy S24', 16990000.00, 9, 'samsung-s24.jpg', '2026-10-24 14:12:01'),
(5, 1, 'Samsung Galaxy S24 Ultra', 'Dien thoai Samsung cao cap', 25990000.00, 5, 'samsung-s24-ultra.jpg', '2026-10-24 14:12:01'),
(6, 1, 'Samsung Galaxy A35', 'Dien thoai Samsung phan khuc tam trung', 6990000.00, 15, 'samsung-a35.jpg', '2026-10-24 14:12:01'),
(7, 1, 'Xiaomi Redmi Note 13', 'Dien thoai Xiaomi gia tot', 4990000.00, 20, 'redmi-note-13.jpg', '2026-10-24 14:12:01'),
(8, 1, 'Xiaomi 14', 'Dien thoai Xiaomi cao cap', 16990000.00, 6, 'xiaomi-14.jpg', '2026-10-24 14:12:01'),
(9, 1, 'OPPO Reno11 F', 'Dien thoai OPPO thiet ke hien dai', 8990000.00, 11, 'oppo-reno11-f.jpg', '2026-10-24 14:12:01'),
(10, 1, 'vivo V30', 'Dien thoai vivo chup anh dep', 10990000.00, 8, 'vivo-v30.jpg', '2026-10-24 14:12:01'),
(11, 2, 'Dell Inspiron 15', 'Laptop Dell hoc tap va van phong', 14990000.00, 8, 'dell-inspiron-15.jpg', '2026-10-24 14:12:01'),
(12, 2, 'Dell XPS 13', 'Laptop Dell cao cap', 28990000.00, 5, 'dell-xps-13.jpg', '2026-10-24 14:12:01'),
(13, 2, 'HP Pavilion 15', 'Laptop HP phuc vu hoc tap', 15990000.00, 7, 'hp-pavilion-15.jpg', '2026-10-24 14:12:01'),
(14, 2, 'Lenovo IdeaPad Slim 5', 'Laptop Lenovo mong nhe', 16990000.00, 9, 'lenovo-ideapad-slim-5.jpg', '2026-10-24 14:12:01'),
(15, 2, 'Lenovo ThinkPad E14', 'Laptop Lenovo cho cong viec', 18990000.00, 6, 'thinkpad-e14.jpg', '2026-10-24 14:12:01'),
(16, 2, 'Acer Aspire 5', 'Laptop Acer hoc tap va van phong', 12990000.00, 12, 'acer-aspire-5.jpg', '2026-10-24 14:12:01'),
(17, 2, 'Acer Nitro V15', 'Laptop gaming Acer', 21990000.00, 5, 'acer-nitro-v15.jpg', '2026-10-24 14:12:01'),
(18, 2, 'Asus TUF Gaming F15', 'Laptop gaming Asus', 23990000.00, 4, 'asus-tuf-f15.jpg', '2026-10-24 14:12:01'),
(19, 2, 'Asus Zenbook 14', 'Laptop Asus mong nhe', 22990000.00, 5, 'asus-zenbook-14.jpg', '2026-10-24 14:12:01'),
(20, 2, 'Laptop MSI Thin 15', 'Laptop MSI danh cho game thu', 19990000.00, 6, 'msi-thin-15.jpg', '2026-10-24 14:12:01'),
(21, 3, 'iPad Air', 'May tinh bang Apple iPad Air', 15990000.00, 8, 'ipad-air.jpg', '2026-10-24 14:12:01'),
(22, 3, 'iPad Pro', 'May tinh bang Apple cao cap', 25990000.00, 4, 'ipad-pro.jpg', '2026-10-24 14:12:01'),
(23, 3, 'iPad mini', 'May tinh bang Apple nho gon', 12990000.00, 6, 'ipad-mini.jpg', '2026-10-24 14:12:01'),
(24, 3, 'Samsung Galaxy Tab S9', 'May tinh bang Samsung cao cap', 18990000.00, 5, 'galaxy-tab-s9.jpg', '2026-10-24 14:12:01'),
(25, 3, 'Samsung Galaxy Tab A9', 'May tinh bang Samsung gia tot', 4990000.00, 12, 'galaxy-tab-a9.jpg', '2026-10-24 14:12:01'),
(26, 3, 'Xiaomi Pad 6', 'May tinh bang Xiaomi', 8990000.00, 9, 'xiaomi-pad-6.jpg', '2026-10-24 14:12:01'),
(27, 3, 'Lenovo Tab P12', 'May tinh bang Lenovo man hinh lon', 9990000.00, 7, 'lenovo-tab-p12.jpg', '2026-10-24 14:12:01'),
(28, 3, 'Huawei MatePad 11', 'May tinh bang Huawei', 10990000.00, 5, 'huawei-matepad-11.jpg', '2026-10-24 14:12:01'),
(29, 4, 'AirPods Pro 2', 'Tai nghe khong day Apple', 5990000.00, 10, 'airpods-pro-2.jpg', '2026-10-24 14:12:01'),
(30, 4, 'AirPods 3', 'Tai nghe Apple AirPods', 3990000.00, 12, 'airpods-3.jpg', '2026-10-24 14:12:01'),
(31, 4, 'Samsung Galaxy Buds FE', 'Tai nghe khong day Samsung', 1990000.00, 15, 'galaxy-buds-fe.jpg', '2026-10-24 14:12:01'),
(32, 4, 'Sony WH-1000XM5', 'Tai nghe chong on Sony', 7990000.00, 5, 'sony-wh1000xm5.jpg', '2026-10-24 14:12:01'),
(33, 4, 'Logitech G102', 'Chuot gaming Logitech', 450000.00, 25, 'logitech-g102.jpg', '2026-10-24 14:12:01'),
(34, 4, 'Logitech MX Master 3S', 'Chuot khong day Logitech', 2490000.00, 10, 'mx-master-3s.jpg', '2026-10-24 14:12:01'),
(35, 4, 'Keychron K2', 'Ban phim co khong day', 1890000.00, 12, 'keychron-k2.jpg', '2026-10-24 14:12:01'),
(36, 4, 'Logitech K380', 'Ban phim Bluetooth nho gon', 750000.00, 18, 'logitech-k380.jpg', '2026-10-24 14:12:01'),
(37, 4, 'Samsung T7 1TB', 'O cung SSD di dong 1TB', 2290000.00, 7, 'samsung-t7-1tb.jpg', '2026-10-24 14:12:01'),
(38, 4, 'Kingston USB 64GB', 'USB luu tru 64GB', 180000.00, 30, 'kingston-usb-64gb.jpg', '2026-10-24 14:12:01'),
(39, 4, 'Anker 20W Charger', 'Cu sac nhanh Anker 20W', 390000.00, 20, 'anker-20w.jpg', '2026-10-24 14:12:01'),
(40, 4, 'Baseus 100W Cable', 'Cap sac nhanh Baseus', 250000.00, 25, 'baseus-100w-cable.jpg', '2026-10-24 14:12:01'),
(41, 4, 'Anker PowerCore 20000', 'Pin sac du phong 20000mAh', 990000.00, 14, 'anker-powercore-20000.jpg', '2026-10-24 14:12:01'),
(42, 4, 'Laptop Stand', 'Gia do laptop bang nhom', 450000.00, 15, 'laptop-stand.jpg', '2026-10-24 14:12:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`MaChiTiet`),
  ADD UNIQUE KEY `UQ_ChiTiet_DonHang_SanPham` (`MaDonHang`,`MaSanPham`),
  ADD KEY `FK_ChiTiet_SanPham` (`MaSanPham`);

--
-- Indexes for table `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`MaDanhMuc`),
  ADD UNIQUE KEY `TenDanhMuc` (`TenDanhMuc`);

--
-- Indexes for table `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`MaDonHang`),
  ADD KEY `FK_DonHang_NguoiDung` (`MaNguoiDung`);

--
-- Indexes for table `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`MaNguoiDung`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`MaSanPham`),
  ADD KEY `FK_SanPham_DanhMuc` (`MaDanhMuc`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `MaChiTiet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `MaDanhMuc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donhang`
--
ALTER TABLE `donhang`
  MODIFY `MaDonHang` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nguoidung`
--
ALTER TABLE `nguoidung`
  MODIFY `MaNguoiDung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `MaSanPham` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `FK_ChiTiet_DonHang` FOREIGN KEY (`MaDonHang`) REFERENCES `donhang` (`MaDonHang`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_ChiTiet_SanPham` FOREIGN KEY (`MaSanPham`) REFERENCES `sanpham` (`MaSanPham`) ON UPDATE CASCADE;

--
-- Constraints for table `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `FK_DonHang_NguoiDung` FOREIGN KEY (`MaNguoiDung`) REFERENCES `nguoidung` (`MaNguoiDung`) ON UPDATE CASCADE;

--
-- Constraints for table `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `FK_SanPham_DanhMuc` FOREIGN KEY (`MaDanhMuc`) REFERENCES `danhmuc` (`MaDanhMuc`) ON UPDATE CASCADE;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
