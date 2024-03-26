<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../templates/comments.tpl.php');
require_once(__DIR__ . '/../database/users.class.php');

?>

<?php function drawStatus(string $status): void
{

    $status_color = [
        'Open' => '#24a0ed',
        'Pending' => '#f5a623',
        'Closed' => '#2ecc71'
    ];
?>

    <div id="status" style="background-color: <?= $status_color[$status]; ?>">
        <p><?= $status ?></p>
    </div>

<?php } ?>

<?php function drawTicket(Ticket $ticket)
{

    $user_pic_id = urlencode((string)(($ticket->user_id) % 30 + 1));
    $user_pic = "../docs/users/$user_pic_id.jpg";

?>

    <a class="ticket no-link" href="../pages/ticket.php?id=<?= urlencode((string)$ticket->id) ?>" tabindex="0">

        <header>
            <img src=<?= $user_pic; ?> alt="User" width="40px">
            <div>
                <p><?= htmlentities($ticket->user) ?></p>
                <p class="email"><?= htmlentities($ticket->email) ?></p>
            </div>
        </header>
        <section class="ticket-tags">
            <p><?= htmlentities($ticket->title) ?></p>
            <p><?php foreach ($ticket->tags as $tag) { ?>
                    <?= htmlentities((string)$tag) ?>
                <?php } ?></p>
        </section>
        <p>
            <?php if ($ticket->assigned_to == null) { ?>
                Unassigned
            <?php } else { ?>
                <?= $ticket->assigned_to ?>
            <?php } ?>
        </p>


        <?php drawStatus($ticket->status) ?>


        <p><?= $ticket->last_update->format('d M Y') ?></p>

    </a>

<?php } ?>

<?php function drawTickets(array $tickets)
{
?>
    <section class="content default-content-grid tickets">
        <header id="main-content-header">
            All Tickets
        </header>

        <p id="main-content-header-1"><?= count($tickets) ?> tickets</p>

        <div id="main-content-header-2">
            <p>REQUESTER</p>
            <p>SUBJECT</p>
            <p>ASSIGNEE</p>
            <p>STATUS</p>
            <p>LAST UPDATE</p>
        </div>

        <div id="main-content">
            <?php foreach ($tickets as $ticket) { ?>
                <?= drawTicket($ticket) ?>
            <?php } ?>
        </div>
    </section>
<?php } ?>

<?php function drawTicketFilters(array $statuses, array $agents, array $departments, array $requesters, array $tags)
{ ?>
    <section class="actions default-actions-grid tickets-actions">
        <header id="actions-header">
            Filters
        </header>
        <article>
            <div class="filter-search">
                <!-- <label for="search">Search</label> -->
                <input type="text" name="search" id="searchTicket" placeholder="Search...">
            </div>
            <form id="actions-content">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="-1">All</option>
                        <?php foreach ($statuses as $status) { ?>
                            <option value="<?= htmlentities((string) $status->getId()) ?>"><?= htmlentities($status->getName()) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="assignee">Assignee</label>
                    <select name="assignee" id="assignee">
                        <option value="-1">All</option>
                        <option value="0">Unassigned</option>
                        <?php foreach ($agents as $agent) { ?>
                            <option value="<?= htmlentities((string) $agent->user_id) ?>"><?= htmlentities($agent->name) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="department">Department</label>
                    <select name="department" class="department">
                        <option value="-1">All</option>
                        <?php foreach ($departments as $department) { ?>
                            <option value="<?= htmlentities((string) $department->getID()) ?>"><?= htmlentities($department->getName()) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div>
                    <label for="requester">Requester</label>
                    <select name="requester" id="requester">
                        <option value="-1">All</option>
                        <?php foreach ($requesters as $requester) { ?>
                            <option value="<?= htmlentities((string) $requester->user_id) ?>"><?= htmlentities($requester->name) ?></option>
                        <?php } ?>
                    </select>
                </div>
                <!-- <div>
                    <label for="tags">Tags</label>
                    <select name="tags" id="tags">
                        <option value="all">All</option>
                        
                    </select>
                </div> -->
                <div>
                    <label for="tags">Tags</label>
                    <input list="autocomplete" type="text" name="tags" id="tags" placeholder="Tags...">
                    <datalist id="autocomplete" data-tag-autocomplete>
                    </datalist>
                </div>
                <div>
                    <!-- order by date -->
                    <label for="order">Order by Date</label>
                    <select name="order" id="order">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </form>
            <div class="action-buttons">
                <div class="button" href="../pages/new_ticket.php">
                    <a href="../pages/new_ticket.php">New Ticket</a>
                </div>
            </div>
        </article>
    </section>
<?php } ?>

<?php function drawNewTicketContent()
{ ?>

    <section class="content new-ticket tickets">
        <header id="main-content-header">
            New Ticket
        </header>

        <article id="main-content">
            <!-- form to submit new ticket -->
            <form id="new-ticket-form" action="../actions/action_new_ticket.php" method="post">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                <input type="hidden" name="tags" value="">
                <div>
                    <label for="subject">Subject</label>
                    <input type="text" name="subject" placeholder="Subject..." required>
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea name="description" placeholder="Type a message..." required></textarea>
                </div>
                <div>
                    <label for="department">Department</label>
                    <input list="department-list" type="text" name="department" class="department" placeholder="Department...">
                    <datalist id="department-list">
                    </datalist>
                </div>
                <div>
                    <label for="tags">Tags</label>
                    <div class="tag-input-button">
                        <input list="autocomplete" type="text" id="tags" placeholder="Tags...">
                        <div class="button"><button type="button">Add</button></div>
                    </div>
                    <datalist data-tag-autocomplete id="autocomplete">
                    </datalist>
                </div>
            </form>
            <div class="tags-list">

            </div>
        </article>
    </section>
<?php } ?>


<?php function drawNewTicketActions()
{ ?>
    <section class="actions new-ticket tickets-actions">
        <header id="actions-header">
            Actions
        </header>
        <article>
            <div class="action-buttons">
                <div class="button">
                    <button id="submit-ticket" type="submit" form="new-ticket-form">Submit</button>
                </div>
                <div class="button">
                    <a id="cancel" href="../pages/home.php">Cancel</a>
                </div>
            </div>
        </article>
    </section>
<?php } ?>


<?php function drawTicketPage(Ticket $ticket, array $comments, array $statuses, array $priorities, array $departments, array $agents, array $arr_ticket_history, array $FAQs, string $user_role)
{
    $session = new Session();
    $id = $session->getId();


    
    $tagNames = array_map(function ($tag) {
        return $tag->name;
    }, $ticket->tags);
?>
    <dialog data-modal>
        <form id="new-ticket-form" action="../actions/action_edit_ticket_tags.php" method="post">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
            <input type="hidden" name="tags" value="<?= htmlentities(implode(',', $tagNames)) ?>">
            <input type="hidden" name="ticket_id" value="<?= htmlentities((string) $ticket->id) ?>">
           
    
            <div>
                <label for="tags">Tags</label>
                <div class="tag-input-button">
                    <input list="autocomplete" type="text" id="tags" placeholder="Tags...">
                    <div class="button"><button type="button">Add</button></div>
                </div>
                <datalist data-tag-autocomplete id="autocomplete">
                </datalist>
            </div>
        </form>
        <div class="tags-list">
            <?php foreach ($ticket->tags as $tag) {
                if (empty($tag->name)) break; ?>
                <div class="tag">
                    <p><?= htmlentities($tag->name) ?></p>
                    <button type="button" id="tag-remove-button">X</button>
                </div>
            <?php } ?>
        </div>
        <div class="button">
            <button id="submit-ticket" type="submit" form="new-ticket-form">Submit</button>
        </div>
    </dialog>

    <section class="contentTicketPage tickets">
        <header id="main-content-header">
            Ticket
        </header>
        <article class="flex-container">
            <?php drawNewTicketPage($ticket, $comments, $user_role, $FAQs) ?>
        </article>
    </section>

    <section class="actions default-actions-grid tickets-actions">
        <header id="actions-header">
            Properties
        </header>
        <article>
            <form id="actions-content" class="properties-content" action="../actions/action_edit_ticket.php" method="POST">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status" <?= (!($user_role === "admin" || $user_role === "agent")) ? 'disabled' : '' ?>>
                        <option value="<?= htmlentities($ticket->status) ?>"><?= htmlentities($ticket->status) ?></option>
                        <?php foreach ($statuses as $status) {
                            if ($status->getName() !== $ticket->status) { ?>
                                <option value="<?= htmlentities($status->getName()) ?>"><?= htmlentities($status->getName()) ?></option>
                        <?php }
                        } ?>
                    </select>

                </div>

                <div>
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority" <?= (!($user_role === "admin" || $user_role === "agent")) ? 'disabled' : '' ?>>
                        <option value="<?= $ticket->priority ?>"><?= $ticket->priority ?></option>
                        <?php foreach ($priorities as $priority) {
                            if ($priority->getName() !== $ticket->priority) { ?>
                                <option value="<?= htmlentities($priority->getName()) ?>"><?= htmlentities($priority->getName()) ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>

                <div>
                    <label>Tags</label>
                    <?php if(($user_role === "admin" || $user_role === "agent") || $ticket->user_id == $id){?>
                    <div class="tags">
                        <?php
                        foreach ($ticket->tags as $tag) {
                            if (!empty($tag->name)) { ?>
                                <p class="tag"><?= htmlentities($tag->getname()) ?></p>
                            <?php } else { ?>
                                <p class="tag">Add a tag</p>
                        <?php }
                        } ?>
                    </div>
                    <?php } else {?>
                        <div class="disabled-tags">
                        <?php
                        foreach ($ticket->tags as $tag) {
                            if (!empty($tag->name)) { ?>
                                <p class="disabled-tag"><?= htmlentities($tag->getname()) ?></p>
                            <?php } else { ?>
                                <p class="disabled-tag">No tags</p>
                        <?php }
                        } ?>
                    </div>
              <?php  }?>
                </div>
               
                   
                        

                <div>
                    <label for="department">Department</label>
                    <select name="department" class="department" <?= (!($user_role === "admin" || $user_role === "agent")) ? 'disabled' : '' ?>>

                        <option value="<?= htmlentities($ticket->department) ?>"><?= htmlentities($ticket->department) ?></option>
                        <?php foreach ($departments as $department) {
                            if ($department->getName() !== $ticket->department) { ?>
                                <option value="<?= htmlentities($department->getName()) ?>"><?= htmlentities($department->getName()) ?></option>
                        <?php }
                        } ?>
                    </select>
                </div>

                <div>
                    <label for="assignee">Assignee</label>
                    <select name="new_assignee" id="assignee" <?= (!($user_role === "admin" || $user_role === "agent")) ? 'disabled' : '' ?>>
                        <option value="<?= htmlentities($ticket->assigned_to) ?>"><?= htmlentities($ticket->assigned_to) ?></option>
                        <?php foreach ($agents as $agent) {
                            if ($agent->name !== $ticket->assigned_to) { ?>
                                <option value="<?= htmlentities($agent->name) ?>"><?= htmlentities($agent->name) ?></option>
                        <?php }
                        } ?>
                    </select>

                </div>

                <?php
                if ($user_role === "admin" || $user_role === "agent") {
                ?>
                    <button class="button save-button">Save Changes</button>
                <?php
                }
                ?>
                <input type="hidden" name="ticket_id" value="<?= htmlentities((string) $ticket->id) ?>" />
            </form>
            <?php drawChangesToTicket($arr_ticket_history) ?>
        </article>
    </section>
<?php
}
?>



<?php function drawNewTicketPage(Ticket $ticket, array $comments, string $user_role, array $FAQs)
{
    $session = new Session();
    $id = $session->getId();

    $user_pic_id = urlencode((string)(($ticket->user_id) % 30 + 1));
    $user_pic = "../docs/users/$user_pic_id.jpg";
    if (!file_exists($user_pic))
        $user_pic = "../docs/user.png";

?>
    <article class="ticketPage">

        <header class="ticketPage-row">
            <div class="ticketPage-pic-container">
                <img src=<?= $user_pic; ?> alt="User" class="ticketPage-pic">
            </div>
            <div class="ticketPage-column">
                <p class="ticketPage-username"><?= htmlentities($ticket->user) ?></p>
                <p class="ticketPage-email"><?= htmlentities($ticket->email) ?></p>
            </div>
        </header>
        <div class="ticketPage-row">
            <p class="ticketPage-title"><?= htmlentities($ticket->title) ?></p>
        </div>
        <div class="ticketPage-row">
            <p class="ticketPage-description"><?= htmlentities($ticket->description) ?></p>
            <?php

            ?>
            <p class="ticketPage-description"><?= $ticket->last_update->format('d M Y') ?></p>



        </div>

        <div id="row" class="ticketPage-row">

            <?php if ($user_role === "admin" || $user_role === "agent") { ?>
                <button id="faq-form-toggle" class="button create-faq">Add to FAQ</button>
                <div class="faq-form-container hidden">
                    <div class="faq-form-toggle">
                        <button id="hide-button" class="button hide-faq">Hide</button>
                    </div>
                    <div class="textbox-container-faq">
                        <form id="faq-form" method="post" action="../actions/action_add_faq_answer.php">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <input type="hidden" name="question" value="<?= htmlentities($ticket->title) ?>"> </input>
                            <input type="hidden" name="ticketID" value="<?= htmlentities((string) $ticket->id) ?>"> </input>
                            <label for="answer">Answer:</label>
                            <textarea name="answer" class="fixed-textbox" rows="2" cols="140" placeholder="Write your answer here"></textarea>
                            <div class="button-container-faq">
                                <button type="submit" class="button-faq send-button">Send</button>
                                <button type="reset" class="button-faq cancel-button">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php } ?>
            <?php if (($user_role === "admin" || $user_role === "agent") && $ticket->status !== "Closed") { ?>
                <button id="answer-faq-form-toggle" class="button create-faq">Answer with FAQ</button>
                <div class="answer-faq-form-container hidden">
                    <div class="answer-faq-form-toggle">
                        <button id="hide-answer-button" class="button hide-faq">Hide</button>
                    </div>
                    <div class="textbox-container-answer-faq">
                        <form id="faq-form" method="post" action="../actions/action_answer_with_faq.php">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <input type="hidden" name="ticketID" value="<?= htmlentities((string) $ticket->id) ?>"> </input>

                            <label for="faqSearch"></label>
                            <input list="faq-list" type="text" name="faqSearch" id="faqSearch" placeholder="FAQ..." required autocomplete="off">
                            <datalist id="faq-list">
                            </datalist>

                            <div class="button-container-faq">

                                <button type="submit" class="button-faq send-button">Send</button>

                            </div>
                        </form>
                    </div>
                </div>

            <?php } ?>
            <?php if ($ticket->user_id == $id) { ?>
                <button id="edit-form-toggle" class="button create-edit">Edit</button>
                <div class="edit-form-container hidden">
                    <div class="edit-form-toggle">
                        <button id="hide-button-edit" class="button hide-edit">Hide</button>
                    </div>
                    <div class="textbox-container-edit">
                        <form id="edit-form" method="post" action="../actions/action_edit_content.php">
                            <input type="hidden" name="user_id" value="<?= htmlentities((string) $ticket->user_id) ?>"> </input>
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <input type="hidden" name="ticketID" value="<?= htmlentities((string) $ticket->id) ?>"> </input>
                            <textarea name="content" class="fixed-textbox" rows="2" cols="140" placeholder="<?= htmlentities($ticket->description) ?>"></textarea>
                            <div class="button-container-edit">
                                <button type="submit" class="button-edit send-button">Save</button>
                                <button type="reset" class="button-edit cancel-button">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

            <?php } ?>
            <?php if ((($user_role === "admin" || $user_role === "agent")) || ($ticket->user_id == $id)) { ?>
                <form id="delete-form" method="post" action="../actions/action_delete_ticket.php">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                    <input type="hidden" name="user_id" value="<?= htmlentities((string) $ticket->user_id) ?>"> </input>
                    <input type="hidden" name="ticketID" value="<?= htmlentities((string) $ticket->id) ?>"> </input>
                    <button class="button" type="submit">Delete</button>
                </form>
            <?php } ?>


        </div>

    </article>



    <section class="comments-container">
        <?php drawComments($comments, $ticket->id, htmlentities($ticket->status), $FAQs) ?>
    </section>


<?php } ?>


<?php
function drawChangesToTicket($arr_ticket_history){
?>
    <section id="change-section">
        <header class="change-header">
            Changes
        </header>
        <article class="ticket-history">
            <!-- <div class="ticket-history"> -->
                <?php if (empty($arr_ticket_history)) : ?>
                    <p>No changes made!</p>
                <?php else : ?>
                    <?php foreach ($arr_ticket_history as $history) : ?>
                        <section class="change">
                            <p class="time"><?= $history->time_of_change->format('F j, Y, g:i a') ?></p>
                            <?php if ($history->old_status !== $history->new_status) : ?>
                                <p>Status changed from <?= htmlentities($history->old_status) ?> to <?= htmlentities($history->new_status) ?></p>
                            <?php endif; ?>
                            <?php if ($history->old_assigned_agent !== $history->new_assigned_agent) : ?>
                                <p>Assignee changed from <?= htmlentities($history->old_assigned_agent) ?> to <?= htmlentities($history->new_assigned_agent) ?></p>
                            <?php endif; ?>
                            <?php if ($history->old_priority !== $history->new_priority) : ?>
                                <p>Priority changed from <?= htmlentities($history->old_priority) ?> to <?= htmlentities($history->new_priority) ?></p>
                            <?php endif; ?>
                            <?php if ($history->old_department !== $history->new_department) : ?>
                                <p>Department changed from <?= htmlentities($history->old_department) ?> to <?= htmlentities($history->new_department) ?></p>
                            <?php endif; ?>
                            <?php if ($history->content_change == 1) : ?>
                                <p>Content has been changed</p>
                            <?php endif; ?>
                            <?php if ($history->tags_change == 1) : ?>
                                <p>Tags have been changed</p>
                            <?php endif; ?>
                            </section>
                    <?php endforeach; ?>
                <?php endif; ?>
            <!-- </div> -->
        </article>
    </section>
<?php
}
?>