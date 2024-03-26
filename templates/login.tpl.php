<?php

declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
?>

<?php function drawLoginForm(Session $session)
{ ?>
    <form action="../actions/action_login.php" method="post" class="login">
        <input type="email" name="email" placeholder="Email" required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Please enter a valid email address (Example: john.doe@example.com)">
        <input type="password" name="password" placeholder="Password" required pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Please enter a valid password. It needs to contain at least one digit and 8 characters"> <!-- Must have at least one digit and be 8 characters in length minimum -->
        <button type="submit">Login</button>
        <a href="../pages/register.php">Register</a>

        <article id="session_messages">
            <?php foreach ($session->getMessages() as $message) { ?>
                <p><?= htmlentities($message['text']) ?></p>
            <?php } ?>
        </article>
    </form>
<?php } ?>

<?php function drawRegisterForm(Session $session)
{ ?>
    <form action="../actions/action_register.php" method="post" class="register">
        <input type="email" name="email" placeholder="Email" required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Please enter a valid email address (Example: john.doe@example.com)">
        <input type="username" name="username" placeholder="Username" required pattern="^[a-zA-Z0-9_-]{3,16}$" title="Please enter a valid username (Example: john_doe"> <!-- Must be between 3 and 16 characters in length -->
        <input type="name" name="name" placeholder="Name" required pattern="^(?:[A-Z][a-z]* ?)+$" title="Please enter a valid name (Example: John Doe)">
        <input type="password" name="password" placeholder="Password" required pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$" title="Please enter a valid password. It needs to contain at least one digit and 8 characters"> <!-- Must have at least one digit and be 8 characters in length minimum -->
        <button type="submit">Register</button>
        <a href="../pages/login.php">Already have an account? Login</a>
        <article id="session_messages">
            <?php foreach ($session->getMessages() as $message) { ?>
                <p><?= htmlentities($message['text']) ?></p>
            <?php } ?>
        </article>
    </form>
<?php } ?>

<?php function drawHeaderLoginRegister(Session $session)
{ ?>
    <!DOCTYPE html>
    <html lang="en-US">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ticket System</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="../css/login.css">
    </head>

    <body>
        <div id="general-messages">
            <?php foreach ($session->getMessages() as $messsage) { ?>
                <article id="<?= htmlentities($messsage['type']) ?>">
                    <p><?= htmlentities($messsage['text']) ?></p>
                </article>
            <?php } ?>
        </div>
        <main>
        <?php } ?>