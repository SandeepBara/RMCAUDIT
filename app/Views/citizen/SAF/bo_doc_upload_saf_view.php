<?= $this->include('layout_home/header');?>
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container" style="padding-top: 10px;">
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Basic Details</h3>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-2">
														<b>Ward No. :</b>
													</div>
													<div class="col-sm-3 pad-btm">
														<?php echo $basic_details['ward_no']; ?>
													</div>
													<div class="col-sm-1">
													</div>
													<div class="col-sm-3">
														<b>Application No. :</b>
													</div>
													<div class="col-sm-3 pad-btm">
														<?php echo $basic_details['saf_no']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Property Type :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php echo $basic_details['property_type']; ?>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b>Ownership Type :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php echo $basic_details['ownership_type']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Address :</b>
													</div>
													<div class="col-md-10 pad-btm">
														<?php echo $basic_details['prop_address']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Area Of Plot :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php echo $basic_details['area_of_plot']; ?> <span class="text-danger">(In decimal)</span>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b>Assessment Type :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php echo $basic_details['assessment_type']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b> Khata :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php echo $basic_details['khata_no']; ?>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b> Plot No. :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php echo $basic_details['plot_no']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<b>Mauja Name :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php echo $basic_details['village_mauja_name']; ?>
													</div>
													<div class="col-md-1">
													</div>
													<div class="col-md-3">
														<b>Rainwater Harvesting Provision :</b>
													</div>
													<div class="col-md-3 pad-btm">
														<?php if($basic_details['is_water_harvesting']=='f')
                                                              {
                                                                echo "No";
                                                              }
                                                              else if($basic_details['is_water_harvesting']=='t')
                                                              {
                                                                echo "Yes";
                                                              }
                                                        ?>
													</div>
												</div>

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
                                                                <th> Document Name</th>
                                                                <th>Applicant Document</th>
                                                                <th>Status</th>
                                                                <th>Remarks</th>
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
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$docval['doc_path'];?>" target="_blank" ><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$docval['doc_path'];?>" style="width: 40px; height: 40px;"></a>
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


                    <form method="post" class="form-horizontal" action="">
                         <!--------saf  doc------------>
                            <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">SAF Form</h3>

                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                        <thead class="thead-light" style="background-color: blanchedalmond;">
                                            <tr>
                                                <th>Document Name</th>
                                                <th>Document</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>SAF Form</td>
                                                <?php
                                                                $exp_tr_doc=explode('.',$owner_saf_form['doc_path']);
                                                                $exp_tr_doc_ext=$exp_tr_doc[1];
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    if($exp_tr_doc_ext=='pdf')
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$owner_saf_form['doc_path'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    else
                                                                    {
                                                                    ?>
                                                                    <a href="<?=base_url();?>/writable/uploads/<?=$owner_saf_form['doc_path'];?>" target="_blank"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$owner_saf_form['doc_path'];?>" style="width: 40px; height: 40px;"></a>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                </tr>


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
                <div class="panel">
                    <div class="panel-body text-center" style="height:70px;">
                        <a href="<?php echo base_url('CitizenPropertySAF/citizen_saf_property_details/'.$id);?>" type="button" class="btn btn-primary">View Property Details</a>
                    </div>
                </div>
                            <!------------>



                        </form>

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
<?= $this->include('layout_home/footer');?>
<script>
$(function() {
		$('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
			$('#imagepreview').attr('src', $(this).find('img').attr('src'));
			$('#imagemodal').modal('show');   
		});
});
</script>

<script>
$(document).ready(function(){
    $('#remarks_div').css('display','none');
    $('#button_div').css('display','none');
    $("#approval_yes").click(function(){
        $('#remarks_div').css('display','none');
        $('#button_div').css('display','block');
        $('#btn_approved_submit').css('display','block');
        $('#btn_backward_submit').css('display','none');
    });
    $("#approval_no").click(function(){
        $('#remarks_div').css('display','block');
        $('#button_div').css('display','block');
        $('#btn_approved_submit').css('display','none');
        $('#btn_backward_submit').css('display','block');
    });

    $("#btn_backward_submit").click(function(){
        var proceed = true;
        var remarks = $("#remarks").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }
        return proceed;
    });
});
</script>