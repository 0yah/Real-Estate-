<?php

include('../server.php');
include('./conn.php');

if(isset($_POST['addHouse'])){

    $courtName = $_POST['courtName'];
    $houseNumber =$_POST['houseNumber'];
    $houseBedroom =$_POST['houseBedroom'];
    $houseRent =$_POST['houseRent'];
    $houseArea =$_POST['houseArea'];
    $houseStatus = "Available";
    $addedDate = date("Y-m-d H:i:s");

    $sql_add_house = "INSERT INTO `house` (`HouseID`, `HouseNo`, `Court`, `BedRooms As 'Bed Rooms'`, `Area`,`Rent`, `HouseStatus`, `CreatedDate`) 
    VALUES (NULL, '$houseNumber', '$courtName', '$houseBedroom', '$houseArea','$houseRent', '$houseStatus', '$addedDate' )";

    mysqli_query($database_connection, $sql_add_house);




}

if(isset($_GET['loadHouses'])){

    $Status = $_GET['Status'];

    //$Status = 1;

    if($Status == 1){
        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE HouseStatus = 'Available'";
    }else if($Status == 2){
        
        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms' ,Area,Rent FROM `house` WHERE HouseStatus = 'Occupied'";
    }else if($Status == 3){
        
        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE HouseStatus = 'Maintainance'";
    }else if($Status == 4){
        
        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE HouseStatus = 'Out of Service'";
    }
    else{
        $sql_get_house = "SELECT  HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent,HouseStatus As 'Status' FROM `house`";
    }


    $result = $database_connection->query($sql_get_house);

    //Initialize array variable
    $dbdata = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $dbdata[] = $row;
    }

    //Print array in JSON format
    echo json_encode($dbdata);

}

if(isset($_GET['findHouse'])){

    $rooms = $_GET['rooms'];
    $area = $_GET['area'];
    //echo $rooms;
    //echo $area;

    if($area == 0){
        $sql_find_house = "SELECT HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE BedRooms  = $rooms AND  HouseStatus = 'Available'";
    }else{
        $sql_find_house = "SELECT HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE Area <= $area AND BedRooms = $rooms AND  HouseStatus = 'Available'";
    }


    $result = $database_connection->query($sql_find_house);

    //Initialize array variable
    $dbdata = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $dbdata[] = $row;
    }

    //Print array in JSON format
    echo json_encode($dbdata);

}


if(isset($_POST['newTenant'])){

    $houseID = $_POST['houseID'];
    $firstname = $_POST['firstname'];
    $secondname =$_POST['secondname'];
    $phonenumber =$_POST['phonenumber'];
    $email =$_POST['email'];
    $password =$_POST['password'];
    $houseStatus = "Booked";
    $addedDate = date("Y-m-d H:i:s");

    $sql_book_house = "UPDATE `house` SET `HouseStatus` = '$houseStatus' WHERE `house`.`HouseID` = '$houseID'";
    $sql_new_user = "INSERT INTO `user` (`UserID`, `FirstName`, `SecondName`, `PhoneNumber`, `isAdmin`, `Email`, `Pass`, `JoinedDate`) VALUES 
    (NULL, '$firstname', '$secondname', '$phonenumber','false', '$email', '$password', '$addedDate')";
    mysqli_query($database_connection, $sql_new_user);
    $user_id = $database_connection->insert_id;
    $sql_new_tenant = "INSERT INTO `tenant` (`TenantID`, `UserID`, `HouseID`) VALUES (NULL, '$user_id', '$houseID')";
    mysqli_query($database_connection, $sql_new_tenant);




}
?>