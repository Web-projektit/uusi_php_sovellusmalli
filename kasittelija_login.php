<?php
/* ALOITUS */   
$display = "d-none";
$message = "";
$success = "success";
$sallittu = true;
$ilmoitukset['errorMsg'] = 'Kirjautuminen epäonnistui. '; 
debuggeri("POST:".var_export($_POST,true));

$yrityskerrat = $_SESSION['yrityskerrat'] ?? 0;
if ($yrityskerrat > YRITYSKERRAT) {
   $message = "Liian monta yritystä. Yritä myöhemmin uudelleen.";
   $display = "d-block";
   $success = "danger";
   $sallittu = false;
   $_SESSION['yrityskerrat'] = 0;
   }

if ($sallittu) {   
if (isset($_POST['painike'])){
   [$errors,$values] = validointi($kentat);
   extract($values);

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
            $yrityskerrat++;
            $_SESSION['yrityskerrat'] = $yrityskerrat;
            }
         }  
      }  
   }   
}
?>