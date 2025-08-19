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
                    <form method="post">
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
                                <select id='fy_mstr_id' name="fy_mstr_id" class="form-control">
                                    <!-- <option value=''>ALL</option> -->
                                <?php
                                if (isset($fyList)) {
                                    foreach ($fyList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>' <?=(isset($fy_mstr_id))?($fy_mstr_id==$list['id'])?"selected":"":"";?>><?=$list['fy'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!--label class="col-md-2 text-bold">Property Type</label>
                            <div class="col-md-6">
                                <div class="checkbox">
                                    <input type="checkbox" id="residencial" name="residencial" class="magic-checkbox" value="residencial" <?=(isset($residencial))?"checked":"";?> >
                                    <label for="residencial">Residential</label>
        
                                    <input type="checkbox" id="non_residential" name="non_residential" class="magic-checkbox" value="non_residential" <?=(isset($non_residential))?"checked":"";?>>
                                    <label for="non_residential">Non-Residential</label>

                                    <input type="checkbox" id="vacant_land" name="vacant_land" class="magic-checkbox" value="vacant_land" <?=(isset($vacant_land))?"checked":"";?>>
                                    <label for="vacant_land">Vacant land</label>

                                    <input type="checkbox" id="government_building" name="government_building" class="magic-checkbox" value="government_building" <?=(isset($government_building))?"checked":"";?>>
                                    <label for="government_building">Government</label>
                                </div>
                            </div-->
                            <div class="col-md-5 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
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
            if (isset($report_list)) {
                $i = 0;
                foreach ($report_list as $list) {
                    $total_current_holding = $total_current_holding + $list['current_holding'];
                    $total_arrear_demand = $total_arrear_demand + $list['arrear_demand'];
                    $total_current_demand = $total_current_demand + $list['current_demand'];
                    $total_collection_from_no_of_hh = $total_collection_from_no_of_hh + $list['collection_from_no_of_hh'];
                    $total_arrear_collection_amount = $total_arrear_collection_amount + $list['arrear_collection_amount'];
                    $total_current_collection_amount = $total_current_collection_amount + $list['current_collection_amount'];
                    $total_balance_hh = $total_balance_hh + $list['balance_hh'];
                    $total_arrear_balance_amount = $total_arrear_balance_amount + $list['arrear_balance_amount'];
                    $total_current_balance_amount = $total_current_balance_amount + $list['current_balance_amount'];     
                    $total_advance_amount += $list['advance_amount'];
                }
                $topResult =    '<div class="panel-control">
                                    <button class="btn btn-info" onclick="clickTotalPieChartOnTop();">Pie-Chart</button>
                                </div>
                                <h5 class="panel-title">Result</h5>';
                $topResult2 =   '<h5 class="panel-title" style="margin: 0px; padding: 0px;"><b>Current</b> (Total Demand - '.round($total_current_demand, 2).', Total Advance(Avg. Calculation) - '.round($total_advance_amount, 2).' , Total Collection - '.round($total_current_collection_amount, 2).', Total Balance - '.round($total_current_balance_amount-$total_advance_amount, 2).')</h5>
                                <h5 class="panel-title" style="margin: 0px; padding: 0px;"><b>Arrear</b> (Total Demand - '.round($total_arrear_demand, 2).', Total Collection - '.round($total_arrear_collection_amount, 2).', Total Balance - '.round($total_arrear_balance_amount, 2).')</h5>';
            }
        ?>        
        <div class="panel panel-dark">
            <div class="panel-heading">
                <?=$topResult;?>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <?=$topResult2??"";?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th colspan="3"></th>
                                        <th colspan="3" class="text-center">DEMAND</th>
                                        <th></th>
                                        <th colspan="3" class="text-center">COLLECTION</th>
                                        <th></th>
                                        <th colspan="3" class="text-center">BALANCE</th>
                                        <th colspan="2" class="text-center">%</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>House Hold</th>
                                        <th>Arrear</th>
                                        <th>Current</th>
                                        <th>Total</th>
                                        <th>Collection From No of HH</th>
                                        <th>Arrear</th>
                                        <th>Current</th>
                                        <th>Total</th>
                                        <th>Balance HH</th>
                                        <th>Arrear</th>
                                        <th>Current</th>
                                        <th>Total</th>
                                        <th>HH % Cover</th>
                                        <th>Amount % Cover</th>
                                        <th>Advance</th>
                                        <th>Pie-Chart</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (isset($report_list)) {
                                    $i = 0;
                                    foreach($report_list as $list)
                                    {
                                        ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$list['ward_no'];?></td>
                                            <td><?=$list['current_holding'];?></td>
                                            <td><?=round($list['arrear_demand'],2);?></td>
                                            <td><?=round($list['current_demand'],2);?></td>
                                            <td><?=round($list['total_demand'],2);?></td>
                                            <td><?=$list['collection_from_no_of_hh'];?></td>
                                            <td><?=round($list['arrear_collection_amount'],2);?></td>
                                            <td><?=round($list['current_collection_amount'],2);?></td>
                                            <td><?=round($list['total_collection_amount'],2);?></td>
                                            <td><?=$list['balance_hh'];?></td>
                                            <td><?=round($list['arrear_balance_amount'],2);?></td>
                                            <td><?=round($list['current_balance_amount'],2);?></td>
                                            <td><?=round($list['total_balance_amount'],2);?></td>
                                            <td><?=$list['hh_percentage'];?></td>
                                            <td><?=$list['amount_percentage'];?></td>
                                            <td><?=$list['advance_amount'];?></td>
                                            <td><button class="btn btn-pink btn-icon btn-circle" onclick="view_pie_chart('WARD NO <?=$list['ward_no'];?>', '<?=$list['total_demand'];?>', '<?=$list['total_collection_amount'];?>', '<?=$list['total_balance_amount'];?>');"><i class="fa fa-pie-chart"></i></button></td>
                                        </tr>
                                        <?php
                                    }
                                    $grand_total_demand=$total_arrear_demand+$total_current_demand;
                                    $grand_total_demand=$grand_total_demand==0?1:$grand_total_demand;
								?>
								</tbody>
                                <tfoot>
                                    <tr class="bg-dark">
										<td></td>
										<td>Total</td>
										<td><?=$total_current_holding;?></td>
										<td><?=round($total_arrear_demand, 2);?></td>
                                        <td><?=round($total_current_demand, 2);?></td>
                                        <td><?=round($grand_total_demand, 2);?></td>
										<td><?=$total_collection_from_no_of_hh;?></td>
										<td><?=round($total_arrear_collection_amount, 2);?></td>
                                        <td><?=round($total_current_collection_amount, 2);?></td>
                                        <td><?=round($total_arrear_collection_amount+$total_current_collection_amount, 2);?></td>
										<td><?=$total_balance_hh;?></td>
										<td><?=round($total_arrear_balance_amount, 2);?></td>
                                        <td><?=round($total_current_balance_amount, 2);?></td>
                                        <td><?=round($total_arrear_balance_amount+$total_current_balance_amount, 2);?></td>
										<td><?=round(($total_collection_from_no_of_hh*100)/($total_current_holding==0?1:$total_current_holding), 2);?></td>
										<td><?=round((($total_arrear_collection_amount+$total_current_collection_amount)*100)/ ($grand_total_demand), 2) ?? 0;?></td>
                                        <td></td>
                                        <td><button id="footer_total_pie_chart_btn" class="btn btn-pink btn-icon btn-circle" onclick="view_pie_chart('ALL WARD', '<?=$total_arrear_demand+$total_current_demand;?>', '<?=$total_arrear_collection_amount+$total_current_collection_amount;?>', '<?=$total_arrear_balance_amount+$total_current_balance_amount;?>');"><i class="fa fa-pie-chart"></i></button></td>
									</tr>
                                </tfoot>
								<?php
                                }
                                ?>
                                
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
    var ward_no = "";
    var total_demand_amount = 0;
    var total_collection_amount = 0;
    var total_balance_amount = 0;
    function view_pie_chart(ward_no, demand_amount, collection_amount, balance_amount) {
        this.ward_no = ward_no;
        this.total_demand_amount = parseFloat(demand_amount);
        this.total_collection_amount = parseFloat(collection_amount);
        this.total_balance_amount = parseFloat(balance_amount);
        
        $("#pie-chart-default-modal").modal('show');
        google.charts.load('current', {'packages':['ImageChart']});
        google.charts.setOnLoadCallback(drawChart);
    }
    $("#btn_search").click(function(){
        $("#btn_search").val("LOADING ...");
    });
    
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Task', 'Hours per Day'],
            ['Balance',      this.total_balance_amount],
            ['Collection',      this.total_collection_amount],
        ]);

        var options = {
            cht: 'p',
            pieSliceText: 'label',
            title: this.ward_no+', DCB PIE-CHART (TOTAL DEMAND - '+this.total_demand_amount+')',
            width: '100%', 
            height: '100%',
            slices: {2: {offset: 0.2}},
            labels: 'value',
            slices: {  1: {offset: 0.2}},
            colors: ['#FF6347', '#8FBC8F'],
        };
        var chart = new google.visualization.ImageChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
$(document).ready(function(){
    var dataTable = $('#dataTableID').DataTable({
        responsive: false,
        "searching":false,
        dom: 'Bfrtip',
        lengthMenu: [
            [ -1],
            [ 'Show all']
        ],
        buttons: [
            {
                text: 'Excel',
                extend: "excel",
                title: "Ward Wise DCB Report (<?=date ("Y-m-d H:i:s A")?>)",
                footer: true,
                exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15] }
            },
            {
                text: 'Print',
                extend: 'print',
                footer: true,
                title: "Ward Wise DCB Report (<?=date ("Y-m-d H:i:s A")?>)",
                exportOptions: { columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15] }
            }
        ]
    });
    
    //view_pie_chart('47392302.59', '38948129.90', '8444172.69');
});
function view_pie_chart_click(total_demand, total_collection_amount, total_balance_amount) {
    $("#pie-chart-default-modal").modal('show');
}
const clickTotalPieChartOnTop = function() {
    $("#footer_total_pie_chart_btn").trigger("click");
}
</script>
