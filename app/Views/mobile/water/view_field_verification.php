<style >
.error
{
    color: red;
}
</style>
<?=$this->include("layout_mobi/header");?>
<script type="text/javascript">
  function OperateDropDown(radio, control, hidden) {

        /*alert(radio);
        alert(control);
        alert(hidden);*/



        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;
        //alert(hid_val);
        //alert(rdo.value);
        //alert(ctrl);

        if (rdo.value == "1") {
            var opt = ctrl.options;
            var pos = 0;
            for (var j = 0; j < opt.length; j++) {
                if (opt[j].value == hid_val) {
                    pos = j;
                    break;
                }
            }
            ctrl.selectedIndex = pos;
            ctrl.disabled = true;
        }
        else {
            ctrl.selectedIndex = 0;
            ctrl.disabled = false;
        }
    }


    function OperateTexBox(radio, control, hidden) {

        /*alert(radio);
        alert(control);
        alert(hidden);*/

     
        var rdo = document.getElementById(radio.trim());
        var ctrl = document.getElementById(control.trim());
        var hid = document.getElementById(hidden.trim());
        var hid_val = hid.value;

       /* alert(rdo.value);
        alert(control);
        alert(hid.value);*/

        
        if (rdo.value == "1") {
            ctrl.value = hid_val;
        

            ctrl.readOnly = true;
        }
        else {
            ctrl.value = "";
            ctrl.readOnly = false;
        }


    }

  
  
</script>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">       
        <div class="panel">
        <div class="panel-heading btn-info"><strong>View Site Inspection Details</strong></div>

        <div class="row-fluid">
        
            <form action="<?php echo base_url('WaterFieldVerification/save_site_inspetion');?>" id="form_tc_verification" name="FORMNAME1" method="post">
              

                <div class="span12" style="margin-left: 0px;">
                    <div class="span2"><b>Application No.: </b> <?=$connection_dtls["application_no"];?></div>
                   
                         
                <input type="hidden" name="water_conn_id" id="water_conn_id" value="<?php echo $connection_dtls['id'];?>">
                 
                    <div class="span2" style="margin-left: 0px;">
                        <b>Applied Date : </b> <?=date('d-m-Y',strtotime($connection_dtls['apply_date']));?>
                    </div>

                   
                </div>
               
                <div class="clr"></div>
                
                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Ward No.</b></div>
                         <div class="span6" style="margin-left: 0px;">
                            <?php echo $ward_no;?>
                              <input type="hidden" name="ward_mstr_id_v" id="ward_mstr_id_v" value="<?php echo $connection_dtls['ward_id']; ?>">

                                <input type="hidden" name="corr_ward_mstr_id_t" id="corr_ward_mstr_id_t" value="<?php echo $connection_dtls['corr_ward_mstr_id_t']; ?>">

                        </div>
                    </div>


                <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Property Type</b></div>
                         <div class="span6" style="margin-left: 0px;">
                             <?php echo $property_type;?>
                               <input type="hidden" name="property_type_id_v" id="property_type_id_v" value="<?php echo $connection_dtls['property_type_id_v']; ?>">

                                <input type="hidden" name="corr_ward_mstr_id_t" id="corr_ward_mstr_id_t" value="<?php echo $connection_dtls['corr_property_type_t']; ?>">
                                


                        </div>
                         <div class="span6" style="margin-left: 0px;">
                             <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Pipeline Type</b></div>
                              <?php echo $pipeline_type;?>

                             <input type="hidden" name="pipeline_type_id_v" id="pipeline_type_id_v" value="<?php echo $connection_dtls['pipeline_type_id_v']; ?>">

                                <input type="hidden" name="corr_pipeline_type_t" id="corr_pipeline_type_t" value="<?php echo $connection_dtls['corr_pipeline_type_t']; ?>">

                        </div>


                      <div class="span6" style="margin-left: 0px;">
                      </div>
                        <div style="clear: both"></div>
                  </div>
              </div>


              <div class="span12" style="margin-left: 0px;">
                    <div class="panel">
                        <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Connection Type</b></div>
                         <div class="span6" style="margin-left: 0px;">
                             <?php echo $connection_type;?>
                           
                             <input type="hidden" name="corr_connection_type_v" id="corr_connection_type_v" value="<?php echo $connection_dtls['corr_connection_type_v']; ?>">

                                <input type="hidden" name="corr_connection_type_t" id="corr_connection_type_t" value="<?php echo $connection_dtls['corr_connection_type_t']; ?>">


                        </div>
                         <div class="span6" style="margin-left: 0px;">
                             <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Connection Through</b></div>
                              <?php echo $connection_through;?>
                                 <input type="hidden" name="connection_through_v" id="connection_through_v" value="<?php echo $connection_dtls['connection_through_v']; ?>">

                                <input type="hidden" name="corr_connection_through" id="corr_connection_through" value="<?php echo $connection_dtls['corr_connection_through']; ?>">



                        </div>


                      <div class="span6" style="margin-left: 0px;">
                      </div>
                        <div style="clear: both"></div>
                  </div>
              </div>




                <div class="clr"></div>
       
                
                <div class="span12">
                      <div class="panel-heading" style="background:#0066FF; color:#fff;"><b>Category</b></div>

                    <div class="span6">
                        
                        
                         <?php echo $category;?>
                         
                              <input type="hidden" name="corr_category_v" id="corr_category_v" value="<?php echo $connection_dtls['corr_category_v']; ?>">

                                <input type="hidden" name="corr_category" id="corr_category" value="<?php echo $connection_dtls['corr_category']; ?>">


                    </div>
                </div>

                <div class="span12">

                     <div class="panel-heading" style="background:#0066FF; color:#fff;"><b> Area of Plot(in Sqft)</b></div>


                    <div class="span6">
                        
                         <?php echo $area_sqft;?>
                          <input type="hidden" name="corr_area_sqft_v" id="corr_area_sqft_v" value="<?php echo $connection_dtls['corr_area_sqft_v']; ?>">

                                <input type="hidden" name="corr_category" id="corr_category" value="<?php echo $connection_dtls['corr_area_sqft_t']; ?>">




                    </div>
                </div>

                <div class="span12">
                    <div class="span6">
                        <input type="submit" name="Proceed" value="Proceed" id="Proceed" class="btn btn-success">
                    </div>

                   <div class="span6">
                        <a href="<?php echo base_url(''); ?>">Go Back</a>
                    </div>

                    
                </div>

            </div>
        </form>
    </div>
</div>
</div>
</div>

<?= $this->include('water/footer');?>

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {

    $('#form_tc_verification').validate({ // initialize the plugin
       

        rules: {
            pipeline_type_id: {
                required: true,
                
            },
            property_type_id: {
                required: true,
               
            },
            connection_type_id: {
                required: true,
               
            },
            connection_through_id: {
                required: true,
               
            },
            category: {
                required: true,
               
            },
            ward_id: {
                required: true,
               
            },
            area_sqft: {
                required: true,
               
            },
        }


    });

});
</script>
