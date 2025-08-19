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
                <h5 class="panel-title">GBSAF Ward Wise DCB</h5>
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
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Holding No.</th>
                                        <th>New Holding No.</th>
                                        <th>Owner Name</th>
                                        <th>Mobile No.</th>
                                        <th>Address</th>
                                        <th>Demand(<?=$privFyear;?>)</th>
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
    var flag = window.location.pathname.split('/').pop();
    $(document).ready(function(){
        var dataTable = $('#empTable').DataTable({
            // responsive: true,
            processing: true, 
            serverSide: true,
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
                    action: function ( e, dt, node, config ) {
                        var formData = $("#myForm").serializeArray();
                        gerUrl="?export=true";
                        $.each(formData, function(i, field) {
                            gerUrl+=("&")+(field.name+"="+field.value); // Corrected: use d[field.name] instead of d.field.name
                        });
                        window.open("<?=base_url("prop_report/lastYearNotPaid")?>"+"/"+flag+gerUrl).opener = null;
                    }
                },
            ],
            ajax: {
                url: "<?=base_url("prop_report/lastYearNotPaid")?>"+"/"+flag,
                type: "POST",
                deferRender: true,
                dataType: "json",
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
                            <th>${summary.total || 0}</th>
                            <th>${(parseFloat(summary.balance || 0)).toFixed(2)}</th>
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
                { data: 'holding_no' ,name: 'holding_no' },
                { data: 'new_holding_no' ,name: 'new_holding_no' },
                { data: 'owner_name' ,name: 'owner_name' },
                { data: 'mobile_no' ,name: 'mobile_no' },
                { data: 'prop_address' ,name: 'prop_address' },
                { data: 'balance' ,name: 'balance' },
            ],
        });
    });

    function searchData(){
        $('#empTable').DataTable().ajax.reload();
    }
</script>