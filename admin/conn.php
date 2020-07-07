<?php

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

$createdb = "CREATE DATABASE IF NOT EXISTS RealEstate";
mysqli_query($conn,$createdb);
$select_db ="USE RealEstate";
mysqli_query($conn,$select_db);




$create_user_table = "CREATE TABLE IF NOT EXISTS  User (
	UserID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	FirstName VARCHAR(500) NOT NULL,
    SecondName VARCHAR(500) NOT NULL,
    PhoneNumber VARCHAR(500) NOT NULL,
	isAdmin BOOLEAN NOT NULL,
	Email varchar(255) NOT NULL UNIQUE,
    Pass VARCHAR(500) NOT NULL,
    JoinedDate DATETIME

);";


mysqli_query($conn,$create_user_table);




$create_house_table="CREATE TABLE IF NOT EXISTS House( 

    HouseID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    BedRooms INT NOT NULL,
    Area INT NOT NULL,
    Rent INT NOT NULL,
    HouseStatus VARCHAR(500),
    CreatedDate DATETIME

);";




mysqli_query($conn,$create_house_table);



$create_tenant_table = "CREATE TABLE IF NOT EXISTS Tenant (
        TenantID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        HouseID INT,
        FirstName VARCHAR(500) NOT NULL,
        SecondName VARCHAR(500) NOT NULL,
        PhoneNumber VARCHAR(500) NOT NULL,
        Email varchar(255) NOT NULL,


        FOREIGN KEY (HouseID) REFERENCES House(HouseID)
);";

mysqli_query($conn,$create_tenant_table);


$create_rent_table = "CREATE TABLE IF NOT EXISTS Rent (
    RentID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    TenantID INT,
    HouseID INT,
    Amount INT,
    Month DATE NOT NULL,
    CreatedDate DATETIME NOT NULL,
    FOREIGN KEY (TenantID) REFERENCES Tenant(TenantID),
    FOREIGN KEY (HouseID) REFERENCES House(HouseID)
    
);";

mysqli_query($conn,$create_rent_table);



?>