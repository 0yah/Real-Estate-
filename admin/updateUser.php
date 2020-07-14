<?php
include('../server.php');
include('./conn.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>



<div class="navbar">
    <?php 
    
    
if(isset($_SESSION['username'])){


    echo "
    
    
    
  <a href='index.php'>Dashboard</a>
  
  <div class='dropdown'>
    <button class='dropbtn'>Houses 
    </button>
    <div class='dropdown-content'>
      <a href='houses.php'>View All</a>
      <a href='addHouse.php'>Add House</a>
    </div>
  </div> 
  <a href='tenants.php'>Tenants</a>
  <a href='rent.php'>Rent</a>

  <div class='dropdown'>
    <button class='dropbtn'>Users 
    </button>
    <div class='dropdown-content'>
      <a href='user.php'>View All</a>
      <a href='addUser.php'>Add User</a>
    </div>
  </div> 
  <a href='../logout.php'>Log Out</a>
    
    ";
}


    ?>
</div>




<?php
    $HouseID =$_GET['userID'];

    if(empty($HouseID)){
        header('location:user.php');
    }

    $sql_house_details = "SELECT user.UserID,user.FirstName,user.SecondName,user.PhoneNumber,user.Email FROM `user` where user.UserID = $HouseID ";

    $fetch = mysqli_query($database_connection, $sql_house_details);

    while ($row = mysqli_fetch_assoc($fetch)) {
        extract($row);
        echo "
    
        <form class='form'>
        <input id='updateUserID' value='$UserID' type='hidden' type='text' />

        <div class='input-group'>
              <label>First Name</label>
              <input id='firstnameUpdate' value='$FirstName' type='text' />
          </div>
          
    
          <div class='input-group'>
              <label>Second Name</label>
              <input id='secondnameUpdate' value='$SecondName' type='text' />
          </div>
    
          <div class='input-group'>
              <label>Phone Number</label>
              <input value='$PhoneNumber' id='phonenumberUpdate'type='text' />
          </div>
    
          <div class='input-group'>
              <label>Email</label>
              <input id='emailUpdate' value='$Email' type='email' />
          </div>
    
    
          <div class='input-group'>
              <label>Password</label>
              <input id='passwordUpdate'  type='password' />
          </div>
            <div id='updateUserError'></div>
    
            <button type='button' class='btn' id='updateUser'>Update</button>
            <button type='button' class='btn' id='deleteUser'>Delete</button>
        </form>
    
    
    


    ";
    }
    ?>





    <script>
        document.querySelector('#updateUser').addEventListener('click', function() {

            var updateUserID = document.querySelector('#updateUserID').value;
            var tenantfirstName = document.querySelector('#firstnameUpdate').value;
            var tenantsecondName = document.querySelector('#secondnameUpdate').value;
            var tenantphoneNumber = document.querySelector('#phonenumberUpdate').value;
            var tenantEmail = document.querySelector('#emailUpdate').value;
            var tenantPassword = document.querySelector('#passwordUpdate').value;

            if(tenantEmail != '' && tenantfirstName != ''&& tenantsecondName != '' && tenantphoneNumber != '' && tenantPassword != ''){
            // console.log(selectedHouseID, tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var response = this.responseText;
                        console.log(response);
                        if (response == "Success") {
                            document.querySelector('#updateUserError').innerHTML = "User updated!";
                          
                        
                        } else if (response == "Error") {
                            document.querySelector('#updateUserError').innerHTML = ` ${tenantfirstName} has not be updated!`;

                        }

                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`updateUser&userID=${updateUserID}&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}&password=${tenantPassword}`);

            }else{
                document.querySelector('#updateUserError').innerHTML = ` Fill all fields!`;

            }



        });



        document.querySelector('#deleteUser').addEventListener('click', function() {

            var updateUserID = document.querySelector('#updateUserID').value;
           
            console.log(updateUserID);
            //console.log(tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    var response = this.responseText;
                        console.log(response);
                        if (response == "Success") {
                            document.querySelector('#updateUserError').innerHTML = "User deleted!";
                          
                        
                        } else if (response == "Error") {
                            document.querySelector('#updateUserError').innerHTML = `User has not been deleted!`;

                        }
             
                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`deleteUser&userID=${updateUserID}`);


        });
    </script>
</body>

</html>