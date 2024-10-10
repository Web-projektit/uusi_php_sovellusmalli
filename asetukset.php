<?php
error_reporting(E_ALL);
$PALVELIN = $_SERVER['HTTP_HOST']."/";
$LINKKI_RESETPASSWORD = "resetpassword.php";
$LINKKI_VERIFICATION = "verification.php";
$PALVELUOSOITE = "asiakaspalvelu@neilikka.fi";

define("MUISTAMINUTKESTO",3600*24*7);
define("ISTUNTOPITUUS",3600);
define("OLETUSSIVU","profiili.php");
define("ETUSIVU","index.php");
define("PROFIILIKUVAKANSIO","profiilikuvat");
define("PROFIILIKUVAKOKO",5242880);
define("EMAIL_FROM","wohjelmointi@gmail.com");
define("EMAIL_FROM_NAME","Ohjelmointikurssi");
define("YRITYSKERRAT",3);
define("YRITYSKERRAT_AIKARAJA",1);

$DB = "neilikka";
$LOCAL = in_array($_SERVER['REMOTE_ADDR'],array('127.0.0.1','REMOTE_ADDR' => '::1'));
if ($LOCAL) {	
    define("DEBUG",true);
    $tunnukset = "../../../tunnukset.php";
    if (file_exists($tunnukset)){
        include_once("../../../tunnukset.php");
        } 
    else {
        die("Tiedostoa ei löydy, ota yhteys ylläpitoon.");
        } 
    $PALVELU = "projektit_PHP/php_sovellusmalli/";    
    $db_server = $db_server_local;
    $db_username = $db_username_local; 
    $db_password = $db_password_local;
    $EMAIL_ADMIN = $admin_mail;
    }
elseif (strpos($_SERVER['HTTP_HOST'],"azurewebsites") !== false){
    //define("DEBUG",false);
    $debug = $_ENV['PHP_DEBUG'] ?? getenv('PHP_DEBUG');
    define("DEBUG", $debug ? true : false);
    $PALVELU = "";
    $db_server = $_ENV['MYSQL_HOSTNAME'] ?? getenv('MYSQL_HOSTNAME');
    $db_username = $_ENV['MYSQL_USERNAME'] ?? getenv('MYSQL_USERNAME');
    $db_password = $_ENV['MYSQL_PASSWORD'] ?? getenv('MYSQL_PASSWORD');
    /* Mailtrap */
    $EMAIL_ADMIN = $_ENV['EMAIL_ADMIN'] ?? getenv('EMAIL_ADMIN'); 
    $username_mailtrap = $_ENV['EMAIL_USERNAME'] ?? getenv('EMAIL_USERNAME');
    $password_mailtrap = $_ENV['EMAIL_PASSWORD'] ?? getenv('EMAIL_PASSWORD');
    }

define("SAHKOPOSTIPALVELU","mailtrap");
if (SAHKOPOSTIPALVELU == 'sendgrid'){
    /* SendGrid */      
    define("EMAIL_HOST","smtp.sendgrid.net");
    define("EMAIL_PORT", 587);
    define("EMAIL_USERNAME",$username_sendgrid);
    define("EMAIL_PASSWORD",$password_sendgrid);
    }
    
elseif (SAHKOPOSTIPALVELU == 'mailtrap'){
    /* Mailtrap */
    define("EMAIL_HOST",'smtp.mailtrap.io');
    define("EMAIL_PORT",2525);
    define("EMAIL_USERNAME",$username_mailtrap);
    define("EMAIL_PASSWORD",$password_mailtrap);
    //debuggeri("username:".USERNAME.",password:".PASSWORD);
    }

function redirect($url){
    if (headers_sent()){
        echo "<script>window.location = '$url'</script>";
        }
    else {
        /* Huom. Tämä estäisi reitityksen Azuressa, mutta ei 
           Xamppissa, jossa on käytössä output_buffering.
           Azureen saa output_bufferingin ainakin .user.ini-tiedostossa. 
           
           Ongelma Omnian Azure-palvelimella: asettamalla output_bufferingin
           header("location: $url") toimii, vaikka headerit
           olisi jo lähetetty, mutta session-muuttujat ovat
           käytettävissä kohteessa vasta viiveellä. 
        */
        
        //echo "Tämä on testausta varten,location:$url"; 
        header("location: $url");
        }
    }    
?>