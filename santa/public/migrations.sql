CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token CHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL
);

CREATE TABLE santa_pairs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    giver_id INT NOT NULL,
    receiver_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_restrictions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    restricted_user_id INT NOT NULL
);
