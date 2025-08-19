<?= $this->include('layout_home/header');?>
<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container" style="padding-top: 10px;">
    <div class="panel panel-bordered panel-primary" style="margin: 0px 40px 5px 40px;">
		<div class="panel-heading">
			<h1 class="panel-title text-center"><?= strtoupper($session->get('ulb_dtl')["ulb_name"]);?></h1>
		</div>
	</div>
    <div id="page-content">
        <?php if(!empty($errMsg)){ ?>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-body">
                <div class="row" >
                    <div class="col-md-12 text-danger">
                    <?php
                    $i = 0;
                    if (!is_array($errMsg)) {
                        print_r($errMsg);
                    } else {
                        foreach ($errMsg as $key => $err) {
                            $i++;
                            echo $i." - ".$err;
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>

        

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Owner Details</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-sm">
                        <thead>
                            <tr>
                                <th>Owner Image</th>
                                <th>Owner Document</th>
                                <th>Upload</th>
                                <th>Name</th>
                                <th>Relation</th>
                                <th>Guardian Name</th>
                                <th>Mobile No.</th>
                                <th>Aadhar No.</th>
                                <th>PAN No.</th>
                                <th>Email ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($owner_detail)) {
                                foreach ($owner_detail as $key => $value) {
                            ?>
                                <tr>
                                    <td>
                                        <?php
                                        if (empty($value['applicant_img_dtl'])) {
                                        ?>
                                            <span class="text-danger">N/A</span>
                                        <?php 
                                        } else {
                                        ?>
											<a href="<?=base_url();?>/writable/uploads/<?=$value['applicant_img_dtl']['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$value['applicant_img_dtl']['doc_path'];?>" style="width: 60px; height: 60px;"></a>
                                            
                                        <?php 
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (empty($value['applicant_doc_dtl'])) {
                                        ?>
                                            <span class="text-danger">N/A</span>
                                        <?php 
                                        } else {
                                        ?>
											<a href="<?=base_url();?>/writable/uploads/<?=$value['applicant_doc_dtl']['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
                                            
                                        <?php 
                                        }
                                        ?>
                                    </td>
                                    <td>
										<?php
                                        if (empty($value['applicant_img_dtl']) || $value['applicant_doc_dtl']['status']==2 || empty($value['applicant_doc_dtl'])) {
                                        ?>
                                            <button type="button" class="btn btn-sm btn-info" id="det_click<?=$key;?>" onclick="owner_details(<?=$key;?>);">Click here to upload</button>
                                            <input type="hidden" id="saf_owner_detail_id<?=$key;?>" value="<?=$value['id'];?>" />
                                        <?php 
                                        } else {
                                        ?>
                                            <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                        <?php 
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?=$value['owner_name'];?>
                                        <input type="hidden" id="owner_name<?=$key;?>" value="<?=$value['owner_name'];?>" />
                                    </td>
                                    <td>
                                        <?=($value['relation_type']!="")?$value['relation_type']:"N/A";?>
                                        <input type="hidden" id="relation_type<?=$key;?>" value="<?=$value['relation_type'];?>" />
                                    </td>                    
                                    <td>
                                        <?=($value['guardian_name']!="")?$value['guardian_name']:"N/A";?>
                                        <input type="hidden" id="guardian_name<?=$key;?>" value="<?=$value['guardian_name'];?>" />
                                    </td>
                                    <td>
                                        <?=($value['mobile_no']!="")?$value['mobile_no']:"N/A";?>
                                        <input type="hidden" id="mobile_no<?=$key;?>" value="<?=$value['mobile_no'];?>" />        
                                    </td>
                                    <td>
                                        <?=($value['aadhar_no']!="")?$value['aadhar_no']:"N/A";?>
                                        <input type="hidden" id="aadhar_no<?=$key;?>" value="<?=$value['aadhar_no'];?>" />  
                                    </td>
                                    <td>
                                        <?=($value['pan_no']!="")?$value['pan_no']:"N/A";?>
                                        <input type="hidden" id="pan_no<?=$key;?>" value="<?=$value['pan_no'];?>" />
                                    </td>
                                    <td>
                                        <?=($value['email']!="")?$value['email']:"N/A";?>
                                        <input type="hidden" id="email<?=$key;?>" value="<?=$value['email'];?>" />
                                    </td>
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
        <!------- End Panel Owner Details-------->
        <!------- Panel SAF Form Details-------->
       <!-- <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">SAF Form Details</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-bordered text-sm">
                        <thead>
                            <tr>
                                <th>Uploaded Document</th>
                                <th>Upload Document</th>
                                <th>Upload File</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                <?php
                                if (empty($saf_form)) {
                                ?>
                                    <span class="text-danger">N/A</span>
                                <?php
                                } else {
                                ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$saf_form['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
									
                                <?php
                                }
                                ?>
                                </td>
                                <td>
                                <?php
                                
                                if (empty($saf_form) || $saf_form['status']==2) {
                                ?>
                                    <button data-target="#saf_form_modal" data-toggle="modal" class="btn btn-info btn-sm">Click here to upload</button>
                                <?php } else { ?>
                                    <span class="text-success"><b>Uploaded Successfully!!</b></span>
                                <?php 
                                } 
                                ?>
                                </td>
                                <td> SAF Form </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> -->
        <!------- End Panel SAF Form Details-------->
        <!------- Panel Property Document Details-------->
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Property Document Details</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-bordered text-sm">
                        <thead>
                            <tr>
                                <th>Uploaded Document</th>
                                <th>Upload Document</th>
                                <th>Upload File</th>
                            </tr>
                        </thead>
                        <tbody>

							<?php if($prop_type_mstr_id==1) { ?>
							<tr>
								<td>
								<?php if (!isset($super_structure_doc_dtl)) { ?>
									<span class="text-danger">N/A</span>
								<?php } else { ?>
									<a href="<?=base_url();?>/writable/uploads/<?=$super_structure_doc_dtl['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
									
								<?php } ?>
								</td>
								<td>
								<?php
								if (!isset($super_structure_doc_dtl) || $super_structure_doc_dtl['status']==2) {
								?>
									<button data-target="#super_structure_modal" data-toggle="modal" class="btn btn-info btn-sm">Click here to upload</button>
								<?php } else { ?>
									<span class="text-success"><b>Uploaded Successfully!!</b></span>
								<?php } ?>
								</td>
								<td>
								<?php
								foreach ($super_structure_doc_list as $key => $value) {
									if($key==0)
										echo $value["doc_name"];
									else
										echo ", ".$value["doc_name"];
								}
								?>
								</td>
							</tr>
							<?php } elseif($prop_type_mstr_id==3) { ?>
								<tr>
									<td>
									<?php if (!isset($flat_doc_dtl)) { ?>
										<span class="text-danger">N/A</span>
									<?php } else { ?>
										<a href="<?=base_url();?>/writable/uploads/<?=$flat_doc_dtl['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
										
									<?php } ?>
									</td>
									<td>
									<?php if (!isset($flat_doc_dtl) || $flat_doc_dtl['status']==2) { ?>
										<button data-target="#flat_modal" data-toggle="modal" class="btn btn-info btn-sm">Click here to upload</button>
									<?php } else { ?>
										<span class="text-success"><b>Uploaded Successfully!!</b></span>
									<?php } ?>
									</td>
									<td>
									<?php
									foreach ($flat_doc_list as $key => $value) {
										if($key==0)
											echo $value["doc_name"];
										else
											echo ", ".$value["doc_name"];
									}
									?>
									</td>
								</tr>
							<?php } else { ?>
								<tr>
									<td>
									<?php if (!isset($transfer_mode_doc_dtl)) { ?>
										<span class="text-danger">N/A</span>
									<?php } else { ?>
										<a href="<?=base_url();?>/writable/uploads/<?=$transfer_mode_doc_dtl['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
										
									<?php } ?>
									</td>
									<td>
									<?php
									if (!isset($transfer_mode_doc_dtl) || $transfer_mode_doc_dtl['status']==2) {
									?>
										<button data-target="#transfer_mode_modal" data-toggle="modal" class="btn btn-info btn-sm">Click here to upload</button>
									<?php } else { ?>
										<span class="text-success"><b>Uploaded Successfully!!</b></span>
									<?php } ?>
									</td>
									<td>
									<?php
									foreach ($transfer_mode_doc_list as $key => $value) {
										if($key==0)
											echo $value["doc_name"];
										else
											echo ", ".$value["doc_name"];
									}
									?>
									</td>
								</tr>
								<tr>
									<td>
									<?php if (!isset($property_type_doc_dtl)) { ?>
										<span class="text-danger">N/A</span>
									<?php } else { ?>
										<a href="<?=base_url();?>/writable/uploads/<?=$property_type_doc_dtl['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
										
									<?php } ?>
									</td>
									<td>
									<?php
									if (!isset($property_type_doc_dtl) || $property_type_doc_dtl['status']==2) {
									?>
										<button data-target="#property_type_modal" data-toggle="modal" class="btn btn-info btn-sm">Click here to upload</button>
									<?php } else { ?>
										<span class="text-success"><b>Uploaded Successfully!!</b></span>
									<?php } ?>
									</td>
									<td>
									<?php
									foreach ($property_type_doc_list as $key => $value) {
										if($key==0)
											echo $value["doc_name"];
										else
											echo ", ".$value["doc_name"];
									}
									?>
									</td>
								</tr>
								<?php if($no_electric_connection=='t') { ?>
									<tr>
										<td>
										<?php if (!isset($no_electric_connection_doc_dtl)) { ?>
											<span class="text-danger">N/A</span>
										<?php } else { ?>
											<a href="<?=base_url();?>/writable/uploads/<?=$no_electric_connection_doc_dtl['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 60px; height: 60px;"></a>
											
										<?php } ?>
										</td>
										<td>
										<?php
										if (!isset($no_electric_connection_doc_dtl) || $no_electric_connection_doc_dtl['status']==2) {
										?>
											<button data-target="#no_electric_connection_modal" data-toggle="modal" class="btn btn-info btn-sm">Click here to upload</button>
										<?php } else { ?>
											<span class="text-success"><b>Uploaded Successfully!!</b></span>
										<?php } ?>
										</td>
										<td>
										<?php
										foreach ($no_electric_connection_doc_list as $key => $value) {
											if($key==0)
												echo $value["doc_name"];
											else
												echo ", ".$value["doc_name"];
										}
										?>
										</td>
									</tr>
								<?php } ?>
							<?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
		<?php if($message){ ?>
		<div class="panel panel-bordered panel-dark">
            <div class="panel-body">
				<div class="col-sm-12">
					<h4 class="text-danger text-center"><?php print_r($message); ?></h4>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php if($show_rmc_btn==true){ ?>
		
		<div class="panel panel-bordered panel-dark">
            <div class="panel-body">
				<div class="col-sm-2 col-sm-offset-5">
					<a href="<?php echo base_url('CitizenSaf/payTax/'.$saf_dtl_id);?>" type="button" class="btn btn-info btn-labeled"> Pay Your Tax</a>
				<div>
			</div>
		</div>
		<?php } ?>
        <!------- End Panel Property Document Details-------->
    </div>  
    <!--End page content-->      
</div>
<!--END CONTENT CONTAINER-->


<!-- Owner Doc Upload Modal -->
<div class="modal fade" id="owner_details_modal" role="dialog"">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Owner Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <input type="hidden" name="saf_owner_dtl_id" id="saf_owner_dtl_id" value="">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" >
                            <tr>
                                <td><b>Name</b></td>
                                <td>:</td>
                                <td id="owner_name_show"></td>
                                <td><b>Relation</b></td>
                                <td>:</td>
                                <td id="relation_type_show"></td>
                                <td><b>Guardian Name</b></td>
                                <td>:</td>
                                <td id="guardian_name_show"></td>
                            </tr>
                            <tr>
                                <td><b>Mobile</b></td>
                                <td>:</td>
                                <td id="mobile_no_show"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Aadhar No.</b></td>
                                <td>:</td>
                                <td id="aadhar_no_show"></td>
                            </tr>
                            <tr>
                                <td><b>Pan No.</b></td>
                                <td>:</td>
                                <td id="pan_no_show"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><b>Email Id</b></td>
                                <td>:</td>
                                <td id="email_show"></td>
                            </tr>
                            <tr>
                                <td>Applicant Image</td>
                                <td>:</td>
                                <td colspan="3"><img/></td>
                                <td colspan="4">
                                    <input type="file" name="applicant_image_file" id="applicant_image_file" class="form-control" accept=".png,.jpg,.jpeg"/>
                                </td>
                            </tr>
                            <tr>
                                <td>Document Type</td>
                                <td>:</td>
                                <td colspan="3">
                                    <select id="owner_doc_mstr_id" name="owner_doc_mstr_id" class="form-control">
                                        <option value="">Select</option>
                                        <?php
                                            if(isset($owner_doc_list)){
                                            foreach ($owner_doc_list as $values){
                                        ?>
                                        <option value="<?=$values['id']?>" ><?=$values['doc_name']?>
                                        </option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td colspan="4">
                                    <input type="file" name="applicant_doc_file" id="applicant_doc_file" class="form-control" accept=".pdf" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="9" class="text-right">
                                    <input type="submit" name="btn_owner_doc_upload" id="btn_owner_doc_upload" class="btn btn-success" value="UPLOAD" />
                                </td>
                            </tr>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Owner Doc Upload Modal -->
<!-- SAF Form Upload Modal -->
<div class="modal fade" id="saf_form_modal" role="dialog"">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">SAF Form Upload</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" >
                            <tr>
                                <td>Document Type</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <input type="hidden" id="upld_doc_mstr_id" name="upld_doc_mstr_id" value="0" />
                                    <span class="text-bold">SAF Form</span>
                                </td>
                                <td>
                                    <input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right"><input type="submit" name="btn_upload" value="UPLOAD" id="btn_upload" class="btn btn-success" /></td>
                            </tr>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End SAF Form Upload Modal -->
<?php if($prop_type_mstr_id==1) { ?>
<!-- Property Super Structure Upload Modal -->
<div class="modal fade" id="super_structure_modal" role="dialog"">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" >
                            <tr>
                                <td>Electricity Bill</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <input type="hidden" id="upld_doc_mstr_id" name="upld_doc_mstr_id" value="<?=(isset($super_structure_doc_list))?$super_structure_doc_list[0]['id']:"";?>">
                                    <span class="text-bold"><?=(isset($super_structure_doc_list))?$super_structure_doc_list[0]['doc_name']:"";?></span>
                                </td>
                                <td>
                                    <input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">
                                    <input type="submit" name="btn_upload" id="btn_upload" class="btn btn-success" value="UPLOAD" />
                                </td>
                            </tr>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Super Structure Upload Modal -->
<?php } elseif($prop_type_mstr_id==3) { ?>
<!-- Property Flat Upload Modal -->
<div class="modal fade" id="flat_modal" role="dialog"">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" >
                            <tr>
                                <td>Property Type</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <select id="upld_doc_mstr_id" name="upld_doc_mstr_id" class="form-control">
                                        <option value="">== SELECT ==</option>
                                        <?php 
                                        if(isset($flat_doc_list)) {
                                            foreach ($flat_doc_list as $key => $value) { 
                                        ?>
                                            <option value="<?=$value['id']?>"><?=$value['doc_name']?></option>
                                        <?php 
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right">
                                    <input type="submit" name="btn_upload" id="btn_upload" class="btn btn-success" value="UPLOAD" />
                                </td>
                            </tr>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Property Flat Upload Modal -->
<?php } else { ?>
<!-- Property Transfer Mode Upload Modal -->
<div class="modal fade" id="transfer_mode_modal" role="dialog"">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" >
                            <tr>
                                <td>Property Type</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <select id="upld_doc_mstr_id" name="upld_doc_mstr_id" class="form-control">
                                        <option value="">== SELECT ==</option>
                                        <?php 
                                        if(isset($transfer_mode_doc_list)) {
                                            foreach ($transfer_mode_doc_list as $key => $value) { 
                                        ?>
                                            <option value="<?=$value['id']?>"><?=$value['doc_name']?></option>
                                        <?php 
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right"><input type="submit" name="btn_upload" id="btn_upload" class="btn btn-success" value="UPLOAD" /></td>
                            </tr>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Property Transfer Mode Upload Modal -->
<!-- Property Transfer Mode Upload Modal -->
<div class="modal fade" id="property_type_modal" role="dialog"">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" >
                            <tr>
                                <td>Property Type</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <select id="upld_doc_mstr_id" name="upld_doc_mstr_id" class="form-control">
                                        <option value="">== SELECT ==</option>
                                        <?php 
                                        if(isset($property_type_doc_list)) {
                                            foreach ($property_type_doc_list as $key => $value) { 
                                        ?>
                                            <option value="<?=$value['id']?>"><?=$value['doc_name']?></option>
                                        <?php 
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right"><input type="submit" name="btn_upload" id="btn_upload" class="btn btn-success" value="UPLOAD" /></td>
                            </tr>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Property Transfer Mode Upload Modal -->
<?php if($no_electric_connection=='t') { ?>
<!-- Property No Electric Connection Upload Modal -->
<div class="modal fade" id="no_electric_connection_modal" role="dialog"">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Property Document</h4>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data" action="">
                    <div class="table-responsive">
                        <table class="table table-bordered text-sm" >
                            <tr>
                                <td>Property Type</td>
                                <td style="width: 10px;">:</td>
                                <td>
                                    <input type="hidden" id="upld_doc_mstr_id" name="upld_doc_mstr_id" value="<?=(isset($no_electric_connection_doc_list))?$no_electric_connection_doc_list[0]['id']:"";?>">
                                    <span class="text-bold"><?=(isset($no_electric_connection_doc_list))?$no_electric_connection_doc_list[0]['doc_name']:"";?></span>
                                </td>
                                <td>
                                    <input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-right"><input type="submit" name="btn_upload" id="btn_upload" class="btn btn-success" value="UPLOAD" /></td>
                            </tr>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Property No Electric Connection Upload Modal -->
<?php } ?>
<?php } ?>

<?= $this->include('layout_home/footer');?>
<script>
function owner_details(il)
{
    var saf_owner_detail_id = $('#saf_owner_detail_id'+il).val();
    var owner_name = $('#owner_name'+il).val();
    if(owner_name=="") { owner_name = "N/A"; }
    var guardian_name = $('#guardian_name'+il).val();
    if(guardian_name=="") { guardian_name = "N/A"; }
    var relation_type = $('#relation_type'+il).val();
    if(relation_type=="") { relation_type = "N/A"; }
    var mobile_no = $('#mobile_no'+il).val();
    if(mobile_no=="") { mobile_no = "N/A"; }
    var aadhar_no = $('#aadhar_no'+il).val();
    if(aadhar_no=="") { aadhar_no = "N/A"; }
    var pan_no = $('#pan_no'+il).val();
    if(pan_no=="") { pan_no = "N/A"; }
    var email = $('#email'+il).val();
    if(email=="") { email = "N/A"; }
    $('#saf_owner_dtl_id').val(saf_owner_detail_id);
    $('#owner_name_show').html(owner_name);
    $('#guardian_name_show').html(guardian_name);
    $('#relation_type_show').html(relation_type);
    $('#mobile_no_show').html(mobile_no);
    $('#aadhar_no_show').html(aadhar_no);
    $('#pan_no_show').html(pan_no);
    $('#email_show').html(email);
    $("#owner_details_modal").modal('show');
}
</script>
