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
                <h5 class="panel-title">Property Collection Report With Rebate, Penalty</h5>
            </div>
            <div class="panel-body">
				<div class="col-md-12">
					<div class="row">
						<label class="col-md-2 text-bold">From Date</label>
						<div class="col-md-3 has-success pad-btm">
							<input type="date" id="from_date" class="form-control" value="<?=date('Y-m-d');?>" />
						</div>
						<label class="col-md-2 text-bold">Upto Date</label>
						<div class="col-md-3 has-success pad-btm">
							<input type="date" id="upto_date" class="form-control" value="<?=date('Y-m-d');?>" />
						</div>
					</div>
					<div class="row">
						<label class="col-md-2 text-bold">Ward No.</label>
						<div class="col-md-3 has-success pad-btm">
							<select id='ward_mstr_id' class="form-control">
								<option value=''>ALL</option>
							<?php
							if (isset($wardList))
                            {
								foreach ($wardList as $list)
                                    {
                                    ?>
                                    <option value='<?=$list['id'];?>'><?=$list['ward_no'];?></option>
                                    <?php
								}
							}
							?>
							</select>
						</div>
						<label class="col-md-2 text-bold">Payment Mode.</label>
						<div class="col-md-3 has-success pad-btm">
							<select id='tran_mode_mstr_id' class="form-control">
								<option value=''>ALL</option>
								<option value='CASH'>CASH</option>
                                <option value='DD'>DD</option>
                                <option value='CHEQUE'>CHEQUE</option>
                                <option value='ONLINE'>ONLINE</option>
							</select>
						</div>
					</div>
                    <div class="row">
                        <label class="col-md-2 text-bold">Property type</label>
						<div class="col-md-3 has-success pad-btm">
							<select id='property_type_id' class="form-control">
								<option value=''>ALL</option>
							<?php
							if (isset($propertytypeList))
                            {
								foreach ($propertytypeList as $list)
                                    {
                                    ?>
                                    <option value='<?=$list['id'];?>'><?=$list['property_type'];?></option>
                                    <?php
								}
							}
							?>
							</select>
						</div>
						<div class="col-md-6 text-left">
							<input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
						</div>
					</div>
				</div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result <span id="footerResult"></span></h5>
            </div>
            <div class="panel-body">
                <span id="summary"></span>
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
                                        <th>Mobile No.</th>
                                        <th><u>PAYMENT</u> <br />From / Upto</th>
                                        <th>Tran. Date</th>
                                        <th>Mode</th>
                                        <th>Paid Amount</th>
                                        <!-- <th>Current Amount</th> -->
                                        <!-- <th>Arrear Amount</th> -->
                                        <!-- <th>Adjust Amount</th> -->
                                        <!-- <th>Rebate</th> -->
                                        <!-- <th>Penalty</th> -->
                                        <th>Tran. No</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class='bg-dark'>
                                        <th></th>
                                        <th style="color: white;">Total Holding</th>
                                        <th style="color: white;"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th style="color: white;">Total Collection</th>
                                        <!-- <th></th> -->
                                        <!-- <th></th> -->
                                        <!-- <th></th> -->
                                        <!-- <th style="color: white;">Total Rebate</th> -->
                                        <!-- <th style="color: white;"></th> -->
                                        <th style="color: white;"></th>
                                        <th style="color: white;"></th>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    //$.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#empTable').DataTable({
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
                    var search_from_date = $('#from_date').val();
                    var search_upto_date = $('#upto_date').val();
                    var search_ward_mstr_id = $('#ward_mstr_id').val();
                    if (search_ward_mstr_id=='') {
                        search_ward_mstr_id = "ALL";
                    }
                    var search_tran_mode_mstr_id = $('#tran_mode_mstr_id').val();
                    if (search_tran_mode_mstr_id=='') {
                        search_tran_mode_mstr_id = "ALL";
                    }
                    var gerUrl = search_from_date+'/'+search_upto_date+'/'+search_ward_mstr_id+'/'+search_tran_mode_mstr_id;
                    window.open('<?=base_url();?>/prop_report/collectionReportExcelExtra/'+gerUrl).opener = null;
                }
            }
        ],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('prop_report/collectionReportAjaxExtra');?>',
            dataSrc: function ( data ) {
                total_collection = data.total_collection;
                recordsTotal  = data.recordsTotal;
                total_rebate = data.total_rebate;
                total_penalty = data.total_penalty;
                current_demand = data.current_demand;
                advance = data.advance;
                arrear_demand = data.arrear_demand;
                arrear_collection = data.arrear_collection;
                current_balance = current_demand-total_collection;
                arrear_balance = arrear_demand-arrear_collection;
                collect_amt = data.collect_amt;
                collectamt = data.amount_collection;
                return data.data;
            },
            "deferRender": true,
            "dataType": "json",
            'data': function(data){
                console.log($('#from_date').val());
                // Append to data
                data.search_from_date = $('#from_date').val();
                data.search_upto_date = $('#upto_date').val();
                data.search_ward_mstr_id = $('#ward_mstr_id').val();
				data.search_property_type_id = $('#property_type_id').val();
                data.search_collector_id = $('#collector_id').val();
                data.search_tran_mode_mstr_id = $('#tran_mode_mstr_id').val();
                
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
            { 'data': 'ward_no' },
            { 'data': 'holding_no' },
			{ 'data': 'new_holding_no' },
            { 'data': 'owner_name' },
            { 'data': 'mobile_no' },
            { 'data': 'from_upto_fy_qtr' },
            { 'data': 'tran_date' },
            { 'data': 'transaction_mode' },
            { 'data': 'payable_amt' },
            // { 'data': 'demandamt' },
            // { 'data': 'arrear_amt' },
            // { 'data': 'adjustment_amt' },
            // { 'data': 'rebate_amt' },
            // { 'data': 'penalty_amt' },
            { 'data': 'tran_no' },
        ],
        drawCallback: function( settings )
        {
            try
            {
                $("#footerResult").html(" (Total Holding - "+recordsTotal+", Total Collection - "+total_collection+", Total Rebate - "+total_rebate+", Total Penalty - "+total_penalty+")");
                var api = this.api();
                $(api.column(2).footer() ).html(recordsTotal);
                $(api.column(10).footer() ).html(total_collection);
                // $(api.column(13).footer() ).html(total_rebate);
                // $(api.column(14).footer() ).html(total_penalty);

                // $('#summary').html(
                //     "<strong>Current (Total Demand - "+current_demand+", Total Advance - 0 , Total Collection - "+collectamt+", Total Balance - "+current_balance+")\n<br>" +
                //     "Arrear (Total Demand - "+arrear_demand+", Total Collection - "+arrear_collection+", Total Balance - "+arrear_balance+")<br></strong><br>"
                // );
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
});
</script>
