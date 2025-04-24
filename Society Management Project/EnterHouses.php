
<?php

//one time use file 

require_once("Includes/config.php"); 
require_once("Includes/session.php");
// Loop through sectors, streets, and houses
$street = 10;
for ($Sector = 'A'; $Sector <= 'D'; $Sector++) {
    for ($j = 1; $j <= 10; $j++) {
        for ($i = 1; $i <= 5; $i++) {
            // Prepare the SQL query
            $sql = "INSERT INTO Houses (Street, Sector) VALUES ('$street', '$Sector')";
            // Execute the query
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully for Sector $Sector, Street $street";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
            }
        }
        $street++;
    }
}

?>