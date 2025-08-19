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
                <h5 class="panel-title">Holding Wise Rebate</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
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
                            <div class="col-md-2 text-right">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="4" class="text-center">Rebate</th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Holding No</th>
                                        <th>Holding Type</th>
                                        <th>Total Payable Amount</th>
                                        <th>No of Transaction</th>
                                        <th>No of Transaction By JSK</th>
                                        <th>Discount Amount By JSK</th>
                                        <th>No of Transaction By Online</th>
                                        <th>Discount Amount By Online</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10">SEARCHING...</td></tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10"></td></tr>
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
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#dataTableID').DataTable({
        'responsive': true,
        'processing': true,
        'language': {
            'processing': '<div class="load8"><div class="loader"></div></div>...',
        },
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
                    var search_ward_mstr_id = $('#ward_mstr_id').val();
                    if (search_ward_mstr_id=='') {
                        search_ward_mstr_id = "ALL";
                    }
                    var gerUrl = search_ward_mstr_id;
                    window.open('<?=base_url();?>/prop_report/holdingWiseRebateExcel/'+gerUrl).opener = null;
                }
            }
        ],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('prop_report/holdingWiseRebateAjax');?>',
            "deferRender": true,
            "dataType": "json",
            'data': function(data){
                // Append to data
                data.search_ward_mstr_id = $('#ward_mstr_id').val();
            },
            beforeSend: function () {
                $("#btn_search").val("LOADING ...");
            },
            complete: function () {
               $("#btn_search").val("SEARCH");
            },
        },

        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'holding_no' },
            { 'data': 'holding_type' },
            { 'data': 'total_payable_amt' },
            { 'data': 'no_of_transaction' },
            { 'data': 'no_of_transaction_by_jsk' },
            { 'data': 'total_discount_amt_by_jsk' },
            { 'data': 'no_of_transaction_by_online' },
            { 'data': 'total_discount_amt_by_online' },
        ]
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});
</script>
