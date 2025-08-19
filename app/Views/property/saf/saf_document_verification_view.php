<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">SAF</a></li>
					<li class="active">Back To Citizen List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Document Verification View <a href="<?php echo base_url('documentverification/saf_back_to_citizen_list') ?>" class="btn btn-sm btn-danger" style="float:right;margin-top:.5%;"> Back </a></h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <label class="col-sm-2">Assessment Type:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($form['assessment_type']))?$form['assessment_type']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Holding No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($form['holding_no']))?$form['holding_no']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">SAF No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($form['saf_no']))?$form['saf_no']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Ward No:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($form['ward_no']))?$form['ward_no']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Remarks:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($form['remarks']))?$form['remarks']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                     <!-------Owner Details-------->
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Owner Details</h3>
                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th>Name</th>
                                                <th>Relation</th>
                                                <th>Guardian Name</th>
                                                <th>Mobile No.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(isset($owner_list)):
                                                 if(empty($owner_list)):
                                            ?>
                                                    <tr>
                                                        <td style="text-align:center;"> Data Not Available...</td>
                                                    </tr>
                                                <?php else: ?>
                                                <?php foreach($owner_list as $value): ?>
                                                    <tr>
															  <td><?php echo $value['owner_name']; ?></td>
															  <td><?php echo $value['relation_type']; ?></td>
															  <td><?php echo $value['guardian_name']; ?></td>
															  <td><?php echo $value['mobile_no']; ?></td>
															</tr>
                                                    <tr class="success">
                                                                <th>Document Name</th>
                                                                <th>Applicant Image</th>
                                                                <th>Status</th>
                                                                <th>Remarks</th>

                                                            </tr>
                                                            <?php
                                            foreach($value['saf_owner_img_list'] as $imgval):
                                                    ?>
                                                            <tr>
                                                                <td>Applicant Image</td>
                                                                <td><a href="<?=base_url();?>/writable/uploads/<?=$imgval["doc_path"];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$imgval["doc_path"];?>" style="width: 40px; height: 40px;"></a></td>
                                                                <td><?php
                                                                    if($imgval['verify_status']=="1")
                                                                    {
                                                                        echo "<span class='text-danger'>Verified</span>";
                                                                    }
                                                                    else if($imgval['verify_status']=="2")
                                                                    {
                                                                        echo "<span class='text-danger'>Rejected</span>";
                                                                    }
                                                                    else if($imgval['verify_status']=="0")
                                                                    {
                                                                        echo "<span class='text-danger'>New</span>";
                                                                    }
                                                                    ?></td>
                                                                <td><?=$imgval['remarks'];?></td>

                                                            </tr>
                                             <?php endforeach; ?>
                                                            <tr class="success">
                                                                <th>Document Name</th>
                                                                <th>Applicant Document</th>
                                                                <th></th>
                                                                <th></th>

                                                            </tr>
                                                             <?php
                                                     foreach($value['saf_owner_doc_list'] as $docval):
                                                    ?>
                                                            <tr>
                                                                <td><?=$docval['doc_name'];?></td>
                                                                <?php
                                                                $exp_ow_doc=explode('.',$docval['doc_path']);
                                                                $exp_ow_doc_ext=$exp_ow_doc[1];
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    if($exp_ow_doc_ext=='pdf')
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$docval['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$docval['doc_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$docval['doc_path'];?>" style="width: 40px; height: 40px;"></img>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php
                                                                    if($docval['verify_status']=="1")
                                                                    {
                                                                        echo "<span class='text-danger'>Verified</span>";
                                                                    }
                                                                    else if($docval['verify_status']=="2")
                                                                    {
                                                                        echo "<span class='text-danger'>Rejected</span>";
                                                                    }
                                                                    else if($docval['verify_status']=="0")
                                                                    {
                                                                        echo "<span class='text-danger'>New</span>";
                                                                    }
                                                                    ?></td>
                                                                <td><?=$docval['remarks'];?></td>

                                                            </tr>
                                             <?php endforeach; ?>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                        </tbody>
					                </table>
                                </div>
                             </div>
                         </div>
                     

                          <!--------prop doc------------>
                            <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Property Document</h3>

                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th>Document Name</th>
                                                <th>Document</th>
                                                <th>Status</th>
                                                <th>Remarks</th>

                                            </tr>
                                        </thead>
                                        <tbody>
										
											<?php
											if($form['prop_type_mstr_id']==1)
											{
											foreach($super_mode_document as $trval):
											?>
											<tr>
                                                <td><?=$trval['doc_name'];?></td>
												<td>
													<a href="<?=base_url();?>/writable/uploads/<?=$trval['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
												</td>
                                                <td>
												<?php
													if($trval['verify_status']=="1")
													{
														echo "<span class='text-danger'>Verified</span>";
													}
													else if($trval['verify_status']=="2")
													{
														echo "<span class='text-danger'>Rejected</span>";
													}
													else if($trval['verify_status']=="0")
													{
														echo "<span class='text-danger'>New</span>";
													}
													?>
												</td>
												<td><?=$trval['remarks'];?></td>
                                            </tr>
                                            <?php endforeach;
											}
											?>
											
											<?php
											if($form['prop_type_mstr_id']==3)
											{
											foreach($flat_mode_document as $trval):
											?>
											<tr>
                                                <td><?=$trval['doc_name'];?></td>
												<td>
													<a href="<?=base_url();?>/writable/uploads/<?=$trval['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
												</td>
                                                <td>
												<?php
													if($trval['verify_status']=="1")
													{
														echo "<span class='text-danger'>Verified</span>";
													}
													else if($trval['verify_status']=="2")
													{
														echo "<span class='text-danger'>Rejected</span>";
													}
													else if($trval['verify_status']=="0")
													{
														echo "<span class='text-danger'>New</span>";
													}
													?>
												</td>
												<td><?=$trval['remarks'];?></td>
                                            </tr>
                                            <?php endforeach;
											}
											?>
											
											<?php
											if ($form['no_electric_connection']=="t") {
											foreach($no_electric_connection_mode_document as $trval):
											?>
											<tr>
                                                <td><?=$trval['doc_name'];?></td>
												<td>
													<a href="<?=base_url();?>/writable/uploads/<?=$trval['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
												</td>
                                                <td>
												<?php
													if($trval['verify_status']=="1")
													{
														echo "<span class='text-danger'>Verified</span>";
													}
													else if($trval['verify_status']=="2")
													{
														echo "<span class='text-danger'>Rejected</span>";
													}
													else if($trval['verify_status']=="0")
													{
														echo "<span class='text-danger'>New</span>";
													}
													?>
												</td>
												<td><?=$trval['remarks'];?></td>
                                            </tr>
                                            <?php endforeach;
											}
											?>
					
                                            <?php
												foreach($prop_tr_mode_document as $trval):
                                            ?>
                                            <tr>
                                                <td><?=$trval['doc_name'];?></td>
												<td>
													<a href="<?=base_url();?>/writable/uploads/<?=$trval['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
												</td>
                                                <td>
												<?php
													if($trval['verify_status']=="1")
													{
														echo "<span class='text-danger'>Verified</span>";
													}
													else if($trval['verify_status']=="2")
													{
														echo "<span class='text-danger'>Rejected</span>";
													}
													else if($trval['verify_status']=="0")
													{
														echo "<span class='text-danger'>New</span>";
													}
													?>
												</td>
												<td><?=$trval['remarks'];?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php
                                                foreach($prop_pr_mode_document as $prval):
                                            ?>
                                            <tr>
                                                <td>
													<?=$prval['doc_name'];?></td>
                                                <td>
													<a href="<?=base_url();?>/writable/uploads/<?=$prval['doc_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
												</td>
                                                <td>
													<?php
														if($prval['verify_status']=="1")
														{
															echo "<span class='text-danger'>Verified</span>";
														}
														else if($prval['verify_status']=="2")
														{
															echo "<span class='text-danger'>Rejected</span>";
														}
														else if($prval['verify_status']=="0")
														{
															echo "<span class='text-danger'>New</span>";
														}
													?>
												</td>
                                                <td><?=$prval['remarks'];?></td>
                                            </tr>
                                            <?php endforeach; ?>
 					                    </tbody>
					                </table>
                                </div>
                </div>
            </div>
                            <!------------>


                </div>
            </div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
///////modal start
<!-- Creates the bootstrap modal where the image will appear -->
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Image preview</h4>
      </div>
      <div class="modal-body">
        <img src="" id="imagepreview" style="width: 400px; height: 264px;" >
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
//////modal end
<?= $this->include('layout_vertical/footer');?>
<script>
$(function() {
		$('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
			$('#imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});		

});
</script>
