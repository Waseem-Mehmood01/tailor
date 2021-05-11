<?php

$text='"Are you sure to want reverse this invoice?"';

if(isset($_GET['delete'])){
    if(isset($_GET['sale_invoice_id'])){
        $rec_type = $_GET['reciept_type'];
        if($rec_type=='sale'){
            $sale_invoice_id = (int)trim($_GET['sale_invoice_id']);
            DB::update('sale_invoice', array('is_reverse'=>'1'), 'sale_invoice_id=%s', $sale_invoice_id);
            
            $s_detail = DB::query("SELECT sd.`item`, sd.`size`, sd.`qty` FROM sale_invoice_detail sd WHERE sd.`sale_invoice_id` = '".$sale_invoice_id."'");
            
            
            foreach($s_detail as $si){
                
                /*
                 * UPDATE STOCK ROLL BACK
                 */
                
                $u_sql = "UPDATE products_price pp SET
                    pp.`stock` = (pp.`stock` + ".(int)$si['qty'].")
                    WHERE pp.`products_id` = '".(int)$si['item']."'
                    AND pp.`size` = '".$si['size']."'";
                
                DB::query($u_sql);
            }
            echo '<script>
			$(document).ready(function() {
				 $( "#msgDiv" ).fadeIn("slow").delay(1000).fadeOut("slow",function(){
					window.location.href="?route=modules/sale/view_all_sale";
				 });
			});
			 </script>';
        } else {
            echo '<script>
			alert("Return Invoice can not be reverse");
			window.location.href="?route=modules/sale/view_all_sale";
			 </script>';
        }
        
    }
}
?>

<?php


if(isset($_POST['btnChangeStatus'])){
  
   /* print_r($_POST);
    die; */
    
    @extract($_POST);
    $phone_notify = 0;
    $email_notify = 0;
    $invoiceId = (int)trim($_POST['invoice_id']);
    if(isset($_POST['phone_notify'])){
        $phone_notify = 1;
    }
    if(isset($_POST['email_notify'])){
        $email_notify = 1;
    }
    
    
     if($phone_notify==1){
     echo '<script>
                $(function(){
                $.ajax({
                  method: "POST",
                  url: "../twiliosms/send_sms_insta.php",
                  data: { 
                        status: "'.trim($_POST['status']).'",
                        phone: "'.trim($c_phone).'",
                         invoice_id: "'.$invoice_id.'",
                         shipment_no: "'.$shipment_no.'"
                         },
                  success: function(e){ console.log(e);}
                });   
                });
            </script>';
    
    }
    

    $insert_h = DB::insert('pos_status_history', array(
        'sale_invoice_id'   => $invoiceId,
        'status_id'         => '0',
        'status'            => $_POST['status'],
        'remarks'           => $_POST['remarks'],
        'phone_notify'      => $phone_notify,
        'email_notify'      => $email_notify
    ));
    
   
    $if_exist = DB::queryFirstField("SELECT COUNT(*) AS total FROM instagram_pos WHERE sale_invoice_id = '".$invoiceId."'");
    
    if($if_exist>0){
        
        $insert_h = DB::update('instagram_pos', array(
            'instagram_id'  => $instagram_id,
            'fname'         => $fname,
            'lname'         => $lname,
            'city'          => $city,
            'state'         => $state,
            'zip'           => $zip,
            'address'       => $address,
            'shipment_no'  => $shipment_no
        ),'sale_invoice_id=%s', $invoiceId);
        
    } else {
        
        $insert_h = DB::insert('instagram_pos', array(
            'sale_invoice_id'=> $invoiceId, 
            'instagram_id'  => $instagram_id,
            'fname'         => $fname,
            'lname'         => $lname,
            'city'          => $city,
            'state'         => $state,
            'zip'           => $zip,
            'address'       => $address,
            'shipment_no'  => $shipment_no
        ));
    }
    
    if($c_phone <> ''){
        DB::update('sale_invoice', array(
            'customer_phone'  => $c_phone
        ),'sale_invoice_id=%s', $invoiceId);
    }
    
    if($c_email <> ''){
        DB::update('sale_invoice', array(
            'customer_email'  => $c_email
        ),'sale_invoice_id=%s', $invoiceId);
    }
    
    
    if($insert_h){
        echo '<script>
			$(document).ready(function() {
				 $( "#msgDiv" ).html("<h4>Order status has been changed</h4>").fadeIn("slow").delay(1000).fadeOut("slow",function(){
					window.location.href="?route=modules/sale/view_all_sale_insta";
				 });
			});
			 </script>';
    }
    
    
}




$where_clause = "";
$having_clause = "";
$default_record_msg = 'Invoices of '.date('M-Y');

$user_id = isset($_POST['user_id']) ? $_POST['user_id']: array();
if(empty($user_id)){ $user_id = isset($_GET['user_id']) ? $_GET['user_id']: array(); }
$tender = isset($_POST['tender']) ? $_POST['tender']: array();
if(empty($tender)){ $tender = isset($_GET['tender']) ? unserialize($_GET['tender']): array(); }


if(isset($_POST['btnFilter'])){
	@extract($_POST);	
} else {
        @extract($_GET);
}


	if(@$from_date<>''){
		@$from_date_time = @$from_date.' '.@$from_time;
		$where_clause .= " 
		AND si.`created_on` >= '".getDateTime(@$from_date_time, "mySQL")."'";
		$default_record_msg = 'Invoices of '.@$from_date_time.' to Now';
	}
	
	if(@$to_date<>''){
		@$to_date_time = @$to_date.' '.@$to_time;
		$where_clause .= " 
		AND si.`created_on` <= '".getDateTime(@$to_date_time, "mySQL")."'";
		$default_record_msg = 'Invoices of '.@$from_date_time.' to '.@$to_date_time;
	}
	
	
	

		
		if(@$item_no_from<>'' AND @$item_no_to<>''){

			if(@$item_no_log <> '='){
				$where_clause .=" 
				AND sd.`item` NOT BETWEEN ".@$item_no_from." AND ".@$item_no_to." ";
			} else {
				
				
				if(@$item_no_from<>''){
						$where_clause .= " 
						AND sd.`item` >= '".@$item_no_from."'";
				}
				
				if(@$item_no_to<>''){
						$where_clause .= " 
						AND sd.`item` <= '".@$item_no_to."'";
				} 
			}
		} else {
				if(@$item_no_from<>''){
					if(@$item_no_log=='='){
						$where_clause .= " 
						AND sd.`item` >= '".@$item_no_from."'";
					} 
				}
				
				if(@$item_no_to<>''){
					if(@$item_no_log=='='){
						$where_clause .= " 
						AND sd.`item` <= '".@$item_no_to."'";
					}
			
				}
		}
		
		
		
		
		
		
		if(@$desc <> ''){

			if(@$desc_log == '='){
				$where_clause .=" 
				AND sd.`description` LIKE '".@$desc."' ";
			} else {
				$where_clause .=" 
				AND sd.`description` NOT LIKE '".@$desc."' ";
			}
		}
	


	
	

	
	if(!empty($user_id)){
                                if(is_serialized($user_id)){
                                    $user_id = unserialize($user_id);
                                }
				$users_array = implode("','", @$user_id);

		
		if(@$user_id_log == '='){
			$where_clause .=" 
			AND si.`created_by` IN ('".$users_array."') ";
		} else {
			$where_clause .=" 
			AND si.`created_by` NOT IN ('".$users_array."') ";
		}
	}
	
                
	if(!empty($tender)){
                                if(is_serialized($tender)){
                                    $tender = unserialize($tender);
                                }
				$tender_array = implode("','", @$tender);

		
		if(@$tender_log == '='){
			$where_clause .=" 
			AND si.`tender` IN ('".$tender_array."') ";
		} else {
			$where_clause .=" 
			AND si.`tender` NOT IN ('".$tender_array."') ";
		}
	}

	

	
	if(!empty($customer)){
                                
                                if(is_serialized($customer)){
                                    $customer = unserialize($customer);
                                }
		
				$customer_array = implode("','", @$customer);

		
		if(@$customer_log == '='){
			$where_clause .=" 
			AND si.`customer` IN ('".$customer_array."') ";
		} else {
			$where_clause .=" 
			AND si.`customer` NOT IN ('".$customer_array."') ";
		}
	}
	
	
	
	if(@$qty_from<>'' AND @$qty_to<>''){

			if(@$qty_log <> '='){
				$having_clause .=" 
			                      AND total_qty NOT IN ('".@$qty_from."' , '".@$qty_to."') ";
			} else {
				
				$having_clause .=" 
			                     AND total_qty >= '".@$qty_from."' AND total_qty <= '".@$qty_to."' ";
			}
		} else {
				if(@$qty_from<>''){
						$having_clause .=" 
			                      AND total_qty >= '".@$qty_from."' ";
			
				}
				
				if(@$qty_to<>''){
					$having_clause .=" 
			                      AND total_qty <= '".@$qty_to."'";
			
				}
		}
	
	
		if(@$dis_perc_from<>'' AND @$dis_perc_to<>''){

			if(@$dis_perc_log <> '='){
				$having_clause .=" 
			                     AND total_dis_perc NOT IN ('".@$dis_perc_from."' , '".@$dis_perc_to."') ";
			} else {
				
				
				if(@$dis_perc_from<>''){
					$having_clause .= " 
						AND total_dis_perc >= '".@$dis_perc_from."' ";
				}
				
				if(@$dis_perc_to<>''){
						$having_clause.= " 
						AND  total_dis_perc <= '".@$dis_perc_to."' ";
				} 
			}
		} else {
				if(@$dis_perc_from<>''){
					if(@$dis_perc_log=='='){
						$having_clause .= " 
                        AND  total_dis_perc >= '".@$dis_perc_from."' ";
					} 
				}
				
				if(@$dis_perc_to<>''){
					if(@$dis_perc_log=='='){
						$having_clause.= " 
						AND total_dis_perc <= '".@$dis_perc_to."' ";
					}
			
				}
		}
		
		
		
		
		if(@$dis_amount_from<>'' AND @$dis_amount_to<>''){

			if(@$dis_amount_log <> '='){
				$having_clause .=" 
			                     AND total_dis NOT IN ('".@$dis_amount_from."' , '".@$dis_amount_to."') ";
			} else {
				
				
				if(@$dis_amount_from<>''){
						$having_clause .= " 
						AND total_dis >= '".@$dis_amount_from."' ";
				}
				
				if(@$dis_amount_to<>''){
						$having_clause .= " 
						AND total_dis <= '".@$dis_amount_to."' ";
				} 
			}
		} else {
				if(@$dis_amount_from<>''){
					if(@$dis_amount_log=='='){
						$having_clause .= " 
						AND total_dis >= '".@$dis_amount_from."' ";
					} 
				}
				
				if(@$dis_amount_to<>''){
					if(@$dis_amount_log=='='){
						$having_clause .= " 
						AND total_dis <= '".@$dis_amount_to."' ";
					}
			
				}
		}
	
	

	
	



if(@$from_date=='' AND @$to_date==''){
 $where_clause .=" 
 AND MONTH(si.`created_on`) = MONTH('".getDateTime('0',"mySQL")."') 
AND YEAR(si.`created_on`) = YEAR('".getDateTime('0',"mySQL")."') ";	
}


/*
 * 
 *  INSTAGRAM SALE ONLY
 * 
 */


$where_clause .= " AND si.`customer`='Instagram' ";



?>




<?php
//Draft Expense Voucher
$tbl = new HTML_Table('', 'table table-hover table-striped table-bordered');
$tbl->addTSection('thead');
$tbl->addRow();
$tbl->addCell('Invoice #', '', 'header');
$tbl->addCell('Reciept Type.', '', 'header');
$tbl->addCell('Tender', '', 'header');
$tbl->addCell('Customer', '', 'header');
$tbl->addCell('No. of Item', '', 'header');
$tbl->addCell('Sub Total', '', 'header');
$tbl->addCell('Discount', '', 'header');
$tbl->addCell('Tax', '', 'header');
$tbl->addCell('Total', '', 'header');
$tbl->addCell('Cost', '', 'header');
$tbl->addCell('Profit', '', 'header');
$tbl->addCell('Date', '', 'header');
$tbl->addCell('Status', '', 'header');
$tbl->addCell('Actions', '', 'header');
$tbl->addTSection('tbody');
?>

<?php
        
        if($having_clause<>''){ 
            $having_clause = " HAVING 1=1 ".$having_clause;
        }
        
        if(isset($_GET["page"])) {
		$page = (int)$_GET["page"];
	} else{
	$page = 1;	
	}
	
	$setLimit = 20;
	$pageLimit = ($page * $setLimit) - $setLimit;
  /*      
$sql = "SELECT * FROM sale_invoice s WHERE s.`is_reverse`='0' ".$where_clause." ".$having_clause." ORDER BY s.`sale_invoice_id` DESC";
*/
        $sql = "SELECT sd.`item` ,sd.`description`,  si.*,  
		si.`created_on` AS last_sold_on,
		si.`total_amount` AS total_sale,SUM(sd.`total_cost`) AS total_cost, 
		si.`dis_perc` AS total_dis_perc, si.`dis_amount` AS total_dis,
		si.`tax_perc` AS total_tax_perc, si.`tax_amount` AS total_tax
		 FROM sale_invoice_detail sd 
				LEFT JOIN sale_invoice si
				ON(si.`sale_invoice_id`=sd.`sale_invoice_id` AND  si.`reciept_type`='sale'  AND si.`is_reverse`='0') 
				WHERE 1=1 ".$where_clause." 
				 GROUP BY si.`sale_invoice_id` ".$having_clause." ORDER BY sale_invoice_id DESC";
        
        $sql2 = $sql;
	DB::query($sql2);
	$total_records = DB::count();
        $sql .= ' LIMIT '.$pageLimit.', '.$setLimit;

/*        
  echo '<pre>';
echo $sql;
echo '</pre>'; */


 $res = DB::query($sql);       
foreach($res as $row) {	
$tbl->addRow();
$tbl->addCell($row['sale_invoice_id']);
$tbl->addCell(strtoupper($row['reciept_type']));
$tbl->addCell(strtoupper($row['tender']));
$tbl->addCell($row['customer']);
$tbl->addCell($row['no_of_item']);
$tbl->addCell($row['sub_total']);
$tbl->addCell($row['dis_amount'].' ['.$row['dis_perc'].'%]');
$tbl->addCell($row['tax_amount']);
$tbl->addCell('<b>'.$row['total_amount'].'</b>');
$tbl->addCell('$'.$row['total_cost']);
$profit  = $row['total_amount']-$row['total_cost'];
$tbl->addCell('<b>$'.$profit .'</b>');
$tbl->addCell(date("d-m-Y", strtotime($row['date'])));
$status = DB::queryFirstField("SELECT `status` FROM pos_status_history WHERE sale_invoice_id = '".$row['sale_invoice_id']."' ORDER BY created_on DESC");
if($status == '' OR is_null($status)){
    $status = 'Pending';
}
$tbl->addCell($status.' <button data-id="'.$row['sale_invoice_id'].'" data-status="'.$status.'" data-phone="'.$row['customer_phone'].'" data-email="'.$row['customer_email'].'" type="button" class="btn btn-info btn-xs changestatus" data-toggle="modal" data-target="#statusModal"><span class="glyphicon glyphicon-pencil"></span> Change</button>');
$tbl->addCell("<div class='btn-group' role='group'>

<a class='pull btn btn-default btn-xs' href ='?route=modules/sale/sale_invoice_view&invoice_id=".$row['sale_invoice_id']."'>Detail&nbsp;<span class='glyphicon glyphicon-edit'></span></a>

<button type='button' class='btn btn-danger btn-xs dropdown-toggle' data-toggle='dropdown'>
    More.. <span class='caret'></span></button>
    <ul class='dropdown-menu' role='menu'>
	<li><a class='' href ='?route=modules/sale/sale_invoice_edit&invoice_id=".$row['sale_invoice_id']."'><span class='glyphicon glyphicon-pencil'></span>Edit&nbsp;</a></li>
	<li><a class='' href ='?route=modules/sale/view_all_sale&sale_invoice_id=".$row['sale_invoice_id']."&delete=yes&reciept_type=".$row['reciept_type']."' onclick='return confirm(".$text.");'><span class='glyphicon glyphicon-share-alt'></span>Reverse Sale&nbsp;</a></li> 
	</ul> 
	</div>
");

}
			  

?>



 <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
          		Instagram Customer Sale
            <small>List of All Sale Invoice Entry .</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">List of Sale Invoice</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

 <!-- Default box -->
          <div class="box">
            <div class="box-header with-border">
              <h4 class=""><?php echo $default_record_msg; ?></h4>
                        <a class="btn btn-primary d-inline" href="?route=modules/sale/view_all_sale">View All of This Month</a>
			<a class="btn btn-success d-inline" href="#"  data-toggle="modal" data-target="#modalFilter"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</a>
                        <a class="btn btn-default d-inline pull-right" href="?route=modules/sale/sale_invoice"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add New Invoice</a>
                          
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
			<div id="msgDiv" style="display:none;" class="alert alert-success alert-dismissible col-md-6 col-md-offset-3" >
						 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> Return!</h4>
						Invoice return back successfully.
					</div>
            <div class="box-body">
				<?php  echo $tbl->display(); ?>
                                
                                   <?php 	
					$page_url="?route=modules/sale/view_all_sale";
                                        if(isset($_POST['btnFilter'])){
                                            foreach ($_POST as $param_name => $param_val) {
                                                if($param_name=='reciept_workstation' || $param_name=='user_id' || $param_name=='tender'|| $param_name=='customer'){
                                                    $param_val = serialize($param_val);
                                                }
                                                $page_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else if(isset($_GET['btnFilter'])){
                                            foreach ($_GET as $param_name => $param_val) {
                                                $page_url .= "&".$param_name."=".$param_val;
                                            }
                                        } else {
                                            
                                        }
                                        
                                        $page_url .= "&";
                                        
                                        
					echo displayPaginationBelow($setLimit,$page,$sql2,$page_url); 
				
                                        ?>
            </div><!-- /.box-body -->
            
              <?php 
            $total_q = DB::query($sql2);
            $sub_total = 0.00;
            $tax_total = 0.00;
            $total = 0.00;
            foreach($total_q as $tot){
                if( ($tot['reciept_type']=='sale') AND ($tot['is_reverse']=='0') ){
                    $sub_total += $tot['sub_total'];
                    $tax_total += $tot['tax_amount'];
                    $total += $tot['total_amount'];
                }
            }
            ?>
            <div class="row">
            <div class="col-md-4 pull-right">
            	<table class="table">
            	<tr>
            		<td>Subtotal: </td>
            		<td><strong>$<?php echo $sub_total; ?></strong></td>
            	</tr>
            	<tr>
            		<td>Tax Amount: </td>
            		<td><strong>$<?php echo $tax_total; ?></strong></td>
            	</tr>
            	<tr>
            		<td><h3>Total: </h3></td>
            		<td><h3>$<?php echo $total; ?> USD</h3></td>
            	</tr>
            	</table>
            </div>
            </div>
            <div class="box-footer">
             
            </div>
          </div><!-- /.box -->
		</section> 



<script>
$(function(){
	$(".changestatus").on("click", function(){
		ID = $(this).data('id');
		$("#c_email").val($(this).data('email'));
		$("#c_phone").val($(this).data('phone'));
		$("#status").val($(this).data('status'));
		$("#invoice_id").val(ID);
		$.ajax({  
			type: "POST",  
			url: "ajax_instagram_pos.php",  
			data: {sale_invoice_id: ID},
			dataType: "json",
			success: function(data){
				console.log(data);
				$("#instagram_id").val(data['instagram_id']);
				$("#fname").val(data['fname']);
				$("#lname").val(data['lname']);
				$("#city").val(data['city']);
				$("#state").val(data['state']);
				$("#zip").val(data['zip']);
				$("#address").val(data['address']);
				$("#shipment_no").val(data['shipment_no']);
				
			}
		});
	});
});
</script>
        
        
  <!-- Modal -->
<div id="statusModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Change Status</h4>
      </div>
      <form class="form-horizontal" action="" method="POST">
          <div class="modal-body">
            <div class="form-group">
                <label class="control-label col-sm-3" for="status">Status:</label>
                <div class="col-sm-6">
                  <select class="form-control" name="status" id="status">
                    <option value="Pending">Pending</option>
                    <option value="Packing">Packing</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Canceled">Canceled</option>
                   </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">Shipment No.</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="shipment_no" id="shipment_no" placeholder="Courier tracking no.">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">Insta UserID</label>
                <div class="col-sm-6">
                  <input type="text" class="form-control" name="instagram_id" id="instagram_id" placeholder="#InstagramUserID">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">Customer Name</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="fname" id="fname" placeholder="First Name" required>
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="lname" id="lname" placeholder="Last Name" required>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">State</label>
                <div class="col-sm-6">
                 <select name="state" id="state" class="form-control select2">
            	<?php 
            	$states = DB::query("SELECT `name` FROM states s WHERE s.`country_id` = '231'");
            	foreach($states as $st){
            	    echo '<option value="'.$st['name'].'">'.$st['name'].'</option>';
            	}
            	?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-3">City</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="city" id="city" placeholder="City/Town">
                </div>
                <div class="col-sm-3">
                  <input class="form-control" name="zip" id="zip" placeholder="Zip Code" type="tel">
                </div>
              </div>
               <div class="form-group">
                <label class="control-label col-sm-3">Address</label>
                <div class="col-sm-6">
                  <textarea class="form-control" name="address" id="address" required></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label col-sm-3" for="notify">Customer Notify</label>
                <div class="col-sm-6">
                  <div class="checkbox"><label class=""><input type="checkbox" name="email_notify" value="email_notify">Email </label> <input type="text" value="" name="c_email" id='c_email'></div>
                  <div class="checkbox"><label class=""><input type="checkbox" name="phone_notify" value="phone_notify">SMS </label> <input type="text" value="" name='c_phone' id='c_phone'></div>
                </div>
              </div>
          </div>
          <div class="form-group">
                <label class="control-label col-sm-3" for="remarks">Remarks:</label>
                <div class="col-sm-6">
                  <textarea class="form-control" name="remarks" id="remarks" ></textarea>
                  <small class="form-text text-muted">*will be sent to customer</small>
                </div>
              </div>
      <input type="hidden" name="invoice_id" id="invoice_id">
      <div class="modal-footer">
      	<button type="submit" name="btnChangeStatus" id="btnChangeStatus" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div>

  </div>
</div>      
        
        
        
        
        
        		 <!-- Modal -->
  <div class="modal fade" id="modalFilter" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Filter By</h4>
        </div>
        <div class="modal-body">
			<form class="form-horizontal" name="frmFilter" id="frmFilter" action="?route=modules/sale/view_all_sale" method="POST">
			
			
			
			
			
			<div class="form-group">
				<label class="control-label col-sm-3">From:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control date-picker" value="<?php echo @$from_date; ?>" name="from_date" id="from_date" placeholder="dd-mm-yyyy">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control timepicker" value="<?php if(isset($from_time)){ echo @$from_time; } else { echo '9:00 AM'; } ?>" name="from_time" id="from_time" placeholder="hh:mm">
				</div>
				<div class="col-sm-3">
				  
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">To:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control date-picker" value="<?php echo @$to_date; ?>" name="to_date" id="to_date" placeholder="dd-mm-yyyy">
				</div>
				<div class="col-sm-3">
				  <input type="text" class="form-control timepicker" value="<?php if(isset($to_time)){ echo @$to_time; } else { echo '11:59 PM'; } ?>" name="to_time" id="to_time" placeholder="hh:mm">
				</div>
				<div class="col-sm-3">
				  
				</div>
			  </div>
		
			
			<div class="form-group">
				<label class="control-label col-sm-3">Item #:</label>
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="item_no_from" id="item_no_from" value="<?php echo @$item_no_from; ?>" maxlength="4" placeholder="From">
				</div>
				
				<div class="col-sm-3">
				  <input type="text" class="form-control" name="item_no_to" value="<?php echo @$item_no_to; ?>" id="item_no_to" maxlength="4" placeholder="To">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="item_no_log">
					<option <?php if(@$item_no_log=='=') { echo 'SELECTED'; } ?> value="=">Include</option>
					<option <?php if(@$item_no_log=='!=') { echo 'SELECTED'; } ?> value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			  <div class="form-group">
				<label class="control-label col-sm-3">Item Name:</label>
				<div class="col-sm-6">
				  <input type="text" value="<?php echo @$desc; ?>" class="form-control" name="desc" id="desc" placeholder="">
				</div>

				<div class="col-sm-3">
				  <select class="form-control" name="desc_log">
					<option <?php if(@$desc_log=='=') { echo 'SELECTED'; } ?> value="=">Include</option>
					<option <?php if(@$desc_log=='!=') { echo 'SELECTED'; } ?> value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
			  
			
			  <div class="form-group">
				<label class="control-label col-sm-3">Tender:</label>
				<div class="col-sm-6">
				  <select class="form-control multiselect" name="tender[]" multiple="multiple" data-placeholder="Default All">
					
					<option <?php if(in_array('cash', @$tender)) { echo 'SELECTED'; } ?> value="cash">Cash</option>
					<option <?php if(in_array('card', @$tender)){ echo 'SELECTED'; } ?> value="card">Card</option>	
					<option <?php if(in_array('venmo', @$tender)) { echo 'SELECTED'; } ?> value="venmo">Venmo</option>
					<option <?php if(in_array('paypal', @$tender)) { echo 'SELECTED'; } ?> value="paypal">Paypal</option>
					<option <?php if(in_array('cashapp', @$tender)) { echo 'SELECTED'; } ?> value="cashapp">Cashapp</option>

				  </select>
				</div>
				<div class="col-sm-3">
				  <select class="form-control" name="tender_log">
					<option <?php if(@$tender_log=='=') { echo 'SELECTED'; } ?>  value="=">Include</option>
					<option <?php if(@$tender_log=='!=') { echo 'SELECTED'; } ?>  value="!=">Exclude</option>
				  </select>
				</div>
			  </div>
        
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-success" name="btnFilter" id="btnFilter">Apply</button>
        	<button type="button" class="btn btn-primary" name="btnReset" id="btnReset">Clear Filter</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      </form>
    </div>
  </div>
  

<script type="text/javascript">

$(function(){ $("#btnReset").on("click",function(){ $(".form-control").val("");});});

</script>
