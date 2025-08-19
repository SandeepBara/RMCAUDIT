<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Form Distribute</h3>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" method="post" action="">
                                        <div class="form-group" >
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

					                    <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">Ward No.</label>
					                        <div class="col-sm-4">
					                             <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                                     <option value="">Select</option>
                                                     <?php
                                                        if(isset($wardList)){
                                                           foreach ($wardList as $values){
                                                     ?>
                                                     <option value="<?=$values['id']?>" ><?=$values['ward_no']?>
                                                     </option>
                                                     <?php
                                                             }
                                                         }
                                                     ?>
                                                </select>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">Owner Name</label>
					                        <div class="col-sm-4">
					                             <input type="text" maxlength="20" placeholder="Enter Owner Name" id="owner_name" name="owner_name" class="form-control" value=""  >
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">Phone No.</label>
					                        <div class="col-sm-4">
					                             <input type="text" maxlength="10" placeholder="Enter Phone No." id="phone_no" name="phone_no" class="form-control" value=""  >
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">Owner Address</label>
					                        <div class="col-sm-4">
					                             <input type="text" maxlength="20" placeholder="Enter Owner Address" id="owner_address" name="owner_address" class="form-control" value=""  >
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <label class="col-sm-2 control-label" for="design">&nbsp;</label>
					                        <div class="col-sm-4">
					                            <button class="btn btn-success" id="btndesign" name="btndesign" type="submit">Submit</button>
                                                 <a href="<?php echo base_url('safdistribution/saf_opt') ?>" class="btn btn-danger"> Back </a>
					                        </div>
					                    </div>

                                        <?php if(isset($validation)){ ?>

                                                    <?= $validation->listErrors(); ?>

                                            <?php } ?>
                                         </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<?=$this->include("layout_mobi/footer");?>

<script type="text/javascript">
$(document).ready( function () {
    $("#btndesign").click(function() {
        var process = true;
        var ward_mstr_id = $("#ward_mstr_id").val();

        if (ward_mstr_id == '') {
            $("#ward_mstr_id").css({"border-color":"red"});
            $("#ward_mstr_id").focus();
            process = false;
          }
        var owner_name = $("#owner_name").val();
        if (owner_name == '') {
            $("#owner_name").css({"border-color":"red"});
            $("#owner_name").focus();
            process = false;
          }
         var phone_no = $("#phone_no").val();
        if (phone_no == '') {
            $("#phone_no").css({"border-color":"red"});
            $("#phone_no").focus();
            process = false;
          }
          if(phone_no!="")
			{
                if(phone_no.length < 10) {
				$("#phone_no").css({"border-color":"red"});
				$('#phone_no').focus();
				return false;
                }
			}
        var owner_address = $("#owner_address").val();
        if (owner_address == '') {
            $("#owner_address").css({"border-color":"red"});
            $("#owner_address").focus();
            process = false;
          }
        return process;
    });
    $("#ward_mstr_id").change(function(){$(this).css('border-color','');});
    $("#owner_name").keyup(function(){$(this).css('border-color','');});
    $("#phone_no").keyup(function(){$(this).css('border-color','');});
     $("#owner_address").keyup(function(){$(this).css('border-color','');});
     $("#owner_address").keyup(function(){$(this).css('border-color','');});
});
</script>