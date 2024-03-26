<?php
    declare(strict_types=1);

        require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/users.class.php');

?>

<?php function drawHeader(Session $session, PDO $db) { ?>
    <!DOCTYPE html>
    <html lang="en-US">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="icon" type="image/x-icon" href="/docs/ticket.svg">

            <title>Ticket System</title>
            <link rel="stylesheet" href="../css/style.css">
            <link rel="stylesheet" href="../css/ticket.css">
            <link rel="stylesheet" href="../css/ticketPage.css">
            <link rel="stylesheet" href="../css/comments.css">
            <link rel="stylesheet" href="../css/profile.css">
            <link rel="stylesheet" href="../css/ticketHistory.css">
            <link rel="stylesheet" href="../css/dashboard.css">
            <link rel="stylesheet" href="../css/faq.css">
            <link rel="stylesheet" href="../css/responsive.css">
            
            <script src="../scripts/utils/utils.js" defer></script>
            <script src="../scripts/ticket_search.script.js" defer></script>
            <script src="../scripts/ticket_filters.script.js" defer></script>
            <script src="../scripts/autocomplete.script.js" defer></script>
            <script src="../scripts/edit_info.js" defer></script>
            <script src="../scripts/single_ticket_comment.js" defer></script>
            <script src="../scripts/navbar.script.js" defer></script>
            <script src="../scripts/ticket_buttons.js" defer></script>
            <script src="../scripts/create_faq.js" defer></script>
            <script src="../scripts/dashboard.script.js" defer></script>
            <script src="../scripts/add_edit_tags.script.js" defer></script>
            <script src="../scripts/modal.js" defer></script>
            <script src="../scripts/utils/responsive_actions.js" defer></script>

        </head>
        <body>
            <div id="general-messages">
                <?php foreach ($session->getMessages() as $messsage) { ?>
                    <article id="<?= htmlentities($messsage['type']) ?>">
                        <p><?= htmlentities($messsage['text']) ?></p>
                    </article>
                <?php } ?>                
            </div>
            <!-- Used for mobile only -->
            <div id="mobile-navbar">
                <img src="../docs/hamburger.svg" alt="Menu" id="menu" width="40">
                <img src="../docs/filters.svg" alt="Filters" id="filters" width="40">
            </div>

            <nav id="sidebar">
           
                <?php
                    // if($session->isLoggedIn()) drawLogoutButton();
                    // else drawLoginButton();
                    drawLogoutButton();
                ?>

                <!-- list of icon buttons -->

                <ul>
                    <li>
                        <ul>
                            <li>
                                <a href="../pages/home.php">
                                    <img src="../docs/home.svg" alt="Home" width="100">
                                </a>
                            </li>
                            <li>
                                <a href="../pages/new_ticket.php">
                                    <img src="../docs/add.svg" alt="New Ticket" width="100">
                                </a>
                            </li>
                            <li>
                                <a id="navbutton">
                                    <img src="../docs/dashboard.svg" alt="Dashboard" width="100">
                                </a>
                            </li>
                            <?php if (User:: getUserRoleByID($db, ($session->getID())) === "admin") { ?>
                            <li class="subnav">
                                <a href="../pages/dashboard_users.php">
                                    Users
                                </a>
                            </li>
                            <li class="subnav">
                                <a href="../pages/dashboard_edit.php">
                                    Edit
                                </a>
                            </li>

                            <?php } ?> 
                        </ul> 
                    </li>

                    <li>
                        <ul>
                            <li>
                                <a href="../pages/faq.php">
                                    <img src="../docs/question-mark.svg" alt="FAQ" width="100">
                                </a>
                            </li>
                            <li>
                                <a href="../pages/profile.php">
                                    <img src="../docs/profile.svg" alt="Profile" width="100">
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <main>
<?php } ?>




<?php function drawLoginButton() { ?>
    <a href="../pages/login.php"><img src="" alt="Login"></a>
<?php } ?>

<?php function drawLogoutButton() { ?>
    <form action="../actions/action_logout.php" method="post" class="login-form">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
        <button type="submit">
            <img src="../docs/logout.svg" alt="Logout" width="100" >
        </button>
    </form>
<?php } ?>



<?php function drawFooter() { ?>
            </main>
        </body>
    </html>
<?php } ?>