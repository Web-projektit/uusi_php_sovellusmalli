<?php
/* ALOITUS */   
$user_id = $_SESSION['user_id'] ?? 0;
$display = $_SESSION["display"] ?? "d-none";
$message = $_SESSION["message"] ?? "";
$success = $_SESSION["success"] ?? "success";
$kuvatiedosto = $_SESSION['kuvatiedosto'] ?? "";
$current_image = $_SESSION['current_image'] ?? "";
unset($_SESSION['display']);
unset($_SESSION['message']);
unset($_SESSION['success']);
unset($_SESSION['current_image']);
unset($_SESSION['kuvatiedosto']); 
debuggeri("user_id:$user_id", "current_image:$current_image", "kuvatiedosto:$kuvatiedosto");

function poista_tunniste($image){
// Poistaa alaviivan ja sen perässä olevan tunnisteen ennen tiedostotyyppiä
return preg_replace('/_[^_]+\.(jpg|jpeg|png|gif)$/i', '.$1', $image);
}

function lisaa_tunniste($image,$tunniste){
// return preg_replace('/\.(jpg|jpeg|png|gif)$/i', "_$tunniste.$1", $image);
return preg_replace('/(\.[^.]+)$/', "_$tunniste$1", $image);     
}

function poista_kuva($imagefile){
    $kuvatiedosto = PROFIILIKUVAKANSIO."/".$imagefile;
    if (file_exists($kuvatiedosto)) unlink($kuvatiedosto);
    }

function vanha_kuva($user_id){
    $query = "SELECT image FROM users WHERE id = $user_id";
    $result = mysqli_my_query($query)[0];
    return $result ? lisaa_tunniste($result->fetch_row()[0],$user_id) : "";
    }

function poista_vanha_kuva($imagefile,$user_id){
    $vanha_imagefile = vanha_kuva($user_id);
    debuggeri("vanha_imagefile:$vanha_imagefile");
    if ($vanha_imagefile && $vanha_imagefile <> $imagefile) poista_kuva($vanha_imagefile);
    }    

function hae_kuva($kentat_tiedosto,$user_id){   
/* Huom. foreach-silmukka on tässä malliksi, ei valmis.
   Nimen tarkistukseen ei ole tässä koodia. 
   Vaihtoehtona satunnaisluvulle tunnisteena on tässä käyttäjän id.
   Vanha kuva poistetaan, jos uusi kuva on eri niminen tai 
   tai sitä ei ole lomakkeella. 
*/
     
    $allowed_images = $GLOBALS['allowed_images'];
    $virhe = false;   
    $image = "";
    $imagefile = "";
    foreach ($kentat_tiedosto as $kentta){
        debuggeri("tiedostokentta:$kentta"); 
        if (!isset($_FILES[$kentta])) continue;    
        if (is_uploaded_file($_FILES[$kentta]['tmp_name'])) {
        $maxsize = PROFIILIKUVAKOKO;
        $temp_file = $_FILES[$kentta]["tmp_name"];       
        $filesize = $_FILES[$kentta]['size'];
        $image = $_FILES[$kentta]['name'];
        $pathinfo = pathinfo($_FILES[$kentta]["name"]);
        $filetype = strtolower($pathinfo['extension']);
        $imagefile = lisaa_tunniste($image,$user_id);
        $target_file = PROFIILIKUVAKANSIO."/$imagefile";
        debuggeri("tiedosto:$temp_file, kohde:$target_file");
        debuggeri("tiedostotyyppi:$filetype, koko:$filesize");
        /* Check if image file is a actual image or fake image */
        if (!$check = getimagesize($temp_file)) $virhe = "Kuva ei kelpaa.";
        elseif (!in_array($filetype,$allowed_images)) $virhe = "Väärä tiedostotyyppi.";
        elseif ($filesize > $maxsize) $virhe = "Kuvan koon tulee olla korkeintaan 5 MB.";
        debuggeri("Imagefile $imagefile,mime: {$check['mime']}, $filetype, $filesize tavua");
        if (!$virhe){
            /* Huom. Tämä korvaa aikaisemman samannimisen tiedoston */
            if (!move_uploaded_file($temp_file,$target_file)) 
                $virhe = "Kuvan tallennus ei onnistunut.";
            } 
        }
      }
    return [$imagefile,$image,$virhe];
    }


if (isset($_POST['painike'])){
    debuggeri("kasittelija_profiili,POST:".var_export($_POST,true));
    [$errors,$values] = validointi($kentat);  
    extract($values);
    /* Huom. myös $current_image saa lomakkeelta arvon */ 
    
    if (empty($errors)){
        /* Huom. Jos kuvaa ei valita lomakkeelta, 
           $_FILES['image'] on tyhjä, joten sekä
           image että virhe jäävät tyhjiksi, ja
           käytetään $_POST['current_image']-arvoa. 
        */
        [$imagefile,$image,$virhe] = hae_kuva($kentat_tiedosto,$user_id);
        debuggeri("imagefile:$imagefile");
        if ($virhe) $errors['image'] = $virhe;
        elseif (!$image && $current_image) {
            $image = "'$current_image'";
            $imagefile = lisaa_tunniste($current_image,$user_id);
            $kuvatiedosto = PROFIILIKUVAKANSIO."/".$imagefile;
            }
        elseif ($image) {
            poista_vanha_kuva($imagefile,$user_id);
            $kuvatiedosto = PROFIILIKUVAKANSIO."/".$imagefile;
            $current_image = $image;
            $image = "'$image'";
            }
        else {
            /* Huom. image ja current_image ovat tyhjiä. */
            poista_vanha_kuva($imagefile,$user_id);
            $kuvatiedosto = "";
            $image = "NULL";
            }
        }   
 
    debuggeri("kasittelija_profiili.php, virheet:".var_export($errors,true));        
    if (empty($errors)) {
        $query = "UPDATE users SET firstname = '$firstname',image = $image WHERE id = $user_id";
        debuggeri($query);
        [$result,$errno,$error] = mysqli_my_query($query);
        $muutos = $yhteys->affected_rows;
        debuggeri("muutettiin:$muutos riviä.");
        if ($muutos > 0) {
            $_SESSION['message'] = "Tiedot on tallennettu.";
            $_SESSION['success'] = "success";
            $_SESSION['display'] = "d-block";
            }
        else {
            $_SESSION['message'] = "Tietoja ei muutettu.";
            $_SESSION['success'] = "info";
            $_SESSION['display'] = "d-block";
            }
        $_SESSION['current_image'] = $current_image;
        $_SESSION['kuvatiedosto'] = $kuvatiedosto;
        /* Palataan profiilisivulle ilman post-arvoja,
           jotta sivun virkistäminen ei tallentaisi 
           profiilitietoja uudestaan. Ilmoitus näkyy, koska
           se on tallennettu sessioon.
        */
        header("Location: profiili.php");
        exit;
        }
    }

elseif ($user_id) {
   $query = "SELECT * FROM users WHERE id = $user_id";
   debuggeri($query);
   $result = $yhteys->query($query);
   if (!$result) die("Tietokantayhteys ei toimi: ".mysqli_error($connection));
   if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
      $image = $row['image'];
      if ($image) {
         $imagefile = lisaa_tunniste($image,$user_id);
         $kuvatiedosto = PROFIILIKUVAKANSIO."/".$imagefile;
         if (!file_exists($kuvatiedosto)) {
            $kuvatiedosto = "";
            $message = "Kuvaa ei löydy.";
            $success = "danger";
            $display = "d-block";
            }
          else {
            /* Jos kuva säilyy samana profiililomakkeella, luodaan
               uusi tunniste ja tallennetaan kuva entisen sijaan sillä. 
            */
            $current_image = $image;
            }    
          }
        }
    else {
        /* Käyttäjän tietoja ei löydy */
        $message = "Tietoja ei löydy.";
        $success = "danger";
        $display = "d-block";
      }
   }
else {
   /* Käyttäjää ei ole määritetty */
   $message =  "Tietoja ei löydy.";
   $success = "danger";
   $display = "d-block";
   }
debuggeri("current_image:$current_image");
debuggeri("kuvatiedosto:$kuvatiedosto");
//ob_end_flush();

?>
