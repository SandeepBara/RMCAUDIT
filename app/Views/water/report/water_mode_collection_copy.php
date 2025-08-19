<?= $this->include('layout_vertical/header');?>
<style type="text/css">
	.error{
		color: red;
	}
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">

<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
    
<!--CONTENT CONTAINER-->
<!--===================================================-->
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
<li><a href="#">Water</a></li>
<li><a href="#">Secondary</a></li>
<li class="active">Mode Wise Collection</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
		<div id="page-content">
			<div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title">Search</h5>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="myform" method="post" >
                        <div class="form-group">
                            <div class="col-md-3">
                                <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                            </div>

                            <div class="col-md-3">
                                <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                                <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" max="<?=date('Y-m-d');?>">
                            </div>

                            <div class="col-md-3">
                                <label class="control-label" for="Payment Mode"><b>Payment Mode</b><span class="text-danger">*</span> </label>
                                <select id="payment_mode" name="payment_mode" class="form-control">
                                    <option value="">ALL</option>
                                    <option value="CASH" <?=(isset($payment_mode))?($payment_mode=="CASH")?"selected":"":"";?>>Cash</option>
                                    <option value="CHEQUE" <?=(isset($payment_mode))?($payment_mode=="CHEQUE")?"selected":"":"";?>>Cheque</option>
                                    <option value="DD" <?=(isset($payment_mode))?($payment_mode=="DD")?"selected":"":"";?>>DD</option>
                                    <option value="ONLINE" <?=(isset($payment_mode))?($payment_mode=="ONLINE")?"selected":"":"";?>>Online</option>
                                </select>
                            </div>  

                            
                            <div class="col-md-2">
                                <label class="control-label">&nbsp;</label>
                                <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="button">Search</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
			<div class="panel panel-bordered panel-dark">
				<div class="panel-heading">
					<h5 class="panel-title">Mode Wise Collection</h5>
				</div>
				<div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
                            <div class="text-center text-danger text-bold">
                                Total Amount: <span >[<i id="total">0</i>]</span>
                            </div>
							<div class="table-responsive">
								<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Ward No.</th>
                                            <th>Consumer/Application No.</th>
                                            <th>Mobile No.</th>
                                            <th>Transaction No.</th>
                                            <th>Transaction Date</th>
                                            <th>Payment Mode</th>
                                            <th>Amount</th>
                                            <th>Cheque No.</th>
                                            <th>Cheuqe Date</th>
                                            <th>Bank Name</th>
                                            <th>Branch Name</th>
                                            <th>Collected By</th>  
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
		<!--===================================================-->
		<!--End page content-->
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>


<script type="text/javascript">
   //debugger;
    $(document).ready(function(){
        //$.fn.dataTable.ext.errMode = 'throw';
        var dataTable = $('#demo_dt_basic').DataTable({
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
                        var from_date = $('#from_date').val();
                        var to_date = $('#to_date').val();
                        var payment_mode = $('#payment_mode').val();
                        // var connection_type = $('#connection_type').val();                        
                        d = new Date();
                        var y = d.getFullYear();
                        var month = d.getMonth()+1;
                        month = month<10?'0'+month:month;
                        date = d.getDate();
                        date = date<10?'0'+date:date;
                        new_date = y+'-'+month+'-'+date;                        
                        if (from_date=='' || from_date==undefined) 
                        {
                            from_date = new_date;
                        }
                        if(to_date=='' || to_date==undefined)
                        {
                            to_date=new_date;
                        }
                        if(payment_mode=='' || payment_mode==undefined)
                        {
                            payment_mode='ALL';
                        }                        
                                        
                        var gerUrl = from_date+'/'+to_date+'/'+payment_mode;
                        window.open('<?=base_url();?>/WaterModeWiseCollection/report_copyExcel/'+gerUrl).opener = null;
                    }
                }
            ],

            /* "columnDefs": [
                { "orderable": false, "targets": [4, 5] }
            ], */
            'ajax': {
                "type": "POST",
                'url':'<?=base_url('WaterModeWiseCollection/report_copy');?>',            
                dataSrc: function ( data ) {
                    total_collection = data.total;
                    recordsTotal  = data.recordsTotal;
                    console.log(data);
                    $('#total').text(data.Total);
                    $('#apply').text('['+data.apply+']');
                    $('#not_apply').text('['+data.not_apply+']');
                    return data.data;
                },
                
                "deferRender": true,
                "dataType": "json",
                'data': function(data){
                    // Append to data
                    data.from_date = $('#from_date').val();
                    data.to_date = $('#to_date').val();
                    data.payment_mode = $('#payment_mode').val();
                    // data.connection_type = $('#connection_type').val();
                    // data.search_entry_type = $('#entry_type').val();
                },
                beforeSend: function () {
                    $("#btn_search").val("LOADING ...");
                    $("#loadingDiv").show();
                },
                complete: function () {
                $("#btn_search").val("SEARCH");
                $("#loadingDiv").hide();
                $('#from').text($('#from_date').val());
                $('#to').text($('#upto_date').val());
                },
            },

            'columns': [
                { 'data': 's_no' },
                { 'data': 'ward_no' },
                { 'data': 'c_a_no' },
                // { 'data': 'applicant_name' },
                { 'data': 'mobile_no' },
                { 'data': 'transaction_no' },
                { 'data': 'transaction_date' },
                { 'data': 'payment_mode' },
                { 'data': 'paid_amount' },                
                { 'data': 'cheque_no' },
                { 'data': 'cheque_date' },
                { 'data': 'bank_name' },
                { 'data': 'branch_name' },
                { 'data': 'emp_name' },

                
                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    // $("#footerResult").html(" (Total Collection - "+Total+")");
                    // var api = this.api();
                    // $(api.column(14).footer() ).html(Total);
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        $('#btn_search').click(function()
        {
            
            from_date_val = $('#from_date').val()
            to_date_val = $('#upto_date').val()
            if(from_date_val=='')
            {
                alert('please select from date !!!')
                return
            }
            if(to_date_val=='')
            {
                alert('please select to date !!!')
                return
            }

            if(new Date(from_date_val) > new Date(to_date_val))
            {
                alert('from data cannot be greater than to data')
                return
            }
            dataTable.draw();
        });
    });
    
</script>
