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
    $tenantID = $_GET['tenantID'];

    if (empty($tenantID)) {
        header('location:tenant.php');
    }


    $sql_house_details = "SELECT house.HouseID,house.HouseNo,house.BedRooms,house.Area,house.Rent  FROM `tenant` JOIN house on house.HouseID = tenant.HouseID WHERE TenantID =$tenantID  ";

    $fetch = mysqli_query($database_connection, $sql_house_details);

    while ($row = mysqli_fetch_assoc($fetch)) {
        extract($row);
        echo "
        <div>House No: $HouseNo</div>
        <div>Rent: $Rent</div>
        <div>Bedrooms: $BedRooms</div>
        <div>Area: $Area</div>


        <form class='form'>

        
        <input type='hidden' id='rentHouseID' value='$HouseID'/>
        <div class='input-group'>
        <label>Month</label>
        <input type='date' name='' id='rentMonth'>                   
        </div>
        
        <div class='input-group'>
        <label>Amount</label>
        <input type='number' name='' id='rentAmount'>              
        </div>
        


        <div id='rentRecordError'></div>
        <button class='btn' type='button' id='addRentRecord'>Add Rent Record</button>
        <button class='btn'type='button' id='loadRent'>View Rent History</button>
    </form>
        ";
    }



    $sql_tenant_details = "SELECT tenant.TenantID As 'ID',tenant.FirstName As 'Firstname',tenant.SecondName AS 'Secondname',tenant.Email,tenant.PhoneNumber as 'Phone' from tenant WHERE tenant.TenantID = $tenantID";

    $fetch = mysqli_query($database_connection, $sql_tenant_details);

    while ($row = mysqli_fetch_assoc($fetch)) {
        extract($row);
        echo "
    




            <form class='form'>
                    <input id='updateID' type='hidden' value='$ID'/>

                    <div class='input-group'>
        <label>First Name</label>
        <input id='updateFirstname' value='$Firstname'placeholder='First Name' type='text' />
                   
        </div>
        
        <div class='input-group'>
        <label>Second Name</label>
        <input id='updateSecondname' value='$Secondname' placeholder='Second Name' type='text' />
                                
        </div>

        <div class='input-group'>
        <label>Phone Number</label>
        <input id='updatePhonenumber'value='$Phone' placeholder='Phone Number' type='text' />
                                         
        </div>

        
        <div class='input-group'>
        <label>Email</label>
        <input id='updateEmail' value='$Email' placeholder='E-mail' type='email' />
                                                    
        </div>

        <div class='input-group'>
        <button type='button' class='btn' id='updateTenant'>Update Tenant</button>
        <button type='button' class='btn'  id='deleteTenant'>Delete</button>
                           
        </div>

        
        


                    <div id='updateTenantError'></div>
                 
                </form>





    ";
    }
    ?>

    <div>

        <div class="tenantRentHistory">

        </div>
    </div>

    <script>
        var selectHouseID = null;
        var selectedStatus = null;



        document.querySelector('#loadRent').addEventListener('click', function() {
           
            var tenantID = document.querySelector('#updateID').value;
            console.log(tenantID);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // console.log(this.responseText);

                    var data = JSON.parse(this.responseText);
                    generateTable(".tenantRentHistory", data);

                }
            };

            xhttp.open("GET", `admin.php?tenantRentHistory&tenantID=${tenantID}`, true);
            xhttp.send();
        });

        if(document.querySelector('#addRentRecord')){
            document.querySelector('#addRentRecord').addEventListener('click', function() {

var rentMonth = document.querySelector('#rentMonth').value;
//document.querySelector('#rentMonth').value;
var tenantID = document.querySelector('#updateID').value;
var houseID = document.querySelector('#rentHouseID').value;


var rentAmount = document.querySelector('#rentAmount').value;

if (rentAmount != '' && rentMonth != '') {
    var nd = new Date(rentMonth);
    //console.log(rentMonth, rentAmount, selectedTenantID, selectedHouseID);

    //document.querySelector('#rentAmount').value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            var data = this.responseText;
            if (data == "Success") {
                document.querySelector('#rentMonth').value = "";
                document.querySelector('#rentAmount').value = "";
                document.querySelector('#rentRecordError').innerHTML = "Successfully  recorded";
            } else if (data == "Error") {
                document.querySelector('#rentRecordError').innerHTML = "Error while  recording";
            }


        }
    };

    xhttp.open("POST", "admin.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(`addRent&tenantID=${tenantID}&houseID=${houseID}&rentMonth=${rentMonth}&rentAmount=${rentAmount}`);

} else {
    document.querySelector('#rentRecordError').innerHTML = "Fill in all the fields";
}





});
        }
        




        document.querySelector('#deleteTenant').addEventListener('click', function() {
            document.querySelector('#updateTenantError').innerHTML = "";
            var tenantID = document.querySelector('#updateID').value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {


                    var data = this.responseText;
                    if (data == "Success") {
                        document.querySelector('#updateTenantError').innerHTML = "Successfully deleted!";
                        document.querySelector('#updateFirstname').value = "";
                        document.querySelector('#updateSecondname').value = "";
                        document.querySelector('#updatePhonenumber').value = "";
                        document.querySelector('#updateEmail').value = "";
                        document.querySelector('.modal').style.display = "none";
                        document.querySelector('.layout').style.filter = "none";
                    } else if (data == "Error") {
                        document.querySelector('#updateTenantError').innerHTML = "Not deleted!";
                    }
                }
            };

            xhttp.open("POST", "admin.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(`deleteTenant&tenantID=${tenantID}`);


        });
        document.querySelector('#updateTenant').addEventListener('click', function() {


            var tenantfirstName = document.querySelector('#updateFirstname').value;
            var tenantsecondName = document.querySelector('#updateSecondname').value;
            var tenantphoneNumber = document.querySelector('#updatePhonenumber').value;
            var tenantEmail = document.querySelector('#updateEmail').value;
            var tenantID = document.querySelector('#updateID').value;


            //console.log(selectedHouseID, tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);

            if (tenantEmail != '' && tenantfirstName != '' && tenantsecondName != '' && tenantphoneNumber != '') {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {


                        var data = this.responseText;
                        if (data == "Success") {
                            document.querySelector('#updateTenantError').innerHTML = "Successfully Updated!";
                        } else if (data == "Error") {
                            document.querySelector('#updateTenantError').innerHTML = "Could not update!";
                        }
                    }
                };

                xhttp.open("POST", "admin.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`updateTenant&tenantID=${tenantID}&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}`);

            } else {

                document.querySelector('#updateTenantError').innerHTML = "Fill in all the fields!";
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
                    errorMessage.innerHTML = 'No Rent Payments Found';
                


                body.appendChild(errorMessage);

            }







        }
    </script>

</body>

</html>