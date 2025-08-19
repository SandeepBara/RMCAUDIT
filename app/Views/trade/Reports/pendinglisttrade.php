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
                    <li class="active">Pending Trade Application</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                        <div class="panel-heading" style="background-color: #298da0;">
                            <h3 class="panel-title">Pending Trade Application List</h3>
                        </div>
                            <div class="panel-body">
                                <div class ="row">
                                    <div class="col-md-12">
                                        <form class="form-horizontal" action="<?php echo base_url('Trade_report/levelwisependingReportexport');?>">
                                            <input type="hidden" name="report_type" class="form-control" value="list">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                                    <input type="date" id="from_date" name="from_date" min="2024-04-01" max="<?=date('Y-m-d');?>" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
                                                    <input type="date" id="to_date" name="to_date" min="2024-04-01" class="form-control" max="<?=date('Y-m-d');?>" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" >
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label" for="user_type"><b>To Level</b> <span class="text-danger">*</span></label>
                                                    <select name="filter_type" id="user_type" class="form-control">
                                                        <option value="Back Office" <?= ($_GET['filter_type']=='Back Office')?'selected':"" ?> >Back Office</option>
                                                        <option value="Dealing Assistant" <?= ($_GET['filter_type']=='Dealing Assistant')?'selected':"" ?> >Dealing Assistant</option>
                                                        <option value="Tax Daroga" <?= ($_GET['filter_type']=='Tax Daroga')?'selected':"" ?>>Tax Daroga</option>
                                                        <option value="Section Head" <?= ($_GET['filter_type']=='Section Head')?'selected':"" ?> >Section Head</option>
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
            <h5 class="panel-title">Pending Trade Application List</h5>
        </div>
        <?php
        $u1="DEEPAK RAJAK";$u2="MD GULZAR";$u3="RAJESH PANDEY";$u4="SRI PAWAN RAM";$u5="PRAKASH RANJAN";
        $ulbdaward=['1'=>$u1,'2'=>$u1,'3'=>$u1,'4'=>$u1,'5'=>$u1,
            '6'=>$u2,'7'=>$u2,'8'=>$u2,'9'=>$u2,'10'=>$u2,'11'=>$u2,'12'=>$u2,'13'=>$u2,'14'=>$u2,'15'=>$u2,
            '16'=>$u3,'17'=>$u3,'18'=>$u3,'19'=>$u3,'20'=>$u3,
            '21'=>$u4,'22'=>$u4,'23'=>$u4,'24'=>$u4,'25'=>$u4,'26'=>$u4,'27'=>$u4,'28'=>$u4,'29'=>$u4,'30'=>$u4,
            '31'=>$u4,'32'=>$u4,'33'=>$u4,'34'=>$u4,'35'=>$u4,'36'=>$u4,'37'=>$u4,'38'=>$u4,
            '48'=>$u4,'49'=>$u4,
            '39'=>$u5,'41'=>$u5,'42'=>$u5,'43'=>$u5,'44'=>$u5,'45'=>$u5,'40'=>$u5,
            '46'=>$u5,'52'=>$u5,'47'=>$u5,'53'=>$u5,'54'=>$u5,'50'=>$u5,'51'=>$u5,'55'=>$u5
        ];

        $t1="GAUTAM KUMAR";$t2="DHARAM RAJ";$t3="LOKNATH TIRKEY";$t4="MARGUB ALAM";$t5="SUBHASH KAHLKHO";$t6="RAKESH MUNDA";
        $t7="PAWAN KACHHAP";$t8="NAKUL TIRKEY";$t9="PAWAN KACHHAP";$t10="BHIM MAHALI";$t11="UDAY THAKUR";$t12="DEEPAK RAJAK";$t13="DILIP KUMAR SHARMA";
        $ulbtxward=['1'=>$t12,'2'=>$t12,'3'=>$t12,'4'=>$t12,'5'=>$t12,
            '6'=>$t4,'8'=>$t4,'9'=>$t4,'10'=>$t4,
            '7'=>$t10,'11'=>$t10,'12'=>$t10,'13'=>$t10,'14'=>$t10,
            '15'=>$t1,'16'=>$t1,'17'=>$t1,'18'=>$t1,'28'=>$t1,
            '19'=>$t13,'20'=>$t13,'21'=>$t13,'22'=>$t13,'23'=>$t13,
            '24'=>$t7,'25'=>$t7,'26'=>$t7,'27'=>$t7,'38'=>$t7,
            '29'=>$t11,'37'=>$t11,
            '30'=>$t5,'31'=>$t5,'35'=>$t5,'36'=>$t5,
            '32'=>$t2,'33'=>$t2,'34'=>$t2,
            '39'=>$t11, '40'=>$t11,
            '41'=>$t3,'42'=>$t3,'46'=>$t3,'54'=>$t3,'55'=>$t3,
            '43'=>$t8,'44'=>$t8,
            '45'=>$t6,'47'=>$t6,'50'=>$t6,'51'=>$t6,'52'=>$t6,'53'=>$t6,
            '48'=>$t8,'49'=>$t8
        ];


        ?>
        <div class="panel-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Ward No.</th>
                            <th>App. No.</th>
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
                                $value["receiver_emp_name"]="";

                                foreach ($lists as $value) :
                                    $ex=explode('/',$value['ward_no']);
                                    $value['ward_no']=str_replace('A','',$ex[0]);
                                    if($value["receiver_user_type_id"]==17){
                                        $value["receiver_emp_name"]=$ulbdaward[$value['ward_no']];
                                    }
                                    if($value["receiver_user_type_id"]==19){
                                        $value["receiver_emp_name"]='ANWAR HUSSAIN';
                                    }
                                    if($value["receiver_user_type_id"]==18){
                                        $value["receiver_emp_name"]='DILIP SHARMA';
                                    }
                                    if($value["receiver_user_type_id"]==20){
                                        $value["receiver_emp_name"]=$ulbtxward[$value['ward_no']];
                                    }
                                    $value["receiver_emp_name"] = (new \App\Models\model_trade_level_pending_dtl(db_connect('db_rmc_trade')))->employeedetails($value["ward_mstr_id"], $value["receiver_user_type_id"], $value["ward_no"])['emp_name']??""; 
                                    ?>
                                    <tr>
                                        <td><?= ++$i; ?></td>
                                        <td><?= $value["ward_no"]??""; ?></td>
                                        <td><?= $value["application_no"]??""; ?></td>
                                        <td><?= $value["receiver_emp_name"]??"" ?></td>
                                        <td><?= $value["apply_date"]??""; ?></td>
                                        <td>
                                            <a class="btn btn-primary" target="_blank" href="<?php echo base_url().'/'.'tradeapplylicence/trade_licence_view/' . md5($value['aid']); ?>" role="button">View</a>
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
                    text: 'Excel',
                    extend: "excel",
                    title: "BTC Trade Application",
                    footer: { text: '' },
                    exportOptions: { columns: [ 0,1,2,3,4] }
                }]
        });
    });

</script>