<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/comments.tpl.php');
    require_once(__DIR__ . '/../database/users.class.php');
    require_once(__DIR__ . '/../database/department.class.php');
    require_once(__DIR__ . '/../database/status.class.php');
    require_once(__DIR__ . '/../database/priority.class.php');
    require_once(__DIR__ . '/../database/hashtag.class.php');
?>

<?php function drawTable(PDO $db){
?>
    <section class="edit-grid tickets">
        <header id="main-content-header">
            Edit
        </header>
 
        <article>    
            <section id="departments">
                <header> Departments </header>
                <?php drawNewDepts($db) ?>
            </section>
            <section id="priorities">
                <header> Priorities </header>
                <?php drawNewPriorities($db) ?>
            </section>
            <section id="status">
                <header> Status </header>
                <?php drawNewStatus($db) ?>
            </section>
            <section id="tags">
                <header> Hashtags </header>
                <?php drawNewHashtags($db) ?>
            </section>
        </article>
    </section>
<?php } ?>


<?php function drawNewDepts(PDO $db){ 

    $depts = Department::getDepartments($db);
    ?>

    <form class = "edit" action="../actions/action_new_dept.php" method="post">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <input type="text" name="new_dept" placeholder="New Department">
        <button type="submit"> Add</button>
    </form>

    <ul>
        <?php foreach($depts as $dept){ ?>
            <li data-id = "<?=$dept->id?>" data-class = "DEPT">
                <?= htmlentities($dept->name) ?>
                <button class="faq-remove-icon delete-icon"><img src="../docs/delete.svg" alt="Delete"></button>
            
            </li>
        <?php } ?>
    </ul>


<?php } ?>

<?php function drawNewStatus(PDO $db){ 

$statuses = Status::getStatuses($db);
?>

<form class="edit" action="../actions/action_new_status.php" method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">  
    <input type="text" name="new_status" placeholder="New Status">
    <button type="submit">Add </button>
</form>

<ul>
    <?php foreach($statuses as $status){ ?>
        <li data-id = "<?=$status->getId()?>" data-class = "STATUS">
            <?= htmlentities($status->getName()) ?>
            <button class="faq-remove-icon delete-icon"><img src="../docs/delete.svg" alt="Delete"></button>
        </li>
    <?php } ?>
</ul>


<?php } ?>

<?php function drawNewPriorities(PDO $db){ 

$priorities = Priority::getPriorities($db);
?>

<form class="edit" action="../actions/action_new_priorities.php" method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <input type="text" name="new_priority" placeholder="New Priority">
    <button type="submit"> Add </button>
</form>

<ul>
    <?php foreach($priorities as $prior){ ?>
        <li data-id = "<?=$prior->getId()?>" data-class = "PRIOR">     
            <?= htmlentities($prior->getName()) ?>
            <button class="faq-remove-icon delete-icon"><img src="../docs/delete.svg" alt="Delete"></button>
        </li>
    <?php } ?>
</ul>


<?php } ?>

<?php function drawNewHashtags(PDO $db){ 

$hashtags = Tag::getAllTags($db);
?>

<form class="edit" action="../actions/action_new_tag.php" method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <input type="text" name="new_tags" placeholder="New Tag">
    <button type="submit"> Add </button>
</form>

<ul>
    <?php foreach($hashtags as $tag){ ?>
        <li data-id = "<?=$tag->getName()?>" data-class = "TAG">     
            <?= htmlentities($tag->getName()) ?>
            <button class="faq-remove-icon delete-icon"><img src="../docs/delete.svg" alt="Delete"></button>
        </li>
    <?php } ?>

</ul>


<?php } ?>
