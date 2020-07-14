<?php include('../server.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            margin: 0px;
            position: relative;
        }

        table {
            background-color: white;
        }

        .layout {
            position: relative;
            filter: blur(0px);
            height: 100vh;
        }

        .modal {
            position: absolute;
            top: 0;
            background-color: white;
            border-radius: 5px;
            padding: 5px;
            overflow-x: scroll;
            display: none;
            top: 25%;
            left: 10%
        }

        .modal::-webkit-scrollbar {
            display: none;
        }

        .modal-header {
            position: relative;

        }

        .modal-header span {
            position: absolute;
            right: 0;
            top: 0;
            font-size: 0.8em;
        }

        .modal-body {
            margin: 20px;

        }

        .modal-body table {}

        .modal-body table th {
            background-color: blue;
            color: white;
            padding: 5px;
        }

        .modal-body table th,
        .modal-body table td {
            border: 1px solid #ddd;
        }


        .modal-body table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .modal-body table tr:hover {
            background-color: #e2d8d8;
        }
    </style>
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



    <form class="form">
        <input type="hidden" id="addTenantHouseID" value=<?php $houseID = $_GET['houseID']; echo "$houseID";?>>
    
    <div class="input-group">
    <label>First Name</label>
            <input id="firstname" type="text" />
    </div>


    <div class="input-group">
    <label>Second Name</label>
    <input id="secondname" type="text" />
    </div>

    <div class="input-group">
    <label>Phone Number</label>
    <input id="phonenumber" type="text" />
    </div>

    <div class="input-group">
    <label>Email</label>
    <input id="email"  type="email" />
           
    </div>




            <div id="addTenantError"></div>
            <button  class='btn'type="button" id="addNewTenant">Add Tenant</button>
        </form>






</body>

<script>
    var selectedHouseID = null;
    var selectedHouseCourt = null;

        document.querySelector('#addNewTenant').addEventListener('click', function() {
        var tenantfirstName = document.querySelector('#firstname').value;
        var tenantsecondName = document.querySelector('#secondname').value;
        var tenantphoneNumber = document.querySelector('#phonenumber').value;
        var tenantEmail = document.querySelector('#email').value;
        var tenantID = document.querySelector('#addTenantHouseID').value;
        

        if(tenantfirstName != '' && tenantEmail != '' && tenantsecondName != '' && tenantphoneNumber !=''){
     
                    //console.log(selectedHouseID, tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var response = this.responseText;
                document.querySelector('#firstname').value = "";
                document.querySelector('#secondname').value = "";
                document.querySelector('#phonenumber').value = "";
                document.querySelector('#email').value = "";
              
                if(response == "Success"){
                    document.querySelector('#addTenantError').innerHTML = "Successfully allocated";
                    
        document.querySelector('.modal').style.display = "none";
                }else if(response == "Error"){
                    document.querySelector('#addTenantError').innerHTML = "Could not allocate the house";
                }
            }
        };

        xhttp.open("POST", "admin.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`newTenant&houseID=${tenantID}&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}`);


        }else{
            document.querySelector('#addTenantError').innerHTML = "Fill all the fields";
        }
      


    });



    function generateTable(target, data) {
        var body = document.querySelector(target);
        body.innerHTML = "";
        var table = document.createElement("table");
        var tableHead = document.createElement("thead");
        var tableBody = document.createElement("tbody");
        tableBody.setAttribute("id", "myTable");



        if (data.length > 0) {
            var headers = Object.keys(data[0]);
            var noHeaders = Object.keys(data[0]).length;
            for (var i = 0; i < noHeaders; i++) {
                var table_head_row = document.createElement('th');
                table_head_row.innerText = headers[i]
                tableHead.appendChild(table_head_row);
                //console.log(headers[i])
            }

            for (var j = 0; j < data.length; j++) {
                var table_row = document.createElement('tr');
                table_row.setAttribute("class", "row");

                var counter = 0;
                for (const [key, value] of Object.entries(data[j])) {
                    //console.log(`${key}: ${value} : ${counter}`);



                    var table_data = document.createElement('td');
                    if (counter == 0) {
                        table_data.setAttribute('class', 'controller')
                    }
                    counter++;
                    table_data.innerText = value;
                    table_row.appendChild(table_data);
                }
                tableBody.appendChild(table_row);
            }

            table.appendChild(tableHead);
            table.appendChild(tableBody);
            // body.appendChild(table);

            body.appendChild(table);

        } else {
            var errorMessage = document.createElement('span');
            errorMessage.innerHTML = 'No free <a href="houses.php">Houses</a> available';

            body.appendChild(errorMessage);

        }







    }

    function loadHouses(status = 1) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);

                var data = JSON.parse(this.responseText);
                generateTable(".loadedHouses", data);

            }
        };

        xhttp.open("GET", `admin.php?loadHouses&Status=${status}`, true);
        xhttp.send();
    }

    document.querySelector('#findHouseBtn').addEventListener('click', function() {
        var getNoRooms = document.querySelector('#numberRooms').value;
        var getNoArea = document.querySelector('#numberArea').value;
        console.log(getNoArea, getNoRooms);

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);


                var data = JSON.parse(this.responseText);
                generateTable(".loadedHouses", data);

            }
        };

        if (getNoArea == 0 || getNoArea == null) {
            getNoArea = 0;

        }

        xhttp.open("GET", `admin.php?findHouse&rooms=${getNoRooms}&area=${getNoArea}`, true);
        xhttp.send();

    });

    loadHouses();




    document.querySelector('#modal-close').addEventListener('click', function() {

        document.querySelector('.modal').style.display = "none";
        document.querySelector('.layout').style.filter = "none";
    });

    setTimeout(function() {

        document.querySelectorAll('.controller').forEach((value, index) => {
            // console.log(value);
            value.addEventListener('click', function() {
                //   console.log(value.innerText);
                document.querySelector('.modal').style.display = "block";
                selectedHouseID = value.innerText;

            });
        });

    }, 3000);
</script>

</html>