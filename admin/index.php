<?php
include('../server.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
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


    <div class="layout">
       
        <div class="right">
            <div class="summary">
                <div id="revenue"></div>
                <div id="houses"></div>
                <div id="tenants"></div>
            </div>
        </div>
    </div>




    <script>
        function loadInfo() {

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);

                    var noRevenue = data.revenue[0].Revenue;
                    var noHouses = data.houses[0].Houses;
                    var noTenants = data.tenants[0].Tentants;

                    document.querySelector('#revenue').innerHTML = `KES ${noRevenue}`;
                    document.querySelector('#houses').innerHTML = `Houses ${noHouses}`;
                    document.querySelector('#tenants').innerHTML = `Tenants ${noTenants}`;
                    console.log(data);

                }
            };

            xhttp.open('GET', `admin.php?loadInfo`, true);
            xhttp.send();

        }

        loadInfo();
    </script>
</body>

</html>