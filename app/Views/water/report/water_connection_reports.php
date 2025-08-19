<?= $this->include('layout_vertical/header'); ?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->
        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li class="active">Water Demand </li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Water Demand Reports</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-1 text-bold">KeyWords</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="text" id="keyword" name="keyword" class="form-control" />
                            </div>
                            <label class="col-md-1 text-bold">From Date</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?=date('Y-m-d')?>" max=<?=date('Y-m-d')?>/>
                            </div>
                            <label class="col-md-1 text-bold">Upto Date</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="date" id="upto_date" name="_date" class="form-control" value="<?=date('Y-m-d')?>" max=<?=date('Y-m-d')?>/>
                            </div>
                            <div class="form-check form-check-inline col-md-4">                                
                                <input style="margin-left:44px;" checked class="form-check-input valid" type="radio" name="category" id="category1" value="APL" />
                                <label class="form-check-label" for="inlineRadio1">APL</label>&nbsp;
                                <input class="form-check-input valid" type="radio" name="category" id="category2" value="BPL" />
                                <label class="form-check-label" for="inlineRadio2">BPL</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="text-center col-md-12">
                        <input type="button" id="btn_search" class="btn btn-primary " value="SAERCH" />

                    </div>
                </div>
                <div class="row">
                    <div class="col_sm-12" id="total" style="float:right;color: #26a69a;">

                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-responsive table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>App No.</th>
                                        <th>Ward No.</th>
                                        <th>Application Name.</th>
                                        <th>Guardian Name</th>
                                        <th>Mobile No.</th>
                                        <th>Apply Date</th>                                        
                                        <th>Category</th>
                                        <th>Apply From</th>
                                        <th>Address</th>
                                        <th>Property Type</th>
                                        <th>User Name</th>
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
<?= $this->include('layout_vertical/footer'); ?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var category = $('input[name="category"]:checked').val();
                        var keyword = $('#keyword').val();
                        var upto_date = $('#upto_date').val(); 
                        var from_date = $('#from_date').val();                      
                        if(keyword=='')
                        {
                            keyword = 'xxx';
                        }                 
                        var gerUrl = category+'/'+from_date+'/'+upto_date+'/'+keyword;
                        //alert();
                        window.open('<?=base_url();?>/water_report/water_connection_reportsExcel/'+gerUrl).opener = null;
                    }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Water Demand Status",
                    download: 'open',
                    footer: {
                        text: ''
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }

            ],
            'ajax': {
                "type": "POST",
                'url': '<?= base_url('water_report/water_connection_reports'); ?>',
                "dataType": "json",
                'data': function(data) {
                    // Append to data
                    data.category = $('input[name="category"]:checked').val();
                    data.keyword = $('#keyword').val();
                    data.upto_date = $('#upto_date').val();
                    data.from_date = $('#from_date').val();
                    console.log( data);

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
            'drawCallback': function(settings) {
                // Here the response
                var response = settings.json;
                var total = response.total;
                $("#total").html(total)
                console.log(response);
            },

            'columns': [{
                    'data': 's_no'
                },
                {
                    'data': 'application_no'
                },
                {
                    'data': 'ward_no'
                },
                {
                    'data': 'applicant_name'
                },
                {
                    'data': 'father_name'
                },
                {
                    'data': 'mobile_no'
                },
                {
                    'data': 'apply_date'
                },
                {
                    'data': 'category'
                },
                {
                    'data': 'apply_from'
                },
                {
                    'data': 'address'
                },                
                {
                    'data': 'property_type'
                },
                {
                    'data': 'emp_name'
                },
                
            ]
        });
        $('#btn_search').click(function() {
            // $(".valid").prop('checked', false);
            // alert();
            dataTable.draw();
        });

        //on click radio button get data 
        $('#genrated').click(function() {
            // $("#licence").val("");
            dataTable.draw();
        });
        $('#not_genrated').click(function() {
            // $("#licence").val("");
            dataTable.draw();
        });

    });
</script>