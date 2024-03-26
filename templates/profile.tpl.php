<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/users.class.php');
?>

<?php function drawEditProfile(PDO $db, string $username, string $name, string $email, int $id) { 

    $user_pic_id = ($id)%30 +1;
    $user_pic = "../docs/users/$user_pic_id.jpg";
    if (!file_exists($user_pic))
    $user_pic = "../docs/user.png";
    $role = User::getUserRoleByID($db, $id);
    ?>

<section id="edit-user" class="actions default-actions-grid">
    <header id="actions-header">
        User Info
    </header>
    <article class="edit-profile" data-id=<?=$id?> >

        <img src=<?= $user_pic; ?> alt="User">

        <?php
        if ($role !== "client"){
            $dept = User::getDepartmentByID($db, $id);
            if ($dept===null) $dept = "Unassigned";
        ?>
        <form class = "edit">
            <label>Department</label>
            <p><?=htmlentities($dept)?>
        </form>
        <?php } ?>

        <form class="edit">
            <label>Username: </label>
            <p><?= htmlentities($username); ?></p>
            <input type ="text" placeholder="<?= htmlentities($username); ?>" data-field="username">
            <button class="edit">Edit</button>
            <button class="save">Save</button>
        </form>
        <!-- <form id="editUsername" style="display:none;">
            <input type="text" id="username" name="username" value="<?= htmlentities($_SESSION['name']); ?>"> 
        </form> -->


        <form class="edit">
            <label>Name:</label>
            <p><?= htmlentities($name); ?></p>
            <input type ="text" placeholder="<?=  htmlentities($name); ?>" data-field="name">
            <button class="edit">Edit</button>
            <button class="save">Save</button>
        </form>
        <!-- <form id="editName" style="display:none;">
            <input type="text" id="name" name="name" value="<?= htmlentities($name); ?>"> 
        </form> -->

        <form class="edit">
            <label>Email:</label>
            <p><?php echo $email; ?></p>
            <input type ="text" placeholder="<?= htmlentities($email); ?>"  data-field="email">
            <button class="edit">Edit</button>
            <button class="save">Save</button>
        </form>
        <!-- <form id="editUsername" style="display:none;">
            <input type="text" id="username" name="username" value="<?= htmlentities($email); ?>"> 
        </form> -->


        <form id="password-ready">
            <label>Password:</label>
            <input type ="password" placeholder="New Password"  data-field="password">
            <input type ="password" placeholder="Confirm Password"  data-field="confirm_password">
            <button class="edit">Edit</button>
            <button class="save">Save</button>
        </form>
        <!-- <form id="editName" style="display:none;">
            <input type="text" id="name" name="name" value="<?= htmlentities($_SESSION['name']); ?>"> 
        </form> -->
    </article>
</section>

<?php } ?>