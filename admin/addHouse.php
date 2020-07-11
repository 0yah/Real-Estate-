<?php include('../server.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>


<nav class="nav">
                <ul>
<?php
if(isset($_SESSION['username'])){
    echo '<li><a href="addTenant.php">Add Tenant</a></li>';
    echo '<li><a href="houses.php">Houses</a></li>';
    echo '<li><a href="tenants.php">Tenants</a></li>';
    echo '<li><a href="rent.php">Rent</a></li>';
    echo '<li><a href="../logout.php">Log out</a></li>'; 

}
?>


                </ul>
            </nav>

    <form>
    <input id="addhouseNo" type="text" placeholder="House Number" />
        <input id="addhouseBedrooms" type="number" min='1' placeholder="No of Bedrooms" />
        <input id="addhouseRent" type="number" min='1' placeholder="Amount of Rent" />
        <input id="addhouseArea" type="number" min='1' placeholder="Area(m²)" />
        <div id="addHouseError"></div>
        <button type="button" id="addHouseBtn">Add House</button>
    </form>


</body>

<script>
    document.querySelector('#addHouseBtn').addEventListener('click', function() {

        var houseBedroom = document.querySelector('#addhouseBedrooms').value;
        var houseRent = document.querySelector('#addhouseRent').value;
        var houseArea = document.querySelector('#addhouseArea').value;
        var houseNo = document.querySelector('#addhouseNo').value;


        if(houseArea != '' && houseBedroom != '' && houseNo!='' && houseRent != ''){

            var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {

                var response = this.responseText;
                if(response=="Success"){
                    document.querySelector('#addhouseBedrooms').value ="";
                document.querySelector('#addhouseRent').value ="";
                document.querySelector('#addhouseArea').value ="";
                document.querySelector('#addhouseNo').value ="";
                document.querySelector('#addHouseError').innerHTML="Successfully added";
                }else if(response == "Error"){
                    document.querySelector('#addHouseError').innerHTML="Something went wrong!";
                }

            }
        };

        xhttp.open("POST", "admin.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`addHouse&houseNo=${houseNo}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}`);

        }else{
            document.querySelector('#addHouseError').innerHTML="Fill all the fields!";
        }


    });
</script>

</html>