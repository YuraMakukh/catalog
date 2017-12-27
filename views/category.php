
<?php defined('CATALOG') or die('Access denied!!!');?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Каталог</title>
    <link rel="stylesheet" href="/views/style.css">
</head>
<body>
<a href="<?=PATH?>">Главная</a><br>
    <div class="wrapper">
        <div class="sidebar">
            <ul class="category">
                <?php echo $categories_menu;?>
            </ul>
        </div>
        <div class="content">
            <p><?=$breadcrumbs;?></p><br>
            <hr>
            <div>
                <select name="perpage" id="perpage">
                    <?php foreach ($option_perpage as $option) :?>
                        <option <?php if ($per_page == $option) echo "selected";?> value="<?=$option;?>"><?=$option;?> товаров на страницу</option>
                    <?php endforeach;?>
                </select>
            </div>

            <?php if ($products):?>

                <?php if ($count_pages > 1):?>
                <div class="pagination"><?=$pagination?></div>
                <?php endif;?>

                <?php foreach ($products as $product):?>
                    <a href="<?=PATH?>product/<?=$product['alias']?>"><?=$product['title']?></a><br>
                <?php endforeach;?>

                <?php if ($count_pages > 1):?>
                    <div class="pagination"><?=$pagination?></div>
                <?php endif;?>

                <?php else :?>
                <p>Здесь товаров нет</p>
            <?php endif;?>
        </div>
    </div>
    <script src="<?=PATH?>views/js/jquery-1.9.0.min.js"></script>
    <script src="<?=PATH?>views/js/jquery.accordion.js"></script>
    <script src="<?=PATH?>views/js/jquery.cookie.js"></script>
    <script>
        $(document).ready(function () {
            $(".category").dcAccordion()
            $("#perpage").change(function(){
                var perPage = this.value;
                $.cookie('per_page', perPage, {expires:7, path:'/'});
                window.location = location.href;
            });
        });
    </script>
</body>
</html>
