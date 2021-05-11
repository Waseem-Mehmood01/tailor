<?php
include_once 'admin/functions.php';

function getMetaTags($products_id = '')
{
    $results = DB::queryFirstRow("SELECT site_title, site_description, site_tags, meta_robots FROM sa_companies");
    if ($products_id != '') {

        $row = DB::queryFirstRow("SELECT meta_title, meta_description, meta_keywords FROM products WHERE products_id='" . $products_id . "'");
        $results['site_title'] = $row['meta_title'];
        $results['site_description'] = $row['meta_description'];
        $results['site_tags'] = $row['meta_keywords'];
    }

    return $results;
}

function getAllProductsByCategory($category_id)
{
    $results = DB::query("SELECT p.products_id,p.name,p.description1,p.description2,p.meta_title,p.meta_description,p.meta_keywords,i.img_path FROM products p JOIN products_img i ON p.products_id = i.products_id WHERE p.categories_id='" . $category_id . "' GROUP by p.products_id");
    return $results;
}


function cleanVar($data = '')
{
    if ($data != '') {
        $data = trim(htmlentities(strip_tags($data)));
        $data = stripslashes($data);
    }
    return $data;
}

function cleanName($string = '')
{
    if ($string != '') {
        $string = str_replace(' ', '', $string);
        $string = preg_replace('/[^A-Za-z0-9]/', '', $string);
    }
    return $string;
}

function displayPaginationOverride($per_page, $page, $sql, $page_url)
{
    DB::query($sql);
    $total = DB::count();
    $adjacents = "2";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $setLastpage = ceil($total / $per_page);
    $lpm1 = $setLastpage - 1;

    $setPaginate = "";
    if ($setLastpage > 1) {
        $setPaginate .= "<ul class='setPaginate'>";
        $setPaginate .= "<li class='setPage'>Page $page of $setLastpage</li>";
        if ($setLastpage < 7 + ($adjacents * 2)) {
            for ($counter = 1; $counter <= $setLastpage; $counter ++) {
                if ($counter == $page)
                    $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                else
                    $setPaginate .= "<li><a href='{$page_url}$counter'>$counter</a></li>";
            }
        } elseif ($setLastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter ++) {
                    if ($counter == $page)
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate .= "<li><a href='{$page_url}$counter'>$counter</a></li>";
                }
                $setPaginate .= "<li class='dot'>...</li>";
                $setPaginate .= "<li><a href='{$page_url}$lpm1'>$lpm1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}$setLastpage'>$setLastpage</a></li>";
            } elseif ($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $setPaginate .= "<li><a href='{$page_url}1'>1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}2'>2</a></li>";
                $setPaginate .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter ++) {
                    if ($counter == $page)
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate .= "<li><a href='{$page_url}$counter'>$counter</a></li>";
                }
                $setPaginate .= "<li class='dot'>..</li>";
                $setPaginate .= "<li><a href='{$page_url}$lpm1'>$lpm1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}$setLastpage'>$setLastpage</a></li>";
            } else {
                $setPaginate .= "<li><a href='{$page_url}1'>1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}2'>2</a></li>";
                $setPaginate .= "<li class='dot'>..</li>";
                for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter ++) {
                    if ($counter == $page)
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate .= "<li><a href='{$page_url}$counter'>$counter</a></li>";
                }
            }
        }

        if ($page < $counter - 1) {
            $setPaginate .= "<li><a href='{$page_url}$next'>Next</a></li>";
            $setPaginate .= "<li><a href='{$page_url}$setLastpage'>Last</a></li>";
        } else {
            $setPaginate .= "<li><a class='current_page'>Next</a></li>";
            $setPaginate .= "<li><a class='current_page'>Last</a></li>";
        }

        $setPaginate .= "</ul>\n";
    }

    return $setPaginate;
}



?>