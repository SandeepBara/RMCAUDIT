<?=$this->include("layout_mobi/header");?>
<style type="text/css">
    .error{

        color: red;
    }
</style>
<!--CONTENT CONTAINER-->
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 

<div id="content-container">
    <!--Page content-->
    <div id="page-content">    

        <div><?php if(isset($_SESSION['msg'])){ echo $_SESSION['msg']; unset($_SESSION['msg']); }?></div>   
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">            
                <a href="#" class="btn btn-info pull-right panel-control" onclick="history.back();">
					<i class="fa fa-arrow-left" aria-hidden="true"></i>Back
				</a>
                <!-- <a href="http://smartulb.co.in/RMCDMC/WaterViewConsumerMobile/consumer_demand_receipt/5f8a7deb15235a128fcd99ad6bfde11e/735b90b4568125ed6c3f678819b6e058" class="btn btn-dark pull-right">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>Back
                </a> -->
               
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
            </div>      
        </div>        
     

         <?php
              if($due_from!="")
              {


              ?>  
              <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title"> Demand Details</h3>
                </div>
                <div class="panel-body">   

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

                 
                </div>
            </div>
            <?php
                }
                else
                {
                   echo '<div class="panel panel-bordered" style="color:green; font-weight:bold; font-size:17px; text-align:center;">No Dues!!!</div>';
                }
            ?>   
        <form method="post" id="demand_generate" enctype="multipart/form-data"> 
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Consumer Connection Details</h3>
                </div>
                <div class="panel-body">
                    <?php
                        if(isset($consumer_dtls['property_type_id']) && $consumer_dtls['property_type_id']==3 && in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                        {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        Last Demand Upto Date :
                                    </div>
                                    <div class="col-md-3">                                        
                                        <b><?= (isset($last_demand_dtl) && (!empty(trim($last_demand_dtl['demand_upto'])))) ? date('d-m-Y',strtotime($last_demand_dtl['demand_upto'])) : date('d-m-Y',strtotime($connection_dtls['connection_date']));?></b>
                                    </div>
                                    <div class="col-md-3">
                                        Rate Per Month
                                    </div>
                                    <div class="col-md-3">
                                        <b><?=$connection_dtls['rate_per_month'];?></b>
                                    </div>
                                </div>

                            </div>
                            <?php
                        }
                        elseif(in_array($connection_dtls['connection_type'],[1,2]) &&($connection_dtls['meter_status']==0))
                        {
                            ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        Period :
                                    </div>
                                    <div class="col-md-3">                                        
                                        <b><?= isset($last_demand_dtl) ? ( (!empty(trim($last_demand_dtl['demand_from']))? date('d-m-Y',strtotime($last_demand_dtl['demand_from'])) :date('d-m-Y',strtotime($connection_dtls['connection_date']))) ." : ".(!empty(trim($last_demand_dtl['demand_upto']))? date('d-m-Y',strtotime($last_demand_dtl['demand_upto'])) :date('d-m-Y',strtotime($connection_dtls['connection_date']))) ) :date('d-m-Y',strtotime($connection_dtls['connection_date']));?></b>
                                    </div>
                                    <div class="col-md-3">
                                        Last Consumtion Unit
                                    </div>
                                    <div class="col-md-3">
                                        <b><?= $last_reading - $getpreviousMeterReding;?></b>
                                    </div>
                                    <div class="col-md-3">
                                        Day Diff : <?=date('d-m-Y',strtotime($last_demand_dtl['demand_upto']))." : ". date("d-m-Y");?>
                                    </div>
                                    <div class="col-md-3">                                        
                                        <b><?= $arg['current_day_diff']??0;?></b>
                                    </div>
                                    <div class="col-md-3">
                                        Avg Reading(Per/day)
                                    </div>
                                    <div class="col-md-3">
                                        <b><?= $arg['arvg']??0;?></b>
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
                                        $meter_no=$connection_dtls['meter_no'];
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
                                <b><?php  echo $connection_type;?></b>
                            </div>
                            <div class="col-md-3">
                                Connection Date
                            </div>
                            <div class="col-md-3">
                                <b><?php  echo date('d-m-Y',strtotime($connection_dtls['connection_date']));?></b>
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
                                    <div><b><?=$connection_dtls['meter_no'];?></b></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    Last Meter Reading
                                </div>
                                <div class="col-md-3">
                                    <div><b><?=$last_reading;?></b></div>
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
                                    <input type="file" name="document" id="document" class="form-control" accept="jpg,jpeg" onchange="fileValidation()">
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
<?=$this->include("layout_mobi/footer");?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script src = "https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

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

    $(document).ready(function () 
    { 
        $('#demand_generate').validate({ // initialize the plugin

        rules: {
            
                final_meter_reading: {
                    required:true,
                    number: true,
                },
                'document':{
                    required:true,
                },
        },
        submitHandler: function(form) {            
            $('#generatedemand').attr('disabled','true');
            form.submit();
        }



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
        var allowedExtensions =/(\.jpg|\.jpeg)$/i;
            
        if (!allowedExtensions.exec(filePath)) 
        {
            alert('Invalid file type');
            fileInput.value = '';
            return false;
        }    
        
    }
</script>
    