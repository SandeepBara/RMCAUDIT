<?= $this->include('layout_vertical/header');?>

<style>
.row{line-height:25px;}
#tdId{font-size: medium; font-weight: bold; text-align: right;}
#leftTd{font-size: medium; font-weight: bold; text-align: center;color: #090f44;}
#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
#left{font-size: medium; font-weight: bold; text-align: left;}
.wardClass{font-size: medium; font-weight: bold;}
td{
	font-size:12px!important;
}

.tr{
	border:1px solid red;	
}
div.scrollmenu {
   overflow: auto;
  white-space: nowrap;
}


@media only screen and (max-width: 600px) {
  #ttl_cons{
    margin-left: 100px !important;
  }
  #ttl_collloectn{
    margin-left: 100px !important;
  }
}
</style>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- <link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet"> -->
<script src="<?=base_url();?>/public/assets/otherJs/ExcelExport.js"></script>
    
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->

                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Trade</a></li>
                    <li><a href="#">Report</a></li>
                    <li class="active">Collection Report of Municipal Licence </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Collection Summary</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal">
								<div class="row">
								<div class="col-md-12">
                                <div class="col-md-3">
 								</div>
								<div class="col-md-2">
									<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>								 
									<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
							 
								<div class="col-md-2">
									<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>							 
									<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								 
								<div class="col-md-2">
								<label class="control-label" for="department_mstr_id">&nbsp;</label><br>
								<button  style="margin-top:2px;" type="button" class="btn btn-primary btn-labeled" id="view_collection" name="view_collection" onclick="get_collection()">View Report</button>&nbsp;&nbsp;&nbsp;&nbsp;
							  </div>	 
							</div>
							</div>
							<div class="col-md-2"></div>
							</form>
						</div>
					</div>	
					<div class="panel panel-bordered panel-dark" id="printableArea">
                    <br>
                         <table class="table table-bordered">
                                <thead>
                                   <tr> <td id="leftTd" colspan="3"  style="background-color: #3a444e; color: #fff;" class="text-center"><b style="font-size: large;">RANCHI MUNICIPAL CORPORATION</b></td></tr>
                                    <tr><td id="leftTd" colspan="3"  style="background-color: #3a444e; color: #fff;"  class="text-center"><b> Collection Summary Report of Municipal Licence</b></td></tr>
                                    <tr><td id="leftTd" colspan="3"  style="background-color: #3a444e; color: #fff;"  class="text-center"><b id="from_date_to_date"></b></td></tr>

                                </thead>
                            </table>


                         <div class="panel panel-bordered panel-dark" id="printableArea" style="margin:20px;">
                           <table class="table table-bordered">
                                <thead>
                                    <th id="leftTd" colspan="3"  style="background-color: #3a444e; color: #fff;"><b style="float: left;"> Payment Collection</b></th>
  
                                </thead>
                            </table>

                        <table class="table table-bordered">
                                
                            <thead>
                                <th id="leftTd" class="col-sm-2"><b>Collection Through</b></th>
                                <th id="leftTd"><b>Cash Payment</b></th>
                                <th id="leftTd"><b>Cheque Payment</b></th>
                                <th id="leftTd"><b>DD Payment</b></th>
                                <th id="leftTd" class="col-sm-2"><b>Total</b></th>

                            </thead>
                            <tbody id="collec_dtls">
                                
                                
                            </tbody>
                            <tfoot>
                                <td id="leftTd" class="col-sm-1"></td>
                                <td id="leftTd"></td>
                                <td id="leftTd"> </td>
                                <td id="leftTd">Grand Total :-</td>
                                <td id="leftTd" class="grand_total"> </td>
                            </tfoot>
                        </table>
							
					</div>
                    <div class="panel panel-bordered panel-dark" id="printableArea" style="margin:20px;">
                           <table class="table table-bordered">
											<thead>
 												<th id="leftTd" colspan="3"  style="background-color: #3a444e; color: #fff;"><b style="float: left;">Online Payment Collection</b></th>
 											</thead>
										</table>

 										<table class="table table-bordered">
											<thead>
 												<th id="leftTd" class="col-sm-3"><b>Online Payment</b></th>
												<th id="leftTd"><b>Total </b></th>
											</thead>
                                            <tbody id="online_collection">
                                            
                                            </tbody>
										</table>
					</div>	
					</div>
						<div class="panel" style="margin-top:-19px;">
							<div class="panel-body text-right">
								<a  style="margin-top:2px;" href="#" download="Trade.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Property Collection');" class="btn btn-primary"><i style="color: #26a659; font-size: 20px;" class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;&nbsp;Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                 <a style="margin-top:2px;" href="#" onClick="printDiv('printableArea')" style="width: 100px" class="btn btn-primary"><i style="color: #017af7; font-size: 20px;" class="fa fa-print" aria-hidden="true"></i>&nbsp;&nbsp;Print</a>
							</div>
						</div>
					
				</div>
			</div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script type="text/javascript">
    $('#view_collection').click(function(){
        $("#to_date").css({"border-color":""});
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(to_date=="")
        {
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
        if(to_date<from_date)
        {
            alert("To Date Should Be Greater Than Or Equals To From Date");
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
    });
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").change(function(){$(this).css('border-color','');});

    function printDiv(divName) { //alert('asfasdf'); return false;
 	var printData = document.getElementById(divName).innerHTML;
	var data = document.body.innerHTML;
	document.body.innerHTML = printData;
	window.print();
	//window.location.reload();
	document.body.innerHTML = data;
	}
</script>




<script>
 function get_collection()
 { 
 	 var from_date = $("#from_date").val();
	 var to_date = $("#to_date").val();
     	$.ajax({
    type: "POST",
    url: "<?=base_url('TradeApplyLicenseReports/collection_report_municpl_licnse_ajax');?>",
	data:{from_date:from_date,to_date:to_date},
    dataType:"json",
    success: function(response){
 		console.log(response);
         $("#collec_dtls").html(response.output_payment);
         $(".grand_total").html(response.output_payment_grand_total);
         $("#online_collection").html(response.output_payment_online);
         $("#from_date_to_date").html(response.from_date_to_date);
    }
  });
  }
  get_collection()
 </script>
 