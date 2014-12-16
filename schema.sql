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
  user_id INT UNSIGNED NOT NULL,
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
  due INT UNSIGNED NOT NULL DEFAULT 0,
  `interval` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  repetition INT NOT NULL DEFAULT 0,
  easiness_factor REAL NOT NULL DEFAULT 2.5,
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

INSERT INTO
  setting (student_id)
  VALUES (1), (2);

INSERT INTO
  deck (user_id, deck_name, hint)
  VALUES
    (1, 'ABCs', 'Say the name of the letter.'),
    (1, 'ABC Sounds', 'Say the sound of the letter.');

INSERT INTO
  student_deck (student_id, deck_id)
  VALUES (1, 1), (1, 2), (2, 1);

INSERT INTO
  card (deck_id, content)
  VALUES
    (1, 'a'), (1, 'b'), (1, 'c'), (1, 'd'), (1, 'e'), (1, 'f'),
    (1, 'g'), (1, 'h'), (1, 'i'), (1, 'j'), (1, 'k'), (1, 'l'),
    (1, 'm'), (1, 'n'), (1, 'o'), (1, 'p'), (1, 'q'), (1, 'r'),
    (1, 's'), (1, 't'), (1, 'u'), (1, 'v'), (1, 'w'), (1, 'x'),
    (1, 'y'), (1, 'z'),
    (1, 'A'), (1, 'B'), (1, 'C'), (1, 'D'), (1, 'E'), (1, 'F'),
    (1, 'G'), (1, 'H'), (1, 'I'), (1, 'J'), (1, 'K'), (1, 'L'),
    (1, 'M'), (1, 'N'), (1, 'O'), (1, 'P'), (1, 'Q'), (1, 'R'),
    (1, 'S'), (1, 'T'), (1, 'U'), (1, 'V'), (1, 'W'), (1, 'X'),
    (1, 'Y'), (1, 'Z'),
    (2, 'Aa'), (2, 'Bb'), (2, 'Cc'), (2, 'Dd'), (2, 'Ee'), (2, 'Ff'),
    (2, 'Gg'), (2, 'Hh'), (2, 'Ii'), (2, 'Jj'), (2, 'Kk'), (2, 'Ll'),
    (2, 'Mm'), (2, 'Nn'), (2, 'Oo'), (2, 'Pp'), (2, 'Qq'), (2, 'Rr'),
    (2, 'Ss'), (2, 'Tt'), (2, 'Uu'), (2, 'Vv'), (2, 'Ww'), (2, 'Xx'),
    (2, 'Yy'), (2, 'Zz');

INSERT INTO
  student_card (student_id, card_id)
  VALUES
    (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8),
    (1, 9), (1, 10), (1, 11), (1, 12), (1, 13), (1, 14), (1, 15), (1, 16),
    (1, 17), (1, 18), (1, 19), (1, 20), (1, 21), (1, 22), (1, 23), (1, 24),
    (1, 25), (1, 26),
    (1, 27), (1, 28), (1, 29), (1, 30), (1, 31), (1, 32), (1, 33), (1, 34),
    (1, 35), (1, 36), (1, 37), (1, 38), (1, 39), (1, 40), (1, 41), (1, 42),
    (1, 43), (1, 44), (1, 45), (1, 46), (1, 47), (1, 48), (1, 49), (1, 50),
    (1, 51), (1, 52),
    (1, 53), (1, 54), (1, 55), (1, 56), (1, 57), (1, 58), (1, 59), (1, 60),
    (1, 61), (1, 62), (1, 63), (1, 64), (1, 65), (1, 66), (1, 67), (1, 68),
    (1, 69), (1, 70), (1, 71), (1, 72), (1, 73), (1, 74), (1, 75), (1, 76),
    (1, 77), (1, 78),
    (2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6), (2, 7), (2, 8),
    (2, 9), (2, 10), (2, 11), (2, 12), (2, 13), (2, 14), (2, 15), (2, 16),
    (2, 17), (2, 18), (2, 19), (2, 20), (2, 21), (2, 22), (2, 23), (2, 24),
    (2, 25), (2, 26);
