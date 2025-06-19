CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `password_hash` varchar(256) NOT NULL,
  `login` varchar(64) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP(),
  `user_last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
   PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `threads`(
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(127) NOT NULL,
  `content` varchar(2024) NOT NULL,
  `create_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `edit_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `power` int(4) NOT NULL DEFAULT 1,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `connection_user_thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `thread_id` int(11) NOT NULL,
  `view_power` int(4) NOT NULL DEFAULT 5,
  `is_owner` tinyint(1) NOT NULL DEFAULT 0,
  `edit_permission` tinyint(1) NOT NULL DEFAULT 0,
  `delete_permission` tinyint(1) NOT NULL DEFAULT 0,
  `create_power` int(4) NOT NULL DEFAULT 0,
  `complete_permission` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;