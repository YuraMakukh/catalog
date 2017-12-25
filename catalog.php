<?php

include 'config.php';
include 'functions.php';

$categories = get_cat();
$categories_tree = map_tree($categories);
$categories_menu = categories_to_string($categories_tree);

    $id = (int)$_GET['category'];
    // хлебные крошки
    $breadcrumbs_array = breadcrumbs($categories, $id);

    if ($breadcrumbs_array){
        $breadcrumbs = "<a href=". DOMEN . "?>Главная</a> / ";
        foreach ($breadcrumbs_array as $id => $title){
            $breadcrumbs .= "<a href='?category={$id}'>{$title}</a> / ";
        }
        $breadcrumbs = rtrim($breadcrumbs, " / ");
        $breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $breadcrumbs);
    }else{
        $breadcrumbs = "<a href=". DOMEN . "?>Главная</a> / Каталог";
    }

    // ID дочерних категорий
    $ids = cats_id($categories, $id);
    $ids = !$ids ? $id : rtrim($ids, ",");

    /*==========Pagination==========*/

    //количество товаров на страницу
    $per_page = 5;

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


