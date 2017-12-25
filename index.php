<?php
include 'config.php';
include 'functions.php';

$categories = get_cat();
$categories_tree = map_tree($categories);
$categories_menu = categories_to_string($categories_tree);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Каталог</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="<?=DOMEN?>">Главная</a><br>
    <div class="wrapper">
        <div class="sidebar">
            <ul class="category">
                <?php echo $categories_menu;?>
            </ul>
        </div>
        <div class="content">
            CONTENT
        </div>
    </div>
    <script src="js/jquery-1.9.0.min.js"></script>
    <script src="js/jquery.accordion.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script>
        $(document).ready(function () {
            $(".category").dcAccordion()
        });
    </script>
</body>
</html>
