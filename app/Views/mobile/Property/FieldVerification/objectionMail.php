<?=$this->include("layout_mobi/header");?>

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content"> 
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				
				<h3 class="panel-title" style="color: white;">Objected Property List</h3>
			</div>  
			<div class="panel-body">
			
            <?php     
            if(isset($objectionList)):
				if(!empty($objectionList)):
					foreach ($objectionList as  $value):
						?>  
						<div class="panel panel-bordered panel-dark">
							
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Objection No</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["objection_no"]?></strong></label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Ward No</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["ward_no"]?></strong></label>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Assessment Type</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["assessment_type"]?></strong></label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Property Type</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["property_type"]?></strong></label>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Applicant Name</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["owner_name"]?></strong></label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Mobile No.</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["mobile_no"]?></strong></label>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label for="exampleInputEmail1">SAF No.</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["saf_no"]?></strong></label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Holding No.</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["holding_no"]?></strong></label>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-2">
										<label for="exampleInputEmail1">Property Address</label>
									</div>
									<div class="col-sm-2">
										<label for="exampleInputEmail1"><strong><?=$value["prop_address"]?></strong></label>
									</div>
								</div>
								
								<div class="span12 text text-center" style="margin-left:0px;">
									<a href="fieldVerifyObj/<?=md5($value["id"])?>"><span class="btn btn-info">Click To Survey</span></a></center>
								</div>
							</div>
						</div>
						<?php 
					endforeach;
				endif;
            endif;
			?>
    		</div>
		</div>
	</div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->

<?=$this->include("layout_mobi/footer");?>
