<?php
$display = "d-none";
$message = "";
$success = "success";
$muutettu = $poistettu_token = false;
$virheet_palvelin['invalidLink'] = "Salasanan aktivointilinkki ei ole voimassa.";
$virheet_palvelin['invalidToken'] = "Linkki on virheellinen.";

$token = $_GET['token'] ?? '';
if ($token) {
   /* Haetaan email */
   $date = date('Y-m-d');
   $token = $yhteys->real_escape_string(strip_tags(trim($token)));
   $query = "SELECT users_id FROM resetpassword_tokens WHERE token = '$token' AND voimassa >= '$date'";
   debuggeri($query);
   $result = $yhteys->query($query);
   if (!list($users_id) = $result->fetch_row()){
      debuggeri("Virheellinen token.");  
      $message = $virheet_palvelin['invalidLink'];
      $display = "d-block";
      $success = "danger";
      }
    } 
else {
    $message = $virheet_palvelin['invalidToken'];
    $display = "d-block";
    $success = "danger";
    }   

if (isset($_POST['painike']) and !$message){
    foreach ($_POST as $kentta => $arvo) {
        if (in_array($kentta, $pakolliset) and empty($arvo)) {
            $errors[$kentta] = $virheilmoitukset[$kentta]['valueMissing'];
            }
        else {
            if (isset($patterns[$kentta]) and !preg_match($patterns[$kentta], $arvo)) {
                $errors[$kentta] = $virheilmoitukset[$kentta]['patternMismatch'];
                }
            else {
                if (is_array($arvo)) $$kentta = $arvo;
                else $$kentta = $yhteys->real_escape_string(strip_tags(trim($arvo)));
                } 
            }
        }

if (empty($errors['password2']) and empty($errors['password'])) {
    if ($_POST['password'] != $_POST['password2']) {
        $errors['password2'] = $virheilmoitukset['password2']['customError'];
        }
    }
    
debuggeri($errors);    
if (empty($errors)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET password = '$password' WHERE id = $users_id";
    $result = $yhteys->query($query);
    $muutettu = $yhteys->affected_rows;
    }

if ($muutettu) {
    $query = "DELETE FROM resetpassword_tokens WHERE users_id = $users_id";
    debuggeri($query);
    $result = $yhteys->query($query);
    $poistettu_token = $yhteys->affected_rows;
    debuggeri("Poistettiin $poistettu_token token.");
    /* Huom. tässä siirrytään suoraan kirjautumissivulle. */
    header("location: login.php");
    exit;
    }
}
?>