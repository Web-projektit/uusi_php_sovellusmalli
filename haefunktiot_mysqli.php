<?php
/* 
 * PHP-tiedosto, joka hakee kaikki PHP-tiedostot annetusta hakemistosta ja sen alihakemistoista.
 * Tiedostot käydään läpi ja etsitään PHP-kirjastofunktiot ja mysqli-metodikutsut.
 * Löydetyt funktiot ja metodikutsut tallennetaan phpfunctions.txt-tiedostoon.
 * 
 * PHP-versio 8.2.12
 * 
 * @category  PHP
 * @package   PHP_functions
 * @license   PHP
 * @link      PHP
 */
include 'debuggeri.php';
echo "Haetaan PHP-funktiot ja mysqli-metodikutsut tiedostoista...<br>";

/**
 * Hakee kaikki PHP-tiedostot annetusta hakemistosta ja sen alihakemistoista.
 *
 * @param string $dir Hakemiston polku.
 * @return array Taulukko, joka sisältää kaikkien PHP-tiedostojen polut.
 */
function getPhpFiles($dir) {
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $files = [];
    
    foreach ($rii as $file) {
        if ($file->isDir()) { 
            continue;
        }
        $filePath = $file->getPathname();
        if (pathinfo($filePath, PATHINFO_EXTENSION) === 'php' &&
            strpos($filePath, 'vendor') === false &&
            strpos($filePath, 'fake') === false &&
            strpos($filePath, ' copy') === false &&
            strpos($filePath, 'haefunktiot') === false &&
            strpos($filePath, 'Exception') === false &&
            strpos($filePath, 'SMTP') === false &&
            strpos($filePath, 'PHPMailer') === false) {
            $files[] = $filePath;
        }
    }
    return $files;
}


function getPhpFunctions($file) {
    $content = file_get_contents($file);
    preg_match_all('/\b(\w+)\s*\(/', $content, $matches);
    return array_unique($matches[1]);
}


function filterPhpLibraryFunctions($functions) {
    $internalFunctions = get_defined_functions()['internal'];
    return array_intersect($functions, $internalFunctions);
}

/**
 * Hakee kaikki $yhteys-> ja $result-> alkuiset metodikutsut annetusta tiedostosta.
 *
 * @param string $file Tiedoston polku.
 * @return array Taulukko, joka sisältää kaikki löydetyt metodikutsut.
 */
function getConnectionAndResultMethods($file) {
    $content = file_get_contents($file);
    preg_match_all('/\$(yhteys|result|stmt)->(\w+)\s*\([^)]*\)/', $content, $matches);
    return array_unique($matches[0]);
}

$dir = __DIR__; // Korvaa tämä polulla PHP-sovellusmallin hakemistoon
// echo "Aloitus, hakemisto: " . $dir . "\n";
// debuggeri("Aloitus, hakemisto: ".$dir);
$phpFiles = getPhpFiles($dir);
$allFunctions = [];
$connectionAndResultMethods = [];

// Tyhjennetään phpfunctions.txt-tiedosto
file_put_contents('phpfunctions.txt', '');

foreach ($phpFiles as $file) {
    //echo "Tiedosto: " . $file . "<br>";
    //debuggeri("Tiedosto: ".$file);
    $functions = getPhpFunctions($file);
    $phpLibraryFunctions = filterPhpLibraryFunctions($functions);
    foreach ($phpLibraryFunctions as $function) {
        $allFunctions[$function][] = basename($file);
        }

    $methods = getConnectionAndResultMethods($file);
    foreach ($methods as $method) {
        $connectionAndResultMethods[$method][] = basename($file);
        }
    }   

ksort($allFunctions);    
ksort($connectionAndResultMethods);
debuggeri($allFunctions);
$output = "Käytetyt PHP-kirjastofunktiot ja tiedostot:\n";
$functionNumber = 1;
foreach ($allFunctions as $function => $files) {
    $output .= $functionNumber . ". " . $function . ":\n";
    foreach ($files as $file) {
        $output .= "   - " . $file . "\n"; // Käytetään non-breaking space -merkkejä
    }
    $functionNumber++;
    }
//debuggeri("OUTPUT: ".$output);
//$output .= "\nKäytetyt mysqli-metodikutsut ja tiedostot:\n";
$output.= "\nKäytetyt \$yhteys->,\$result-> ja \$stmt-> metodikutsut ja tiedostot:\n";
$functionNumber = 1;
foreach ($connectionAndResultMethods as $method => $files) {
    $output .= $functionNumber . ". " . $method . ":\n";
    foreach ($files as $file) {
        $output.= "   - " . $file . "\n"; // Käytetään non-breaking space -merkkejä
        }
    $functionNumber++;
}

file_put_contents('phpfunctions.txt', $output);