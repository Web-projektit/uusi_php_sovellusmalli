<?php

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
            strpos($filePath, 'faker') === false &&
            strpos($filePath, 'Exception') === false &&
            strpos($filePath, 'SMTP') === false &&
            strpos($filePath, 'PHPMailer') === false) {
            $files[] = $filePath;
        }
    }
    
    return $files;
}

/**
 * Hakee kaikki $yhteys-> ja $result-> alkuiset metodikutsut annetusta tiedostosta.
 *
 * @param string $file Tiedoston polku.
 * @return array Taulukko, joka sisältää kaikki löydetyt metodikutsut.
 */
function getConnectionAndResultMethods($file) {
    $content = file_get_contents($file);
    preg_match_all('/\$(yhteys|result)->(\w+)\s*\([^)]*\)/', $content, $matches);
    return array_unique($matches[0]);
}

$dir = __DIR__; // Korvaa tämä polulla PHP-sovellusmallin hakemistoon
$phpFiles = getPhpFiles($dir);
$allFunctions = [];
$connectionAndResultMethods = [];

// Tyhjennetään phpfunctions.txt-tiedosto
file_put_contents('phpfunctions.txt', '');

foreach ($phpFiles as $file) {
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

$output = "Käytetyt \$yhteys-> ja \$result-> metodikutsut ja tiedostot:\n";
$functionNumber = 1;
$output = "Käytetyt PHP-kirjastofunktiot ja tiedostot:\n";
foreach ($allFunctions as $function => $files) {
    $output .= $functionNumber . ". " . $function . ":\n";
    foreach ($files as $file) {
        $output .= "   - " . $file . "\n"; // Käytetään non-breaking space -merkkejä
    }
    $functionNumber++;
    }

$output .= "\nKäytetyt mysqli-metodikutsut ja tiedostot:\n";
$functionNumber = 1;
    
foreach ($connectionAndResultMethods as $method => $files) {
    $output .= $functionNumber . ". " . $method . ":\n";
    foreach ($files as $file) {
        $output .= "   - " . $file . "\n"; // Käytetään non-breaking space -merkkejä
    }
    $functionNumber++;
}

file_put_contents('phpfunctions.txt', $output);