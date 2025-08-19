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

        <div style="color: red; text-align: center; font-size: 16px;"><?php if(isset($error)){ echo $error; unset($error); }?></div>   
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
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
<div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Connection Details</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-3">
                            Connection Type
                        </div>
                        <div class="col-md-3">
                            <?php 
                            if($connection_dtls['connection_type']==1)
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

                            echo $connection_type;
                            ?>
                        </div>
                        <div class="col-md-3">
                            Connection Date
                        </div>
                        <div class="col-md-3">
                            <?php echo date('d-m-Y',strtotime($connection_dtls['connection_date'])); ?>
                        </div>

                    </div>
                    
                </div>
            </div>
</div>
    <form method="post" enctype="multipart/form-data" id="connection_dtls">
      <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Update Connection Details</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="col-md-3">Connection Type</div>
                        <div class="col-md-3">
                            <select name="connection_type" id="connection_type" class="form-control" onchange="show_meter(this.value)">
                                <option value="">Select</option>
                                <option value="1">Meter</option>
                                <option value="2">Gallon</option>
                                <option value="3">Fixed</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">Connection Date</div>
                        <div class="col-md-3">
                            <input type="date" name="connection_date" id="connection_date"class="form-control">
                        </div>
                        
                        <div id="meter_div" style="display: none;">

                        <div class="col-md-3">Meter No.</div>
                        <div class="col-md-3">
                            <input type="text" name="meter_no" id="meter_no" class="form-control">
                        </div>
                        
                        <div class="col-md-3">Meter Doc</div>
                        <div class="col-md-3">
                            <input type="file" name="meter_doc" id="meter_doc" class="form-control">
                        </div>
                        
                        </div>

                        <?php
                        if($connection_dtls['connection_type']==1 or $connection_dtls['connection_type']==2)
                        {
                            
                        
                        ?>
                       <div class="col-md-3">Final Meter Reading</div>
                        <div class="col-md-3">
                            <input type="text" name="final_meter_reading" id="final_meter_reading" class="form-control" >
                        </div>
                       
                       <?php
                        }
                       ?>
                      


                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" name="update_conn_type" id="update_conn_type" class="btn btn-success">
                    </div>
                </div>

            </div>
      </div>
</form>
<!--
<div class="row">
    <div class="col-md-12">
      
        <a href="<?php echo base_url('WaterViewConsumerMobile/view/'.md5($consumer_dtls['id']));?>" class="btn btn-info">SKIP</a>
        
    </div>
</div>
-->

<!--// current connection type details will be shown so as to change it or not by citizen

// is meter not ok then consumer connection table has enty with connection type 3 taht is fixed so in that period fixed calculation will be done
 -->

            </div>
        </div>
        <!--End page content-->
        <!--END CONTENT CONTAINER-->
        <?=$this->include("layout_mobi/footer");?>
        <script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


        <script>

            $(document).ready(function () 
            {

                $('#connection_dtls').validate({ // initialize the plugin


                    rules: {
                        connection_type: {
                            required: true,

                        },
                        connection_date: {
                            required: true,

                        },
                        meter_no: {
                            required: true,

                        },
                        meter_doc: {
                            required: true,

                        },
                        final_meter_reading: {
                            required: true,

                        },

                    }


                });

            });
        </script>

     

        <script type="text/javascript">

              function show_meter(str)
              {
                    var conn_type=str;
                    //alert(conn_type);

                    if(conn_type!=3)
                    {
                        $("#meter_div").show();
                    }
                    else
                    {
                        $("#meter_div").hide();
                    }

              }
              

              function isNumDot(e)
              {
                var charCode = (e.which) ? e.which : e.keyCode;
                if(charCode==46){
                    var txt = e.target.value;
                    if ((txt.indexOf(".") > -1) || txt.length==0) {
                        return false;
                    }
                }
                else{
                    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                        return false;
                    }
                }
              }

        
            function modelInfo(msg){
            $.niftyNoty({
                type: 'info',
                icon : 'pli-exclamation icon-2x',
                message : msg,
                container : 'floating',
                timer : 5000
                });
            }
            <?php 
            if($error=flashToast('error'))
            {
                echo "modelInfo('".$error."');";
            }
            ?>
  


        </script>

