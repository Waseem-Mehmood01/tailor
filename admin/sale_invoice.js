$(document).ready(function() {
	var i=$('table tr').length;
	$(".addmore").on('click',function(){
		addInvoiceRow();
	});
	
	$("#tender_amount").on("change keypress keydown keyup", function(){
		calculateChangeDue();
	});

});	





//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//deletes the selected table rows
$(".delete").on('click', function() {
	$('.case:checkbox:checked').parents("tr").remove();
	$('#check_all').prop("checked", false); 
	calculateSubTotal();
	calculateTotal();
	count_qty();
        calculateTax();
        calculateDisc();

});

//price change
$(document).on('change keyup blur','.changesNo',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	qty = $('#qty_'+id[1]).val();
	rate = $('#rate_'+id[1]).val();
	sub_total = parseFloat(qty)*parseFloat(rate);
	sub_total = parseFloat(sub_total).toFixed(2);
	if( qty!='' && rate !='' ) $('#total_'+id[1]).val( sub_total );
	calculateSubTotal();
	calculateTotal();
	count_qty();
        calculateTax();
        calculateDisc();

});

/*
$('#shipping').on('blur keydown keypress',function(e){
        calculateSubTotal();
	calculateTotal();
        calculateTax();
        calculateDisc();
});
*/
$('#tax').on('blur keydown keypress',function(e){
        calculateSubTotal();
	calculateTotal();
        calculateTax();
        calculateDisc();
});
$('#disc').on('blur keydown keypress',function(e){
        calculateSubTotal();
	calculateTotal();
        calculateTax();
        calculateDisc();
});

/*
$(document).on('blur keydown keypress','.item_code',function(e){
	if (e.keyCode == '13'|| e.keyCode == '9'){ 
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	item = $('#item_'+id[1]).val();
	pl = $("#customers :selected").data('pl');
	qt = $('#qty_'+id[1]).val();
	if(qt==''){ qt=1; }
	if(item!=''){
				$.ajax({  
						type: "POST",  
						url: "ajax_inventory.php",  
						data: {item_code: item, pl: pl},
						dataType: "json",
						success: function(data){
							//console.log(data);
							$('#item_'+id[1]).val(data["item"]);
							$('#description_'+id[1]).val(data["description"]);
							$('#rate_'+id[1]).val(data["inven_price"]);
							$('#cost_'+id[1]).val(data["cost"]);
							$('#size_'+id[1]).val(data["size"]);
							$('#qty_'+id[1]).val(data["qty"]);
							qty = $('#qty_'+id[1]).val();
							rate = $('#rate_'+id[1]).val();
							sub_total = parseFloat(qty)*parseFloat(rate);
							sub_total = parseFloat(sub_total).toFixed(2);
							if( qty!='' && rate !='' ) $('#total_'+id[1]).val( sub_total );
                                                        count_items();
							count_qty();
							addInvoiceRow();
							calculateSubTotal();
							calculateTotal();
							count_qty();
                                                        calculateTax();
                                                        calculateDisc();
							console.log( 'desc: '+ id[1]);
							if ( e.keyCode == '13'){ 
								$('#description_'+id[1]).removeAttr('value');
                            j = parseInt(id[1])+parseInt(1);
							$('#item_'+j).focus();
                            console.log('#item_'+j);
							}
						}
					});
			} else {
							$('#description_'+id[1]).val('');
							$('#rate_'+id[1]).val('');
							$('#qty_'+id[1]).val('');
							$('#total_'+id[1]).val('');
							 count_items();
							count_qty();
							addInvoiceRow();
							calculateSubTotal();
							calculateTotal();
							count_qty();
                            calculateTax();
                            calculateDisc();
                            $("#li-step1").removeClass("active");
            				$("#li-step2").addClass("active");
            				$("#li-step2").removeClass('disabled');
            				$('.setup-content').hide();
            				$("#step-2").fadeIn("slow");
            				var am = $("#subTotal").val();
            				$("#subTotal2").val(am);
            				$("#tender_amount").val(am);
            				$("#tender_amount").attr('min', am);
            				$("#tender_amount").focus();
							
			}
	
	}

});
*/

$("#disc").on("keyup change blur",function(){
	calculateDisc();
});
$("#tax").on("keyup change blur",function(){
	calculateTax();
});


function count_items(){				
	m = 0;
	$('.description').each(function(){
		if($(this).val() != '' ) { m = parseInt(m)+1; } 
	});
	$("#count_item").val(m);
}
function count_qty(){				
	n = 0;
	$('.qty').each(function(){
		if($(this).val() != '' ) { n = parseInt(n)+parseInt($(this).val()); } 
	});
	$("#total_qty").val(n);
}

function addInvoiceRow(){
	var i = $(".item_code").last().attr("id");
	i = i.replace ( /[^\d.]/g, '' );
	i = parseInt(i)+1;
	var k = i-1;
	console.log($("#item_"+k).val());
	$('.item_code').removeAttr('required')
	if($("#item_"+k).val()!=''){
	html = '<tr>';
	html += '<td><input class="case" tabindex="-1" type="checkbox"/></td>';
	/*html += '<td><input type="text" class="form-control item_code" name="item[]" id="item_'+i+'" autocomplete="false" onfocus="this.select();"></td>'; */
	html += '<td><select class="form-control item_code" name="item[]" id="item_'+i+'" required="required">'+$("#item_"+k).html()+'</select></td>';
	html += '<input type="hidden" name="rows[]"/>';
	html += '<input type="hidden" name="cost[]" id="cost_'+i+'" value="0"/>';
	html += '<td><input class="form-control description" data-toggle="modal" data-target="#productModal" name="description[]" id="description_'+i+'" type="text" placeholder="Description" tabindex="-1" /></td>';
	
	html += '<td><input type="tel" class="form-control changesNo qty" name="qty[]" id="qty_'+i+'" onkeypress="return IsNumeric(event);" onfocus="this.select();" autocomplete="false"></td>'; 
	html += '<td><input class="form-control changesNo" name="rate[]" id="rate_'+i+'" tabindex="-1" type="tel" placeholder="Rate" autocomplete="off"  onkeypress="return IsNumeric(event);" onfocus="this.select();" /></td>';
	html += '<td><input type="tel" name="total[]" id="total_'+i+'" class="form-control totalLinePrice" autocomplete="off" onkeypress="return IsNumeric(event);" ondrop="return false;" onpaste="return false;" readonly="true" tabindex="-1" /></td>';            
	html += '</tr>';
	$('table').append(html);
	i++;
	}
}
//total price calculation 
function calculateTotal(){
	total = 0;
	sub = $("#s_total").val();
	dis = $("#s_total2").val();

	tx = $("#s_total3").val();
	shp=0;
	/*
	tx = 0;
	shp=0;
	/*
	shp = $("#shipping").val();
	if(shp<0 || shp=='' || shp=='NaN'){
            shp=0;
            $("#shipping").val(0.00);
        }
	if(shp==''){ shp=0;} */
	total = (parseFloat(sub)+parseFloat(tx))-(parseFloat(dis));
	total = parseFloat(total).toFixed(2);
	$("#subTotal").val(total);
}

function calculateSubTotal(){
	subTotal = 0 ; 
	$('.totalLinePrice').each(function(){
		if($(this).val() != '' ){ 
                    subTotal += parseFloat( $(this).val() ); 
                }
	});
	subTotal = parseFloat(subTotal).toFixed(2);
	$('#s_total').val( subTotal );
	
/*	tx = $('#s_total3').val();
	
	total = parseFloat(subTotal) - parseFloat(tx);
	total = parseFloat(total).toFixed(2); */
	$("#sub_total").val(subTotal);
	
	
	
}

function calculateTax(){
        tx = 0;
	tx_val=0;
	tx = $("#tax").val();
	if( tx < 0 || tx == '' ){ tx=0;}
	s_total = $("#s_total").val();
	
	tx_val = parseFloat(tx/100)*parseFloat(s_total);
	
	/*tx_val = parseFloat(s_total) - parseFloat(tx_val);*/
	tx_val = parseFloat(tx_val).toFixed(2);
	$("#s_total3").val(tx_val);
	
}

function calculateDisc(){
        dis = 0;
	dis_val=0;
	dis = $("#disc").val();
	if(dis<0||dis==''||dis>100){ dis=0;}
	s_total = $("#s_total").val();
	dis_val = parseFloat(s_total/100)*parseFloat(dis);
	dis_val = parseFloat(dis_val).toFixed(2);
	$("#s_total2").val(dis_val);
}

function calculateChangeDue(){
	var changeDue = 0.00;
	var subTotal2 = $("#subTotal2").val();
	var tenderAmount = $("#tender_amount").val();
	if(tenderAmount=='') tenderAmount = 0.00; 
	if(subTotal2=='') subTotal2 = 0.00;
	changeDue = parseFloat(tenderAmount) - parseFloat(subTotal2);
	changeDue = parseFloat(changeDue).toFixed(2);
	if(changeDue < 0){
		$("#btnSaveInvoice").prop('disabled', true);
	} else {
		$("#btnSaveInvoice").prop('disabled', false);
	}
	$("#change_due").html(changeDue);
	$("#due_amount").val(changeDue);
}


//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46,09); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}





$(document).on('blur','.totalLinePrice',function(){
		addInvoiceRow();
		id_arr = $(this).attr('id');
		id = id_arr.split("_");
		id = parseInt(id[1])+parseInt(1);
		$('#item_'+id).focus();

});


	
		$('#disc').bind('keydown', function(e){
		   if(e.keyCode == 13) { 
		   $("#tax").focus(); 
		   }
	    });
	   $('#tax').bind('keydown', function(e){
		   if(e.keyCode == 13) { 
		 /*  $("#shipping").focus(); */
		   $("#comment1").focus(); 
		   }
	    });
		/*
	   $('#shipping').bind('keydown', function(e){
		   if(e.keyCode == 13) { 
		   $("#comment1").focus(); 
		   }
	    }); */
	   $('#comment1').bind('keydown', function(e){
		   if(e.keyCode == 13) { 
		   $("#comment2").focus(); 
		   }
	    });
	   $('#comment2').bind('keydown', function(e){
		   if(e.keyCode == 13) { 
				$("#li-step1").removeClass("active");
				$("#li-step2").addClass("active");
				$("#li-step2").removeClass('disabled');
				$('.setup-content').hide();
				$("#step-2").fadeIn("slow");
				var am = $("#subTotal").val();
				$("#subTotal2").val(am);
				$("#tender_amount").val(am);
				$("#tender_amount").attr('min', am);
				$("#tender_amount").focus();
		   }
	    });
	   
	   $('#tender_amount').bind('keydown', function(e){
		   if(e.keyCode == 13) { 
			   $( "#btnSaveInvoice:enabled" ).trigger( "click" );
		   }
	    });
		

	    
	    $(document).on('blur keydown keypress','.qty',function(e){
			if (e.keyCode == '13'){
				
				if($(this).val()=='0') { 
						$(this).parents("tr").remove();
				}
				
				
				
				
				if($(this).val()!=''){
				var m = $(this).attr("id");
				m = m.replace ( /[^\d.]/g, '' );
				m = parseInt(m)+1;
					
				$("#item_"+m).focus(); 
				event.preventDefault();
				} else {
					//$("#disc").focus(); 
				}
			}
		});
	   
   


$(function () {
	$("#addCustomer").on("click",function () {
		var cu_name = $("#customer_name").val();
		var cu_email =  $("#customer_name").val();
		var cu_contact = $("#customer_contact").val();
		if(cu_name=='' || cu_email=='' || cu_contact==''){
			alert("Blank Fields not Allowed");		
		} else {
			$.ajax({
				url:'ajax_add_customer.php',
				data: $("#frmCustomerAdd").serialize(),
				method: 'POST',
				success: function (data){
						if(data!='0'){
							$("#customers").html(data);
							$("#customerModalAdd").modal("close");
						}else{
							alert('whoops..! Somthing went wrong while adding customer');						
						}						
							
				}
			
			});
		
		}
	});
	

	


	
});



$("form").bind('keydown', function(e){
    if (e.keyCode == 120) {
    	
    	$("#li-step1").removeClass("active");
		$("#li-step2").addClass("active");
		$("#li-step2").removeClass('disabled');
		$("#activate-step-2").trigger( "click" );
		$('.setup-content').hide();
		$("#step-2").fadeIn("slow");	
		
     }
});

$("form").bind('keydown', function(e){
    if (e.keyCode == 121) {
    	
    	$("#activate-step-2").trigger( "click" );
    	$("#card").trigger( "click" );
    	$("#activate-step-3").trigger( "click" );
    	$("#li-step2").removeClass("active");
		$("#li-step3").addClass("active");
		$("#li-step3").removeClass('disabled');
		$('.setup-content').hide();
		$("#step-3").fadeIn("slow");
     }
});
/*
$("form").bind('keydown', function(e){
    if (e.keyCode == 122) {
    	$("#split").trigger( "click" );
    	$("#activate-step-2").trigger( "click" );
    	$("#li-step1").removeClass("active");
		$("#li-step2").addClass("active");
		$("#li-step2").removeClass('disabled');
		$('.setup-content').hide();
		$("#step-2").fadeIn("slow");				 
     }
});



$('input[name="card_type"]').bind('keydown', function(e){
	alert();
});
*/
 

