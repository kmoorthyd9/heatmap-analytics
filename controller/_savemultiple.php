<?php

/*
 * if you need to save the row of a  table  
 * Additional requirements  page / page?key=value / page?key=value&key2=value2,
 * model_count, model1, model,...
 * returns  $data, $msg
 */
$saved = false;
if (isset($_REQUEST["model_count"])) {
    $count = $_REQUEST["model_count"];

    for ($i = 0; $i < $count; $i++) {
        $pos = "model" . $i;
        echo $pos;
        if (isset($_REQUEST[$pos])) {
            $saved = save($_REQUEST[$pos], $_REQUEST);
        }
    }
}

function save($model, $request) {

    $class_name = $model;
    echo $class_name;
    include_once "../model/" . strtolower($class_name) . ".php";
    $model = new $class_name;
    // called debug
    if (isset($_REQUEST["debug"])) {
        $model->debug();
    }
    return $model->save($_REQUEST);
}

/* redirect headders */
if (isset($_REQUEST["page"])) {
    $page = $_REQUEST["page"];
    $page = createurl($page, $saved);
} else {
    header("Location: ../404.php");
}

function createurl($page, $status) {
    if (strpos($page, '?') !== false) {
        $args = array_reverse(explode("?", $page));
        $url = implode("&", $args);
        if ($status) {
            header("Location: ../$args[1]?save=Successfully Added&" . $url);
        } else {
            header("Location: ../$args[1]?save=failed&" . $url);
        }
    } else {
        if ($status) {
            header("Location: ../$page?save=Successfully Added");
        } else {
            header("Location: ../$page?save=failed");
        }
    }
    return $page;
}
