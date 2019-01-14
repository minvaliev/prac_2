<?php
session_start();
$connectionOfForum = new PDO('mysql:host=localhost; dbname=forum; charset=utf8', 'root', '' );
$commentsOfUsers = $connectionOfForum->query("SELECT * FROM `comments` WHERE moderation='new' ORDER BY date DESC ");

$result = $connectionOfForum->query("SELECT * FROM `images` ");
$result = $result->fetchAll();





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

<?php if ($_SESSION['login'] == 'Admin') { // Если зашли под админской учётной записью, то выводить следующее содержимое ?>

    <?php
    if (count($result) == 0 ) { // Если в таблице "images" нет никаких данных, то добавляем новую фотографию.
        $connectionOfForum->query("INSERT INTO `images` (`imagname`,`extension`) VALUES ('$fileName', '$fileExtension')");

        $lastId = $connectionOfForum->query("SELECT MAX(id) FROM `images` ");
        $lastId = $lastId->fetchAll();
        $lastId = $lastId[0][0];

        $fileNameNew = 'avatar' . '.' . 'jpg';
        $fileDestination = 'uploads/' . $fileNameNew;

        move_uploaded_file($fileTmpName, $fileDestination);
        echo "Файл успешно загружен!";
        header("Location: index.php");
    }

    if (isset($_POST['submit'])) {
        $argum = $_FILES['file']['name'];

        if (count($argum) <= 3) { // Если выбрано больше трёх фотографий, то выводить сообщение "Превышено максимальное колличество загружаемых фотографи".
            foreach ($argum as $key => $elm) {

                $fileName = $_FILES['file']['name'][$key];
                $fileTmpName = $_FILES['file']['tmp_name'][$key];
                $fileType = $_FILES['file']['type'][$key];
                $fileError = $_FILES['file']['error'][$key];
                $fileSize = $_FILES['file']['size'][$key];

                $fileExtension = strtolower(end(explode('.', $fileName)));

                // Если в имени файла несколько точек (например, abc.xyz.jpg), то имя файла сохраняется не до первой точки, а до последней (имя на выходе - abc.xyz).
                // Реализация ниже в коде.
                $fileName = explode('.', $fileName);
                unset($fileName[array_search($fileExtension,$fileName)]);
                $newtext = implode(".",$fileName);
                $fileName = preg_replace('/[0-9]/', '', $newtext);

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'js'];

                if (in_array($fileExtension, $allowedExtensions)) {
                    if ($fileSize < 5000000) {
                        if ($fileError === 0) {
                            if (count($result) == 0 ) { // Если в таблице "images" нет никаких данных, то добавляем новую фотографию.
                                $connectionOfForum->query("INSERT INTO `images` (`imagname`,`extension`) VALUES ('$fileName', '$fileExtension')");

                                $lastId = $connectionOfForum->query("SELECT MAX(id) FROM `images` ");
                                $lastId = $lastId->fetchAll();
                                $lastId = $lastId[0][0];

                                $fileNameNew = 'avatar' . '.' . 'jpg';
                                $fileDestination = 'uploads/' . $fileNameNew;

                                move_uploaded_file($fileTmpName, $fileDestination);
                                echo "Файл успешно загружен!";
                                header("Location: admin.php");
                            }
                            else {
                                $connectionOfForum->query("DELETE FROM `forum` . `images` "); // Иначе, сначала удаляем фото из БД, а далее из самой папки расположения.
                                $image = "uploads/"  . 'avatar' . '.' . 'jpg';
                                if (file_exists($image)) {
                                    unlink($image);
                                }

                                $connectionOfForum->query("INSERT INTO `images` (`imagname`,`extension`) VALUES ('$fileName', '$fileExtension')");

                                $lastId = $connectionOfForum->query("SELECT MAX(id) FROM `images` ");
                                $lastId = $lastId->fetchAll();
                                $lastId = $lastId[0][0];

                                $fileNameNew = 'avatar' . '.' . 'jpg';
                                $fileDestination = 'uploads/' . $fileNameNew;

                                move_uploaded_file($fileTmpName, $fileDestination);
                                echo "Файл успешно загружен!";
                                header("Location: admin.php");
                            }

                        } else {
                            echo "Что то пошло не так";
                        }
                    } else {
                        echo "Слишком большой размер файла!";
                    }
                } else {
                    echo "Неверный тип файла!";
                }

            }
        }
        else {
            echo "Превышено максимальное колличество загружаемых фотографи";
        }
    }

    $data = $connectionOfForum->query("SELECT * FROM `images` ");
    echo "<div style='display: flex;align-items: flex-end; flex-wrap:wrap;'>";
    foreach ($data as $img) {
        $delete = "delete".$img['id'];
        $image = "uploads/"  . 'avatar' . '.' . 'jpg';
        if (isset($_POST[$delete])) {
            $imageId = $img['id'];
            $connectionOfForum->query("DELETE FROM `forum` . `images` WHERE id='$imageId'");
            if (file_exists($image)) {
                unlink($image);
            }
        }

        if (file_exists($image)) {
            echo "<div>";
            echo "<img width='150' height='150' src=$image>";
            echo "<form method='POST'><button name='delete".$img['id']."'style='display:block; margin:auto; margin-top:10px'> 
        Удалить </button></form></div>";
        }
    }
    echo "</div>";
    ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file[]" multiple>
        <button name="submit">Отправить</button>
    </form>

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


