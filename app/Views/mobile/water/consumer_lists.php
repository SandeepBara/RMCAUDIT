<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<style type="text/css">
	.error
	{
		color: red;
	}
</style>
<div id="content-container">
	<!--Page content-->
	<div id="page-content">       
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<div class="panel-control">						
					<a class="pull-right btn btn-info btn_wait_load" href="#" onclick="history.back()">
						<i class="fa fa-arrow-left" aria-hidden="true"></i>Back
					</a>                             
				</div>
				<h3 class="panel-title">Search Consumer List </h3>
			</div>
			<div class="panel-body">
				<form method="get" action="<?=base_url()."/WaterSearchConsumerMobile/search_consumer_tc";?><?=(isset($param) && !empty($param))?('/'.$param):'';?>" id="search_consumer">
					<div class="row">
						<div class="col-md-2">
							<label for="exampleInputEmail1">Ward No.  :</label>
						</div>
						<div class="col-md-3">
							<div class="form-group">
								<select name="ward_id" id="ward_id" class="form-control">
									<option value="">Select</option>
									<?php
									if($ward_list):
										foreach($ward_list as $val):
											?>
											<option value="<?php echo $val['ward_mstr_id'];?>" <?php if($ward_id==$val['ward_mstr_id']){ echo "selected";} ?>><?php echo $val['ward_no'];?></option>
											<?php
										endforeach;
									endif;

									?>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<label for="exampleInputEmail1">Keyword  :</label>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<input type="text" name="keyword" id="keyword" value="<?php echo $keyword;?>" class="form-control" placeholder="Enter Owner Name or Mobile No. or Consumer No.">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
							<input type="submit" name="Search" id="Search" value="Search" class="form-control btn btn-success">
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">Consumer List </h3>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered ">
					<thead>
						<th>S No.</th>
						<th>Consumer No.</th>
						<th>Ward No.</th>
						<th>Application No.</th>
						<th>Consumer Name</th>
						<th>Mobile No.</th>
						<th>Address</th>
						<th>View</th>                     
					</thead>
					<tbody>
						<?php
						if($consumer_dtls)
						{
							$i=isset($offset)?($offset+1):1;
							foreach($consumer_dtls as $val)
							{
								?>
								<tr>
									<td><?php echo $i;?></td>
									<td><?php echo $val['consumer_no'];?></td>
									<th><?php echo $val['ward_no'];?></th>
									<td><?php echo $val['application_no'];?></td>
									<td><?php echo $val['applicant_name'];?></td>
									<td><?php echo $val['mobile_no'];?></td>
									<td><?php echo (isset($val['address']) && !empty($val['address'])? $val['address']:'N/A');?></td>
							<!--<td><a href="<?php echo base_url('WaterViewConsumerMobile/view/'.md5($val['id']));?>" class="btn btn-info">View</a></td>
							-->
							<?php
							if($param=='update')
							{
								?>
								<td><a href="<?php echo base_url('WaterUpdateConsumerConnection/index/'.md5($val['id']).'/tc');?>" class="btn btn-info btn_wait_load">View</a></td>
								<?php
							}
							elseif($param=='survey')
							{
								?>
								<td><a href="<?php echo base_url('WaterMobileIndex/WaterSurvey/'.md5($val['id']).'');?>" class="btn btn-info btn_wait_load">View</a></td>
								<?php
							}
							else
							{
								?>
								<td><a href="<?php echo base_url('WaterViewConsumerMobile/view/'.($val['id']));?>" class="btn btn-info btn_wait_load">View</a></td>
								<?php
							}
							?>
							

							
							
							
						</tr>
						<?php
						$i++;
					}
				}
				?>
			</tbody>
		</table>
		<?php echo pagination($count??0,10);?>
	</div>

</div>
</div>
</div>
<!--End page content-->
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>

	$(document).ready(function () 
	{

	    $('#search_consumer').validate({ // initialize the plugin
	    	rules: {
	    		ward_id: {
	    			required: true,

	    		},
	    	}
	    });
	});

	$('#Search').click(function(){
		if($('#ward_id').val()=="" && $('#keyword').val()==""){
			return false;
		}else{
			$('#Search').val('Please_wait');
			return true;
		}
	});
</script>