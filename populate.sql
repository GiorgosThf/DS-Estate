USE booking_app;

-- Insert users
INSERT INTO users (first_name, last_name, username, password, email)
VALUES ('John', 'Doe', 'johndoe', 'pass1234', 'john@example.com'),
       ('Jane', 'Smith', 'janesmith', 'pass5678', 'jane@example.com'),
       ('Alice', 'Johnson', 'alicejohnson', 'pass9101', 'alice@example.com');

-- Insert listings
INSERT INTO listings (photo_url, title, area, number_of_rooms, price_per_night, owner_id)
VALUES ('src/app/assets/thumbnails/1.jpg', 'Beautiful Cottage', 'Athens', 3, 100, 1),
       ('src/app/assets/thumbnails/2.jpg', 'Modern Apartment', 'Thessaloniki', 2, 80, 2),
       ('src/app/assets/thumbnails/3.jpg', 'Cozy Studio', 'Patras', 1, 50, 3),
       ('src/app/assets/thumbnails/4.jpg', 'Luxury Villa', 'Heraklion', 5, 200, 1),
       ('src/app/assets/thumbnails/5.jpg', 'Beach House', 'Chania', 4, 150, 2),
       ('src/app/assets/thumbnails/6.jpg', 'Mountain Cabin', 'Larisa', 3, 90, 3),
       ('src/app/assets/thumbnails/7.jpg', 'City Center Flat', 'Volos', 2, 75, 1),
       ('src/app/assets/thumbnails/8.jpg', 'Rustic Barn', 'Ioannina', 4, 110, 2),
       ('src/app/assets/thumbnails/9.jpg', 'Penthouse Suite', 'Kalamata', 3, 130, 3),
       ('src/app/assets/thumbnails/10.jpg', 'Country House', 'Sparta', 5, 140, 1),
       ('src/app/assets/thumbnails/11.jpg', 'Historic Home', 'Nafplio', 4, 120, 2),
       ('src/app/assets/thumbnails/12.jpg', 'Modern Loft', 'Kavala', 1, 60, 3);