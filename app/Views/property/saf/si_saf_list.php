<?= $this->include('layout_vertical/header'); ?>
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
			<li><a href="#"> SAF</a></li>
            <li class="active">SAF Inbox</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Inbox</h5>
            </div>
            <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" method="get" action="<?=base_url('si_saf/inbox_list');?>">
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label" for="prop_type_mstr_id"><b>Assessment Type</b></label>
                                <select id="assessment_type" name="assessment_type" class="form-control">
                                    <option value="">ALL</option>
                                    <option value="New Assessment" <?=isset($assessment_type)?($assessment_type=='New Assessment')?"SELECTED":"":"";?>>New Assessment</option>
                                    <option value="Reassessment" <?=isset($assessment_type)?($assessment_type=='Reassessment')?"SELECTED":"":"";?>>Reassessment</option>
                                    <option value="Mutation" <?=isset($assessment_type)?($assessment_type=='Mutation')?"SELECTED":"":"";?>>Mutation</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="prop_type_mstr_id"><b>Property Type</b></label>
                                <select id="prop_type_mstr_id" name="prop_type_mstr_id" class="form-control">
                                    <option value="">ALL</option>
                                    <option value="1" <?=isset($prop_type_mstr_id)?($prop_type_mstr_id==1)?"SELECTED":"":"";?> >SUPER STRUCTURE</option>
                                    <option value="2" <?=isset($prop_type_mstr_id)?($prop_type_mstr_id==2)?"SELECTED":"":"";?> >INDEPENDENT BUILDING</option>
                                    <option value="3" <?=isset($prop_type_mstr_id)?($prop_type_mstr_id==3)?"SELECTED":"":"";?> >FLATS / UNIT IN MULTI STORIED BUILDING</option>
                                    <option value="4" <?=isset($prop_type_mstr_id)?($prop_type_mstr_id==4)?"SELECTED":"":"";?> >VACANT LAND</option>
                                    <option value="5" <?=isset($prop_type_mstr_id)?($prop_type_mstr_id==5)?"SELECTED":"":"";?> >OCCUPIED PROPERTY</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="ward_mstr_id"><b>Ward No</b></label>
                                <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                    <option value="">ALL</option>
                                    <?php foreach ($wardList as $value) : ?>
                                        <option value="<?= $value['ward_mstr_id'] ?>" <?= (isset($ward_mstr_id)) ? $ward_mstr_id == $value["ward_mstr_id"] ? "SELECTED" : "" : ""; ?>><?= $value['ward_no']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="control-label" for="search_param"><b>Search</b> </label>
                                <input type="text" id="search_param" name="search_param" class="form-control" placeholder="Enter Search Keyword" value="<?=$search_param??"";?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <label class="control-label" for="btn_search">&nbsp;</label>
                                <button type="submit" class="btn btn-success" id="btn_search">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Inbox List</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="demo_dt_basic" class="table table-striped table-bordered text-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ward No.</th>
									<th>Assessment Type</th>
									<th>Property Type</th>
                                    <th>SAF No.</th>
                                    <th>Owner Name</th>
                                    <th>Mobile No.</th>
                                    <th>Forward Date/Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                foreach ($inboxList as $value) {
                                ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= $value["ward_no"]; ?></td>
										<td><?= $value["assessment_type"]; ?></td>
										<td><?= $value["property_type"]; ?></td>
                                        <td><?= $value["saf_no"]; ?></td>
                                        <td><?= $value["owner_name"]; ?></td>
                                        <td><?= $value["mobile_no"]; ?></td>
                                        <td><?= $value["forward_date"]." ".$value["forward_time"]; ?></td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="<?= base_url('si_saf/view/' . md5($value['saf_dtl_id'])); ?>">View</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?= pagination($pager); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->

</div>
<!--===================================================-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer'); ?>


<script type="text/javascript">
    $(document).ready(function() {
        $("#from_date").change(function() {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if ((startDay.getTime()) > (endDay.getTime())) {
                alert("Please select valid To Date!!");
                $("#from_date").val('');
            }
        });
        $("#to_date").change(function() {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();

            var startDay = new Date(from_date);
            var endDay = new Date(to_date);

            if ((startDay.getTime()) > (endDay.getTime())) {
                alert("Please select valid To Date!!");
                $("#to_date").val('');
            }
        });

        $("#btn_search").click(function() {
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            if (from_date == "") {
                alert("Please Select From Date");
                $('#from_date').focus();
                return false;
            }

            if (to_date == "") {
                alert("Please Select To date");
                $('#to_date').focus();
                return false;
            }
        });

    });
</script>