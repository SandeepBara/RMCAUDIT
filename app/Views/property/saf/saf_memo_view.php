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
					<li><a href="#">SAF Document Verification</a></li>
					<li class="active">SAF Document Verification View</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Document Verification View <a href="<?php echo base_url('documentverification/saf_generated_memo_list') ?>" class="btn btn-sm btn-danger" style="float:right;margin-top:.5%;"> Back </a></h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
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
                        <label class="col-sm-2">SAF Memo No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($memo_no['memo_no']))?$memo_no['memo_no']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Generated Holding No.:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($holding_no['holding_no']))?$holding_no['holding_no']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Last Effective Date:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($prop_tax['qtr']))?$prop_tax['qtr'].'/'.$fy['fyFrom']:'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2">Effective Amount:</label>
                        <div class="col-sm-4 pad-btm">
                             <b><?=(isset($prop_tax['id']))?($prop_tax['holding_tax']+$prop_tax['water_tax']+$prop_tax['education_cess']+$prop_tax['health_cess']+$prop_tax['latrine_tax']):'<span class="text-danger">N/A</span>';?></b>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                        <!-------Owner Details-------->
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Owner Details</h3>
                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Guardian Name</th>
                                                <th>Relation</th>
                                                <th>Mobile No.</th>
                                                <th>Aadhar No.</th>
                                                <th>PAN No.</th>
                                                <th>Email ID</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tr_tbody">
                                            <tr>
                                                <td>
                                                <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = $value['owner_name']; 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                                </td>
                                                <td>
                                                <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = $value['guardian_name']; 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                                </td>
                                                <td>
                                                <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = $value['relation_type']; 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                                </td>
                                                <td>
                                                <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = $value['mobile_no']; 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                                </td>
                                                <td>
                                                <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = $value['aadhar_no']; 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                                </td>
                                                <td>
                                                <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = $value['pan_no']; 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                                </td>
                                                <td>
                                                <?php
                                                    if(isset($owner_list)):
                                                         if(empty($owner_list)):
                                                    ?>
                                                            <span class="text-danger">N/A</span>
                                                    <?php else:
                                                    $ownerArray = [];
                                                    foreach($owner_list as $value) { 
                                                        $ownerArray[] = $value['email']; 
                                                    }
                                                    echo implode(', ', $ownerArray);
                                                      endif;  
                                                 endif;  ?>
                                                </td>
                                            </tr>
 					                    </tbody>
					                </table>
                                </div>
                             </div>
                         </div>
                        </div>
                    </div>
                    <form method="post" class="form-horizontal" action="">
                    <div class="row">
                        <div class="col-md-12">
                        <!------Related Document Start-------->


                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Related Document</h3>

                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:160px;">Document Name</th>
                                                <th style="width:160px;">Document Image</th>
                                                <th style="width:160px;">Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Applicant Image</td>
                                                <td><a href="#" class="pop"><img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$applicant_image["doc_path"];?>" style="width: 40px; height: 40px;"></a></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><?=$applicant_document['doc_name'];?></td>
                                                <td><a href="#" class="pop"> <img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$applicant_document['doc_path'];?>" style="width: 40px; height: 40px;"></a></td>
                                                <td>
                                                    <?php
                                                    if($applicant_document['verify_status']=="1")
                                                    {
                                                        echo "<span class='text-danger'>Verified</span>";
                                                    }
                                                    else if($applicant_document['verify_status']=="2")
                                                    {
                                                        echo "<span class='text-danger'>Rejected</span>";
                                                    }
                                                    ?>
                                                </td>

                                            </tr>

 					                    </tbody>
					                </table>
                                </div>
                </div>
            </div>
                            <!----related doc-------->
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
             <!--------prop doc------------>
                            <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
                                <h3 class="panel-title">Property Document</h3>

                            </div>
                            <div class="panel-body" style="padding-bottom: 0px;">
                                <div class="table-responsive">
                                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th style="width:160px;">Document Name</th>
                                                <th style="width:160px;">Document Image</th>
                                                <th style="width:160px;">Status</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td><?=$prop_tr_mode_document['doc_name'];?></td>
                                                <td><a href="#" class="pop"> <img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$prop_tr_mode_document['doc_path'];?>" style="width: 40px; height: 40px;"></a></td>
                                                <td>
                                                    <?php
                                                    if($prop_tr_mode_document['verify_status']=="1")
                                                    {
                                                        echo "<span class='text-danger'>Verified</span>";
                                                    }
                                                    else if($prop_tr_mode_document['verify_status']=="2")
                                                    {
                                                        echo "<span class='text-danger'>Rejected</span>";
                                                    }
                                                    ?>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td><?=$prop_pr_mode_document['doc_name'];?></td>
                                                <td><a href="#" class="pop"> <img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$prop_pr_mode_document['doc_path'];?>" style="width: 40px; height: 40px;"></a></td>
                                                <td>
                                                    <?php
                                                    if($prop_pr_mode_document['verify_status']=="1")
                                                    {
                                                        echo "<span class='text-danger'>Verified</span>";
                                                    }
                                                    else if($prop_pr_mode_document['verify_status']=="2")
                                                    {
                                                        echo "<span class='text-danger'>Rejected</span>";
                                                    }
                                                    ?>
                                                </td>

                                            </tr>
 					                    </tbody>
					                </table>
                                </div>
                </div>
            </div>
                            <!------------>


                            </div>

                    </div>

                        </form>
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
