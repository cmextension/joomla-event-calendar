CREATE TABLE `#__eventcalendar_events` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `all_day` tinyint UNSIGNED NULL DEFAULT '0',
  `background_color` varchar(255) NULL DEFAULT '',
  `text_color` varchar(255) NULL DEFAULT '',
  `class_names` text NULL,
  `styles` text NULL,
  `language` char(7) NOT NULL,
  `state` tinyint NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `created_by` int UNSIGNED NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL,
  `modified_by` int UNSIGNED NOT NULL DEFAULT '0',
  `checked_out` int UNSIGNED NULL DEFAULT '0',
  `checked_out_time` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__eventcalendar_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_language` (`language`);

ALTER TABLE `#__eventcalendar_events`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;