<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <ol class="breadcrumb">
						<li><a href="#"><i class="demo-pli-home"></i></a></li>
						<li><a href="#">Adjustment</a></li>
						<li class="active">Search Property</li>
                    </ol>
                </div>
				<!-- ======= Cta Section ======= -->

				<div id="page-content">
					<form action="<?=base_url('amount_adjustment/search_property');?>" method="post" role="form" class="php-email-form" id="myform">
						<div class="panel panel-bordered panel-dark">
							<div class="panel-heading">
								<h3 class="panel-title">Property Search</h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-2">
										<label for="keyword">
											Enter Keywords 
											<i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="Enter Holding No. Or 15 Digit Unique No."></i>
										</label>
									</div>
									<div class="col-md-3 pad-btm">
										<div class="form-group">
											<input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keywords" value="<?=$keyword ?? NULL;?>">
										</div>
									</div>
									<div class="col-md-3 pad-btm">
										<button type="submit" id="search" name="search" class="btn btn-primary" value="search">SEARCH</button>
									</div>
								</div>
							</div>
						</div>
					</form>
					<?php
					if(isset($result))
					{
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Citizen List :</h3>
						</div>
						<div class="panel-body">
							<div id="saf_distributed_dtl_hide_show">
								<div class="row">
									<div class="col-md-12">
										<div class="table-responsive">
											<table id="ss" class="table table-striped table-bordered"  width="100%" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;text-align:center;">
												<thead class="bg-trans-dark text-dark">
													<th>Sl No. </th>
													<th>Ward No </th>
													<th>Holding No </th>
													<th>15 Digit Unique House No. </th>
													<th>Owner(s) Name </th>
													<th>Address </th>
													<th>Mobile No. </th>
													<th>Khata No. </th>
													<th>Plot No. </th>
													<th>Action </th>
												</thead>
												<tbody>
													<?php foreach($result as $post){ ?>
														<tr>
															<td><?=++$offset;?></td>
															<td><?=$post['ward_no']?$post['ward_no']:"N/A"; ?></td>
															<td><?=$post['holding_no']?$post['holding_no']:"N/A"; ?></td>
															<td><?=$post['new_holding_no']?$post['new_holding_no']:"N/A"; ?></td>
															<td>
																<?php echo $post['owner_name']; ?><br>
																
															</td>
															<td><?=$post['prop_address']?$post['prop_address']:"N/A"; ?></td>
															<td><?=$post['mobile_no']?$post['mobile_no']:"N/A"; ?></td>
															<td><?=$post['khata_no']?$post['khata_no']:"N/A"; ?></td>
															<td><?=$post['plot_no']?$post['plot_no']:"N/A"; ?></td>
															<td>
																<?php if($post['status']==0){ ?>
																	<b style="color:red;">Deactive</b>
																<?php } ?>
																<a href="<?php echo base_url('amount_adjustment/adjust_Property_details/'.md5($post['prop_dtl_id']));?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a>
															</td>
														</tr>
													<?php } ?>
												</tbody>
											</table>
											<?=pagination($pager);?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div><br><br><!-- End Contact Section -->

<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script>
$(document).ready(function () 
{
    $('#myform').validate({ // initialize the plugin
        rules: {
            keyword: {
                required: "#keyword:blank",
            }
        }


	});
});
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
