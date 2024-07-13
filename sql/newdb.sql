DROP DATABASE IF EXISTS user_management;
CREATE DATABASE IF NOT EXISTS user_management;
USE user_management;

CREATE TABLE users (
                       user_id INT PRIMARY KEY AUTO_INCREMENT,
                       GUID CHAR(36) NOT NULL UNIQUE DEFAULT (UUID()),
                       email VARCHAR(255) NOT NULL
);

CREATE TABLE accounts (
                          account_id INT PRIMARY KEY AUTO_INCREMENT,
                          user_GUID CHAR(36) NOT NULL UNIQUE,
                          pwd VARCHAR(255) NOT NULL,
                          salt VARCHAR(255) NOT NULL,
                          stretch INT NOT NULL,
                          created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (user_GUID) REFERENCES users(GUID)
);

CREATE TABLE tmp_accounts (
                              tmp_accs_id INT PRIMARY KEY AUTO_INCREMENT,
                              user_GUID CHAR(36) NOT NULL UNIQUE,
                              pwd VARCHAR(255) NOT NULL,
                              salt VARCHAR(255) NOT NULL,
                              stretch INT NOT NULL,
                              created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              FOREIGN KEY (user_GUID) REFERENCES users(GUID)
);

CREATE TABLE accounts_attempts (
                                   attempt_id INT PRIMARY KEY AUTO_INCREMENT,
                                   user_GUID CHAR(36) NOT NULL,
                                   attempt_timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                   FOREIGN KEY (user_GUID) REFERENCES users(GUID)
);

CREATE TABLE webservices (
                             webservice_id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                             webservice_name VARCHAR(24) NOT NULL UNIQUE
);

INSERT INTO webservices (webservice_id, webservice_name) VALUES
                                                             (1, 'signUp'),
                                                             (2, 'verifyAccount'),
                                                             (3, 'changePassword'),
                                                             (4, 'deleteAccount'),
                                                             (5, 'signIn'),
                                                             (6, 'signedIn'),
                                                             (7, 'signOut'),
                                                             (8, 'confirmPasswordChange'),
                                                             (9, 'confirmAccountDeletion');

CREATE TABLE accounts_otp (
                              otp_id INT PRIMARY KEY AUTO_INCREMENT,
                              user_GUID CHAR(36) NOT NULL UNIQUE,
                              OTP CHAR(8) NOT NULL,
                              created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              expires_at TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 1 DAY),
                              webservice_id INT NOT NULL,
                              FOREIGN KEY (user_GUID) REFERENCES users(GUID),
                              FOREIGN KEY (webservice_id) REFERENCES webservices(webservice_id)
);

CREATE TABLE secured_actions (
                                 action_id INT PRIMARY KEY AUTO_INCREMENT,
                                 user_GUID CHAR(36) NOT NULL,
                                 action VARCHAR(255) NOT NULL,
                                 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                 FOREIGN KEY (user_GUID) REFERENCES users(GUID)
);

-- TODO : create table for sessions with tokens