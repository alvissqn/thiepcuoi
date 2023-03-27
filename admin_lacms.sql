-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th3 20, 2023 lúc 08:25 AM
-- Phiên bản máy phục vụ: 10.6.10-MariaDB
-- Phiên bản PHP: 7.4.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `admin_lacms`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `logs`
--

CREATE TABLE `logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `category`, `action`, `action_color`, `action_value`, `old_value`, `new_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'permission', 'set_permission_for_role', 'warning', 'Admin', 'a:3:{i:0;a:1:{s:15:\"permission_name\";s:4:\"post\";}i:1;a:1:{s:15:\"permission_name\";s:12:\"user_manager\";}i:2;a:1:{s:15:\"permission_name\";s:12:\"file_manager\";}}', 'a:3:{i:0;s:4:\"post\";i:1;s:12:\"user_manager\";i:2;s:12:\"file_manager\";}', '2023-03-18 02:30:05', '2023-03-18 02:30:05'),
(2, 1, 'settings', 'update_setting', 'warning', 'settings__style_font', 'a:7:{s:20:\"settings__style_font\";N;s:25:\"settings__style_font_size\";N;s:29:\"settings__style_primary_color\";N;s:29:\"settings__style_primary_hover\";N;s:31:\"settings__style_secondary_color\";N;s:31:\"settings__style_secondary_hover\";N;s:26:\"settings__style_css_custom\";N;}', 'a:7:{s:20:\"settings__style_font\";N;s:25:\"settings__style_font_size\";N;s:29:\"settings__style_primary_color\";s:7:\"#5A8DEE\";s:29:\"settings__style_primary_hover\";s:7:\"#5A8DEE\";s:31:\"settings__style_secondary_color\";s:7:\"#475F7B\";s:31:\"settings__style_secondary_hover\";s:7:\"#506B8B\";s:26:\"settings__style_css_custom\";N;}', '2023-03-18 02:30:43', '2023-03-18 02:30:43'),
(3, 1, 'settings', 'update_setting', 'warning', 'settings__general_language', 'a:5:{s:26:\"settings__general_language\";N;s:34:\"settings__general_pagination_limit\";N;s:29:\"settings__general_time_format\";N;s:29:\"settings__general_date_format\";N;s:37:\"settings__general_search_engine_index\";N;}', 'a:5:{s:26:\"settings__general_language\";s:2:\"vi\";s:34:\"settings__general_pagination_limit\";N;s:29:\"settings__general_time_format\";s:11:\"H:i - d/m/Y\";s:29:\"settings__general_date_format\";s:5:\"d/m/Y\";s:37:\"settings__general_search_engine_index\";N;}', '2023-03-18 02:30:46', '2023-03-18 02:30:46'),
(4, 1, 'settings', 'update_setting', 'warning', 'settings__admin_theme', 'a:6:{s:21:\"settings__admin_theme\";N;s:38:\"settings__style_admin_background_color\";N;s:34:\"settings__style_admin_menu_padding\";N;s:36:\"settings__style_admin_menu_font_size\";N;s:40:\"settings__style_admin_actived_background\";N;s:35:\"settings__admin_quick_edit_language\";N;}', 'a:6:{s:21:\"settings__admin_theme\";s:5:\"light\";s:38:\"settings__style_admin_background_color\";s:7:\"#F2F4F4\";s:34:\"settings__style_admin_menu_padding\";s:3:\"0.7\";s:36:\"settings__style_admin_menu_font_size\";s:3:\"0.9\";s:40:\"settings__style_admin_actived_background\";s:7:\"#5A8DEE\";s:35:\"settings__admin_quick_edit_language\";N;}', '2023-03-18 02:31:07', '2023-03-18 02:31:07'),
(5, 1, 'settings', 'update_setting', 'warning', 'settings__admin_theme', 'a:6:{s:21:\"settings__admin_theme\";s:5:\"light\";s:38:\"settings__style_admin_background_color\";s:7:\"#F2F4F4\";s:34:\"settings__style_admin_menu_padding\";s:3:\"0.7\";s:36:\"settings__style_admin_menu_font_size\";s:3:\"0.9\";s:40:\"settings__style_admin_actived_background\";s:7:\"#5A8DEE\";s:35:\"settings__admin_quick_edit_language\";N;}', 'a:6:{s:21:\"settings__admin_theme\";s:4:\"dark\";s:38:\"settings__style_admin_background_color\";s:7:\"#F2F4F4\";s:34:\"settings__style_admin_menu_padding\";s:3:\"0.7\";s:36:\"settings__style_admin_menu_font_size\";s:3:\"0.9\";s:40:\"settings__style_admin_actived_background\";s:7:\"#5A8DEE\";s:35:\"settings__admin_quick_edit_language\";N;}', '2023-03-18 02:31:11', '2023-03-18 02:31:11'),
(6, 1, 'settings', 'update_setting', 'warning', 'settings__admin_theme', 'a:6:{s:21:\"settings__admin_theme\";s:4:\"dark\";s:38:\"settings__style_admin_background_color\";s:7:\"#F2F4F4\";s:34:\"settings__style_admin_menu_padding\";s:3:\"0.7\";s:36:\"settings__style_admin_menu_font_size\";s:3:\"0.9\";s:40:\"settings__style_admin_actived_background\";s:7:\"#5A8DEE\";s:35:\"settings__admin_quick_edit_language\";N;}', 'a:6:{s:21:\"settings__admin_theme\";s:4:\"dark\";s:38:\"settings__style_admin_background_color\";s:7:\"#F2F4F4\";s:34:\"settings__style_admin_menu_padding\";s:3:\"0.7\";s:36:\"settings__style_admin_menu_font_size\";s:3:\"0.9\";s:40:\"settings__style_admin_actived_background\";s:7:\"#5A8DEE\";s:35:\"settings__admin_quick_edit_language\";N;}', '2023-03-18 02:31:13', '2023-03-18 02:31:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `log_categories`
--

CREATE TABLE `log_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `log_categories`
--

INSERT INTO `log_categories` (`id`, `name`, `action`) VALUES
(1, 'permission', 'set_permission_for_role'),
(2, 'settings', 'update_setting');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_user_id` bigint(20) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `expired` timestamp NULL DEFAULT NULL,
  `send_mail` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification_readed`
--

CREATE TABLE `notification_readed` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `notification_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `options`
--

CREATE TABLE `options` (
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_array` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `options`
--

INSERT INTO `options` (`name`, `value`, `group_name`, `is_array`) VALUES
('settings__admin_quick_edit_language', NULL, NULL, 0),
('settings__admin_theme', 'dark', NULL, 0),
('settings__general_date_format', 'd/m/Y', NULL, 0),
('settings__general_language', 'vi', NULL, 0),
('settings__general_pagination_limit', NULL, NULL, 0),
('settings__general_search_engine_index', NULL, NULL, 0),
('settings__general_time_format', 'H:i - d/m/Y', NULL, 0),
('settings__style_admin_actived_background', '#5A8DEE', NULL, 0),
('settings__style_admin_background_color', '#F2F4F4', NULL, 0),
('settings__style_admin_menu_font_size', '0.9', NULL, 0),
('settings__style_admin_menu_padding', '0.7', NULL, 0),
('settings__style_css_custom', NULL, NULL, 0),
('settings__style_font', NULL, NULL, 0),
('settings__style_font_size', NULL, NULL, 0),
('settings__style_primary_color', '#5A8DEE', NULL, 0),
('settings__style_primary_hover', '#5A8DEE', NULL, 0),
('settings__style_secondary_color', '#475F7B', NULL, 0),
('settings__style_secondary_hover', '#506B8B', NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `default` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `color`, `default`) VALUES
(1, 'Admin', '#FF0000', 1),
(2, 'Member', '#000000', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_notifications`
--

CREATE TABLE `role_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `notification_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_name`) VALUES
(1, 1, 'post'),
(2, 1, 'user_manager'),
(3, 1, 'file_manager');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` tinyint(4) NOT NULL DEFAULT 0,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `password`, `gender`, `role_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@azwebsite.vn', '0995656565', '$2y$10$P0jNQZ4dWEy9QX3ad8Hh6.MUkAm4XiT7m0Uh8eg0zx7x85Ik3PVxa', 0, 1, 1, '2023-03-18 02:29:10', '2023-03-18 02:29:10');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_data`
--

CREATE TABLE `user_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_data`
--

INSERT INTO `user_data` (`id`, `user_id`, `key`, `value`) VALUES
(1, 1, 'last_online', '1679106889'),
(2, 1, 'pagination_limit', '5');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_notifications`
--

CREATE TABLE `user_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `notification_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `___migrations`
--

CREATE TABLE `___migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `___migrations`
--

INSERT INTO `___migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2020_04_10_090958_create_options_table', 1),
(3, '2020_04_12_183957_create_roles_table', 1),
(4, '2020_04_13_064352_create_role_permissions_table', 1),
(5, '2020_04_13_193610_create_logs_table', 1),
(6, '2020_04_14_104358_create_log_categories_table', 1),
(7, '2020_04_15_154103_create_user_data_table', 1),
(8, '2020_04_21_082247_create_notifications_table', 1),
(9, '2020_04_21_082307_create_user_notifications_table', 1),
(10, '2020_04_21_083548_create_role_notifications_table', 1),
(11, '2020_04_21_145929_create_notification_readed_table', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `log_categories`
--
ALTER TABLE `log_categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notification_readed`
--
ALTER TABLE `notification_readed`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `options`
--
ALTER TABLE `options`
  ADD UNIQUE KEY `options_name_unique` (`name`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `role_notifications`
--
ALTER TABLE `role_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `user_data`
--
ALTER TABLE `user_data`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `user_notifications`
--
ALTER TABLE `user_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `___migrations`
--
ALTER TABLE `___migrations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `log_categories`
--
ALTER TABLE `log_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `notification_readed`
--
ALTER TABLE `notification_readed`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `role_notifications`
--
ALTER TABLE `role_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `user_data`
--
ALTER TABLE `user_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `user_notifications`
--
ALTER TABLE `user_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `___migrations`
--
ALTER TABLE `___migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
