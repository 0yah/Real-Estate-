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

  <a href='../logout.php'>Log Out</a>
    
    ";
        }


        ?>
    </div>



    

<form class="form">


<div class="input-group">
  		<label>First Name</label>
          <input id="firstnameAdd" type="text" />
  	</div>


      <div class="input-group">
  		<label>Second Name</label>
          <input id="secondnameAdd"  type="text" />
                   
      </div>
      

      <div class="input-group">
  		<label>Phone Number </label>
          <input id="phonenumberAdd"  type="text" />
              
                   
      </div>
      

      <div class="input-group">
  		<label>Email </label>
          <input id="emailAdd"  type="email" />
          
              
                   
      </div>
      
      <div class="input-group">
  		<label>Password </label>
          <input id="passwordAdd" placeholder="Password" type="password" />
             
              
                   
  	</div>

                    <button class="btn" type="button" id="addUser">Add</button>

                    <div id="addUserError"></div>
                </form>
           


                <script>


document.querySelector('#addUser').addEventListener('click', function() {
            var tenantfirstName = document.querySelector('#firstnameAdd').value;
            var tenantsecondName = document.querySelector('#secondnameAdd').value;
            var tenantphoneNumber = document.querySelector('#phonenumberAdd').value;
            var tenantEmail = document.querySelector('#emailAdd').value;
            var tenantPassword = document.querySelector('#passwordAdd').value;

            //console.log(selectedHouseID, tenantfirstName, tenantsecondName, tenantphoneNumber, tenantEmail, tenantPassword);

            if (tenantEmail != '' && tenantsecondName != '' && tenantfirstName != '' && tenantphoneNumber != '' && tenantPassword != '') {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        console.log(this.responseText);
                        var response = this.responseText;
                        if (response == 'Registered') {

                            document.querySelector('#addUserError').innerHTML = "Email used";
                        } else if (response == 'Success') {

                            document.querySelector('#firstnameAdd').value = "";
                            document.querySelector('#secondnameAdd').value = "";
                            document.querySelector('#phonenumberAdd').value = "";
                            document.querySelector('#emailAdd').value = "";
                            document.querySelector('#passwordAdd').value = "";
                            loadUsers();

                            document.querySelector('.modal').style.display = "none";
                            document.querySelector('.layout').style.filter = "none";
                        } else if (response == 'Error') {
                            document.querySelector('#addUserError').innerHTML = "Something wrong happened check the fields and try again";
                        }



                    }
                };

                xhttp.open("POST", "admin.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send(`addUser&firstname=${tenantfirstName}&secondname=${tenantsecondName}&phonenumber=${tenantphoneNumber}&email=${tenantEmail}&password=${tenantPassword}`);


            }else{
                document.querySelector('#addUserError').innerHTML = "Fill in all the fiel";
            }



        });
                </script>
    
</body>
</html>