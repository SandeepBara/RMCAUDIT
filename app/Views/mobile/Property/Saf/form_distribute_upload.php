<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Form Distribute View</h3>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
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
					                        <div class="col-sm-4">
					                             SAF No.: <b><?php echo $form['saf_no'] ?></b>
					                        </div>
					                    </div>

					                    <div class="form-group">
					                        <div class="col-sm-4">
					                             Ward No.: <b><?php echo $ward['ward_no'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                             Owner Name: <b><?php echo $form['owner_name'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                             Phone No.: <b><?php echo $form['phone_no'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                             Owner Address: <b><?php echo $form['owner_address'] ?></b>
					                        </div>
					                    </div>
                                        <div class="form-group">
					                        <div class="col-sm-12">
                                                <div class="panel panel-bordered panel-dark">
                                                    <div class="panel-heading">
                                                        <h3 class="panel-title">Upload Image</h3>

                                                    </div>
                                                    <div class="panel-body" style="padding-bottom: 0px;">
                                                        <div>
                                                            <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                <tbody>
                                                                    <tr>
                                                                        <td>Left Image</td>
                                                                        <td>
                                                                            <?php
                                                                        if(isset($left_image_exists)):
                                                                         if(empty($left_image_exists)):
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <div class="col-sm-4 col-xs-9">
                                                                                    <input type="file" id="left_image_path" name="left_image_path" class="form-control" value="" accept="image/*" capture="camera"   >
                                                                                </div>
                                                                                <div class="col-sm-4 col-xs-3">
                                                                                    <button class="btn btn-sm btn-danger" id="btn_left_img_upload" name="btn_left_img_upload" type="submit">Upload</button>
                                                                                </div>
                                                                            </div>
                                                                            <?php else: ?>
                                                                            <img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$left_image_exists[0]["image_path"];?>" style="width: 40px; height: 40px;">
                                                                            <?php endif;  ?>
                                                                            <?php endif;  ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Right Image</td>
                                                                        <td>
                                                                            <?php
                                                                        if(isset($right_image_exists)):
                                                                         if(empty($right_image_exists)):
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <div class="col-sm-4 col-xs-9">
                                                                                    <input type="file" id="right_image_path" name="right_image_path" class="form-control" value="" accept="image/*" capture="camera"  >
                                                                                </div>
                                                                                <div class="col-sm-4 col-xs-3">
                                                                                    <button class="btn btn-sm btn-danger" id="btn_right_img_upload" name="btn_right_img_upload" type="submit">Upload</button>
                                                                                </div>
                                                                            </div>
                                                                            <?php else: ?>
                                                                            <img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$right_image_exists[0]["image_path"];?>" style="width: 40px; height: 40px;">
                                                                            <?php endif;  ?>
                                                                            <?php endif;  ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Front Image</td>
                                                                        <td>
                                                                            <?php
                                                                        if(isset($front_image_exists)):
                                                                         if(empty($front_image_exists)):
                                                                            ?>
                                                                            <div class="form-group">
                                                                                <div class="col-sm-4 col-xs-9">
                                                                                    <input type="file" id="front_image_path" name="front_image_path" class="form-control" value="" accept="image/*" capture="camera"  >
                                                                                </div>
                                                                                <div class="col-sm-4 col-xs-3">
                                                                                    <button class="btn btn-sm btn-danger" id="btn_front_img_upload" name="btn_front_img_upload" type="submit">Upload</button>
                                                                                </div>
                                                                            </div>
                                                                            <?php else: ?>
                                                                            <img id="imageresource" src="<?=base_url();?>/writable/uploads/<?=$front_image_exists[0]["image_path"];?>" style="width: 40px; height: 40px;">
                                                                            <?php endif;  ?>
                                                                            <?php endif;  ?>
                                                                        </td>
                                                                    </tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>					                        
					                    </div>
                                        <?php
                                            if(!empty($btn_show)){

                                        ?>
                                        <div class="form-group">
					                        <div class="col-sm-4">
					                            <a href="<?php echo base_url('mobi/home') ?>" class="btn btn-danger"> Home </a>
					                        </div>
					                    </div>
                                        <?php
                                            }
                                        ?>
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

     $("#btn_left_img_upload").click(function() {
        var process = true;

        var left_image_path = $("#left_image_path").val();
        if (left_image_path == '') {
            $("#left_image_path").css({"border-color":"red"});
            $("#left_image_path").focus();
            process = false;
        }

        return process;
    });
     $("#btn_right_img_upload").click(function() {
        var process = true;

        var right_image_path = $("#right_image_path").val();
        if (right_image_path == '') {
            $("#right_image_path").css({"border-color":"red"});
            $("#right_image_path").focus();
            process = false;
        }

        return process;
    });
    $("#btn_front_img_upload").click(function() {
        var process = true;


        var front_image_path = $("#front_image_path").val();
        if (front_image_path == '') {
            $("#front_image_path").css({"border-color":"red"});
            $("#front_image_path").focus();
            process = false;
        }
        return process;
    });
    $("#left_image_path").change(function(){$(this).css('border-color','');});
    $("#right_image_path").change(function(){$(this).css('border-color','');});
    $("#front_image_path").change(function(){$(this).css('border-color','');});

});
</script>