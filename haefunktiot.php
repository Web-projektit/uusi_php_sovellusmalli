<?php
define('ROOT', __DIR__);

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
        if (pathinfo($file->getPathname(), PATHINFO_EXTENSION) === 'php') {
            $files[] = $file->getPathname();
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

$dir = ROOT; // Korvaa tämä polulla PHP-sovellusmallin hakemistoon
$phpFiles = getPhpFiles($dir);
$allFunctions = [];

foreach ($phpFiles as $file) {
    $functions = getPhpFunctions($file);
    $phpLibraryFunctions = filterPhpLibraryFunctions($functions);
    $allFunctions = array_merge($allFunctions, $phpLibraryFunctions);
}

$allFunctions = array_unique($allFunctions);
sort($allFunctions);

echo "Käytetyt PHP-kirjastofunktiot:\n";
foreach ($allFunctions as $function) {
    echo $function . "\n";
}