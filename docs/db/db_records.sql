INSERT INTO `coffee` (`id`, `name`, `intensity`, `price`, `stock`) VALUES
	(1, 'Cappuccino', 10, 5, 100),
	(2, 'Latte', 9, 6, 90),
	(3, 'Espresso', 8, 7, 80),
	(4, 'American', 7, 8, 70),
	(5, 'Macchiato', 6, 9, 60);

INSERT INTO `user` (`id`, `username`, `roles`, `password`) VALUES
	(1, 'admin', '["ROLE_ADMIN"]', '$argon2id$v=19$m=65536,t=4,p=1$29GWJ9sVPnx/jq2pdBlBPA$+mmjHOXLUMGa8SS3JxyCqQ/aRBGsE4bIFsQSPLH3Kpo'),
	(2, 'customer_1', '["ROLE_USER"]', '$argon2id$v=19$m=65536,t=4,p=1$pd7aeXYFv9tke0p21TSmyA$5iJ5sm5eLM4edHNawI3elgOyoLYkz10zgnJxsO0/+h4'),
	(3, 'customer_2', '["ROLE_USER"]', '$argon2id$v=19$m=65536,t=4,p=1$WuXm5XTc/dq/fJ2J0gtSIw$rGnjY846JL/WbLLBnV4UE1JA4u9ofQ1QdIAY5OGHKH8'),
	(4, 'customer_3', '["ROLE_USER"]', '$argon2id$v=19$m=65536,t=4,p=1$ZzzjiQftXLcp9iAD/eYBLA$X3gzf4evKPO7NGTI3moOTSNBhLkg5yzFJmWg0nynGOI'),
	(5, 'customer_4', '["ROLE_USER"]', '$argon2id$v=19$m=65536,t=4,p=1$vwQ8ZpBaxQr1XDw+ALqgAw$E0VpyP50B2vdynZUFRzEBoI30KivwowY/w4NKe4Momg');
	
INSERT INTO `shop_order` (`id`, `user_id`, `coffee_id`, `amount`, `quantity`) VALUES
	(1, 1, 1, 10, 2),
	(2, 2, 1, 20, 4),
	(3, 3, 2, 60, 10),
	(4, 4, 2, 30, 5),
	(5, 4, 3, 15, 2);

