<?= $this->include("layout_mobi/header"); ?>

<style type="text/css">
    .row {
        font-weight: bold;
        color: #000000;
    }

    .label {
        font-weight: bold;
        color: #000000;
    }

    .table td {
        font-weight: bold;
        color: #000000;
        font-size: 12px;
    }

    .bolder {
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        #pymnt {
            margin-bottom: 20px;
        }
    }
</style>
<script src="<?= base_url(); ?>/public/assets/otherJs/validation.js"></script>

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">

    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="post">
            <?php if (isset($validation)) { ?>
                <?= $validation->listErrors(); ?>
            <?php } ?>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Trade License Status <span class = "pull-right btn btn-info btn_wait_load" onclick="back()"><i class='fa fa-arrow-left' aria-hidden='true'></i>Back</span> </h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <span style="font-weight: bold; font-size: 14px; color: #bb4b0a;"> Your Application No. is <span style="color: #179a07;"><?php echo $licencee['application_no']; ?></span>. Application Status: <?php echo $application_status; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Apply New License</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Application Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['application_type']; ?>
                        </div>

                        <label class="col-md-3">License No </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo empty($licencee['license_no']) ? 'N/A' : $licencee['license_no']; ?>
                        </div>

                        <?php
                        // License Approved
                        //print_var($licencee);
                        if ($licencee['pending_status'] == 5) {
                            $yetToBeExpire = round((strtotime($licencee['valid_upto']) - time()) / (60 * 60 * 24));
                            if ($yetToBeExpire >= 0)
                                $valid_till = "($yetToBeExpire days)";
                            else
                                $valid_till = '<span class="text text-danger">(Expired)</span>';
                        ?>
                            <label class="col-md-3">Valid Upto </label>
                            <div class="col-md-3 pad-btm">
                                <?php echo $licencee['valid_upto']; ?> <?php echo $valid_till; ?>
                            </div>
                        <?php
                        }
                        ?>



                        <label class="col-md-3">Firm Type </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['firm_type']; ?>
                        </div>


                    </div>
                    <div class="row">
                        <label class="col-md-3">Type of Ownership of Business Premises</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['ownership_type']; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Business Description :<span class="text-danger"></span></label>
                        <div class="col-md-3 pad-btm text-bold">
                            <?= isset($licencee['brife_desp_firm']) ? $licencee['brife_desp_firm'] : 'N/A'; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Details</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Holding / SAF No. </label>
                        <div class="col-md-3 pad-btm"><?php echo $holding_no ? $holding_no : "N/A"; ?>
                        </div>

                        <label class="col-md-3">Ward No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $ward_no; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Firm Name</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['firm_name']; ?>
                        </div>
                        <label class="col-md-3">Total Area(in Sq. Ft) </label>
                        <?php echo $licencee['area_in_sqft']; ?>
                        <input type="hidden" id="area_in_sqft" value="<?= $licencee['area_in_sqft'] ? $licencee['area_in_sqft'] : ""; ?>">
                    </div>
                    <div class="row">
                        <label class="col-md-3">Firm Establishment Date</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['establishment_date']; ?>
                                <?php
                                if($application_type['id']==1)
                                {
                                    ?>
                                    <input type="hidden" id="firm_date" value="<?= $holding['establishment_date'] ? date('d-m-Y', strtotime($holding['establishment_date'])) : date('d-m-Y'); ?>">                                    
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="hidden" id="firm_date" value="<?= $licencee['valid_from'] ? date('d-m-Y', strtotime($licencee['valid_from'])) : date('d-m-Y'); ?>">
                                        <?php
                                }
                                ?> 
                                <input type="hidden" id="notice_date" value="<?= isset($notice_date) ? date('d-m-Y', strtotime($notice_date)) : ""; ?>">
                        </div>
                        <label class="col-md-3">Licence For</label>
                        <div class="col-md-3 pad-btm">
                            <?php if (isset($licencee['licence_for_years'])) {
                                echo $licencee['licence_for_years'] . ' YEARS';
                            } ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Address</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['address']; ?>
                        </div>
                        <label class="col-md-3">Landmark</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['landmark'] ? $licencee['landmark'] : "N/A"; ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Pin</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $licencee['pin_code']; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Firm Owner Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Owner Name </th>
                                            <th>Guardian Name</th>
                                            <th>Mobile No </th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <?php

                                    if (isset($firm_owner)) {
                                        if (!empty($firm_owner)) {
                                            foreach ($firm_owner as  $value) {
                                    ?>
                                                <tr>
                                                    <td><?= $value["owner_name"]; ?></td>
                                                    <td><?= $value["guardian_name"] ?></td>
                                                    <td><?= $value["mobile"] ?></td>
                                                    <td><?= $value["address"] ?></td>
                                                </tr>
                                    <?php }
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Items of Trade</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Trade Code</th>
                                            <th>Trade Item</th>
                                        </tr>
                                    </thead>

                                    <?php

                                    if (isset($trade_items)) {
                                        if (!empty($trade_items)) {
                                            foreach ($trade_items as  $valueitem) {
                                    ?>
                                                <tr>
                                                    <td><?= $valueitem["trade_code"] ? $valueitem["trade_code"] : "N/A" ?></td>

                                                    <td><?= $valueitem["trade_item"] ? $valueitem["trade_item"] : "N/A" ?></td>
                                                </tr>
                                    <?php }
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Transaction Details</h3>
                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered text-sm">
                                    <thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>Transaction Date</th>
                                            <th>Transaction No.</th>
                                            <th>Mode</th>
                                            <th>Amount</th>
                                            <th>View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($trans_detail)) {
                                            if (!empty($trans_detail)) {
                                                foreach ($trans_detail as  $valuetran) {
                                        ?>
                                                    <tr>
                                                        <td><?= $valuetran["transaction_date"] ?></td>

                                                        <td><?= $valuetran["transaction_no"] ?></td>
                                                        <td><?= $valuetran["payment_mode"] ?></td>

                                                        <td><?= $valuetran["paid_amount"] ?></td>
                                                        <td><a href="<?php echo base_url('mobitradeapplylicence/view_transaction_receipt/' . md5($valuetran['related_id']) . '/' . md5($valuetran['id'])); ?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View</a></td>
                                                    </tr>
                                        <?php }
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <?php
            if ($licencee['pending_status'] == 5) {
            ?>
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <?php
                                # 4 Surrender
                                if ($licencee['application_type_id'] == 4) {
                                ?>
                                    <a href="<?= base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/' . md5(3) . '/' . md5($licencee['id'])); ?>" class="btn btn-primary">Apply Ammendment</a>
                                <?php
                                }

                                if ($yetToBeExpire <= 30) {
                                ?>
                                    <a href="<?= base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/' . md5(2) . '/' . md5($licencee['id'])); ?>" class="btn btn-primary">Apply Renewal</a>

                                <?php
                                }

                                if ($yetToBeExpire >= 0 && $licencee['application_type_id'] != 4) {
                                ?>
                                    <a href="<?= base_url('tradeapplylicenceMobile/ApplyNewLicenseMobi/' . md5(4) . '/' . md5($licencee['id'])); ?>" class="btn btn-primary">Apply Surrender</a>
                                <?php
                                }

                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <?php 
            if ($licencee['application_type_id'] != 4) 
            {
                if ($licencee['pending_status'] != 5) 
                { ?>
                    <div class="panel panel-bordered panel-dark"><br>
                        <div class="panel-body" style="padding-bottom: 0px;">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <?php
                                    if($licencee['payment_status']!=0)
                                    {
                                    ?>
                                        <a href="<?php echo base_url('mobitradeapplylicence/view_provisional/' . md5($licencee['id'])); ?>" type="button" id="customer_view_detail" class="btn btn-primary" style="color:white;">View Provisional</a>
                                    <?php
                                    }
                                    if($licencee['payment_status']==0)
                                    {
                                        ?>
                                        <button data-target="#demo-lg-modalss" data-toggle="modal" class="btn btn-warning" type="button" onclick="denial_carcge()">Pay Now</button>
                                        <?php
                                    }?>
                                </div>
                            </div>
                        </div><br>
                    </div>
                <?php  
                }
                
            } ?>
        </form>
    </div>
    <!--End page content-->
</div>
<div id="demo-lg-modalss" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title" id="myLargeModalLabel">Large modal</h4>
            </div>
            <div class="modal-body">
                <!-- check bounce payment -->
                <div class="panel  panel-dark" id="model" style="display: '';">
                    <div class="panel-body">
                        <div class="row">
                            <form id = 'payment' name = 'payment' action="<?php echo base_url(); ?>/Trade_report/check_bounce_payment/<?= md5($licencee['id']) ?>" method='post'>
                                <?php
                                //print_var($application_type);
                                if ($application_type["id"] <> 4) {
                                ?>
                                    <div class="panel panel-bordered panel-dark">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Licence Required for the Year</h3>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            # Renewal
                                            if ($application_type["id"] == 2) {
                                            ?>
                                                <div class="row">
                                                    <label class="col-md-2">License Expire</label>
                                                    <div class="col-md-3 pad-btm"> <b> <?= $licencee['valid_from']; ?> </b> </div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="row">
                                                <label class="col-md-2">License For<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <?php

                                                    if ($application_type["id"] == 3) {
                                                    ?>
                                                        <select id="licence_for" name="licence_for" class="form-control" onclick="show_charge()" required>
                                                            <option value="1">1 Year</option>
                                                        </select>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <select id="licence_for" name="licence_for" class="form-control" onchange="show_charge()" required>
                                                            <option value="">--Select--</option>
                                                            <option value="1">1 Year</option>
                                                            <option value="2">2 Year</option>
                                                            <option value="3">3 Year</option>
                                                            <option value="4">4 Year</option>
                                                            <option value="5">5 Year</option>
                                                            <option value="6">6 Year</option>
                                                            <option value="7">7 Year</option>
                                                            <option value="8">8 Year</option>
                                                            <option value="9">9 Year</option>
                                                            <option value="10">10 Year</option>
                                                        </select>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <label class="col-md-2">Charge Applied<span class="text-danger">*</span></label>

                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="charge" disabled="disabled" class="form-control" value="<?php echo $rate ?? 0; ?>" onkeypress="return isNum(event);" required/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <label class="col-md-2">Penalty<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="penalty" disabled="disabled" class="form-control" value="<?php echo $penalty ?? 0; ?>" onkeypress="return isNum(event);" required/>
                                                </div>

                                                <label class="col-md-2">Denial Amount<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="denialAmnt" disabled="disabled" class="form-control" value="0" onkeypress="return isNum(event);" required />
                                                </div>
                                            </div>


                                            <div class="row">

                                                <label class="col-md-2">Total Charge<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" id="total_charge" disabled="disabled" class="form-control" value="<?php echo $total_charge ?? 0; ?>" onkeypress="return isNum(event);" min="299" required />
                                                </div>


                                                <label class="col-md-2">Payment Mode<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <select class="form-control" id="payment_mode" name="payment_mode" onchange="myFunction()"required>
                                                        <option value="">Choose...</option>
                                                        <option value="CASH">CASH</option>
                                                        <option value="CHEQUE">CHEQUE</option>
                                                        <option value="DEMAND DRAFT">DEMAND DRAFT</option>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row" id="chqno" style="display: none;">
                                                <label class="col-md-2">Cheque/DD Date<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="date" class="form-control" id="chq_date" name="chq_date" value="<?= date("Y-m-d") ?>" placeholder="Enter Cheque/DD Date" >
                                                </div>
                                                <label class="col-md-2">Cheque/DD No.<span class="text-danger">*</span></label>

                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control" id="chq_no" name="chq_no" value="" placeholder="Enter Cheque/DD No." >
                                                </div>
                                            </div>
                                            <div class="row" id="chqbank" style="display: none;">
                                                <label class="col-md-2">Bank Name<span class="text-danger">*</span></label>
                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control" id="bank_name" name="bank_name" value="" placeholder=" Enter Bank Name" >
                                                </div>
                                                <label class="col-md-2">Branch Name<span class="text-danger">*</span></label>

                                                <div class="col-md-3 pad-btm">
                                                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="" placeholder=" Enter Branch Name" >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                ?>

                                <div class="panel panel-bordered panel-dark">
                                    <div class="col-md-10" id="dd"></div>
                                    <div class="panel-body demo-nifty-btn text-center">
                                        <?php
                                        $onclick = '';
                                        if ($application_type['id'] != 4) // Surrender
                                        {
                                            $onclick = 'onclick="return confirmsubmit()"';
                                        }
                                        ?>
                                        <input type="hidden" name="apply_from" value="tc" />
                                        <button type="submit" id="btn_review" name="btn_review" <?= $onclick; ?> class="btn btn-primary">SUBMIT</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--END CONTENT CONTAINER-->\

<script>
    
    function back()
    {
        window.history.back();
    }
    
    function form_validate()
    { 
        $("#payment").validate({
            rules:{                         
                chq_date:{
                    required:true
                },                
                chq_no:{
                    required:true
                },                
                bank_name:{
                    required:true
                },                
                branch_name:{
                    required:true
                },                
                
                applyWith:{
                    required:true
                },                  
               
                licence_for:{
                    required:true
                }, 
                charge:{
                    required:true
                }, 
                total_charge:{
                    required:true
                }, 
                payment_mode:{
                    required:true
                },

            },
            messages:{  
                applyWith:{
                    required:"Please Select Apply With",
                },
                               
                chq_date:{
                    required:"Please Select date"  
                },                
                chq_no:{
                    required:"Please Enter Cheque/DD No."  
                },                
                bank_name:{
                    required:"Please Enter Bank Name"  
                },                
                branch_name:{
                    required:"Please Enter Branch Name"  
                },                
                licence_for:{
                    required:"Please Enter Licence For"  
                }, 

                payment_mode:{
                    required:"Please Enter Payment Mode"  
                },
                               
            }
        });
    } 

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

    function confirmsubmit()
    {   
        var p = document.getElementById('payment_mode').value;
        var brname = document.getElementById('branch_name').value;
        var bname = document.getElementById('bank_name').value;
        var chqno= document.getElementById('chq_no').value;
        var chqdate = document.getElementById('chq_date').value;
        var totalcharge = document.getElementById('total_charge').value;
        var deAmnt = document.getElementById('denialAmnt').value;
        var pen = document.getElementById('penalty').value;
        var char = document.getElementById('charge').value;
        var lfor = document.getElementById('licence_for').value;        
        $('#btn_review').hide();
        if((p=='CHEQUE'|| p=='DEMAND DRAFT') && (brname==''||bname==''||chqno==''||totalcharge==''))
        {
            $('#btn_review').show();
            alert('Enter All Filed');
            return false;
        }
        if((p==''|| totalcharge=='' ||deAmnt==''||pen==''||char==''||lfor==''))
        {
            $('#btn_review').show();
            alert('Enter All Filed');
            return false;
        }
        return true;
        
        var val =form_validate();
        //alert($("#payment").valid());
        alert(val);
        if($("#payment").valid())
        {
            var amt = $('#total_charge').val();
            var del=confirm("Are you sure you want to confirm Payment of Rs "+amt+"?");
            return del;
        }
        else
        {
            return false;
        }
        
    }
    document.ready(function(){
        $('#btn_review').click('on',function(){
            alert();
        });
    })
    function denial_carcge() {       
        var notice_date=$('#notice_date').val();   
        if (notice_date != "") 
        {
            $('#btn_review').hide();
            $.ajax({
                type: "POST",
                // url: '<?php echo base_url("Trade_report/dinial_charge"); ?>',
                url: '<?php echo base_url("TradeCitizen/dinial_charge"); ?>',
                dataType: "json",
                data: {
                    "notice_date": notice_date,                   
                    
                },

                success: function(data) {
                    //alert(JSON.parse(data)) ;
                    console.log(typeof(data));
                    console.log(data);
                    
                    if (data.response == true) 
                    {
                        console.log('inside true')
                        //var cal = data.rate * timefor;
                        $("#denialAmnt").val(data.amount);                      
                        $('#btn_review').show();
                    } 
                    
                }

            });
        }

    }

    // function show_charge() {
    //     var timefor = $("#licence_for").val();
    //     var str = $("#area_in_sqft").val();
    //     var edate = $("#firm_date").val();
    //     var noticedate = $("#noticedate").val();
    //     if (<?= $application_type['id'] ?> == 1) {
    //         if (edate > noticedate && noticedate != "") {
    //             $(".hideNotice").css("display", "none");
    //             $("#denialAmnt").val(0);
    //             alert("Notice date should not be smaller then Firm establishment date");
    //             $("#applyWith option:selected").prop("selected", false);
    //             $("#noticeNo").val("");
    //             $("#noticedate").val("");
    //             $("#owner_business_premises").val("");
    //         }
    //     }
    //     if (str != "" && timefor != "") {
    //         $('#btn_review').hide();
    //         $.ajax({
    //             type: "POST",
    //             url: '<?php echo base_url("tradeapplylicence/getcharge"); ?>',
    //             dataType: "json",
    //             data: {
    //                 "areasqft": str,
    //                 "applytypeid": <?= $application_type["id"] ?>,
    //                 "estdate": edate,
    //                 "licensefor": timefor,
    //                 "tobacco_status": 0,
    //                 apply_licence_id: <?= $licencee['id'] ?>
    //             },

    //             success: function(data) {
    //                 console.log(data);
    //                 // alert(data);
    //                 if (data.response == true) {
    //                     var cal = data.rate * timefor;
    //                     $("#charge").val(data.rate);
    //                     $("#penalty").val(data.penalty);
    //                     $("#total_charge").val(data.total_charge);
    //                     var ttlamnt = parseInt(data.total_charge) + parseInt($("#denialAmnt").val());
    //                     $("#total_charge").val(ttlamnt);
    //                     $('#btn_review').show();
    //                 } else {

    //                     $("#charge").val(0);
    //                     $("#penalty").val(0);
    //                     $("#total_charge").val(0);
    //                     $("#denialAmnt").val(0);

    //                 }
    //             }

    //         });
    //     }

    //     <?php
    //     if ($application_type["id"] == 2) {
    //     ?>
    //         var for_year = $('#licence_for').val();
    //         var valid_from = $('#firm_date').val();
    //         //alert(for_year);alert(valid_from); 
    //         $('#btn_review').display = 'none';
    //         $('#btn_review').hide();
    //         jQuery.ajax({
    //             type: "POST",
    //             url: '<?php echo base_url("TradeCitizen/re_day_diff"); ?>' + '/' + valid_from + '/' + for_year + '/' + 'ajax',
    //             dataType: "json",

    //             success: function(data) {
    //                 console.log(data);
    //                 if (parseInt(data.diff_day) < 0) {
    //                     $("#licence_for option:selected").prop("selected", false);
    //                     $("#charge").val('');
    //                     $("#penalty").val('');
    //                     $("#total_charge").val('');
    //                 }

    //                 $('#btn_review').show();

    //             }
    //         });
    //     <?php
    //     }
    //     ?>

    // }
    function show_charge() 
    {        
        var timefor = $("#licence_for").val();
        var str = $("#area_in_sqft").val();
        var edate = $("#firm_date").val();
        var noticedate = $("#noticedate").val();
        var temp = <?=$application_type["id"];?>;
        // alert($("#noticedate").val());
        if (<?= $application_type['id'] ?> == 1) 
        {
            if (edate > noticedate && noticedate != "") 
            {
                $(".hideNotice").css("display", "none");
                $("#denialAmnt").val(0);
                alert("Notice date should not be smaller then Firm establishment date");
                $("#applyWith option:selected").prop("selected", false);
                $("#noticeNo").val("");
                $("#noticedate").val("");
                $("#owner_business_premises").val("");
            }
        }
        if (str != "" && timefor != "") 
		{
            $('#btn_review').hide();
            $.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/getcharge"); ?>',
                dataType: "json",
                data: {
                    "areasqft": str,
                    "applytypeid": <?= $application_type["id"] ?>,
                    "estdate": edate,
                    "licensefor": timefor,
                    "tobacco_status": 0,
                    "nature_of_business":<?=("'". $licencee['nature_of_bussiness']."'"); ?>,
                    apply_licence_id: <?= $licencee['id'] ?>
                },

                success: function(data) {
                    console.log(data);
                    // alert(parseInt($("#denialAmnt").val()));
                    if (data.response == true) {
                        var cal = data.rate * timefor;
                        $("#charge").val(data.rate);
                        $("#penalty").val(data.penalty);
                        // $("#total_charge").val(data.total_charge);
                        $("#denialAmnt").val(data.arear_amount);
                        var ttlamnt = parseInt(data.total_charge);
                        $("#total_charge").val(ttlamnt);
                        $('#btn_review').show();
                    } else {

                        $("#charge").val(0);
                        $("#penalty").val(0);
                        $("#total_charge").val(0);
                        $("#denialAmnt").val(0);

                    }
                }

            });
        }

        <?php
        if ($application_type["id"] == 21) {
        ?>
            var for_year = $('#licence_for').val();
            var valid_from = $('#firm_date').val();
            //alert(for_year);alert(valid_from); 
            $('#btn_review').display = 'none';
            $('#btn_review').hide();
            jQuery.ajax({
                type: "POST",
                url: '<?php echo base_url("TradeCitizen/re_day_diff"); ?>' + '/' + valid_from + '/' + for_year + '/' + 'ajax',
                dataType: "json",

                success: function(data) {
                    console.log(data);
                    if (parseInt(data.diff_day) < 0) {
                        $("#licence_for option:selected").prop("selected", false);
                        $("#charge").val('');
                        $("#penalty").val('');
                        $("#total_charge").val('');
                    }

                    $('#btn_review').show();

                }
            });
        <?php
        }
        ?>

    }

    function myFunction() {
        var mode = document.getElementById("payment_mode").value;
        if (mode == 'CASH' || mode=='') {
            $('#chqno').hide();
            $('#chqbank').hide();
        } else {
            $('#chqno').show();
            $('#chqbank').show();
        }
    }
</script>
<?= $this->include('layout_mobi/footer'); ?>