CREATE DATABASE imagepulse;
CREATE USER 'imagepulse_admin'@'localhost' IDENTIFIED BY '123456';
GRANT ALL PRIVILEGES ON imagepulse.* TO 'imagepulse_admin'@'localhost';
FLUSH PRIVILEGES;