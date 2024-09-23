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
            strpos($filePath, 'fake') === false &&
            strpos($filePath, 'haefunktiot') === false &&
            strpos($filePath, ' copy') === false &&
            strpos($filePath, 'Exception') === false &&
            strpos($filePath, 'SMTP') === false &&
            strpos($filePath, 'PHPMailer') === false) {
            $files[] = $filePath;
        }
    }
    
    return $files;
}

/**
 * Hakee kaikki PHP-kirjastofunktiot annetusta tiedostosta.
 *
 * @param string $file Tiedoston polku.
 * @return array Taulukko, joka sisältää kaikki löydetyt PHP-kirjastofunktiot.
 */
function getPhpFunctions($file) {
    $content = file_get_contents($file);
    preg_match_all('/\b(\w+)\s*\(/', $content, $matches);
    return array_unique($matches[1]);
}

/**
 * Suodattaa pois käyttäjän määrittelemät funktiot ja palauttaa vain PHP-kirjastofunktiot.
 *
 * @param array $functions Taulukko, joka sisältää kaikki löydetyt funktiot.
 * @return array Taulukko, joka sisältää vain PHP-kirjastofunktiot.
 */
function filterPhpLibraryFunctions($functions) {
    $internalFunctions = get_defined_functions()['internal'];
    return array_intersect($functions, $internalFunctions);
}

$dir = __DIR__; // Korvaa tämä polulla PHP-sovellusmallin hakemistoon
$phpFiles = getPhpFiles($dir);
$allFunctions = [];
file_put_contents('phpfunctions.txt', '');

foreach ($phpFiles as $file) {
    $functions = getPhpFunctions($file);
    $phpLibraryFunctions = filterPhpLibraryFunctions($functions);
    foreach ($phpLibraryFunctions as $function) {
        $allFunctions[$function][] = basename($file);
    }
}

ksort($allFunctions);

echo "Käytetyt PHP-kirjastofunktiot ja tiedostot:<br>\n";
$functionNumber = 1;
foreach ($allFunctions as $function => $files) {
    $output = $functionNumber . ". " . $function . ":\n";
    echo $functionNumber . ". " . $function . ":<br>\n";
    foreach ($files as $file) {
        $output .= "   - " . $file . "\n";
        echo "&nbsp;&nbsp;&nbsp;&nbsp;- " . $file . "<br>\n";
        }
    file_put_contents("phpfunctions.txt", $output, FILE_APPEND);    
    $functionNumber++;
}