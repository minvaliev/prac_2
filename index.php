<?php
$connection = new PDO('mysql:host=localhost; dbname=academy; charset=utf8', 'root', '' );

$profile = $connection->query('SELECT * FROM `profile`');
$profile = $profile->fetchAll();

$education = $connection->query('SELECT * FROM `education` ORDER BY yearEnd DESC ');
$language = $connection->query('SELECT * FROM `languages` ORDER BY id DESC ');
$interest = $connection->query('SELECT * FROM `interests` ');
$experience = $connection->query('SELECT * FROM `experience` ORDER BY yearEnd ');
$experienceStr = $connection->query('SELECT * FROM `experience` ORDER BY yearEnd ');
$project = $connection->query('SELECT * FROM `projects` ');
$skill = $connection->query('SELECT * FROM `skills` ');
//var_dump($profile);
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html аlang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>Responsive Resume/CV Template for Developers</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Responsive HTML5 Resume/CV Template for Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,500,400italic,300italic,300,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <!-- Global CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <!-- Plugins CSS -->
    <link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome.css">

    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/styles.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="wrapper">
    <div class="sidebar-wrapper">
        <div class="profile-container">
            <img class="profile" src="assets/images/1.jpg" alt="" />
            <h1 class="name"><?= $profile[0]["name"] ?></h1>
            <h3 class="tagline"><?= $profile[0]["post"] ?></h3>
        </div><!--//profile-container-->

        <div class="contact-container container-block">
            <ul class="list-unstyled contact-list">
                <li class="email"><i class="fa fa-envelope"></i><a href="mailto: yourname@email.com"><?= $profile[0]["email"]   ?> </a></li>
                <li class="phone"><i class="fa fa-phone"></i><a href="tel:<?= $profile[0]["phone"] ?>"><?= $profile[0]["phone"]  ?></a></li>
                <li class="website"><i class="fa fa-globe"></i><a href="<?= $profile[0]["site"] ?>" target="_blank"><?= $profile[0]["site"] ?></a></li>
            </ul>
        </div><!--//contact-container-->
        <div class="education-container container-block">
            <h2 class="container-block-title">Образование</h2>
            <?php foreach ($education as $educations) : ?>
            <div class="item">
                <h4 class="degree"><?=$educations["speciality"] ?></h4>
                <h5 class="meta"><?=$educations["title"] ?></h5>
                <div class="time"><?=$educations["yearStart"] ?> - <?=$educations["yearEnd"] ?></div>
            </div><!--//item-->
            <?php endforeach; ?>
        </div><!--//education-container-->

        <div class="languages-container container-block">
            <h2 class="container-block-title">Языки</h2>
            <ul class="list-unstyled interests-list">
                <?php foreach ($language as $languages ) : ?>
                <li><?=$languages["title"] ?> <span class="lang-desc">(<?=$languages["level"] ?> )</span></li>
                <?php endforeach ?>
            </ul>
        </div><!--//interests-->

        <div class="interests-container container-block">
            <h2 class="container-block-title">Интересы</h2>
            <ul class="list-unstyled interests-list">
                <?php foreach ($interest as $interests) : ?>
                <li><?=$interests["interest"] ?></li>
                <?php endforeach ?>
            </ul>
        </div><!--//interests-->

    </div><!--//sidebar-wrapper-->

    <div class="main-wrapper">

        <section class="section summary-section">
            <h2 class="section-title"><i class="fa fa-user"></i>Обо мне</h2>
            <div class="summary">
                <p> Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley. <p> </div><!--//summary-->
        </section><!--//section-->

        <section class="section experiences-section">

            <h2 class="section-title"><i class="fa fa-briefcase"></i>Опыт работы</h2>
            <?php foreach ($experience as $experinces) { ?>

            <div class="item">
                <div class="meta">
                    <div class="upper-row">
                        <h3 class="job-title"><?=$experinces["post"] ?></h3>
                        <div class="time"><?=$experinces["yearStart"] ?> - <?=$experinces["yearEnd"] ?: "По настоящее время" ?> </div>
                    </div><!--//upper-row-->
                    <div class="company"> <?=$experinces["company"] . ',' . ' ' .  $experinces["city"]?> </div>
                </div><!--//meta-->
                <div class="details">
                    <?=$experinces["about"]  ?>
                </div><!--//details-->
            </div><!--//item-->

            <?php } ?>

        </section><!--//section-->

        <section class="section projects-section">
            <h2 class="section-title"><i class="fa fa-archive"></i>Проекты</h2>
            <div class="intro">
                <p>You can list your side projects or open source libraries in this section. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum et ligula in nunc bibendum fringilla a eu lectus.</p>
            </div><!--//intro-->
            <?php foreach ($project as $projects) : ?>
            <div class="item">
                <span class="project-title"><a href="<?=$projects["link"] ?>"><?=$projects["title"] ?></a></span> - <span class="project-tagline"><?=$projects["about"] ?></span>

            </div><!--//item-->
            <?php endforeach ?>
        </section><!--//section-->

        <section class="skills-section section">
            <h2 class="section-title"><i class="fa fa-rocket"></i>Навыки</h2>
            <?php foreach ($skill as $skills) : ?>
            <div class="skillset">
                <div class="item">
                    <h3 class="level-title"><?=$skills["title"] ?></h3>
                    <div class="level-bar">
                        <div class="level-bar-inner" data-level="<?=$skills["data-level"] ?>%">
                        </div>
                    </div><!--//level-bar-->
                </div><!--//item-->
            </div>
            <?php endforeach ?>
        </section><!--//skills-section-->

        <form action="#" method="POST">
            <textarea name="comment" cols="30" rows="10" placeholder="Оставить отзыв"></textarea>
            <input type="text" name="name" placeholder="Введите ваше имя">
            <button>Отправить отзыв</button>
        </form>

        <?php

        if ($_POST['comment'] && $_POST['name']) {
            $comment = $_POST['comment'];
            $name = $_POST['name'];
            $date = date("d-m-Y");
                if (strpos($comment, 'редиска') !== false) {
                    $net = 'Записывать данное слово "Редиска" нельзя!';
                    echo $net;

                }
                else {
                    $connection->query("INSERT INTO `comments` (`comment`,`name`,`date`) VALUES ('$comment','$name','$date')");

                        if (!empty($_POST)) {
                            echo "Существует";
                            header('Location: index.php');
                        }
                }
            }

        $commentsOfUsers = $connection->query("SELECT * FROM `comments`");

        foreach ($commentsOfUsers as $comment) {
        ?>

        <div style="font-size: 25px; margin: auto;" >

            <?='#' . $comment['id'] . '. ' . $comment['name']. ', ' .  $comment['date'] . '- "' . $comment['comment'] . '!"'; ?>

        </div>

        <?php } ?>

    </div><!--//main-body-->
</div>


<!-- Javascript -->
<script type="text/javascript" src="assets/plugins/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- custom js -->
<script type="text/javascript" src="assets/js/main.js"></script>
</body>
</html>

