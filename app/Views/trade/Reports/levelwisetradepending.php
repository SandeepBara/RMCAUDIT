<?= $this->include('layout_vertical/header');?>
<style>
    .row{line-height:25px;}
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">

<style>
    #levelTable th, #levelTable td{
        text-align: center;
        border: 1px solid #af8d8d !important;
    }
    .activelink{
        color:#0f2fa3;
        border: 0;
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
            <li class="active">Level Wise Trade Pending Report</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading" style="background-color: #298da0;">
                <h3 class="panel-title">Level Wise Trade Pending Report</h3>
            </div>
            <div class="panel-body">
                <div class ="row">
                    <div class="col-md-12">
                        <form class="form-horizontal" method="get" action="<?php echo base_url('Trade_report/levelwisependingReport');?>">
                            <div class="form-group">
                                <div class="col-md-2">
                                    <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                    <!--                                            <input type="date" id="from_date" name="from_date" min="2024-02-07" value="2024-02-07" max="--><?php //=date('Y-m-d');?><!--" class="form-control" placeholder="From Date" value="--><?php //=(isset($from_date))?$from_date:date('Y-m-d');?><!--" >-->
                                    <input type="date" id="from_date" name="from_date" min ="<?=explode("-",getFY())[0]."-04-01";?>"  max="<?=date('Y-m-d');?>" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
                                    <input type="date" id="to_date" name="to_date"  class="form-control" max="<?=date('Y-m-d');?>" min ="<?=explode("-",getFY())[0]."-04-01";?>" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" >
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label" for="btn_search">&nbsp;</label>
                                    <button class="btn btn-primary btn-block" id="btn_search" type="submit">Search</button>

                                </div>
                                <div class="col-md-2">
                                </div>

                                <div class="col-md-4">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>



            <div class="panel-body">
                <div class="row">
                    <div class="table-responsive">

                        <table class="table table-sm table-striped table-bordered text-sm table-responsive datatable text-center" id="levelTable">
                            <thead><tr style="background-color: #e6e1e1;">
                                <th rowspan="4" style="vertical-align: middle;">Online Application</th>
                                <th>Pending at Level</th>
                                <th colspan="3">Pending at BO Level</th>
                                <th colspan="3">Pending at ULB Dealing Assistant</th>
                                <th colspan="3">Pending at ULB Level</th>
                            </tr>
                            <tr>
                                <th>Timeline</th>
                                <th colspan="3">T+2</th>
                                <th colspan="3">T+5</th>
                                <th colspan="3">T+21</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Done</th>
                                <th>In Process</th>
                                <th>Pending</th>
                                <th>Done</th>
                                <th>In Process</th>
                                <th>Pending</th>
                                <th>Tax Daroga</th>
                                <th>Section Head</th>
                                <th>DMC</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th colspan="3">2 Days</th>
                                <th colspan="3">3 Days</th>
                                <th>10 Days</th>
                                <th>3 Days</th>
                                <th>3 Days</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($total_app!=0){ ?>
                                <tr style="font-size: 15px;">
                                    <th><?= $total_app ?></th>
                                    <th></th>
                                    <th><?= $badone ?></th>
                                    <th><?= $baprocess ?></th>
                                    <th>
                                        <a target="_blank" href="<?php echo base_url('Trade_report/levelwisependingReportexport?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Back+Office');?>">
                                            <button class="activelink" formtarget="_blank"><?= $batotal ?></button>
                                        </a>
                                    </th>
                                    <th><?= $dadone ?></th>
                                    <th><?= $daprocess ?></a></th>
                                    <th>
                                        <a target="_blank" href="<?php echo base_url('Trade_report/levelwisependingReportexport?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Dealing+Assistant');?>">
                                            <button class="activelink" formtarget="_blank"><?= $datotal ?></button>
                                       </a>
                                    </th>
                                    <th>
                                        <a target="_blank" href="<?php echo base_url('Trade_report/levelwisependingReportexport?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Tax+Daroga');?>">
                                            <button class="activelink" formtarget="_blank"><?= $tdtotal ?></button>
                                        </a>
                                    </th>
                                    <th>
                                        <a target="_blank" href="<?php echo base_url('Trade_report/levelwisependingReportexport?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Section+Head');?>"> 
                                            <button class="activelink" formtarget="_blank"><?= $shtotal ?></button>
                                       </a>
                                    </th>
                                    <th>
                                        <a target="_blank" href="<?php echo base_url('Trade_report/levelwisependingReportexport?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Executive+Officer');?>">
                                            <button class="activelink" formtarget="_blank"><?= $eototal ?></button>
                                        </a>
                                    </th>
                                </tr>
                            <?php } ?>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<?= $this->include('layout_vertical/footer');?>
<script>
    var temp = "ALL";
    // $.fn.dataTable.ext.errMode = 'throw';
    // var dataTable = $('#levelTable').DataTable();
    // dataTable.draw();
    /*{
        // lengthMenu: [
        //     [10, 25, 50, 5000],
        //     ['10 rows', '25 rows', '50 rows', '5000 rows']
        // ],
        buttons: [
            'pageLength',

        ],
    });*/
    $('#btn_search').click(function(){
        temp='BY';
        dataTable.draw();
    });
</script>