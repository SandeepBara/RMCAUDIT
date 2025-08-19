<?=$this->include("layout_mobi/header");?>
<style type="text/css">
	.error{
		color: red;
	}
</style>

<!--CONTENT CONTAINER-->
	<div id="content-container">
    <!--Page content-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
				
					<div class="panel-heading flex" style="display: flex;">
						<div style="flex:1;"><h3 class="panel-title"><b style="color:white;">Search Property</b></h3></div>
						<div style="flex:1;text-align:right"><a href="<?=base_url('Mobi/mobileMenu/property');?>" class="btn btn-info btn_wait_load">Back</a></div>
						
					</div>
					<div class="panel-body" id="demo">
                    <div class="col-md-12">
						<form id="Form" action="<?php echo base_url('mobi/list_of_Property');?>" method="get">
							<div class="row">
								<div class="col-md-12">
									<div class="radio">
										<input type="radio" id="by_15_holding_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_15_holding" <?= isset($by_holding_owner_dtl) ? (strtolower($by_holding_owner_dtl) == "by_15_holding") ? "checked" : "" : "checked"; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter 15 Digit Unique No.');">
										<label for="by_15_holding_dtl">By 15 Digit Holding Details</label>
									</div>
								</div>
								<div class="col-md-12">
									<div class="radio">
										<input type="radio" id="by_holding_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_holding" <?= isset($by_holding_owner_dtl) ? (strtolower($by_holding_owner_dtl) == "by_holding") ? "checked" : "" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Holding No. ');">
										<label for="by_holding_dtl">By Holding Details</label>
									</div>
								</div>
								<div class="col-md-12">
									<div class="radio">
										<input type="radio" id="by_owner_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_owner" <?= (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_owner") ? "checked" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Register Mobile No. Or Owner Name');">
										<label for="by_owner_dtl">By Owner Details</label>
									</div>
								</div>
								<div class="col-md-12">
									<div class="radio">
										<input type="radio" id="by_address_dtl" class="magic-radio" name="by_holding_owner_dtl" value="by_address" <?= (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_address") ? "checked" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Address');">
										<label for="by_address_dtl">By Address</label>
									</div>
								</div>
							</div>
							<div class="col-md-12" style="font-size:14px;">
								<div class="row">
									<div class="col-md-2">
										<label for="exampleInputEmail1">Ward No. </label>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<select id="ward_mstr_id" name="ward_mstr_id" class="form-control m-t-xxs">
												<option value="">Select</option>
												<?php if($ward): ?>
												<?php foreach($ward as $post): ?>
												 <option value="<?=$post['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$post["ward_mstr_id"]?"SELECTED":"":"";?>><?=$post['ward_no'];?></option>
												<?php endforeach; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>
									<div class="col-md-2">
									<?php 
										$keyword_change_id = "";
										if (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_owner") { 
											$keyword_change_id = "Enter Register Mobile No. Or Owner Name";
										} else if (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_address") { 
											$keyword_change_id = "Enter Address"; 
										} else if (isset($by_holding_owner_dtl) && strtolower($by_holding_owner_dtl) == "by_holding") { 
											$keyword_change_id = "Enter Holding No."; 
										} else {
											$keyword_change_id = "Enter 15 Digit Unique No."; 
										}  
									?>
										<label for="keyword">Enter Keywords <span class="text-danger">*</span></label>
										<i id="keyword_change_id" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="<?=$keyword_change_id;?>"></i>
										<!-- <label for="keyword">Enter Keywords <span class="text-danger">*</span> <i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="Enter Holding No. Or 15 Digit Unique No. Or Register Mobile No. Or Owner Name"></i> </label> -->
									</div>
									<div class="col-md-3 pad-btm">
										<div class="form-group">
											<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter keyword" value="<?=$keyword??"";?>">
										</div>
									</div>
									
								
									<div class="col-md-2 pad-btm text-center">
										<button type="submit" id="search" class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</form>
                	</div>
            	</div>
			</div>
            <!-------Transfer Mode-------->
			
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>Property List</b></h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="demo_dt_basic" class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th>Sl No. </th>
									<th>Ward No </th>
									<th>Holding No </th>
									<th>New Holding No </th>
									<th>Owner(s) Name </th>
									<th>Address </th>
									<th>Mobile No. </th>
									<th>Khata No. </th>
									<th>Plot No. </th>
									<th>Action </th>
								</tr>
							</thead>		
							<tbody id="tr_tbody">
								<?php if(isset($posts)):
										$i=1;  ?>
										<?php foreach($posts as $post): ?>
									<tr>
										<td><?=++$offset; ?></td>
										<td><?=$post['ward_no']; ?></td>
										<td><?=$post['holding_no']; ?></td>
										<td><?=$post['new_holding_no']; ?></td>
										<td><?=$post['owner_name']; ?><br></td>
										<td><?=$post['prop_address']; ?></td>
										<td><?=$post['mobile_no']; ?></td>
										<td><?=$post['khata_no']; ?></td>
										<td><?=$post['plot_no']; ?></td>
										<td>
											<a href="<?=base_url('Mobi/full/'.md5($post['prop_dtl_id']));?>" type="button" class="btn btn-primary customer_view_detail" style="color:white;">View</a>
										</td>
									</tr>
									<?php endforeach; ?>
									<?php else: ?>
									<tr>
										<td colspan="8" class="text text-center text-danger text-bold"> Data Are Not Available!!</td>
									</tr>
									<?php endif; ?>
									
								</tr>
							</tbody>
						</table>
						<?=pagination(isset($pager)?$pager:0);?>
					</div>
                </div>
            </div>
            
        
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
$("#search").click(function() {
	if ($("#ward_mstr_id").val()!="" && $("#keyword").val()!="") {
		$("#search").html("Please wait..");
	}
});
$(".customer_view_detail").click(function() {
	$(this).html("Wait");
});

$(document).ready(function () {
    $('#Form').validate({ // initialize the plugin
        rules: {
            ward_mstr_id: {
                required: true,
            },
            keyword: {
                required: true,
            }
        }
	});
});
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>