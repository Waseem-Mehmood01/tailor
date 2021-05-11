<?php
require ('functions.php');

if (isset($_POST['invoice_id'])) {

    $quote_card_info_id = (int) trim($_POST['invoice_id']);
    $status = $_POST['status'];
    $remarks = $_POST['remarks'];

    $qr = DB::query("SELECT * FROM `quote_card_info_timline` WHERE  quote_card_info_id  =" . $quote_card_info_id);
   /*
    * THIS IS TOTALY A WRONG METHOD FOR UPDATING STATUS
    * LETS SAY IF WE WANT TO TRACK WHEN LOGO RECIEVED AND WHEN DESIGN WHEN PRODUCTION
    */
    $date = "CURRENT_TIMESTAMP";
    if ($qr != NULL) {
        $pr = DB::query("UPDATE quote_card_info_timline SET status ='$status',remarks= '$remarks',created_on=$date WHERE  quote_card_info_timline_id =" . $qr[0]['quote_card_info_timline_id']);
    } else {
        $pr = DB::query("INSERT INTO quote_card_info_timline(quote_card_info_id, status, remarks, created_on) VALUES ($quote_card_info_id, '$status', '$remarks', $date)");
    }
    /*
     * if($quote_card_info_id<>''){
     *
     * // $pr = DB::query("INSERT INTO quote_card_info_timline(quote_card_info_id, status, remarks, created_on) VALUES ($quote_card_info_id, '$status', '$remarks', $date)");
     *
     *
     * // }
     * VERY BAD TECHNIQUE TO REDIRECT PAGE USING AJAX RESPONSE
     * 
     * // 
     */
    header('location: index.php?route=modules/quote/view_quotes');
}

?>

