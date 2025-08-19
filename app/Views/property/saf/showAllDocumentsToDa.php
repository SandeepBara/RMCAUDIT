<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
	<div id="page-head">
		<ol class="breadcrumb">
		<li><a href="#"><i class="demo-pli-home"></i></a></li>
		<li><a href="#">SAF</a></li>
		<li class="active">SAF All Documents</li>
		</ol>
	</div>
	<!--Page content-->
	<div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Saf Documents
                       
                    </h3>
                    
                </div>
                <div class="panel-body">

                  
                    <div class="row">
                        <label class="col-md-3">Does the property being assessed has any previous Holding Number? </label>
                        <div class="col-md-3 text-bold pad-btm">
                            <?=(isset($has_previous_holding_no))?($has_previous_holding_no=='t')?"Yes":"No":"N/A";?>
                        </div>
                        <?php
                        if($has_previous_holding_no=='t')
                        {
                            ?>
                            <label class="col-md-3">Previous Holding No.</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=($holding_no);?>
                            </div>
                            
                            <?php
                        }
                        ?>
                        
                    </div>
                    <hr />
                    <div id="has_prev_holding_dtl_hide_show" class="<?=(isset($has_previous_holding_no))?($has_previous_holding_no==0)?"hidden":"":"";?>">
                        <div class="row">
                            <label class="col-md-3">Is the Applicant tax payer for the mentioned holding? If owner(s) have been changed click No.</label>
                            <div class="col-md-3 text-bold pad-btm">
                                <?=(isset($is_owner_changed))?($is_owner_changed==1)?"YES":"NO":"N/A";?>
                            </div>
                            <div id="is_owner_changed_tran_property_hide_show" class="<?=(isset($is_owner_changed))?($is_owner_changed==0)?"hidden":"":"";?>">
                                <label class="col-md-3">Mode of transfer of property from previous Holding Owner</label>
                                <div class="col-md-3 text-bold pad-btm">
                                        <?=(isset($transfer_mode))?$transfer_mode:"N/A";?>
                                </div>
                            </div>
                        </div>
                        <hr />
                    </div>
                    

                <?= $this->include('common/basic_details_saf');?>
                </div>



            </div>
      
        
            
            
            
            
            

            <!--------prop doc------------>
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Documents</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;" id="documen">
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Document Name</th>
                                    <th>Document</th>
                                    <th>Status</th>
                                    <th>Verified On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- FOR SAF FORM AND APPLICANT IMAGE -->
                                <?php
                                $i=0;
                                $everyDocVerified=true;
                                foreach($uploaded_doc_list as $doc)
                                {
                                    if($doc['other_doc'] !='applicant_image' && $doc['other_doc'] !='saf_form'){
                                        continue;
                                    }
                                    $owner_document=(array)null;
                                    if(is_numeric($doc["saf_owner_dtl_id"]) && $doc["saf_owner_dtl_id"]!="")
                                    {
                                        $saf_owner_dtl_id=$doc["saf_owner_dtl_id"];
                                        // $owner_document = array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                        //     return ($var['id'] == $saf_owner_dtl_id);
                                        // })[0];
                                        //updated since getting error
                                        $owner_document = isset(array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                            return ($var['id'] == $saf_owner_dtl_id);
                                        })[0])?array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                            return ($var['id'] == $saf_owner_dtl_id);
                                        })[0]:null;
                                        //print_var($owner_document);
                                    }
                                    ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td>
                                            <?=$doc["doc_name"];?>
                                            <?php 
                                            if(isset($owner_document["owner_name"]))
                                            {
                                                ?>
                                                <br>
                                                <span class="text text-primary">(<?=$owner_document["owner_name"];?>)</span>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>" target="_blank">
                                                <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                            </a>
                                        </td>
                                        <td>
                                        
                                        </td>
                                        <td></td>
                                        
                                    </tr>
                                    <?php
                                }
                                ?>
                                <!-- FOR ALL DOCUMENTS EXCEPT SAF_FORM AND APPLICANT IMAGE -->
                                <?php
                                // $i=2;
                                $everyDocVerified=true;
                                foreach($uploaded_doc_list as $doc)
                                {
                                    if($doc['other_doc'] =='applicant_image' || $doc['other_doc'] =='saf_form'){
                                        continue;
                                    }
                                    $owner_document=(array)null;
                                    if(is_numeric($doc["saf_owner_dtl_id"]) && $doc["saf_owner_dtl_id"]!="")
                                    {
                                        $saf_owner_dtl_id=$doc["saf_owner_dtl_id"];
                                        // $owner_document = array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                        //     return ($var['id'] == $saf_owner_dtl_id);
                                        // })[0];
                                        //updated since getting error
                                        $owner_document = isset(array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                            return ($var['id'] == $saf_owner_dtl_id);
                                        })[0])?array_filter($saf_owner_detail, function ($var) use ($saf_owner_dtl_id) {
                                            return ($var['id'] == $saf_owner_dtl_id);
                                        })[0]:null;
                                        //print_var($owner_document);
                                    }
                                    ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td>
                                            <?=$doc["doc_name"];?>
                                            <?php 
                                            if(isset($owner_document["owner_name"]))
                                            {
                                                ?>
                                                <br>
                                                <span class="text text-primary">(<?=$owner_document["owner_name"];?>)</span>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?=base_url();?>/getImageLink.php?path=<?=$doc["doc_path"];?>" target="_blank">
                                                <img src="<?=base_url();?>/public/assets/img/pdf_logo.png" style="width: 40px; height: 40px;" />
                                            </a>
                                        </td>
                                        <td>
                                        <?php
                                            if($doc["verify_status"]==0)
                                            { 
                                                $everyDocVerified=false;
                                                echo "Pending";
                                            }elseif($doc["verify_status"]==1){
                                                echo "<span class='text-success'>Verified</span>";
                                            }else if($doc['verify_status']==2){
                                                $everyDocVerified=false; ?>
                                                <span class="text text-danger" data-toggle="tooltip" title="<?=$doc["remarks"];?>">Rejected</span>
                                                
                                           <?php }
                                                ?>
                                        </td>
                                        <td>
                                            <?php  if($doc['verified_on'] !=''){
                                                echo date('Y-m-d',strtotime($doc['verified_on']));
                                            } ?>
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