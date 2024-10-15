<?php 
include "asetukset.php";
include "debuggeri.php";
include "db.php";
include "rememberme.php";
debuggeri(__FILE__);
$loggedIn = secure_page();
$title = 'Profiili';
$css = 'profiili.css';

$tietokanta = "neilikka";
/*$kentat = ['firstname','lastname','email','password','password2'];
$kentat_suomi = ['etunimi','sukunimi','sähköpostiosoite','salasana','salasana'];
$pakolliset = ['firstname','lastname','email','password','password2'];*/
$kentat_tiedosto = ['image'];

$kentat = ['firstname','current_image'];
$kentat_suomi = ['etunimi','kuva'];
$pakolliset = ['firstname'];
include "virheilmoitukset.php";
include "kasittelija_profiili.php";
echo "<script>const virheilmoitukset = $virheilmoitukset_json</script>";
include "header.php"; 
/* Huom. current_image sisältää profiilikuvatiedoston 
   alkuperäisen nimen. Tässä profiilia tallennettaessa vanha
   kuva poistetaan ja uusi tallennetaan tilalle. */
?>
<div class="container">
<!-- Kuva ja perustiedot 
<img src="https://cdn.pixabay.com/photo/2019/07/02/03/10/highland-cattle-4311375_1280.jpg" alt="Profiilikuva" class="profile-image">
-->

<form method="post" class="mb-3 col-md-9 needs-validation" enctype="multipart/form-data" novalidate >
<fieldset>
<legend>Profiili</legend>

<div class="row">
<label for="firstname" class="col-sm-4 form-label">Etunimi</label>
<div class="col-sm-8">
<input pattern="<?= pattern("firstname"); ?>" type="text" class="mb-1 form-control <?= is_invalid('firstname'); ?>" name="firstname" id="firstname" 
       placeholder="Etunimi" value="<?= arvo("firstname"); ?>" 
       required autofocus> 
<div class="invalid-feedback">
<?= $errors['firstname'] ?? ""; ?>    
</div>
</div>    
</div>


<input type="hidden" id="current_image" name="current_image" value="<?= $current_image; ?>"></input>
<div class="row mb-sm-1">
<label for="image" class="form-label mb-0 col-sm-4">Kuva</label>
<div class="col-sm-8">
<input id="image" name="image" type="file" accept="image/*" pattern="<?= pattern('image'); ?>" class="form-control <?= is_invalid('image'); ?>" placeholder="kuva"></input>
<div class="invalid-feedback">
<?= $errors['image'] ?? ""; ?>
</div>
<div class="previewDiv mt-1 col-sm-8" id="previewDiv">
<img class="previewImage" src="<?= $kuvatiedosto; ?>" id="previewImage" width="" height="">
<button type="button" class="btn btn-outline-secondary btn-sm float-end mt-1" onclick="tyhjennaKuva('image')">Poista</button>
</div>
</div>
</div>

</fieldset>
<input type="submit" name="painike" class="btn btn-primary" value="Tallenna"></input>   

<div class="info-section">
    <div class="info-title">Nimi:</div>
    <div>Matti Meikäläinen</div>
</div>
<div class="info-section">
    <div class="info-title">Ammatti:</div>
    <div>Ohjelmistokehittäjä</div>
</div>
<!-- Yhteystiedot -->
<div class="info-section">
    <div class="info-title">Yhteystiedot:</div>
    <div>Email: matti.meikäläinen@example.com</div>
    <div>Puhelin: 040-1234567</div>
</div>
<!-- Harrastukset -->
<div class="info-section">
    <div class="info-title">Harrastukset:</div>
    <ul class="hobbies-list">
    <li>Koodaus</li>
    <li>Valokuvaus</li>
    <li>Matkustelu</li>
    <li>Lukeminen</li>
    </ul>
</div>


<div  id="ilmoitukset" class="alert alert-<?= $success ;?> alert-dismissible fade show <?= $display ?? ""; ?>" role="alert">
<p><?= $message; ?></p>
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

</div>
<?php include "footer.php"; ?>