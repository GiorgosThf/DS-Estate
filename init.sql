CREATE DATABASE IF NOT EXISTS booking_app;
USE booking_app;

CREATE TABLE IF NOT EXISTS users
(
    id         INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50)  NOT NULL CHECK (first_name REGEXP '^[a-zA-Z]+$'),
    last_name  VARCHAR(50)  NOT NULL CHECK (last_name REGEXP '^[a-zA-Z]+$'),
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(10)  NOT NULL CHECK (LENGTH(password) BETWEEN 4 AND 10 AND password REGEXP '.*[0-9].*'),
    email      VARCHAR(100) NOT NULL UNIQUE CHECK (email LIKE '%@%')
);

CREATE TABLE IF NOT EXISTS listings
(
    id              INT AUTO_INCREMENT PRIMARY KEY,
    photo_url       VARCHAR(255),
    title           VARCHAR(100) NOT NULL CHECK (title REGEXP '^[a-zA-Z ]+$'),
    area            VARCHAR(100) NOT NULL CHECK (area REGEXP '^[a-zA-Z ]+$'),
    number_of_rooms INT          NOT NULL CHECK (number_of_rooms > 0),
    price_per_night INT          NOT NULL CHECK (price_per_night > 0),
    owner_id        INT,
    FOREIGN KEY (owner_id) REFERENCES users (id)
);

CREATE TABLE IF NOT EXISTS reservations
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    listing_id  INT,
    user_id     INT,
    start_date  DATE  NOT NULL,
    end_date    DATE  NOT NULL,
    total_price FLOAT NOT NULL,
    FOREIGN KEY (listing_id) REFERENCES listings (id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    CHECK (start_date < end_date)
);

DELIMITER //

CREATE PROCEDURE IF NOT EXISTS CheckAvailability(
    IN listing INT,
    IN start_date DATE,
    IN end_date DATE,
    OUT is_available BOOLEAN
)
BEGIN


    DECLARE conflict_count INT;

    SELECT COUNT(*)
    INTO conflict_count
    FROM reservations
    WHERE listing_id = listing
      AND (start_date BETWEEN reservations.start_date AND reservations.end_date
        OR end_date BETWEEN reservations.start_date AND reservations.end_date
        OR reservations.start_date BETWEEN start_date AND end_date
        OR reservations.end_date BETWEEN start_date AND end_date);

    SET is_available = (conflict_count = 0) AND (start_date <= end_date);

END //

DELIMITER ;
