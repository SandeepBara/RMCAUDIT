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
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Search Applicant</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="form-group">
							<div class="col-md-3">
								<label class="control-label" for="from_date"><b>From Date</b> </label>
								<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
							</div>
							<div class="col-md-3">
								<label class="control-label" for="upto_date"><b>To Date</b> </label>
								<input type="date" id="upto_date" name="upto_date" class="form-control" placeholder="Upto Date" value="<?=date('Y-m-d');?>">
							</div>
							<div class="col-md-3">
								<label class="control-label text-bold">Ward No.</label>
                                <select id='ward_mstr_id' class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($ward_list)) {
                                    foreach ($ward_list as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>'><?=$list['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>  
							<div class="col-md-3">
								<label class="control-label" for="srchbtn">&nbsp;</label>
								<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="button">SEARCH</button>
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
                                        <th>Application No.</th>
                                        <th>Firm Name</th>
                                        <th>Application Type</th>
                                        <th>Apply Date</th>
                                        <th>Apply By</th>
                                        <th>View</th>     
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
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#empTable').DataTable({
        'responsive': true,
        'processing': true,
        
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
            'url':'<?=base_url('TradeSearchApplicant/tradeapplicationlistAjax');?>',
            "dataType": "json",
            'data': function(data){
                // Append to data
                data.search_from_date = $('#from_date').val();
                data.search_upto_date = $('#upto_date').val();
                data.search_ward_mstr_id = $('#ward_mstr_id').val();                
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
       /* 'drawCallback': function (settings) { 
            // Here the response
            var response = settings.json;
            console.log(response);
        },*/
        
        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'application_no' },
            { 'data': 'firm_name' },
            { 'data': 'application_type' },
            { 'data': 'apply_date' },
            { 'data': 'emp_name' },
            { 'data': 'view' },
                        
        ]
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});
</script>
