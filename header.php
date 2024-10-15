<?php 
if (!session_id()) session_start();
ini_set('default_charset', 'utf-8');
ini_set('upload_max_filesize', '10M');
include_once "debuggeri.php";
/* Huom. suojatulla sivulla on asetukset,db,rememberme.php; */
if (!isset($loggedIn)){
  require_once "asetukset.php";
  include_once "db.php";
  include "rememberme.php";
  $loggedIn = loggedIn();
  }
debuggeri("header.php,loggedIn:$loggedIn");  
register_shutdown_function('debuggeri_shutdown');
$active = basename($_SERVER['PHP_SELF'], ".php");

function active($sivu,$active){
  return $active == $sivu ? 'active' : '';  
  }

/* Huom. nav-suojaus vie viimeiset linkit oikealle. */
?>
<!DOCTYPE html>
<html lang="fi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="omniamusta_tausta.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
<link rel="stylesheet" href="navbar.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.10.2/css/all.css">
<link rel="stylesheet" href="site.css">
<?php if (isset($css)) echo "<link rel='stylesheet' href='$css'>"; ?>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
<script defer src="scripts.js"></script>
<?php if (isset($js)) echo "<script defer src='$js'></script>"; ?>
<title><?= $title ?? 'Sivusto'; ?></title>
</head>
<body>
<nav>
<a class="brand-logo" href="<?= ETUSIVU ;?>">
<img src="omniamusta_tausta.png" alt="Logo"></a>
<input type="checkbox" id="toggle-btn">
<label for="toggle-btn" class="icon open"><i class="fa fa-bars"></i></label>
<label for="toggle-btn" class="icon close"><i class="fa fa-times"></i></label>
<a class="<?= ($active == 'kuvagalleria') ? 'active':''; ?>" href="kuvagalleria.php">Kuvagalleria</a>
<?php
$nav_suojaus = 'nav-suojaus';
switch ($loggedIn) {
  case true:
    echo "<a class='".active('profiili',$active). "' href='profiili.php'>Profiili</a>";
    break;
  case 'admin':
    echo "<a class='".active('profiili',$active). "' href='profiili.php'>Profiili</a>";
    echo "<a class='".active('kayttajat',$active). "' href='kayttajat.php'>Käyttäjät</a>";
    break;
  default:
    echo "<a class='nav-suojaus ".active('login',$active)."' href='login.php'>Kirjautuminen</a>";
    break;
    } 
/* nav-suojaus vie linkit oikealle. */    
switch ($loggedIn) {
  case 'admin':
    $nav_suojaus = '';
    echo "<a class='nav-suojaus'".active('phpinfo',$active)."' href='phpinfo.php'>phpinfo</a>";
    echo "<a class='".active('fake',$active)."' href='fake.php'>fake</a>";
  case true:
    echo "<a class='$nav_suojaus' href='poistu.php'>Poistu</a>";    
    break;
  } 

?>
</nav>
