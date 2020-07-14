<?php
include('../server.php');
include('./conn.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>



<div class="navbar">
        <?php


        if (isset($_SESSION['username'])) {


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

  <div class='dropdown'>
  <button class='dropbtn'>Tenants 
  </button>
  <div class='dropdown-content'>
    <a href='tenants.php'>View All</a>
    <a href='addTenant.php'>Add Tenant</a>
  </div>
</div> 
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
    $HouseID =$_GET['houseID'];

    if(empty($HouseID)){
        header('location:houses.php');
    }

    $sql_house_details = "SELECT HouseID,HouseNo,HouseStatus,Area,BedRooms,Rent FROM `house` WHERE HouseID = $HouseID ";

    $fetch = mysqli_query($database_connection, $sql_house_details);

    while ($row = mysqli_fetch_assoc($fetch)) {
        extract($row);
        echo "
    


    <form class='form'> 
    <input id='updatehouseID' type='hidden' value='$HouseID'/>

    <div class='input-group'>
    <label>House No</label>
    <input id='updatehouseNumber' type='text'  value ='$HouseNo' placeholder='House Number' />
    
</div>

    

    <div class='input-group'>
    <label>Bedroms</label>
    <input id='updatehouseBedrooms' value= '$BedRooms' type='number' min='1' placeholder='No of Bedrooms' />
   
</div>


<div class='input-group'>
<label>Rent</label>
<input id='updatehouseRent' value='$Rent' type='number' min='1' placeholder='Amount of Rent' />

</div>

<div class='input-group'>
<label>Area(m²)</label>
<input id='updatehouseArea' value='$Area' type='number' min='1' placeholder='Area(m²)' />
  
</div>

<div class='input-group'>
<label>Status</label>
<select id='updateHouseStatus'>
<option selected disabled>Change Status</option>
    <option value='Available'>Available</option>
    <option value='Maintainance'>Maintainance</option>
    <option value='Out of Service'>Out of Service</option>
</select>
</div>
<div id='updateHouseError'></div>
";

if($HouseStatus=="Available"){
    echo "<button type='button' class='btn' id='addHouseTenantBtn'>Add Tenant </button>";
}
echo "


    
    <button type='button' class='btn' id='updateHouseBtn'>Update House</button>
    <button type='button' class='btn' id='deleteHouseBtn'>Delete</button>
</form>


    ";
    }
    ?>





    <script>
        var selectHouseID = null;
        var selectedStatus = null;

        document.querySelector('#updateHouseStatus').addEventListener('change', function(event) {

            selectedStatus = event.target.value;
            console.log("djksdjkds", selectedStatus);
        });

        if(document.querySelector('#addHouseTenantBtn')){

            document.querySelector('#addHouseTenantBtn').addEventListener('click',function(){
            var selectHouseID = document.querySelector('#updatehouseID').value;
            window.location.assign(`addTenant.php?houseID=${selectHouseID}`);
        });
        }



        document.querySelector('#updateHouseBtn').addEventListener('click', function() {

            var houseNumber = document.querySelector('#updatehouseNumber').value;
            var houseBedroom = document.querySelector('#updatehouseBedrooms').value;
            var houseRent = document.querySelector('#updatehouseRent').value;
            var houseArea = document.querySelector('#updatehouseArea').value;
            var selectHouseID = document.querySelector('#updatehouseID').value;


            if (selectHouseID != null && houseArea != '' && houseBedroom != '' && houseNumber != '' && houseRent != '' && selectedStatus != null) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var response = this.responseText;
                        console.log(response);
                        if (response == "Success") {
                            document.querySelector('#updateHouseError').innerHTML = "House updated!";
                          
                        
                        } else if (response == "Error") {
                            document.querySelector('#updateHouseError').innerHTML = `House ${houseNumber} has not be updated!`;

                        }
                    }
                };

                xhttp.open("POST", "admin.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`updateHouse&houseID=${selectHouseID}&houseNumber=${houseNumber}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}&houseStatus=${selectedStatus}`);
                console.log(`updateHouse&houseID=${selectHouseID}&houseNumber=${houseNumber}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}&houseStatus=${selectedStatus}`);

            } else {
                document.querySelector('#updateHouseError').innerHTML = "Fill in all the fields";
            }



        });


        document.querySelector('#deleteHouseBtn').addEventListener('click', function() {

            var selectHouseID = document.querySelector('#updatehouseID').value;
            if (selectHouseID != null) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {


                        var response = this.responseText;
                        if (response == "Success") {


                            document.querySelector('#updateHouseError').innerHTML = "House Deleted!";
                            document.querySelector('#updatehouseNumber').value = "";
                            document.querySelector('#updatehouseBedrooms').value = "";
                            document.querySelector('#updatehouseRent').value = "";
                            document.querySelector('#updatehouseArea').value = "";
                        
                            
                        } else if (response == "Error") {
                            document.querySelector('#updateHouseError').innerHTML = "House could not be deleted!";

                        }

                    }
                };

                xhttp.open("POST", "admin.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`deleteHouse&houseID=${selectHouseID}`);
                console.log(`deleteHouse&houseID=${selectHouseID}`);
            }



        });
    </script>

</body>

</html>