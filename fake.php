<?php
/* 
Composerin asennus: https://getcomposer.org/download/
1 hae: Composer-Setup.exe ja asenna composer.
2 komentoriviltä (terminaali): composer require fakerphp/faker
3 php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
4 php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else 
{ echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"   
5 php composer-setup.php  
6 php -r "unlink('composer-setup.php');"
*/
$title = 'Fake';
//$css = 'fake.css';
include "header.php"; 
require_once 'vendor/autoload.php';
$faker = Faker\Factory::create('fi_FI');
$countryCode = "358";
$replace = ['+',' ','-','(',')'];
$replaceWith = ''; 
//$num_records = 25;
$num_records = 0;


function phone($phone) {
    $countryCode = $GLOBALS['countryCode'];
    $replace = $GLOBALS['replace'];
    $replaceWith = $GLOBALS['replaceWith'];
    $phone = str_replace($replace,$replaceWith,$phone);
    $p = ltrim($phone,'0');
    return ($p === $phone) ? $phone : $countryCode.$p;
    }

$kentatArr = ['firstname','lastname','email','is_active']; 
$kentat = implode(",",$kentatArr);
[$firstname,$lastname,$email,$is_active] = $kentatArr;
$stmt = $yhteys->prepare("INSERT INTO users ($kentat) VALUES (?, ?, ?, ?)");
if (!$stmt) die("Preparation failed: " . $conn->error);
$stmt->bind_param("ssss", $firstname, $lastname, $email, $is_active);
for ($i = 0; $i < $num_records; $i++) {
    $firstname = $faker->firstname;
    $lastname = $faker->lastname;
    $email = $faker->email;
    $is_active = (string) mt_rand(0, 1);
    if (!$stmt->execute()) {
        echo "Execution failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }
$stmt->close();

$query = "SELECT lastname,firstname,email,is_active,role FROM users ORDER BY lastname,firstname";
$result = $yhteys->query($query);
$rows = $result->num_rows;
?>
<div class="container"> 

<?php
/*
echo $faker->name.'<br>';
echo $faker->firstname.'<br>';
echo $faker->lastname.'<br>';
echo $faker->email.'<br>';
echo phone($faker->phoneNumber).'<br>';
echo $faker->streetAddress.'<br>';
echo $faker->postcode.'<br>';
echo $faker->city.'<br>';
echo $faker->text.'<br>'; 
*/
echo "<table class='table table-striped table-hover table-sm'>";
echo "<thead class='thead-dark'><tr><th>Etunimi</th><th>Sukunimi</th><th>Sähköposti</th><th>Aktiivinen</th><th>Rooli</th></tr></thead>"; 
for ($j = 0; $j < $rows; ++$j) {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);
    echo "<tr>";
    for ($k = 0; $k < 5; ++$k) echo "<td>$row[$k]</td>";
    echo "</tr>";
    }
echo "</table>";
echo $num_records . " fake user records inserted into database.<br> ";
echo "Total number of records in database: $rows<br>";
?>
</div>
<?php include "footer.html"; ?>