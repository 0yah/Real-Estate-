<?php
include('../server.php');
include('./conn.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent</title>
    
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
        <input type="date" id="from">
        <input type="date" id="to">
        <button id='Apply' type="button">Apply</button>
        <div class="right">
        <div id="revenueText"></div>
        <div class="loadRent">

        </div>
        </div>
    </div>



<script>

var to = null;

document.querySelector('#from').addEventListener('change',function(event){

    to = event.target.value;
});

var from = null;
document.querySelector('#to').addEventListener('change',function(event){
    from = event.target.value;
});

document.querySelector('#Apply').addEventListener('click',function(){

    var d = new Date(from);
    console.log(d.getDate);

    console.log(from,to);

    if(from != null && to!=null){
        var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        // console.log(this.responseText);

        var data = JSON.parse(this.responseText);
        console.log(data);
        var rent = data.records;
        var revenue = data.revenue[0].Revenue;
        if(!revenue){
            revenue = 0;
        }
        document.querySelector('#revenueText').innerHTML = `KES ${revenue}`;
        generateTable(".loadRent", rent);

    }
};

xhttp.open('GET', `admin.php?loadRent&from=${from}&to=${to}`, true);
xhttp.send();




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
              
                    errorMessage.innerHTML = `No Rent Found for Period ${from} - ${to}`;
              

                body.appendChild(errorMessage);

            }







        }

function loadRent(tenantID) {

var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        // console.log(this.responseText);

        var data = JSON.parse(this.responseText);
        console.log(data);
        var rent = data.records;
        var revenue = data.revenue[0].Revenue;
        document.querySelector('#revenueText').innerHTML = `KES ${revenue}`;
        generateTable(".loadRent", rent);

    }
};

xhttp.open('GET', `admin.php?loadRent`, true);
xhttp.send();




}

loadRent();
</script>


</body>
</html>