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
                <h5 class="panel-title">GB Saf Wise Demand Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form method="post" action="<?php echo base_url('prop_report/exportgbsafreport');?>">
                    <div class="row">
                            <label class="col-md-2 text-bold">Financial Year</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='fy_mstr_id' name="fy_mstr_id" class="form-control">
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
                            <div class="col-md-5 text-right">
                                <button class="btn btn-primary" id="btn_search" type="button">Search</button>
                            </div>
                            <div class="col-md-2">
                                <div class="panel-control">
                                    <label class="control-label" for="btn_search">&nbsp;</label>
                                    <button type="submit"  class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> EXCEL EXPORT</button>
                                </div>
                            </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-sm-12" id="summary">
                    <h5 class="panel-title" style="margin: 0px; padding-bottom: 20px;" id="summaryreport">
                    </h5>
                </div>

                <table class="table table-striped table-bordered text-sm table-responsive datatable" id="demandTable">
                    <thead><tr style="background-color: #e5eced;">
                        <th>Sl No.</th>
                        <th>New Ward No.</th>
                        <th>GB SAF No.</th>
                        <th>Name</th>
                        <th>Address</th>
<!--                        <th>Date From</th>-->
<!--                        <th>Date Upto</th>-->
                        <th>Due Amount</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <!--End page content-->
</div>
<!--END CONTENT CONTAINER-->
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

<script>
    var temp = "ALL";
    $(document).ready(function(){
        function renderdataable(){
            $.fn.dataTable.ext.errMode = 'throw';
            var dataTable = $('#demandTable').DataTable({
                'responsive': true,
                'processing': true,
                'destroy': true,
                'searching': true,
                'serverSide': true,
                dom: 'Bfrtip',
                lengthMenu: [
                    [10, 25, 50, 5000],
                    ['10 rows', '25 rows', '50 rows', '5000 rows']
                ],
                buttons: [
                    'pageLength',
                ],

                /* "columnDefs": [
                    { "orderable": false, "targets": [4, 5] }
                ], */
                'ajax': {
                    "type": "POST",
                    'url':'<?=base_url('prop_report/demandreportGbsafAjax');?>',
                    "dataType": "json",
                    'data': function(data){
                        // Append to data
                        data.btn_search = temp;
                        data.new_ward_mstr_id = $('#new_ward_mstr_id').val();
                        data.fy_mstr_id = $('#fy_mstr_id').val();
                        // data.saf_no = $('#saf_no').val();
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
                 'drawCallback': function (settings) {
                     var response = settings.json;
                     $("#summary #summaryreport").html("<b>Current</b> (Total Demand - "+response.summary.current_demand+", Total Collection - "+response.summary.current_collection+" )");
                 },
                order: [[0, 'ASC']],
                'columns': [
                    { 'data': 's_no' },
                    { 'data': 'ward_no' },
                    { 'data': 'application_no' },
                    { 'data': 'name' },
                    { 'data': 'address' },
                    // { 'data': 'datefrom' },
                    // { 'data': 'dateupto' },
                    { 'data': 'total_demand' },
                    { 'data': 'status' },
                ]
            });
        }
        $('#btn_search').click(function(){
            temp='BY';
            renderdataable();
        });
    });
</script>