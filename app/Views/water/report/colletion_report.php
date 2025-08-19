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
        <li><a href="#">Report</a></li>
        <li class="active">TC Wise Collection Report  </li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">       
        <div class="panel panel-bordered panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Collection Report</h5>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <div class="col-md-2">
                        <label class="control-label" for="tc_no"><b>TC Name </b> <span class="text-danger">*</span></label>
                        <select name="tc_id" id="tc_id" class="form-control">
                            <option value="0">All</option>
                            <?php
                            if($tc)
                            {
                                foreach($tc as $val)
                                {
                                    if($val['lock_status']==1)
                                        continue;
                                ?>
                                <option value="<?php echo $val['id'];?>"><?php echo $val['emp_name'];?> (<?=$val['employee_code']?>)</option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>        
                    <div class="col-md-2">
                        <label class="control-label" for="tc_no"><b>Payment Mode</b> <span class="text-danger">*</span></label>
                        <select name="pay_mode" id="pay_mode" class="form-control">
                            <option value="all">All</option>
                            <option value="CASH">CASH</option>
                            <option value="CHEQUE">CHEQUE</option>
                            <option value="DD">DD</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="control-label" for="from_date"><b>From Date</b><span class="text-danger">*</span> </label>
                        <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($to_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                    </div>
                    <div class="col-md-2">
                        <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                        <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="control-label" for="department_mstr_id">&nbsp;</label>
                        <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="button" onclick="get_tc_wise_collection()">Search</button>
                    </div>
                </form>
            </div>
        </div>
                
        <div class="panel panel-dark">
            <div class="panel-heading">
                
            <br>&nbsp; Result 
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>                                        
                                        <th>Ward No.</th>
                                        <th>Transaction No.</th>
                                        <th>Transaction Date</th>                                                                            
                                        <th>Payment Type</th>
                                        <th>Amount</th>
                                        <th>Consumer No.</th>
                                        <th>Application No.</th>  
                                        <th>Payment Mode</th> 
                                        <th>Collected By</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                    <td colspan="5" style="text-align: right;color: red;font-weight: bold;">Total</td>
                                    <td style="text-align: right;color: red;font-weight: bold;" id="ttl"></td>
                                    </tr>
                                </tfoot>
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
 
    function get_tc_wise_collection()
    {
        $.fn.dataTable.ext.errMode = 'throw';
        var dataTable = $('#empTable').DataTable({
            'responsive': true,
            'processing': true,
            
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, 5000,10000,15000],
                ['10 rows', '25 rows', '50 rows', '5000 rows','10000 rows','15000 rows',]
            ],
            buttons: [
                'pageLength',
                {
                    text: 'excel',
                    extend: "excel",
                    title: "Collection Report",
                    footer: { text: '' },
                    exportOptions: { columns: [ 0, 1,2,3,4,5,6,7,8,9] }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Collection Report",
                    download: 'open',
                    footer: { text: '' },
                    exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9] },
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                }
                
            ],

            /* "columnDefs": [
                { "orderable": false, "targets": [4, 5] }
            ], */
            'ajax': {
                "type": "POST",
                'url':'<?=base_url('water_report/colletion_report');?>',
                "dataType": "json",
                'data': function(data){ 
                    console.log(data);
                    console.log(data.length);
                    data.tc_id = $("#tc_id").val();
                    //data.appl_type = $("#app_type").val();
                    data.paymnt_mode = $("#pay_mode").val();
                    data.from_date = $("#from_date").val();
                    data.to_date = $("#to_date").val();
    
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
                $("#ttl").text(response.total);
                console.log(response.total);
            },
            order: [[0, 'DESC']],
            'columns': [
                { 'data': 's_no' },
                { 'data': 'ward_no' },
                { 'data': 'transaction_no' },
                { 'data': 'transaction_date' },
                { 'data': 'transaction_type' },
                { 'data': 'paid_amount' },
                { 'data': 'consumer_no' },
                { 'data': 'application_no' },
                { 'data': 'payment_mode' },
                { 'data': 'emp_name' },
                { 'data': 'link' },
            ]
        });
        $('#btn_search').click(function(){
            dataTable.draw();
        });
    }
	get_tc_wise_collection();
 </script>








 
