<?= $this->include('layout_vertical/header');?>
<style>
.error{
    color: red;
}
</style>

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
        <li><a href="#">SAF</a></li>
        <li class="active">Search Application</li>
        </ol>
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title text-center">Search Application</h5>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="get" id="myform">
                            <div class="form-group">
                                <div class="col-md-2 text-center">
                                    <label class="control-label" for="ward_id"><b>Ward No.</b> <span class="text-danger"></span></label>
                                    <select id="ward_id" name="ward_id" class="form-control">
                                        <option value=''>== SELECT ==</option>
                                        <?php
                                            if(isset($ward_list)) {
                                                foreach($ward_list AS $list) {
                                                ?>
                                                <option value="<?=$list["id"];?>" <?=(isset($ward_id) && $ward_id==$list['id'])?"selected":"";?>>
                                                    <?=$list['ward_no'];?>
                                                </option>
                                                <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1 text-center">
                                    <label for="" class="control-label"></label><br><br>
                                    <span class="text-danger"> OR
                                </div>
                                <div class="col-md-2 text-center">
                                    <label class="control-label" for="saf_no"><b>SAF No.</b> <span class="text-danger"></span></label>
                                    <input type="text" id="saf_no" name="saf_no" class="form-control" placeholder="" value="<?=(isset($saf_no))?$saf_no:'';?>">
                                </div>
                                <div class="col-md-1 text-center">
                                    <label for="" class="control-label"></label><br><br>
                                    <span class="text-danger"> OR
                                </div>
                                <div class="col-md-2 text-center">
                                    <label class="control-label" for="fromDate"><b>From Date</b> <span class="text-danger"></span></label>
                                    <input type="date" id="fromDate" name="fromDate" class="form-control" placeholder="From Date" value="<?=(isset($fromDate))?$fromDate:"";?>" max="<?=date('Y-m-d');?>">
                                </div>
                                <div class="col-md-2 text-center">
                                    <label class="control-label" for="uptoDate"><b>Upto Date</b> <span class="text-danger"></span></label>
                                    <input type="date" id="uptoDate" name="uptoDate" class="form-control" placeholder="Upto Date" value="<?=(isset($uptoDate))?$uptoDate:"";?>" max="<?=date('Y-m-d');?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 text-center"></div>
                                <div class="col-md-1 text-center">
                                    <label for="" class="control-label"></label><br><br>
                                    <span class="text-danger"> OR
                                </div>
                                <div class="col-md-2 text-center">
                                    <label class="control-label" for="owner_name"><b>Owner Name</b> <span class="text-danger"></span></label>
                                    <input type="text" id="owner_name" name="owner_name" class="form-control" placeholder="Owner Name" value="<?=(isset($owner_name))?$owner_name:'';?>">
                                </div>
                                <div class="col-md-1 text-center">
                                    <label for="" class="control-label"></label><br><br>
                                    <span class="text-danger"> OR
                                </div>
                                <div class="col-md-2 text-center">
                                    <label class="control-label" for="holding_no"><b>Holding No.</b> <span class="text-danger"></span></label>
                                    <input type="text" id="holding_no" name="holding_no" class="form-control" placeholder="new or old holding no." value="<?=(isset($holding_no))?$holding_no:'';?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5"></div>
                                <div class="col-md-2">
                                    <button type="submit" id="search" class="btn btn-primary">SEARCH</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
   <?php 
   if(isset($result_list)){
    ?>
        <div id="page-content">
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title">Search Result</h5>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered text-sm" id="demo_dt_basic">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ward No.</th>
                                <th>SAF No.</th>
                                <th>Holding No.</th>
                                <th>Owner Name</th>
                                <th>Mobile No.</th>
                                <th>Property Type</th>
                                <th>Assessment Type</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            foreach($result_list as $list)
                            {
                                ?>
                                    <tr>
                                        <td><?=++$offset;?></td>
                                        <td><?=$list['ward_no']??"";?></td>
                                        <td><?=$list['saf_no']??"";?></td>
                                        <td><?=$list['holding_no']??"";?></td>
                                        <td><?=$list['owner_name']??"";?></td>
                                        <td><?=$list['mobile_no']??"";?></td>
                                        <td><?=$list['property_type']??"";?></td>
                                        <td><?=$list['assessment_type']??"";?></td>
                                        <td><a href="<?=base_url();?>/safdtl/verificationDtl/<?=$list['id'];?>" class="btn btn-primary"> View </a></td>
                                    </tr>
                                <?php
                            }
                        ?>
                        </tbody>
                    </table>
                    </div>
                    <?=pagination(isset($pager)?$pager:0);?>
                </div>
            </div>
        </div>
    <?php
   }
   ?>
   
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myform').validate({ // initialize the plugin
            rules: {
                ward_id: {
                    required: function(element) {
                        return $('#saf_no').val() === '' &&
                            $('#fromDate').val() === '' &&
                            $('#uptoDate').val() === '' &&
                            $('#owner_name').val() === '' &&
                            $('#holding_no').val() === '';
                    },
                },
                saf_no: {
                    required: function(element) {
                        return $('#ward_id').val() === '' &&
                            $('#fromDate').val() === '' &&
                            $('#uptoDate').val() === '' &&
                            $('#owner_name').val() === '' &&
                            $('#holding_no').val() === '';
                    },
                },
                fromDate: {
                    required: function(element) {
                        return ($('#ward_id').val() === '' &&
                            $('#saf_no').val() === '' &&
                            $('#uptoDate').val() === '' &&
                            $('#owner_name').val() === '' &&
                            $('#holding_no').val() === '')||($('#uptoDate').val() !== '');
                    },
                },
                uptoDate: {
                    required: function(element) {
                        return ($('#ward_id').val() === '' &&
                            $('#saf_no').val() === '' &&
                            $('#fromDate').val() === '' &&
                            $('#owner_name').val() === '' &&
                            $('#holding_no').val() === '')||($('#fromDate').val() !== '');
                    },
                },
                owner_name: {
                    required: function(element) {
                        return ($('#ward_id').val() === '' &&
                            $('#saf_no').val() === '' &&
                            $('#fromDate').val() === '' &&
                            $('#uptoDate').val() === '' &&
                            $('#holding_no').val() === '');
                    },
                },
                holding_no: {
                    required: function(element) {
                        return ($('#ward_id').val() === '' &&
                            $('#saf_no').val() === '' &&
                            $('#fromDate').val() === '' &&
                            $('#uptoDate').val() === '' &&
                            $('#owner_name').val() === '');
                    },
                }
            }
        });
    });
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>