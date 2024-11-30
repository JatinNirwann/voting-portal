CREATE DATABASE voting_portal;

USE voting_portal;

-- Table to store constituency information
CREATE TABLE constituency (
    voter_id VARCHAR(255) PRIMARY KEY,
    constituency_code VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL
);

-- Table to store user registration information
CREATE TABLE user_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    voter_id VARCHAR(255) NOT NULL UNIQUE
);

-- Table to store voter information
CREATE TABLE voters (
    voter_id VARCHAR(255) PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    constituency_code VARCHAR(255) NOT NULL
);

-- Table to store candidate information
CREATE TABLE candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    party VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    description TEXT,
    constituency_code VARCHAR(255) NOT NULL
);

-- Table to store voting information
CREATE TABLE votes (
    voter_id VARCHAR(255) PRIMARY KEY,
    candidate_id VARCHAR(255) NOT NULL,
    vote_cast TINYINT DEFAULT 0
);
