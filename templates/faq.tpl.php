<?php 
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../templates/ticket.tpl.php');
    require_once(__DIR__ . '/../database/users.class.php');
?>
<?php 
function drawFAQ(FAQ $faq,string $user_role){
    
?>
    <article id="faq" class="faq-grid">
        <div class="faq-icon">
            <img src="../docs/question-mark.svg" alt="FAQ">
        </div>
        <div class="faq-text">
            <h3><?=htmlentities($faq->question)?></h3>
            <h4><?=htmlentities($faq->answer)?></h4>
        </div>
        <?php if ($user_role === "admin" || $user_role === "agent") { ?>
            <div class="faq-remove-icon">
                <form method="post" action="../actions/action_remove_faq.php" >
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>" />
                <input type="hidden" name="faq_id" value="<?= htmlentities((string) $faq->faq_id) ?>" />
                <button class="delete-icon"><img src="../docs/delete.svg" alt="Delete"></button>
                </form>
            </div>
        <?php } ?>
    </article>
<?php 
}
?>





<?php function drawFAQs(array $faqs,string $user_role){
    
?>
    <article class="faqs-grid tickets">
        <header id="main-content-header">
            <p>Frequently Asked Questions</p>
        </header>

        <section id="main-content-header-1">
            <p><?= count($faqs) ?> FAQs</p>
        
        </section>
         <?php if ($user_role === "admin" || $user_role === "agent") { ?>
        <button id="create-faq-form-toggle" class="button create-faq">Create</button>
<div class="create-faq-form-container hidden">
  <div class="create-faq-form-toggle">
    <button id="create-hide-button" class="button hide-faq">Hide</button>
  </div>
  <div class="textbox-container-create-faq hidden">
    <form id="faq-form" method="post" action="../actions/action_add_faq.php">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
      <label for="question">Question:</label>
      <textarea id="question" name="question" class="fixed-textbox" rows="2" cols="50" placeholder="Write your question here"></textarea>
      <label for="answer">Answer:</label>
      <textarea id="answer" name="answer" class="fixed-textbox" rows="2" cols="50" placeholder="Write your answer here"></textarea>
      <div class="button-container-faq">
        <button type="submit" class="button-faq send-button">Send</button>
        <button type="reset" class="button-faq cancel-button">Cancel</button>
      </div>
    </form>
  </div>
</div>

<?php }?>


        <section id="main-content" class="faqs">
                <?php foreach($faqs as $faq){ ?>
                    <?= drawFAQ($faq,$user_role) ?>
                <?php } ?>
        </section>


    </article>
<?php 
}
 ?>

