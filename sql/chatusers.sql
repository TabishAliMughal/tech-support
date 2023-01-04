CREATE TABLE `chat_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(45) NOT NULL,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `role` varchar(45) NOT NULL,
  `contact` varchar(45) NOT NULL,
  `created_at` varchar(45) DEFAULT NULL,
  `updated_at` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
)