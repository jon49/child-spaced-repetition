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
  deck_name VARCHAR(100) NOT NULL,
  hint VARCHAR(100),
  PRIMARY KEY (deck_id)
);

CREATE TABLE student_deck (
  deck_id INT UNSIGNED,
  student_id INT UNSIGNED,
  active TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (deck_id, student_id),
  FOREIGN KEY (deck_id) REFERENCES deck(deck_id),
  FOREIGN KEY (student_id) REFERENCES student(student_id)
);

CREATE TABLE setting (
  setting_id INT UNSIGNED AUTO_INCREMENT,
  student_id INT UNSIGNED NOT NULL,
  queue_limit INT NOT NULL DEFAULT 50,
  PRIMARY KEY (setting_id),
  FOREIGN KEY (student_id) REFERENCES student(student_id)
);

CREATE TABLE card (
  card_id INT UNSIGNED AUTO_INCREMENT,
  deck_id INT UNSIGNED NOT NULL,
  content VARCHAR(100),
  PRIMARY KEY (card_id),
  FOREIGN KEY (deck_id) REFERENCES deck(deck_id)
);

CREATE TABLE student_card (
  student_id INT UNSIGNED NOT NULL,
  card_id INT UNSIGNED NOT NULL,
  due DATETIME,
  `interval` INT UNSIGNED,
  repetition INT UNSIGNED,
  easiness_factor DOUBLE,
  PRIMARY KEY (student_id, card_id),
  FOREIGN KEY (student_id) REFERENCES student(student_id),
  FOREIGN KEY (card_id) REFERENCES card(card_id)
);

INSERT INTO
  user (email, `password`)
  VALUES
    ('jon@jon.com', PASSWORD('abcdef'));

INSERT INTO
  student (user_id, student_name)
  VALUES (1, 'Sally'), (1, 'Billy');
