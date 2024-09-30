<?php
/* ALOITUS */   
$display = "d-none";
$message = "";
$success = "success";
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id) {
   $query = "SELECT * FROM users WHERE id = $user_id";
   $result = $yhteys->query($query);
   if (!$result) die("Tietokantayhteys ei toimi: ".mysqli_error($connection));
   if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $image = $row['image'];
      $kuvatiedosto = PROFIILIKUVAKANSIO."/".$image;
      if (!file_exists($kuvatiedosto)) {
            $kuvatiedosto = "";
            $message = "Kuvaa ei löydy.";
            $success = "danger";
            $display = "d-block";
            }
      }
    else {
        $message = "Tietoja ei löydy.";
        $success = "danger";
        $display = "d-block";
      }
   }
else {
   $message =  "Tietoja ei löydy.";
   $success = "danger";
   $display = "d-block";
   }

