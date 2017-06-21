

CREATE TABLE `task` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `version` INTEGER NOT NULL,
  `app_key` varchar(64) NOT NULL,
  `type` integer NOT NULL,
  `payload` TEXT NOT NULL,
  `status` INTEGER NOT NULL DEFAULT '0',
  `updated_at` INTEGER unsigned NOT NULL,
  `created_at` INTEGER unsigned NOT NULL
);
