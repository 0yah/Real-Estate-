<?php

include('../server.php');
//include('./conn.php');

if (isset($_POST['addHouse'])) {

    $houseNo = $_POST['houseNo'];
    $houseBedroom = $_POST['houseBedroom'];
    $houseRent = $_POST['houseRent'];
    $houseArea = $_POST['houseArea'];
    $houseStatus = "Available";
    $addedDate = date("Y-m-d H:i:s");


    $sql_add_house = "INSERT INTO `house` (`HouseID`, `HouseNo`, `BedRooms`, `Area`, `Rent`, `HouseStatus`, `CreatedDate`) 
    VALUES (NULL, '$houseNo', '$houseBedroom', '$houseArea', '$houseRent', '$houseStatus', '$houseStatus')";
    mysqli_query($database_connection, $sql_add_house);

    $last_id = $database_connection->insert_id;
    if($last_id>0){
        echo 'Success';
    }else{
        echo 'Error';
    }






}

if (isset($_GET['loadHouses'])) {

    $Status = $_GET['Status'];

    //$Status = 1;

    if ($Status == 1) {
        $sql_get_house = "SELECT HouseID As 'ID',HouseNo As 'House No',BedRooms As 'Bed Rooms',Area,Rent,HouseStatus As 'Status' FROM `house` WHERE HouseStatus = 'Available'";
    } else if ($Status == 2) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo As 'House No',BedRooms As 'Bed Rooms' ,Area,Rent,HouseStatus As 'Status' FROM `house` WHERE HouseStatus = 'Occupied'";
    } else if ($Status == 3) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo As 'House No',BedRooms As 'Bed Rooms',Area,Rent,HouseStatus As 'Status' FROM `house` WHERE HouseStatus = 'Maintainance'";
    } else if ($Status == 4) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo As 'House No',BedRooms As 'Bed Rooms',Area,Rent,HouseStatus As 'Status' FROM `house` WHERE HouseStatus = 'Out of Service'";
    } else if ($Status == 5) {

        $sql_get_house = "SELECT HouseID As 'ID',HouseNo As 'House No',BedRooms As 'Bed Rooms',Area,Rent,HouseStatus As 'Status' FROM `house` WHERE HouseStatus = 'Booked'";
    } else {
        $sql_get_house = "SELECT  HouseID As 'ID',HouseNo As 'House No',BedRooms As 'Bed Rooms',Area,Rent,HouseStatus As 'Status' FROM `house`";
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
        $sql_find_house = "SELECT HouseID As 'ID',BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE BedRooms  = $rooms AND  HouseStatus = 'Available'";
    } else {
        $sql_find_house = "SELECT HouseID As 'ID',BedRooms As 'Bed Rooms',Area,Rent FROM `house` WHERE Area <= $area AND BedRooms = $rooms AND  HouseStatus = 'Available'";
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
    $houseStatus = "Booked";
    $addedDate = date("Y-m-d H:i:s");

    $sql_book_house = "UPDATE `house` SET `HouseStatus` = '$houseStatus' WHERE `house`.`HouseID` = '$houseID'";
    mysqli_query($database_connection, $sql_book_house);
    
    $sql_new_tenant = "INSERT INTO `tenant` (`TenantID`, `HouseID`, `FirstName`, `SecondName`, `PhoneNumber`, `Email`) VALUES (NULL,
     '$houseID', '$firstname', '$secondname', '$phonenumber', '$email')";
    mysqli_query($database_connection, $sql_new_tenant);

    $last_id = $database_connection->insert_id;
    if($last_id>0){
        echo 'Success';
    }else{
        echo 'Error';
    }


}


if (isset($_GET['loadInfo'])) {

    $sql_no_users = "SELECT COUNT(UserID) as 'Users' FROM `user`";
    $sql_no_houses = "SELECT COUNT(HouseID) as 'Houses' FROM `house`";
    $sql_no_tenants = "SELECT COUNT(TenantID) as 'Tentants' FROM `tenant`";
    $sql_revenue = "SELECT SUM(Amount) as 'Revenue' FROM `rent`";

    $result = $database_connection->query($sql_revenue);

    //Initialize array variable
    $revenue = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $revenue[] = $row;
    }

    $result = $database_connection->query($sql_no_tenants);

    //Initialize array variable
    $tenants = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $tenants[] = $row;
    }


    $result = $database_connection->query($sql_no_houses);
    //Initialize array variable
    $houses = array();
    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $houses[] = $row;
    }




    //Print array in JSON format
   
    $myResult =  new \stdClass();
    $myResult->houses = $houses;
    $myResult->revenue = $revenue;
    $myResult->tenants = $tenants;

    $myJSONResult = json_encode($myResult);
    echo $myJSONResult;


}


if (isset($_GET['loadTenants'])) {

    $sql_get_tenants = "SELECT tenant.TenantID As 'ID',tenant.FirstName As 'Firstname',tenant.SecondName AS 'Secondname',tenant.Email,tenant.PhoneNumber as 'Phone' from tenant";

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
    $sql_get_tenant_info = "SELECT CONCAT(MONTHNAME(rent.Month),'-',Year(rent.Month)) as 'Month',rent.Amount from rent JOIN tenant ON tenant.TenantID = rent.TenantID WHERE rent.TenantID = $tenantID ORDER BY rent.Month DESC LIMIT 6 ";

    $result = $database_connection->query($sql_get_tenant_info);

    //Initialize array variable
    $TenantInfo = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $TenantInfo[] = $row;
    }

    $sql_house_info = "SELECT house.HouseID,house.HouseNo,house.HouseStatus,house.BedRooms,house.Area,house.Rent from tenant JOIN house ON house.HouseID = tenant.HouseID WHERE tenant.TenantID = '$tenantID'";

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
    
    WHERE house.HouseID = $houseID AND
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
    $houseNumber = $_POST['houseNumber'];
    $houseBedroom = $_POST['houseBedroom'];
    $houseRent = $_POST['houseRent'];
    $houseArea = $_POST['houseArea'];
    $houseStatus = $_POST['houseStatus'];
    //echo $houseID;

    $sql_update_house =  "UPDATE `house` SET  `HouseNo` = '$houseNumber', `BedRooms` = '$houseBedroom', `Area` = '$houseArea', `Rent` = '$houseRent', `HouseStatus` = '$houseStatus' WHERE `house`.`HouseID` = '$houseID'";
    mysqli_query($database_connection,$sql_update_house);


    $deleted_rows = $database_connection->affected_rows;
    if($deleted_rows > 0){
        echo 'Success';
    }else{
        echo 'Error';
    }

}

if(isset($_POST['deleteHouse'])){

    $houseID = $_POST['houseID'];
    
    $sql_delete_house =  "DELETE FROM `house` WHERE `house`.`HouseID` = $houseID";
    mysqli_query($database_connection,$sql_delete_house);

    $deleted_rows = $database_connection->affected_rows;
    if($deleted_rows > 0){
        echo 'Success';
    }else{
        echo 'Error';
    }

}


if(isset($_GET['loadUsers'])){
    $sql_load_users = "SELECT user.UserID As 'ID', CONCAT(user.FirstName ,' ', user.SecondName) AS 'Name' ,user.Email,user.PhoneNumber As 'Phone' FROM user";

    $result = $database_connection->query($sql_load_users);

    //Initialize array variable
    $dbdata = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $dbdata[] = $row;
    }

    //Print array in JSON format
    echo json_encode($dbdata);
}

if(isset($_POST['addUser'])){

    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $addedDate = date("Y-m-d H:i:s");



    $userquery = "SELECT * FROM user WHERE  EMAIL='$email' LIMIT 1";
	$AM = mysqli_query($database_connection, $userquery);
	$bb = mysqli_fetch_assoc($AM);
    if ($bb) {
		if ($bb['Email'] === $email) {
			echo 'Registered';
		}
	}else{


        $sql_new_user = "INSERT INTO `user` (`UserID`, `FirstName`, `SecondName`, `PhoneNumber`, `Email`, `Pass`, `JoinedDate`) 
        VALUES (NULL, '$firstname', '$secondname', '$phonenumber', '$email', '$password', '$addedDate');";
        mysqli_query($database_connection, $sql_new_user);

        $last_id = $database_connection->insert_id;
        if($last_id>0){
            echo 'Success';
        }else{
            echo 'Error';
        }


    }
    


}

if(isset($_POST['updateUser'])){

    $user_id = $_POST['userID'];
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql_update_user = "UPDATE `user` SET `FirstName` = '$firstname', `SecondName` = '$secondname', `PhoneNumber` = '$phonenumber', `Email` = '$email', `Pass` = '$password' WHERE `user`.`UserID` = $user_id";
    mysqli_query($database_connection, $sql_update_user);

    
    $deleted_rows = $database_connection->affected_rows;
    if($deleted_rows > 0){
        echo 'Success';
    }else{
        echo 'Error';
    }
    
}

if(isset($_POST['deleteUser'])){

    $user_id = $_POST['userID'];
    $sql_delete_user = "DELETE FROM `user` WHERE `user`.`UserID` = $user_id";
    mysqli_query($database_connection, $sql_delete_user);
    $deleted_rows = $database_connection->affected_rows;
    if($deleted_rows > 0){
        echo 'Success';
    }else{
        echo 'Error';
    }


}


if(isset($_POST['updateTenant'])){
    $tenantID = $_POST['tenantID'];
    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];


    $sql_update_tenant = "UPDATE `tenant` SET
     `FirstName` = '$firstname',
      `SecondName` = '$secondname',
       `PhoneNumber` = '$phonenumber',
        `Email` = '$email' WHERE `tenant`.`TenantID` = '$tenantID'";

    mysqli_query($database_connection, $sql_update_tenant);


    $deleted_rows = $database_connection->affected_rows;
    if($deleted_rows > 0){
        echo 'Success';
    }else{
        echo 'Error';
    }

}


if(isset($_POST['deleteTenant'])){

    $tenantID = $_POST['tenantID'];
    $sql_delete_tenant = "DELETE FROM `tenant` WHERE `tenant`.`TenantID` = $tenantID";
    mysqli_query($database_connection, $sql_delete_tenant);
    $deleted_rows = $database_connection->affected_rows;
    if($deleted_rows > 0){
        echo 'Success';
    }else{
        echo 'Error';
    }


}

if(isset($_POST['addRent'])){

    $houseID = $_POST['houseID'];
    $tenantID = $_POST['tenantID'];
    $rentMonth = $_POST['rentMonth'];
    $rentAmount = $_POST['rentAmount'];
    $addedDate = date("Y-m-d H:i:s");

    $sql_update_house = "UPDATE `house` SET `HouseStatus` = 'Occupied' WHERE `house`.`HouseID` = $houseID";
    mysqli_query($database_connection, $sql_update_house);

    $sql_add_rent = "INSERT INTO `rent` (`RentID`, `TenantID`, `HouseID`, `Amount`, `Month`, `CreatedDate`) VALUES (NULL, '$tenantID', '$houseID', '$rentAmount', '$rentMonth', '$addedDate')";
    mysqli_query($database_connection, $sql_add_rent);


    $deleted_rows = $database_connection->affected_rows;
    if($deleted_rows > 0){
        echo 'Success';
    }else{
        echo 'Error';
    }


}


if(isset($_GET['loadRent'])){
   

    if(isset( $_GET['from']) && isset($_GET['to'])){
        $from = $_GET['from'];
        $to = $_GET['to'];
        $sql_load_rent = "SELECT rent.RentID as 'ID',house.HouseID as 'House No',CONCAT(tenant.FirstName,' ',tenant.SecondName) As 'Name',rent.Amount,CONCAT(MONTHNAME(rent.Month),'-',Year(rent.Month)) as 'Month' FROM `rent` JOIN tenant on tenant.TenantID = rent.TenantID JOIN house on house.HouseID = rent.HouseID 
        WHERE rent.Month BETWEEN CAST('$from' AS DATE)  AND CAST('$to' AS DATE) 

        ORDER BY rent.Month DESC";

$sql_revenue = "SELECT SUM(Amount) as 'Revenue' FROM `rent` WHERE rent.Month BETWEEN CAST('$from' AS DATE)  AND CAST('$to' AS DATE)";
  
    }else{
        $sql_load_rent = "SELECT rent.RentID as 'ID',house.HouseID as 'House No',CONCAT(tenant.FirstName,' ',tenant.SecondName) As 'Name',rent.Amount,CONCAT(MONTHNAME(rent.Month),'-',Year(rent.Month)) as 'Month' FROM `rent` JOIN tenant on tenant.TenantID = rent.TenantID JOIN house on house.HouseID = rent.HouseID ORDER BY rent.Month DESC";
        $sql_revenue = "SELECT SUM(Amount) as 'Revenue' FROM `rent`";
    }
   
    $result = $database_connection->query($sql_load_rent);

    //Initialize array variable
    $records = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }





    $result = $database_connection->query($sql_revenue);

    //Initialize array variable
    $revenue = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $revenue[] = $row;
    }


    //Print array in JSON format
   
    $myResult =  new \stdClass();
    $myResult->revenue = $revenue;
    $myResult->records = $records;

    $myJSONResult = json_encode($myResult);
    echo $myJSONResult;

}


if(isset($_GET['tenantRentHistory'])){

    $tenantID = $_GET['tenantID'];
    $sql_tenant_rent_history = "SELECT house.HouseNo as 'House No', rent.Amount,CONCAT(MONTHNAME(rent.Month),'-',Year(rent.Month)) as 'Month' from tenant JOIN rent on rent.TenantID = tenant.TenantID JOIN house on house.HouseID = tenant.HouseID WHERE tenant.TenantID = $tenantID";

    $result = $database_connection->query($sql_tenant_rent_history);

    //Initialize array variable
    $dbdata = array();

    //Fetch into associative array
    while ($row = $result->fetch_assoc()) {
        $dbdata[] = $row;
    }

    //Print array in JSON format
    echo json_encode($dbdata);
}