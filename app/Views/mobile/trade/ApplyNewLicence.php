<?=$this->include("layout_mobi/header");?>


<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                
                <div id="page-content">
					
					        <div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h5 class="panel-title">Apply Licence</h5>
									</div>
									<div class="panel-body">
                                   
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="post">
												<div class="form-group">
                                                    <div class="col-md-12">
														<a class="btn btn-primary" href="<?=base_url('');?>/tradeapplylicenceMobile/ApplyNewLicenseMobi/<?=md5(1);?>" role="button">Apply New License</a>
													</div>
												</div>
                                                <div class="form-group">
													<div class="col-md-3">
														<label class="control-label" for="from_date"><b>From Date</b> </label>
														<div class="input-group date">
															<input type="text" id="from_date" name="from_date" class="form-control mask_date" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" readonly>
															<span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
														</div>
													</div>
                                                <div class="col-md-3">
                                                    <label class="control-label" for="to_date"><b>To Date</b> </label>
                                                    <div class="input-group">
                                                        <input type="text" id="to_date" name="to_date" class="form-control mask_date" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" readonly>
                                                        <span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="control-label" for="to_date"><b>&nbsp;</b> </label>
                                                    <div class="input-group date">     
                                                       <label class="control-label"><b>OR</b> </label>
                                                    </div>
                                                </div>
                                                 <div class="col-md-3">
                                                    <label class="control-label" for="keyword"><b>Keyword</b><span class="text-danger">*</span> </label>
                                                    <input type="text" id="keyword" name="keyword" class="form-control" placeholder="Enter Keyword" value="">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                                    <button class="btn btn-success btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
								</div>
								<div class="panel panel-bordered panel-dark">
									<div class="panel-heading">
										<h5 class="panel-title">Apply Licence</h5>
									</div>
                                    
                                    <div class="table-responsive">
										<table id="demo_dt_basic" class="table table-bordered" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>Sl No.</th>
													<th>Ward No.</th>
													<th>Licence No.</th>
													<th>Estd Date</th>
													<th>Firm Name</th>
													<th>Owner Name</th>
													<th>Mobile No.</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php //print_r($owner);
												if(isset($licencedet)):
												if(empty($licencedet)):
												?>
												<tr>
													<td colspan="8" style="text-align: center;">Data Not Available!!</td>
												</tr>
												<?php else:
												$i=0;
												foreach ($licencedet as $value):
												?>
												<tr>
													<td><?=++$i;?></td>
													<td><?=$value["ward_no"];?></td>
													<td><?=$value["license_no"];?></td>
													<td><?=$value["establishment_date"];?></td>
													<td><?=$value["firm_name"];?></td>
													<td><?=$value["owner_name"];?></td>
													<td><?=$value["mobile"];?></td>
													<td>
														<?php 
														if($value["valid_upto"] > date('Y-m-d'))
														{
															echo 'Active';
														}
														else
														{
															echo 'Expired';
														}
														?>
														</td>
													<td>
														<center><button class="btn btn-sm btn-primary" type="button" onclick="openmodel('<?=md5($value["id"]);?>')">Do</button></center>
													</td>
												</tr>
												<?php endforeach;?>
												<?php endif;  ?>
												<?php endif;  ?>
											</tbody>
										</table>
									</div>
								</div>
				</div>
					
					    
				
						<!-- The Modal -->
							<div class="modal" id="myModal">
								<div class="modal-dialog">
									<div class="modal-content">
					  
										<!-- Modal Header -->
										<div class="modal-header">
										  <h4 class="modal-title">Select Licence Type</h4>
										</div>
						
										<!-- Modal body -->
										<div class="modal-body">
											<input type="hidden" name="modelid" id="modelid" value="">
											<div class="col-md-8 text-center pad-btm">
												<select name="licence_Type" id="licence_Type" onchange="typ_licnc()" class="form-control" >
													<option value="">SELECT</option>
													<option value="2">RENEWAL</option>
													<option value="3">AMENDMENT</option>
													<option value="4">SURRENDER</option>
												</select>
											</div>
											<button type="button" class="btn btn-primary" onclick="ProceedFun();">Proceed</button>
										</div>
						
										<!-- Modal footer -->
										<div class="modal-footer">
										  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
										</div>
									</div>
								</div>
							</div>
  
                <!--===================================================-->
                <!--End page content-->
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>



<script type="text/javascript">

function openmodel(val){
	$("#modelid").val(val);
	$("#myModal").modal();
}
function ProceedFun()
{

	var modelid = $("#modelid").val();
	var licence_Type = $("#licence_Type").val();
	
	if(licence_Type!='') {
		if(licence_Type==2){
			window.location.href = "<?=base_url('');?>/tradeapplylicenceMobile/applylicence/<?=md5(2);?>/"+modelid;
		} 
		if(licence_Type==3){
			window.location.href = "<?=base_url('');?>/tradeapplylicenceMobile/applylicence/<?=md5(3);?>/"+modelid;
		} 
		if(licence_Type==4){
			window.location.href = "<?=base_url('');?>/tradeapplylicenceMobile/applylicence/<?=md5(4);?>/"+modelid;
		}
	}
	
}
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


	
	});
	
	function typ_licnc(){
		var licence_Type = $("#licence_Type").val();
		if(licence_Type==2){
			$('#surrender').hide(); 
			$('#amendment').hide(); 
			$('#renewal').show();  
		}else if(licence_Type==3){
			$('#amendment').show(); 
			$('#renewal').hide();
			$('#surrender').hide(); 
		}else if(licence_Type==4){
			$('#surrender').show(); 
			$('#renewal').hide(); 
			$('#amendment').hide(); 
		}else{
			$('#amendment').hide(); 
			$('#renewal').hide();
			$('#surrender').hide(); 
		}
	}

 </script>
