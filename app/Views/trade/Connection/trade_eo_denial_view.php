<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<style>
.row{line-height:25px;}
</style>
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
					<li><a href="#">Trade</a></li>
					<li class="active">Trade Denial Application View</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading"  style="background-color:#39a9b0;">
							<div class="panel-control">
								<a href="<?php echo base_url('Trade_EO/denialInbox') ?>" class="btn btn-default">Back</a>
							</div>
							<h3 class="panel-title">Details of Applied Denial Form </h3>
						</div>
						<div class="panel-body">
							<div class="panel panel-bordered panel-dark">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<h2><b>Firm Name :- <?=$denial_details['firm_name']?$denial_details['firm_name']:"N/A"; ?></b></h2>
										</div>
                                        <div class="col-sm-4 text-center">
											
										</div>
										<div class="col-sm-4"> 
                                        <h2><b> Firm Image :-<a href="<?=base_url();?>/getImageLink.php?path=<?=$denial_details['file_name'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></b></h2>

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
                         <?php if($denial_details['status']==5){?>
                          <div class="panel panel-bordered panel-dark">
                            <form method="post" class="form-horizontal" action="" enctype="multipart/form-data">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-4">
											<h3><b>Notice No. :- <?=$noticeDetails['notice_no']?$noticeDetails['notice_no']:"N/A"; ?></b></h3>
										      <input type="hidden" name="notice_id" value="<?=$noticeDetails['id']?>" >
                                        </div>
                                        <div class="col-sm-4">
                                        <h3><b> View Notice :-<a target="popup" onclick="window.open('<?php echo base_url('denial/notice/'.md5($denial_details['id']));?>','popup','width=600,height=600,scrollbars=no,resizable=no'); return false;" href="<?php echo base_url('denial/notice/'.$denial_details['id']);?>" type="button"     style="color:white;"> <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></b></h3>
                                         
                                    </div>
										<div class="col-sm-4">
                                            <?php if($approvedDocDetails['id']==""){?>
                                        <h3><b> Choose Approved Notice :-<input required type="file" id="approvedoc" name="approvedoc"   class="form-control" ></b></h3>
										<?php }else{?>
                                            <h3>View Approved Notice <a href="<?=base_url();?>/writable/uploads/<?=$approvedDocDetails['file'];?>" target="_blank" > <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;"></a></h3>
                                         <?php } ?>
                                    </div>
									</div>
                                    <?php if($approvedDocDetails['id']==""){?>
                                    <div class="row">
                                    <label class="col-md-2" >Remarks</label>
                                    <div class="col-md-6">
                                    <textarea  type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" ></textarea>
                                    </div>
                                    <div class="col-sm-6 text-center"><br>
                                        <button class="btn btn-primary" id="btn_upload" name="btn_upload" type="submit">Upload</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    </div>
                                    <?php } ?>
								</div>
                            </form>
							</div>
                            <?php }?>
                            <?php if($denial_details['status']==1){?>
                                                  <div class="panel panel-bordered panel-dark">
                                                    <div class="panel-body" style="padding-bottom: 0px;">
                                                       <form method="post" class="form-horizontal" action="">
                                                        <input type="hidden" name="apply_licence_id" value="<?=md5($basic_details['id']??null);?>"/>
                                                                                                               
                                                           <div class="form-group">
                                                               <label class="col-md-2" >Remarks</label>
                                                               <div class="col-md-10">
                                                                   <textarea required type="text" placeholder="Enter Remarks" id="remarks" name="remarks" class="form-control" onkeypress="return isAlphaNum(event);"></textarea>
                                                               </div>
                                                           </div>
                                                           <div class="form-group">
                                                                <label class="col-md-2" >&nbsp;</label>
                                                               <div class="col-md-10">
                                                                    <button class="btn btn-success" id="btn_approved_submit" name="btn_approved_submit" type="submit">Approve</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                     <input type="submit" value="Reject" class="btn btn-danger" id="btn_reject" name="btn_reject" />
                                                               </div>
                                                           </div>
                                                            
                                                                                                              
                                                        </form>
                                                    </div>
                                                </div>
                                                <?php }?>
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


<?= $this->include('layout_vertical/footer');?>
<script>
    $(function() {
        $('.pop').on('click', function() {
            //alert($(this).find('img').attr('src'));
            $('#imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');   
        });
    });

    $(document).ready(function(){
        $('#remarks_div').css('display','none');
        $('#button_div').css('display','none');
        $('#forward_div').css('display','none');
        $("#forward_approval_yes").click(function(){
            $('#remarks_div').css('display','none');
            $('#button_div').css('display','block');
            $('#forward_div').css('display','none');
            $('#btn_approved_submit').css('display','none');
            $('#btn_backward_submit').css('display','none');
            $('#btn_forward_submit').css('display','block');
        });
        $("#forward_approval_no").click(function(){
            $("#approval_yes").prop("checked", true);
            $("#approval_no").prop("checked", true);
            $('#remarks_div').css('display','none');
            $('#button_div').css('display','block');
            $('#forward_div').css('display','block');
            $('#btn_approved_submit').css('display','block');
            $('#btn_backward_submit').css('display','none');
            $('#btn_forward_submit').css('display','none');
            $("#approval_yes").prop("checked", true);
        });
        $("#approval_yes").click(function(){
            $('#remarks_div').css('display','none');
            $('#button_div').css('display','block');
            $('#forward_div').css('display','block');
            $('#btn_approved_submit').css('display','block');
            $('#btn_backward_submit').css('display','none');
            $('#btn_forward_submit').css('display','none');
        });
        $("#approval_no").click(function(){
            $('#remarks_div').css('display','block');
            $('#button_div').css('display','block');
            $('#forward_div').css('display','block');
            $('#btn_approved_submit').css('display','none');
            $('#btn_backward_submit').css('display','block');
            $('#btn_forward_submit').css('display','none');
        });
        $("#btn_approved_submit").click(function(){
            var proceed = true;
            var remarks = $("#remarks").val().trim();
            if(remarks=="")
            {
                $('#remarks').css('border-color','red');
                proceed = false;
            }
            return proceed;
        });
        $("#btn_backward_submit").click(function(){
            var proceed = true;
            var remarks = $("#remarks").val().trim();
            if(remarks=="")
            {
                $('#remarks').css('border-color','red');
                proceed = false;
            }
            return proceed;
        });
        $("#btn_backToCitizen").click(function(){
            var proceed = true;
            var remarks = $("#remarks").val().trim();
            if(remarks=="")
            {
                $('#remarks').css('border-color','red');
                proceed = false;
            }
            return proceed;
        });
        $("#btn_reject").click(function(){
            var proceed = true;
            var remarks = $("#remarks").val().trim();
            if(remarks=="")
            {
                $('#remarks').css('border-color','red');
                proceed = false;
            }
            return proceed;
        });
        $("#remarks").keyup(function(){$(this).css('border-color','');});
    });
    function debarred(ID)
    {
        var result = confirm("Do You Want To Debarred Trade Licence!!!");
        if(result)
         window.location.replace("<?=base_url();?>/Trade_EO/debarredTradeLicence/"+ID);
    }
    function approved(ID)
    {
        var result = confirm("Do You Want To Approve Trade Licence!!!");
        if(result)
         window.location.replace("<?=base_url();?>/Trade_EO/approveTradeLicence/"+ID);
    }
    
    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }
</script>
