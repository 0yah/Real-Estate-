<?php include('../server.php') ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form>
        <input id="addcourtName" type="text" placeholder="Court Name" />
        <input id="addhouseNumber" type="text" placeholder="House Number" />
        <input id="addhouseBedrooms" type="number" placeholder="No of Bedrooms" />
        <input id="addhouseRent" type="number" placeholder="Amount of Rent" />
        <input id="addhouseArea" type="number" placeholder="Area(m²)" />
        <button type="button" id="addHouseBtn">Add House</button>
    </form>


</body>

<script>
    document.querySelector('#addHouseBtn').addEventListener('click', function() {

        var courtName = document.querySelector('#addcourtName').value;
        var houseNumber = document.querySelector('#addhouseNumber').value;
        var houseBedroom = document.querySelector('#addhouseBedrooms').value;
        var houseRent = document.querySelector('#addhouseRent').value;
        var houseArea = document.querySelector('#addhouseArea').value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector('#addcourtName').value ="";
                document.querySelector('#addhouseNumber').value ="";
                document.querySelector('#addhouseBedrooms').value ="";
                document.querySelector('#addhouseRent').value ="";
                document.querySelector('#addhouseArea').value ="";
            }
        };

        xhttp.open("POST", "admin.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send(`addHouse&courtName=${courtName}&houseNumber=${houseNumber}&houseBedroom=${houseBedroom}&houseArea=${houseArea}&houseRent=${houseRent}`);


    });
</script>

</html>