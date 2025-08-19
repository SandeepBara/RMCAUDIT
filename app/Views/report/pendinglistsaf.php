<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<style>
    #levelTable th{
        text-align: center;
        border-bottom: 0.01px solid #7e7474 !important;
        border-top: 0.01px solid #7e7474 !important;
        border-left: 0.01px solid #7e7474 !important;
    }
    #levelTable td{
        text-align: center;
        border-left: 0.01px solid #7e7474 !important;
        border-bottom: 0.01px solid #7e7474 !important;
    }
    #levelTable_wrapper{
        width: 100%;
        overflow-x: scroll;
    }
    .green{
        background-color: #0ba30b;
        color: #dfdfdf !important;
    }
    .blue{
        background-color: #186bc1;
        color: #dfdfdf !important;
    }
</style>
<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Report</a></li>
                    <li class="active">Pending SAF</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                        <div class="panel-heading" style="background-color: #298da0;">
                            <h3 class="panel-title">Pending SAF</h3>
                        </div>
                            <div class="panel-body">
                                <div class ="row">
                                    <div class="col-md-12">
                                        <form class="form-horizontal" action="<?php echo base_url('levelwisependingform/exportreportleveltimetaken2');?>">
                                            <input type="hidden" name="report_type" class="form-control" value="list">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                                    <input type="date" id="from_date" name="from_date" min="2016-04-01" max="<?=date('Y-m-d');?>" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
                                                    <input type="date" id="to_date" name="to_date" min="2016-04-01" class="form-control" max="<?=date('Y-m-d');?>" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" >
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
                                                    <select name="filter_type" id="user_type" class="form-control">
                                                        <option value="Back Office" <?= ($_GET['filter_type']=='Back Office')?'selected':"" ?> >Back Office</option>
                                                        <option value="Dealing Assistant" <?= ($_GET['filter_type']=='Dealing Assistant')?'selected':"" ?> >Dealing Assistant</option>
                                                        <option value="Tax Collector" <?= ($_GET['filter_type']=='Tax Collector')?'selected':"" ?>>TCA</option>
                                                        <option value="ULB Tax Collector" <?= ($_GET['filter_type']=='ULB Tax Collector')?'selected':"" ?>>ULB Tax Collector</option>
                                                        <option value="Section Incharge" <?= ($_GET['filter_type']=='Section Incharge')?'selected':"" ?> >Property Section Incharge</option>
                                                        <option value="Executive Officer" <?= ($_GET['filter_type']=='Executive Officer')?'selected':"" ?> >DMC/AMC</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="">
                                                        <label class="control-label d-block" for="btn_search" style="width: 100%">&nbsp; &nbsp;</label>
                                                        <button type="submit"  class="btn btn-primary d-block"></i> Search</button>
<!--                                                    <button type="submit"  class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> EXCEL EXPORT</button>-->
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
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
            <h5 class="panel-title">Pending SAF List</h5>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Ward No.</th>
                            <th>Assessment Type</th>
                            <th>SAF No.</th>
                            <th>Employee Name</th>
                            <th>Apply Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (isset($lists)) :
                            if (empty($lists)) :
                                ?>
                                <tr>
                                    <td colspan="7" style="text-align: center;">Data Not Available!!</td>
                                </tr>
                            <?php else :
                                $i = $offset??0;
                                foreach ($lists as $value) :
                                  //  $value=array_values($value)[0];
//                                    dd($value)
                                    //print_var($value);continue;
                                    ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= $value["ward_no"]??""; ?></td>
                                        <td><?= $value["assessment_type"]??""; ?></td>
                                        <td><?= $value["saf_no"]??"" ?></td>
                                        <td><?= (new \App\Models\model_level_pending_dtl(db_connect('db_rmc_property')))->employeedetails($value["ward_mstr_id"],$value["receiver_user_type_id"],$value["ward_no"])['emp_name']; ?></td>
                                        <td><?= $value["apply_date"]??""; ?></td>
                                        <td>
                                            <!-- level id passing -->
                                            <a class="btn btn-primary" target="_blank" href="<?php echo base_url('safDtl/full/' . $value['id']??""); ?>" role="button">View</a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
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

            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'excel',
                    extend: "excel",
                    title: "Pending SAF Report",
                    footer: { text: '' },
                    exportOptions: { columns: [ 0,1,2,3,4,5] }
                }]
        });
    });

</script>