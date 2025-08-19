<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<?php
    $userType = Session()->get("emp_details")["user_type_mstr_id"]??"";
?>

<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Bulk Notice</h5>
            </div>
            <div class="panel-body">
				<form id="myForm" method="get" >
                    <div class="col-md-12">
                        <div class="row">
                            
                            <label class="col-md-1 text-bold" for="ward_id">Ward No</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select name="ward_id" id="ward_id" class="form-control">
                                    <option value="">All</option>
                                    <?php
                                        foreach($wardList as $val){
                                            ?>
                                                <option value="<?=$val["id"];?>" <?=(($ward_id??"")== $val["id"]? "selected" : ""); ?>><?=$val["ward_no"];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            

                            <label class="col-md-1 text-bold" for="property_type_id">Property Type</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select name="property_type_id" id="property_type_id" class="form-control">
                                    <option value="">All</option>
                                    <?php
                                        foreach($propertyTypeList as $val){
                                            ?>
                                                <option value="<?=$val["id"];?>" <?=(($property_type_id??"")==$val["id"] ? "selected" : ""); ?>><?=$val["property_type"];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>

                            <label class="col-md-1 text-bold" for="from_amt" style="display:<?=$userType==1?'inline':'none';?>">Demand Amt From</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="text" name="from_amt" id="from_amt" class="form-control" value="<?=(($from_amt??"")); ?>" onkeypress="return isNum(event);" style="display:<?=$userType==1?'inline':'none';?>"/>
                            </div>
                            <label class="col-md-1 text-bold" for="upto_amt" style="display:<?=$userType==1?'inline':'none';?>">Demand Amt Upto</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="text" name="upto_amt" id="upto_amt" class="form-control" value="<?=(($upto_amt??"")); ?>" onkeypress="return isNum(event);" style="display:<?=$userType==1?'inline':'none';?>"/>
                            </div>
                            
                        </div>
                        <div class = "row">
                            
                            <label class="col-md-1 text-bold" for="report_type" >Report Type</label>
                            <div class="col-md-3 pad-btm radio">
                                <select name="report_type" id="report_type" class="form-control">
                                    <option value="All" <?=($report_type??"")=="All" ? "selected" :"";?>>All</option>
                                    <option value="total_remaining_defaulter" <?=($report_type??"")=="total_remaining_defaulter" ? "selected" :"";?>>Defaulter</option>
                                    <option value="total_defaulter_pay_without_notice" <?=($report_type??"")=="total_defaulter_pay_without_notice" ? "selected" :"";?>>Defaulter Pay Without Notice</option>
                                    <option value="total_notice_generated" <?=($report_type??"")=="total_notice_generated" ? "selected" :"";?>>Notice Generated</option>
                                    <option value="total_notice_served" <?=($report_type??"")=="total_notice_served" ? "selected" :"";?>>Notice Served</option>
                                    <option value="total_payment_received_from_notice" <?=($report_type??"")=="total_payment_received_from_notice" ? "selected" :"";?>>Payment Received From Notice</option>

                                </select>
                                <!-- <input type="radio" id="defaulter_list" class="magic-radio" name="report_type" value="defaulter_list" checked /> <label for="defaulter_list">Defaulter List</label>
                                <input type="radio" id="notice_to_be_generate" class="magic-radio" name="report_type" value="notice_to_be_generate" <?=(($report_type??"")=="notice_to_be_generate" ? "checked" : ""); ?> /> <label for="notice_to_be_generate">Notice To Be Generate</label>
                                <input type="radio" id="notice_be_generated" class="magic-radio" name="report_type" value="notice_be_generated" <?=(($report_type??"")=="notice_be_generated" ? "checked" : ""); ?>/> <label for="notice_be_generated">Notice Generated</label>
                                <input type="radio" id="notice_be_closed" class="magic-radio" name="report_type" value="notice_be_closed" <?=(($report_type??"")=="notice_be_closed" ? "checked" : ""); ?> /> <label for="notice_be_closed">Collection From Notice</label>
                                <input type="radio" id="notice_summary" class="magic-radio" name="report_type" value="notice_summary" <?=(($report_type??"")=="notice_summary" ? "checked" : ""); ?> /> <label for="notice_summary">Notice Summary</label> -->
                            </div>
                        </div>
                        <div class="row">
                            <span id="notice_serial" style="display: none;">
                                <label class="col-md-1 text-bold" for="serialNo" >Notice Serial</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <select name="serialNo" id="serialNo" class="form-control">
                                        <option value="">All</option>
                                        <option value="1" <?=(($serialNo??"")=="1" ? "selected" : ""); ?>>1'st</option>
                                        <option value="2" <?=(($serialNo??"")=="2" ? "selected" : ""); ?>>2'nd</option>
                                        <option value="3" <?=(($serialNo??"")=="3" ? "selected" : ""); ?>>3'rd</option>
                                        <option value="4" <?=(($serialNo??"")=="4" ? "selected" : ""); ?>>4'th</option>
                                        <option value="5" <?=(($serialNo??"")=="5" ? "selected" : ""); ?>>5'th</option>
                                    </select>
                                </div>

                                <label class="col-md-1 text-bold" for="closeStatus" >Notice Status</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <select name="closeStatus" id="closeStatus" class="form-control">
                                        <option value="">ALL</option>
                                        <option value="open" <?=(($closeStatus??"")=="open" ? "selected" : ""); ?>>OPEN</option>
                                        <option value="close" <?=(($closeStatus??"")=="close" ? "selected" : ""); ?>>CLOSE</option>
                                    </select>
                                </div>
                                <label class="col-md-1 text-bold" for="closeStatus" >Served Status</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <select name="servedStatus" id="servedStatus" class="form-control">
                                        <option value="">ALL</option>
                                        <option value="served" <?=(($servedStatus??"")=="served" ? "selected" : ""); ?>>Served</option>
                                        <option value="not_served" <?=(($servedStatus??"")=="not_served" ? "selected" : ""); ?>>Not Served</option>
                                    </select>
                                </div>
                            </span>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <span id="date_div" style="display: none;">
                                    <label class="col-md-1 text-bold" for="fromDate" >Notice From Date</label>
                                    <div class="col-md-2 has-success pad-btm">
                                        <input type="date" value="<?=$fromDate??date('Y-m-d')?>" max="<?=date('Y-m-d')?>" name="fromDate" id="fromDate" class="form-control"/>
                                    </div>

                                    <label class="col-md-1 text-bold" for="uptoDate" >Notice Upto Date</label>
                                    <div class="col-md-2 has-success pad-btm">
                                        <input type="date"  value="<?=$uptoDate??date('Y-m-d')?>" max="<?=date('Y-m-d')?>"  name="uptoDate" id="uptoDate" class="form-control" />
                                            
                                    </div>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />&nbsp;&nbsp;&nbsp;
                            <span id="print_btn">
                            </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
							<div class="table-responsive" id="individual_view">
								<table id="tbl_by_individual" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th id="selectAll">#</th>
                                            <th>Ward No</th> 
                                            <th>Holding No</th>
											<th>Property Type</th>
											<th>Address</th>
                                            <th>Owner Name</th>
                                            <th>Mobile No.</th>	
                                            <th>From</th>
                                            <th>Upto</th>                                            
                                            <th>Demand Amount</th>
                                            <th>View Notice</th>
                                            <th>View Notice Reiving</th>
                                            <th>Payment View</th>
                                            <th>View Holding</th>										
										</tr>
									</thead>
									<tbody>
										
									</tbody> 
								</table>
							</div>

						</div>
					</div>
			    </div>
			</div>
		<!--===================================================-->
		<!--End page content-->
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/plugins/select2/js/select2.min.js"></script>


<script type="text/javascript">
   //debugger;
   let selectAll=false;
    var individual_view_tbl = $('#tbl_by_individual').DataTable({
        // 'responsive': true,
        'processing': true,
        "ordering": false,
        "searching": false ,
        "deferLoading": 0, // default ajax call prevent
        'serverSide': true,
        dom: 'Bfrtip',        
        lengthMenu: [
            [20,  50, 100,-1],
            ['20 rows', '50 rows', '100 rows', 'ALL rows']
        ],
        buttons: [
            'pageLength',
            {
                text: 'Excel Export',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    var gerUrl = 'true?';
                    var formData = $("#myForm").serializeArray();
                    $.each(formData, function(i, field) {
                        gerUrl += (field.name+'='+field.value)+"&";
                    });
                    window.open('<?=base_url();?>/propDtl/generateBulkNoticeDtl/'+gerUrl).opener = null;
                }
            },            
        ],

        'ajax': {
            "type": "POST",
            'url':'<?=base_url('propDtl/generateBulkNoticeDtl');?>',            
            "timeout": 300000, // 5 minute              
            "deferRender": true,
            "dataType": "json",
            'dataSrc': function ( data ) {
                summary = data.summary;
                return data.data;
            },
            'data': function(data){
                var formData = $("#myForm").serializeArray();
                $.each(formData, function(i, field) {
                    if(field.name.match(/\[\]/)){
                        if(field.name in data){
                            data[field.name].push(field.value);
                        }else{
                            data[field.name] = [field.value];
                        }
                    }
                    else{
                        data[field.name] = field.value;
                    }
                });
            },
            beforeSend: function () {
                $("#btn_search").val("LOADING ...");
                $("#loadingDiv").show();
            },
            complete: function () {
                $("#btn_search").val("SEARCH");
                $("#loadingDiv").hide();
            },
        },

        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'holding_no' },
            { 'data': 'property_type' },
            { 'data': 'prop_address' },
            { 'data': 'owner_name'},
            { 'data': 'mobile_no' },
            { 'data': 'from' },
            { 'data': 'upto' },
            { 'data': 'balance' },
            { 'data': 'noticeLink' },
            { 'data': 'noticeReivingLink' },
            { 'data': 'is_payment_reive' },
            { 'data': 'link' },
            
        ],
        drawCallback: function( settings )
        {
            try
            {
                // var api = this.api();
                // $(api.column(3).footer() ).html(summary.total_saf);
                // $(api.column(5).footer() ).html(summary.outstanding_demand);
                // $(api.column(6).footer() ).html(summary.current_demand);
                // $(api.column(7).footer() ).html(summary.total_demand);
                // $(api.column(8).footer() ).html(summary.arrear_collection);
                // $(api.column(9).footer() ).html(summary.current_collection);
                // $(api.column(10).footer() ).html(summary.total_collection);
                // $(api.column(11).footer() ).html(summary.arrear_balance);
                // $(api.column(12).footer() ).html(summary.current_balance);
                // $(api.column(13).footer() ).html(summary.total_balance);
            }
            catch(err)
            {
                console.log(err.message);
            }
        }

    });

    let tbl = individual_view_tbl;
    
    function callLoading(){
        $('#btn_search').click();
        history.replaceState(null, '', '<?=base_url("propDtl/generateBulkNoticeDtl");?>');
    }
    $(document).ready(function(){  



        $('#btn_search').click(function()
        {
            tbl.draw();
            selectAll =  false;
        });
        '<?=$ajax?>'=="parent" ? ( callLoading()): "";

    });
    
    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }
</script>