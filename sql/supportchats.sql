CREATE TABLE `support_chats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `reply_user_id` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
)