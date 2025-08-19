<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->

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
                        <li><a href="#"> Water </a></li>
                        <li><a href="<?php echo base_url('water_da/index')?>"> Dealing Assistant Inbox </a></li>
                        <li class="active"> Water Connection View </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
            <div id="page-content">

                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Water Connection Details View - <?php echo $consumer_details['application_no']; ?> </h3>
                    </div>
                    <div class="panel-body">     
                        <div class="row">
                            <label class="col-md-2 bolder">Type of Connection </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['connection_type']; ?>
                            </div>
                            <label class="col-md-2 bolder">Connection Through </label>
                            <div class="col-md-3 pad-btm">
                                            <?php echo $consumer_details['connection_through']; ?> 
                                        </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 bolder">Property Type </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['property_type']; ?> 
                            </div>
                                <label class="col-md-2 bolder">Pipeline Type </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['pipeline_type']; ?> 
                            </div>
                        </div>
                    
                        <div class="row">
                            <label class="col-md-2 bolder">Category </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['category']; ?> 
                            </div>
                                <label class="col-md-2 bolder">Owner Type </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['owner_type']; ?> 
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Property Details</h3>
                    </div>
                    <div class="panel-body">                     
                        <div class="row">
                            <label class="col-md-2 bolder">Ward No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['ward_no']; ?>
                            </div>
                            <?php

                                if($consumer_details['prop_dtl_id']!="" and $consumer_details['prop_dtl_id']!=0)
                                {
                            ?>
                            <label class="col-md-2 bolder">Holding No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['holding_no']; ?> 
                            </div>
                            <?php   
                                }
                                else
                                {
                            ?>
                            <label class="col-md-2 bolder">SAF No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['saf_no']; ?> 
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <div class="row">
                            <label class="col-md-2 bolder">Area in Sqft.</label>
                            <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['area_sqft']; ?> 
                            </div>
                            <label class="col-md-2 bolder">Area in Sqmt.</label>
                            <div class="col-md-3 pad-btm">
                                <?php echo round($consumer_details['area_sqmt'],2); ?> 
                            </div>
                        </div>
                    <div class="row">
                            <label class="col-md-2 bolder">Address</label>
                            <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['address']; ?> 
                            </div>
                            <label class="col-md-2 bolder">Landmark </label>
                            <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['landmark']; ?> 
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 bolder">Pin</label>
                            <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['pin']; ?> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
			
                <div class="panel panel-bordered panel-dark">
					<div class="panel-heading">
						<h3 class="panel-title"> Owner Details</h3>
					</div>
					<div class="panel-body">
						<table class="table table-bordered table-responsive">
							<thead class="bg-trans-dark text-dark">
								<tr>
									<th class="bolder">Owner Name</th>
									<th class="bolder">Guardian Name</th>
									<th class="bolder">Mobile No.</th>
									<th class="bolder">Email ID</th>
									<th class="bolder">State</th>
									<th class="bolder">District</th>
									<th class="bolder">City</th>
								</tr>
							</thead>
							<tbody>
								<?php
								if($owner_details)
								{
									foreach($owner_details as $val)
								{
								?>
								<tr>
									<td><?php echo $val['applicant_name'];?></td>
									<td><?php echo $val['father_name'];?></td>
									<td><?php echo $val['mobile_no'];?></td>
									<td><?php echo $val['email_id'];?></td>
									<td><?php echo $val['state'];?></td>
									<td><?php echo $val['district'];?></td>
									<td><?php echo $val['city'];?></td>
								</tr>
								<?php
								}
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

				
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Electricity Connection Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <label class="col-md-2 bolder">K No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_k_no']; ?>
                            </div>
                            <label class="col-md-2 bolder">Bind Book No.</label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_bind_book_no']; ?> 
                        </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 bolder">Electricity Account No. </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_account_no']; ?> 
                            </div>
                            <label class="col-md-2 bolder">Electricity Category </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $consumer_details['elec_category']; ?> 
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Document List</h3>
                    </div>
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <div class="table-responsive">
                            <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Document Name</th>
                                        <th>Document</th>
                                        <th>Verify/Reject</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i=0;echo"<pre>";//print_r($owner_details);echo"</pre>";
                                foreach($doc_list as $doc)
                                {
                                    //Checking if consumer document
                                    $owner_name=NULL; 
                                    if($doc["applicant_detail_id"]>0)
                                    {
                                        $owner=array();

                                        $applicant_detail_id=$doc['applicant_detail_id'];
                                        foreach($owner_details as $value)
                                        {
                                           
                                            if($value['id']==$applicant_detail_id)
                                            {
                                                $owner=$value;
                                            }
                                        }

                                        // $owner1 = array_filter(
                                        //                     $owner_details, function ($var) use ($applicant_detail_id) 
                                        //                                     { 
                                        //                                      return ($var['id'] == $applicant_detail_id);
                                        //                                     }
                                        //                                     )[0]; ;
                                        $owner_name='<span class="text text-primary">('.$owner["applicant_name"].')</span>';
                                    }
                                    ?>
                                     <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$doc["document_name"]!="" ? $doc["document_name"] : $doc["doc_for"];?> <?=$owner_name;?></td>
                                        <td>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["document_path"];?>" target="_blank" title="<?=$doc["document_name"];?>">
                                            <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;">
                                            </a>
                                        </td>
                                        <td>
                                        <?php
                                        
                                        if($doc["verify_status"]==0) // Not Verified then verify
                                        {
                                            ?>
                                            <form method="POST">
                                                <input type="hidden" name="applicant_doc_id" value="<?=$doc["id"];?>">
                                                    <button type="submit" name="btn_verify" value="Verify" class="btn btn-success btn-rounded btn-labeled">
                                                        <i class="btn-label fa fa-check"></i>
                                                        <span> Verify </span>
                                                    </button>

                                                    <a class="btn btn-danger btn-rounded btn-labeled" role="button" data-toggle="modal" data-target="#rejectModal<?=$doc["id"];?>">
                                                        <i class="btn-label fa fa-close"></i>
                                                        <span> Reject </span>
                                                    </a>
                                            </form>

                                            <div class="modal fade" id="rejectModal<?=$doc["id"];?>" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h4 class="modal-title"> Mention Reason For Document Rejection - <?=$doc["document_name"];?> <?=$owner_name;?> </h4>
                                                        <button type="button" class="close" data-dismiss="modal">×</button>
                                                        </div>
                                                    
                                                        
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="applicant_doc_id" value="<?=$doc["id"];?>">
                                                                <textarea type="text" name="remarks" id="remarks" class="form-control" placeholder="Mention Remarks Here" required=""></textarea>
                                                            </div>
                                                        
                                                        
                                                            <div class="modal-footer">
                                                            <input type="submit" name="btn_reject" value="Reject" class="btn btn-primary">
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        if($doc["verify_status"]==1) // Approved
                                        {
                                            ?>
                                            <span class="text text-success text-bold">Approved</span>
                                            <?php
                                        }
                                        if($doc["verify_status"]==2) // Rejected
                                        {
                                            ?>
                                            <span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" data-original-title="<?=$doc["remarks"];?>">Rejected</span>
                                            <?php
                                        }
                                        ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?=$this->include('water/water_connection/LevelRemarksTab');?>
                

                <form method="post" class="form-horizontal" action="">
                    <div class="panel panel-bordered panel-dark">
                        <div class="panel-body" style="padding-bottom: 0px;">
                            <div class="form-group">
                                <label class="col-md-2" >Remarks</label>
                                    <div class="col-md-10">
                                        <textarea type="text" placeholder="Enter Remarks" id="remarksm" name="remarks" class="form-control" required></textarea>
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2" >&nbsp;</label>
                                    <div class="col-md-10">
                                    <?php
                                    
                                    $everyDocApproved = "Yes";
                                    foreach($doc_list as $doc)
                                    {
                                        // 0 Not Verfied, 2 Rejected
                                        if(in_array($doc["verify_status"], [0, 2])){
                                        $everyDocApproved = "No";
                                        break;
                                        }
                                    }


                                    if($everyDocApproved=="Yes")
                                    {
                                        ?>
                                        <button class="btn btn-primary" id="btn_approve_submit" name="btn_approve_submit" type="submit">Approve</button>
                                        
                                        <?php
                                    }
                                    ?>
                                    <button class="btn btn-primary" id="btn_app_submit" name="btn_app_submit" type="submit">Back To Citizen</button>
                                    <a href="<?=base_url().'/WaterApplyNewConnection/updat_application/'.md5($consumer_details['id']);?>" target="_blanck" class="btn btn-mint">Update Application</a>
                                    </div>
                            </div>
                        </div>
                    </div>
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

<script>
    function app_img_remarks_details(il)
    {
        debugger;
     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var applicant_img_verify_status =$('#applicant_img_verify_status'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
     //var verified_count = parseInt($("#verified_count").val());
     //alert(app_img_verify);
     if(app_img_verify=="2")
        {
            if(count_change_app>0)
            {
                if(applicant_img_verify_status==1)
                {
                    $("#applicant_img_verify_status"+il).val(0);
                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }

            $("#app_img_remarks"+il).show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(app_img_verify=="1")
        {
            if(applicant_img_verify_status==0)
            {
                $("#applicant_img_verify_status"+il).val(1);
                var str=count_change_app+1;
                $("#count_change_app").val(str);

                $("#app_img_remarks"+il).hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    }
    function app_doc_remarks_details(il)
    {

     var app_img_verify =$('#app_img_verify'+il).val();
     var app_img_remarks =$('#app_img_remarks'+il).val();
     var app_doc_verify =$('#app_doc_verify'+il).val();
     var app_doc_remarks =$('#app_doc_remarks'+il).val();
     var applicant_doc_verify_status =$('#applicant_doc_verify_status'+il).val();
     var count_change_app = parseInt($("#count_change_app").val());
     var count_app = parseInt($("#count_app").val());
       //alert(app_img_verify);
     if(app_doc_verify=="2")
        {
            if(count_change_app>0){
                if(applicant_doc_verify_status==1){
                   $("#applicant_doc_verify_status"+il).val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }

            $("#app_doc_remarks"+il).show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(app_doc_verify=="1")
        {
            if(applicant_doc_verify_status==0){
                    $("#applicant_doc_verify_status"+il).val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#app_doc_remarks"+il).hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    }
    $(document).ready(function()
    {
        $(".app_img_remarks").hide();
        $(".app_doc_remarks").hide();
        $("#pr_remarks").hide();
        $("#ap_remarks").hide();
        $("#cf_remarks").hide();
        $("#ed_remarks").hide();
        $("#mb_remarks").hide();
        $("#bpl_remarks").hide();



        $("#pr_verify").on('change',function()
        {
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var pr_verify = $("#pr_verify").val();
        var pr_verify_status = $("#pr_verify_status").val();
        if(pr_verify=="2")
        {
            if(count_change_app>0){
                if(pr_verify_status==1){
                   $("#pr_verify_status").val(0);


                    var str=count_change_app-1;
                    $("#count_change_app").val(str);
                }
            }
            $("#pr_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(pr_verify=="1")
        {
            if(pr_verify_status==0){
                    $("#pr_verify_status").val(1);

                var str=count_change_app+1;
                $("#count_change_app").val(str);
                $("#pr_remarks").hide();
            }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#ap_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var ap_verify = $("#ap_verify").val();
        var ap_verify_status = $("#ap_verify_status").val();
        if(ap_verify=="2")
        {
            if(count_change_app>0){
                if(ap_verify_status==1){
                   $("#ap_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                }
            }
            $("#ap_remarks").show();
             $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(ap_verify=="1")
        {
            if(ap_verify_status==0){
                $("#ap_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#ap_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });

    $("#cf_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var cf_verify = $("#cf_verify").val();
        var cf_verify_status = $("#cf_verify_status").val();
        if(cf_verify=="2")
        {
            if(count_change_app>0){
                if(cf_verify_status==1){
                   $("#cf_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#cf_remarks").show();
             $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(cf_verify=="1")
        {
            if(cf_verify_status==0){
                    $("#cf_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#cf_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#ed_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var ed_verify = $("#ed_verify").val();
        var ed_verify_status = $("#ed_verify_status").val();
        if(ed_verify=="2")
        {
            if(count_change_app>0){
                if(ed_verify_status==1){
                   $("#ed_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#ed_remarks").show();
             $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(ed_verify=="1")
        {
            if(ed_verify_status==0){
                $("#ed_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
             $("#ed_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });

    $("#mb_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var mb_verify = $("#mb_verify").val();
        var mb_verify_status = $("#mb_verify_status").val();
        if(mb_verify=="2")
        {
            if(count_change_app>0){
                if(mb_verify_status==1){
                   $("#mb_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#mb_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(mb_verify=="1")
        {
            if(mb_verify_status==0){
                $("#mb_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
            $("#mb_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#bpl_verify").on('change',function(){
		var count_change_app = parseInt($("#count_change_app").val());
        var count_app = parseInt($("#count_app").val());
        var bpl_verify = $("#bpl_verify").val();
        var bpl_verify_status = $("#bpl_verify_status").val();
        if(bpl_verify=="2")
        {
            if(count_change_app>0){
                if(bpl_verify_status==1){
                   $("#bpl_verify_status").val(0);
                var str=count_change_app-1;
                $("#count_change_app").val(str);
                    }
            }
            $("#bpl_remarks").show();
            $("#btn_approve_submit").css("display","none");
            $("#btn_app_submit").css("display","block");
        }
        else if(bpl_verify=="1")
        {
            if(bpl_verify_status==0){
                $("#bpl_verify_status").val(1);
            var str=count_change_app+1;
            $("#count_change_app").val(str);
            $("#bpl_remarks").hide();
                }
            if(str==count_app)
            {
                $("#btn_approve_submit").css("display","block");
                $("#btn_app_submit").css("display","none");
            }
        }
    });
    $("#btn_app_submit").click(function(){
        var proceed = true; 

        $('#saf_receive_table').find('.app_img_verify').each(function(){
            $(this).css('border-color','');
            var ID = this.id.split('app_img_verify')[1];
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
            if($(this).val()=='2'){
                if ($("#app_img_remarks"+ID).val()=="") {
                    $("#app_img_remarks"+ID).css('border-color','red'); 	proceed = false;
                }

            }
        });
        $('#saf_receive_table').find('.app_doc_verify').each(function(){
            $(this).css('border-color','');
            var IDD = this.id.split('app_doc_verify')[1];
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }
             if($(this).val()=='2'){
                 if ($("#app_doc_remarks"+IDD).val()=="") {
                     $("#app_doc_remarks"+IDD).css('border-color','red'); 	proceed = false;
                 }
             }
        });
        
        var remarks = $("#remarksm").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }
        console.log(remarks);
        var pr_verify = $("#pr_verify").val();
        if(pr_verify=="")
        {
            $('#pr_verify').css('border-color','red');
            proceed = false;
        }
        if(pr_verify=="2")
        {
            var pr_remarks = $("#pr_remarks").val();
            if(pr_remarks=="")
            {
                $('#pr_remarks').css('border-color','red');
                proceed = false;
            }
        }
        var ap_verify = $("#ap_verify").val();
        if(ap_verify=="")
        {
            $('#ap_verify').css('border-color','red');
            proceed = false;
        }
        if(ap_verify=="2")
        {
            var ap_remarks = $("#ap_remarks").val();
            if(ap_remarks=="")
            {
                $('#ap_remarks').css('border-color','red');
                proceed = false;
            }
        }

        var cf_verify = $("#cf_verify").val();
        if(cf_verify=="")
        {
            $('#cf_verify').css('border-color','red');
            proceed = false;
        }
        if(cf_verify=="2")
        {
            var cf_remarks = $("#cf_remarks").val();
            if(cf_remarks=="")
            {
                $('#cf_remarks').css('border-color','red');
                proceed = false;
            }
        }
        var ed_verify = $("#ed_verify").val();
        if(ed_verify=="")
        {
            $('#ed_verify').css('border-color','red');
            proceed = false;
        }
        if(ed_verify=="2")
        {
            var ed_remarks = $("#ed_remarks").val();
            if(ed_remarks=="")
            {
                $('#ed_remarks').css('border-color','red');
                proceed = false;
            }
        }

        var mb_verify = $("#mb_verify").val();
        if(mb_verify=="")
        {
            $('#mb_verify').css('border-color','red');
            proceed = false;
        }
        if(mb_verify=="2")
        {
            var mb_remarks = $("#mb_remarks").val();
            if(mb_remarks=="")
            {
                $('#mb_remarks').css('border-color','red');
                proceed = false;
            }
        }
        var bpl_verify = $("#bpl_verify").val();
        if(bpl_verify=="")
        {
            $('#bpl_verify').css('border-color','red');
            proceed = false;
        }
        if(bpl_verify=="2")
        {
            var bpl_verify = $("#bpl_verify").val();
            if(bpl_verify=="")
            {
                $('#bpl_verify').css('border-color','red');
                proceed = false;
            }
        }
        console.log(proceed);
        return proceed;
    });
    $("#btn_approve_submit").click(function(){
        var proceed = true;

        var remarks = $("#remarks").val();
        if(remarks=="")
        {
            $('#remarks').css('border-color','red');
            proceed = false;
        }
        $('#saf_receive_table').find('.app_img_verify').each(function(){
            $(this).css('border-color','');
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

        });
        $('#saf_receive_table').find('.app_doc_verify').each(function(){
            $(this).css('border-color','');
            if(!$.trim($(this).val())){ $(this).css('border-color','red'); 	proceed = false; }

        });

        var pr_verify = $("#pr_verify").val();
        if(pr_verify=="")
        {
            $('#pr_verify').css('border-color','red');
            proceed = false;
        }

        var ap_verify = $("#ap_verify").val();
        if(ap_verify=="")
        {
            $('#ap_verify').css('border-color','red');
            proceed = false;
        }



        var cf_verify = $("#cf_verify").val();
        if(cf_verify=="")
        {
            $('#cf_verify').css('border-color','red');
            proceed = false;
        }

        var ed_verify = $("#ed_verify").val();
        if(ed_verify=="")
        {
            $('#ed_verify').css('border-color','red');
            proceed = false;
        }



        var mb_verify = $("#mb_verify").val();
        if(mb_verify=="")
        {
            $('#mb_verify').css('border-color','red');
            proceed = false;
        }

        var bpl_verify = $("#bpl_verify").val();
        if(bpl_verify=="")
        {
            $('#bpl_verify').css('border-color','red');
            proceed = false;
        }

        return proceed;
    });
});
</script>