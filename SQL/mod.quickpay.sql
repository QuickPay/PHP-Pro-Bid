INSERT INTO `ppb_payment_gateways` (`name`, `logo_path`, `type`, `order_id`, `site_fees`, `direct_payment`)
VALUES ('QuickPay', '/mods/img/logos/quickpay.png', 1, '', '', '');

ALTER TABLE `ppb_transactions`
ADD `quickpay_payment_id` varchar(255) COLLATE 'utf8_general_ci' NULL;
ALTER TABLE `ppb_transactions`
    AUTO_INCREMENT=100001;