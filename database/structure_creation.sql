CREATE DATABASE IF NOT EXISTS voting_portal;
USE voting_portal;

-- Table: districts
CREATE TABLE districts (
    constituency_code INT PRIMARY KEY,
    constituency_name VARCHAR(100) NOT NULL,
    state VARCHAR(50) NOT NULL
);

-- Table: candidates
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age TINYINT NOT NULL DEFAULT 42,
    constituency_code INT NOT NULL,
    party VARCHAR(100) NOT NULL,
    photo VARCHAR(255) DEFAULT '../images/parties/default-image.png',
    description VARCHAR(255),
    FOREIGN KEY (constituency_code) REFERENCES districts(constituency_code)
);

-- Table: voters
CREATE TABLE voters (
    voter_id VARCHAR(12) PRIMARY KEY,
    district_code INT NOT NULL,
    username VARCHAR(50) UNIQUE,
    constituency_code INT,
    age INT,
    FOREIGN KEY (district_code) REFERENCES districts(constituency_code)
);

-- Table: user_data
CREATE TABLE user_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    voter_id VARCHAR(12),
    age INT,
    FOREIGN KEY (voter_id) REFERENCES voters(voter_id)
);

-- Table: votes
CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voter_id VARCHAR(12) NOT NULL,
    candidate_id INT NOT NULL,
    vote_cast BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    vote_given_to VARCHAR(255),
    FOREIGN KEY (voter_id) REFERENCES voters(voter_id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id)
);
