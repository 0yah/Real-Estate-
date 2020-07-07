<?php

include('../server.php');
include('./conn.php');

if (isset($_POST['addHouse'])) {

    $courtName = $_POST['courtName'];
    $houseNumber = $_POST['houseNumber'];
    $houseBedroom = $_POST['houseBedroom'];
    $houseRent = $_POST['houseRent'];
    $houseArea = $_POST['houseArea'];
    $houseStatus = "Available";
    $addedDate = date("Y-m-d H:i:s");


    $sql_add_house = "INSERT INTO `house` (`HouseID`, `HouseNo`, `Court`, `BedRooms`, `Area`, `Rent`, `HouseStatus`, `CreatedDate`) 
    VALUES (NULL, '$houseNumber', '$courtName', '$houseBedroom', '$houseArea', '$houseRent', '$houseStatus', '$addedDate')";
    mysqli_query($database_connection, $sql_add_house);
    
}

if (isset($_GET['loadHouses'])) {

    $Status = $_GET['Status'];

    //$Status = 1;

    if ($Status == 1) {
        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE HouseStatus = 'Available'";
    } else if ($Status == 2) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms' ,Area,Rent FROM `house` WHERE HouseStatus = 'Occupied'";
    } else if ($Status == 3) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE HouseStatus = 'Maintainance'";
    } else if ($Status == 4) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE HouseStatus = 'Out of Service'";
    } else if ($Status == 5) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE HouseStatus = 'Booked'";
    } else {
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

if (isset($_GET['findHouse'])) {

    $rooms = $_GET['rooms'];
    $area = $_GET['area'];
    //echo $rooms;
    //echo $area;

    if ($area == 0) {
        $sql_find_house = "SELECT HouseNo,Court,BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE BedRooms  = $rooms AND  HouseStatus = 'Available'";
    } else {
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


if (isset($_POST['newTenant'])) {

    $houseID = $_POST['houseID'];
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
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


if (isset($_GET['loadInfo'])) {

    $sql_no_users = "SELECT COUNT(UserID) as 'Users' FROM `user`";
    $sql_no_houses = "SELECT COUNT(HouseID) as 'Houses' FROM `house`";
    $sql_no_tenants = "SELECT COUNT(TenantID) as 'Tentants' FROM `tenant`";
    $sql_revenue = "SELECT SUM(Amount) as 'Revenue' FROM `rent`";
}


if (isset($_GET['loadTenants'])) {

    $sql_get_tenants = "SELECT tenant.TenantID As 'ID',user.FirstName As 'Firstname',user.SecondName AS 'Secondname',user.Email,user.PhoneNumber as 'Phone' from tenant JOIN user ON tenant.UserID = user.UserID";

    $result = $database_connection->query($sql_get_tenants);

    //Initialize array variable
    $dbdata = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $dbdata[] = $row;
    }

    //Print array in JSON format
    echo json_encode($dbdata);
}

if (isset($_GET['loadTenantInfo'])) {

    $tenantID = $_GET['TenantID'];
    $sql_get_tenant_info = "SELECT rent.Month,rent.Amount from rent JOIN tenant ON tenant.TenantID = rent.TenantID WHERE rent.TenantID = '$tenantID'";

    $result = $database_connection->query($sql_get_tenant_info);

    //Initialize array variable
    $TenantInfo = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $TenantInfo[] = $row;
    }

    $sql_house_info = "SELECT house.HouseID,house.HouseStatus,house.HouseNo,house.Court,house.BedRooms,house.Area,house.Rent from tenant JOIN house ON house.HouseID = tenant.HouseID WHERE tenant.TenantID = '$tenantID'";

    $result = $database_connection->query($sql_house_info);

    //Initialize array variable
    $HouseInfo = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $HouseInfo[] = $row;
    }


    //Print array in JSON format
   
    $myResult =  new \stdClass();
    $myResult->House = $HouseInfo;
    $myResult->Rent = $TenantInfo;

    $myJSONResult = json_encode($myResult);
    echo $myJSONResult;
}


if (isset($_GET['loadHouseInfo'])) {

    $houseID = $_GET['HouseID'];
    $sql_get_house_info = "SELECT HouseNo,Court,BedRooms,Rent,Area,HouseStatus FROM `house` WHERE HouseID = '$houseID'";

    $result = $database_connection->query($sql_get_house_info);
    //Initialize array variable
    $HouseInfo = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $HouseInfo[] = $row;
    }




    $sql_get_tenant_info = "SELECT user.FirstName,user.SecondName,user.PhoneNumber,user.Email FROM house 
    JOIN tenant ON tenant.HouseID = house.HouseID
    JOIN user ON user.UserID = tenant.UserID
    
    WHERE house.HouseID = 1 AND
    house.HouseStatus = 'Occupied' OR house.HouseStatus = 'Booked'";

    $result = $database_connection->query($sql_get_tenant_info);

    //Initialize array variable
    $TenantInfo = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $TenantInfo[] = $row;
    }


    //Print array in JSON format
   
    $myResult =  new \stdClass();
    $myResult->House = $HouseInfo;
    $myResult->Tenant = $TenantInfo;

    $myJSONResult = json_encode($myResult);
    echo $myJSONResult;
}


if(isset($_POST['updateHouse'])){

    $houseID = $_POST['houseID'];
    $courtName = $_POST['courtName'];
    $houseNumber = $_POST['houseNumber'];
    $houseBedroom = $_POST['houseBedroom'];
    $houseRent = $_POST['houseRent'];
    $houseArea = $_POST['houseArea'];
    $houseStatus = $_POST['houseStatus'];
    echo $houseID;

    $sql_update_house =  "UPDATE `house` SET `HouseNo` = '$houseNumber', 
    `Court` = '$courtName',
     `BedRooms` = '$houseBedroom', `Area` = '$houseArea', `Rent` = '$houseRent', `HouseStatus` = '$houseStatus' WHERE `house`.`HouseID` = '$houseID'";
    mysqli_query($database_connection,$sql_update_house);

}

if(isset($_POST['deleteHouse'])){

    $houseID = $_POST['houseID'];
    
    $sql_delete_house =  "DELETE FROM `house` WHERE `house`.`HouseID` = $houseID";
    mysqli_query($database_connection,$sql_delete_house);

}

?>