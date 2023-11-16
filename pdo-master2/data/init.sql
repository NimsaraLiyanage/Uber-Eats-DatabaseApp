CREATE DATABASE test;

USE test;

CREATE TABLE userOrder (
    
    restaurantName VARCHAR(30) NOT NULL,
    customerId VARCHAR(30) NOT NULL,
    orderItem VARCHAR(50) NOT NULL,
    price INT(10) NOT NULl,
    location VARCHAR(50)
);

ALTER TABLE userOrder
ADD COLUMN orderId INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ADD COLUMN date TIMESTAMP;

CREATE TABLE Courier (
    courier_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    courier_name VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    restaurant_name VARCHAR(100),
    CreatedDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE OrderCourier (
    OrderCourierId INT(11) PRIMARY KEY AUTO_INCREMENT ,
    orderId  INT(11) UNSIGNED,
    FOREIGN KEY (orderId) REFERENCES userOrder(orderId),
    courierId INT(11) UNSIGNED ,
    FOREIGN KEY (courierId) REFERENCES Courier(courier_id)
);