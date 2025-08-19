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
                <h5 class="panel-title">Date & Ward wise Level wise grievance pending & close</h5>
            </div>
            <div class="panel-body">
				<form id="myForm" method="post" >
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-2 text-bold" for="fromDate">From Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="fromDate" name="fromDate" class="form-control" value="<?=isset($fromDate)? $fromDate:date('Y-m-d');?>" />
                            </div>
                            <label class="col-md-2 text-bold" for="uptoDate" >Upto Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="uptoDate" class="form-control" name="uptoDate" value="<?=isset($uptoDate) ? $uptoDate : date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-1 text-bold" for="wardId">Ward No.</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select id='wardId' class="form-control" name="wardId">
                                    <option value=''>ALL</option>
                                    <?php
                                    if (isset($wardList))
                                    {
                                        foreach ($wardList as $list){
                                            ?>
                                            <option value='<?=$list['id'];?>' <?=isset($wardId) && $wardId== $list['id'] ? "selected" : "" ;?>><?=$list['ward_no'];?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <label class="col-md-1 text-bold" for="status">Token Status</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select id='status' class="form-control" name="status">
                                    <option value=''>ALL</option>
                                    <option value='1' <?=isset($status) && $status==1 ? "selected" : "";?>>Pending</option>
                                    <option value='5' <?=isset($status) && $status==5 ? "selected" : "";?>>Close</option>
                                    <option value='4' <?=isset($status) && $status==4 ? "selected" : "";?>>Rejected</option>
                                </select>
                            </div>
                            <label class="col-md-1 text-bold" for="moduleId">Module</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select id='moduleId' class="form-control" name="moduleId">
                                    <option value=''>ALL</option>
                                    <option value='1' <?=isset($moduleId) && $moduleId==1 ? "selected" : "";?>>Property</option>
                                    <option value='2' <?=isset($moduleId) && $moduleId==2 ? "selected" : "";?>>Water</option>
                                    <option value='3' <?=isset($moduleId) && $moduleId==3 ? "selected" : "";?>>Trade</option>
                                </select>
                            </div>
                            <label class="col-md-1 text-bold" for="applyFrom">Apply From</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select id='applyFrom' class="form-control" name="applyFrom">
                                    <option value=''>ALL</option>
                                    <option value='1' <?=isset($applyFrom) && $applyFrom==1 ? "selected" : "";?>>Citizen</option>
                                    <option value='2' <?=isset($applyFrom) && $applyFrom==2 ? "selected" : "";?>>Counter</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />    &nbsp;&nbsp;&nbsp;
                            <span id="print_btn">
                            </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result <span id="footerResult"></span></h5>
            </div>
            <div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
							<div class="table-responsive">
								<table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th>#</th>   
											<th>Token No.</th>   
											<th>Ref No</th>
											<th>Ref Type</th>
                                            <th>Module</th>
											<th>Token Status</th>
											<th>Apply From</th>
                                            <th>Apply Date</th>
                                            <th>Closing Date</th>
											<th>Pending Day</th>
                                            <th>Query</th>
											
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
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'ALL rows']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var gerUrl = 'true?';
                        var formData = $("#myForm").serializeArray();
                        $.each(formData, function(i, field) {
                            gerUrl += (field.name+'='+field.value)+"&";
                        });
                        window.open('<?=base_url();?>/grievance_new/appliedGrievanceReport/'+gerUrl).opener = null;
                    }
                }
            ],

            'ajax': {
                "type": "POST",
                'url':'<?=base_url('grievance_new/appliedGrievanceReport');?>',            
                                
                "deferRender": true,
                "dataType": "json",
                'data': function(data){
                    var formData = $("#myForm").serializeArray();
                    $.each(formData, function(i, field) {
                        data[field.name] = field.value;
                    });
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
                { 'data': 'token_no' },
                { 'data': 'app_no' },
                { 'data': 'app_type' },
                { 'data': 'module' },
                { 'data': 'token_status' },
                { 'data': 'apply_from' },
                { 'data': 'created_on' },
                { 'data': 'closing_on' },
                { 'data': 'day_difference' },
                { 'data': 'queries' },
                

                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        $('#btn_search').click(function()
        {
            dataTable.draw();
        });
        dataTable.draw();
    });
    
</script>