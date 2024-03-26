<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

?>

<?php
function drawComment(Comment $comment) {
    
    $user_pic_id = urlencode((string)(($comment->user_id) % 30 + 1));
    $user_pic = "../docs/users/$user_pic_id.jpg";
    if (!file_exists($user_pic))
        $user_pic = "../docs/user.png";

    ?>
    <article class="comment">
        <div class="comment-row">
            <div class="comment-pic-container">
                <img src=<?= $user_pic; ?> alt="User" class="comment-pic">
            </div>
            <div class="comment-column">
                <p class="comment-username"><?=htmlentities($comment->username)?></p>
                <p class="comment-content"><?=htmlentities($comment->content)?></p>
                <p class="comment-date"><?=$comment->created_at->format('d M Y')?></p>
            </div>
           
        </div>
    </article>
    <?php 
}

?> 



<?php
function drawComments(array $comments, int $ticket_id, string $ticket_status, array $FAQs) {
    $session = new Session();
    $id = $session->getId();
   
    
?>
    <article id="comments">
        
        <p class="comment-count"><?= count($comments) ?> comments</p>
        <section class="comment-section">
            <?php foreach($comments as $comment) { ?>
                <?=drawComment($comment,$id)?>
            <?php } ?>
        </section>
        <?php if ($ticket_status == "Closed"): ?>
    <!-- <div class="textbox-container" style="display: none;"> -->
    <div class="textbox-container d-none">
<?php else: ?>
    <div class="textbox-container">
<?php endif; ?>
    <form id="comment-form" method="post" action="../actions/action_ticket_comment.php">
        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
        <input type="hidden" name="ticket_id" value="<?= htmlentities((string) $ticket_id) ?>" />
        <textarea name="content" class="fixed-textbox" rows="2" cols="50" placeholder="Write your comment here"></textarea>
        <div class="button-container">
            <button type="submit" class="button send-button">Send</button>
            <button type="button" class="button cancel-button">Cancel</button>
           
        </div>
    </form>
</div>


    </article>
<?php
}
?>


 


