CREATE TABLE `#__eventcalendar_resources` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `event_background_color` varchar(255) NULL DEFAULT '',
  `event_text_color` varchar(255) NULL DEFAULT '',
  `language` char(7) NOT NULL,
  `state` tinyint NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `created_by` int UNSIGNED NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL,
  `modified_by` int UNSIGNED NOT NULL DEFAULT '0',
  `checked_out` int UNSIGNED NULL DEFAULT '0',
  `checked_out_time` datetime NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__eventcalendar_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_language` (`language`);

ALTER TABLE `#__eventcalendar_resources`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

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
  `link_type` enum('menu_item','url','') NOT NULL DEFAULT '',
  `menu_item_id` int UNSIGNED NULL DEFAULT '0',
  `url` varchar(2048) NULL DEFAULT '',
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
  ADD KEY `idx_allday` (`all_day`),
  ADD KEY `idx_starttime` (`start_time`),
  ADD KEY `idx_endtime` (`end_time`),
  ADD KEY `idx_state` (`state`),
  ADD KEY `idx_language` (`language`);

ALTER TABLE `#__eventcalendar_events`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `#__eventcalendar_event_resource` (
  `id` int UNSIGNED NOT NULL,
  `event_id` int UNSIGNED NOT NULL,
  `resource_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `#__eventcalendar_event_resource`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_event_id_resource_id` (`event_id`, `resource_id`);

ALTER TABLE `#__eventcalendar_event_resource`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;