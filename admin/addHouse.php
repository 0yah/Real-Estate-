<?php include('../server.php') ?>

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




    <form class="form">

        <div class="input-group">
            <label>House Number:</label>
            <input id="addhouseNo" type="text" placeholder="" />
        </div>

        <div class="input-group">
            <label>Bedrooms:</label>
            <input id="addhouseBedrooms" type="number" min='1' placeholder="No of Bedrooms" />
        </div>

        <div class="input-group">
            <label>Rent:</label>
            <input id="addhouseRent" type="number" min='1' placeholder="Amount of Rent" />

        </div>
        <div class="input-group">
            <label>Area:</label>
            <input id="addhouseArea" type="number" min='1' placeholder="Area(m²)" />
        </div>

        <div class="input-group">
            <button class='btn' type="button" id="addHouseBtn">Add House</button>
        </div>


        <div id="addHouseError"></div>

    </form>


</body>

<script>
    document.querySelector('#addHouseBtn').addEventListener('click', function() {

        var houseBedroom = document.querySelector('#addhouseBedrooms').value;
        var houseRent = document.querySelector('#addhouseRent').value;
        var houseArea = document.querySelector('#addhouseArea').value;
        var houseNo = document.querySelector('#addhouseNo').value;


        if (houseArea != '' && houseBedroom != '' && houseNo != '' && houseRent != '') {

            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

                    var response = this.responseText;
                    if (response == "Success") {
                        document.querySelector('#addhouseBedrooms').value = "";
                        document.querySelector('#addhouseRent').value = "";
                        document.querySelector('#addhouseArea').value = "";
                        document.querySelector('#addhouseNo').value = "";
                        document.querySelector('#addHouseError').innerHTML = "Successfully added";
                    } else if (response == "Error") {
                        document.querySelector('#addHouseError').innerHTML = "Something went wrong!";
                    }

                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`addHouse&houseNo=${houseNo}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}`);

        } else {
            document.querySelector('#addHouseError').innerHTML = "Fill all the fields!";
        }


    });
</script>

</html>