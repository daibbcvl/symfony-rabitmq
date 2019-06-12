# Password: admin123
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `enabled`, `first_name`, `last_name`)
VALUES
	(1,'admin@example.com','["ROLE_ADMIN"]','$2y$13$8OgMP1Afbls5rUYCyMl2nuqIDCHuc/4qapiwSdTuwBoRg1skcB5Oy',1,'Super','Admin');