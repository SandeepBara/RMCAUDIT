<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<script type="text/javascript">
	
</script>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
 <div id="page-head">
                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->

                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Trade</a></li>
                     <li class="active">Trade Application List  </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
    <!--Page content-->
    <div id="page-content">       
<div class="panel panel-bordered panel-dark">
						<div class="panel-heading">
							<h5 class="panel-title">Trade Application List</h5>
						</div>
						<div class="panel-body">
							<form class="form-horizontal">
								<div class="col-md-3">
									<label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
									<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($to_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
									<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
								</div>
								<div class="col-md-3">
									<label class="control-label" for="to_date"><b>Ward No.</b><span class="text-danger">*</span></label>
                                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                        <option value="all">ALL</option> 
                                        <?php foreach($wardList as $value):?>
                                        <option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id))?$ward_mstr_id==$value["ward_mstr_id"]?"SELECTED":"":"";?>><?=$value['ward_no'];?>
                                        </option>
                                        <?php endforeach;?>
                                    </select>
								</div>
								<div class="col-md-2">
									<label class="control-label" for="department_mstr_id">&nbsp;</label>
									<button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="button" onclick="get_trade_application_list()">Search</button>
								</div>
							</form>
						</div>
					</div>
					 
        <div class="panel panel-dark">
            <div class="panel-heading">
                
               <br>&nbsp; Results
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
                                        <th>Firm Name </th>
                                        <th>Application Type</th>
                                        <th>Applied Date</th>
                                        <th>Validity</th>
                                         <th>Action</th>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
 
<script type="text/javascript">
 
    function get_trade_application_list()
    {
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
                {
                    text: 'excel',
                    extend: "excel",
                    title: "Ward Wise Collection",
                    footer: { text: '' },
                    exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8] }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Ward Wise Collection",
                    download: 'open',
                    footer: { text: '' },
                    exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8] }
                }
                
            ],

            /* "columnDefs": [
                { "orderable": false, "targets": [4, 5] }
            ], */
            'ajax': {
                "type": "POST",
                'url':'<?=base_url('TradeDocument/get_trade_application_list_ajax');?>',
                "dataType": "json",
                'data': function(data){
                    data.from_date = $("#from_date").val();
                    data.to_date = $("#to_date").val();
                    data.ward_id = $("#ward_mstr_id").val(); 
    
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
                // Here the response
                var response = settings.json;
                var getTotalAmount = response.getTotalAmount;
                var total = response.total;
                $("#total_amount").text(getTotalAmount);
                $("#total").text(total);
            },
            order: [[0, 'DESC']],
            'columns': [
                { 'data': 's_no' },
                { 'data': 'ward_no' },
                { 'data': 'application_no'},
                { 'data': 'firm_name' },
                { 'data': 'application_type'}, 
                { 'data': 'apply_date' },
                { 'data': 'valid_upto' },
                { 'data' : 'view'},
            ]
        });
        $('#btn_search').click(function(){
            dataTable.draw();
        });
    }
	get_trade_application_list();
 </script>








 
