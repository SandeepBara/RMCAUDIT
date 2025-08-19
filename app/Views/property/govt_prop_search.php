<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <div id="page-title">
            <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
        </div>
        <!--End page title-->
        <!--Breadcrumb-->
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">GOVERNMENT PROPERTY</a></li>
        <li class="active">SEARCH</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">SEARCH</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <form class="" method="post" action="<?php echo base_url('propDtl/govtsearch');?>">
                                    <div class="row mar-btn">
                                        <label class="control-label col-md-2" for="ward_mstr_id"><b>Ward No.</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                                <option value=''>== SELECT ==</option>
                                                <?php
                                                if(isset($ward_list)){
                                                    foreach($ward_list AS $list) {
                                                ?>
                                                    <option value='<?=$list['id'];?>' <?=(isset($ward_mstr_id)?($ward_mstr_id==$list['id'])?"selected":"":"")?>><?=$list['ward_no'];?></option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <label class="control-label col-md-2" for="holding_no"><b>Holding No</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="holding_no" name="holding_no" class="form-control" placeholder="Holding No" value="<?=(isset($holding_no))?$holding_no:"";?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="control-label col-md-2" for="owner_name"><b>Owner Name</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="owner_name" name="owner_name" class="form-control" placeholder="Owner Name" value="<?=(isset($owner_name))?$owner_name:"";?>">
                                        </div>
                                        <label class="control-label col-md-2" for="mobile_no"><b>Mobile No</b> <span class="text-danger">*</span></label>
                                        <div class="col-md-3 pad-btm">
                                            <input type="text" id="mobile_no" name="mobile_no" class="form-control" placeholder="Mobile No" value="<?=(isset($mobile_no))?$mobile_no:"";?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <?php if(isset($ward_mstr_id) && !isset($result_list)) { ?>
                                            <span class="text-danger">no record found...</span>
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-success" id="btn_search" name="btn_search">SEARCH</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
    <?php
    if(isset($result_list)) {
    ?>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">List</h5>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Holding No.</th>
                                        <th>Owner Name</th>
                                        <th>Mobile No.</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i=0;
                                foreach($result_list as $list) {
                                    $i++;
                                ?>
                                    <tr>
                                        <td><?=$i;?></td>
                                        <td><?=($list['ward_no']!="")?$list['ward_no']:"N/A";?></td>
                                        <td><?=$list['holding_no'];?></td>
                                        <td><?=($list['owner_name']!="")?$list['owner_name']:"N/A";?></td>
                                        <td><?=($list['mobile_no']!="")?$list['mobile_no']:"N/A";?></td>
                                        <td><a href="<?=base_url();?>/propDtl/govtFull/<?=md5($list['id']);?>"><i class="fa fa-street-view" style="font-size: 22px"></i></a></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
function modelInfo(msg){
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
$(document).ready(function() {
    
});
$("#btn_search").click(function(){
    if ($("#ward_mstr_id").val()=="") {
        $("#ward_mstr_id").css('border-color', 'red'); return false;
    }
    if ($("#holding_no").val()=="" && $("#owner_name").val()=="" && $("#mobile_no").val()=="") {
        modelInfo("Please enter minimum 1 field...<br />(holding no, owner name, mobile no)"); return false;
    }
    return true;
});
$("#ward_mstr_id").change(function(){ $(this).css('border-color', ''); });
 </script>

