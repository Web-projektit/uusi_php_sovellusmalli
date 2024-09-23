Uusi version php_sovellusmallista. Sisältää pieniä muutoksia. Huom. git.config user.name ja git.config user.email ja vanhan Deploymentin poistaminen Azuressa, jos väärät Github-käyttäjätunnukset ovat jääneet voimaan.
Tässä on javascriptiä käyttävä navigointipalkki (header_js.php, navbar_js.css) ja css:ään perustuva navigointipalkki (header.php ja navbar.css), jossa on myös oma painike avatun navigointipalkin sulkemiseen.  Jälkimmäisen linkit perustuvat käyttäjän rooliin.

Molemmissa käytetään flexboxia. 

Käyttäjän rooli tallennetaan loggedIn -session-muuttujaan. Sillä voi olla arvo false,
rooli tai evästeeseen koodattu user id. 