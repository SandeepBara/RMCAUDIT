<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="#">Property</a></li>
		<li class="active">Special Document Verification</li>
		</ol>
	</div>
	<!--Page content-->
	<div id="page-content">
            
        
        <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Ward No</th>
                                            <th>Old Holding No.</th>
                                            <th>New Holding No.</th>
                                            <th>Address</th>
                                            <!-- <th>Ward</th> -->
                                            <!-- <th>Aadhar No.</th>
                                            <th>PAN No.</th>
                                            <th>Email ID</th>
                                            <th>DOB</th>
                                            <th>Gender</th>
                                            <th>Is_Specially_Abled</th>
                                            <th>Is_Armed_Force</th> -->
                                        </tr>
                                    </thead>
                                    <tbody id="owner_dtl_append">
                                <?php
                                
                                if (isset($prop_dtl))
                                {
                                    // foreach ($owner_details_list as $owner_detail)
                                    // {
                                        
                                        ?>
                                        <tr>
                                            <td>
                                                <?=$prop_dtl['ward_mstr_id'];?>
                                            </td>
                                            <td>
                                                <?=$prop_dtl['holding_no'];?>
                                            </td>
                                            <td>
                                                <?=$prop_dtl['new_holding_no'];?>
                                            </td>
                                            <td>
                                                <?=$prop_dtl['prop_address'];?>
                                            </td>
                                            
                                            
                                        </tr>
                                <?php
                                    // }
                                }
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="panel panel-bordered panel-dark">
                
                
            </div>
           
            
           
            

            <!--------prop doc------------>
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Document</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;" id="documen">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Owner Name</th>
                                    <th>Uploaded On</th>
                                    <th>Document Name</th>
                                    <th>Value</th>
                                    <th>Document</th>
                                    <th>Verify/Reject</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=0;
                                $everyDocVerified=true;
                                foreach($sepcial_doc_dtl_list as $doc)
                                {
                                    $owner_document=(array)null;
                                    // if(is_numeric($doc["saf_owner_dtl_id"]) && $doc["saf_owner_dtl_id"]!="")
                                    // {
                                    //     $saf_owner_dtl_id=$doc["saf_owner_dtl_id"];
                                    //     // $owner_document = array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                    //     //     return ($var['id'] == $saf_owner_dtl_id);
                                    //     // })[0];
                                    //     //updated since getting error
                                    //     $owner_document = isset(array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                    //         return ($var['id'] == $saf_owner_dtl_id);
                                    //     })[0])?array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                    //         return ($var['id'] == $saf_owner_dtl_id);
                                    //     })[0]:null;
                                    //     //print_var($owner_document);
                                    // }
                                    ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td>
                                            <?=$doc["owner_name"];?>
                                        </td>
                                        <td>
                                            <?=date('Y-m-d',strtotime($doc["created_on"]));?>
                                        </td>
                                        <td>
                                            <?=$doc["other_doc"];?>
                                            
                                        </td>
                                        <td>
                                            <?php
                                            if($doc["other_doc"]=="gender_document"){
                                                echo "Gender (".$doc['gender'].")"; ?>
                                                 
                                            <?php }
                                            if($doc["other_doc"]=="dob_document"){
                                                echo "DOB (".$doc['dob'].")"; ?>
                                                 
                                           <?php }
                                            if($doc["other_doc"]=="handicaped_document"){
                                                echo "Specially Abled (".$doc['is_specially_abled'].")"; ?>
                                                 
                                            <?php }
                                            if($doc["other_doc"]=="armed_force_document"){
                                                echo "Armed Force (".$doc['is_armed_force'].")"; ?>
                                                 
                                            <?php }
                                              ?>
                                            
                                        </td>
                                        <td>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>" target="_blank">
                                                <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                            </a>
                                        </td>
                                        <td>
                                            <?php
                                            if($doc["verify_status"]==0 || $doc['verify_status']==1|| $doc['verify_status']==2)
                                            {
                                                $everyDocVerified=false;
                                                ?>
                                                  <?php if($doc["verify_status"]==0) {  ?>
                                                <form method="POST">
                                                
                                                <?php
                                            if($doc["other_doc"]=="gender_document"){
                                               ?>
                                                 <input hidden type="text" name="gender_value" value="<?= $doc['gender'] ?>">
                                            <?php }
                                            if($doc["other_doc"]=="dob_document"){
                                                ?>
                                                 <input hidden type="text" name="dob_value" value="<?= $doc['dob'] ?>">
                                           <?php }
                                            if($doc["other_doc"]=="handicaped_document"){
                                                 ?>
                                                 <input hidden type="text" name="handicapped_value" value="<?= $doc['is_specially_abled'] ?>">
                                            <?php }
                                            if($doc["other_doc"]=="armed_force_document"){
                                                 ?>
                                                 <input hidden type="text" name="armed_value" value="<?= $doc['is_armed_force'] ?>">
                                            <?php }
                                              ?>
                                                <input hidden type="text" name="prop_doc_id" value="<?= $doc['id'] ?>">
                                                <input hidden type="text" name="prop_owner_details_id" value="<?= $doc['prop_owner_details_id'] ?>">
                                                <input hidden type="text" name="other_doc" value="<?= $doc['other_doc'] ?>">
                                                      
                                                        <button type="submit" name="btn_verify" value="Verify" class="btn btn-success btn-rounded btn-labeled">
                                                            <i class="btn-label fa fa-check"></i>
                                                            <span> Verify </span>
                                                        </button>

                                                        <a class="btn btn-danger btn-rounded btn-labeled" role="button" data-toggle="modal" data-target="#rejectModal<?=$i;?>">
                                                            <i class="btn-label fa fa-close"></i>
                                                            <span> Reject </span>
                                                        </a>
                                                </form>

                                                <?php }  
                                                 else if($doc["verify_status"]==1)
                                                 {
                                                     ?>
                                                     <span class="text text-success">Verfied</span>
                                                     <?php
                                                 }
                                                 else if($doc["verify_status"]==2)
                                                 {
                                                     $everyDocVerified=false;
                                                     ?>
                                                     <span class="text text-danger" data-toggle="tooltip" title="<?=$doc["remarks"];?>">Rejected</span>
                                                     <?php
                                                 }
                                                 ?>
                                                <div class="modal fade" id="rejectModal<?=$i;?>" style="display: none;">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <h4 class="modal-title"> Mention Reason For Document Rejection - <?=$doc["other_doc"];?> </h4>
                                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                                            </div>
                                                        
                                                            
                                                            <form method="POST">
                                                            <input hidden type="text" name="prop_doc_id" value="<?= $doc['id'] ?>">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="saf_doc_dtl_id" value="<?=$doc["id"];?>">
                                                                    <textarea type="text" name="remarks" id="remarks" class="form-control" placeholder="Mention Remarks Here" required></textarea>
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



            <div class="panel panel-bordered panel-dark">
                <div class="panel-body" style="padding-bottom: 0px;">
                    <form method="POST">
                    <?php 
                    
                    if(isset($level_dtl['verification_status']) && $level_dtl['verification_status']==0)
                    {
                        ?>
                        <div class="form-group">
                            <label class="col-md-2 text-bold">Final Remarks<span class="text-danger">*</span></label>
                            <div class="col-md-10">
                                <textarea id="remarks" name="remarks" class="form-control" placeholder="Please Enter Remark" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2">&nbsp;&nbsp;&nbsp;</label>
                            <div class="col-md-10" style="padding: 20px 20px 20px 10px;">
                                <button type="submit" class="btn btn-danger" id="btn_back_to_citizen" name="btn_back_to_citizen">Back To Citizen</button>
                                <?php
                                if(!empty($memo) && $everyDocVerified==true)
                                {
                                    // Memo Generated and document verified then forward
                                    ?>
                                    <button type="submit" class="btn btn-success" id="btn_forward" name="btn_forward">Forward</button>
                                    <?php
                                }
                                if(empty($memo) && $everyDocVerified==true)
                                {
                                    // If Memo not generated but doc verified then generate memo
                                    ?>
                                    <button type="submit" class="btn btn-success" id="btn_generate_memo" name="btn_generate_memo">Generate Memo</button>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php 
                    }
                    ?>
                    </form>
                </div>
            </div>
        </form>
    <!-- End page content-->
</div>
<?=$this->include('layout_vertical/footer');?>

<script>
function PopupCenter(url, title, w, h) {  
    // Fixes dual-screen position                         Most browsers      Firefox  
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;  
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;  
              
    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;  
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;  
              
    var left = ((width / 2) - (w / 2)) + dualScreenLeft;  
    var top = ((height / 2) - (h / 2)) + dualScreenTop;  
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);  
  
    // Puts focus on the newWindow  
    if (window.focus) {  
        //newWindow.focus();  
    }  
} 
<?php
// return $this->response->redirect();
if(isset($_GET["memo_id"]) && $_GET["memo_id"]!=NULL){
    ?>
    
    PopupCenter('<?=base_url('citizenPaymentReceipt/da_eng_memo_receipt/'.md5($ulb_mstr_id).'/'.($_GET["memo_id"]));?>', 'Self Assessment Memo', 1024, 786);
    
    <?php
}
?>
</script>