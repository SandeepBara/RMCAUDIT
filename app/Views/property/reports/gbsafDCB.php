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
                <h5 class="panel-title">GBSAF  DCB</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form id="myForm" method="post">
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
                                <select id='fyear' name="fyear" class="form-control">
                                <?php
                                if (isset($fyearList)) {
                                    foreach ($fyearList as $list) {
                                        ?>
                                            <option value='<?=$list;?>' <?=(isset($fyear))?($fyear==$list)?"selected":"":"";?>><?=$list;?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5 text-right">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" onclick="searchData()" />                                
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>               
        <div class="panel panel-dark">
            
            <div class="panel-body">                
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Ward No</th>
                                        <th rowspan="2">Application no</th>
                                        <th rowspan="2">Office Name</th>
                                        <th rowspan="2">Officer Name</th>
                                        <th rowspan="2">Mobile No</th>
                                        <th rowspan="2">Address</th>
                                        <th colspan="3">Demand</th>
                                        <th colspan="3">Collection</th>
                                        <th colspan="3">Balance</th>
                                    </tr>
                                    <tr>
                                        <th>Arrear Demand</th>
                                        <th>Current Demand</th>
                                        <th>Total Demand</th>

                                        <th>Arrear Collection</th>
                                        <th>Current Collection</th>
                                        <th>Total Collection</th>
                                        
                                        <th>Arrear Outstand</th>
                                        <th>Current Outstand</th>
                                        <th>Total Outstand</th>
                                    </tr>
                                </thead>
                                <tbody>
										
								</tbody> 
                                <tfoot>

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

<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    $(document).ready(function(){
        var dataTable = $('#empTable').DataTable({
            // responsive: true,
            processing: true, 
            serverSide: false,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, 100,-1],
                ['10 rows', '25 rows', '50 rows', '100 rows','All']
            ],
            buttons: [
                'pageLength',
                {
                    extend: 'excel',
                    text: 'Excel Export',
                    className: 'btn btn-success',
                },
            ],
            ajax: {
                url: "<?=base_url("prop_report/gbSAFDCB")?>",
                data: function(d) {
                    // Add custom form data to the AJAX request
                    var formData = $("#myForm").serializeArray();
                    $.each(formData, function(i, field) {
                        d[field.name] = field.value; // Corrected: use d[field.name] instead of d.field.name
                    });

                },
                dataSrc: function (json) {
                    // Build footer row from summary data
                    let summary = json?.summary || {};
                    let footerHtml = `
                        <tr>
                            <th colspan="6">Total</th>
                            <th>${summary.total_saf || 0}</th>
                            <th>${summary.arrear_demand || 0}</th>
                            <th>${summary.current_demand || 0}</th>
                            <th>${(parseFloat(summary.arrear_demand || 0) + parseFloat(summary.current_demand || 0)).toFixed(2)}</th>
                            <th>${summary.arrear_collection || 0}</th>
                            <th>${summary.current_collection || 0}</th>
                            <th>${(parseFloat(summary.arrear_collection || 0) + parseFloat(summary.current_collection || 0)).toFixed(2)}</th>
                            <th>${summary.arrear_outstand || 0}</th>
                            <th>${summary.current_outstand || 0}</th>
                            <th>${(parseFloat(summary.arrear_outstand || 0) + parseFloat(summary.current_outstand || 0)).toFixed(2)}</th>
                        </tr>
                    `;
                    $('#empTable tfoot').html(footerHtml);

                    return json.data;
                },
                beforeSend: function() {
                    $("#btn_search").val("LOADING ...");
                    $("#loadingDiv").show();
                },
                complete: function() {
                    $("#btn_search").val("SEARCH");
                    $("#loadingDiv").hide();
                },
            },            
            'columns': [
                { data: 's_no',name:'s_no' },
                { data: 'ward_no' ,name: 'ward_no'},
                { data: 'application_no' ,name: 'application_no' },
                { data: 'office_name' ,name: 'office_name' },
                { data: 'officer_name' ,name: 'officer_name' },
                { data: 'mobile_no' ,name: 'mobile_no' },
                { data: 'address' ,name: 'address' },

                { data: 'arrear_demand' ,name: 'arrear_demand' },
                { data: 'current_demand' ,name: 'current_demand' },
                { data: 'total_demand' ,name: 'total_demand',render:function(row,type,data){return (parseFloat(data.arrear_demand? data.arrear_demand:0) + parseFloat(data.current_demand ? data.current_demand : 0))} },
                { data: 'arrear_collection' ,name: 'arrear_collection' },
                { data: 'current_collection' ,name: 'current_collection' },
                { data: 'total_collection' ,name: 'total_collection',render:function(row,type,data){return (parseFloat(data.arrear_collection? data.arrear_collection:0) + parseFloat(data.current_collection ? data.current_collection : 0))}  },
                { data: 'arrear_outstand' ,name: 'arrear_outstand' },
                { data: 'current_outstand' ,name: 'current_outstand' },
                { data: 'total_outstand' ,name: 'total_outstand',render:function(row,type,data){return (parseFloat(data.arrear_outstand? data.arrear_outstand:0) + parseFloat(data.current_outstand ? data.current_outstand : 0))} },
            ],
        });
    });

    function searchData(){
        $('#empTable').DataTable().ajax.reload();
    }
</script>