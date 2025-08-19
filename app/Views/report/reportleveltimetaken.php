<?= $this->include('layout_vertical/header');?>
<style>
.row{line-height:25px;}
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<style>
    #levelTable th{
        text-align: center;
        border-bottom: 0.01px solid #7e7474 !important;
        border-top: 0.01px solid #7e7474 !important;
        border-left: 0.01px solid #7e7474 !important;
    }
    #levelTable td{
        text-align: center;
        border-left: 0.01px solid #7e7474 !important;
        border-bottom: 0.01px solid #7e7474 !important;
    }
    #levelTable_wrapper{
        width: 100%;
        overflow-x: scroll;
    }
    .green{
        background-color: #0ba30b;
        color: #dfdfdf !important;
    }
    .blue{
        background-color: #186bc1;
        color: #dfdfdf !important;
    }
</style>

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">
                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
                    <li><a href="#"><i class="demo-pli-home"></i></a></li>
                    <li><a href="#">Report</a></li>
                    <li class="active">Level Wise Days Taken Report</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">
					<div class="panel panel-bordered panel-dark">
                        <div class="panel-heading" style="background-color: #298da0;">
                            <h3 class="panel-title">Level Wise Days</h3>
                        </div>
                            <div class="panel-body">
                                <div class ="row">
                                    <div class="col-md-12">
                                    <form class="form-horizontal" method="post" action="<?php echo base_url('levelwisependingform/exportreportleveltotaldays/');?>">
                                            <div class="form-group">
                                                <div class="col-md-2">
                                                    <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                                    <input type="date" id="from_date" name="from_date" min="2024-02-07" max="<?=date('Y-m-d');?>" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>" >
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
                                                    <input type="date" id="to_date" name="to_date" class="form-control" min="2024-02-07" max="<?=date('Y-m-d');?>" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>" >
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="ward No"><b>Ward No</b><span class="text-danger">*</span> </label>
                                                    <select id="ward_mstr_id" name="ward_mstr_id" class="form-control">
                                                        <option value="All">ALL</option>
                                                        <?php
                                                        foreach($wardList as $value)
                                                        {
                                                            ?>
                                                            <option value="<?=$value['ward_mstr_id']?>" <?=(isset($ward_mstr_id, $ward_mstr_id) && ($ward_mstr_id==$value["ward_mstr_id"])) ? "selected" : NULL;?>>
                                                                <?=$value['ward_no'];?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="to_date"><b>Saf No.</b> </label>
                                                    <input type="text" id="saf_no" name="saf_no" class="form-control" placeholder="Saf No." value="<?=(isset($saf_no))?$saf_no:"";?>" >
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="control-label" for="btn_search">&nbsp;</label>
                                                    <button class="btn btn-primary btn-block" id="btn_search" type="button">Search</button>


                                                </div>
                                                <div class="col-md-2">
                                                    <div class="panel-control">
                                                        <label class="control-label" for="btn_search">&nbsp;</label>
                                                        <button type="submit"  class="btn btn-primary"><i class="fa fa-arrow-down" aria-hidden="true"></i> EXCEL EXPORT</button>
                                                    </div>
                                                </div>

                                                <div class="col-md-4"></div>
                                            </div>
                                       </form>
                                    </div>
                                </div>
                            </div>
						<div class="panel-body">
                            <table class="table table-striped table-bordered text-sm table-responsive datatable" id="levelTable">
                            <thead><tr style="background-color: #e5eced;">
									<th  rowspan="2">Sl No.</th>
									<th  rowspan="2">Saf No</th>
									<th rowspan="2">Apply Date</th>
									<th rowspan="2">Payment Date</th>
									<th rowspan="2" class="green">Digitisation (TCA) (2 Days)</th>
                                    <th rowspan="2" class="blue">Document Verification(ULB) (5 Days)</th>
                                    <th rowspan="2" class="green">Geotagging(TCA) (8 Days)</th>
                                    <th colspan="3" class="blue">Final Memo(ULB)</th>
                                    <th rowspan="2">Approve Date</th>
                                    <th rowspan="2">Total Days</th>
								</tr>
                                <tr>
                                    <th class="blue">ULB Tax Collector Stage1 (5 Days)</th>
                                    <th class="blue">Property Section Incharge Stage2 (3 Days)</th>
                                    <th class="blue">DMC/AMC Stage3 (2 Days)</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
									$i=0;
									foreach([] as $rec)
									{
										?>
										<tr>
											<td><?=++$i;?></td>
											<td><?=$rec["saf_no"].'/'.$rec["id"];?></td>
											<td><?=$rec["apply_date"];?></td>
											<td><?=$rec["payment_date"];?></td>
											<td><?=$rec["backoffice"];?></td>
											<td><?=$rec["dealingassistant"];?></td>
											<td><?=$rec["taxcollector"];?></td>
											<td><?=$rec["ulbtaxcollector"];?></td>
											<td><?=$rec["propertysectionincharge"];?></td>
											<td><?=$rec["propertysectionincharge"];?></td>
											<td><?=$rec["approve_date"];?></td>
											<th><?=$rec["backoffice"]+$rec["dealingassistant"]+$rec["taxcollector"]+
                                                   $rec["ulbtaxcollector"]+$rec["propertysectionincharge"]+$rec["executiveofficer"];?></th>
										</tr>
										<?php
									}
								?>
                                </tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<?= $this->include('layout_vertical/footer');?>
<script>
    var temp = "ALL";
    $(document).ready(function(){
        function renderdataable(){
            $.fn.dataTable.ext.errMode = 'throw';
            var dataTable = $('#levelTable').DataTable({
                'responsive': true,
                'processing': true,
                'destroy': true,
                'searching': false,
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
                    'url':'<?=base_url('levelwisependingform/reportleveltimetakenAjax');?>',
                    "dataType": "json",
                    'data': function(data){
                        // Append to data
                        data.btn_search = temp;
                        data.search_from_date = $('#from_date').val();
                        data.search_upto_date = $('#to_date').val();
                        data.search_ward_mstr_id = $('#ward_mstr_id').val();
                        data.saf_no = $('#saf_no').val();
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
                order: [[0, 'ASC']],
                'columns': [
                    { 'data': 's_no' },
                    { 'data': 'saf_no' },
                    { 'data': 'apply_date' },
                    { 'data': 'payment_date' },
                    { 'data': 'backoffice' },
                    { 'data': 'dealingassistant' },
                    { 'data': 'taxcollector' },
                    { 'data': 'ulbtaxcollector' },
                    { 'data': 'propertysectionincharge' },
                    { 'data': 'executiveofficer' },
                    { 'data': 'approve_date' },
                    { 'data': 'total' },
                ]
            });
        }
        $('#btn_search').click(function(){
            temp='BY';
            renderdataable();
        });
    });
</script>