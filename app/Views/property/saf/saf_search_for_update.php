<?= $this->include('layout_vertical/header');?>
<?php $session = session(); ?>
<style>
.error {
    color: red;
}
</style>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">SAF Search for update</a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="form_search" name="form_search" method="post" action="<?=base_url('saf/searchSafDtl');?>">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h3 class="panel-title">SAF Search for update</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <label class="col-md-3">Ward No</label>
                        <div class="col-md-3 pad-btm">
                            <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                <option value="">== SELECT ==</option>
                                <?php
                                if(isset($wardList)){
                                    foreach ($wardList as $ward) {
                                ?>
                                <option value="<?=$ward['id'];?>" <?=(isset($ward_mstr_id))?($ward['id']==$ward_mstr_id)?"selected":"":"";?>><?=$ward['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Application No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="saf_no" name="saf_no" class="form-control atLeastOneFieldRequired" placeholder="Application No" value="<?=(isset($saf_no))?$saf_no:'';?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12 pad-btm"><span class="text-danger"><b><u>OR</u></b></span></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Applicant Name<span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="owner_name" name="owner_name" class="form-control atLeastOneFieldRequired" placeholder="Applicant Name" value="<?=(isset($owner_name))?$owner_name:'';?>" />
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-12 pad-btm"><span class="text-danger"><b><u>OR</u></b></span></label>
                    </div>
                    <div class="row">
                        <label class="col-md-3">Mobile No. <span class="text-danger">*</span></label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" id="mobile_no" name="mobile_no" class="form-control atLeastOneFieldRequired" placeholder="Mobile No" value="<?=(isset($mobile_no))?$mobile_no:'';?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 col-xs-4"></div>
                        <div class="col-md-2 col-xs-4 text-center">
                            <button type="SUBMIT" id="btn_search" name="btn_search" class="btn btn-block btn-primary">SEARCH</button>
                        </div>
                        <div class="col-md-5 col-xs-4"></div>
                    </div>
                </div>                
            </div>
        </form>
    <?php
    if(isset($searchList)) {
    ?>
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h3 class="panel-title">Search List</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered text-sm">
                                <thead class="bg-trans-dark text-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Application No.</th>
                                        <th>Owner Name</th>
                                        <th>Guardian Name</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                foreach ($searchList as $key => $list) {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$list['saf_no'];?></td>
                                        <td><?=$list['owner_name'];?></td>
                                        <td><?=$list['guardian_name'];?></td>
                                        <td><?=$list['mobile_no'];?></td>
                                        <td><?=$list['prop_address'];?></td>
                                        <td>
                                        <a href="<?=base_url();?>/SAF/backOfficeSAFUpdate/<?=md5($list['id']);?>" class="btn btn-mint btn-icon"><i class="fa fa-edit"></i></a>
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
            </div>                
        </div>
    <?php
    }
    ?>
    </div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script src="<?=base_url();?>/public/assets/js/additional-methods.min.js"></script>
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
    <?=(isset($errors))?"modelInfo('".$errors."');":"";?>
    $(document).ready(function(){        
        $("#form_search").validate({
            rules:{
                
                saf_no: {
                    require_from_group: [1, ".atLeastOneFieldRequired"]
                },
                owner_name: {
                    require_from_group: [1, ".atLeastOneFieldRequired"]
                },
                mobile_no: {
                    require_from_group: [1, ".atLeastOneFieldRequired"]
                }
            },
            
        });
    });

</script>