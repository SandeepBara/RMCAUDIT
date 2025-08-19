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
					<li class="active">SAF Forward and Backward List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">SAF Forward and Backward View <a href="<?php echo base_url('documentverification/forward_list') ?>" class="btn btn-sm btn-danger" style="float:right;margin-top:.5%;"> Back </a></h3>

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
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$docval['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$docval['doc_path'];?>" style="width: 40px; height: 40px;"></a>
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

                    <?php
                    if($form['prop_type_mstr_id']!='4')
                    {
                    ?>
                    <div class="panel-group">
                            <div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
                                                <h3 class="panel-title"><a data-toggle="collapse" href="#collapse3">Occupancy Details</a></h3>
											</div>
                                            <div id="collapse3" class="panel-collapse collapse">
                                             <div class="panel-body">
											<div class="table-responsive">
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="thead-light" style="background-color: blanchedalmond;">
														  <th scope="col">Sl No.</th>
														  <th scope="col">Floor</th>
														  <th scope="col">Use Type</th>
														  <th scope="col">Occupancy Type</th>
														  <th scope="col">Construction Type</th>
														  <th scope="col">Total Area (in Sq. Ft.)</th>
														  <th scope="col">Total Taxable Area (in Sq. Ft.)</th>
													</thead>
													<tbody>
														<?php if($occupancy_detail):
														$i=1;
														?>
														<?php foreach($occupancy_detail as $occupancy_detail): ?>
														<tr>
															<td><?php echo $i++; ?></td>
															<td><?php echo $occupancy_detail['floor_name']; ?></td>
															<td><?php echo $occupancy_detail['usage_type']; ?></td>
															<td><?php echo $occupancy_detail['occupancy_name']; ?></td>
															<td><?php echo $occupancy_detail['construction_type']; ?></td>
															<td><?php echo $occupancy_detail['builtup_area']; ?></td>
															<td><?php echo $occupancy_detail['carpet_area']; ?></td>
														</tr>
													<?php endforeach; ?>
													<?php endif; ?>
													</tbody>
												</table>
											</div>
                                                </div>
                                </div>
										</div>
                    </div>
                    <?php
                    }
                    ?>
                     <div class="panel-group">
                    <div class="panel panel-bordered panel-dark">

                                            <div class="panel-heading">
                                                <h3 class="panel-title"><a data-toggle="collapse" href="#collapse31">Tax Details</a></h3>
											</div>
                                            <div id="collapse31" class="panel-collapse collapse">
                                             <div class="panel-body">
											<div class="table-responsive">												
												<table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
													<thead class="thead-light" style="background-color: blanchedalmond;">
															<th scope="col">ARV</th>
															<th scope="col">Effected From</th>
															<th scope="col">Holding Tax</th>
															<th scope="col">Water Tax</th>
															<th scope="col">Conservancy/Latrine Tax</th>
															<th scope="col">Education Cess</th>
															<th scope="col">Health Cess</th>
															<th scope="col">Quarterly Tax</th>
													</thead>
													<tbody>
														<tr>
															<?php if($tax_list):
																$qtr_tax=0; ?>
															<?php foreach($tax_list as $tax_list): 
																$qtr_tax=$tax_list['holding_tax']+$tax_list['water_tax']+$tax_list['latrine_tax']+$tax_list['education_cess']+$tax_list['health_cess'];
															?>
														<tr>
															<td><?php echo $tax_list['arv']; ?></td>
															<td>Quarter : <?php echo $tax_list['qtr']; ?> / Year : <?php echo $tax_list['fy']; ?></td>
															<td><?php echo $tax_list['holding_tax']; ?></td>
															<td><?php echo $tax_list['water_tax']; ?></td>
															<td><?php echo $tax_list['latrine_tax']; ?></td>
															<td><?php echo $tax_list['education_cess']; ?></td>
															<td><?php echo $tax_list['health_cess']; ?></td>
															<td><?php echo $qtr_tax; ?></td>     
														</tr>
														<?php endforeach; ?>
														<?php else: ?>
														<tr>
															<td colspan="7" style="text-align:center;"> Data Not Available...</td>
														</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
                                                </div>
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
                                                     foreach($prop_tr_mode_document as $trval):
                                                ?>
                                            <tr>
                                                <td><?=$trval['doc_name'];?></td>
                                                <?php
                                                                $exp_tr_doc=explode('.',$trval['doc_path']);
                                                                $exp_tr_doc_ext=$exp_tr_doc[1];
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    if($exp_tr_doc_ext=='pdf')
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$trval['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$trval['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$trval['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>

                                                <td><?php
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
                                                                    ?></td>
                                                                <td><?=$trval['remarks'];?></td>

                                                </tr>
                                            <?php endforeach; ?>
                                            <?php
                                                     foreach($prop_pr_mode_document as $prval):
                                                ?>
                                            <tr>
                                                <td><?=$prval['doc_name'];?></td>
                                                <?php
                                                                $exp_pr_doc=explode('.',$prval['doc_path']);
                                                                $exp_pr_doc_ext=$exp_pr_doc[1];
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    if($exp_pr_doc_ext=='pdf')
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$prval['doc_path'];?>" target="_blank"> <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"/></a>
                                                                    <?php
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$prval['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$prval['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    ?>

                                        </td>
                                                <td><?php
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
                                                                    ?></td>
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
