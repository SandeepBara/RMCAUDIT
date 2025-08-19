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
        <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title">Pagination Page</h5>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12 pad-all bg-mint">
                        <div class="row">
                            <label class="col-md-1 text-bold">From Ward ID.</label>
                            <div class="col-md-2">
                                <input type="text" id="from_ward_id" class="form-control" value="" placeholder="From Ward ID" />
                            </div>
                            <label class="col-md-1 text-bold">Upto Ward ID.</label>
                            <div class="col-md-2">
                                <input type="text" id="upto_ward_id" class="form-control" value="" placeholder="Upto Ward ID" />
                            </div>
                            <div class="col-md-2">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SAERCH" />
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward id</th>
                                        <th>Ward No.</th>
                                        <th>ULB id</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
                                    <tr><th colspan="15">&nbsp;</th></tr>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#empTable').DataTable({
        'responsive': true,
        'processing': true,
        'language': {
            'processing': '<div class="load8"><div class="loader"></div></div>...',
        },
        'serverSide': true,
        lengthMenu: [
            [10, 25, 50, 5000],
            ['10 rows', '25 rows', '50 rows', '5000 rows']
        ],
        "columnDefs": [
            { "orderable": false, "targets": [0, 4] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            {
                extend: "excel",
                footer: { text: '' },
                exportOptions: { columns: [0,1,2,3,4] }
            }, {
                extend: 'pdf',
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [0,1,2,3,4] 
            }
        }],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('test_ajax/getwardList');?>',
            "dataType": "json",
            'data': function(data){
                // Read values
                var from_ward_id = $('#from_ward_id').val();
                var upto_ward_id = $('#upto_ward_id').val();
                // Append to data
                data.search_by_from_ward_id = from_ward_id;
                data.search_by_upto_ward_id = upto_ward_id;
            }
        },

        'columns': [
            { 'data': 's_no' },
            { 'data': 'id' },
            { 'data': 'ward_no' },
            { 'data': 'ulb_mstr_id' },
            { 'data': 'status' },
        ]
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});
</script>
