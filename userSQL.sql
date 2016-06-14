DROP TABLE IF EXISTS user_rated_films;

DROP TABLE IF EXISTS rating;

DROP TABLE IF EXISTS reg_user;
DROP TABLE IF EXISTS film;

CREATE TABLE film(
	film_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	film_name LONGTEXT,
	PRIMARY KEY(film_id)
);

CREATE TABLE rating(
	rating_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	rating_value INT UNSIGNED,
	rating_text VARCHAR(255),
	PRIMARY KEY(rating_id)
);

CREATE TABLE reg_user(
	user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
	user_name VARCHAR(255) UNIQUE,
	user_password CHAR(128),
	user_salt BINARY(64),

	user_favorite_film INT UNSIGNED,
	user_comment LONGTEXT,
	
	PRIMARY KEY(user_id),
	FOREIGN KEY(user_favorite_film) REFERENCES film(film_id)
);




CREATE TABLE user_rated_films(
	user_id INT UNSIGNED,
	film_id INT UNSIGNED,
	rating_id INT UNSIGNED,
	PRIMARY KEY (user_id, film_id),
	FOREIGN KEY (user_id) REFERENCES reg_user(user_id),
	FOREIGN KEY (film_id) REFERENCES film(film_id),
	FOREIGN KEY (rating_id) REFERENCES rating(rating_id)
);

INSERT INTO rating(rating_value, rating_text) VALUES(1 ,'Terrible');
INSERT INTO rating(rating_value, rating_text) VALUES(2 ,'Unenjoyable');
INSERT INTO rating(rating_value, rating_text) VALUES(3 ,'Mediocre');
INSERT INTO rating(rating_value, rating_text) VALUES(4 ,'Would Watch Again');
INSERT INTO rating(rating_value, rating_text) VALUES(5 ,'Best Film Ever');


INSERT INTO film(film_name) VALUES('Django Unchained');
INSERT INTO film(film_name) VALUES('Pulp Fiction');
INSERT INTO film(film_name) VALUES('The Hateful Eight');
INSERT INTO film(film_name) VALUES('Reservoir Dogs');
INSERT INTO film(film_name) VALUES('Inglorious Basterds');
INSERT INTO film(film_name) VALUES('Death Proof');


DELIMITER $$
DROP PROCEDURE IF EXISTS prc_user_create$$
CREATE PROCEDURE prc_user_create(IN inUname VARCHAR(255), IN inPasswd VARCHAR(1024), IN inFavFilm INT, IN inComment LONGTEXT, OUT oUserId INT, OUT oResult INT)
BEGIN
	DECLARE establishedId INT DEFAULT NULL;
	DECLARE inUserSalt BINARY(64) DEFAULT UUID();
	DECLARE passwd CHAR(128) DEFAULT SHA2(CONCAT(inUserSalt, inPasswd), 512);
	DECLARE CONTINUE HANDLER FOR 1062
		SET oResult := 1;
	INSERT INTO reg_user(user_name, user_password, user_salt, user_comment, user_favorite_film) VALUES(LOWER(inUname), passwd, inUserSalt, inComment, inFavFilm);
	
	SET oUserId := (SELECT user_id FROM reg_user WHERE user_name = LOWER(inUname));
	SET oResult := 0;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS prc_user_login$$
CREATE PROCEDURE prc_user_login(IN inUname LONGTEXT, IN inPasswd NVARCHAR(1024), OUT oResult INT, OUT oUserId INT)
BEGIN
	DECLARE selectedId INT DEFAULT NULL;
	DECLARE authorizedId INT DEFAULT NULL;
	DECLARE testPass CHAR(128) DEFAULT NULL;
	DECLARE unameSalt BINARY(64) DEFAULT NULL;
	SELECT user_id INTO selectedId FROM reg_user WHERE user_name = LOWER(INuNAME);
	IF(selectedId IS NOT NULL) THEN
		SET unameSalt := (SELECT user_salt FROM reg_user WHERE user_id = selectedId);
		SET testPass:= SHA2(CONCAT(unameSalt, inPasswd), 512);
		SELECT user_id INTO authorizedId FROM reg_user WHERE user_id = selectedId AND user_password = testPass;

		IF(authorizedId IS NOT NULL) THEN
			SET oResult := 0;
			SET oUserId := authorizedId;
		ELSE
			SET oResult := 1;
		END IF;
	ELSE
		SET oResult := 2;
	END IF;
END$$
DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS prc_add_user_rated_film$$
CREATE PROCEDURE prc_add_user_rated_film(IN inUserId INT, IN inFilmId INT, IN inRatingId INT, OUT oResult INT)
BEGIN
	DECLARE selectedUserId INT DEFAULT NULL;
	DECLARE selectedFilmId INT DEFAULT NULL;
	DECLARE selectedRatingId INT DEFAULT NULL;
	SELECT user_id INTO selectedUserId FROM reg_user WHERE user_id = inUserId;
	SELECT film_id INTO selectedFilmId FROM film WHERE film_id = inFilmId;
	SELECT rating_id INTO selectedRatingId FROM rating WHERE rating_id = inRatingId;
	IF((selectedUserId IS NOT NULL) AND (selectedFilmId IS NOT NULL)) THEN
		INSERT INTO user_rated_films(user_id, film_id, rating_id) VALUES (selectedUserId, selectedFilmId, selectedRatingId);
		SET oResult := 0;
	ELSE
		SET oResult := 1;
	END IF;
END$$
DELIMITER ;