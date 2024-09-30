<?php
/* ALOITUS */   
$display = "d-none";
$message = "";
$success = "success";
$sallittu = true;
$ilmoitukset['errorMsg'] = 'Kirjautuminen epäonnistui. '; 
debuggeri("POST:".var_export($_POST,true));
$_SESSION['yritysaika'] ??= date("Y-m-d H:i:s");
$_SESSION['yrityskerrat'] ??= 0;
$yrityskerrat = $_SESSION['yrityskerrat'];
$apu1 = strtotime($_SESSION['yritysaika']) + YRITYSKERRAT_AIKARAJA * 60 > time();
$apu2 = strtotime($_SESSION['yritysaika']) + YRITYSKERRAT_AIKARAJA * 60;
$apu3 = time();
debuggeri("yrityskerrat: $yrityskerrat,yritysaika: {$_SESSION['yritysaika']}");  
debuggeri("apu: $apu1, apu2: " . date("Y-m-d H:i:s",$apu2) . ",apu3: " . date("Y-m-d H:i:s",$apu3));
/* Huom. tämä on kesken */

if ($yrityskerrat > YRITYSKERRAT and strtotime($_SESSION['yritysaika']) + YRITYSKERRAT_AIKARAJA * 60 > time()) {
   //$aikaraja = YRITYSKERRAT_AIKARAJA;
   $aikajaljella = ceil(YRITYSKERRAT_AIKARAJA - (time() - strtotime($_SESSION['yritysaika']))/60);
   $message = "Liian monta yritystä. Yritä uudelleen $aikajaljella min päästä.";
   $display = "d-block";
   $success = "danger";
   $sallittu = false;
   }


if ($sallittu) {   
if (isset($_POST['painike'])){
   [$errors,$values] = validointi($kentat);
   extract($values);

   $rememberme = $rememberme ?? false;
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
               $_SESSION["loggedIn"] = "$role";
               $_SESSION["user_id"] = $id;
               if ($rememberme) rememberme($id);
               if (isset($_SESSION['next_page'])){
                  $location = $_SESSION['next_page'];
                  unset($_SESSION['next_page']);
                  }
               else $location = OLETUSSIVU;   
               $_SESSION['yrityskerrat'] = 0;
               header("location: $location");
               exit;
               }      
            else {
               $errors['email'] = $virheilmoitukset['verificationRequiredErr'];
               }
            }
         else {
            $errors['password'] = $virheilmoitukset['emailPwdErr'];
            $_SESSION['yrityskerrat'] = $yrityskerrat % (YRITYSKERRAT + 1) + 1; 
            $_SESSION['yritysaika'] = date("Y-m-d H:i:s");
            $_SESSION['odotus'] = false;
            }
         }  
      }  
   }   
}
?>