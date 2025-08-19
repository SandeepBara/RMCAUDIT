<?= $this->include('layout_vertical/header');?>

<style>
.row{line-height:25px;}
#tdId{font-size: medium; font-weight: bold; text-align: right;}
#leftTd{font-size: medium; font-weight: bold; text-align: left;color: #090f44;}
#tdRight{font-size: medium; font-weight: bold; text-align: right;color: #090f44;}
#left{font-size: medium; font-weight: bold; text-align: left;}
.wardClass{font-size: medium; font-weight: bold;}
</style>
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
                <li><a href="#">Report</a></li>
                <li class="active">Licence Report</li>
                </ol>
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                <!--End breadcrumb-->
            </div>
            <!--Page content-->
            <!--===================================================-->
            <div id="page-content">
                <div class="panel panel-bordered panel-dark" >
                    <div class="panel-heading">
                        <h3 class="panel-title">Filter With Summary</h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST">
                            <div class="row">
                                <div class="col-md-3">
									<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>								 
									<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
							 
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>							 
									<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-2">
                                    <label class="control-label" for="department_mstr_id">&nbsp;</label><br>
                                    <button style="margin-top:2px;" type="submit" class="btn btn-primary btn-labeled" id="btn_property" name="btn_property">Search</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel panel-bordered panel-dark" >
                    <div class="panel-heading">
                        <div class="panel-control">
                            <a href="#" download="Consumer Summary.xls"  onClick="return ExcellentExport.excel(this, 'printableArea', 'Consumer Summary');" class="btn btn-primary text-center">Export to Excel</a>
                        </div>
                        <h3 class="panel-title">Licence Summary</h3>
                    </div>
                    <div class="panel-body" id="printableArea">
                        <div class="col-md-12">
                            
                            <div class="col-md-12">
                                <div class="panel panel-bordered panel-dark" id="printableArea">
                                    <div class="panel-heading" style="background-color: #298da0;">
                                        <h3 class="panel-title">Licence Summary Description</h3>
                                    </div>
                                    <div class="panel-body">
                                        
                                        <table class="table table-bordered table-responsive">
                                            <tr>
                                                <th id="leftTd">Type</th>
                                                <th id="leftTd" style="text-align: right;">No. of Application</th>
                                                <th id="leftTd" style="text-align: right;">No. of Expire Licence</th>
                                                <th id="leftTd" style="text-align: right;">No. of Valid Licence</th>
                                                <th id="leftTd" style="text-align: right;">No. of Unknow Validity Licence</th>
                                                <th id="leftTd" style="text-align: right;">No. of Holding Licence</th>
                                                <th id="leftTd" style="text-align: right;">No. of NonHolding Licence</th>
                                                <th id="leftTd" style="text-align: right;">No. of Holding App</th>
                                                <th id="leftTd" style="text-align: right;">No. of NonHolding App</th>
                                                <th id="leftTd" style="text-align: right;">No. of Tomaco Licence</th>
                                                
                                            </tr>
                                            <?php
                                                foreach($application_type as $val)
                                                {
                                                    ?>
                                                    <tr>
                                                        <td><?=$val['application_type'];?></td>
                                                        <td><?=$val['total'];?></td>
                                                        <td><?=$val['expire_licence'];?></td>
                                                        <td><?=$val['valid_licence'];?></td>
                                                        <td><?=$val['unknow_validity'];?></td>
                                                        <td><?=$val['holding_no'];?></td>
                                                        <td><?=$val['no_holding_no'];?></td>
                                                        <td><?=$val['aholding_no'];?></td>
                                                        <td><?=$val['ano_holding_no'];?></td>
                                                        <td><?=$val['tobacco_status'];?></td>
                                                    </tr>
                                                    <?php  
                                                }

                                                ?>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="panel panel-bordered panel-dark" id="printableArea">
                                    <div class="panel-heading" style="background-color: #298da0;">
                                        <h3 class="panel-title">Category Type</h3>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered">
                                        
                                        <tr>
                                            <th id="leftTd">Type</th>
                                            <th id="leftTd" style="text-align: right;">No.of Licence</th>
                                            <th id="leftTd" style="text-align: right;">No.of Holding Licence</th>
                                            <th id="leftTd" style="text-align: right;">No.of NonHolding Licence</th>                                            
                                        </tr>
                                        <?php
                                        
                                        foreach($category_type as $val)
                                        {
                                            ?>
                                            <tr>
                                                <td><?=$val['category_type'];?></td>
                                                <td style="text-align: right;"><?=$val['total_licences'];?></td>
                                                <td style="text-align: right;"><?=$val['holding_no'];?></td>
                                                <td style="text-align: right;"><?=$val['no_holding_no'];?></td>
                                            </tr>
                                            <?php

                                        }
                                        ?>

                                        
                                    </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="panel panel-bordered panel-dark" id="printableArea">
                                        <div class="panel-heading" style="background-color: #298da0;">
                                            <h3 class="panel-title">Ownership Type Description</h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th id="leftTd">Description</th>
                                                    <th id="leftTd" style="text-align: right;">No.of Licence</th>
                                                </tr>
                                                <?php
                                                        if(isset($ownership_type))
                                                        {
                                                            foreach($ownership_type as $val)
                                                            {
                                                                ?>
                                                                <tr>
                                                                    <td><?=!empty($val['ownership_type'])?$val['ownership_type']:'N/A';?></td>
                                                                    <td style="text-align: right;"><?=$val['total_licences'];?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                            </table>
                                        </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="panel panel-bordered panel-dark" id="printableArea">
                                        <div class="panel-heading" style="background-color: #298da0;">
                                            <h3 class="panel-title">Level Pending Description</h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th id="leftTd" style="text-align: right;">No.of Licence</th>
                                                    <th id="leftTd" style="text-align: right;">No.of Backoffice</th>
                                                    <th id="leftTd" style="text-align: right;">No.of Level</th>
                                                    <th id="leftTd" style="text-align: right;">No.of Approved</th>
                                                    <th id="leftTd" style="text-align: right;">No.of Rejected</th>
                                                </tr>
                                                <?php
                                                        if(isset($level_pending))
                                                        {
                                                            foreach($level_pending as $val)
                                                            {
                                                                ?>
                                                                <tr>
                                                                    <td><?=$val['total_licences'];?></td>
                                                                    <td style="text-align: right;"><?=$val['pending_backoffice'];?></td>
                                                                    <td style="text-align: right;"><?=$val['pending_level'];?></td>
                                                                    <td style="text-align: right;"><?=$val['approved'];?></td>
                                                                    <td style="text-align: right;"><?=$val['rejected'];?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                    ?>
                                            </table>
                                        </div>
                                </div>
                            </div>

                        </div>
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
    $('#btn_property').click(function(){
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if(to_date==""){
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            return false;
        }
        if(to_date<from_date){
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
	window.location.reload();
	document.body.innerHTML = data;
	}
</script>