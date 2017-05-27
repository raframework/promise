

CREATE TABLE `queue` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `app_key` varchar(64) NOT NULL,
  `payload` TEXT NOT NULL,
  `updated_at` INTEGER unsigned NOT NULL,
  `created_at` INTEGER unsigned NOT NULL
);
