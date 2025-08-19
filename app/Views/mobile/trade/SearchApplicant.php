<?=$this->include("layout_mobi/header");?>


<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                
                <!--===================================================-->
                <div id="page-content">
					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel panel-bordered panel-dark">
					            <div class="panel-heading">
					                <h5 class="panel-title"> Search Trade Application <span class = "pull-right btn btn-info btn_wait_load" onclick="history.back();"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</span></h5>
					            </div>
                                <div class="panel-body">
                                    <div class ="row">
                                        <div class="col-md-12">
                                            <form class="form-horizontal" method="GET">
                                                <div class="form-group">
                                                    <div class="col-md-3">
														<label class="control-label" for="from_date"><b>From Date</b> </label>
															<input type="date" id="from_date" name="from_date" class="form-control frmtodate" placeholder="From Date" max="<?=date('Y-m-d')?>" value="<?=(isset($fromdate))?$fromdate:date('Y-m-d');?>">														
													</div>
													<div class="col-md-3">
														<label class="control-label" for="to_date"><b>To Date</b> </label>
															<input type="date" id="to_date" name="to_date" class="form-control frmtodate" placeholder="To Date" max="<?=date('Y-m-d');?>"  value="<?=(isset($todate))?$todate:date('Y-m-d');?>">
													</div>
													
													 <div class="col-md-3">
															<label class="control-label" for="ward No"><b> Keyword </b><span class="text-danger">*</span> </label>
															<input type="text" name="keyword" id="keyword" class="form-control" value="<?=$keyword ?? NULL;?>" />
													</div>
													<div class="col-md-2">
														<label class="control-label" for="department_mstr_id">&nbsp;</label>
														<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
													</div>
												</div>
                                            </form>
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="panel panel-bordered panel-dark">
								<div class="panel-heading">
									<h3 class="panel-title">Applicant List</h3>
								</div>
								<div class="table-responsive">
									<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
										<thead class="bg-trans-dark text-dark">
											<tr>
												<th>#</th>
												<th>Ward No.</th>
												<th>Application No.</th>
												<th>Firm Name</th>
												<th>Mobile No.</th>
												<th>Application Type</th>
												<th>Valid Upto</th>
												<th>Apply Date</th>
												<th>Action</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php
										if(isset($application_details)):
										if(empty($application_details)):
										?>
											<tr>
												<td colspan="7" style="text-align: center;">Data Not Available!!</td>
											</tr>
										<?php else:
											$i=0;
											foreach ($application_details as $value):
										?>
											<tr>
												<td><?=++$i;?></td>
												<td><?=$value["ward_no"];?></td>
												<td><?=$value["application_no"];?></td>
												<td><?=$value["firm_name"];?></td>
												<td><?=$value["mobile"];?></td>
												<td><?=$value["application_type"];?></td>
												<td><?=($value["valid_upto"] != null)?date('d M Y',strtotime($value["valid_upto"])):"N/A";?></td>
												<td><?=date('d M Y',strtotime($value["apply_date"]));?></td>
												<td>
													<a class="btn btn-primary btn_wait_load" href="<?php echo base_url('mobitradeapplylicence/trade_licence_view/'.md5($value['id']));?>" role="button">View</a>

												</td>
											</tr>
										<?php endforeach;?>
										<?php endif;?>
										<?php endif;?>
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
<?=$this->include("layout_mobi/footer");?>

<script>
$(".frmtodate").change(function(){ 
var from_date = $("#from_date").val();
var to_date = $("#to_date").val();
if(from_date > to_date)
{
    alert("From Date should not be greater then To Date");
    $("#to_date").val("");
}

});

$('#btn_search').click(function(){

	if($('#from_date').val()==null && $('#to_date').val() && $('#keyword').val()==null){
		return false;
	}else{
		$('#btn_search').html('Please Wait...');
		return true;
	}
})
</script>
