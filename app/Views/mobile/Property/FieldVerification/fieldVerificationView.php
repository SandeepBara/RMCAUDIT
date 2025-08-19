<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content"> 
		<div class="panel panel-bordered panel-dark">
			<div class="panel-heading">
				<h3 class="panel-title">SAF Verification Details</h3>
			</div>
			
			<div class="panel-body">
				<div class="row pad-btm">
					<div class="col-md-12" style="text-align: center;">
						<div class="col-md-4">
							<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is :</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$saf_no?></span>
						</div>
						<div class="col-md-4">
							<span style="font-weight: bold; color: #bb4b0a;">Applied Date :   </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$apply_date?></span>
						</div>
					</div>
				</div>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Owner Details</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<th scope="col">Name</th>
								<th scope="col">Guardian's Name</th>
								<th scope="col">Relation</th>
							</thead>		
							<tbody>
							<?php 
							foreach ($owner_detail as  $owner_val)
							{
								?>
								<tr class="onr_dtl" id='occ_tr_1'>
									<td><?=$owner_val["owner_name"]?></td>
									<td><?=$owner_val["guardian_name"]?></td>
									<td><?=$owner_val["relation_type"]?></td>
								</tr>
								<?php 
							}
							?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title">Verification Details</h3>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
							<thead class="bg-trans-dark text-dark">
								<th class="text-center" colspan="2"><strong>Self Assessed </strong></th>
								<th class="text-center" colspan="2"><strong>Verification Report </strong></th>
								
							</thead>		
							<tbody>
								<tr>
									<td>Property Type : </td>
									<td><b><?=$property_type?></b></td>
									<td><b><?=$vproperty_type?></b></td>
								</tr>
								<tr>
									<td>Ward No. : </td>
									<td><b><?=$ward_no?></b></td>
									<td><b><?=$vward_no?></b></td>
								</tr>
								<tr>
									<td>Zone : </td>
									<td><b><?=$zone_id; ?></b></td>
									<td><b><?=$vzone_id; ?></b></td>
								</tr>
								<tr>
									<td>Width of Road : </td>
									<td><b><?=$road_type; ?></b></td>
									<td><b><?=$vroad_type; ?></b></td>
								</tr>
								<tr>
									<td>Area of Plot (in Decimal) : </td>
									<td><b><?=$area_of_plot; ?></b></td>
									<td><b><?=$varea_of_plot; ?></b></td>
								</tr>
								<tr>
									<td>Rainwater harvesting provision : </td>
									<td><b><?php echo $is_water_harvesting=='t'?'Yes':'No';?></b></td>
									<td><b><?php echo $vis_water_harvesting=='t'?'Yes':'No';?></b></td>
								</tr>
								<tr>
									<td>Does Property Have Mobile Tower? : </td>
									<td><b><?php echo $is_mobile_tower=='t'?'Yes':'No';?></b></td>
									<td><b><?php echo $vis_mobile_tower=='t'?'Yes':'No';?></b></td>
								</tr>
								<tr>
									<td>Does Property Have Hoarding Board? : </td>
									<td><b><?php echo $is_hoarding_board=='t'?'Yes':'No';?></b></td>
									<td><b><?php echo $vis_hoarding_board=='t'?'Yes':'No';?></b></td>
								</tr>
								<tr>
									<td>Is property have Petrol Pump ? : </td>
									<td><b><?php echo $is_petrol_pump=='t'?'Yes':'No';?></b></td>
									<td><b><?php echo $vis_petrol_pump=='t'?'Yes':'No';?></b></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php 
				
				if($prop_type_detail['property_type']<>"Vacant Land")
				{
					?>
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Self Assessed  Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<th class="text-center"><strong>Floor No</strong></th>
									<th class="text-center"><strong>Use Type</strong></th>
									<th class="text-center"><strong>Occupancy Type</strong></th>
									<th class="text-center"><strong>Construction Type</strong></th>
									<th class="text-center"><strong>Built Up Area  &nbsp;<span style="font-size: smaller">(in Sq. Ft)</span></strong></th>
									<th class="text-center"><strong>Carpet Area &nbsp;<span style="font-size: smaller">(in Sq. Ft)</span></strong></th>
									<th class="text-center" title="Date of Completion"><strong>Date Of Completion&nbsp;</strong></th>
								</thead>		
								<tbody>
								<?php 
								foreach ($floor_details as  $valfloor)
								{
									
									?>	
									<tr class="text-center">
										<td><?=$valfloor['floor_name']?></td>
										<td><?=$valfloor['usage_type']?></td>
										<td><?=$valfloor['occupancy_name']?></td>
										<td><?=$valfloor['construction_type']?></td>
										<td><?=$valfloor['builtup_area']?></td>
										<td><?=$valfloor['carpet_area']?></td>
										<td><?=date('m-Y',strtotime($valfloor['date_from']))?></td>
									</tr>
									<?php 
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
					
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title">Verified Details</h3>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
								<thead class="bg-trans-dark text-dark">
									<th class="text-center"><strong>Floor No</strong></th>
									<th class="text-center"><strong>Use Type</strong></th>
									<th class="text-center"><strong>Occupancy Type</strong></th>
									<th class="text-center"><strong>Construction Type</strong></th>
									<th class="text-center"><strong>Built Up Area  &nbsp;<span style="font-size: smaller">(in Sq. Ft)</span></strong></th>
									<th class="text-center"><strong>Carpet Area &nbsp;<span style="font-size: smaller">(in Sq. Ft)</span></strong></th>
									<th class="text-center" title="Date of Completion"><strong>Date Of Completion&nbsp;</strong></th>
								</thead>		
								<tbody>
								<?php 
								foreach ($vfloor_details as  $valfloor1)
								{
									
									?>	
									<tr class="text-center <?=($valfloor1['saf_floor_dtl_id']=="0")?"text-danger":NULL;?>">
										<td><?=$valfloor1['floor_name']?></td>
										<td><?=$valfloor1['usage_type']?></td>
										<td><?=$valfloor1['occupancy_name']?></td>
										<td><?=$valfloor1['construction_type']?></td>
										<td><?=$valfloor1['builtup_area']?></td>
										<td><?=$valfloor1['carpet_area']?></td>
										<td><?=date('m-Y',strtotime($valfloor1['date_from']))?></td>
									</tr>
									<?php 
								}
								?>
								</tbody>
							</table>
						</div>
					</div>
					<?php 
				}
				?>
				<div class="panel">
					<div class="panel-body text-center">
					<?php
						if(isset($levelId) && $levelId==0){
							?>
							<center><a href="<?=base_url("mobisafDemandPayment/saf_property_details/".md5($Saf_dtl_id));?>"><span class="btn btn-info">Go to Home</span></a></center>
							<?php
						}else{
							?>
							<center><a href="<?=base_url('mobi/home');?>"><span class="btn btn-info">Go to Home</span></a></center>
							<?php
						}
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
    <!--End page content-->
</div>
	
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
 	