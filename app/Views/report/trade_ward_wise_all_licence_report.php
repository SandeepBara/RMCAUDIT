<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Trade</a></li>
					<li class="active">Trade All Licence Report By Ward</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
                        <div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title"> Trade All Licence Report By Ward </h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" method="post" action="<?=base_url('');?>/TradeApplyLicenseReports/AllLicenceReport">
								<div class="col-md-3">
									<label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
									<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($to_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
									<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>Ward No.</b><span class="text-danger">*</span></label>
									<select name="ward_id" id="ward_id" class="form-control">
										<option value="all">All</option>
										<?php
										if($ward_list):
										foreach($ward_list as $val):
										?>
										<option value="<?php echo $val['id'];?>" <?php if($ward_id==$val['id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
										<?php
										endforeach;
										endif;
										?>
									</select>
								</div>
								<div class="col-md-2">
									<label class="control-label" for="department_mstr_id">&nbsp;</label>
									<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit" >Search</button>
								</div>
							</form>
						</div>
					</div>
					        <div class="panel panel-bordered panel-dark">
					            <div class="panel-heading">
                                
					                <h5 class="panel-title"> Result</h5>
                                   
					            </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="table-responsive">
					                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                                <th>#</th>
                                                <th>Ward No.</th>
                                                <th colspan=2>New Licence</th>
                                                <th colspan=2>Renewal Licence</th>
                                                <th colspan=2>Amendment Licence</th>
												<th colspan=2>Surrender Licence</th>
                                                <th colspan=2>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        <tr>

                                                <th></th>
                                                <th></th>
                                                <th>Total Licence</th>
                                                <th>Total Amount</th>
                                                <th>Total Licence</th>
                                                <th>Total Amount</th>
                                                <th>Total Licence</th>
                                                <th>Total Amount</th>
                                                <th>Total Licence</th>
                                                <th>Total Amount</th>
                                                <th>Total Licence</th>
                                                <th>Total Amount</th>
                                                 <th></th>
                                            </tr>
                                    </thead>
                                        <tbody>
                                        
                                    <?php
                                    if(isset($licencedtls)):
                                          if(empty($licencedtls)):
                                    ?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            $totalappln=0;
                                            $totalcollection=0;
                                            $no_new=0;
                                            $new_paid=0;
                                            $no_renew=0;
                                            $renew_paid=0;
                                            $no_amend=0;
                                            $amend_paid=0;
                                            $no_sur=0;
                                            $sur_paid=0;
                                            // print_var($licencedtls);die;
                                            foreach ($licencedtls as $value):
                                    ?>

                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value["ward_no"]??null;?></td>
                                                <td><?=$value["no_new"]??null;?></td>
                                                <td><?=$value["new_paid"]??null;?></td>
                                                <td><?=$value["no_renew"]??null;?></td>
												<td><?=$value["renew_paid"]??null;?></td>
                                                <td><?=$value["no_amend"]??null;?></td>
												<td><?=$value["amend_paid"]??null;?></td>
                                                <td><?=$value["no_sur"]??null;?></td>
												<td><?=$value["sur_paid"]??null;?></td>
                                                <td><?= $totalappl = ($value["no_new"]??0)+($value["no_renew"]??0)+($value["no_amend"]??0)+($value["no_sur"]??0)?> </td>
												<td><?= $totalcolctn = (isset($value["new_paid"])?$value['new_paid']:0)+(isset($value["renew_paid"])?$value['renew_paid']:0)+(isset($value["amend_paid"])?$value['amend_paid']:0)+(isset($value["sur_paid"])?$value['sur_paid']:0);?></td>
                                                <td>
                                                    <?php if($value["no_new"]??null!=0 || $value["new_paid"]??null!="0.00" || $value["no_renew"]??null !=0 || $value["renew_paid"]??null !="0.00" || $value["no_amend"]??null !=0 || $value["amend_paid"]??null!="0.00" || $value["no_sur"]??null !=0 ||$value["sur_paid"]??null !="0.00"){?>
                                                    <a class="btn btn-primary" href="<?php echo base_url('TradeApplyLicenseReports/view_by_ward/'.md5($value['id']).'/'.base64_encode($from_date).'/'.base64_encode($to_date));?>" role="button">View</a>
                                                    <?php }?>
                                                </td>
                                               <?php  
                                               $totalappln += $totalappl;
                                               $totalcollection += $totalcolctn;
                                               $no_new += $value["no_new"];
                                               $new_paid += isset($value["new_paid"])?$value['new_paid']:0;
                                               $no_renew += isset($value["no_renew"])?$value['no_renew']:0;
                                               $renew_paid += isset($value["renew_paid"])?$value['renew_paid']:0;
                                               $no_amend += isset($value["no_amend"])?$value['no_amend']:0;
                                               $amend_paid += isset($value["amend_paid"])?$value['amend_paid']:0;
                                               $no_sur += isset($value["no_sur"])?$value['no_sur']:0;
                                               $sur_paid += isset($value["sur_paid"])?$value['sur_paid']:0;
                                               ?>
                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
                                    
 					                    </tbody>
                                         <tfooter>
                                             <tr>
                                            <td></td>
                                            <td></td>
                                            <td><h4><?= $no_new??0?></h4></td>
                                            <td><h4><?= (isset($new_paid)?number_format((float)$new_paid, 2, '.', ''):0.00); ?></h4></td>
                                            <td><h4><?= $no_renew??0?></h4></td>
                                            <td><h4><?= (isset($no_renew)?number_format((float)$renew_paid, 2, '.', ''):0.00);?></h4></td>
                                            <td><h4><?= $no_amend??0?></h4></td>
                                            <td><h4><?= (isset($amend_paid)?number_format((float)$amend_paid, 2, '.', ''):0.00);?></h4></td>
                                            <td><h4><?= $no_sur??0?></h4></td>
                                            <td><h4><?= (isset($sur_paid)?number_format((float)$sur_paid, 2, '.', ''):0.00);?></h4></td>
                                            <td><h4><?= $totalappln??0?></h4></td>
                                            <td><h4><?= (isset($totalcollection)?number_format((float)$totalcollection, 2, '.', ''):0.00);?></h4></td>
                                            </tr>
                                            </tfooter>
					                </table>
                                        </div>
                                    </div>
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
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $('#from_date1').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
    $('#to_date1').datepicker({ 
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
	$(document).ready(function() {
        $("#from_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#from_date").val('');
            }
        });
        $("#to_date").change(function ()
        {
            var from_date= $("#from_date").val();
            var to_date= $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if((startDay.getTime())>(endDay.getTime()))
            {
                alert("Please select valid To Date!!");
                $("#to_date").val('');
            }
        });

        $("#btn_search").click(function(){
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            if(from_date=="")
			{
				alert("Please Select From Date");
				$('#from_date').focus();
				return false;
			}

			if(to_date=="")
			{
				alert("Please Select To date");
				$('#to_date').focus();
				return false;
			}
        });
		$('#demo_dt_basic').DataTable({
			responsive: true,
            "bPaginate": false,
 			dom: 'Bfrtip',
	        lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
	        ],
	        buttons: [
	        	 
	          {
				text: 'excel',
				extend: "excel",
				title: "Ward Wise Collection Report of Municipal License",
                //title: " Ranchi Municipal Corporation",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9,10,11] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Ward Wise Collection Report of Municipal License",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9,10,11] }
			}]
		});
	});
    function modelInfo(msg){
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php 
        if($licence=flashToast('licence'))
        {
            echo "modelInfo('".$licence."');";
        }
    ?>
 </script>


