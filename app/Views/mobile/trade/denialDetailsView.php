<?=$this->include("layout_mobi/header");?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url();?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
            <div id="page-head">
<!--Page Title-->
<div id="page-title">
</div>
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Trade</a></li>
<li class="active">Report</li>
 </ol>
</div>
<div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading"  style="background-color:#39a9b0;">
							<div class="panel-control">
 							</div>
							<h3 class="panel-title">
								Details of Applied Denial Form 
								<span class = "pull-right btn btn-info" onclick="history.back();"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</span>
							</h3>
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
										<?php if($emp_name =='TC'){?>
											<h3><b> View Notice :-<a target="popup" onclick="window.open('<?php echo base_url('denial/notice/'.md5($denial_details['id']));?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('denial/notice/'.md5($denial_details['id']));?>" type="button"     style="color:white;"> <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></b></h3>
											<?php } else {?>
 										 <?php  if($approvedDocDetails['file']){?>
                                            <h3>View Approved Notice <a href="<?=base_url();?>/writable/uploads/<?=$approvedDocDetails['file'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></h3>
                                          <?php }else{?>
                                                <!-- <h3>View Approved Notice : N/A</h3> -->
												<h3>View Approved Notice : <?=$denial_details['status']==5 ? "<a target='popup' onclick='window.open(".base_url('denial/notice/'.md5($denial_details['id']))."','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;' href='".base_url('denial/notice/'.md5($denial_details['id']))."' type='button'     style='color:white;'> <img id='imageresource' src='".base_url()."/public/assets/img/pdf_logo.png' style='width: 40px; height: 40px;'></a>":"N/A";?></h3>
                                            <?php }?>
                                              <?php } ?>
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
            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
     
     
	$(document).ready(function() {
		$('#demo_dt_basic').DataTable({
			responsive: true,
			dom: 'Bfrtip',
	        lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
	        ],
	        buttons: [
	        	'pageLength',
	          {
				text: 'excel',
				extend: "excel",
				title: "<?=$status??null?>    <?=$from_date??null?>  -  <?=$to_date??null?>",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "<?=$status??null?>       <?=$from_date??null?>  -  <?=$to_date??null?>",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3] }
			}]
		});
	});
 </script>

