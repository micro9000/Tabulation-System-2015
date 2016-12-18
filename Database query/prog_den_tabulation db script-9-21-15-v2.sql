CREATE DATABASE IF NOT EXISTS prog_den_tabulation;

USE prog_den_tabulation;

CREATE TABLE IF NOT EXISTS admin (
	id int NOT NULL AUTO_INCREMENT,
	username VARCHAR(50),
	password VARCHAR(255),
	fname VARCHAR(50),
	lname VARCHAR(50),
	designation VARCHAR(10),
	PRIMARY KEY(id)
)ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS event (
	id INT NOT NULL AUTO_INCREMENT,
	title VARCHAR(100),
	description VARCHAR(100),
	date DATE,
	time TIME,
	venue VARCHAR(100),
	PRIMARY KEY(id),
	user_id INT NOT NULL,
	INDEX(user_id),
	FOREIGN KEY(user_id) REFERENCES admin(id) ON UPDATE CASCADE
)ENGINE = INNODB;



CREATE TABLE IF NOT EXISTS official(
	id INT NOT NULL AUTO_INCREMENT,
	username VARCHAR(50),
	password VARCHAR(255),
	alias VARCHAR(100),
	fname VARCHAR(50),
	lname VARCHAR(50),
	gender VARCHAR(10),
	designation VARCHAR(10),
	PRIMARY KEY(id),
	admin_creator_id INT NOT NULL,
	event_id INT NOT NULL,
	INDEX(admin_creator_id),
	INDEX(event_id),
	FOREIGN KEY(admin_creator_id) REFERENCES admin(id) ON UPDATE CASCADE,
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE
)ENGINE = INNODB;


CREATE TABLE IF NOT EXISTS round(
	id INT NOT NULL AUTO_INCREMENT,
	round_no INT NOT NULL,
	round_name VARCHAR(100),
	event_id INT NOT NULL,
	scoring_type VARCHAR(50),
	PRIMARY KEY(id),
	INDEX(event_id),
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE
)ENGINE = INNODB;



CREATE TABLE IF NOT EXISTS candidate_info(
	id INT NOT NULL AUTO_INCREMENT,
	fname VARCHAR(50),
	lname VARCHAR(50),
	mname VARCHAR(50),
	age INT,
	gender VARCHAR(10),
	height VARCHAR(10),
	vitals VARCHAR(20),
	address VARCHAR(60),
	event_id INT NOT NULL,
	
	INDEX(event_id),
	PRIMARY KEY(id),
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE
)ENGINE = INNODB;


CREATE TABLE IF NOT EXISTS candidate(
	candidate_id INT NOT NULL,
	candidate_no INT NOT NULL,
	event_id INT NOT NULL,
	
	INDEX(candidate_id),
	INDEX(event_id),
	
	FOREIGN KEY(candidate_id) REFERENCES candidate_info(id) ON UPDATE CASCADE,
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE
	
)ENGINE = INNODB;



CREATE TABLE IF NOT EXISTS round_score(
	round_id INT NOT NULL,
	event_id INT NOT NULL,
	candidate_id INT NOT NULL,
	score INT,
	INDEX(round_id),
	INDEX(event_id),
	FOREIGN KEY(round_id) REFERENCES round(id) ON UPDATE CASCADE,
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE,
	FOREIGN KEY(candidate_id) REFERENCES candidate_info(id) ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS overall_score(
	candidate_id INT NOT NULL,
	event_id INT NOT NULL,
	round_id INT NOT NULL,
	score INT NOT NULL,
	gender VARCHAR(10),
	INDEX(candidate_id),
	INDEX(event_id),
	INDEX(round_id),
	FOREIGN KEY(candidate_id) REFERENCES candidate_info(id) ON UPDATE CASCADE,
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE,
	FOREIGN KEY(round_id) REFERENCES round(id) ON UPDATE CASCADE
)ENGINE = INNODB;

CREATE TABLE IF NOT EXISTS category(
	id INT NOT NULL AUTO_INCREMENT,
	event_id INT NOT NULL,
	round_id INT NOT NULL,
	category_name VARCHAR(50),
	max_score INT NOT NULL,
	INDEX(event_id),
	INDEX(round_id),
	PRIMARY KEY(id),
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE,
	FOREIGN KEY(round_id) REFERENCES round(id) ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS category_score(
	category_id INT NOT NULL,
	candidate_id INT NOT NULL,
	event_id INT NOT NULL,
	round_id INT NOT NULL,
	scorer_id INT NOT NULL,
	score INT NOT NULL,
	INDEX(category_id),
	INDEX(candidate_id),
	INDEX(event_id),
	INDEX(scorer_id),
	INDEX(round_id),
	FOREIGN KEY(category_id) REFERENCES category(id) ON UPDATE CASCADE,
	FOREIGN KEY(candidate_id) REFERENCES candidate_info(id) ON UPDATE CASCADE,
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE,
	FOREIGN KEY(round_id) REFERENCES round(id) ON UPDATE CASCADE
)ENGINE=INNODB;



CREATE TABLE IF NOT EXISTS images(
       id INT NOT NULL AUTO_INCREMENT,
       img_path VARCHAR(255),
       img_name VARCHAR(255),
       event_id INT NOT NULL,
       candidate_id INT NOT NULL,
       PRIMARY KEY(id),
       INDEX(event_id),
       INDEX(candidate_id),
       FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE,
       FOREIGN KEY(candidate_id) REFERENCES candidate_info(id) ON UPDATE CASCADE
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS round_qualifiers(
	id INT NOT NULL AUTO_INCREMENT,
	round_id INT NOT NULL,
	candidate_id INT NOT NULL,
	event_id INT NOT NULL,
	PRIMARY KEY(id),
	INDEX(round_id),
	INDEX(candidate_id),
	INDEX(event_id),
	FOREIGN KEY(round_id) REFERENCES round(id) ON UPDATE CASCADE,
	FOREIGN KEY(candidate_id) REFERENCES candidate_info(id) ON UPDATE CASCADE,
	FOREIGN KEY(event_id) REFERENCES event(id) ON UPDATE CASCADE
)ENGINE=INNODB;
