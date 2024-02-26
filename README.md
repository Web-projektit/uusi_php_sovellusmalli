Tässä on javascriptiä käyttävä navigointipalkki (header_js.php, navbar_js.css) ja css:ään perustuva navigointipalkki (header.php ja navbar.css), jossa on myös oma painike avatun navigointipalkin sulkemiseen.  Jälkimmäisen linkit perustuvat käyttäjän rooliin.

Molemmissa käytetään flexboxia. 

Alkuperäisessä (header_js.php, navbar_org.css) on käytössä float: left.

Käyttäjän rooli tallennetaan loggedIn -session-muuttujaan. Sillä voi olla arve false,
rooli tai evästeeseen koodattu user id. 