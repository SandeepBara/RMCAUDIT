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
                    <li class="active">Level Wise SAF For Pending Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
						<div class="panel-heading" style="background-color: #298da0;">
                            <h3 class="panel-title">Level Wise SAF For Pending</h3>
                        </div>
                        <div class="panel-body">
                            <div class ="row">
                                <div class="col-md-12">
                                  <form class="form-horizontal" method="get" action="<?php echo base_url('levelwisependingform/reportleveltimetaken2');?>">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
<!--                                            <input type="date" id="from_date" name="from_date" min="2024-02-07" value="2024-02-07" max="--><?php //=date('Y-m-d');?><!--" class="form-control" placeholder="From Date" value="--><?php //=(isset($from_date))?$from_date:date('Y-m-d');?><!--" >-->
                                            <input type="date" id="from_date" name="from_date"   max="<?=date('Y-m-d');?>" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
                                            <input type="date" id="to_date" name="to_date" class="form-control" max="<?=date('Y-m-d');?>" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="btn_search">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" id="btn_search" type="submit">Search</button>

                                        </div>
                                        <div class="col-md-2">
<!--                                            <div class="panel-control">-->
<!--                                                <label class="control-label" for="btn_search">&nbsp;</label>-->
<!--                                                <a href="--><?php //echo base_url('levelwisependingform/exportreportleveltotaldays/');?><!--" class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> EXCEL EXPORT</a>-->
<!--                                            </div>-->
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
                            <th colspan="3">Pending at TCA</th>
                            <th colspan="3">Pending at ULB Level</th>
                        </tr>
                        <tr>
                            <th>Timeline</th>
                            <th colspan="3">T+2</th>
                            <th colspan="3">T+7</th>
                            <th colspan="3">T+15</th>
                            <th colspan="3">T+25</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Done</th>
                            <th>In Process</th>
                            <th>Pending</th>
                            <th>Done</th>
                            <th>In Process</th>
                            <th>Pending</th>
                            <th>Done</th>
                            <th>In Process</th>
                            <th>Pending</th>
                            <th>Ulb Tax Collector</th>
                            <th>City Manager/Section Incharge</th>
                            <th>DMC</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th colspan="3">2 Days</th>
                            <th colspan="3">5 Days</th>
                            <th colspan="3">8 Days</th>
                            <th>5 Days</th>
                            <th>3 Days</th>
                            <th>2 Days</th>
                        </tr>
                        </thead>
                        <tbody>
                           <?php if($totalsaf!=0){ 
                            
                                $datotal=$dealingassistantdonel+$dealingassistantdoneg+
                                $dealingassistantprogressl+$dealingassistantpendingg;
                                $bo_donetotal=$backofficedonel+$backofficedoneg;
                                if($datotal!=$bo_donetotal)
                                {
                                    $bo_done=$datotal;
                                }else{
                                    $bo_done=$bo_donetotal;
                                }
                            ?>
                           <tr style="font-size: 15px;">
                                <th><?= $totalsaf ?></th>
                                <th></th>
                                <th><?= $bo_done ?>
                            
                        </th>
                                <th><?= $backofficeprogressl ?></th>
                                <th>
                                    <a target="_blank" href="<?php echo base_url('levelwisependingform/reportleveltimetaken2?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Back+Office');?>">
                                        <button class="activelink" formtarget="_blank"><?= $backofficependingg ?></button>
                                    </a>
                                </th>
                                <th><?= $dealingassistantdonel+$dealingassistantdoneg ?></th>
                                <th><?= $dealingassistantprogressl ?></a>
                                </th>
                                <th>
                                    <a target="_blank" href="<?php echo base_url('levelwisependingform/reportleveltimetaken2?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Dealing+Assistant');?>">
                                        <button class="activelink" formtarget="_blank"><?= $dealingassistantpendingg ?></button>
                                    </a>
                                </th>
                                <th><?= $taxcollectordonel+$taxcollectordoneg ?></th>
                                <th><?= $taxcollectorprogressl ?></th>
                                <th>
                                    <a target="_blank" href="<?php echo base_url('levelwisependingform/reportleveltimetaken2?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Tax+Collector');?>">
                                        <button class="activelink" formtarget="_blank"><?= $taxcollectorpendingg ?></button>
                                    </a>
                                </th>
                                <th>
                                    <a target="_blank" href="<?php echo base_url('levelwisependingform/reportleveltimetaken2?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=ULB+Tax+Collector');?>">
                                        <button class="activelink" formtarget="_blank"><?= $ulbtaxcollectorpendingg ?></button>
                                    </a>
                                </th>
                                <th>
                                    <a target="_blank" href="<?php echo base_url('levelwisependingform/reportleveltimetaken2?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=ULB+Tax+Collector');?>">
                                        <button class="activelink" formtarget="_blank"><?= $propertysectioninchargependingg ?></button>
                                    </a>
                                </th>
                                <th>
                                    <a target="_blank" href="<?php echo base_url('levelwisependingform/reportleveltimetaken2?report_type=list&from_date='.$from_date.'&to_date='.$to_date.'&filter_type=Executive+Officer');?>">
                                        <button class="activelink" formtarget="_blank"><?= $executiveofficerpendingg ?></button>
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