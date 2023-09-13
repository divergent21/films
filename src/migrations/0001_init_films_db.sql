-- CREATE TABLE Films
CREATE TABLE IF NOT EXISTS Films (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    since DATE NOT NULL,
    format VARCHAR(255) NOT NULL,
    CONSTRAINT UC_Film UNIQUE (title, since, format)
);

-- CREATE TABLE Actors
CREATE TABLE IF NOT EXISTS Actors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    CONSTRAINT UC_Actor UNIQUE (first_name, last_name)
);

-- CREATE TABLE Films_Actors_relative
CREATE TABLE IF NOT EXISTS Films_Actors_relative (
    id INT AUTO_INCREMENT PRIMARY KEY,
    film_id INT NOT NULL,
    actor_id INT NOT NULL,
    FOREIGN KEY (film_id) REFERENCES Films(id),
    FOREIGN KEY (actor_id) REFERENCES Actors(id)
);

-- CREATE TABLE Auth
CREATE TABLE IF NOT EXISTS Auth (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    session_id TEXT NOT NULL
);