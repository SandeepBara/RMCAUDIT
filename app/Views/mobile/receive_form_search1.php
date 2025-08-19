<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Receive Form Search</h3>

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
                                <label class="col-sm-2 control-label" for="design">SAF No.</label>
                                <div class="col-sm-4">
                                     <input type="text" maxlength="20" placeholder="Enter SAF No." id="saf_no" name="saf_no" class="form-control" value=""  >
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="design">&nbsp;</label>
                                <div class="col-sm-4">
                                    <button class="btn btn-success" id="btndesign" name="btndesign" type="submit">Submit</button>
                                     <a href="<?php echo base_url('safdistribution/saf_opt') ?>" class="btn btn-danger"> Back </a>
                                </div>
                            </div>
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
        var saf_no = $("#saf_no").val();

        if (saf_no == '') {
            $("#saf_no").css({"border-color":"red"});
            $("#saf_no").focus();
            process = false;
          }
        return process;
    });
    $("#saf_no").keyup(function(){$(this).css('border-color','');});
});
</script>