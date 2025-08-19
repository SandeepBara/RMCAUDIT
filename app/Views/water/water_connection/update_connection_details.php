<?=$this->include("layout_vertical/header");?>
<style type="text/css">
    .error{

        color: red;
    }
	
	.row{line-height:25px;}

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
                    <div class="col-sm-3">
                        <b>Consumer No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['consumer_no']?$consumer_dtls['consumer_no']:"N/A"; ?>
                    </div>
                    
                    <div class="col-sm-3">
                        <b>Application No. :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['application_no']?$consumer_dtls['application_no']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <b>Pipeline Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['pipeline_type']?$consumer_dtls['pipeline_type']:"N/A"; ?>
                    </div>
                    
                    <div class="col-sm-3">
                        <b>Property Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['property_type']?$consumer_dtls['property_type']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <b>Connection Type :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['connection_type']?$consumer_dtls['connection_type']:"N/A"; ?>
                    </div>
                    
                    <div class="col-sm-3">
                        <b>Connection Through :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['connection_through']?$consumer_dtls['connection_through']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <b>Category :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['category']?$consumer_dtls['category']:"N/A"; ?>
                    </div>
                    
                    <div class="col-sm-3">
                        <b>Area in Sqft :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['area_sqft']?$consumer_dtls['area_sqft']:"N/A"; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <b>Owner Name :</b>
                    </div>
                    <div class="col-sm-3">
                        <?=$consumer_dtls['owner_name']?$consumer_dtls['owner_name']:"N/A"; ?>
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
                             $metor_no = 'N/A';
                             $last_read = '0.00';
                            if($connection_dtls['connection_type']==1)
                            {
                                $connection_type='Meter';
                                $metor_no=$connection_dtls['meter_no'];
                                $last_read = $last_reading;
                             
                            }
                            else if($connection_dtls['connection_type']==2)
                            {
                                $connection_type='Gallon';
                                $metor_no=$connection_dtls['meter_no'];
                                $last_read = $last_reading;
                            }
                            else
                            {
                                $connection_type='Fixed';
                            }

                            echo $connection_type;
                            ?>
                        </div>
                        <?php
                        if($connection_type!='Fixed')
                        {
                            ?>
                                <div class="col-md-3">
                                    Meter No.
                                </div>
                                <div class="col-md-3">
                                    <strong><?=$metor_no;?></strong>
                                </div>
                                <div class="col-md-3">
                                    Last Reading.
                                </div>
                                <div class="col-md-3">
                                    <strong><?=$last_read;?></strong>
                                </div>
                                
                            <?php
                        }
                        ?>
                        <div class="col-md-3">
                            <?= $connection_type;?> Connection Date
                        </div>
                        <div class="col-md-3">
                            <?php echo date('d-m-Y',strtotime($connection_dtls['connection_date'])); ?>
                        </div>

                    </div>
                </div>
            </div>
	    </div>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Consumer Connection History</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <table class="table table-responsive table-split">
                        <thead>
                            <tr>
                                <th>Connection Type</th>
                                <th>Date Of Connection</th>
                                <th>Meter No</th>
                                <th>Initial Reading</th>
                                <th>Document</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach($AllConnectionDetails as $val)
                            {
                                $connection_type = "Fixed";
                                if(in_array($val['connection_type'],[1,2]) &&($val['meter_status']==0))
                                {
                                    $connection_type = "Meter/Fixed";
                                }
                                elseif(in_array($val['connection_type'],[1]))
                                {
                                    $connection_type = "Meter";
                                }
                                elseif(in_array($val['connection_type'],[2]))
                                {
                                    $connection_type = "Gallon";
                                }
                                ?>
                                <tr>
                                    <td><?=$connection_type;?></td>
                                    <td><?=$val['connection_date']?></td>
                                    <td><?=$val['meter_no']?></td>
                                    <td><?=$val['initial_reading']?></td>
                                    <td class="bolder">
                                        <?php
                                            $path = $val['meter_doc'];
                                            $extention = strtolower(explode('.', $path)[1]??null);
                                            if ($extention=="pdf")
                                            {
                                                ?>
                                                    <a href="<?=base_url();?>/getImageLink.php?path=<?='RANCHI/meter_image/'.$path;?>" target="_blank"> 
                                                        <img id="imageresource" src="<?=base_url();?>/public/assets/img/pdf_logo.png" class='img-lg' />
                                                    </a>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                    <a href="<?=base_url();?>/getImageLink.php?path=<?='RANCHI/meter_image/'.$path;?>" target="_blank">
                                                        <img src='<?=base_url();?>/getImageLink.php?path=<?='RANCHI/meter_image/'.$path;?>' class='img-lg' />
                                                    </a>
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
        <form method="post" enctype="multipart/form-data" id="connection_dtls">
            <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title">Update Connection Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div class="col-md-3">Connection Type</div>
                                <div class="col-md-3">
                                    <select name="connection_type" id="connection_type" class="form-control" onchange="show_meter(this.value);meter_to_meter(this.value);">
                                        <option value="">Select</option>
                                        <option value="1">Meter</option>
                                        <option value="2">Gallon</option>
                                        <option value="3">Fixed</option>
                                        <?=$connection_type!='Fixed'?"<option value='4'>Meter/Fixed</option>":""?>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">Connection Date</div>
                                <div class="col-md-3">
                                    <input type="date" name="connection_date" id="connection_date"class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div id="meter_div" style="display: none;" class="form-group">
                                    <div class="col-md-3">Meter No.</div>
                                    <div class="col-md-3">
                                        <input type="text" name="meter_no" id="meter_no" class="form-control" onkeypress="return isAlphaNumCommaSlash(event);">
                                    </div>
                                    <div class="col-md-3">Meter Doc</div>
                                    <div class="col-md-3">
                                        <input type="file" name="meter_doc" id="meter_doc" class="form-control">
                                    </div>
                                </div>
                            
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                <?php
                                    if($connection_dtls['connection_type']==1 or $connection_dtls['connection_type']==2)
                                    {
                                        ?>
                                            
                                            <div class="col-md-3">Old Meter Final Reading</div>
                                            <div class="col-md-3">
                                                <input type="text" name="final_meter_reading" id="final_meter_reading" class="form-control" onkeypress="return isNumDot(event);" >
                                            </div>
                                            <span class = "meter_to_meter initial_meter_reading" style="display: none;">
                                                <div class="col-md-3" >New Meter Initial Reading</div>
                                                <div class="col-md-3">
                                                    <input type="text" name="initial_meter_reading" id="initial_meter_reading" class="form-control" onkeypress="return isNumDot(event);" >
                                                </div>                                        
                                            </span>
                                            
                                        
                                        <?php
                                    }
                                    elseif($connection_dtls['connection_type']==3)
                                    {
                                        ?>
                                            
                                            <div class="col-md-3">Initial Meter Reading</div>
                                            <div class="col-md-3">
                                                <input type="text" name="initial_meter_reading" id="initial_meter_reading" class="form-control" onkeypress="return isNumDot(event);"  >
                                            </div>
                                                                            
                                        <?php
                                    }
                                ?>
                                </div>
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
<?=$this->include("layout_vertical/footer");?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    jQuery.validator.addMethod("numDot", function(value, element) 
    {
        return this.optional(element) || /^\d+(?:\.\d+)+$/i.test(value);
    }, "Letters only please (0-9.)");
    jQuery.validator.addMethod("alphaNumCommaSlash", function(value, element) {
        return this.optional(element) || /^[a-zA-Z0-9- ]+$/i.test(value);
    }, "Letters only please (a-z, A-Z, 0-9, -)");

    function isAlphaNumCheck(val){
        var regex = /^[a-z0-9]+$/i;
        if (!val.match(regex)) return false;
        return true;
    }
    function isAlphaNumCommaSlashCheck(val){
        var regex = /^[a-z\d\\/,\s]+$/i;
        if (!val.match(regex)) return false;
        return true;
    }
    function isNumDotCheck(val){
        var regex = /^[1-9]\d*(((,\d{3}){1})?(\.\d{1,8})?)$/;
        if (!val.match(regex)) return false;
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

    function isNumComma(key) {
                var keycode = (key.which) ? key.which : key.keyCode;
                if (!(keycode == 8 || keycode == 46) && (keycode < 48 || keycode > 57)) {
                    return false;
                }else {
                    var parts = key.srcElement.value.split('.');
                    if (parts.length > 1 && keycode == 46)
                        return false;
                    return true;
                }
    }

    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ( e.which != 47 && e.which != 45 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }




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
                initial_meter_reading:{
                    required: true,

                },

            },
            submitHandler: function(form) {
                if(confirm("Are sure want to submit?")){                    
                    $('#update_conn_type').hide(); 
                    return true;                   
                }
                else
                {    
                    $('#update_conn_type').show();                  
                    return false;
                }
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
     
    function meter_to_meter(str)
    {
        var conn_type=str;
        //alert(conn_type);

        if(conn_type!=3 || conn_type!=4)
        {
            <?php
            if($connection_dtls['connection_type']==1 || $connection_dtls['connection_type']==2)
            {
                ?>
                $(".meter_to_meter").show();
                if(conn_type==4)
                {
                   $(".initial_meter_reading").hide(); 
                }
                <?php
            }
            else
            {
                ?>
                $(".meter_to_meter").hide();
                <?php
            }
            ?>
        }
        else
        {
            $(".meter_to_meter").hide();
        }
       
    }

    function isNumDot(e)
    {
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode==46)
        {
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length==0) {
                return false;
            }
        }
        else
        {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) 
            {
                return false;
            }
        }
    }


    function modelInfo(msg)
    {
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

