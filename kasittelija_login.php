<?php
/* ALOITUS */   
$display = "d-none";
$message = "";
$success = "success";
$ilmoitukset['errorMsg'] = 'Kirjautuminen epäonnistui. '; 
debuggeri("POST:".var_export($_POST,true));

if (isset($_POST['painike'])){
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
   $rememberme = isset($rememberme) ? true : false;
   if ($errors) debuggeri($errors);
   if (!$errors){
      $query = "SELECT users.id,password,is_active,name FROM users LEFT JOIN roles ON role = roles.id WHERE email = '$email'";
      debuggeri($query);
      $result = $yhteys->query($query);
      if (!$result) die("Tietokantayhteys ei toimi: ".mysqli_error($connection));
      if (!$result->num_rows) {
         debuggeri("$email:$virheilmoitukset[accountNotExistErr]");
         $message =  $ilmoitukset['errorMsg'];
         $success = "danger";
         $display = "d-block";
         }
      else {
         [$id,$password_hash,$is_active,$role] = $result->fetch_row();
         if (password_verify($password, $password_hash)){
            if ($is_active){
               if (!session_id()) session_start();
               $_SESSION["loggedIn"] = $role;
               if ($rememberme) rememberme($id);
               if (isset($_SESSION['next_page'])){
                  $location = $_SESSION['next_page'];
                  unset($_SESSION['next_page']);
                  }
               else $location = OLETUSSIVU;   
               header("location: $location");
               exit;
               }      
            else {
               $errors['email'] = $virheilmoitukset['verificationRequiredErr'];
               }
            }
         else {
            $errors['password'] = $virheilmoitukset['emailPwdErr'];
            }
         }  
      }  
   }   
?>