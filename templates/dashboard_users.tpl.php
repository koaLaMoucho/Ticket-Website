<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/comments.tpl.php');
    require_once(__DIR__ . '/../database/users.class.php');
    require_once(__DIR__ . '/../database/department.class.php');
?>

<?php function drawUsers(array $users, PDO $db, Session $session){
?>
    <section class="faqs-grid tickets">
        <header id="main-content-header">
            All Users
        </header>

        <p id="main-content-header-1"><?= count($users) ?> users</p>

        <div id="main-content-header-2">
            <p>USER</p>
            <p id="role-deparment">ROLE</p>
            <p>DEPARTMENT</p>
        </div>
            
        <div id="main-content">
            <?php foreach($users as $user){ ?>
                <?php $department = User::getDepartmentByID($db, $user->user_id);
                if (is_null($department)){
                    $department = "-";
                } 
                else{
                    $department = $department;
                }?>
                <?php drawUser($db, $user, htmlentities($user->name), htmlentities($user->email), htmlentities($user->role), $department, $session); ?>
            <?php } ?>

        
        </div>
    </section>
<?php } ?>


<?php function drawUser(PDO $db, User $user, string $name, string $email, string $role, string $department, Session $session){

$user_pic_id = urlencode((string)(($user->user_id) % 30 + 1));
$user_pic = "../docs/users/$user_pic_id.jpg";

$depts = Department ::getOtherDepartments($db, $department);
if ($role == "client"){
    $other1 = "agent";
    $other2 = "admin";
}
else if ($role == "agent"){
    $other1 = "client";
    $other2 = "admin";
}
else if ($role == "admin"){
    $other1 = "client";
    $other2 = "agent";
}

?>

<article class="ticket">
    <header>
        <img src=<?= $user_pic; ?> alt="User" width="40px">
        <div>
            <p><?= $name ?></p>
            <p class="email"><?= $email ?></p>
        <div>
    </header>

    <form class = "edit" action="../actions/action_change_role.php" method="post">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <input type="hidden" name="user_id" value="<?= $user->user_id ?>">

        <select name="role" id="role">
            <option><?= $role ?></option>
            <option><?= $other1 ?></option>
            <option><?= $other2 ?></option>
        </select>
        <select name="department" class="department">
            <option><?= $department ?></option>

            <?php foreach($depts as $dept){ ?>
                <option><?= htmlentities($dept->name) ?></option>
            <?php } ?>
            <?php if($department !== "-"){ ?>
                <option>-</option>
            <?php } ?>

        </select>
        <button type="submit">Save</button>
    </form>

</article>

<?php } ?>
