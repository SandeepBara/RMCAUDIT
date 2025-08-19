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
					<li><a href="#">Trade </a></li>
					<li class="active">Track Application No.</li>
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
					                <h5 class="panel-title">Track Application No.</h5>
					            </div>
					            <?php //print_var($_SESSION['tempData']);die(); ?>
                                <div class="panel-body">
                                	<div class="row">
                                		<div class="col-md-10 text-center">
                                    		<input type="radio" id="sby_whatever" name="search_by" value="sby_whatever" onclick="showBox(this.value);">
												<label class="control-label" for="from_date">
													<b>Enter Application, Licence or Mobile No </b> 
												</label>
											<input type="radio" id="sby_fname" name="search_by" value="sby_fname" onclick="showBox(this.value);">
												<label class="control-label" for="from_date" <?= isset($_POST['variable'])?$_POST['variable']:null?>>
													<b>Enter Firm Name</b> 
												</label>
                                        </div>
                                	</div>
                                    <div class ="row" id="one" style="display:none;">
                                		<form class="form-horizontal"  method="post" action="<?php echo base_url('trade_da/track_application_no'.(($view??false)?"/$view":""));?>">
                                			<div class="form-group">
                                				<div class="col-md-3"></div>
	                                    		<div class="col-md-4">
	                                    			<input type="text" minlength="4" value="<?=isset($application_no)?$application_no:null?>" id="applcn_no" name="applcn_no" class="form-control" placeholder="Enter one of the above..." required>
													<br><span style="color: red"></span>
												</div>
												<div class="col-md-2">
													<button class="btn btn-primary " id="btn_search" name="btn_search" type="submit">Search</button>
												</div>
											</div>
                                		</form>
                                    </div>
                                    <div class="row" id="two" style="display:none;">
	                                    	<form class="form-horizontal"  method="post" action="<?php echo base_url('trade_da/track_application_no'.(($view??false)?"/$view":""));?>">
	                                    		<div class="form-group">
                                				<div class="col-md-3"></div>
	                                            <div class="col-md-4">
	                                                <input type="text" minlength="4" value="<?=($_SERVER['REQUEST_METHOD']=='POST')?$_POST['firm_name']:null?>" id="firm_name" name="firm_name" class="form-control" placeholder="Enter Firm Name...">
													<br><span style="color: red">
														<?php if(isset($validation)){ ?>
															<?=   $validation; ?>
														<?php } ?>
														</span>
														<span>
															<?php if(isset($error)){ ?>
																<?=$error?>
															<?php } ?>
														</span>
												</div>
												<div class="col-md-2">
													<button class="btn btn-primary " id="btn_search" name="btn_search" type="submit">Search</button>

												</div>
												</div>
	                                        </form>
                                    	</div>


                                                	<!-- <br><br><br> -->
													<!-- <div class="col-md-8 col-md-offset-2"> -->
														<!-- <input type="radio" id="sby_whatever" name="search_by" value="sby_whatever" onclick="showBox(this.value);">
														<label class="control-label" for="from_date"><b>Enter Application, Licence or Mobile No </b> </label>&emsp;
														<input type="radio" id="sby_fname" name="search_by" value="sby_fname" onclick="showBox(this.value);">
														<label class="control-label" for="from_date" <?= isset($_POST['variable'])?$_POST['variable']:null?>><b>Enter Firm Name</b> </label><br> -->

														<!-- <div class="col-md-6" id="anything" style="display:none;">
															<input type="text" value="<?=isset($application_no)?$application_no:null?>" id="applcn_no" name="applcn_no" class="form-control" placeholder="Enter one of the above...">
															<span style="color: red"><br>
															<button class="btn btn-primary " id="btn_search" name="btn_search" type="submit">Search</button>

														</div> -->
														
																<?php //if(isset($validation)){ ?>
																<?php//  $validation; ?>
																<?php //} ?>
															<!-- </span> -->
																<?php //if(isset($error)){ ?>
																<?php //$error?>
																<?php// } ?>
														 
														
													<!-- </div> -->
												
                                            <!-- </form> -->
                                            <!-- <form class="form-horizontal" id="fname" method="post" style="display:none;" action="<?php echo base_url('trade_da/track_application_no');?>">
                                            	<div class="col-md-4" style="margin-left: 250px;">
													<input type="text" value="<?=($_SERVER['REQUEST_METHOD']=='POST')?$_POST['firm_name']:null?>" id="firm_name" name="firm_name" class="form-control" placeholder="Enter Firm Name..." >
													<span style="color: red;"><br>
													<button class="btn btn-primary " id="btn_search" name="btn_search" type="submit">Search</button>
                                           
												</div>
											</form> -->
                                       <!--  </div>
                                    </div><br><br><br> -->
									<div class="row">
										<div class="col-md-12 table-responsive">
											<?php 
												if(isset($id) && !empty($id)){
													$i=$offset;
													$j=0;
													// echo '<script>alert('.sizeof($id).')</script>';
											?>
											<table class="table  table-bordered table-hover table-striped">
												<thead>
													<tr>
														<th>#.</th>
														<th>Action</th>
														<th>Application No.</th>
														<th>Licence No.</th>
														<th>Applicant Name</th>
														<th>Father's Name</th>
														<th>Mobile No.</th>
														<th>Firm Name</th>
														<th>Valid Upto</th>
														<th>Applied Date</th>
														<th>Applied From</th>
													</tr>
												</thead>
												<tbody>
													<?php
														foreach($id as $val){ ?>
															<tr>
																<td><?= ++$i; ?></td>
																<td>
																	<a href="<?= base_url($page."/".md5($val['id'])) ?>" class="btn btn-primary">
																		View
																	</a>
																</td>
																<td><?= $val['application_no']??null; ?></td>
																<td><?= $val['license_no']??null; ?></td>
																<td><?= $val['applicant_name']??null; ?></td>
																<td><?= $val['father_name']??null; ?></td>
																<td><?= $val['mobile_no']??null; ?></td>
																<td><?= $val['firm_name']??null; ?></td>
																<td>
																	<?=  (($val['validity']!='N/A'))?date('d M Y',strtotime($val['validity'])):"N/A"; ?>
																	
																</td>
																<td>
																	<?= (($val['apply_date']!='N/A'))?date('d M Y',strtotime($val['apply_date'])):"N/A";  ?>
																	
																</td>
																<td><?= $val['apply_from']??null; ?></td>
															</tr>

													<?php }} ?>

													 
													

												</tbody>
											</table>
												<?php //echo pagination($id['count']); ?>
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
    $('#from_date').datepicker({
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });
    $('#to_date').datepicker({
    	format: "yyyy-mm-dd",
    	weekStart: 0,
    	autoclose:true,
    	todayHighlight:true,
    	todayBtn: "linked",
    	clearBtn:true,
    	daysOfWeekHighlighted:[0]
    });

	$(document).ready(function() {
		
		$('#one').css('display','block');


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
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5] }
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


   function showBox(e){
   	debugger;
   	var box_val = e;
   	if(box_val=="sby_fname"){

   		$('#two').css('display','block');
   		$('#one').css('display','none');
   		$('#anything').attr('disabled','disabled');
   		$('#anything').val(null);
   	}else if(box_val=="sby_whatever"){

   		$('#two').css('display','none');
   		$('#one').css('display','block');
   		$('#fname').attr('disabled','disabled');
   		$('#fname').val(null);
   	}
   }
 </script>
