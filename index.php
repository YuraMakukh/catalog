<?php

include 'catalog.php';

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
            <p><?=$breadcrumbs;?></p><br>
            <hr><br>
            <?php if ($products):?>
                <?php foreach ($products as $product):?>
                    <a href="?product=<?=$product['id']?>"><?=$product['title']?></a><br>
                <?php endforeach;?>
            <?php else :?>
                <p>Здесь товаров нет</p>
            <?php endif;?>
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
