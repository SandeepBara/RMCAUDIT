<?=$this->include("layout_mobi/header");?>


<!--CONTENT CONTAINER-->
	<div id="content-container">
		<!--Page content-->
		<div id="page-content">
			<form action="<?=base_url('SafVerification/field_verification/'.$id.'/'.$levelid);?>" id="form_tc_verification" name="FORMNAME1" method="post" onSubmit="return checkEveryRadioBtnSelectedOrNot();">
				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<div class="panel-control">
							<a href="<?=base_url();?>/SafDocumentMoble/viewDoc/<?=$id;?>" class="btn btn-info btn_wait_load">View Documents</a>
						</div>
						<h3 class="panel-title"><b> Self Assessment - Field Survey </b></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12" style="text-align: center;">
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;"> Your Application No. is :</span> &nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$saf_no?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;">Application Type:   </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$Saf_detail["assessment_type"];?></span>
								</div>
								<div class="col-md-4">
									<span style="font-weight: bold; color: #bb4b0a;">Applied Date :   </span>&nbsp;&nbsp;&nbsp;<span style="color: #179a07;"><?=$apply_date?></span>
								</div>
							</div>
						</div>
						<?php if ($Saf_detail["assessment_type"]=="Mutation") { ?>
						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Previous Owner Details</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Transfer Mode</b>
									</div>
									<div class="col-sm-6">
										<b><?=$transfer_mode??"";?> </b>
									</div>
								</div>
								<br />
								<div class="row">
									<div class="col-sm-12">
										<table class="table table-striped table-bordered">
											<thead class="bg-info">
												<tr>
													<th>Owner Name</th>
													<th>Mobile No.</th>
												</tr>
											</thead>
											<tbody>
											<?php 
											if (isset($prev_prop_owner_dtl)) {
												foreach ($prev_prop_owner_dtl as $key => $prev_prop_value) {
											?>
												<tr>
													<td><?=$prev_prop_value["owner_name"]==""?"N/A":$prev_prop_value["owner_name"];?></td>
													<td><?=$prev_prop_value["mobile_no"]==""?"N/A":$prev_prop_value["mobile_no"];?></td>
												</tr>
											<?php 
												}
											}
											?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
						<?php } ?>
						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Ward No</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $ward_no;?>
										<input type="hidden" name="hid_ward_id" id="hid_ward_id" value="<?=$ward_mstr_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vward_no; ?>
											<input type="hidden" name="vhid_ward_id" id="vhid_ward_id" value="<?=$vward_no?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php $chk="";
										$chk2="";
										$diswrd="";
										if(isset($_POST["rdo_ward_no"])){
											if(@$_POST["rdo_ward_no"]==1){ $chk='checked="checked"';  $diswrd='disabled="disabled"';}if(@$_POST["rdo_ward_no"]==0){ $chk2='checked="checked"';
											}
										}?>
										<input type="radio" name="rdo_ward_no" id="rdo_ward_no1" value="1" onClick="OperateDropDown('rdo_ward_no1', 'ward_id', 'hid_ward_id');mapde_ward(this.value)" <?=$chk?> />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_ward_no" id="rdo_ward_no2"  value="0" onClick="OperateDropDown('rdo_ward_no2', 'ward_id', 'hid_ward_id');mapde_ward(this.value)" <?=$chk2?> />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<?php
									//print_var($ward_list);
								?>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="ward_id" id="ward_id"  <?=$diswrd?> required class="form-control" onchange="append_new_ward(this.value)">
											<option value="">Select</option>
											<?php
											foreach ($ward_list as  $ward)
											{
												?>
												<option value="<?=$ward["ward_mstr_id"]?>" <?php if(isset($_POST["ward_id"]) && $ward["ward_mstr_id"]==$_POST["ward_id"]){?> selected="selected"<?php }?>><?=$ward["ward_no"]?></option>
												<?php 
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<!-- new ward no-->
						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>New Ward No</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $new_ward_no;?>
										<input type="hidden" name="hid_new_ward_id" id="hid_new_ward_id" value="<?=$new_ward_mstr_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vnew_ward_no; ?>
											<input type="hidden" name="vhid_new_ward_id" id="vhid_new_ward_id" value="<?=$vnew_ward_no?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php $chk="";
										$chk2="";
										$disnwrd="";
										if(isset($_POST["rdo_new_ward_no"])){
											if(@$_POST["rdo_new_ward_no"]==1){ $chk='checked="checked"';  $disnwrd='disabled="disabled"';}if(@$_POST["rdo_new_ward_no"]==0){ $chk2='checked="checked"';
											}
										}?>
										<input type="radio" name="rdo_new_ward_no" id="rdo_new_ward_no1" value="1" onClick="OperateDropDown('rdo_new_ward_no1', 'new_ward_id', 'hid_new_ward_id')" <?=$chk?> />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_new_ward_no" id="rdo_new_ward_no2"  value="0" onClick="OperateDropDown('rdo_new_ward_no2', 'new_ward_id', 'hid_new_ward_id')" <?=$chk2?> />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<?php
									//print_var($ward_list);
								?>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="new_ward_id" id="new_ward_id"  <?=$disnwrd?> required class="form-control">
											<option value="">Select</option>
											<?php
											$map_ward = isset($ward_list_mapp)&& !empty($ward_list_mapp) ? $ward_list_mapp : $ward_list; 
											foreach ($map_ward as  $ward)
											{
												?>
												<option value="<?=$ward["ward_mstr_id"]?>" <?php if(isset($_POST["new_ward_id"]) && $ward["ward_mstr_id"]==$_POST["new_ward_id"]){?> selected="selected"<?php }?>><?=$ward["ward_no"]?></option>
												<?php 
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<!-- new ward no-->

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Zone</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $zone_id;?>
										<input type="hidden" name="hid_zone" id="hid_zone" value="<?=$zone_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vzone_id;?>
											<input type="hidden" name="vhid_zone" id="vhid_zone" value="<?=$vzone_id;?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php 
										$chk=NULL;
										$chk2=NULL;
										$diswrd=NULL;
										if(isset($_POST["rdo_zone"]))
										{
											if($_POST["rdo_zone"]==1)
											{
												$chk='checked="checked"';
												$diswrd='disabled="disabled"';
											}
											if($_POST["rdo_zone"]==0)
											{
												$chk2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_zone" id="rdo_zone1" value="1" onClick="OperateDropDown('rdo_zone1', 'zone', 'hid_zone')" <?=$chk?> />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_zone" id="rdo_zone2"  value="0" onClick="OperateDropDown('rdo_zone2', 'zone', 'hid_zone')" <?=$chk2?> />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="zone" id="zone" <?=$diswrd?> required class="form-control">
											<option value="">Select</option>
											<option value="1" <?php if(isset($_POST["zone"]) && $_POST["zone"]==1){?> selected="selected"<?php }?>>1</option>
											<option value="2" <?php if(isset($_POST["zone"]) && $_POST["zone"]==2){?> selected="selected"<?php }?>>2</option>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Property Type</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $property_type;?>
										<input type="hidden" name="hid_property_type" id="hid_property_type" value="<?=$prop_type_mstr_id;?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vproperty_type;?>
											<input type="hidden" name="hid_property_typev" id="hid_property_typev" value="<?=$vproperty_type?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php
										$chkprop=NULL;
										$chkprop2=NULL;
										$disprop=NULL;
										if(isset($_POST["rdo_property_type"]))
										{
											if($_POST["rdo_property_type"]==1)
											{
												$chkprop='checked="checked"';
												$disprop='disabled="disabled"';
											}
											if($_POST["rdo_property_type"]==0)
											{
												$chkprop2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_property_type" id="rdo_property_type1" <?=$chkprop?>  value="1" onClick="OperateDropDown('rdo_property_type1', 'property_type_id', 'hid_property_type')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_property_type" id="rdo_property_type2" <?=$chkprop2?> value="0" onClick="OperateDropDown('rdo_property_type2', 'property_type_id', 'hid_property_type')" />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="property_type_id" id="property_type_id"   <?=$disprop?> class="form-control" onchange="remove_ExteraFloor(this.value);">
											<option value="">Select</option>
											<?php
											foreach ($prop_type_list as  $proptype)
											{
												?>
												<option value="<?=$proptype["id"]?>"  <?php if(isset($_POST["property_type_id"]) && $_POST["property_type_id"]==$proptype["id"]){?> selected="selected"<?php }?>><?=$proptype["property_type"]?></option>
												<?php 
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<?php 
						if($assessment_type=="Mutation" || $assessment_type=="Mutation with Reassessment")
						{
							?>
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Percentage of Property Transferred</b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12">
											<input type="number" name="percentage_of_property" id="percentage_of_property" value="<?=$_POST["percentage_of_property"] ?? NULL;?>" class="form-control" step='0.01' max="100" required/>
										</div>
									</div>
								</div>
							</div>
							<?php 
						}
						?>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Area of Plot (in decimal)</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?=$area_of_plot?>
										<input type="hidden" name="hid_area_of_plot" id="hid_area_of_plot" value="<?=$area_of_plot?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?=$varea_of_plot?>
											<input type="hidden" name="hid_area_of_plotv" id="hid_area_of_plotv" value="<?=$varea_of_plot?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php
										$chkparea=NULL;
										$disparea=NULL;
										$chkparea2=NULL;
										if(isset($_POST["rdo_area_of_plot"]))
										{
											if($_POST["rdo_area_of_plot"]==1)
											{
												$chkparea='checked="checked"'; 
												$disparea='disabled="disabled"';
											}
											if($_POST["rdo_area_of_plot"]==0)
											{
												$chkparea2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_area_of_plot" id="rdo_area_of_plot1" <?=$chkparea?> value="1" onClick="OperateTexBox('rdo_area_of_plot1', 'area_of_plot', 'hid_area_of_plot')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_area_of_plot" id="rdo_area_of_plot2"  <?=$chkparea2?> value="0" onClick="OperateTexBox('rdo_area_of_plot2', 'area_of_plot', 'hid_area_of_plot')" />&nbsp;&nbsp;Incorrect
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b> <input type="tel"  name="area_of_plot" id="area_of_plot"  value="<?=$_POST["area_of_plot"] ?? NULL;?>"  class="form-control" <?=$disparea?> required/>

									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Road Type</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?php echo $road_type; ?>
										<input type="hidden" name="hid_street_type" id="hid_street_type" value="<?=$road_type_mstr_id?>" />
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?php echo $vroad_type; ?>
											<input type="hidden" name="hid_street_typev" id="hid_street_typev" value="<?=$vroad_type?>" />
										</div>
									<?php }?>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<b>Check </b>
									</div>
									<div class="col-sm-6">
										<?php
										
										$chkstreet=NULL;
										$disstret=NULL;
										$chkstreet2=NULL;
										if(isset($_POST["rdo_street_type"]))
										{
											if($_POST["rdo_street_type"]==1)
											{
												$chkstreet='checked="checked"';
												$disstret='disabled="disabled"';
											}
											if($_POST["rdo_street_type"]==0)
											{
												$chkstreet2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_street_type" id="rdo_street_type1"  value="1" <?=$chkstreet?> onClick="OperateDropDown('rdo_street_type1', 'street_type_id', 'hid_street_type')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_street_type" id="rdo_street_type2"  value="0" <?=$chkstreet2?>onClick="OperateDropDown('rdo_street_type2', 'street_type_id', 'hid_street_type')" />&nbsp;&nbsp;Incorrect
									</div>
								</div>

								<div class="row">
									<div class="col-sm-12">
										<b>Verification </b>
										<select name="street_type_id" id="street_type_id"  <?=$disstret?> class="form-control">
											<option value="">Select</option>
											<?php
											foreach ($road_type_list as $valroad)
											{
												?>
												<option value="<?=$valroad["id"]?>" <?php if(isset($_POST["street_type_id"]) && $_POST["street_type_id"]==$valroad["id"]){?> selected="selected"<?php }?>><?=$valroad["road_type"]?></option>
												<?php 
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
				$i=1;
				//print_var($floor_details);//exit;
				foreach ($floor_details as $value)
				{
					if(isset($value["vfloor_details"]) && !empty($value["vfloor_details"]))
					foreach ($value["vfloor_details"] as  $valuefloor)
					{
						$vusage_type=$valuefloor["usage_type"];
						$voccupancy_name=$valuefloor["occupancy_name"];
						$vconstruction_type=$valuefloor["construction_type"];
						$vbuiltup_area=$valuefloor["builtup_area"];
					}
					else
					{
						$vusage_type=NULL;
						$voccupancy_name=NULL;
						$vconstruction_type=NULL;
						$vbuiltup_area=NULL;
					}
					?>

					<div class="panel panel-bordered panel-dark oldFloors" >
						<div class="panel-heading">
							<h3 class="panel-title"><b><?=$value["floor_name"]?></b></h3>
						</div>
						<div class="panel-body">
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Usage Type - <?=$value["floor_name"];?> </b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value['usage_type']?>
											<input type="hidden" id="hid_use_type_id_<?=$i?>" value="<?=$value['usage_type_mstr_id']?>" name="hid_use_type_id_<?=$i?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$vusage_type?>
												<input type="hidden" id="hid_use_type_idv_<?=$i?>" value="<?=$vusage_type?>" name="hid_use_type_idv_<?=$i?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php  
											$chkuse=NULL;
											$chkuse2=NULL;
											$disuse=NULL;
											if(isset($_POST["rdo_usage_type$i"]))
											{
												if($_POST["rdo_usage_type$i"]==1)
												{
													$chkuse='checked="checked"';
													$disuse='disabled="disabled"';
												}
												else if($_POST["rdo_usage_type$i"]==0)
												{
													$chkuse2='checked="checked"';
													$disuse="";
												}
											}
											?>
											<input type="radio" name="rdo_usage_type<?=$i?>" id="rdo_usage_type1"  value="1" <?=$chkuse?> onClick="OperateDropDown('rdo_usage_type1', 'usagetypeid_<?=$i?>', 'hid_use_type_id_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_usage_type<?=$i?>" id="rdo_usage_type2"  value="0"<?=$chkuse2?>  onClick="OperateDropDown('rdo_usage_type2', 'usagetypeid_<?=$i?>', 'hid_use_type_id_<?=$i?>')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b>
											<select name="usagetypeid_<?=$i?>" id="usagetypeid_<?=$i?>" <?=$disuse?> required class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($usage_list as  $valuse)
												{
													?>
													<option value="<?php echo $valuse["id"];?>" <?php if(isset($_POST["usagetypeid_$i"]) && $valuse["id"]==$_POST["usagetypeid_$i"]){?> selected="selected"<?php }?>><?php echo $valuse["usage_type"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Occupancy Type - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value['occupancy_name']?>
											<input type="hidden" name="hid_occupancy_type_id_<?=$i?>" id="hid_occupancy_type_id_<?=$i?>" value="<?=$value["occupancy_type_mstr_id"]?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$voccupancy_name?>
												<input type="hidden" name="hid_occupancy_type_idv_<?=$i?>" id="hid_occupancy_type_idv_<?=$i?>" value="<?=$voccupancy_name?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php  $chkocc=""; $chkocc2="";  $disocc="";
											if(isset($_POST["rdo_occupancy_type$i"])){
												if(@$_POST["rdo_occupancy_type$i"]==1){ $chkocc='checked="checked"';  $disocc='disabled="disabled"';}if(@$_POST["rdo_occupancy_type$i"]==0){ $chkocc2='checked="checked"';$disocc="";
												}
											}?>
											<input type="radio" name="rdo_occupancy_type<?=$i?>" id="rdo_occupancy_type1"  value="1" <?=$chkocc?> onClick="OperateDropDown('rdo_occupancy_type1', 'occupancytypeid_<?=$i?>', 'hid_occupancy_type_id_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_occupancy_type<?=$i?>" id="rdo_occupancy_type2"  value="0"<?=$chkocc2?>  onClick="OperateDropDown('rdo_occupancy_type2', 'occupancytypeid_<?=$i?>', 'hid_occupancy_type_id_<?=$i?> ')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b>
											<select name="occupancytypeid_<?=$i?>" id="occupancytypeid_<?=$i?>" <?=$disocc?> class="form-control" required >
												<option value="">Select</option>
												<?php
												foreach ($occupancy_list as $valoccu)
												{
													?>
													<option value="<?php echo $valoccu["id"];?>"<?php if(isset($_POST["occupancytypeid_".$i]) && $valoccu["id"]==$_POST["occupancytypeid_".$i]){?> selected="selected"<?php }?> ><?php echo $valoccu["occupancy_name"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Construction Type - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value['construction_type']?>
											<input type="hidden" id="hid_construction_type_id_<?=$i?>" value="<?=$value['const_type_mstr_id']?>" name="hid_construction_type_id_<?=$i?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$vconstruction_type?>
												<input type="hidden" name="hid_construction_type_idv_<?=$i?>" id="hid_construction_type_idv_<?=$i?>" value=" <?=$vconstruction_type?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php  $chkcons=""; $chkcons2=""; $discons="";
											if(isset($_POST["rdo_construction_type$i"])){
												if(@$_POST["rdo_construction_type$i"]==1){ $chkcons='checked="checked"';  $discons='disabled="disabled"';}if(@$_POST["rdo_construction_type$i"]==0){ $chkcons2='checked="checked"';$discons="";
												}
											}?>
											<input type="radio" name="rdo_construction_type<?=$i?>" id="rdo_construction_type1"  value="1" <?=$chkcons?> onClick="OperateDropDown('rdo_construction_type1', 'consttypeid<?=$i?>', 'hid_construction_type_id_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_construction_type<?=$i?>" id="rdo_construction_type2"  value="0" <?=$chkcons2?> onClick="OperateDropDown('rdo_construction_type2', 'consttypeid<?=$i?>', 'hid_construction_type_id_<?=$i?>')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b>
											<select name="consttypeid<?=$i?>" id="consttypeid<?=$i?>" <?=$discons?> required class="form-control">
												<option value="">Select</option>
												<?php
												foreach ($const_type_list as  $valcons)
												{
													?>
													<option value="<?php echo $valcons["id"];?>" <?php if(isset($_POST["consttypeid$i"]) && $valcons["id"]==$_POST["consttypeid$i"]){?> selected="selected"<?php }?>><?php echo $valcons["construction_type"];?></option>
													<?php 
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Builtup Area - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?=$value["builtup_area"]?>
											<input type="hidden" id="hid_builtup_area_<?=$i?>" value="<?=$value["builtup_area"]?>" name="hid_builtup_area_<?=$i?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?=$vbuiltup_area?>
												<input type="hidden" name="hid_builtup_areav_<?=$i?>" id="hid_builtup_areav_<?=$i?>" value="<?=$vbuiltup_area?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php 
											$disbarea=NULL;
											$chkbarea2=NULL;
											$chkbarea=NULL;
											if(isset($_POST["rdo_builtup_area$i"]))
											{
												if($_POST["rdo_builtup_area$i"]==1)
												{
													$chkbarea='checked="checked"'; 
													$disbarea="readonly";
												}
												if($_POST["rdo_builtup_area$i"]==0)
												{
													$chkbarea2='checked="checked"';
												}
											}?>
											<input type="radio" name="rdo_builtup_area<?=$i?>" id="rdo_builtup_area1"  value="1" <?=$chkbarea?> onClick="OperateTexBox('rdo_builtup_area1', 'builtuparea_<?=$i?>', 'hid_builtup_area_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_builtup_area<?=$i?>" id="rdo_builtup_area2"  value="0" <?=$chkbarea2?> onClick="OperateTexBox('rdo_builtup_area2', 'builtuparea_<?=$i?>', 'hid_builtup_area_<?=$i?>')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12 ">
											<b>Verification </b> <input type="number" id="builtuparea_<?=$i?>" name="builtuparea_<?=$i?>" value="<?=$_POST["builtuparea_$i"] ?? NULL;?>" required class="form-control oldFloors"/>
										</div>
									</div>
								</div>
							</div>
							<!-- -->
							<div class="panel panel-bordered">
								<div class="panel-heading" style="background:#1b8388f7;">
									<h3 class="panel-title"><b>Date From - <?=$value["floor_name"]?></b></h3>
								</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<b>Self-Assessed </b>
										</div>
										<div class="col-sm-6">
											<?php
												if(isset($value["date_from"])) {
													if($value["date_from"]!="") {
														echo date("Y-m", strtotime($value["date_from"]));
													}
												}
											?>
											<input type="hidden" id="hid_completion_date_<?=$i?>" value="<?=isset($value["date_from"])?date("Y-m", strtotime($value["date_from"])):"";?>" name="hid_completion_date_<?=$i?>" />
										</div>
										<?php if($user_type_mstr_id==7){?>
											<div class="col-sm-6">
												<b>Assessed By Agency TC  </b>
											</div>
											<div class="col-sm-6">
												<?php
													if(isset($vdate_from) && !is_null($vdate_from)) {
															echo date("Y-m", strtotime($vdate_from));
													}
												?>
												<input type="hidden" name="hid_completion_datev_<?=$i?>" id="hid_completion_datev_<?=$i?>" value="<?=isset($vdate_from)?date("Y-m", strtotime($vdate_from)):"";?>" />
											</div>
										<?php }?>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Check </b>
										</div>
										<div class="col-sm-6">
											<?php 
											$disbarea=NULL;
											$chkbarea2=NULL;
											$chkbarea=NULL;
											if(isset($_POST["rdo_completion_date$i"]))
											{
												if($_POST["rdo_completion_date$i"]==1)
												{
													$chkbarea='checked="checked"'; 
													$disbarea="readonly";
												}
												if($_POST["rdo_completion_date$i"]==0)
												{
													$chkbarea2='checked="checked"';
												}
											}?>
											<input type="radio" name="rdo_completion_date<?=$i?>" id="rdo_completion_date1"  value="1" <?=$chkbarea?> onClick="OperateTexBox('rdo_completion_date1', 'completion_date<?=$i?>', 'hid_completion_date_<?=$i?>')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
											<input type="radio" name="rdo_completion_date<?=$i?>" id="rdo_completion_date2"  value="0" <?=$chkbarea2?> onClick="OperateTexBox('rdo_completion_date2', 'completion_date<?=$i?>', 'hid_completion_date_<?=$i?>')" />&nbsp;&nbsp;Incorrect
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<b>Verification </b> <input type="month" id="completion_date<?=$i?>" name="completion_date<?=$i?>" value="<?=$_POST["completion_date$i"] ?? NULL;?>" max="<?=date("Y-m");?>" required class="form-control" />
										</div>
									</div>
								</div>
							</div>
							<!-- -->
						</div>
					</div>
				
					<?php 
					$i++; 
				}
				?>

				<div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Extra Details</b></h3>
					</div>
					<div class="panel-body">
						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Hoarding Board(s)</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Does Property Have Hoarding Board </b>
									</div>
									<div class="col-sm-6">
										<?=$is_hoarding_board=='t'?'Yes':'No';?>
										<input type="hidden" id="hid_builtup_area_<?=$i?>" value="<?=$hoarding_area;?>" name="hid_builtup_area_<?=$i?>" class="form-control"/>
									</div>
									<?php if($user_type_mstr_id==7){?>
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?=$vis_hoarding_board=='t'?'Yes':'No';?>
											<input type="hidden" name="hordv" id="hordv" value="<?=$vis_hoarding_board?>" class="form-control"/>
										</div>
									<?php }?>
								</div>
								<?php if($is_hoarding_board=='t'){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Installation Date of Hoarding Board(s) </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_hording_installation_date" value="<?=$hoarding_installation_date?>" class="form-control"> <?=$hoarding_installation_date?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Total  Area  (in Sq. Ft.) </b>
										</div>
										<div class="col-sm-12">
											<input type="hidden" id="assess_total_hording_area" value="<?=$hoarding_area?>" class="form-control"> <?=$hoarding_area?>
										</div>
									</div>
								<?php }else{?>
									<input type="hidden" id="assess_hording_installation_date" />
									<input type="hidden" id="assess_total_hording_area"  />
								<?php }?>
								<div class="row">
									<div class="col-sm-6 pad-btm">
										<b>Does Property Have Hoarding Board(s)  </b>
									</div>
									<div class="col-sm-6 pad-btm">
										<?php 
										$hordval1=$_POST["has_hording"] ?? NULL;
										
										?>
										<select name="has_hording" id="has_hording" onChange="HideUnhide('has_hording','hrd_details','hording_installation_date','total_hording_area')" class="form-control">
											<option value="">Select</option>
											<option value="1"<?php if($hordval1==1){?> selected="selected"<?php }?> >Yes</option>
											<option value="0" <?php if($hordval1==0){?> selected="selected"<?php }?>>No</option>
										</select>
									</div>
									<div class="col-sm-12 pad-btm">
										<div class="hrd_details" style="display:none">
											Installation Date of Hoarding Board(s) :
											<input type="date" name="hording_installation_date" id="hording_installation_date" class="form-control" value="<?php if(isset($_POST["hording_installation_date"])){echo $_POST["hording_installation_date"];}else{ echo $hoarding_installation_date;}?>" />
											<input type="hidden" id="assess_hording_installation_date" value="<?=$hoarding_installation_date?>" class="form-control"> <?=$hoarding_installation_date?>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<div class="hrd_details" style="display:none">
											Total Floor Area of Roof / Land (in Sq. Ft.)  :
											<input type="text" name="total_hording_area" id="total_hording_area"  value="<?php if(isset($_POST["total_hording_area"])){echo $_POST["total_hording_area"];}else{ echo $hoarding_area;}?>" class="form-control"/>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="panel panel-bordered">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Mobile Tower</b></h3>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Does Property Have Mobile Tower </b>
									</div>
									<div class="col-sm-6">
										<?=$is_mobile_tower=='t'?'Yes':'No';?>
									</div>
								</div>
								<?php if($is_mobile_tower=='t'){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Installation Date of Mobile Tower </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_tower_installation_date" value="<?=$tower_installation_date?>" class="form-control"/><?=$tower_installation_date?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Total  Area  (in Sq. Ft.) </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_total_tower_area" value="<?=$tower_area?>" class="form-control"/> <?=$tower_area?>
										</div>
									</div>
								<?php }else{?>
									<input type="hidden" id="assess_tower_installation_date" />
									<input type="hidden" id="assess_total_tower_area"  />
								<?php }?>
								<?php if($user_type_mstr_id==7){?>
									<div class="row">
										<div class="col-sm-6 pad-btm">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6 pad-btm">
											<?=$vis_mobile_tower=='t'?'Yes':'No';?>
											<input type="hidden" name="towerv" id="towerv" class="form-control" value="<?=$vis_mobile_tower?>" />
										</div>
									</div>
								<?php }?>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<b>Does Property Have Mobile Tower(s)  </b>
										<?php 
										
										if(isset($_POST["has_mobile_tower"])){$towlval=$_POST["has_mobile_tower"];}else{$towlval=NULL;}?>
										<select name="has_mobile_tower" id="has_mobile_tower" onChange="HideUnhide('has_mobile_tower','tw_details','tower_installation_date','total_tower_area')" class="form-control">
											<option value="">Select</option>
											<option value="1"<?php if($towlval==1){?> selected="selected"<?php }?> >Yes</option>
											<option value="0" <?php if($towlval==0){?> selected="selected"<?php }?>>No</option>
										</select>
									</div>
									<div class="col-sm-12 pad-btm">
										<div class="tw_details" style="display:none"><b>Installation Date of Mobile Tower(s) </b>
											<input type="date" name="tower_installation_date" id="tower_installation_date" value="<?=$_POST["tower_installation_date"] ?? $tower_installation_date;?>" class="form-control"/>
											<input type="hidden" id="assess_tower_installation_date" value="<?=$_POST["tower_installation_date"] ?? $tower_installation_date;?>" class="form-control"> <?=$tower_installation_date?>

										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<div class="tw_details" style="display:none">
											<b>Total Floor Area of Roof / Land (in Sq. Ft.) </b>
											<input value="<?=$_POST["total_tower_area"] ?? $tower_area;?>" type="text" name="total_tower_area" id="total_tower_area" class="form-control" />
										</div>
									</div>
								</div>
							</div>
						</div>

						
						

							
						<div class="panel panel-bordered oldFloors">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Petrol Pump</b></h3>
							</div>
							<?php if($property_type=='INDEPENDENT BUILDING'){?>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<b>Is property a Petrol Pump ? </b>
									</div>
									<div class="col-sm-6">
										<?=$is_petrol_pump=='t'?'Yes':'No';?>
									</div>
								</div>
								<?php if($is_petrol_pump=='t'){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Completion Date of Petrol Pump </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_petrol_pump_completion_date" value="<?=$petrol_pump_completion_date?>" class="form-control"/><?=$petrol_pump_completion_date?>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<b>Underground Storage Area (in Sq. Ft.) </b>
										</div>
										<div class="col-sm-6">
											<input type="hidden" id="assess_under_ground_area" value="<?=$under_ground_area?>" class="form-control"/> <?=$under_ground_area?>
										</div>
									</div>
								<?php }else{?>
									<input type="hidden" id="assess_petrol_pump_completion_date" />
									<input type="hidden" id="assess_under_ground_area"  />
								<?php }?>
								<?php }?>
								<?php if($user_type_mstr_id==7){?>
									<div class="row">
										<div class="col-sm-6 pad-btm">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6 pad-btm">
											<?=$vis_petrol_pump=='t'?'Yes':'No';?>
											<input type="hidden" name="petrolrv" id="petrolrv" value="<?=$vis_petrol_pump?>" class="form-control"/>
										</div>
									</div>
								<?php }?>
								<div class="row">
									<div class="col-sm-6 pad-btm">
										<b>Is property a Petrol Pump ?  </b>
									</div>
									<div class="col-sm-6 pad-btm">
										<?php if(isset($_POST["is_petrol_pump"])){$petrolval=$_POST["is_petrol_pump"];}else{$petrolval=$is_petrol_pump;}?>
										<select name="is_petrol_pump" id="is_petrol_pump" onChange="HideUnhide('is_petrol_pump','pt_details','petrol_pump_completion_date','under_ground_area')" class="form-control">
											<option value="">Select</option>
											<option value="1" <?php if($petrolval==1){?> selected="selected"<?php }?> >Yes</option>
											<option value="0" <?php if($petrolval==0){?> selected="selected"<?php }?>>No</option>
										</select>
									</div>
									<div class="col-sm-12 pad-btm">
										<div class="pt_details" style="display:none"><b>Completion Date of Petrol Pump </b>
											<input type="date" name="petrol_pump_completion_date" id="petrol_pump_completion_date" value="<?php if(isset($_POST["petrol_pump_completion_date"])){echo $_POST["petrol_pump_completion_date"];}else{ echo $petrol_pump_completion_date;}?>"  class="form-control"/>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12 pad-btm">
										<div class="pt_details" style="display:none">
											<b>Underground Storage Area (in Sq. Ft.) fdfdfd </b>
											<input type="tel" name="under_ground_area" id="under_ground_area"  value="<?=$assess_petrol_pump_area ?? $under_ground_area;?>" class="form-control" />
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="panel panel-bordered oldFloors water_harvesting_div">
							<div class="panel-heading" style="background:#1b8388f7;">
								<h3 class="panel-title"><b>Rainwater Harvesting Provision</b></h3>
							</div>

							<div class="panel-body ">
								<div class="row">
									<div class="col-sm-6">
										<b>Self-Assessed </b>
									</div>
									<div class="col-sm-6">
										<?=$is_water_harvesting=='t'?'Yes':'No';?>
									</div>
									<input type="hidden" name="hid_water_harvesting" id="hid_water_harvesting" value="<?php if($is_water_harvesting=='t'){echo '1';}else{echo '0';}?>" class="form-control"/>
								</div>

								<?php if($user_type_mstr_id==7){?>
									<div class="row">
										<div class="col-sm-6">
											<b>Assessed By Agency TC  </b>
										</div>
										<div class="col-sm-6">
											<?=$vis_water_harvesting=='t'?'Yes':'No';?>
											<input type="hidden" name="rainwater_harvestv" id="rainwater_harvestv" value="<?=$vis_water_harvesting?>" class="form-control"/>
										</div>
									</div>
								<?php }?>
								<div class="row">
									<div class="col-sm-6">
										<b>Check  </b>
									</div>
									<div class="col-sm-6">
										<?php
										$chkharbst=NULL;
										$dishrbst=NULL;
										$chkharbst2=NULL;
										if(isset($_POST["rdo_water_harvesting"]))
										{
											if($_POST["rdo_water_harvesting"]==1)
											{
												$chkharbst='checked="checked"';
												$dishrbst='disabled="disabled"';
											}
											if($_POST["rdo_water_harvesting"]==0)
											{
												$chkharbst2='checked="checked"';
											}
										}
										?>
										<input type="radio" name="rdo_water_harvesting" id="rdo_water_harvesting1"  value="1" <?=$chkharbst?> onClick="OperateDropDown('rdo_water_harvesting1', 'water_harvesting', 'hid_water_harvesting')" />&nbsp;&nbsp;Correct&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" name="rdo_water_harvesting" id="rdo_water_harvesting2"  value="0" <?=$chkharbst2?> onClick="OperateDropDown('rdo_water_harvesting2', 'water_harvesting', 'hid_water_harvesting')" />&nbsp;&nbsp;Incorrect
									</div>
									<div class="col-sm-12">
										<div class="pt_details" style="display:none"><b>Verification </b>
											<select name="water_harvesting" id="water_harvesting"   <?=$dishrbst?> class="form-control">
												<option value="">Select</option>
												<option value="1" <?php if(isset($_POST["water_harvesting"]) && $_POST["water_harvesting"]==1){?> selected="selected"<?php }?> >Yes</option>
												<option value="0" <?php if(isset($_POST["water_harvesting"]) && $_POST["water_harvesting"]==0){?> selected="selected"<?php }?>>No</option>
											</select>
										</div>
									</div>
								</div>

							</div>
						</div>


						<?php
						// vacant land
						if($prop_type_mstr_id!=4)
						{
							?>

							<!--Floor Added by Agency Tc-->
							<?php 
							if(isset($no_of_addedfloor) && $no_of_addedfloor>0)
							{
								?>
								<div class="panel panel-bordered oldFloors">
									<div class="panel-heading" style="background:#1b8388f7;">
										<h3 class="panel-title" style="background:#FF0000; color:#fff;"><b>Floor Added By Agency TC</b></h3>
									</div>
									<?php
									foreach ($vfloor_details_added as $rowusesver)
									{
										?>
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-6">
													<b>Floor No </b>
												</div>
												<div class="col-sm-6">
													<?=$rowusesver['floor_name']?>
												</div>
												<div class="col-sm-6">
													<b>Use Type </b>
												</div>
												<div class="col-sm-6">
													<?=$rowusesver['usage_type']?>
												</div>
												<div class="col-sm-6">
													<b>Occupancy Type </b>
												</div>
												<div class="col-sm-6">
													<?php echo $rowusesver['occupancy_name'];?>
												</div>
												<div class="col-sm-6">
													<b>Construction Type </b>
												</div>
												<div class="col-sm-6">
													<?=$rowusesver['construction_type']?>
												</div>
												<div class="col-sm-6">
													<b>Carpet Area </b>
												</div>
												<div class="col-sm-6">
													<?=$rowusesver['carpet_area']?>
												</div>
												<div class="col-sm-6">
													<b>Build Up Area </b>
												</div>
												<div class="col-sm-6">
													<?=$rowusesver['builtup_area']?>
												</div>
												<div class="col-sm-6">
													<b>Date From </b>
												</div>
												<div class="col-sm-6">
													<?=date('m-Y',strtotime($rowusesver['date_from']))?>
												</div>
											</div>
										</div>
										<?php 
									}
									?>
								</div>
								<?php 
							}

						
							?>
							
							<?php
						}
						?>




						<!-- Add Extera Floor -->
						<div class="panel panel-bordered panel-dark" id="ExteraFloor">
							<div class="panel-heading">
								<h3 class="panel-title"><b>Do You Want To Add Floor</b> &nbsp;&nbsp;
									<input type="checkbox" name="chkfloor" id="chkfloor" <?php if(isset($_POST["chkfloor"]) && $_POST["chkfloor"]=='on'){echo 'checked="checked"';}?> onClick="checkboxchk()">
								</h3>
							</div>
							<div id="showflrr" style="display:none;">
								<div class="panel-body">
									<div class="panel panel-bordered">
										<div class="panel-heading" style="background:#1b8388f7; margin-bottom: 10px;">
											<h3 class="panel-title"><b> Floor Details </b></h3>
										</div>
										<?php
										if(isset($newfloor) && !empty($newfloor))
										{
											$i=0;
											foreach($newfloor as $newEachFloor)
											{
												//print_var($newEachFloor);
											++$i;
											?>
											<div class="panel panel-bordered panel-dark container-fluid" id="floorPanel<?=$i;?>">
												<div class="panel-body">
												<div class="row">
													<div class="col-sm-12">
														Floor No asaas
													</div>
													<div class="col-sm-12">
														<select name="floor_id[]" id="floor_id<?=$i;?>" class="form-control">
															<option value="">Select</option>
															<?php
															foreach ($floor_list as  $valfloor)
															{
																?>
																<option value="<?=$valfloor["id"]?>" <?=($valfloor["id"]==$newEachFloor['floor_id'])?'selected':NULL;?>>
																<?=$valfloor["floor_name"]?></option>
																<?php 
															}
															?>
															</select>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														Use Type
													</div>
													<div class="col-sm-12">
														<select name="use_type_id[]" id="use_type_id<?=$i;?>" class="form-control">
															<option value="">Select</option>
															<?php
															foreach ($usage_list as  $valuse)
															{
																?>
																<option value="<?=$valuse["id"]?>" <?=($valuse["id"]==$newEachFloor['use_type_id'])?'selected':NULL;?>>
																<?=$valuse["usage_type"]?></option>
																<?php 
															}
															?>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														Occupancy Type
													</div>
													<div class="col-sm-12">
														<select name="occupancy_type_id[]" id="occupancy_type_id<?=$i;?>" class="form-control">
															<option value="">Select</option>
															<?php
															foreach ($occupancy_list as  $valoccu)
															{
																?>
																<option value="<?=$valoccu["id"]?>" <?=($valoccu["id"]==$newEachFloor['occupancy_type_id'])?'selected':NULL;?>><?=$valoccu["occupancy_name"]?></option>
																<?php 
															}
															?>
															</select>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														Construction Type
													</div>
													<div class="col-sm-12">
														<select name="construction_type_id[]" id="construction_type_id<?=$i;?>" class="form-control">
															<option value="">Select</option>
															<?php
															foreach ($const_type_list as  $valcons)
															{
																?>
																<option value="<?=$valcons["id"]?>" <?=($valcons["id"]==$newEachFloor['construction_type_id'])?'selected':NULL;?>><?=$valcons["construction_type"]?></option>
																<?php 
															}
															?>
														</select>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														Built Up Area  (in Sq. Ft)
													</div>
													<div class="col-sm-12">
														<input type="tel" name="builtup_area[]" id="builtup_area<?=$i;?>" value="<?=$newEachFloor['builtup_area'];?>" class="form-control" />
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														Date From
													</div>
													<div class="col-sm-12">
														<input type="month" name="occ_mm[]" id="occ_mm<?=$i;?>" value="<?=$newEachFloor['occ_mm'];?>" max="<?=date("Y-m");?>" class="form-control" />
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12">
														<span class="btn btn-info"><a href="javascript:AddOccupancy()" style="text-decoration:none; color:#FFFFFF">Add</a></span>
														<span class="btn btn-danger"><a href="javascript:RemoveOccupancy(<?=$i;?>)" style="text-decoration:none; color:#FFFFFF">Remove</a></span>
													</div>
												</div>
											</div>
											</div>
											<?php
											}
										}
										else
										{
											?>
											<div class="panel panel-bordered panel-dark container-fluid">
											<div class="panel-body">
											<div class="row">
												<div class="col-sm-12">
													Floor No 
												</div>
												<div class="col-sm-12">
													<select name="floor_id[]" id="floor_id1" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($floor_list as  $valfloor)
														{
															?>
															<option value="<?php echo $valfloor["id"];?>"><?php echo $valfloor["floor_name"];?></option>
															<?php 
														}
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Use Type
												</div>
												<div class="col-sm-12">
													<select  name="use_type_id[]" id="use_type_id1" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($usage_list as $valuse)
														{
															?>
															<option value="<?php echo $valuse["id"];?>" <?php if(1==$valuse["id"]){?> selected="selected"<?php }?>><?php echo $valuse["usage_type"];?></option>
															<?php 
														}
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Occupancy Type
												</div>
												<div class="col-sm-12">
													<select name="occupancy_type_id[]"  id="occupancy_type_id1" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($occupancy_list as $valoccu)
														{
															?>
															<option value="<?php echo $valoccu["id"];?>" <?php if(1==$valoccu["occupancy_name"]){?> selected="selected"<?php }?>><?php echo $valoccu["occupancy_name"];?></option>
															<?php 
														}
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Construction Type
												</div>
												<div class="col-sm-12">
													<select name="construction_type_id[]" id="construction_type_id1" class="form-control">
														<option value="">Select</option>
														<?php
														foreach ($const_type_list as  $valcons)
														{
															?>
															<option value="<?php echo $valcons["id"];?>" ><?php echo $valcons["construction_type"];?></option>
															<?php 
														}
														?>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Built Up Area  (in Sq. Ft)
												</div>
												<div class="col-sm-12">
													<input type="tel" name="builtup_area[]"  value="" id="builtup_area1" class="form-control">
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													Date From
												</div>
												<div class="col-sm-12">
													<input type="month" name="occ_mm[]" id="occ_mm1" max="<?=date("Y-m");?>" class="form-control" />
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12">
													<span class="btn btn-info"><a href="javascript:AddOccupancy()" style="text-decoration:none; color:#FFFFFF">Add</a></span>
												</div>
											</div>

											</div>
											</div>
											<?php 
										}
										?>
										<div id="extra_floor_div"></div>
									</div>
								</div>
							</div>
						</div>
						<!--   Add New Floor -->









				<input type="hidden" name="countarea" id="countarea" value="<?=$_POST["countarea"] ?? 1;?>" />

				<div class="panel">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h3 class="panel-title"><b>Remarks</b> &nbsp;&nbsp;</h3>
						</div>
					</div>
					<div class="panel-body">
						<div class="row">							
							<div class="col-sm-12">
								<textarea class="form-control" required name="remarks" id="remarks" onkeypress="return isAlphaNum(event);"><?=isset($_POST["remarks"])?$_POST["remarks"]:"";?></textarea>
							</div>
						</div>
					</div>
					<div class="panel-body text-center">
						<input type="submit" name="btn_submit" value="Proceed to survey" style="width: 140px;background:linear-gradient(255deg,rgba(0,0,0,1) 20%,rgba(52,255,0,1)70%)!important;" onClick="return ValidateRadio()" class="btn btn-success" />
					</div>
				</div>
			</form>
		</div>
	</div>


<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>
<script>
	
	$(document).ready(function ()
	{	
        $("#FORMNAME1").validate({ 
            rules: {
                ward_id: {   required:true, }, 
				property_type_id: {   required:true, }, 
				percentage_of_property: {   required:true,   number:true, }, 
				area_of_plot: {   required:true,   number:true, }, 
				street_type_id: {   required:true, }, 
				total_floors: {   required:true,   digits:true, }, 
				usetype_10302: {   required:true, }, 
				occu_10302: {   required:true, }, 
				cons_10302: {   required:true, }, 
				txt_builtarea_10302: {   required:true,   number:true, },
				has_mobile_tower: {   required:true, },
				tower_installation_date: {   required:true,   date_hyphen:true, },
				total_tower_area: {   required:true,   number:true, },
				is_petrol_pump: {   required:true, },
				petrol_pump_completion_date: {   required:true,   date_hyphen:true, },
				under_ground_area: {   required:true,   number:true, },
				// water_harvesting: {   required:true, },
				floor_id1: {   required:true, },
				use_type_id1: {   required:true, },
				occupancy_type_id1: {   required:true, },
				construction_type_id1: {   required:true, },
				builtup_area1: {   required:true, },
				occ_mm1: {   required:true, },
				occ_yyyy1: {   required:true, },
            },
            messages: 
			{

            },
        });
		remove_new_ward_correct();
		remove_ExteraFloor($('#property_type_id').val());
    });

    function checkboxchk()
    {
        var check=document.getElementById("chkfloor");
		// alert(check.checked);
        if(check.checked==true)
        {
            document.getElementById("showflrr").style.display="block";
			$("#floor_id1").attr('required', '');
			$("#use_type_id1").attr('required', '');
			$("#occupancy_type_id1").attr('required', '');
			$("#construction_type_id1").attr('required', '');
			$("#builtup_area1").attr('required', '');
			$("#occ_mm1").attr('required', '');
        }
        else
        {
            document.getElementById("showflrr").style.display="none";
			$("#floor_id1").removeAttr('required', '');
			$("#use_type_id1").removeAttr('required', '');
			$("#occupancy_type_id1").removeAttr('required', '');
			$("#construction_type_id1").removeAttr('required', '');
			$("#builtup_area1").removeAttr('required', '');
			$("#occ_mm1").removeAttr('required', '');
        }
    }

    
    function ValidateRadio()
    {
		var property_type_id  = $("#property_type_id").val();
		const list=[];
		var remarks  = $("#remarks").val();
		if(remarks.trim()=='')
		{
			list.push(" Enter remarks");
		}
        var rdo_ward_no=document.getElementsByName('rdo_ward_no');		
        if(rdo_ward_no[0].checked==false && rdo_ward_no[1].checked==false)
        {
            //alert("Please Verify Ward No");
			list.push(" Ward No");
            //return false;
        }

		var rdo_new_ward_no=document.getElementsByName('rdo_new_ward_no');
        if(rdo_new_ward_no[0].checked==false && rdo_new_ward_no[1].checked==false)
        {
            //alert("Please Verify Ward No");
			list.push(" New Ward No");
            //return false;
        }

        var rdo_property_type=document.getElementsByName('rdo_property_type'); 
        if(rdo_property_type[0].checked==false && rdo_property_type[1].checked==false)
        {
           // alert("Please Verify Property Type");
			list.push("Property Type");
            //return false;
        }

        var rdo_area_of_plot=document.getElementsByName('rdo_area_of_plot');
        if(rdo_area_of_plot[0].checked==false && rdo_area_of_plot[1].checked==false)
        {
            //alert("Please Verify Area of plot");
			list.push(" Verify Area of plot");
            //return false;
        }

        var rdo_street_type=document.getElementsByName('rdo_street_type');
        if(rdo_street_type[0].checked==false && rdo_street_type[1].checked==false)
        {
            //alert("Please Verify Road Type");
			list.push(" Verify Road Type");
            //return false;
        }

		var has_hording = $("#has_hording").val();
		if(has_hording==1)
		{
			var hording_installation_date = $("#hording_installation_date").val();
			if(hording_installation_date == null || hording_installation_date == "")
			{
				//alert("Please Enter Hording Installation Date");
				list.push('  Hording Installation Date\n');
				//return false;
			}

			var total_hording_area = $("#total_hording_area").val();
			if(total_hording_area == null || total_hording_area == "")
			{
				//alert("Please Enter Hording Area");
				list.push('  Enter Hording Area\n');
				//return false;
			}
		}

		var has_mobile_tower = $("#has_mobile_tower").val();
		if(has_mobile_tower==1)
		{
			var tower_installation_date = $("#tower_installation_date").val();
			if(tower_installation_date == null || tower_installation_date == "")
			{
				// alert("Please Enter Mobile Tower Installation Date");
				// return false;
				list.push('  Enter Mobile Tower Installation Date\n');
			}

			var total_tower_area = $("#total_tower_area").val();
			if(total_tower_area == null || total_tower_area == "")
			{
				// alert("Please Enter Mobile Tower Occupied Area");
				// return false;
				list.push('  Enter Mobile Tower Occupied Area\n');
			}
		}

		
        var rdo_water_harvesting=document.getElementsByName('rdo_water_harvesting');
        if(rdo_water_harvesting.length!=0 && rdo_water_harvesting[0].checked==false && rdo_water_harvesting[1].checked==false && property_type_id!=4)
        {
            // alert("Please Verify Water Harvesting");
            // return false;
			list.push('  Verify Water Harvesting \n');
        }

		

		var is_petrol_pump = $("#is_petrol_pump").val();
		if(is_petrol_pump==1)
		{
			var petrol_pump_completion_date = $("#petrol_pump_completion_date").val();
			if(petrol_pump_completion_date == null || petrol_pump_completion_date == "")
			{
				// alert("Please Enter Petrol Pump Completion Date");
				// return false;
				list.push('  Enter Petrol Pump Completion Date \n');
			}

			var under_ground_area = $("#under_ground_area").val();
			if(under_ground_area == null || under_ground_area == "")
			{
				// alert("Please Enter Petrol Pump Underground Area");
				// return false;
				list.push('  Enter Petrol Pump Underground Area \n');
			}
		}


        var per=document.getElementById("percentage_of_property");
        if(per!=null && per.value.length!=0)
        {
            if(!isNaN(per.value))
            {
                var val=parseFloat(per.value);
                if(val<0 || val>100)
                {
                    // alert("Invalid percentage of property transfered");
                    // return false;
					list.push('  Enter Petrol Pump Underground Area \n');
                }
            }
        }

		if(list.length>0)
		{	
			
			
			var sms = '  Please Select \n';
			list.forEach(function(element,index){
				sms +=(index+1)+'. '+element+'\n'; 
			})
			
			alert(sms);
			return false;
		}
		else
		{
			return true;
		}
		

    }

	function OperateDropDown(radio, control, hidden)
	{
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        if (rdo.value == "1") {
            var opt = ctrl.options;
            var pos = 0;
            for (var j = 0; j < opt.length; j++) {
                if (opt[j].value == hid_val) {
                    pos = j;
                    break;
                }
            }
            ctrl.selectedIndex = pos;
            ctrl.disabled = true;
        }
        else
		{
            ctrl.selectedIndex = 0;
            ctrl.disabled = false;
        } 
			
		if(control.trim()=='property_type_id')
		{
			remove_ExteraFloor(ctrl.value);
		}
    }


    function OperateTexBox(radio, control, hidden)
	{
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        if (rdo.value == "1")
		{
            ctrl.value = hid_val;
            ctrl.readOnly = true;
        }
        else
		{
            ctrl.value = "";
            ctrl.readOnly = false;
        }
    }

    function HideUnhide(test,target,targetdate,tragetarea)
    {
		//alert(targetdate);
        var ddl=document.getElementById(test);

        var val = ddl.options[ddl.selectedIndex].value;
        var tw_details=document.getElementsByClassName(target);
        // alert(val);
        if(val=="1")
        {
			//alert(tw_details.length);
            for(var i=0;i<tw_details.length;i++)
            {
				//alert(targetdate);
                tw_details[i].style.display="";
                var tdate = document.getElementById('assess_'+targetdate).value;

                var tarea =  document.getElementById('assess_'+tragetarea).value;
                //alert(tdate);

                document.getElementById(targetdate).value = tdate;
                document.getElementById(tragetarea).value = tarea;
            }
        }
        else
        {
            for(var i=0;i<tw_details.length;i++)
            {
                tw_details[i].style.display="none";
                /*document.getElementById(targetdate).value = "";
                document.getElementById(tragetarea).value = "";*/
            }
        }
    }


	function checkdate(d,cnt)
    {
        try{
            var msg="";
            var curryr=<?=date('Y')?>;
            var currmn=<?=date('m')?>;
            var mmyy=<?=date('Ym')?>;
            var mm=document.getElementById("occ_mm"+cnt).value;
            var yy=document.getElementById("occ_yyyy"+cnt).value;
            //var scmmdd="";
            var scmmdd = yy + "" + mm;


            if(d=='M')
            {
                //alert(isNaN(mm));
                if(isNaN(mm) == false){alert("Please Enter only Digit"); document.getElementById("occ_mm"+cnt).value="";   document.getElementById("occ_mm"+cnt).focus();}
                else if(mm<1 || mm>12){alert("Please Enter valid month"); document.getElementById("occ_mm"+cnt).value=""; document.getElementById("occ_mm"+cnt).focus();}
                else if(yy!="")
                {
                    if(yy>curryr){alert("Complition Year Must Be less or equal to current Year"); document.getElementById("occ_yyyy"+cnt).value="";}
                    else if(scmmdd>mmyy){alert("Date of Completion Must Be less or equal to current date"); document.getElementById("occ_yyyy"+cnt).value="";document.getElementById("occ_mm"+cnt).value="";}
                }
            }

            if(d=='Y')
            {
                if(isNaN(yy)){alert("Please Enter Valid Year"); document.getElementById("occ_yyyy"+cnt).value="";  document.getElementById("occ_yyyy"+cnt).focus();}
                else if(yy<1960){alert("Year Must be grater than 1960"); document.getElementById("occ_yyyy"+cnt).value=""; document.getElementById("occ_yyyy"+cnt).focus();}
                else if(yy>curryr){alert("Year Must be less or equal to current Year"); document.getElementById("occ_yyyy"+cnt).value="";  document.getElementById("occ_yyyy"+cnt).focus();}
                else if(mm!="")
                {
                    if(scmmdd>mmyy){alert("Date of Completion Must Be less or equal to current date"); document.getElementById("occ_yyyy"+cnt).value="";document.getElementById("occ_yyyy"+cnt).value="";}
                }

            }



        }    catch (err) { alert(err.message);}


    }

	
	var occ=<?=$_POST["countarea"] ?? 1;?>;
    function AddOccupancy()
	{
		++occ;
		var str='<div class="panel panel-bordered panel-dark container-fluid" id="floorPanel'+occ+'">\
					<div class="panel-body">\
					<div class="row">\
						<div class="col-sm-12">\
							Floor No \
						</div>\
						<div class="col-sm-12">\
							<select name="floor_id[]" id="floor_id'+occ+'" class="form-control" required>\
								<option value="">Select</option>\
								<?php
								foreach ($floor_list as  $valfloor)
								{
									?>
									<option value="<?=$valfloor["id"]?>"><?=$valfloor["floor_name"]?></option>\
									<?php 
								}
								?>
								</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Use Type\
						</div>\
						<div class="col-sm-12">\
							<select name="use_type_id[]" id="use_type_id'+occ+'" class="form-control" required>\
								<option value="">Select</option>\
								<?php
								foreach ($usage_list as  $valuse)
								{
									?>
									<option value="<?=$valuse["id"]?>"><?=$valuse["usage_type"]?></option>\
									<?php 
								}
								?>
							</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Occupancy Type\
						</div>\
						<div class="col-sm-12">\
							<select name="occupancy_type_id[]" id="occupancy_type_id'+occ+'" class="form-control" required>\
								<option value="">Select</option>\
								<?php
								foreach ($occupancy_list as  $valoccu)
								{
									?>
									<option value="<?=$valoccu["id"]?>"><?=$valoccu["occupancy_name"]?></option>\
									<?php 
								}
								?>
								</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Construction Type\
						</div>\
						<div class="col-sm-12">\
							<select name="construction_type_id[]" id="construction_type_id'+occ+'" class="form-control" required>\
								<option value="">Select</option>\
								<?php
								foreach ($const_type_list as  $valcons)
								{
									?>
									<option value="<?=$valcons["id"]?>"><?=$valcons["construction_type"]?></option>\
									<?php 
								}
								?>
							</select>\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Built Up Area  (in Sq. Ft)\
						</div>\
						<div class="col-sm-12">\
							<input type="tel" name="builtup_area[]" value="" id="builtup_area'+occ+'" class="form-control" required />\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							Date From\
						</div>\
						<div class="col-sm-12">\
							<input type="month" name="occ_mm[]" id="occ_mm'+occ+'" max="<?=date("Y-m");?>" class="form-control" required />\
						</div>\
					</div>\
					<div class="row">\
						<div class="col-sm-12">\
							<span class="btn btn-info"><a href="javascript:AddOccupancy()" style="text-decoration:none; color:#FFFFFF">Add</a></span>\
							<span class="btn btn-danger"><a href="javascript:RemoveOccupancy('+occ+')" style="text-decoration:none; color:#FFFFFF">Remove</a></span>\
						</div>\
					</div>\
				</div>\
				</div>';
		console.log(str);
		$("#extra_floor_div").append(str);
		$("#countarea").val(occ);
    }

    function RemoveOccupancy(elementId)
	{
		$("#floorPanel"+ elementId).remove();
    }

    function checkEveryRadioBtnSelectedOrNot()
    { 
		var property_type_id = $("#property_type_id").val();
        var dddd=0;
        var totalChercks=0;
        $('input:radio').each(function()
		{
            if($(this).is(':checked'))
			{
				totalChercks+=1;
			}
			else
			{
				totalChercks=totalChercks;
			} 
			dddd+=1;
		});
        var check=dddd/2;
		console.log(dddd);
		console.log(totalChercks);
		console.log($('input:radio'));
		// alert();
		// return false		
        if(check!=totalChercks && <?=$prop_type_mstr_id?>==property_type_id && <?=$prop_type_mstr_id?>!=4)
		{
			alert("Please Answer All Question");
			return false;
		}
    }

	HideUnhide('has_hording','hrd_details','hording_installation_date','total_hording_area')
    HideUnhide('has_mobile_tower','tw_details','tower_installation_date','total_tower_area')
    HideUnhide('is_petrol_pump','pt_details','petrol_pump_completion_date','under_ground_area')
    checkboxchk();
	function mapde_ward(old_ward_id_correct)
	{
		var old_ward_id = $("#ward_id").val();
		var hid_new_ward_id = $("#hid_new_ward_id").val();
		remove_new_ward_correct(hid_new_ward_id);
		append_new_ward(old_ward_id);
		
	}
	function remove_new_ward_correct()
	{	
		var values=[];
		var hnew_ward_id = $("#hid_new_ward_id").val();
		var new_ward_id = $("#new_ward_id").val();
		new_ward_id = new_ward_id=='' || new_ward_id==undefined ? hnew_ward_id : new_ward_id;
		$("#new_ward_id option").each(function(val)
		{
			// console.log($(this).val());
			$(this).val().trim()!='' ?values.push($(this).val()):'';
		});			
		if(!values.includes(new_ward_id))
		{
			$('#rdo_new_ward_no1').hide();
		}
		else
		{
			$('#rdo_new_ward_no1').show();
		}		
			
	}
	function append_new_ward(value)
	{	
		var old_ward_id_correct = $('#rdo_ward_no2').val();	
		var hid_new_ward_id = $("#hid_new_ward_id").val();;	
		if(old_ward_id_correct==0)
		{
			$('#new_ward_id').empty();
		}
        var old_ward_mstr_id = $("#ward_id").val();
        if(old_ward_mstr_id!="" && old_ward_id_correct==0)
		{
            try{
                $.ajax({
                    type:"POST",
                    url: "<?=base_url('CitizenSaf/getNewWardDtlByOldWard');?>",
                    dataType: "json",
                    data: {
                        "old_ward_mstr_id":old_ward_mstr_id,
                    },
                    beforeSend: function() {
                        $("#loadingDiv").show();
                    },
                    success:function(data){
                        if(data.response==true){
							if(old_ward_id_correct==0)
							{
								$('#new_ward_id').empty();
							}
							var dd =data.data.split("option>");							
							if(dd.length==2)
							{
								$("#new_ward_id").html("<option value=''>==SELECT==</option>");
								$("#new_ward_id").append(data.data);
							}
							else
							{
								$("#new_ward_id").html(data.data);
							}
							remove_new_ward_correct();
                        }
                        $("#loadingDiv").hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#loadingDiv").hide();
                    }
                });
            }catch (err) {
                alert(err.message);
            }
        }
	}
	//mapde_ward();
	function remove_ExteraFloor(prop_type_id)
	{

		if(prop_type_id!=4)
		{
			$('#ExteraFloor').show();
			$("#oldFloors").show();
			$(".oldFloors").find("select, textarea").attr('required',true);			
			$(".oldFloors").find("input:radio").attr('disabled',false);
			$(".oldFloors").attr('style',"display:inlineblock");
			$(".oldFloors").attr('required',false);
			$("#water_harvesting").attr('required',false);
		}
		else
		{
			var dd = $(".oldFloors").find("select, textarea").attr('required',false);
			$(".oldFloors").attr('required',false);
			$(".oldFloors").find("input:radio").attr('disabled',true);
			$(".oldFloors").attr('style',"display:none;");
			$("#chkfloor").prop('checked', false);
			$('#ExteraFloor').hide();
			checkboxchk();
			
		}
		if(prop_type_id!=4 && <?=$prop_type_mstr_id?>==4)
		{
			$("#chkfloor").prop('checked', true);
			checkboxchk();
		}
		if(prop_type_id==4 && <?=$prop_type_mstr_id?>==4)
		{			
			$('#rdo_water_harvesting1').attr('disabled',true);
			$('#rdo_water_harvesting2').attr('disabled',true);
			$(".oldFloors").find("input:radio").attr('disabled',true);
		}
		
		if(prop_type_id!=4 && <?=$prop_type_mstr_id?>==4)
		{ 
			$(".pt_details").attr('style',"display:inlineblock");
			
		}
	}
	function property_required()
	{
		var property_type_id  = $("#property_type_id").val();
		if(property_type_id!=4)
			return true;
		else
			return false;
	}
</script>
