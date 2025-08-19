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
                            <label class="col-md-2 text-bold">Financial Year</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='fy_mstr_id' name="fy_mstr_id" class="form-control">
                                    <!-- <option value=''>ALL</option> -->
                                <?php
                                if (isset($fyList)) {
                                    foreach ($fyList as $list) {
                                ?>
                                    <option value='<?=$list['fy'];?>' <?=(isset($fy_mstr_id))?($fy_mstr_id==$list['id'])?"selected":"":"";?>><?=$list['fy'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-2 text-right">
                                <input type="button" id="btn_search" class="btn btn-primary btn-block" value="SEARCH" />                                
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
                                        <th colspan="5"></th>
                                        <th colspan="3" class="text-center">Rebate</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Holding No</th>
                                        <th>Payable Amount</th>
                                        <th>Amount Paid</th>
                                        <th>First Quater Rebate</th>
                                        <th>JSK (2.5%)</th>
                                        <th>Online (5%)</th>
                                        <th>Total Rebate</th>
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
                    var fy_year = $('#fy_mstr_id').val();
                    var gerUrl = search_ward_mstr_id;
                    window.open('<?=base_url();?>/prop_report/holdingWiseRebateExcel2/'+search_ward_mstr_id+'/'+fy_year).opener = null;
                }
            }
        ],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('prop_report/holdingWiseRebate2Ajax');?>',
            "deferRender": true,
            "dataType": "json",
            'data': function(data){
                // Append to data
                data.search_ward_mstr_id = $('#ward_mstr_id').val();
                data.search_fy_mstr_id = $('#fy_mstr_id').val();
            },
            beforeSend: function () {
                $("#btn_search").val("LOADING ...");
				$("#loadingDiv").show();
            },
            complete: function () {
               $("#btn_search").val("SEARCH");
			   $("#loadingDiv").hide();
            },
            error: function (jqXHR, exception) {
                console.log(jqXHR);
                console.log(exception);
                $("#loadingDiv").hide();
            },
            timeout: 120000
        },
        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'holding_no' },
            { 'data': 'payable_amt' },
            { 'data': 'paid_amt' },
            { 'data': 'dtd_discount_amt' },
            { 'data': 'jsk_discount_amt' },
            { 'data': 'online_discount_amt' },
            { 'data': 'total_rebate' },
        ]
    });

    $('#btn_search').click(function(){
        
        dataTable.draw();
    });
});
</script>
