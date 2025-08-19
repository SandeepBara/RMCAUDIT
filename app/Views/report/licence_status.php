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
                
               <br>&nbsp; <?=$licence_status?>
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
                            <input type="hidden" value="<?=$from_date?>" id="from_date">
                            <input type="hidden" value="<?=$to_date?>" id="to_date">
                            <input type="hidden" value="<?=$ward_id?>" id="ward_id">
                            <input type="hidden" value="<?=$status?>" id="status">
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
                title: "Trade Licence",
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,] }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Trade Licence",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6,] }
            }
        ],

        /* "columnDefs": [
            { "orderable": false, "targets": [4, 5] }
        ], */
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('TradeApplyLicence/licence_statusAjax');?>',
            "dataType": "json",
            'data': function(data){
                console.log(data);
                data.from_date = $("#from_date").val();
                data.to_date = $("#to_date").val();
                data.ward_id = $("#ward_id").val(); 
                data.status = $("#status").val(); 

             }
        },
        /*'drawCallback': function (settings) { 
            // Here the response
            var response = settings.json;
            console.log(response);
        },*/
        order: [[0, 'DESC']],
        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'application_no' },
            { 'data': 'firm_name' },
            { 'data': 'application_type' },
            { 'data': 'apply_date' },
            { 'data': 'apply_from' },
            { 'data': 'view' },
                        
        ]
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});
</script>


