<?= $this->include('layout_vertical/header');?>
<style>
	.row{line-height: 25px;}
	.wardClass{font-size: medium; font-weight: bold;}
	#tdId{font-size: medium; font-weight: bold; text-align: right;}
	#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
	#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
	#left{font-size: medium; font-weight: bold; text-align: left;}
</style>
<!-- <style type="text/css" media="print">
.dontprint{ display:none}
</style> -->
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="<?=base_url();?>/public/assets/otherJs/ExcelExport.js"></script>
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~-->
                    <!--End page title-->

                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Reminder Sms Send</a></li>
                    </ol>
                    <!--~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
                    <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">List</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post">
								<div class="row" >
									<div class="col-md-12">
                                        <div class="col-md-1">
                                            <label class="control-label" for="from_date"><b>From Date</b></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" required id="from_date" name="from_date" value="<?=date("Y-m-d");?>">
                                        </div>
                                        <div class="col-md-1">
                                            <label class="control-label" for="to_date"><b>Upto Date</b></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" required id="to_date" name="to_date" value="<?=date("Y-m-d");?>">
                                        </div>  
                                        <div class="col-md-1">
                                            <label class="control-label" for="limit"><b>Limit</b></label>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" required id="limit" name="limit" onkeypress="return isNum(event);" value="100">
                                        </div>                                          
									</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <label class="control-label" for="module"><b>Module</b></label>
                                        </div>
                                        <div class="col-md-5">
                                            <select id="module" name="module" class="form-control">
                                                <option value="PROPERTY" <?=($module??"")=="PROPERTY"?"selected":"";?>>PROPERTY</option> 
                                                <!-- <option value="WATER" <?=($module??"")=="WATER"?"selected":"";?>>WATER</option> 
                                                <option value="TRADE" <?=($module??"")=="TRADE"?"selected":"";?>>TRADE</option>  -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-1">
                                            <label class="control-label" for="ward_id"><b>Ward No</b></label>
                                        </div>
                                        <div class="col-md-5">
                                            <select id="ward_id" name="ward_id" class="form-control">
                                                <option value="">select</option> 
                                                <?php
                                                    foreach($wardList as $val){
                                                        ?>
                                                            <option value="<?=$val["id"];?>" <?=($ward_id??"")==$val["id"]?"selected":"";?>><?= $val["ward_no"];?></option> 
                                                        <?php
                                                    }
                                                ?>
                                                
                                            </select>
                                        </div>
                                    </div>
								</div>
                                <div class="row">
                                    <div class="panel">
                                        <div class="panel-body text-center">
                                            <button type="button" class="btn btn-primary btn-labeled" id="btn_send" name="btn_send">Send SMS</button> &nbsp;&nbsp;&nbsp;&nbsp;
                                            <a  href="#" download="sms_reminder.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Property Collection');" class="btn btn-primary">Export to Excel</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="button" onClick="printDiv('printableArea')" style="width: 100px" class="btn btn-primary" value="Print">
                                        </div>
                                    </div>

                                </div>
							</form>
						</div>
					</div>	
					<div class="panel panel-bordered panel-dark" id="printableArea">
						<div class="panel-heading">
							<h3 class="panel-title">Details <?=$module??"";?></h3>
							
						</div>
                        <div class="panel-body" id ="table"><h1>jjj</h1>
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
    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }
    $(document).ready(function(){
        

        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,7] }
            }]
        });
    });
    $('#btn_send').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var modules = $("#module").val();
        var limit = $("#limit").val();
        var ward_id = $("#ward_id").val();
        if(to_date=="")
        {
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
        if(from_date=="")
        {
            $("#from_date").css({"border-color":"red"});
            $("#from_date").focus();
            return false;
        }
        if(modules=="")
        {
            $("#module").css({"border-color":"red"});
            $("#module").focus();
            return false;
        }        
        if(to_date<from_date)
        {
            alert("To Date Should Be Greater Than Or Equals To From Date");
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
        var postdata={
            from_date : from_date,
            to_date : to_date,
            module : modules,
            limit:limit,
            ward_id:ward_id,
        };
        $.ajax({
            url:"<?php echo base_url("onlineRequest/sendPropertySms");?>",    
            type: "post",    //request type,
            dataType: 'json',
            data: postdata,
            beforeSend: function() {
                $("#loadingDiv").show();
                $("#pay").attr('disabled', true);

            },
            success:function(result){                
                $("#loadingDiv").hide();
                $("#pay").attr('disabled', false);
                console.log(result);
                $("#table").html(result?.table);
                if(result.status==false){
                    modelInfo(result?.message);
                }
            },
            error:function(e){
                $("#loadingDiv").hide();
                console.log(eval(e));
            }
        });
    });
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").change(function(){$(this).css('border-color','');});

    function printDiv(divName) { 
	var printData = document.getElementById(divName).innerHTML;
	var data = document.body.innerHTML;
	
	document.body.innerHTML = printData;
	window.print();
	window.location.reload();
	document.body.innerHTML = data;
	}
</script>