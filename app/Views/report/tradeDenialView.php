<?= $this->include('layout_vertical/popupHeader');?>
<style>
.row{line-height:25px;}
</style>
<link href="<?=base_url();?>/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading"  style="background-color:#39a9b0;">
							<div class="panel-control">
 							</div>
							<h3 class="panel-title">Details of Applied Denial Form </h3>
						</div>
						<div class="panel-body">
                        <div class="panel panel-bordered panel-dark">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-12">
											<h2><b>Application Status :-<?php if($denial_details['status']==5)
                                            {?>
                                              <a style="color:#267121;">Approved</a>
                                           <?php }
                                            else
                                            {?>
                                             <a style="color:#cb1133;"> Rejected</a>
                                           <?php } ?>
                                            </b></h2>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-bordered panel-dark">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<h2><b>Firm Name :- <?=$denial_details['firm_name']?$denial_details['firm_name']:"N/A"; ?></b></h2>
										</div>
                                        <div class="col-sm-4 text-center">
											
										</div>
										<div class="col-sm-4"> 
                                        <h2><b> Firm Image :-<a href="<?=base_url();?>/writable/uploads/<?=$denial_details['file_name'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></b></h2>

										</div>
									</div>
								</div>
							</div>
                                        <div class="panel panel-bordered panel-dark">
											<div class="panel-heading">
												<h3 class="panel-title">Basic Details</h3>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-sm-3">
														<b>Ward No. :</b>
													</div>
													<div class="col-sm-3">
														<?=$ward['ward_no']?$ward['ward_no']:"N/A"; ?>
													</div>
													
													<div class="col-sm-3">
														<b>Owner Name  :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['applicant_name']?$denial_details['applicant_name']:"N/A"; ?>
													</div>
												</div>
                                                <div class="row">
													<div class="col-sm-3">
														<b>Holding No. :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['holding_no']?$denial_details['holding_no']:"N/A"; ?>
													</div>
													
													<div class="col-sm-3">
														<b>Licence No. :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['license_no']?$denial_details['license_no']:"N/A"; ?>
													</div>
												</div>
                                                <div class="row">
													<div class="col-sm-3">
														<b>Mobile No. :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['mob_no']?$denial_details['mob_no']:"N/A"; ?>
													</div>
													
													<div class="col-sm-3">
														<b>City :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['city']?$denial_details['city']:"N/A"; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-3">
														<b>Landmark  :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['landmark']?$denial_details['landmark']:"N/A"; ?>
													</div>
													
													<div class="col-sm-3">
														<b>Apply Date :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['created_on']?date_format(date_create($denial_details['created_on']),"d-m-Y H:i:s"):"N/A"; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-3">
														<b>Remarks :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['remarks']?$denial_details['remarks']:"N/A"; ?>
													</div>
													
													<div class="col-sm-3">
														<b> Address :</b>
													</div>
													<div class="col-sm-3">
														<?=$denial_details['address']?$denial_details['address']:"N/A"; ?>
													</div>
												</div>
												</div>                                                
											</div>
                           <div class="panel panel-bordered panel-dark">
                            <form method="post" class="form-horizontal" action="" enctype="multipart/form-data">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<h3><b>Notice No. :- <?=$noticeDetails['notice_no']?$noticeDetails['notice_no']:"N/A"; ?></b></h3>
										      <input type="hidden" name="notice_id" value="<?=$noticeDetails['id']?>" >
                                        </div>
                                        
										<div class="col-sm-4">
 										 <?php  if($approvedDocDetails['file']){?>
                                            <h3>View Approved Notice <a href="<?=base_url();?>/writable/uploads/<?=$approvedDocDetails['file'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></h3>
                                          <?php }else{?>
                                                <h3>View Approved Notice : N/A</h3>
                                            <?php }?>
                                    </div>
									</div>
                                     
								</div>
                            </form>
							</div>
							</div>
								</div>
						</div>

                <!--===================================================-->
                <!--End page content-->

            
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->

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


