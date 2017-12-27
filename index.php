<?php
//error_reporting(E_ALL);
define('CATALOG', true);

include 'config.php';
include 'functions.php';

$categories = get_cat();
$categories_tree = map_tree($categories);
$categories_menu = categories_to_string($categories_tree);


/******************Routing*****************/

$url = ltrim($_SERVER['REQUEST_URI'], '/');

$routes = [
    ['url' => '#^$|^\?$#', 'view' => 'category'],

    ['url' => '#^product/(?P<product_alias>[a-z0-9-]+)#i', 'view' => 'product'],
    ['url' => '#^category/(?P<id>[0-9-]+)#i', 'view' => 'category'],
];

foreach ($routes as $rout){
    if (preg_match($rout['url'], $url, $match)){
        $view = $rout['view'];
        break;
    }
}

if (empty($match)){
    include 'views/404.php';
    exit;
}
extract($match);
// $id - ID категории
// $product_alias - alias продукта
//// $view - вид для подключения
/******************Routing*****************/

if( isset($product_alias) ){
    // массив данных продукта
    $get_one_product = get_one_product($product_alias);
    // получаем ID категории
    $id = $get_one_product['parent'];
}else{
    $id = (int)$_GET['category'];
}

    // хлебные крошки
    $breadcrumbs_array = breadcrumbs($categories, $id);

    if ($breadcrumbs_array){
        $breadcrumbs = "<a href=". PATH . "?>Главная</a> / ";
        foreach ($breadcrumbs_array as $id => $title){
            $breadcrumbs .= "<a href='" . PATH . "category/{$id}'>{$title}</a> / ";
        }
        if (!$get_one_product){
            $breadcrumbs = rtrim($breadcrumbs, " / ");
            $breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $breadcrumbs);
        }else{
            $breadcrumbs .= $get_one_product['title'];
        }

    }else{
        $breadcrumbs = "<a href=". PATH . "?>Главная</a> / Каталог";
    }

    // ID дочерних категорий
    $ids = cats_id($categories, $id);
    $ids = !$ids ? $id : rtrim($ids, ",");

    /*==========Pagination==========*/

    //количество товаров на страницу
    $per_page = (int)$_COOKIE['per_page'] ? $_COOKIE['per_page'] : PERPAGE;

    //общие количество товаров
    $count_goods = count_goods($ids);

    // необходимое количество страниц
    $count_pages = ceil($count_goods / $per_page);
    if (!$count_pages) $count_pages = 1; //минимум одна страница

    //получение запрашеваемой страницы
    if ($_GET['page']) {
        $page = (int)$_GET['page'];
        if ($page < 1) $page = 1;

    }else{
        $page = 1;
    }
    if ($page > $count_pages) $page = $count_pages;

    //начальная позиция для запроса
    $start_pos = ($page - 1) * $per_page;

    $pagination = pagination($page, $count_pages);

    /*==========Pagination==========*/

    // list of products
    $products = get_products($ids, $start_pos, $per_page);

    include "views/{$view}.php";
//    include 'views/product.php';


