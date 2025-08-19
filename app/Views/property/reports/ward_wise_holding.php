<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Ward Wise Holding Report</h5>
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
                                    <option value='<?=$list['id'];?>'><?=$list['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <label class="col-md-2 text-bold">Amount Greater Than OR Equal To</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="number" id='amt_greater_than_or_equal' name="amt_greater_than_or_equal" class="form-control" value="1" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 text-bold">Not Paid From</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='fy_mstr_id' name="fy_mstr_id" class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($fyList)) {
                                    foreach ($fyList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>'><?=$list['fy'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <label class="col-md-3"></label>
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
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Holding No.</th>
										<th>Unique House No.</th>
                                        <th>Owner Name</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>From (QTR|FY)</th>
                                        <th>Due Amount</th>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script type="text/javascript">

var nIntervId;
const loadingAnimation = function(intervalResult) {
    var timeLap = 2;
    if(intervalResult==true) {
        $("#btn_search").val("LOADING .");
        nIntervId = setInterval(loadingAnimationStart, 1000);
    }
    if(intervalResult==false) {
        $("#btn_search").val("SEARCH");
        clearInterval(nIntervId);
    }

    function loadingAnimationStart() {
        if(timeLap==1){
            $("#btn_search").val("LOADING .");
            timeLap++;
        } else if(timeLap==2){
            $("#btn_search").val("LOADING ..");
            timeLap++;
        } else if(timeLap==3){
            $("#btn_search").val("LOADING ...");
            timeLap = 1;
        } else {
            $("#btn_search").val("SEARCH");
        }
    }
}
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#empTable').DataTable({
        'responsive': true,
        'processing': true,
        "deferLoading": false,
        
        "deferLoading": 0, // default ajax call prevent
        'serverSide': true,
        dom: 'Bfrtip',
        lengthMenu: [
            [10, 25, 50, 5000, 10000],
            ['10 rows', '25 rows', '50 rows', '5000 rows', '10000 rows']
        ],
        buttons: [
            'pageLength',
            {
                text: 'Excel Export',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    var search_ward_mstr_id = $('#ward_mstr_id').val();
                    var search_amt_greater_than_or_equal = $('#amt_greater_than_or_equal').val();
                    var search_fy_mstr_id = $('#fy_mstr_id').val();
                    if (search_ward_mstr_id=='') {
                        search_ward_mstr_id = "ALL";
                    }
                    if (search_fy_mstr_id=='') {
                        search_fy_mstr_id = "ALL";
                    }
                    var gerUrl = search_ward_mstr_id+'/'+search_amt_greater_than_or_equal+'/'+search_fy_mstr_id;
                    window.open('<?=base_url();?>/prop_report/wardWiseHoldingExcel/'+gerUrl);//.opener = null;
                }
            }
        ],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('prop_report/wardWiseHoldingAjax');?>',
            "deferRender": true,
            "dataType": "json",
            'data': function(data){
                // Append to data
                data.search_ward_mstr_id = $('#ward_mstr_id').val();
                data.search_amt_greater_than_or_equal = $('#amt_greater_than_or_equal').val();
                data.search_fy_mstr_id = $('#fy_mstr_id').val();
            },
            beforeSend: function () {
                loadingAnimation(true);
				$("#loadingDiv").show();
            },
            complete: function () {
                loadingAnimation(false);
				$("#loadingDiv").hide();
            },
        },
        /* "drawCallback": function (settings) { 
            // Here the response
            var response = settings.json;
            console.log(response);
        }, */
        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'holding_no' },
			{ 'data': 'new_holding_no' },
            { 'data': 'owner_name' },
            { 'data': 'mobile_no' },
            { 'data': 'address' },
            { 'data': 'from_qtr_fy' },
            { 'data': 'due_amt' },
        ]
    });
    $('#btn_search').click(function(){
        if($('#amt_greater_than_or_equal').val()>-1) {
            dataTable.draw();
        } else {
            $('#amt_greater_than_or_equal').css('border-color', 'red');
        }
    });
});
$('#amt_greater_than_or_equal').keypress(function(){
    $('#amt_greater_than_or_equal').css('border-color', 'green');
});
</script>
