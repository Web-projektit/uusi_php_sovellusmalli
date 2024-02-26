<?php 
include "asetukset.php";
include "db.php";
include "rememberme.php";
include "debuggeri.php";
if ($loggedIn = loggedIn()) {
    header("location: profiili.php");
    exit;
    }
$title = 'Kirjautuminen';
$css = 'login.css';
/* Lomakkeen kentät, nimet samat kuin users-taulussa. */
$kentat = ['email','password','rememberme'];
$kentat_suomi = ['sähköpostiosoite','salasana','muista minut'];
$pakolliset = ['email','password'];
include "virheilmoitukset.php";
include 'kasittelija_login.php';
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include "header.php"; 
?>
<div class="container">

<form method="post" autocomplete="on" class="mb-3 needs-validation" novalidate>    
<fieldset>
<legend>Kirjautuminen</legend>

<div class="row">
<label for="email" class="col-sm-4 form-label">Sähköpostiosoite</label>
<div class="col-sm-8">
<input type="email" class="mb-1 form-control <?= is_invalid('email'); ?>" name="email" id="email" 
       placeholder="etunimi.sukunimi@palvelu.fi" value="<?= arvo("email"); ?>"
       pattern="<?= pattern('email'); ?>" autofocus required>
<div class="invalid-feedback">
<?= $errors['email'] ?? ""; ?>    
</div>
</div>
</div>

<div class="row">
<label for="password" class="col-sm-4 form-label">Salasana</label>
<div class="col-sm-8">
<input type="password" class="mb-1 form-control <?= is_invalid('password'); ?>" name="password" id="password" 
       placeholder="salasana" pattern="<?= pattern('password'); ?>" required>
<div class="invalid-feedback">
<?= $errors['password'] ?? ""; ?>    
</div>
</div>
</div>


<div class="row offset-sm-4">
<div class="form-check ms-2">
<input class="form-check-input" type="checkbox" value="checked" <?= nayta_rememberme('rememberme'); ?> id="rememberme" name="rememberme"/>
<label class="form-check-label" for="rememberme">Muista minut</label>
<div class="invalid-feedback">
<?= $errors['rememberme'] ?? ""; ?>    
</div>
</div>
</div>


<div class="div-button">
<input type="submit" name="painike" class="offset-sm-4 mt-3 mb-2 btn btn-primary" value="Kirjaudu">  
</div>

<div class="row offset-sm-4">
<a href="forgotpassword.php">Unohtuiko salasana</a>
</div>

<div class="row offset-sm-4">
<!--<p class="mt-2 pt-1 mb-0">Käyttäjätunnus puuttuu?-->
<a href="rekisteroitymislomake.php">Rekisteröidy</a>
</div>

</fieldset>
</form>

<?php
/*if (isset($_POST['painike']) && $errors){
    echo '<div class="ilmoitukset mt-3">';
    foreach ($errors as $kentta => $arvo) {
      echo "<div class=\"alert alert-danger\" role=\"alert\">$arvo</div>";   
      }
    echo "</div>";
    }*/
?>

<div id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

</div>
<?php
include('footer.html');
?>