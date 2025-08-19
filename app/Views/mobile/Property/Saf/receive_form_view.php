<?=$this->include("layout_mobi/header");?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Search By</h3>

            </div>
            <?php //print_r($ward_mstr_id);?>
            <div class="panel-body">
                 <div class="row">
                    <div class="col-xs-12">
                        <form class="form-horizontal" method="post" action="<?=base_url('safdistribution/receive_form_search/');?>">
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
                                     <input type="text" maxlength="20" placeholder="Enter SAF No." id="saf_no" name="saf_no" class="form-control" value="<?=(isset($saf_no))?$saf_no:"";?>"  >
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="design"></label>
                                <div class="col-sm-4">
                                     <span class="text-danger">OR</span>
                                </div>
                            </div>
                            <?php //print_r($wardList);?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="design">Ward No.</label>
                                <div class="col-sm-4">
                                    <?php //print_r($wardList);?>
                                     <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                         <option value="">Select</option>
                                         <?php
                                            if(isset($wardList)){
                                               foreach ($wardList as $values){
                                         ?>
                                         <option value="<?=$values['id']?>" <?=($ward_mstr_id==$values['id'])?"selected":"";?>><?=$values['ward_no']?>
                                         </option>
                                         <?php
                                                 }
                                             }
                                         ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="design"></label>
                                <div class="col-sm-4">
                                     <span class="text-danger">AND</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="design">Phone No.</label>
                                <div class="col-sm-4">
                                     <input type="text" maxlength="10" placeholder="Enter Phone No." id="phone_no" name="phone_no" class="form-control" value="<?=(isset($phone_no))?$phone_no:"";?>"  >
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="design">&nbsp;</label>
                                <div class="col-sm-4">
                                    <button class="btn btn-success" id="btndesign" name="btndesign" type="submit">Submit</button>

                                </div>
                            </div>
                         </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="panel panel-bordered panel-dark">
                            <div class="panel-heading">
					                <h5 class="panel-title">List</h5>
					            </div>
                <div class="panel-body" style="padding-bottom: 0px;">
                    <div class="table-responsive">
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Owner Name</th>
                                    <th>Mobile No.</th>
                                    <th>Ward No.</th>
                                    <th>Address</th>
                                    <th>SAF No.</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                                <tbody>
                                <tbody>
                                    <?php
                                            //print_r($owner);
                                    if(isset($saf_list)):
                                          if(empty($saf_list)):
                                    ?>
                                            <tr>
                                                <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($saf_list as $value):
                                    ?>
                                            <tr>
                                                <td><?=$value["owner_name"];?></td>
                                                <td><?=$value["phone_no"];?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["owner_address"];?></td>
                                                <td><?=$value["saf_no"];?></td>

                                                <td>
                                                    <a class="btn btn-primary" href="<?php echo base_url('safdistribution/form_receive/'.(md5($value['id'])));?>" role="button">View</a>

                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>

                            </table>
                        </div>
                </div>
            </div>
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
        var ward_mstr_id = $("#ward_mstr_id").val();
        var phone_no = $("#phone_no").val();

        if (saf_no == ''&& ward_mstr_id == ''&& phone_no == '') {
            $("#saf_no").css({"border-color":"red"});
            $("#saf_no").focus();
            process = false;
          }
		if (saf_no == ''&& ward_mstr_id != ''&& phone_no == '') {
            $("#phone_no").css({"border-color":"red"});
            $("#phone_no").focus();
            process = false;
        }
		if (saf_no == ''&& ward_mstr_id == ''&& phone_no != '') {
            $("#ward_mstr_id").css({"border-color":"red"});
            $("#ward_mstr_id").focus();
            process = false;
        }
        return process;
    });
    $("#saf_no").keyup(function(){$(this).css('border-color','');});
    $("#ward_mstr_id").change(function(){$(this).css('border-color','');});
    $("#phone_no").keyup(function(){$(this).css('border-color','');});
});
</script>                  