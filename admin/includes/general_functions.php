<?php

function is_localhost()
{
    $whitelist = array(
        '127.0.0.1',
        '::1'
    );
    if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function getDateTime($time = 0, $form = "dtLong")
{
    Switch ($form) {
        case "dtVLong":
            $strform = "D, jS F, Y g:i:s A (\G\M\T O)";
            break;
        case "dtLong":
            $strform = "D, jS F, Y g:i A";
            break;
        case "dtShort":
            $strform = "jS M, Y g:i A";
            break;
        case "dtMin":
            $strform = "j-n-y G:i";
            break;
        case "dLong":
            $strform = "D, jS F Y";
            break;
        case "dShort":
            $strform = "j-M-Y";
            break;
        case "dMin":
            $strform = "j-n-y";
        case "dOnly":
            $strform = "Y-n-j";
            break;
        case "tLong":
            $strform = "G:i:s (\G\M\T O)";
            break;
        case "tShort":
            $strform = "G:i";
            break;
        case "mySQL":
            $strform = "Y-m-d H:i:s";
            break;
        default:
            $strform = "j-M-Y g:ia";
    }
    if ($time == 0) {
        $formated_time = date($strform);
    } else {
        $time = strtotime($time);
        $formated_time = date($strform, $time);
    }
    return $formated_time;
}

function formate_date_days($timestamp)
{
    $date = date('d/m/Y', strtotime($timestamp));

    if ($date == date('d/m/Y')) {
        $date = 'Today ' . date('h:m A', strtotime($timestamp));
    } elseif ($date == date('d/m/Y', strtotime("-1 days"))) {
        $date = 'Yesterday ' . date('h:m A', strtotime($timestamp));
    } else {
        $date = date('D, d M y, ', strtotime($timestamp)) . date('h:m A', strtotime($timestamp));
    }
    return $date;
}

// To Prevent SQL injection
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
{
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
        case "text":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "long":
        case "int":
            $theValue = ($theValue != "") ? intval($theValue) : "NULL";
            break;
        case "double":
            $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
            break;
        case "date":
            $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
            break;
        case "defined":
            $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
            break;
    }
    return $theValue;
}

function get_domain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}

function get_host()
{
    if ($host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
        $elements = explode(',', $host);

        $host = trim(end($elements));
    } else {
        if (! $host = $_SERVER['HTTP_HOST']) {
            if (! $host = $_SERVER['SERVER_NAME']) {
                $host = ! empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
            }
        }
    }

    // Remove port number from host
    $host = preg_replace('/:\d+$/', '', $host);

    return trim($host);
}

function round2dp($number)
{
    return number_format((float) $number, 2, '.', ',');
}

function col_index($string, $line)
{
    $found = 0;
    $i = 0;
    while ($found == 0 && $i < count($line)) {
        if ($line[$i] == $string) {
            $found = 1;
        } else {
            $i = $i + 1;
        }
    }
    if ($found == 0)
        return - 1;
    else
        return $i;
}

function parseTree($root, $arr)
{
    $return = array();
    // Traverse the tree and search for direct children of the root
    foreach ($arr as $child => $parent) {
        // A direct child is found
        if ($parent == $root) {
            // Remove item from tree (we don't need to traverse this again)
            unset($arr[$child]);
            // Append the child into result array and parse it's children
            $return[] = array(
                'name' => $child,
                'children' => parseTree($child, $arr)
            );
        }
    }
    return empty($return) ? null : $return;
}

function printTree($arr)
{
    if (! is_null($arr) && count($arr) > 0) {
        echo '<ul>';
        foreach ($arr as $node) {
            echo "<li>" . $node['sect_name'] . "";
            if (array_key_exists('children', $node)) {
                printTree($node['children']);
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

function is_serialized($data)
{
    if (! is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('N;' == $data) {
        return true;
    }
    if (! preg_match('/^([adObis]):/', $data, $badions)) {
        return false;
    }
    switch ($badions[1]) {
        case 'a':
        case 'O':
        case 's':
            if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                return true;
            }
            break;
        case 'b':
        case 'i':
        case 'd':
            if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                return true;
            }
            break;
    }
    return false;
}

function displayPaginationBelow($per_page, $page, $sql, $page_url)
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
                    $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
            }
        } elseif ($setLastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter ++) {
                    if ($counter == $page)
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
                $setPaginate .= "<li class='dot'>...</li>";
                $setPaginate .= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            } elseif ($setLastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $setPaginate .= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate .= "<li class='dot'>...</li>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter ++) {
                    if ($counter == $page)
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
                $setPaginate .= "<li class='dot'>..</li>";
                $setPaginate .= "<li><a href='{$page_url}page=$lpm1'>$lpm1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>$setLastpage</a></li>";
            } else {
                $setPaginate .= "<li><a href='{$page_url}page=1'>1</a></li>";
                $setPaginate .= "<li><a href='{$page_url}page=2'>2</a></li>";
                $setPaginate .= "<li class='dot'>..</li>";
                for ($counter = $setLastpage - (2 + ($adjacents * 2)); $counter <= $setLastpage; $counter ++) {
                    if ($counter == $page)
                        $setPaginate .= "<li><a class='current_page'>$counter</a></li>";
                    else
                        $setPaginate .= "<li><a href='{$page_url}page=$counter'>$counter</a></li>";
                }
            }
        }

        if ($page < $counter - 1) {
            $setPaginate .= "<li><a href='{$page_url}page=$next'>Next</a></li>";
            $setPaginate .= "<li><a href='{$page_url}page=$setLastpage'>Last</a></li>";
        } else {
            $setPaginate .= "<li><a class='current_page'>Next</a></li>";
            $setPaginate .= "<li><a class='current_page'>Last</a></li>";
        }

        $setPaginate .= "</ul>\n";
    }

    return $setPaginate;
}

function CategoryTreeSelect(&$output = null, $parent = 0, $indent = null, $selectID)
{
    $r = DB::query("SELECT categories_id, name FROM categories WHERE parent_id='" . $parent . "'");
    foreach ($r as $c) {
        $output .= '<option value="' . $c['categories_id'] . '"';
        if ($c['categories_id'] == $selectID) {
            $output .= 'SELECTED';
        }
        $output .= '>' . $indent . $c['name'] . "</option>";
        if ($c['categories_id'] != $parent) {
            // in case the current category's id is different that $parent
            // we call our function again with new parameters
            CategoryTreeSelect($output, $c['categories_id'], $indent . "&nbsp;&nbsp;&nbsp;&nbsp;", $selectID);
        }
    }
    // return the list of categories
    return $output;
}



function get_product_avg_cost($products_id)
{
    $avg = 0;
    $avg = DB::queryFirstField("SELECT AVG(cost_price) FROM products_price WHERE active = 1 AND  products_id = " . (int) $products_id);
    return round($avg, 2);
}

function get_product_avg_sale($products_id)
{
    $avg = 0;
    $avg = DB::queryFirstField("SELECT AVG(sale_price) FROM products_price WHERE active = 1 AND  products_id = " . (int) $products_id);
    return round($avg, 2);
}

function get_total_avg_cost()
{
    $avg = 0;
    $avg = DB::queryFirstField("SELECT AVG(pp.`cost_price`) FROM products_price pp
 LEFT JOIN products p
 ON(p.`products_id`=pp.`products_id`)
  WHERE p.`active`= 1 ");
    return round($avg, 2);
}

function get_total_avg_sale_price()
{
    $avg = 0;
    $avg = DB::queryFirstField("SELECT AVG(pp.`sale_price`) FROM products_price pp
 LEFT JOIN products p
 ON(p.`products_id`=pp.`products_id`)
  WHERE p.`active`= 1 ");
    return round($avg, 2);
}

function is_promotion($products_id)
{
    $is_promo = DB::queryFirstField("SELECT is_promotion FROM products WHERE products_id = '" . $products_id . "'");
    if ($is_promo == 1) {
        $is_promo = TRUE;
    } else {
        $is_promo = FALSE;
    }
    return $is_promo;
}

function has_discount($products_id, $size)
{
    $has_discount = DB::queryFirstField("SELECT count(*) AS total FROM discounts WHERE products_id= '" . (int) $products_id . "'");
    if ($has_discount > 0) {
        $get_size = DB::queryFirstField("SELECT size FROM discounts WHERE products_id= '" . (int) $products_id . "'");
        if ($get_size == '') {
            $sql = "SELECT * FROM discounts WHERE products_id= '" . (int) $products_id . "'";
        } else {
            $sql = "SELECT * FROM discounts WHERE products_id= '" . (int) $products_id . "' AND size = '" . stripslashes($size) . "'";
        }
        $is_promo = DB::queryFirstRow($sql);
        $promo = array(
            'has_offer' => 1,
            'min_order_qty' => $is_promo['min_order_qty'],
            'new_price' => $is_promo['new_price']
        );
    } else {
        $promo = array(
            'has_offer' => 0,
            'min_order_qty' => 1,
            'new_price' => 0.00
        );
    }
    return $promo;
}

function update_stock($size, $products_id)
{
    if (is_promotion($products_id)) {
        $sql = "UPDATE products_price SET stock = (stock - 1) WHERE products_id = '" . $products_id . "'";
        $subitems = DB::query("SELECT sub_items,sub_item_size FROM promotion_items WHERE item_no = '" . $products_id . "'");
        foreach ($subitems as $items) {
            update_stock($items['sub_items'], $items['sub_item_size']);
        }
    } else {
        $sql = "UPDATE products_price SET stock = (stock - 1) WHERE products_id = '" . $products_id . "' AND size = '" . $size . "'";
    }
    
    DB::query($sql);
}


function display_with_children($parentRow, $level)
{
    if ($level != 0) {
        echo '<li id="child_node_' . $level . '"><a target="_BLANK" href="?route=modules/shop/manage_products&cID=' . $parentRow['categories_id'] . '">' . $parentRow['name'];
        echo '</a>&nbsp;&nbsp;';
        echo '<a alt="Edit" data-metakeywords="' . $parentRow['meta_keywords'] . '" data-metatitle="' . $parentRow['meta_title'] . '" data-metadescription="' . $parentRow['meta_description'] . '" data-parent="' . $parentRow['parent_id'] . '" data-name="' . $parentRow['name'] . '" data-id="' . $parentRow['categories_id'] . '" title="Edit" data-toggle="modal" data-target="#editModal" class="text-success edit" href="#"><i class="fa fa-pencil"></i></a>';
    }
    // if your id column is integer, you don't need the quotation mark
    $result = DB::query('SELECT * FROM categories WHERE parent_id=' . $parentRow['categories_id'] . ';');
    if (DB::count() != 0) {
        echo '<ul>';
        // use the fetch_assoc to get an associative array
        foreach ($result as $row) {
            display_with_children($row, $level + 1);
        }
        echo '</ul>';
    }
    echo '</li>';
}

function get_category_name($categories_id)
{
    $cate = "";
    $cate = DB::queryFirstField("SELECT c.`name` FROM categories c WHERE c.`categories_id` = '" . (int) $categories_id . "'");
    return $cate;
}

function get_product_category($products_id)
{
    $cate = "";
    $cate = DB::queryFirstField("SELECT c.`name` FROM categories c LEFT JOIN products p ON(p.`categories_id` = .c.`categories_id`) WHERE p.`products_id` = '" . (int) $products_id . "'");
    return $cate;
}

function get_product_price($products_id)
{
    $price = '';
    $price = DB::queryFirstField("SELECT sale_price FROM products_price WHERE products_id  = '" . (int) $products_id . "'");
    return $price;
}

function get_product_name($products_id)
{
    $name = '';
    $name = DB::queryFirstField("SELECT name FROM products WHERE products_id  = '" . (int) $products_id . "'");
    return $name;
}

function get_manufacturers_name($manufacturers_id)
{
    $manuf = '';
    $manuf = DB::queryFirstField("SELECT NAME FROM manufacturers WHERE manufacturers_id = '" . (int) $manufacturers_id . "'");
    if (DB::count() < 1) {
        $manuf = '';
    }
    return $manuf;
}

function get_product_img($products_id)
{
    $name = '';
    $name = DB::queryFirstField("SELECT img_path FROM products_img WHERE products_id  = '" . (int) $products_id . "'");
    return $name;
}

function get_product_thmb($products_id)
{
    $name = '';
    $name = DB::queryFirstField("SELECT img_path FROM products_img WHERE products_id  = '" . (int) $products_id . "'");
    return '/images/thumbnail/' . $name;
}

function get_product_img_all_fields($products_id, $color)
{
    $all = DB::query("SELECT * FROM products_img WHERE products_id  = '" . (int) $products_id . "' and color = '" . $color . "'");
    // print_r($all[0]['img_path']);die(1);
    return $all;
}

function get_sale_no()
{
    $n = DB::queryFirstField("SELECT COUNT(*) as total_sale FROM sale_invoice
WHERE YEAR(created_on) = YEAR(CURDATE())
AND MONTH(created_on) = MONTH(CURDATE())
AND created_on <= CURDATE() + INTERVAL 30 DAY");
    $n = $n + 1;
    return $n;
}

function resolove_barcode($barcode)
{
    $get_item = '';

    $get_item = DB::queryFirstField("SELECT products_id FROM products_price WHERE barcode = '" . trim($barcode) . "' OR products_id = '" . trim($barcode) . "'");

    return $get_item;
}

function get_price_id($barcode)
{
    $get_id = '';
    $get_id = DB::queryFirstField("SELECT products_price_id FROM products_price WHERE barcode = '" . trim($barcode) . "'");

    return $get_id;
}

function get_invoice_cost($sale_invoice_id)
{
    $cost = DB::queryFirstField("SELECT SUM(pp.`cost_price`*sd.`qty`) AS cost  FROM products_price pp, sale_invoice_detail sd
WHERE  sd.`sale_invoice_id` = '" . $sale_invoice_id . "'
AND
CASE
    WHEN sd.`size` = '' THEN pp.`products_id` = sd.`item`
    WHEN sd.`size` !='' THEN pp.`products_id` = sd.`item` AND pp.`size` LIKE sd.`size`
  END");

    if ($cost == '') {
        $cost = 0.00;
    }

    return $cost;
}

function make_bitly_url($url, $login, $appkey, $format = 'xml', $version = '2.0.1')
{
    // create the URL
    $bitly = 'http://api.bit.ly/shorten?version=' . $version . '&longUrl=' . urlencode($url) . '&login=' . $login . '&apiKey=' . $appkey . '&format=' . $format;

    // get the url
    // could also use cURL here
    $response = file_get_contents($bitly);

    // parse depending on desired format
    if (strtolower($format) == 'json') {
        $json = @json_decode($response, true);
        return $json['results'][$url]['shortUrl'];
    } else // xml
    {
        $xml = simplexml_load_string($response);
        return 'http://bit.ly/' . $xml->results->nodeKeyVal->hash;
    }
}

function get_quote_status($quote_card_info_id)
{
    $status = DB::queryFirstField("SELECT qt.`status` FROM quote_card_info_timline qt WHERE qt.`quote_card_info_id` = '" . (int) $quote_card_info_id . "' ORDER BY qt.`quote_card_info_timline_id` DESC");
    if ((DB::count() < 1) or ($status == '')) {
        $status = 'New Lead';
    }

    return $status;
}

function get_quote_status_name($status_id)
{
    return DB::queryFirstField("select status_name from quote_status where quote_status_id = '" . (int) $status_id . "'");
}

function get_conversation_text($conversation_sms_id)
{
    $text = DB::queryFirstField("SELECT cs.`text` FROM conversation_sms cs WHERE cs.`conversation_sms_id` = '" . (int) $conversation_sms_id . "'");
    return substr($text, 0, 50);
}

function get_customer_name_by_phone($contact)
{
    $row = DB::queryFirstRow("SELECT fname,lname FROM quote_customers WHERE contact = '" . $contact . "'");
    if (DB::count() > 0) {
        return $row['fname'] . ' ' . $row['lname'];
    } else {
        return $contact;
    }
}

function get_quote_remarks($quote_card_info_id)
{
    $status = DB::queryFirstField("SELECT qt.`remarks` FROM quote_card_info_timline qt WHERE qt.`quote_card_info_id` = '" . (int) $quote_card_info_id . "' ORDER BY qt.`created_on` DESC");
    if ((DB::count() < 1) or ($status == '')) {
        $status = '';
    }

    return $status;
}

function checkInvoiceWithLead($leads_id)
{
    $count = DB::queryFirstField("SELECT sale_invoice_id FROM sale_invoice WHERE quote_card_info_id ='" . $leads_id . "'");
    if (DB::count() > 0) {
        return $count;
    } else {
        return 0;
    }
}

function getLeadDateTime($quote_card_info_id)
{
    return DB::queryFirstField("SELECT created_on FROM quote_card_info WHERE quote_card_info_id = '" . (int) $quote_card_info_id . "'");
}

function find_quote_status($status_name, $quote_card_info_id)
{
    $has_found = FALSE;
    $total = DB::queryFirstField("SELECT COUNT(*) AS total FROM quote_card_info_timline qt WHERE qt.`status` LIKE '" . $status_name . "' AND qt.`quote_card_info_id`='" . $quote_card_info_id . "'");
    if ($total > 0) {
        $has_found = TRUE;
    }
    return $has_found;
}

function get_quote_status_percent($quote_card_info_id)
{
    // $get_status = get_quote_status($quote_card_info_id);
    $comple_perc = 0;

    if (find_quote_status('New Lead', $quote_card_info_id)) {
        $comple_perc = 0;
    }

    if (find_quote_status('Waiting on Approval', $quote_card_info_id)) {
        $comple_perc = 25;
    }
    if (find_quote_status('Design Approved', $quote_card_info_id)) {
        $comple_perc = 45;
    }
    if (find_quote_status('In Production', $quote_card_info_id)) {
        $comple_perc = 75;
    }
    if (find_quote_status('Shipped', $quote_card_info_id)) {
        $comple_perc = 100;
    }

    /*
     * switch ($get_status) {
     * case 'New Lead':
     * $comple_perc = 0;
     * break;
     * case 'Waiting on Approval':
     * $comple_perc = 25;
     * break;
     * case 'Design Approved':
     * $comple_perc = 45;
     * break;
     * case 'In Production':
     * $comple_perc = 75;
     * break;
     * case 'Shipped':
     * $comple_perc = 100;
     * break;
     * default:
     * break;
     * }
     */
    return $comple_perc;
}
