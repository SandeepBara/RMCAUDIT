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
                            <label class="col-md-1 text-bold" for="ward_id">Ward No</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select name="ward_id" id="ward_id" class="form-control">
                                    <option value="All">Select</option>
                                    <?php
                                    foreach($ward as $val)
                                    {
                                        ?>
                                            <option value="<?=$val['id'];?>"><?=$val["ward_no"];?></option>
                                        <?php
                                    }

                                    ?>
                                </select>
                            </div>
                            <label class="col-md-1 text-bold" for="keyword">KeyWords</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="text" id="keyword" name="keyword" class="form-control" />
                            </div>
                            <label class="col-md-1 text-bold">Demand Upto</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=date('Y-m-d')?>" max=<?=date('Y-m-d')?>/>
                            </div>                            
                        </div>
                        <div class="row">
                            <div class="form-check form-check-inline col-md-12">
                                <div class="col-md-2">
                                    <input  type="checkbox" id="dry_consumer" name="dry_consumer" value="Dry Consumer">
                                    <label class="form-check-label" for="dry_consumer"> Dry Consumer</label><br>
                                </div>
                                <div class="col-md-2">
                                    <input  type="checkbox" id="main_consumer" name="main_consumer" value="Main Consumer">
                                    <label class="form-check-label" for="main_consumer"> Main Consumer</label><br>
                                </div>
                                <div class="col-md-2">                                    
                                    <input type="checkbox" id="super_dry_consumer" name="super_dry_consumer" value="Supper Dry Consumer">
                                    <label class="form-check-label" for="super_dry_consumer"> Supper Dry Consumer</label><br>
                                </div>
                                <div class="form-check form-check-inline col-md-4 text-center">                                
                                    <input style="margin-left:44px;" class="form-check-input valid" type="radio" name="connection_type" id="meter" value="1" />
                                    <label class="form-check-label" for="meter">Meter</label>&nbsp;
                                    <input class="form-check-input valid" type="radio" name="connection_type" id="fixed" value="3" />
                                    <label class="form-check-label" for="fixed">Fixed</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-check form-check-inline col-md-12 text-center">                                
                                <input style="margin-left:44px;" checked class="form-check-input valid" type="radio" name="genrated" id="genrated" value="genrated" />
                                <label class="form-check-label" for="inlineRadio1">Genreted</label>&nbsp;
                                <input class="form-check-input valid" type="radio" name="genrated" id="not_genrated" value="not_genrated" />
                                <label class="form-check-label" for="inlineRadio2">Not Genreted</label>
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
                                        <th>Ward No.</th>
                                        <th>Consumer No.</th>
                                        <th>Application Name.</th>
                                        <th>Guardian Name</th>
                                        <th>Mobile No.</th>
                                        <th>Apply Date</th>
                                        <th>Connection Type</th>
                                        <th>Category</th>
                                        <th>Consumer Type</th>
                                        <th>Current Demand Date</th>
                                        <th>Last Demand Date</th>
                                        <th>Demand Type</th>
                                        <th>Last Meter Reading</th>
                                        <th>View</th>
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
                        var genrated = $('input[name="genrated"]:checked').val();
                        var keyword = $('#keyword').val();
                        var ward_id = $('#ward_id').val();
                        var upto_date = $('#upto_date').val(); 
                        var connection_type = $('input[name="connection_type"]:checked').val();
                        dry_consumer = $('#dry_consumer').is(':checked');
                        main_consumer = $('#main_consumer').is(':checked');
                        super_dry_consumer = $('#super_dry_consumer').is(':checked');
                        let consumer_type=null;                    
                        if(dry_consumer)
                        {
                            consumer_type ="Dry Consumer";
                        }
                        if(main_consumer )
                        {
                            consumer_type ='Main Consumer'; 
                        }
                        if(super_dry_consumer)
                        {
                            consumer_type ='Supper Dry Consumer' 
                        }
                        if(dry_consumer && main_consumer)
                        {
                            consumer_type ="Dry Consumer & Main Consumer";  
                        }
                        if(dry_consumer && super_dry_consumer)
                        {
                            consumer_type ="Dry Consumer & Supper Dry Consumer";  
                        }
                        if(main_consumer && super_dry_consumer)
                        {
                            consumer_type ="Main Consumer & Supper Dry Consumer";  
                        }
                        if(main_consumer && super_dry_consumer && dry_consumer )
                        {
                            consumer_type = null; 
                        }                       
                        if(keyword=='')
                        {
                            keyword = 'xxx';
                        } 
                        if(!connection_type)
                        {
                            connection_type = "All";
                        }                
                        var gerUrl = genrated+'/'+upto_date+'/'+ward_id+'/'+keyword+"/"+connection_type;
                        if(consumer_type)
                        {
                            gerUrl+="/"+consumer_type;
                        }
                        window.open('<?=base_url();?>/water_report/demad_reportsExcel/'+gerUrl).opener = null;
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
                'url': '<?= base_url('water_report/demad_reports'); ?>',
                "dataType": "json",
                'data': function(data) {
                    // Append to data
                    data.genrated = $('input[name="genrated"]:checked').val();
                    data.keyword = $('#keyword').val();
                    data.ward_id = $('#ward_id').val();
                    data.upto_date = $('#upto_date').val();
                    data.connection_type = $('input[name="connection_type"]:checked').val();
                    dry_consumer = $('#dry_consumer').is(':checked');
                    main_consumer = $('#main_consumer').is(':checked');
                    super_dry_consumer = $('#super_dry_consumer').is(':checked');
                    let consumer_type=null;
                   
                    if(dry_consumer)
                    {
                        consumer_type ="Dry Consumer";
                    }
                    if(main_consumer )
                    {
                        consumer_type ='Main Consumer'; 
                    }
                    if(super_dry_consumer)
                    {
                        consumer_type ='Supper Dry Consumer' 
                    }
                    if(dry_consumer && main_consumer)
                    {
                        consumer_type ="Dry Consumer & Main Consumer";  
                    }
                    if(dry_consumer && super_dry_consumer)
                    {
                        consumer_type ="Dry Consumer & Supper Dry Consumer";  
                    }
                    if(main_consumer && super_dry_consumer)
                    {
                        consumer_type ="Main Consumer & Supper Dry Consumer";  
                    }
                    if(main_consumer && super_dry_consumer && dry_consumer )
                    {
                        consumer_type = null; 
                    }
                    data.consumer_type = consumer_type;
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
                    'data': 'ward_no'
                },
                {
                    'data': 'consumer_no'
                },
                {
                    'data': 'owner_name'
                },
                {
                    'data': 'father_name'
                },
                {
                    'data': 'mobile_no'
                },
                {
                    'data': 'connection_date'
                },
                {
                    'data': 'meter_status'
                },
                {
                    'data': 'category'
                },
                {
                    'data': 'consumer_type'
                },
                {
                    'data': 'max_demand_upto'
                },
                {
                    'data': 'last_demands'
                },
                {
                    'data': 'demand_type'
                },
                {
                    'data': 'last_meter_reading'
                },
                {
                    'data': 'view'
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