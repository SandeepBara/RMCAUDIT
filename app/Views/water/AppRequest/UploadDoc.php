<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#"> Water </a></li>
            <li><a href="<?=base_url('WaterConsumerRequest/ViewDtl/'.($id));?>">Dtl</a></li>
			<li class="active"><a href="#"> Upload Documents </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
	
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">            
            <div class="panel-heading">
                <h3 class="panel-title"> Request Details </h3>
            </div>
            <div class="panel-body">  
                <div class="row">
                    <label class="col-md-2 bolder">Request No.</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$request_dtl['request_no']??""; ?></b>
                    </div>
                    <label class="col-md-2 bolder">Request Type</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$request_dtl['request_type']??""; ?></b>
                    </div>                    
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Apply Date</label>
                    <div class="col-md-3 pad-btm">
                        <b><?=$request_dtl['apply_date']??""; ?></b>
                    </div>
                    <!-- <label class="col-md-2 bolder">Document</label>
                    <div class="col-md-3 pad-btm">
                        <?php
                            if ($request_dtl['doc_path1']??false)
                            {
                                $path = $request_dtl['doc_path'];
                                $extention = strtolower(explode('.', $path)[1]);
                                if ($extention=="pdf")
                                {
                                    ?>
                                        <a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$path;?>','xtf','900','700');"> 
                                            <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                        </a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$path;?>','xtf','900','700');">
                                            <img src='<?=base_url();?>/getImageLink.php?path=<?=$path;?>' class='img-lg' />
                                        </a>
                                    <?php
                                }
                                    
                            }
                            else
                            {
                                echo "<span class='text-danger text-bold'>Doc Not Uploaded</span>";
                            }
                        ?> 
                    </div> -->
                </div>                
            </div>
        </div> 

        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Upload Document</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-trans-dark text-dark">
                        <tr>
                            <th>#</th>                            
                            <th>Document Type</th>
                            <th>Document</th>
							<th>Verification Status</th>
                            <th>Upload</th>
                            <th style="width: 25%;">Document(s) Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i=0;	                        
                        $everyDocUploaded=true;
                        foreach($required_doc_list as $row)
                        {
                            $docs_name = implode(', ', array_map(function ($entry) {
                                return $entry['doc_name'];
                            }, $row));

                            $doc_for = implode(', ', array_map(function ($entry) {
                                return $entry['doc_for'];
                            }, $row));

                            $is_mandatory = implode(', ', array_map(function ($entry) {
                                return $entry['is_mandatory'];
                            }, $row));

                            $document_uploaded=[];
                            foreach($uploaded_doc_list as $rec)
                            {
                                foreach($row as $rec1)
                                if($rec["document_id"]==$rec1["id"])
                                {$document_uploaded=$rec;break;}
                            }
                            ?>
                            <tr>
                                <td><?=++$i;?></td>
                                <td><?=str_replace("_" ," ",$doc_for);?> <span class="text-danger" <?=$is_mandatory==0 ? 'style="display: none;"' : "" ;?>>*</span></td>
                                <td>
                                    <?php 
                                    if($document_uploaded)
                                    {
										# print_var($document_uploaded);exit;
                                        $extention = strtolower(explode('.',  $document_uploaded["document_path"])[1]);
                                        if ($extention=="pdf")
                                        {
                                            ?>
                                                <a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>','xtf','900','700');"> 
                                                    <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                    <br><span class="text text-primary"><?=$document_uploaded["document_name"];?></span>
                                                </a>
                                                
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <a onclick="myPopup('<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>','xtf','900','700');">
                                                    <img src='<?=base_url();?>/getImageLink.php?path=<?=$document_uploaded["document_path"];?>' class='img-lg' />
                                                    <br><span class="text text-primary"><?=$document_uploaded["document_name"];?></span>
                                                </a>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <span class="text text-danger text-bold">Not Uploaded</span>
                                        <?php
                                    }
                                    ?>
                                    
                                </td>
								<td>
                                    <?php 
                                    if($document_uploaded)
                                    {
										if($document_uploaded["verify_status"]==1)
										echo '<span class="text text-success text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$document_uploaded["remarks"].'">Verfied</span>';
										elseif($document_uploaded["verify_status"]==2){
                                            $everyDocUploaded=false;
                                            echo '<span class="text text-danger text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$document_uploaded["remarks"].'">Rejected</span>';
                                        }
										else
										echo '<span class="text text-warning text-bold" data-placement="top" data-toggle="tooltip" title="" data-original-title="'.$document_uploaded["remarks"].'">Pending</span>';
                                    }
                                    else
                                    { 
                                        $everyDocUploaded=false;
									    echo "<span class='text-danger text-bold'>Not Uploaded</span>";
									}
                                    ?>
                                    
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#btn_upload_modal<?=$i;?>">Click here to upload</button>
                                    <!-- Owner Doc Upload Modal -->
                                    <div class="modal fade" id="btn_upload_modal<?=$i;?>" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Upload Document</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="post" enctype="multipart/form-data">
                                                        <input type="hidden" name="other_doc" id="other_doc" value="<?=$row[0]["doc_for"];?>" />
                                                        <div class="table-responsive">
                                                            <form method="post" enctype="multipart/form-data">
                                                            <table class="table table-bordered text-sm" >
                                                                <tr>
                                                                    <td><b>Document Name</b></td>
                                                                    <td>:</td>
                                                                    <td>
                                                                        <?php
                                                                            if($row && !$row[0]["doc_name"]){
                                                                                    
                                                                                ?>
                                                                                <!-- value="<?=$row[0]["id"];?>" -->
                                                                                <input type="hidden" name="doc_mstr_id" id="doc_mstr_id"  >
                                                                                <input type="text" required name="other_doc_name" id="other_doc_name" >
                                                                                <?php
                                                                            }
                                                                            else{
                                                                                ?>
                                                                                <select class="form-control" name="doc_mstr_id" id="doc_mstr_id" required>
                                                                                    <option value="">Select</option>
                                                                                    <?php
                                                                                    foreach($row as $select)
                                                                                    {
                                                                                        ?>
                                                                                        <option value="<?=$select["id"];?>"><?=$select["doc_name"];?></option>
                                                                                        <?php
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                                <?php
                                                                            }
                                                                        ?>
                                                                    </td>
                                                                    <td><input type="file" name="upld_doc_path" id="upld_doc_path" class="form-control" accept=".pdf,.jpeg,.jpg,.png" required></td>
                                                                    <td><input type="submit" name="btn_upload" class="btn btn-success" value="Upload" /></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><?=$docs_name;?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>

		<?php 
		$payment_status = $request_dtl["payment_status"];
        if($fullDocUpload==true && $payment_status==1)
        {
            ?>
            <div class="panel panel-dark">
                <div class="panel-body">
                    <div class="col-sm-2 col-sm-offset-5">
                        <button type="button" class="btn btn-primary btn-rounded" data-toggle="modal" data-target="#forward_backward_model" onclick="setModel('Forward')"> Forward</button>                        
                    </div>
                </div>
            </div>
		    <?php 
        }
        else if($payment_status==0)
        {
            ?>
            <div class="panel panel-dark">
                <div class="panel-body">
                    <div class="alert alert-info">
                        <strong>Notice!</strong> Payment is not clear.
                    </div>
                </div>
            </div>
		    <?php 
        }
        ?>

        <!--  ================== -->
        <div id="forward_backward_model" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #25476a;">
                        <button type="button" class="close" style="color: white; font-size:30px;" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" style="color: white;"><span id='action_title'> </span> <?=$request_dtl['request_no'];?></h4>
                    </div>
                    <form id ="post_next_form" action="" method="post">
                        <div class="modal-body">                               
                            <div class="row">
                                <input type="hidden" class="form-control" id="action_type" name="action_type" value="<?=$request_dtl["request_type"];?>">
                                <input type="hidden" class="form-control" id="views" name="views" value="<?=$from??"outBox";?>">
                                <div class="col-md-2 has-success pad-btm">
                                    
                                </div>
                                <div class="col-md-8 has-success pad-btm">
                                    <label class="col-md-12 text-bold" for="timeslot3">Remarks</label>
                                    <textarea class="form-control" name="level_remarks" id="level_remarks" onkeypress="return isAlphaNum(event);"></textarea>
                                </div>                                    
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-primary btn-labeled" style="text-align:center;" id="action_btn" name="action_btn" value="Pay"/>
                                </div>                                    
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- =================== -->
	</div><!--End page content-->
</div><!--END CONTENT CONTAINER-->

	
<script type="text/javascript">
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }

    function setModel(str=''){
        $("#action_title").html(str);        
        $("#action_type").val(str);
        $("#action_btn").val(str);
        
    }

    $("document").ready(function(){
        $('#post_next_form').validate({
            rules: {                
                level_remarks: {
                    required: true,           
                },
            },

            submitHandler: function(form) {
                str = $("#action_type").val();
                if(confirm("Are sure want to make "+str.toLowerCase()+" ?")){
                    $("#action_btn").hide();
                    $("#loadingDiv").show()
                    return true;
                }
                else
                {
                    return false;
                }
            }
        });
    });
</script>
<?=$this->include('layout_vertical/footer');?>