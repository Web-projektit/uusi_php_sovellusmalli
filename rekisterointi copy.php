<?php
$display = "d-none";
$message = "";
$success = "success";
$lisays = $lisattiin_token = $lahetetty = false;

if (isset($_POST['button'])) {

foreach ($_POST as $kentta => $arvo) {
    $$kentta = $yhteys->real_escape_string(strip_tags(trim($arvo)));
    if (in_array($kentta, $pakolliset) and empty($arvo)) {
        $errors[$kentta] = $virheilmoitukset[$kentta]['valueMissing'];
        }
    else {
        if (isset($patterns[$kentta]) and !preg_match($patterns[$kentta], $arvo)) {
            $errors[$kentta] = $virheilmoitukset[$kentta]['patternMismatch'];
            }
        }
    }

if (empty($errors['password2']) and empty($errors['password'])) {
    if ($_POST['password'] != $_POST['password2']) {
        $errors['password2'] = $virheilmoitukset['password2']['customError'];
        }
    }
    
$query = "SELECT 1 FROM users WHERE email = '$email'";
$result = $yhteys->query($query);
if ($result->num_rows > 0) {
    $errors['email'] = $virheilmoitukset['email']['emailExistsError'];
    }

$query = "SELECT 1 FROM users WHERE firstname = '$firstname' AND lastname = '$lastname'";
$result = $yhteys->query($query);
if ($result->num_rows > 0) {
    debuggeri($query);
    $errors['firstname'] = $virheilmoitukset['firstname']['nameExistsError'];
    $errors['lastname'] = $virheilmoitukset['lastname']['nameExistsError'];
    }    

debuggeri($errors);    
if (empty($errors)) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname', '$lastname', '$email', '$password')";
    $result = $yhteys->query($query);
    $lisays = $yhteys->affected_rows;
    }

if ($lisays) {
    $id = $yhteys->insert_id;
    $token = md5(rand().time());
    $query = "INSERT INTO signup_tokens (users_id,token) VALUES ($id,'$token')";
    debuggeri($query);
    $result = $yhteys->query($query);
    $lisattiin_token = $yhteys->affected_rows;
    }

if ($lisattiin_token) {
    $msg = "Vahvista sähköpostiosoitteesi alla olevasta linkistä:<br><br>";
    $msg.= "<a href='http://$PALVELIN/$PALVELU/verification.php?token=$token'>Vahvista sähköpostiosoite</a>";
    $msg.= "<br><br>t. t. $PALVELUOSOITE";
    $subject = "Vahvista sähköpostiosoite";
    $lahetetty = posti($email,$msg,$subject);
    }   

if ($lahetetty){
    $message = "Tiedot on allennettu. Sinulle on lähetty antamaasi sähköpostiosoitteeseen
                vahvistuspyyntö. Vahvista siinä olevasta linkistä sähköpostiosoitteesi.";

    }
elseif ($lisays) {
    $message = "Tallennus onnistui!";
    }
else {
    $message = "Tallennus epäonnistui!";
    $success = "danger";
    }
$display = "d-block";

/*var_export($_POST);
echo "<br>";
var_export($errors);*/
}

?>