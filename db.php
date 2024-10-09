<?php
try {
   $yhteys = new mysqli($db_server, $db_username, $db_password, $DB);
   if ($yhteys->connect_error) {
       die("Yhteyden muodostaminen epäonnistui: " . $yhteys->connect_error);
       }
   $yhteys->set_charset("utf8");
   }
catch (Throwable $e) {
   die("Virhe yhteyden muodostamisessa: " . $e->getMessage());
   }

function mysqli_my_query($query) {
   $yhteys = $GLOBALS['yhteys']; 
   $result = false;
   try {
      $result = $yhteys->query($query);
      return [$result,$yhteys->errno,$yhteys->error]; 
      } 
   catch (Exception $e) {
      /* Huom. Tulisi palauttaa virhe, eikä tulostaa sitä. */
      // echo "<p class='alert alert-danger'>Virhe tietokantakyselyssä.</p>";
      debuggeri("Virhe $yhteys->errno kyselyssa $query: " . $e->getMessage());
      return [$result,$yhteys->errno,$e->getMessage()];
      }
   return $result;
   }
   

function db_connect(){
return $GLOBALS['yhteys'];   
}
?>