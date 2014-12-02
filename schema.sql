drop DATABASE spaced_learning_db;
CREATE DATABASE spaced_learning_db;
USE spaced_learning_db;

CREATE TABLE user (
  user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  `password` CHAR(41) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL,
  PRIMARY KEY (user_id)
);

CREATE TABLE student (
  student_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  student_name VARCHAR(100) NOT NULL,
  PRIMARY KEY (student_id),
  FOREIGN KEY (user_id) REFERENCES user(user_id)
);

CREATE TABLE deck (
  deck_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  student_id INT UNSIGNED NOT NULL,
  deck_name VARCHAR(100) NOT NULL,
  active TINYINT(1) DEFAULT 1,
  PRIMARY KEY (deck_id),
  FOREIGN KEY (student_id) REFERENCES student(student_id)
);

CREATE TABLE card (
  card_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  deck_id INT UNSIGNED NOT NULL,
  content INT UNSIGNED NOT NULL,
  due DATE NOT NULL,
  interval INT UNSIGNED NOT NULL DEFAULT 0,
  repetition INT UNSIGNED NOT NULL DEFAULT 0,
  easiness_factor DOUBLE NOT NULL DEFAULT 2.5,
  note VARCHAR(100),
  PRIMARY KEY (card_id),
  FOREIGN KEY (deck_id) REFERENCES deck(deck_id)
);

CREATE TABLE setting (
  setting_id INT UNSIGNED AUTO_INCREMENT,
  user_id INT UNSIGNED,
  queueLimit INT NOT NULL DEFAULT 50,
  PRIMARY KEY (setting_id),
  FOREIGN KEY (user_id) REFERENCES user(user_id)
);
