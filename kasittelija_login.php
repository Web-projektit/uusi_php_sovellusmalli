<?php
/* ALOITUS */   
$display = "d-none";
$message = "";
$success = "success";

$ilmoitukset['errorMsg'] = 'Kirjautuminen epäonnistui. '; 
debuggeri("kasittelija_login.php,POST:".var_export($_POST,true));
$sallittu = true;
$eston_kesto = YRITYSKERRAT_AIKARAJA * 60;
$_SESSION['epaonnistuneet_yritykset'] ??= 0;
$_SESSION['viimeinen_yritys_aika'] ??= 0;

// Tarkistetaan, onko käyttäjä estetty
if ($_SESSION['epaonnistuneet_yritykset'] >= YRITYSKERRAT) {
   $aika_viimeisesta_yrityksesta = time() - $_SESSION['viimeinen_yritys_aika'];
   if ($aika_viimeisesta_yrityksesta < $eston_kesto) {
       $jäljellä_oleva_aika = $eston_kesto - $aika_viimeisesta_yrityksesta;
       $message = "Liian monta epäonnistunutta yritystä. Odota " . ceil($jäljellä_oleva_aika / 60) . " minuutti(a) ennen seuraavaa yritystä.";
       $display = "d-block";
       $success = "danger";
       $sallittu = false;  
       } 
   else {
      // Nollataan epäonnistuneet yritykset eston jälkeen
      $_SESSION['epaonnistuneet_yritykset'] = 0;
      }
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
               if (!session_id()){ 
                  session_set_cookie_params(ISTUNTOPITUUS);
                  session_start();
                  }
               $_SESSION['epaonnistuneet_yritykset'] = 0; 
               $_SESSION["loggedIn"] = $role;
               $_SESSION["user_id"] = $id;
               if ($rememberme) rememberme($id,MUISTAMINUTKESTO);
               if (isset($_SESSION['next_page'])){
                  $location = $_SESSION['next_page'];
                  unset($_SESSION['next_page']);
                  }
               else $location = OLETUSSIVU;   
               $headers_sent = headers_sent() ? "true" : "false";
               debuggeri("kasittelija_login,headers sent: $headers_sent");
               redirect($location);
               exit;
               }      
            else {
               $errors['email'] = $virheilmoitukset['verificationRequiredErr'];
               }
            }
         else {
            $errors['password'] = $virheilmoitukset['emailPwdErr'];
            // $_SESSION['odotus'] = false;

            }
         }  
      }  
   // Kirjautuminen epäonnistui
   if ($errors) {
      $_SESSION['epaonnistuneet_yritykset'] += 1;
      $_SESSION['viimeinen_yritys_aika'] = time();
      debuggeri("Epäonnistuneet yritykset: ".$_SESSION['epaonnistuneet_yritykset']);
      }   
   }
}
?>