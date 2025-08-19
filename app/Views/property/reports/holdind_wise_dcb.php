<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Ward Wise DCB Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form id="myForm" method="post">
                        <div class="row">
                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_mstr_id' name="ward_mstr_id" class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($wardList)) {
                                    foreach ($wardList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>' <?=(isset($ward_mstr_id))?($ward_mstr_id==$list['id'])?"selected":"":"";?>><?=$list['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <label class="col-md-2 text-bold">Financial Year</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='fyear' name="fyear" class="form-control">
                                <?php
                                if (isset($fyList)) {
                                    foreach ($fyList as $list) {
                                ?>
                                    <option value='<?=$list;?>' <?=(isset($fyear))?($fyear==$list)?"selected":"":"";?>><?=$list;?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 text-right">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $topResult = '<h5 class="panel-title">Result</h5>';
            $total_current_holding = 0;
            $total_arrear_demand = 0;
            $total_current_demand = 0;
            $total_collection_from_no_of_hh = 0;
            $total_arrear_collection_amount = 0;
            $total_current_collection_amount = 0;
            $total_balance_hh = 0;
            $total_arrear_balance_amount = 0;
            $total_current_balance_amount = 0;
            $total_advance_amount = 0;
            $total_remaining_advance_amount = 0;
            if (isset($report_list)) {
                $i = 0;
                foreach ($report_list as $list) {
                    $total_current_holding = $total_current_holding + $list['current_holding'];
                    $total_arrear_demand = $total_arrear_demand + $list['arrear_demand'];
                    $total_current_demand = $total_current_demand + $list['current_demand'];
                    $total_collection_from_no_of_hh = $total_collection_from_no_of_hh + $list['collection_from_no_of_hh1'];
                    $total_arrear_collection_amount = $total_arrear_collection_amount + $list['arrear_collection_amount'] +$list['arrear_collection_amount2'] ;

                    //$total_current_collection_amount = $total_current_collection_amount + $list['current_collection_amount'];
                    // $total_current_collection_amount = $total_current_collection_amount + $list['actual_collection_amount2'];
                    $total_current_collection_amount = $total_current_collection_amount + $list['actual_collection_amount'] +$list['actual_collection_amount2'];

                    $total_balance_hh = $total_balance_hh + $list['balance_hh'];
                    $total_arrear_balance_amount = $total_arrear_balance_amount + $list['arrear_balance_amount'];
                    $total_current_balance_amount = $total_current_balance_amount + $list['current_balance_amount'];     
                    $total_advance_amount += $list['advance_amount'];
                    $total_remaining_advance_amount+=$list['remaining_advance_amount']??0;
                }
                $topResult =    '<div class="panel-control">
                                    <button class="btn btn-info" onclick="clickTotalPieChartOnTop();">Pie-Chart</button>
                                </div>
                                <h5 class="panel-title">Result</h5>';
                $topResult2 =   '<h5 class="panel-title" style="margin: 0px; padding: 0px;"><b>Current</b> (Total Demand - '.round($total_current_demand, 2).', Total Advance(Avg. Calculation) - '.round($total_advance_amount, 2).' , Total Collection - '.round($total_current_collection_amount, 2).', Total Balance - '.round($total_current_balance_amount-$total_advance_amount, 2).')</h5>
                                <h5 class="panel-title" style="margin: 0px; padding: 0px;"><b>Arrear</b> (Total Demand - '.round($total_arrear_demand, 2).', Total Collection - '.round($total_arrear_collection_amount, 2).', Total Balance - '.round($total_arrear_balance_amount, 2).')</h5>
                                <h5 class="panel-title" style="margin: 0px; padding: 0px;"><b>Remaining Advance</b> ('.round($total_remaining_advance_amount, 2).')</h5>';
            }
        ?>        
        <div class="panel panel-dark">
            <div class="panel-heading">
                <?=$topResult;?>
            </div>
            <div class="panel-body">                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th rowspan="4">#</th>
                                        <th rowspan="4">Holding No</th>
                                        <th rowspan="4">SAF No</th>
                                        <th rowspan="4">Owner Name</th>
                                        <th rowspan="4">Ward No</th>
                                        <th rowspan="4">Type Of HH</th>
                                        <th rowspan="4">Status of Demand Collectable/Not Collectable/Litigation</th>
                                        <th colspan="5">Total Demand</th>
                                        <th colspan="6">Collection against Demand</th>
                                        <th colspan="3">Balance Due</th>
                                        <th colspan="3">Other Amount Collected</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3">Arrear Demand</th>
                                        <th rowspan="2">Current Demand</th>
                                        <th rowspan="2">Total Demand</th>
                                        <th colspan="2">Collection During the Period</th>
                                        <th colspan="2">Rebate</th>
                                        <th colspan="2">Adjustment</th>
                                        <th rowspan="2">Arrear Due</th>
                                        <th rowspan="2">Current Due</th>
                                        <th rowspan="2">Total Due</th>
                                        <th rowspan="2">Penalty Amount</th>
                                         <th rowspan="2">Interest Amount</th>
                                    </tr>
                                    <tr>
                                        <th>Opening Arrear Demand</th>
                                        <th>Arrear Demand generated During the Year</th>
                                        <th>Arrear Demand deactivated During the Year</th>
                                        <th>From Arrear Demand</th>
                                        <th>Current Year Demand</th>
                                        <th>From Arrear Demand</th>
                                        <th>From Current Demand</th>
                                        <th>From Arrear Demand</th>                                        
                                        <th>From Current Demand</th>
                                    </tr>
                                    <tr>
                                        <th>(1)</th>
                                        <th>(2)</th>
                                        <th>(3)</th>
                                        <th>(4)</th>
                                        <th>(5=1+2+3+4)</th>
                                        <th>(6)</th>
                                        <th>(7)</th>
                                        <th>(8)</th>
                                        <th>(9)</th>
                                        <th>(10)</th>
                                        <th>(11)</th>
                                        <th>(12=1+2-3-6-8)</th>
                                        <th>(13=4-7-9-11)</th>
                                        <th>(14=12+13)</th>
                                        <th>(15)</th>
                                        <th>(16)</th>
                                    </tr>
                                </thead>
                                <tbody>
										
								</tbody>  
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
<div class="modal" id="pie-chart-default-modal" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Pie Chart</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div id="piechart"></div>
            </div>
        </div>
    </div>
</div>
<!--End Default Bootstrap Modal-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script type="text/javascript">
    $(document).ready(function(){
        //$.fn.dataTable.ext.errMode = 'throw';
        var dataTable = $('#empTable').DataTable({
            //'responsive': true,
            'processing': true,
            
            "deferLoading": 0, // default ajax call prevent
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, 5000],
                ['10 rows', '25 rows', '50 rows', '5000 rows']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var gerUrl = 'true?';
                        var formData = $("#myForm").serializeArray();
                        $.each(formData, function(i, field) {
                            gerUrl += (field.name+'='+field.value)+"&";
                        });
                        window.open('<?=base_url();?>/prop_report/holdingWiseDcb/'+gerUrl).opener = null;
                    }
                }
            ],
            'ajax': {
                "type": "POST",
                'url':'<?=base_url('prop_report/holdingWiseDcb');?>',
                dataSrc: function ( data ) {
                    total_collection = data.total_collection;
                    recordsTotal  = data.recordsTotal;
                    return data.data;
                },
                "deferRender": true,
                "dataType": "json",
                'data': function(data){
                    var formData = $("#myForm").serializeArray();
                    $.each(formData, function(i, field) {
                        data[field.name] = field.value;
                    });
                    
                },
                beforeSend: function () {
                    $("#btn_search").val("LOADING ...");
                    $("#loadingDiv").show();
                },
                complete: function () {
                $("#btn_search").val("SEARCH");
                    $("#loadingDiv").hide();
                },
            },
            
            'columns': [
                { 'data': 's_no' },
                { 'data': 'holding_no' },
                { 'data': 'saf_no' },
                { 'data': 'owner_name' },
                { 'data': 'ward_no' },
                { 'data': 'holding_type' },
                { 'data': 'status' },
                { 'data': 'outstanding' },
                { 'data': 'arrear_demand_generated' },
                { 'data': 'arrear_demand_deactiveted' },
                { 'data': 'current_demand' },
                { 'data': 'total_demand' },
                { 'data': 'arrear_collection' },
                { 'data': 'current_collection' },
                { 'data': 'arrear_rebate' },
                { 'data': 'current_rebate' },
                { 'data': 'arrear_adjustment' },
                { 'data': 'current_adjustment' },
                { 'data': 'arrear_balance' },
                { 'data': 'current_balance' },
                { 'data': 'total_balance' },
                { 'data': 'penalty' },
                { 'data': 'intrest' },
            ],
            drawCallback: function( settings )
            {
                try
                {
                    // $("#footerResult").html(" (Total Holding - "+recordsTotal+", Total Collection - "+total_collection+")");
                    // var api = this.api();
                    // $(api.column(2).footer() ).html(recordsTotal);
                    // $(api.column(8).footer() ).html(total_collection);
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }
        });
        $('#btn_search').click(function(){
            dataTable.draw();
        });
        dataTable.draw();
    });
</script>
