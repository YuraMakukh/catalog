<?php
/**
 * Удобная расспечатка массива
 */
function d($arr){
    echo "<pre>" . print_r($arr, true) . "</pre>";
}

/**
 * Получение масива категорий
 */
function get_cat(){
    global  $connection;
    $query = "SELECT * FROM categories";
    $res = mysqli_query($connection, $query);

    $arr_cat = [];
    while ($row = mysqli_fetch_assoc($res)){
        $arr_cat[$row['id']] = $row;
    }
    return $arr_cat;
}

/**
 * Построение дерева
 **/
function map_tree($dataset) {
    $tree = array();

    foreach ($dataset as $id=>&$node) {
        if (!$node['parent']){
            $tree[$id] = &$node;
        }else{
            $dataset[$node['parent']]['childs'][$id] = &$node;
        }
    }

    return $tree;
}

/**
 * Дерево в строку HTML
 * @params array
 **/
function categories_to_string($data){
    foreach($data as $item){
        $string .= categories_to_template($item);
    }
    return $string;
}

/**
 * @param $category
 * @return string
 */
function categories_to_template($category){
    ob_start();
    include 'category_template.php';
    return ob_get_clean();
}

/**
 * Хлебные крошки
 * @param $array
 * @param $id
 * @return array|bool
 */
function breadcrumbs($array, $id){
    if(!$id) return false;

    $count = count($array);
    $breadcrumbs_array = array();
    for($i = 0; $i < $count; $i++){
        if($array[$id]){
            $breadcrumbs_array[$array[$id]['id']] = $array[$id]['title'];
            $id = $array[$id]['parent'];
        }else break;
    }
    return array_reverse($breadcrumbs_array, true);
}

/**
 * ID Дочерних категорий
 * @param $array
 * @param $id
 * @return bool|string
 */
function cats_id($array, $id){
    if (!$id) return false;

    foreach ($array as $item){
        if ($item['parent'] == $id){
            $data .= $item['id'] . ",";
            $data .= cats_id($array, $item['id']);
        }

    }
    return $data;
}

/**
 * Получение списка товаров
 * @param $ids
 * @return array
 */
function get_products($ids, $start_pos, $per_page){
    global $connection;
    if ($ids){
        $query ="SELECT * FROM products WHERE parent IN($ids) ORDER BY title LIMIT {$start_pos}, {$per_page}";
    }else{
        $query = "SELECT * FROM products ORDER BY title LIMIT {$start_pos}, {$per_page}";
    }
    $res = mysqli_query($connection, $query);
    $products = [];
    while ($row = mysqli_fetch_assoc($res)){
        $products[] = $row;
    }
    return $products;
}

/**
 * @param $id
 * @return array|null
 */
function get_one_product($id){
    global $connection;
    $query = "SELECT * FROM products WHERE id = $id LIMIT 1";
    $res = mysqli_query($connection, $query);
    $product = mysqli_fetch_assoc($res);
    return $product;
}

/**
 * общиее количество товаров для определенной категории
 * @param $ids
 * @return int
 */
function count_goods($ids){
    global $connection;
    if (!$ids){
        $query = "SELECT COUNT(*) FROM products";
    }else{
        $query = "SELECT COUNT(*) FROM products WHERE parent IN ($ids)";
    }
    $res = mysqli_query($connection, $query);
    $count_goods = mysqli_fetch_row($res);
    return $count_goods[0];
}


function pagination($page, $count_pages, $modrew = true){
    //$modrew = true - ЧПУ
    //<< < 3 4 5 6 7 > >>
    //$back - ссылка НАЗАД
    //$forward - ссылка ВПЕРЕД
    //$start_page - ссылка В НАЧАЛО
    //$end_page - ссылка В КОНЕЦ
    //$page2left - вторая страница слева
    //$page1left - первая страница слева
    //$page2right - вторая страница справа
    //$page1right - первая страница справа

    $uri = '?';
    if (!$modrew){
        //если есть параметры в запросе
        if ($_SERVER['QUERY_STRING']){
            foreach ($_GET as $key => $value){
                if ($key != 'page') $uri .= "{$key}=$value" . "&amp;";
            }
        }
    }else{
        $url = $_SERVER['REQUEST_URI'];
        $url = explode("?", $url);
        if (isset($url[1]) && $url[1] != ''){
            $params = explode("&", $url[1]);
            foreach ($params as $param)
            if (!preg_match("#page=#", $param)){
                $uri .= "{$param}&amp;";
            }
        }
    }


    if($page > 1){
        $back = "<a class='nav_link' href='{$uri}page=" . ($page - 1) . "'>&lt;</a>";
    }
    if($page < $count_pages){
        $forward = "<a class='nav_link' href='{$uri}page=" . ($page + 1) . "'>&gt;</a>";
    }
    if($page > 3) {
        $start_page = "<a class='nav_link' href='{$uri}page=1'>&laquo;</a>";
    }
    if($page < ($count_pages - 2)) {
        $end_page = "<a class='nav_link' href='{$uri}page=" . ($count_pages) . "'>&raquo;</a>";
    }
    if($page - 2 > 0) {
        $page2left = "<a class='nav_link' href='{$uri}page=" . ($page - 2) . "'>" . ($page - 2) . "</a>";
    }
    if($page - 1 > 0) {
        $page1left = "<a class='nav_link' href='{$uri}page=" . ($page - 1) . "'>" . ($page - 1) . "</a>";
    }
    if($page + 1 <= $count_pages) {
        $page1right = "<a class='nav_link' href='{$uri}page=" . ($page + 1) . "'>" . ($page + 1) . "</a>";
    }
    if($page + 2 <= $count_pages) {
        $page2right = "<a class='nav_link' href='{$uri}page=" . ($page + 2) . "'>" . ($page + 2) . "</a>";
    }
    return $start_page . $back . $page2left . $page1left . "<a class='nav_activ'>" . $page . '</a>' . $page1right . $page2right . $forward . $end_page;
}

