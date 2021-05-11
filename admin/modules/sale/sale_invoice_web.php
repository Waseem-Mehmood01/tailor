<?php
$auto_print=0;
if(isset($_GET['invoice_id'])){
	$invoice_id = $_GET['invoice_id'];
	$invoice = DB::queryFirstRow("select * from sale_invoice where sale_invoice_id='".$invoice_id."'");
	if(isset($_GET['is_print'])){
			if($_GET['is_print']=='1'){
				$auto_print=1;
			}
	}
?>

        <section class="content-header">
          <h1>
          	Sales
            <small>Sale Invoice</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="<?php echo SITE_ROOT; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Sales</a></li>
            <li class="active">Sale Invoice</li>
          </ol>
        </section>
        <!-- Main content -->
        <section >
          <!-- title row -->
          <div class="">
             <div class="box-header">
              <small><?php echo get_store_workstation_name($invoice['reciept_workstation']); ?> </small>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
			<input type="hidden" id="auto_print" id="auto_print" value="<?php echo $auto_print; ?>" />

<?php 

$printAble='';
$printAble .='		<div class="" style="padding:1px 1px; margin-top:1px;" id="printAble">
		<style>
@media print{
	h1,h2,h3,h4,strong,b{
		    font-family: serif!important;
	}
	td{
		font-size:10px;
	}
	.box-header, .box-footer, .box-footer, footer, header,.pace, .box-title, .box-tools, .content-header,  .btn, .with-border{
		display:none;
	}
    body{
        margin: 1px 1px!important;
    }
}


</style>
		<table width="100%">
			<tbody>
            <tr>
                <td colspan="2" style="text-align:center;"><h5>CUSTOMER COPY</h5></td>
               
            </tr>
			<tr>
			<td>P.O Box: 6529</td>
            <td>Tel: +1-000-000000</td>
			</tr>
            <tr>
			<td>VAT Reg.No.: 123XYZABC</td>
            <td style="text-align:right;">'.date('d-m-Y H:i A', strtotime($invoice['created_on'])).'</td>
			</tr>

            
			<tr>
			<td style="width: 35%;">Cashier:
			<td style="width: 65%;">Receipt#: '.$invoice['sale_no'].'
			</td>
			</tr>
			
			
			<tr>
			<td>'.strtoupper($invoice['created_by']).'</td>
			<td>Ref#: '.$invoice['ref_no'].'</td>
			</tr>
			
			<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			</tr>
<tr style="text-align:center;">>
			<td colspan="2">-------------------------</td>
			</tr>
<tr style="text-align:center;">>
			<td colspan="2">TAX INVOICE</td>
			</tr>
<tr style="text-align:center;">>
			<td colspan="2">-------------------------</td>
			</tr>
			</tbody>
		</table>
	
	
	<table width="100%">
		<thead style="text-align: center;">
		<tr style="border:1px solid black;">
			<td style="text-align: center;">&nbsp;Item</td>
			<td style="text-align:left; padding-left:4%;">&nbsp;Description</td>
<td style="text-align: center;">&nbsp;Qty</td>
			<td style="text-align: center;">&nbsp;Price</td>
<td style="text-align: center;">&nbsp;Total</td>
		</tr>
		</thead>
		<tbody style="border-bottom:1px solid black; text-align: center;">';
		?>
		<?php 
			$invoice_detail = DB::query("select * from sale_invoice_detail where sale_invoice_id = $invoice_id");
			foreach($invoice_detail as $invo){
			    $desc = '';
			    if(strlen($invo['description']) > 30){
			        $desc = substr($invo['description'], 0, 30).'..';
			    } else {
			        $desc = $invo['description'];
			    }
		$printAble .='
		<tr>
		<td>'.$invo['item'].'</td>
		<td style="text-align:left; padding-left:5%;">'.$desc.'</td>
		<td>'.$invo['qty'].'</td>
		<td style="text-align:right; padding-right:5%;">'.$invo['unit_price'].'</td>
		<td style="text-align:right; padding-right:5%;">'.$invo['total'].'</td>
		</tr>';
		 }
                 
		$printAble .='</tbody>';
                $printAble .='<tfoot style="text-align: center;">';
                $printAble .='<tr>';
                 $printAble .='<td></td>';
                 $printAble .='<td colspan="2" style="text-align: right;">'.$invoice['total_qty'].' Item(s)</td>';
                 $printAble .='<td></td>';
                 $printAble .='<td></td>';
                 $printAble .= '</tr>';
                 $printAble .='</tfoot>';
	$printAble .='</table>
<BR>
				<table style="float: right;">
					<tr>
						<td><b>TOTAL: </b></td>
	 					<td><b>USD '.$invoice['total_amount'].'</b>(inc.VAT)</td>
					</tr>
					<tr><td colspan="2"><b>----VAT Description----</b></td></tr>	
					<tr>
						<td>Sub-Total: </td>
						<td><b>USD '.$invoice['sub_total'].'</b></td>
					</tr>
					<tr>
						<td>VAT(5%): </td>
						<td>USD '.$invoice['tax_amount'].'</td>
					</tr>
					<tr>
						<td>Discount: </td>
						<td>USD '.$invoice['dis_amount'].'</td>
					</tr>
					<tr>
						<td><b>TOTAL: </b></td>
	 					<td><b>USD '.$invoice['total_amount'].'</b></td>
					</tr>
					<tr>';
	if($invoice['tender']=='split'){
	    
	   $payments =  DB::query("SELECT * FROM sale_split_payment WHERE sales_invoice_id = '".$invoice_id."'");
	    
	   foreach ($payments as $payment){
	       
	       $printAble .= '<tr>';
	       
	       $printAble .= '<td>'.strtoupper(str_replace("_", " ", $payment['payment_type'])).' </td>';
	       
	       $printAble .= '<td>USD '.$payment['amount'].'</td>';
	       
	       $printAble .= '</tr>';
	       
	   }
	    
	} else {
	    
		$printAble .= '<td>'.strtoupper($invoice['tender']).' </td>';
		
		$printAble .= '<td>USD '.$invoice['tender_amount'].'</td>';
		
	}
		$printAble .= '</tr>
					<tr>
						<td>Change Due: &nbsp;&nbsp;</td>
	 					<td>USD '.$invoice['due_amount'].'</td>
					</tr>
				</table>	
	<table width="100%" style="text-align:center;">
		<tbody>
		<td width="100%"><BR>Thank you for shopping with us<BR></td>
		</tr>
		</tbody>
	</table>

</div><!-- /.box-body -->';
echo $printAble;
	/** SEND MAIL **/
	if(isset($_GET['action'])){
		$action = $_GET['action'];
		if($action=='mail'){
			
									/* ||)) 
									   ||\\ ecipient
													***/
									echo '<div id="loadingdiv">
											  <p class="ajax-loader"><img src="'.SITE_ROOT.'images/loading.gif" >
												<br>Sending..
											</p>	
											</div>';
									$to = "support@centaurusint.com";
									$subject = "Sale Invoice-".$invoice_id;
									$headers = "From: CentaurusInt <no-reply@centaurusint.net>";
									$headers .= "MIME-Version: 1.0\r\n";
									$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";	
									if(mail($to,$subject,$printAble,$headers)){
									$message = '<div class="alert alert-success alert-dismissible">
													<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
													<h4><i class="icon fa fa-check"></i>Mail Sent!</h4>
													Sale Invoice sent successfully.
												  </div>';
									DB::Update('sale_invoice',
												array('is_email_sent'=>'1'),
												'sale_invoice_id=%s',$invoice_id
												);
									
									} else {
									 echo "<script>alert('Mail sending fail to:');location.href='?route=modules/sale/sale_invoice_view&invoice_id=".$invoice_id."';</script>";
									}
		}
	}
?>
<BR><BR><BR><BR>
	 <div class="col-md-4 pull-right"> 
		<a class="btn btn-default" href="?route=modules/sale/view_all_sale"  /><i class="glyphicon glyphicon-chevron-left"></i>&nbsp;View All</a>
		<button id="printForm" class="btn btn-success" onclick="javascript:printInvoice('printAble');" /><i class="glyphicon glyphicon-print"></i>&nbsp;PRINT</button>
		<!--<<a class="btn btn-primary" href="?route=modules/sale/sale_invoice_view&action=mail&invoice_id=<?php // echo $invoice_id; ?>" /><i class="glyphicon glyphicon-envelope"></i>&nbsp;E-mail</a>
		<a class="btn btn-default" target="_BLANK" href="<?php // echo SITE_ROOT."home/export_sale.php?invoice_id=".$invoice_id; ?>"  /><i class="glyphicon glyphicon-export"></i>&nbsp;Export</a>-->
		<a class="btn btn-primary" href="?route=modules/sale/sale_invoice_edit&invoice_id=<?php echo $invoice_id; ?>" /><i class="glyphicon glyphicon-pencil"></i>&nbsp;Edit</a>
		<a class="btn btn-success" href="?route=modules/sale/sale_invoice"  /><i class="glyphicon glyphicon-plus"></i>&nbsp;New</a>
		
		</div>
<BR><BR><BR><BR><BR><BR>
          <div class="box-footer">
	
             <small></small>
            </div><!-- /.box-footer-->
          </div><!-- /.box -->
		  <?php echo @$message;
			
	
		  ?>
     	 </section><!-- /.content -->    
<?php } else { die("Whoops..! Something went wrong go back"); } ?>


    <script language="javascript" type="text/javascript">
		
        function printInvoice(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";
			
			
			window.focus();
            //Print Page
            window.print();
            
           

            window.onafterprint = function() {
            	location.href = '?route=modules/sale/sale_invoice';
            };
            
    		
        	window.close();
        	
			

            //Restore orignal HTML
            document.body.innerHTML = oldPage;

          
        }
		


		$(function(){
			var auto = $("#auto_print").val();
			if(auto=='1'){
				printInvoice('printAble');
			}
		});
		
		$(function(){
		$('.alert').delay(2000).hide('slow',function(){
			location.href="?route=modules/sale/sale_invoice_view&invoice_id=<?php echo $invoice_id; ?>";
		});
});
    </script>
