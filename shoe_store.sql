-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Jul 2025 pada 16.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoe_store`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `activity`, `created_at`) VALUES
(1, 2, 'User registered', '2025-07-18 05:53:20'),
(2, 2, 'User login', '2025-07-18 05:53:27'),
(3, 2, 'Added product \'Birkenstock Arizona\' to cart (quantity: 1)', '2025-07-18 05:53:32'),
(4, 2, 'Added product \'Birkenstock Arizona\' to cart (quantity: 1)', '2025-07-18 06:18:04'),
(5, 2, 'Added product \'Adidas Ultraboost 22\' to cart (quantity: 1)', '2025-07-18 06:32:13'),
(6, 2, 'Added product \'Converse Chuck Taylor\' to cart (quantity: 1)', '2025-07-18 06:56:19'),
(7, 2, 'Added product \'Birkenstock Arizona\' to cart (quantity: 1)', '2025-07-18 07:01:29'),
(8, 2, 'Added product \'Nike Air Max 270\' to cart (quantity: 1)', '2025-07-18 07:20:14'),
(9, 2, 'Added product \'Adidas Ultraboost 22\' to cart (quantity: 1)', '2025-07-18 07:33:49'),
(10, 2, 'Added product \'Nike Air Max 270\' to cart (quantity: 1)', '2025-07-18 07:37:13'),
(11, 1, 'User login', '2025-07-18 15:15:34'),
(12, 1, 'User logout', '2025-07-18 15:17:28'),
(13, 1, 'User login', '2025-07-18 15:21:58'),
(14, 1, 'Mengubah produk: Nike Air Max 270', '2025-07-18 15:28:39'),
(15, 1, 'Membersihkan cache sistem', '2025-07-18 15:34:28'),
(16, 1, 'Memperbarui pengaturan sistem', '2025-07-18 15:34:42'),
(17, 1, 'User login', '2025-07-18 19:25:16'),
(18, 1, 'Mengubah produk: Nike Air Max 270', '2025-07-18 20:23:40'),
(19, 1, 'Mengubah produk: Nike Air Max 270', '2025-07-18 20:25:29'),
(20, 1, 'Updated category: Sandal Casual', '2025-07-18 20:42:09'),
(21, 1, 'Updated category: Sandal Casual', '2025-07-18 20:42:17'),
(22, 1, 'Added category: Sandal Gunung', '2025-07-18 20:42:33'),
(23, 1, 'Menambahkan pengguna: Nur Taliyah', '2025-07-18 20:43:17'),
(24, 1, 'Mengubah pengguna: Nur Taliyah', '2025-07-18 20:43:26'),
(25, 1, 'User logout', '2025-07-18 20:44:00'),
(26, 3, 'User login', '2025-07-18 20:44:11'),
(27, 3, 'Added product \'Birkenstock Arizona\' to cart (quantity: 1)', '2025-07-18 20:44:28'),
(28, 3, 'Added product \'Converse Chuck Taylor\' to cart (quantity: 1)', '2025-07-18 20:44:32'),
(29, 3, 'User logout', '2025-07-18 21:01:27'),
(30, 1, 'User login', '2025-07-18 21:01:39'),
(31, 1, 'Updated order #3 status to cancelled', '2025-07-18 21:09:43'),
(32, 1, 'Updated order #ORD-20250718103750-2 status to pending', '2025-07-18 21:16:32'),
(33, 1, 'Updated order #ORD-20250718103750-2 status to delivered', '2025-07-18 21:16:37'),
(34, 1, 'User logout', '2025-07-18 21:26:37'),
(35, 1, 'User logout', '2025-07-19 00:54:56'),
(36, 1, 'User login', '2025-07-19 00:55:12'),
(37, 1, 'User logout', '2025-07-19 01:05:09'),
(38, 1, 'User login', '2025-07-19 01:05:21'),
(39, 2, 'User logout', '2025-07-20 20:10:56'),
(40, 1, 'User login', '2025-07-20 20:12:01'),
(41, 1, 'User logout', '2025-07-20 20:13:10'),
(42, 3, 'User login', '2025-07-20 20:14:12'),
(43, 1, 'User login', '2025-07-25 18:32:18'),
(44, 1, 'Mengubah produk: Nike Air Max 270', '2025-07-25 18:32:41'),
(45, 1, 'User logout', '2025-07-25 18:53:11'),
(46, 1, 'User login', '2025-07-25 18:53:28'),
(47, 1, 'User logout', '2025-07-25 18:53:42'),
(48, 1, 'User login', '2025-07-25 18:53:51'),
(49, 1, 'User logout', '2025-07-25 18:54:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Sepatu Sneakers', NULL, '2025-07-18 05:48:00', '2025-07-18 05:48:00'),
(2, 'Sepatu Formal', NULL, '2025-07-18 05:48:00', '2025-07-18 05:48:00'),
(3, 'Sandal Casual', 'Yo iki sandal', '2025-07-18 05:48:00', '2025-07-18 20:42:17'),
(4, 'Sandal Sport', NULL, '2025-07-18 05:48:00', '2025-07-18 05:48:00'),
(5, 'Sepatu Olahraga', NULL, '2025-07-18 05:48:00', '2025-07-18 05:48:00'),
(6, 'Sandal Gunung', 'Gae Selokan', '2025-07-18 20:42:33', '2025-07-18 20:42:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `courier_note` text DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `status`, `address`, `city`, `state`, `zip`, `phone`, `courier_note`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 'ORD-20250718063337-2', 2, 2598000.00, 'pending', 'm', 'm', 'm', 'm', 'm', 'm', 'BRI', '2025-07-17 23:33:37', '2025-07-17 23:33:37'),
(2, 'ORD-20250718103750-2', 2, 4797000.00, 'delivered', 'k', 'k', 'k', 'k', 'j', '', 'BRI', '2025-07-18 03:37:50', '2025-07-18 13:16:37'),
(3, 'ORD-20250718194455-3', 3, 3297000.00, 'cancelled', 'h', 'h', 'h', '12', '0212', 'mati aja', 'BCA', '2025-07-18 12:44:55', '2025-07-18 13:09:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(1, 1, 1, 2, 1299000.00, '2025-07-17 23:33:37'),
(2, 2, 2, 1, 2199000.00, '2025-07-18 03:37:50'),
(3, 2, 1, 1, 1299000.00, '2025-07-18 03:37:50'),
(4, 2, 1, 1, 1299000.00, '2025-07-18 03:37:50'),
(5, 3, 5, 1, 1099000.00, '2025-07-18 12:44:55'),
(6, 3, 3, 1, 899000.00, '2025-07-18 12:44:55'),
(7, 3, 1, 1, 1299000.00, '2025-07-18 12:44:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `brand` varchar(255) DEFAULT '',
  `size` varchar(100) DEFAULT '',
  `material` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category_id`, `created_at`, `updated_at`, `brand`, `size`, `material`) VALUES
(1, 'Nike Air Max 270', 'Sepatu sneakers premium dengan teknologi Air Max terbaru', 1299000.00, 45, 1, '2025-07-18 05:48:00', '2025-07-25 18:32:41', 'NIKE', '40', 'Batu'),
(2, 'Adidas Ultraboost 22', 'Sepatu lari dengan teknologi Boost untuk kenyamanan maksimal', 2199000.00, 29, 5, '2025-07-18 05:48:00', '2025-07-18 11:37:50', '', '', ''),
(3, 'Converse Chuck Taylor', 'Sepatu klasik yang timeless dan stylish', 899000.00, 74, 1, '2025-07-18 05:48:00', '2025-07-18 20:44:55', '', '', ''),
(4, 'Clarks Desert Boot', 'Sepatu formal casual yang elegan', 1599000.00, 25, 2, '2025-07-18 05:48:00', '2025-07-18 05:48:00', '', '', ''),
(5, 'Birkenstock Arizona', 'Sandal premium dengan footbed anatomis', 1099000.00, 39, 3, '2025-07-18 05:48:00', '2025-07-18 20:44:55', '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `is_primary`) VALUES
(1, 1, 'assets/img/products/nike-air-max-1.jpg', 1),
(2, 1, 'assets/img/products/nike-air-max-2.jpg', 0),
(3, 2, 'assets/img/products/adidas-ultraboost-1.jpg', 1),
(4, 2, 'assets/img/products/adidas-ultraboost-2.jpg', 0),
(5, 3, 'assets/img/products/converse-chuck-1.jpg', 1),
(6, 4, 'assets/img/products/clarks-desert-1.jpg', 1),
(7, 5, 'assets/img/products/birkenstock-1.jpg', 1),
(8, 1, 'assets/img/products/1_1752823719_0.jpg', 0),
(9, 1, 'assets/img/products/1_1752841420_0.jpg', 0),
(10, 1, 'assets/img/products/1_1752841529_0.jpg', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Customer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'store_name', 'ShoeBrand Store'),
(2, 'store_logo', 'assets/img/logo.png'),
(3, 'store_contact', '+62 812-3456-7890'),
(4, 'store_email', 'info@shoebrand.com'),
(5, 'store_address', 'Jl. Fashion Street No. 123, Jakarta'),
(6, 'site_name', 'ShoeBrand Store'),
(7, 'site_description', 'Toko sepatu online terpercaya'),
(8, 'contact_email', 'info@shoestore.com'),
(9, 'contact_phone', '08123456789'),
(10, 'contact_address', 'Jl. Contoh No. 123, Jakarta'),
(11, 'facebook_url', ''),
(12, 'instagram_url', ''),
(13, 'twitter_url', ''),
(14, 'whatsapp_number', '628123456789'),
(15, 'maintenance_mode', '0'),
(16, 'allow_registration', '1'),
(17, 'min_order_amount', '50000'),
(18, 'shipping_cost', '10000'),
(19, 'free_shipping_min', '100000'),
(20, 'tax_rate', '0');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role_id` int(11) DEFAULT 2,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@shoebrand.com', 'admin123', NULL, NULL, 1, 1, '2025-07-18 05:48:00', '2025-07-25 18:54:31'),
(2, 'Muhammad Ammar AlFarabi', 'customer@example.com', 'customer123\n', NULL, NULL, 2, 1, '2025-07-18 05:53:20', '2025-07-25 18:58:45'),
(3, 'Nur Taliyah', 'sekian@gmail.com', 'password123', '0812', 'dimana saja', 2, 1, '2025-07-18 20:43:17', '2025-07-25 18:54:31');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ketidakleluasaan untuk tabel `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
