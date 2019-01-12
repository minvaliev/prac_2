<?php
session_start();

if (!$_SESSION['login'] || !$_SESSION['password']) {
    header("Location: login.php");
    die();
}

if ($_POST['aut']) {
    session_destroy();
    header("Location: login.php");
}

if (count($_POST) > 0) {
    header("Location: admin.php");
}

$connectionOfForum = new PDO('mysql:host=localhost; dbname=forum; charset=utf8', 'root', '' );
$commentsOfUsers = $connectionOfForum->query("SELECT * FROM `comments` WHERE moderation='new' ORDER BY date DESC ");
?>

<style>
    body {
        background-color: #1b6d85;
        margin: 20px;
        font-family: Arial,sans-serif;
    }

    * {
        font-size: 30px;
    }

    button {
        margin-top: 20px;
    }
</style>

<?php if ($_SESSION['login'] == 'Admin') { ?>
    <h1>Админка сайта портфолио с правами доступа "Администратор сайта". Вы можете отклонять отзовы и публиковать.</h1>
    <form method="POST">
        <?php foreach ($commentsOfUsers as $comments) { ?>
        <select name="<?=$comments['id']?>" id="<?=$comments['id']?>">
            <option selected style="display:none; " value="new"  >Выберите необходимое действие</option>
            <option value="ok">OK</option>
            <option value="rejected">ОТКЛОНИТЬ</option>
        </select>
        <label for="<?=$comments['id']?>">
            <?=$comments['username'] . ' оставил комментарий: "' . $comments['comment'] . "\"<br/>"  ?>
        </label>
        <?php } ?>
        <button>Модерировать</button>
    </form>

<?php } ?>

<?php if ($_SESSION['login'] == 'Moderator') { ?>
    <h1>Админка сайта портфолио с правами доступа "Модератор". Вы можете только отклонять отзовы.</h1>
    <form method="POST">
        <?php foreach ($commentsOfUsers as $comments) { ?>
            <select name="<?=$comments['id']?>" id="<?=$comments['id']?>">
                <option value="rejected">ОТКЛОНИТЬ</option>
            </select>
            <label for="<?=$comments['id']?>">
                <?=$comments['username'] . ' оставил комментарий: "' . $comments['comment'] . "\"<br/>"  ?>
            </label>
        <?php } ?>
        <button>Модерировать</button>
    </form>

<?php } ?>

<hr>
<form method="POST">
    <input type="submit" name="aut" value="Выйти из админки" >
</form>

<?php

foreach ($_POST as $num=>$checked) {
    if ($checked == 'ok') {
        $connectionOfForum->query("UPDATE `comments` SET moderation='ok' WHERE id=$num");
    }
    else {
        $connectionOfForum->query("UPDATE `comments` SET moderation='rejected' WHERE id=$num");
    }
}