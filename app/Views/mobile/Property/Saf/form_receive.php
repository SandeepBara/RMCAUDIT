<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Form Receive </h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="row" >
                        <div class="col-sm-6">
                            <center><b><h4 style="color:red;">
                                <?php
                                if(!empty($err_msg)){
                                    echo $err_msg;
                                }
                                ?>
                                </h4>
                                </b></center>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-xs-2">SAF No.:</label>
                        <div class="col-sm-4 col-xs-4 pad-btm">
                             <b><?php echo $form['saf_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-xs-2">Ward No:</label>
                        <div class="col-sm-4 col-xs-4 pad-btm">
                             <b><?php echo $ward['ward_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2  col-xs-2">Owner Name:</label>
                        <div class="col-sm-4 col-xs-4 pad-btm">
                             <b><?php echo $form['owner_name'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2  col-xs-2">Phone No.:</label>
                        <div class="col-sm-4 col-xs-4 pad-btm">
                             <b><?php echo $form['phone_no'] ?></b>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-xs-2">Owner Address:</label>
                        <div class="col-sm-4 col-xs-4 pad-btm">
                             <b><?php echo $form['owner_address'] ?></b>
                        </div>
                    </div>
                </div>
            </div>
        <form method="POST" enctype="multipart/form-data" action="">
        <input type="hidden" name="saf_distributed_dtl_id" value="<?=(isset($form['id']))?$form['id']:'';?>">
        <!-------Transfer Mode-------->
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">Property Document Details</h3>

                </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="col-xs-12 col-md-6">
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td>Registered Deed</td>
                                        <td>
                                            <select id="trans_doc_mstr_id" name="trans_doc_mstr_id" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                if(isset($transfer_mode)){
                                                    foreach ($transfer_mode as $values){
                                                ?>
                                                <option value="<?=$values['id']?>" ><?=$values['doc_name']?>
                                                </option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td> Property </td>
                                        <td>
                                            <select id="prop_doc_mstr_id" name="prop_doc_mstr_id" class="form-control">
                                                <option value="">Select</option>
                                                <?php
                                                if(isset($property_type)){
                                                    foreach ($property_type as $values){
                                                ?>
                                                <option value="<?=$values['id']?>" ><?=$values['doc_name']?>
                                                </option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>    
                        </div>                  
                </div>
            </div>


            <button class="btn btn-success" id="btndesign" name="btndesign" type="submit">Submit</button>
        </form>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>
<script type="text/javascript">
$(document).ready( function () {
    $("#btndesign").click(function() {
        var process = true;

        var prop_doc_mstr_id = $("#prop_doc_mstr_id").val();
        if (prop_doc_mstr_id == '') {
            $("#prop_doc_mstr_id").css({"border-color":"red"});
            $("#prop_doc_mstr_id").focus();
            process = false;
        }

        return process;
    });
    $("#prop_doc_mstr_id").change(function(){$(this).css('border-color','');});

});
</script>