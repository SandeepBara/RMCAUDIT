<?php
    $display='';
    if(isset($tc) && $tc)
        echo $this->include("layout_mobi/header");
    else
        echo $this->include("layout_vertical/header");
?>
<style type="text/css">
    .error{

        color: red;
    }
	.row{line-height:25px;}
</style>
<!--CONTENT CONTAINER-->
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <?php 
            if(isset($tc) && $tc)
            {
                $display="display:none;";
            }
        ?>
        <ol class="breadcrumb" style = "<?=$display;?>">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li><a href="<?php echo base_url('WaterViewConsumerDetails/index/'.$bid);?>" >Water Connection Details</a></li>
            <li class="active">Demand Generate</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">    

        <div><?php if(isset($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); }?></div>   
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <?php
                    if(isset($tc) && $tc)
                    {
                       ?>
                        <a href="#" class="btn btn-info pull-right panel-control" onclick="history.back();">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>Back
                        </a>
                       <?php
                    }
                ?>                
                <h3 class="panel-title">Owner Basic Details</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-2">
                        <b>Consumer No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['consumer_no']?$consumer_dtls['consumer_no']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Application No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['application_no']?$consumer_dtls['application_no']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Pipeline Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['pipeline_type']?$consumer_dtls['pipeline_type']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Property Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['property_type']?$consumer_dtls['property_type']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Connection Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['connection_type']?$consumer_dtls['connection_type']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Connection Through :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['connection_through']?$consumer_dtls['connection_through']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Category :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['category']?$consumer_dtls['category']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Area in Sqft :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['area_sqft']?$consumer_dtls['area_sqft']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <b>Owner Name :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['owner_name']?$consumer_dtls['owner_name']:"N/A"; ?>
                    </div>
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-3">
                        <b>Mobile No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['mobile_no']?$consumer_dtls['mobile_no']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 bolder">Consumer Connection Date<span class="text-danger"></span></label>
                    <div class="col-md-3 pad-btm">
                        <?php echo date('d-m-Y',strtotime($consumer_dtls['created_on']));?> 
                    </div>
                </div>
            </div>      
        </div>
        
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Consumer Due Details</h3>
            </div>
            <div class="panel-body">
                <?php 
                    if(isset($tc) and $tc )
                    {
                        ?>

                            <div class="row">
                                <label class="col-md-2 bolder">Due From</label>
                                <div class="col-md-3 pad-btm">
                                    <?php 

                                        echo date('F',strtotime($due_from)).' / '.date('Y',strtotime($due_from))
                                    ?>
                                </div>

                                <label class="col-md-2 bolder">Due Upto </label>
                                <div class="col-md-3 pad-btm">
                                    <?php echo date('F',strtotime($due_upto)).' / '.date('Y',strtotime($due_upto)) ?> 
                                </div>
                                
                            

                            </div>
                            

                            <div class="row">
                                <label class="col-md-2 bolder">Arrear Demand</label>
                                <div class="col-md-3 pad-btm"  style="color: red; font-weight: bold; font-size: 17px;">
                                    <?php echo $arr_due_amt; ?>
                                </div>

                                <label class="col-md-2 bolder">Current Demand </label>
                                <div class="col-md-3 pad-btm"  style="color: red; font-weight: bold; font-size: 17px;">
                                    <?php echo $curr_due_amt; ?> 
                                </div>

                                

                            </div>
                        <?php
                    }
                    else
                    {
                        ?>
                        <table class="table table-responsive" id="demo_dt_basic">
                            <tr>
                                <th>Demand Month</th>
                                <th>Amount</th>
                                <th>Penalty</th>
                                
                            </tr>

                            <?php
                            
                            foreach($due_details as $val)
                            {
                            // echo $val['demand_upto'];
                            // echo  $month=date('F',strtotime($val['demand_upto']));
                            ?>
                            <tr>
                                <td><?php echo $val['demand_month'];?></td>
                                <td><?php echo $val['current_amount']?></td>
                                <td><?php echo $val['penalty']?></td>
                                
                            </tr>

                            <?php    
                            }
                            ?>
                        </table>

                        <?php
                    }
                ?>
                
            </div>
        </div>
        
        <form method="post" id="demand_generate" enctype="multipart/form-data"> 
            
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Meter/Fixed Connection Details <?=$oneAvgBill?" <span class='text-danger'> Warnig Previous Already Average Billing</span>":""?></h3>
                </div>
                <div class="panel-body">
                    <?php
                        $functionName = "";
                        if(isset($consumer_dtls['property_type_id']) && $consumer_dtls['property_type_id']==3 && in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                        {
                            $functionName = "onchange=getaverageBillingRate(this.value,'".$last_demand_dtl['demand_upto']."')";
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        Last Demand Upto Date :
                                    </div>
                                    <div class="col-md-3">                                        
                                        <?= (isset($last_demand_dtl) && (!empty(trim($last_demand_dtl['demand_upto'])))) ? date('d-m-Y',strtotime($last_demand_dtl['demand_upto'])) : date('d-m-Y',strtotime($connection_dtls['connection_date']));?>
                                    </div>
                                    <div class="col-md-3">
                                        Avg Reading(Per/day)
                                    </div>
                                    <div class="col-md-3">
                                        <?=$connection_dtls['rate_per_month'];?>
                                    </div>

                                    <div class="col-md-3">
                                        Day Diff : <?=date('d-m-Y',strtotime($last_demand_dtl['demand_upto']))." : <span id ='rang'>". date("d-m-Y")."</span>";?>
                                    </div>
                                    <div class="col-md-3">  
                                        <span id="current_day_diff">
                                            <?= $arg['current_day_diff']??0;?>
                                        </span>                                      
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                        elseif(in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                        {
                            $functionName = "onchange=getaverageBillingRate(this.value,'".$last_demand_dtl['demand_upto']."')";
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        Period :
                                    </div>
                                    <div class="col-md-3">                                        
                                        <?= isset($last_demand_dtl) ? ( (!empty(trim($last_demand_dtl['demand_from']))? date('d-m-Y',strtotime($last_demand_dtl['demand_from'])) :date('d-m-Y',strtotime($connection_dtls['connection_date']))) ." : ".(!empty(trim($last_demand_dtl['demand_upto']))? date('d-m-Y',strtotime($last_demand_dtl['demand_upto'])) :date('d-m-Y',strtotime($connection_dtls['connection_date']))) ) :date('d-m-Y',strtotime($connection_dtls['connection_date']));?>
                                    </div>
                                    <div class="col-md-3">
                                         Last Consumtion Unit
                                    </div>
                                    <div class="col-md-3">
                                        <?= $last_reading - $getpreviousMeterReding;?>
                                    </div>
                                    <div class="col-md-3">
                                        Day Diff : <?=date('d-m-Y',strtotime($last_demand_dtl['demand_upto']))." : <span id ='rang'>". date("d-m-Y")."</span>";?>
                                    </div>
                                    <div class="col-md-3">  
                                        <span id="current_day_diff">
                                            <?= $arg['current_day_diff']??0;?>
                                        </span>                                      
                                    </div>
                                    <div class="col-md-3">
                                         Avg Reading(Per/day)
                                    </div>
                                    <div class="col-md-3">
                                        <?= $arg['arvg']??0;?>
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-3">
                                Connection Type
                            </div>
                            <div class="col-md-3">
                                <?php
                                    if(in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                                    {
                                        $connection_type = "Meter/Fixed";
                                    }
                                    elseif($connection_dtls['connection_type']==1)
                                    {
                                        $connection_type='Meter';
                                    }
                                    else if($connection_dtls['connection_type']==2)
                                    {
                                        $connection_type='Gallon';
                                    }   
                                    else
                                    {
                                        $connection_type='Fixed';
                                    }
                                ?>
                                <?php  echo $connection_type;?>
                            </div>
                            <div class="col-md-3">
                                <?=$connection_type;?> Connection Date
                            </div>
                            <div class="col-md-3">
                                <?php  echo date('d-m-Y',strtotime($connection_dtls['connection_date']));?>
                            </div>
                        </div>

                    </div>
                    <?php
                    if($connection_dtls['connection_type']!=3)
                    {
                        ?> 
                            <div class="row">
                                <div class="col-md-12"> 
                                    <div class="col-md-3">
                                        Meter No.
                                    </div>
                                    <div class="col-md-3">
                                        <div><?=$connection_dtls['meter_no'];?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        Last Meter Reading
                                    </div>
                                    <div class="col-md-3">
                                        <?php  echo isset($last_reading)?$last_reading:'N/A';?>
                                    </div>
                                    <div class="col-md-3">
                                        Final Meter Reading
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="final_meter_reading" id="final_meter_reading" class="form-control" onkeypress="return isNumDot(event);">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        Meter Image 
                                    </div>
                                    <div class="col-md-3">
                                        <input type="file" name="document" id="document" class="form-control" accept="pdf,jpg,png,jpeg" onchange="fileValidation()">
                                    </div>
                                    <div class="col-md-3">
                                        Demand Upto Date 
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="upto_date" id="upto_date" class="form-control" value="<?=date('Y-m-d');?>" required <?=$functionName;?>>
                                    </div>
                                </div>
                            </div>

                        <?php
                    }
                    else{
                        ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        Demand Upto Date 
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="upto_date" id="upto_date" class="form-control" value="<?=date('Y-m-d');?>" required>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                    ?>                     

                </div>
            </div>
            <div class="panel-body">
                <button type="submit" value="generatedemand" id ="generatedemand" name="generatedemand" class="btn btn-warning">Generate Demand</button>
            </div>

        </form>


     </div>
</div>
<!--End page content-->
<!--END CONTENT CONTAINER-->
<?php 
    if(isset($tc) && $tc)
        echo$this->include("layout_mobi/footer");
    else
        echo $this->include("layout_vertical/footer");
?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
    
        $(document).ready(function () 
        {
            $('#demand_generate').validate({ // initialize the plugin            

            rules: {
                
                    final_meter_reading: {
                    required:true,
                    number: true
                }

            },                    
            submitHandler: function(form) {            
                $('#generatedemand').attr('disabled','true');
                form.submit();
            }


            });
        });
        function getaverageBillingRate(upto_date,from_date)
        {
            var d1 = new Date(from_date);   
            var d2 = new Date(upto_date);   
                
            var diff = d2.getTime() - d1.getTime(); 
            var daydiff = diff / (1000 * 60 * 60 * 24); 
            var dd = d2.getDate()  ;
            dd = dd<=9?("0"+dd):dd;
            var mm = d2.getMonth()+1;
            mm = mm<=9?("0"+mm):mm;
            document.getElementById('rang').innerHTML= (dd)+"-"+(mm)+"-"+d2.getFullYear();
            document.getElementById('current_day_diff').innerHTML= daydiff;
        }
</script>
<script>

    $(document).ready(function (){

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
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Report",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5] }
            }]
        });

    });
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
</script>

    
<script type="text/javascript">
    function modelInfo(msg){
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 6000
        });
    }
    function modelDanger(msg){
        $.niftyNoty({
            type: 'danger',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 6000
        });
    }
    <?php 
        if($error=flashToast('error'))
        {
            echo "modelDanger('".$error."');";
        }
        if($error=flashToast('message'))
        {
            echo "modelInfo('".$error."');";
        }    
    ?>
    function fileValidation() 
    {
        var fileInput = document.getElementById('document');
            
        var filePath = fileInput.value;
        fileSize = document.getElementById('document').files[0].size;    
        if(fileSize > 1048576) { // 1MD = 1048576
            $("#document").val("");
            alert("Try to upload file less than 1MB!");
            return false;
        }
        var allowedExtensions =/(\.jpg|\.pdf|\.png|\.jpeg)$/i;
            
        if (!allowedExtensions.exec(filePath)) 
        {
            alert('Invalid file type');
            fileInput.value = '';
            return false;
        }    
        
    }
</script>
    
        