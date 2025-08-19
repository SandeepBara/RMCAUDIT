<?= $this->include('layout_vertical/header');?>

<style type="text/css">
    .error {
        color: red;
    }
</style>

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
           <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->

        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">Accounts</a></li>
        <li class="active">Update Payment Mode</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Collection Details</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="post" >
                            <div class="form-group">                                
                           		<div class="col-md-3">
	                                <label class="control-label" for="tran_no"><b>Transaction No.</b><span class="text-danger">*</span> </label>
	                                <input type="text" id="tran_no" name="tran_no" class="form-control" value="<?=(isset($_POST['tran_no']))?$_POST['tran_no']:'';?>" required>
                           		</div>

                                <div class="col-md-3">
	                                <label class="control-label" for="module"><b>Module</b><span class="text-danger">*</span> </label>
                                    <select name="module" id="module" class="form-control" onchnge ="cheang_header(this.values)"required>
                                        <option value="">--Select--</option>
                                        <option value="property" <?=isset($_POST['module']) && $_POST['module']=='property'? 'selected':'';?> > Property</option>
                                        <option value="saf" <?=isset($_POST['module']) && $_POST['module']=='saf'? 'selected':'';?> > Saf</option>
                                        <option value="gov_tr" <?=isset($_POST['module']) && $_POST['module']=='gov_tr'? 'selected':'';?> >Gov. Property</option>
                                        <option value="water" <?=isset($_POST['module']) && $_POST['module']=='water'? 'selected':'';?> >Water</option>
                                        <option value="trade" <?=isset($_POST['module']) && $_POST['module']=='trade'? 'selected':'';?> >Trade</option>
                                    </select>									
                            	</div>

                           		<div class="col-md-3">
                                    <label class="control-label" >&nbsp;</label>
                                    <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Result</h5>
                    </div>
                    <div class="panel-body">
                        
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>SI.No</th>
                                    <th>Employee<br/>Name</th>
                                    <th id="module_property">XXXXXXXXXXXX</th>
                                    <th>Transaction<br/>No.</th>
                                    <th>Date</th>
                                    <th>Collected<br/>Amount</th>
                                    <th>Payment<br/>Mode</th>
                                    <th>chk NO.</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    $i=0;
                                    foreach ($transaction as $value)
                                    {
                                        ?>
                                            <tr>
                                               
                                                <td><?=++$i;?></td>
                                                <td><?=$value['emp_name'];?></td>
                                                <td><?=$value['holding_no'];?></td>
                                                <td><?=$value['transaction_no'];?></td>
                                                <td><?=$value['transaction_date'];?></td>
                                                <td><?=$value['paid_amount'];?></td>
                                                <td><?=$value['payment_mode'];?><?="<script> var old_payment='".strtoupper($value['payment_mode'])."';  </script> "?></td>
                                                <td><?=$value['cheque_no'];?></td>

                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#from<?=$value["id"];?>">Update</button>
                                                        <!-- Owner Doc Upload Modal -->
														<div class="modal fade" id="from<?=$value["id"];?>" role="dialog">
															<div class="modal-dialog modal-lg">
																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																		<h4 class="modal-title">Payment Details</h4>
																	</div>
																	<div class="modal-body">
																		<form method="post" enctype="multipart/form-data" name="model_from1" id = "model_from1">
																			<input type="hidden" name="transaction_id" id="transaction_id" value="<?=$value["id"];?>" />
                                                                            <input type="hidden" name="type" id="type" value="<?=$value["transaction_type"];?>">
																			<div class="table-responsive">
																				<table class="table table-bordered text-sm" >
																					<tr>
																						<td><b>Name</b></td>
																						<td>:</td>
																						<td><?=$value["applicant_name"]??null?></td>
                                                                                        <?php
                                                                                            if($value["transaction_type"]=='gov_tr')
                                                                                            {
                                                                                                ?>
                                                                                                <td><b>Designation Name</b></td>
                                                                                                <td>:</td>
                                                                                                <td><?=$value["father_name"]??null?></td>
                                                                                                <?php
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                ?>
                                                                                                <td><b>Guardian Name</b></td>
                                                                                                <td>:</td>
                                                                                                <td><?=$value["father_name"]??null?></td>
                                                                                                <?php
                                                                                            }
                                                                                        ?>
																					</tr>
																					<tr>
																						<td><b>Mobile</b></td>
																						<td>:</td>
																						<td><?=$value["mobile_no"]??null?></td>
																						<td>Email Id</td>
																						<td>:</td>
																						<td><?=$value["email_id"]??null?></td>
																						
																					</tr>
																					<tr>
																						
																						<td>Trans No.</td>
																						<td>:</td>
																						<td>
                                                                                            <?=$value["transaction_no"]??null?>
                                                                                            <input type="hidden" name="transaction_no" value="<?=$value["transaction_no"]??null?>" />
                                                                                        </td>
																						<td><b>Payment Mode</b></td>
																						<td>:</td>
																						<td><?=$value["payment_mode"]??null?></td>
																					</tr>
                                                                                    <tr>
																						
																						<td></td>
																						<td></td>
																						<td></td>
																						<td><b>Amount</b></td>
																						<td>:</td>
																						<td><?=$value["paid_amount"]??null?></td>
																					</tr>
                                                                                    <tr>
																						<td><label for="payment_mode">Select</label></td>
																						<td>:</td>
																						<td ></td>
																						<td colspan="3" >
																							<select name="payment_mode" id="payment_mode" class="form-control" onchange="myFunction()" required>
                                                                                                <option value="">--select--</option>
                                                                                                <option value="CASH">CASH</option>
                                                                                                <option value="CHEQUE">CHEQUE</option>
                                                                                                <option value="DD">DD</option>
                                                                                            </select>
																						</td>                                                                                        
																					</tr>
                                                                                    <tr id="chqno" style="display: none;">
                                                                                        <td class="col-md-2">Cheque/DD Date<span class="text-danger">*</span></td>
                                                                                        <td>:</td>
                                                                                        <td class="col-md-3 pad-btm">
                                                                                            <input type="date" class="form-control" id="chq_date" name="chq_date" value="<?=date("Y-m-d")?>" min = "<?=date("Y-m-d")?>" placeholder="Enter Cheque/DD Date">
                                                                                        </td>
                                                                                        <td class="col-md-2">Cheque/DD No.<span class="text-danger">*</span></td>
                                                                                        <td>:</td>
                                                                                        <td class="col-md-3 pad-btm">
                                                                                            <input type="text" class="form-control" id="chq_no" name="chq_no" value="" placeholder="Enter Cheque/DD No." onkeypress="return isNum(event)" maxlength="15">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr id="chqbank" style="display: none;">
                                                                                        <td class="col-md-2">Bank Name<span class="text-danger">*</span></td>
                                                                                        <td>:</td>
                                                                                        <td class="col-md-3 pad-btm">
                                                                                            <input type="text" class="form-control" id="bank_name" name="bank_name" value="" placeholder=" Enter Bank Name" onkeypress="return isAlpha(event)">
                                                                                        </td>
                                                                                        <td class="col-md-2">Branch Name<span class="text-danger">*</span></td>
                                                                                        <td>:</td>
                                                                                        <td class="col-md-3 pad-btm">
                                                                                            <input type="text" class="form-control" id="branch_name" name="branch_name" value="" placeholder=" Enter Branch Name" onkeypress="return isAlpha(event)">
                                                                                        </td>
                                                                                    </tr>

																					<!-- <tr>
																						<td>Receipt Image</td>
																						<td>:</td>
																						<td colspan="2"><img/></td>
																						<td colspan="4">
																							<input type="file" name="receipt_image" id="receipt_image" class="form-control" accept=".png, .jpg, .jpeg, .pdf" required/>
																						</td>
																					</tr> -->
																					
																					<tr>
																						<td colspan="8" >
                                                                                            <textarea name="remarks" id="remarks" placeholder="Remarks" class="form-control" required onkeypress ='return isAlphaNum(event)'></textarea>
                                                                                        </td>
                                                                                        <td class="text-right">
																							<input type="submit" name="update" id="update" class="btn btn-success" value="UPDATE" />
																						</td>
																					</tr>

																				</table>
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														</div>
                                                </td>
                                                <?php   if($value['payment_mode']=='CHEQUE' || $value['payment_mode']=='DD' || $value['payment_mode']=='Cheque'){ ?>
                                                <td>
                                                    <button  type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#from2<?=$value["id"];?>">Update Cheque no.</button>
                                                        <!-- Owner Doc Upload Modal -->
														<div class="modal fade" id="from2<?=$value["id"];?>" role="dialog">
															<div class="modal-dialog modal-lg">
																<!-- Modal content-->
																<div class="modal-content">
																	<div class="modal-header">
																		<button type="button" class="close" data-dismiss="modal">&times;</button>
																		<h4 class="modal-title">Update Cheque/DD/NEFT No.</h4>
																	</div>
																	<div class="modal-body">
																		<form method="post" enctype="multipart/form-data" name="model_from2" id = "model_from2">
																			<input type="hidden" name="transaction_id" id="transaction_id" value="<?=$value["id"];?>" />
                                                                            <input type="hidden" name="type" id="type" value="<?=$value["transaction_type"];?>">
																			<div class="table-responsive">
																				<table class="table table-bordered text-sm" >
																					<tr>
																						<td><b>Name</b></td>
																						<td>:</td>
																						<td><?=$value["applicant_name"]??null?></td>
                                                                                        <?php
                                                                                            if($value["transaction_type"]=='gov_tr')
                                                                                            {
                                                                                                ?>
                                                                                                <td><b>Designation Name</b></td>
                                                                                                <td>:</td>
                                                                                                <td><?=$value["father_name"]??null?></td>
                                                                                                <?php
                                                                                            }
                                                                                            else
                                                                                            {
                                                                                                ?>
                                                                                                <td><b>Guardian Name</b></td>
                                                                                                <td>:</td>
                                                                                                <td><?=$value["father_name"]??null?></td>
                                                                                                <?php
                                                                                            }
                                                                                        ?>
																					</tr>
																					<tr>
																						<td><b>Mobile</b></td>
																						<td>:</td>
																						<td><?=$value["mobile_no"]??null?></td>
																						<td>Email Id</td>
																						<td>:</td>
																						<td><?=$value["email_id"]??null?></td>
																						
																					</tr>
																					<tr>
																						
																						<td>Trans No.</td>
																						<td>:</td>
																						<td>
                                                                                            <?=$value["transaction_no"]??null?>
                                                                                            <input type="hidden" name="transaction_no" value="<?=$value["transaction_no"]??null?>" />
                                                                                        </td>
																						<td><b>Payment Mode</b></td>
																						<td>:</td>
																						<td><?=$value["payment_mode"]??null?></td>
																					</tr>
                                                                                    <tr>
																						
																						<td></td>
																						<td></td>
																						<td></td>
																						<td><b>Amount</b></td>
																						<td>:</td>
																						<td><?=$value["paid_amount"]??null?></td>
																					</tr>
                                                                                    
                                                                                    

																					
																					<tr>
                                                                                        <td><?=$value['payment_mode']??"";?> No.</td>
                                                                                        <td>:</td>
																						<td>
                                                                                            <input hidden type="text" value="<?= $module_name ?>" name="module_name">
                                                                                            <input hidden type="text" name="cheque_tbl_id" value="<?= $value['cheque_tbl_id'] ?>">
                                                                                            <input name="cheque_dd_neft_no_value" id="cheque_dd_neft_no_value" value="<?php 
                                                                                            if(isset($value['payment_mode'])){
                                                                                                if($value['payment_mode']=='CHEQUE' || $value['payment_mode']=='DD' || $value['payment_mode']=='Cheque'){
                                                                                                    echo $value['cheque_no'];
                                                                                                }
                                                                                            } ?>" class="form-control" required onkeypress ='return isAlphaNum(event)'>
                                                                                        </td>
                                                                                        <td class="text-left"><?=$value['payment_mode']??"";?> Date</td>
                                                                                        <td>:</td>
                                                                                        <td>
                                                                                            <input type="date" name="cheque_date" value="<?= $value['cheque_date'] ?>" class="form-control" required>	
                                                                                        </td>
																					</tr>
                                                                                    <tr>
                                                                                        <td>Bank Name</td>
                                                                                        <td>:</td>
																						<td>
                                                                                            <input type="text" value="<?=$value['bank_name']?>" name="bank_name" class="form-control" required onkeypress ='return isAlphaNum(event)'>
                                                                                            
                                                                                        </td>
                                                                                        <td class="text-left">Branch name</td>
                                                                                        <td>:</td>
                                                                                        <td>
                                                                                            <input type="text" name="branch_name" value="<?= $value['branch_name'] ?>" class="form-control" required onkeypress ='return isAlphaNum(event)'>	
                                                                                        </td>
																					</tr>

																				</table>
																			</div>
                                                                            <div class="row text-center">
                                                                                <input type="submit" name="update_cheque_no" id="update_cheque_no" class="btn btn-success text-right" value="Save" />
                                                                            </div>
																		</form>
																	</div>
																</div>
															</div>
														</div>
                                                </td>
                                                <?php } ?>
                                                    
                                            </tr>
                                        <?php 
                                    }
                                
                                ?>
                            </tbody>
                        </table>
                            
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--===================================================-->
    <!--End page content-->
</div>
<!--===================================================-->
        <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>

<script type="text/javascript">
    $(document).ready(function(){

        // $('#update_cheque_button').click(function(){

        //     transaction_id = $('#transaction_id').val()
        //     p_module = $("#module").val();
        //     console.log(transaction_id)
        //     try {
        //     $.ajax({
        //         type: "POST",
        //         url: "<?= base_url('CashVerification/ajaxGetChequeNo'); ?>",
        //         dataType: "json",
        //         data: {
        //             "transaction_id": transaction_id,
        //             "module": p_module
        //         },
        //         beforeSend: function() {
        //             $("#btn_search").val("LOADING ...");
        //             $("#loadingDiv").show();
        //             console.log('beofore calling');
        //         },
        //         success: function(data) {
        //             $("#loadingDiv").hide();
        //             console.log("response ", data, " ",data.cheque_no)
        //             $('#cheque_dd_neft_no_value').val(data.cheque_no)

                  
        //         },
        //         error: function(jqXHR, textStatus, errorThrown) {
        //             console.log('error')
        //         }
        //     });
        // } catch (err) {
        //     alert(err.message);
        // }
        // })

        var module = $("#module").val();
        console.log(module);
        if(module=='property')
        {
            $('#module_property').html('Holding No.');
        }else if(module=='saf')
        {
            $('#module_property').html('Saf No.');

        }
        else if(module=='gov_tr')
        {
            $('#module_property').html('Holding');
        }
        else if(module=='water')
        {
            $('#module_property').html('App/Consumer No');
        }
        else if(module=='trade')
        {
            $('#module_property').html('App No.');
        }
        else
        {
            $('#module_property').html('XXXXXXXXXXXX');
        }
    });

    $('#btn_search').click(function(){
        var tran_date = $('#module').val();
        var tran_no = $('#tran_no').val();
        var process = true;
        if(tran_date==""){
            $("#module").css({"border-color":"red"});
            $("#tran_date").focus();
            process = false;
        }
        if(tran_no==""){
            $("#tran_no").css({"border-color":"red"});
            $("#tran_no").focus();
            process = false;
        }
        return process;
    });
    $("#module").change('on',function(){    
        var module = $("#module").val();
        console.log(module);
        if(module=='property')
        {
            $('#module_property').html('Holding No.');
        }else if(module=='saf')
        {
            $('#module_property').html('Saf No.');

        }
        else if(module=='gov_tr')
        {
            $('#module_property').html('Holding');
        }
        else if(module=='water')
        {
            $('#module_property').html('App/Consumer No');
        }
        else if(module=='trade')
        {
            $('#module_property').html('App No.');
        }
        else
        {
            $('#module_property').html('XXXXXXXXXXXX');
        }
            
    });

    function isAlpha(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
            return false;

        return true;
    }

    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }

    function isNumDot(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode==46){
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length==0) {
                return false;
            }
        }else{
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    }

    

    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isDateFormatYYYMMDD(value){
        var regex = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/;
        if (!regex.test(value) ) {
            return false;
        }else{
            return true;
        }
    }

    function myFunction() {
        var mode = document.getElementById("payment_mode").value;
        console.log(mode);        
        if(old_payment== mode)
        {
            alert("Can't Select Same Payment Mode");
            document.getElementById("payment_mode").value='';
        }
        if (mode == 'CASH' || mode =='')
        {
            $('#chqno').hide(); 
            $('#chqbank').hide();
        }
        else
        {
            $('#chqno').show(); 
            $('#chqbank').show();
        }
    }   

    $("#model_from1").validate({
        rules:{   
            chq_date:{
                required:true,                
            }  ,
            chq_no:{
                required:true
            },
            bank_name:{
                required:true
            },
            branch_name:{
                required:true,
            },
            payment_mode:{
                required:true
            },
            remarks:{
                required:true,
                minlength:10,                   
            },              
            

        },
        
        
    });
   
    $('#update').click(function(){
        var payment_mode = $('#payment_mode').val();
        // var receipt_image = $('#receipt_image').val();
        var remarks = $('#remarks').val();
        console.log(payment_mode);        
        var process = true;        
        if(payment_mode=="")
        {
            $("#payment_mode").css({"border-color":"red"});
            $("#payment_mode").focus();
            process = false;
        }
        if(remarks==""){
            $("#remarks").css({"border-color":"red"});
            $("#remarks").focus();
            process = false;
        }
        if(payment_mode=="CHEQUE" || payment_mode=="DD")
        {
            var chq_date = $("#chq_date").val();
            var chq_no = $("#chq_no").val();
            var bank_name = $("#bank_name").val();
            var branch_name = $("#branch_name").val();           
            
        }
        
        return process;
    });
</script>
