<?php 
include "asetukset.php";
include "db.php";
include "rememberme.php";
$loggedIn = secure_page('admin');
$title = 'Käyttäjähallinta';
$css = 'Kayttajat.css';
include "header.php";

function puhdista($arvo){
return htmlspecialchars($arvo, ENT_QUOTES, 'UTF-8');
}

if (isset($_POST['tallenna'])) {
    debuggeri($_POST);
    $values = "";
    $query = "INSERT INTO users (id,is_active,role) VALUES ";

    foreach($_POST['id'] as $id) {
        $is_active = in_array($id,$_POST['is_active']) ? 1 : 0;
        $role = in_array($id,$_POST['name']) ? 2 : 1;
        $values.= "($id,'$is_active',$role),";
        }
    $values = rtrim($values,',');
    $query.= $values;
    $query.= " ON DUPLICATE KEY UPDATE is_active = VALUES(is_active), role = VALUES(role)";
    debuggeri($query);
    $result = $yhteys->query($query);
}

$itemsPerPage = 25; 
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage; 

$query = "SELECT COUNT(*) FROM users";
$result = $yhteys->query($query);
[$totalItems] = $result->fetch_row();
$totalPages = ceil($totalItems / $itemsPerPage); 
if ($page < 1 || $page > $totalPages) $page = 1; 


$query = "SELECT users.id AS id,firstname,lastname,is_active,role,name 
          FROM users LEFT JOIN roles ON role = roles.id WHERE role <> 3 ORDER BY lastname,firstname
          LIMIT $offset, $itemsPerPage"; 
$result = $yhteys->query($query);
$users = ($result->num_rows) ? $result->fetch_all(MYSQLI_ASSOC) : [];
debuggeri($users);
?>
<div class="container">
<!-- Kuva ja perustiedot -->
<form action="kayttajat.php" method="post">    
<table class='table table-striped table-hover table-sm'>
<thead class='thead-dark'>
    <tr>
        <th>Sukunimi</th>
        <th>Etunimi</th>
        <th>Aktiivinen</th>
        <th>Pääkäyttäjä</th>
    </tr>
</thead>
    <?php foreach ($users as $key => $user) { ?> 
    <tr>
    <td class="d-none"><input type="hidden" name="id[]" value="<?= $user['id'] ?>"></td>    
    <td class="wide"><?= puhdista($user['lastname']) ?></td>
    <td><?= puhdista($user['firstname']) ?></td>
    <td class="narrow px-4"><input name="is_active[]" value=<?= puhdista($user['id']) ?> type="checkbox" <?php if ($user['is_active']) echo "checked" ?>></td>
    <td class="narrow px-4"><input name="name[]" value=<?= puhdista($user['id']) ?> type="checkbox" <?php if ($user['name'] == 'mainuser') echo "checked" ?>></td> 
    </tr>    
    <?php } ?> 
<tr><td colspan="4" class="p-3">
<input class="btn btn-primary float-end" type="submit" name="tallenna" value="Tallenna">  
</td></tr>
</table>
</form>
<div class="pagination">
    <a href="?page=1" class="<?= ($page == 1 ? 'disabled' : '') ?>">
    <i class="fas fa-angle-double-left fa-fw"></i></a>
    <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>">
    <i class="fas fa-angle-left fa-fw"></i></a>
    <?php else: ?>
    <span class="disabled">
    <i class="fas fa-angle-left fa-fw"></i>
    </span>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i == $page): ?>
    <span><?php echo $i; ?></span>
    <?php else: ?>
    <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
    <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo $page + 1; ?>">
    <i class="fas fa-angle-right fa-fw"></i></a>
    <?php else: ?>
    <span class="disabled">
    <i class="fas fa-angle-right fa-fw"></i>
    </span>
    <?php endif; ?>
    <a href="?page=<?php echo $totalPages; ?>" class="<?= ($page == $totalPages ? 'disabled' : '') ?>">
    <i class="fas fa-angle-double-right fa-fw"></i></a>
</div>

</div>
<?php include "footer.html"; ?>