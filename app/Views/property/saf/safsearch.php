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
                        <h5 class="panel-title">Search Application</h5>
                    </div>
                    <div class="panel-body">
                        <form class="" method="get" action="<?=base_url('safdtl/searchApplication');?>" id="myform">
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div class="radio">
                                        <input type="radio" id="by_application_dtl" class="magic-radio" name="by_application_owner_dtl" value="by_application" <?= isset($by_application_owner_dtl) ? (strtolower($by_application_owner_dtl) == "by_application") ? "checked" : "" : "checked"; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Application No.');">
                                        <label for="by_application_dtl">By Application Details</label>

                                        <input type="radio" id="by_owner_dtl" class="magic-radio" name="by_application_owner_dtl" value="by_owner" <?= (isset($by_application_owner_dtl) && strtolower($by_application_owner_dtl) == "by_owner") ? "checked" : ""; ?> onchange="$('#keyword_change_id').attr('data-original-title', 'Enter Register Mobile No. Or Owner Name');">
                                        <label for="by_owner_dtl">By Owner Details</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-1">Ward No.</label>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                            <option value=''>== SELECT ==</option>
                                            <?php
                                            if(isset($ward_list)) {
                                                foreach($ward_list AS $list) {
                                            ?>
                                                    <option value="<?=$list["id"];?>" <?=(isset($ward_mstr_id) && $ward_mstr_id==$list['id'])?"selected":"";?>>
                                                        <?=$list['ward_no'];?>
                                                    </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="keyword">
                                        Enter Keywords
                                        <i id="keyword_change_id" class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="<?= (isset($by_application_owner_dtl) && strtolower($by_application_owner_dtl) == "by_owner") ? "Enter Register Mobile No. Or Owner Name" : "Enter Application No."; ?>"></i>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" id="keyword" name="keyword" class="form-control" style="height:38px;" placeholder="Enter Keywords" value="<?= $keyword ?? NULL; ?>" />

                                    </div>
                                </div>
                                <div class="col-md-3">
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
                            <th>Owner Name</th>
                            <th>Guardian Name</th>
                            <th>Mobile No.</th>
                            <th>Property Type</th>
                            <th>Assessment Type</th>
                            <th>Apply Date </th>
                            <th>Address</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if(isset($result_list))
                    foreach($result_list as $list)
                    {
                        ?>
                            <tr>
                                <td><?=++$offset;?></td>
                                <td><?=$list['ward_no']?></td>
                                <td><?=$list['saf_no']?></td>
                                <td><?=$list['owner_name']?></td>
                                <td><?=$list['guardian_name']?></td>
                                <td><?=$list['mobile_no']?></td>
                                <td><?=$list['property_type']?></td>
                                <td><?=$list['assessment_type']?></td>
                                <td><?=$list['apply_date']?></td>
                                <td><?=$list['prop_address']?></td>
                                <td><a href="<?=base_url();?>/safdtl/full/<?=$list['id'];?>" class="btn btn-primary"> View </a></td>
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
   
</div>
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myform').validate({ // initialize the plugin
            rules: {
                ward_mstr_id: {
                    required: "#keyword:blank",
                },
                keyword: {
                    required: "#ward_mstr_id:blank",
                }
            }
        });
    });
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>