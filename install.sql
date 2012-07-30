CREATE TABLE pin_association (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    pinset INTEGER REFERENCES pinsets(pinsets_id),
    pin VARCHAR(10),
    name VARCHAR(100)
);
