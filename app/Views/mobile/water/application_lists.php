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
					<h3 class="panel-title">Search Applicants</h3>
				</div>
				<div class="panel-body">
					<form method="get" action="" id="search_consumer">
						<div class="row">
							<div class="col-md-2">
								<label for="exampleInputEmail1">Ward No.  :</label>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<select name="ward_id" id="ward_id" class="form-control">
										<option value="">Select</option>
										<?php
										if($ward_list):
										foreach($ward_list as $val):
											?>
											<option value="<?php echo $val['ward_mstr_id'];?>" <?php if($ward_id==$val['ward_mstr_id']){ echo "selected"; }?>><?php echo $val['ward_no'];?></option>
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
							<div class="col-md-2">
								<div class="form-group">
									<input type="text" name="keyword" id="keyword" value="<?php echo $keyword;?>" class="form-control" placeholder="Enter Owner Name or Mobile No. or Consumer No.">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group" style="text-align: center;">
									<input type="submit" name="Search" id="Search" value="Search" class="btn btn-success" />
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h3 class="panel-title">Application List </h3>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered ">
						<thead>
							<th>S No.</th>
							<th>Ward No.</th>
							<th>Application No.</th>
							<th>Consumer Name</th>
							<th>Mobile No.</th>
							<th>View</th>
						</thead>
						<tbody>
							<?php
							if($applicants_dtl)
							{
								$i=$offset??1;
								foreach($applicants_dtl as $val)
								{
									?>
									<tr>
										<td><?php echo $i;?></td>
										<td><?php echo $val['ward_no'];?></td>
										<td><?php echo $val['application_no'];?></td>
										<td><?php echo $val['applicant_name'];?></td>
										<td><?php echo $val['mobile_no'];?></td>
										<td><a href="<?php echo base_url('WaterPaymentConnectionMobile/payment/'.md5($val['id']));?>" class="btn btn-info btn_wait_load">View</a></td>
									</tr>
									<?php
									$i++;
								}
							}
							?>							
						</tbody>						
					</table>
					<?=pagination($count??0);?>
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
    		$('#Search').val('Please Wait...');
    		return true;
    	}
    	
    });
</script>