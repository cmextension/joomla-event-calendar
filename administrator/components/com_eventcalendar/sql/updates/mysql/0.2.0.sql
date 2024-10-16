ALTER TABLE `#__eventcalendar_events`
    ADD `link_type` enum('menu_item','url','') NOT NULL DEFAULT '' AFTER `styles`,
    ADD `menu_item_id` int UNSIGNED NULL DEFAULT '0' AFTER `link_type`,
    ADD `url` varchar(2048) NULL DEFAULT '' AFTER `menu_item_id`;