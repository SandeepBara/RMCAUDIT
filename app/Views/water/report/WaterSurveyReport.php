<?= $this->include('layout_vertical/header'); ?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<style>
    .survey{
        color:white !important;
        background-color: #26a69a;
        border-color: mistyrose !important;
    }
    .holding{
        background-color: #3c4552 !important;
        border-color: mistyrose !important;
    }
    .connection{
        background-color: #042024;
    }
    .discconection{
        background-color: #140f1f;
    }
    .bill{
        background-color: #3c4552;
    }
</style>
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
                        </div>
                        <div class="row">
                            <label class="col-md-1 text-bold">From Date</label>
                            <div class="col-md-2 has-success pad-btm">
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?=date('Y-m-d')?>" max=<?=date('Y-m-d')?>/>
                            </div> 
                            <label class="col-md-1 text-bold">Upto Date</label>
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
                                <input style="margin-left:44px;" checked class="form-check-input valid" type="radio" name="survey" id="survey_done" value="survey_done" />
                                <label class="form-check-label" for="inlineRadio1">Survey Done</label>&nbsp;
                                <input class="form-check-input valid" type="radio" name="survey" id="survey_no_done" value="survey_no_done" />
                                <label class="form-check-label" for="inlineRadio2">Survey Not Done</label>
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
                                        <th>Connection Type</th>
                                        <th>Consumer Type.</th>
                                        <th>Holding No.</th>
                                        <th>Saf No.</th>
                                        <th>Owner Name.</th>
                                        <th>Mobile No.</th>
                                        <th>Address.</th>

                                        <th class="survey holding">Holding Map</th>
                                        <th class="survey holding">Survey Holding No.</th>
                                        <th class="survey holding">Survey Saf No.</th>
                                        <th class="survey holding">Holding Not Map Remarks</th>
                                        <th class="survey connection">Survey Connection Type</th>
                                        <th class="survey connection">Survey Meter No.</th>
                                        <th class="survey connection">Survey Meter Funcationl</th>
                                        <th class="survey connection">Water Supply Duration</th>
                                        <th class="survey discconection">Is Disconnection Apply</th>
                                        <th class="survey discconection">Disconnection Doc</th>
                                        <th class="survey bill">Anny Bill Serve By Tc</th>
                                        <th class="survey bill">Last Bill ServeDate</th>
                                        <th class="survey bill">Remarks Of Bill Not Surved</th>
                                        <th class="survey">Latitude</th>
                                        <th class="survey">Longitude</th>
                                        <th class="survey">Survey Img</th>
                                        <th class="survey">Survey Date</th>
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
            // 'responsive': true,
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
                        gerUrl="";
                        var survey = $('input[name="survey"]:checked').val();
                        gerUrl+="survey="+survey;
                        var keyword = $('#keyword').val();
                        gerUrl+="&keyword="+keyword;
                        var ward_id = $('#ward_id').val();
                        gerUrl+="&ward_id="+ward_id;
                        var from_date = $('#from_date').val();
                        gerUrl+="&from_date="+from_date;
                        var upto_date = $('#upto_date').val();
                        gerUrl+="&upto_date="+upto_date;
                        var connection_type = $('input[name="connection_type"]:checked').val();
                        gerUrl+="&connection_type="+connection_type;
                        var dry_consumer = $('#dry_consumer').is(':checked');
                        var main_consumer = $('#main_consumer').is(':checked');
                        var super_dry_consumer = $('#super_dry_consumer').is(':checked');
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
                        gerUrl+="&consumer_type="+consumer_type;
                        window.open('<?=base_url();?>/water_report/WaterSurveyExport?'+gerUrl).opener = null;
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
                'url': '<?= base_url('water_report/WaterSurvey'); ?>',
                "dataType": "json",
                'data': function(data) {
                    // Append to data
                    data.survey = $('input[name="survey"]:checked').val();
                    data.keyword = $('#keyword').val();
                    data.ward_id = $('#ward_id').val();
                    data.from_date = $('#from_date').val();
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
                    console.log(data);
                    

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
                var response = settings.json;
                var total = response.total;
                $("#total").html(total)
                console.log(response);
                console.log($('input[name="survey"]:checked').val());
                if($('input[name="survey"]:checked').val()=='survey_no_done')
                {
                    $(".survey").hide();

                }
                else{
                    $(".survey").show();
                }
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
                    'data': 'meter_status'
                },
                {
                    'data': 'consumer_type'
                },
                {
                    'data': 'holding_no'
                },
                {
                    'data': 'saf_no'
                },
                {
                    'data': 'owner_name'
                },
                {
                    'data': 'mobile_no'
                },
                {
                    'data': 'address'
                },

                {
                    'data': 'holding_map'
                },
                {
                    'data': 'survey_holding_no'
                },
                {
                    'data': 'survey_saf_no'
                },
                {
                    'data': 'survey_reason_not_map'
                },
                {
                    'data': 'survey_meter_connection_type'
                },
                {
                    'data': 'survey_meter_no'
                },
                {
                    'data': 'survey_meter_working'
                },
                {
                    'data': 'survey_supply_duration'
                },
                {
                    'data': 'survey_apply_disconneciton'
                },
                {
                    'data': 'survey_desconn_document'
                },
                {
                    'data': 'survey_served_status'
                },
                {
                    'data': 'survey_last_bill_serve_date'
                },
                {
                    'data': 'survey_bill_not_serve_reason'
                },
                {
                    'data': 'latitude'
                },
                {
                    'data': 'longitude'
                },
                {
                    'data': 'geo_doc'
                },
                {
                    'data': 'survey_date'
                },
                
            ],
            "columnDefs": [
                { className: "survey holding", "targets": [ 10,11,12,13 ] },            
                { className: "survey connection", "targets": [ 14,15,16,17 ] },
                { className: "survey discconection", "targets": [ 18,19] },
                { className: "survey bill", "targets": [ 20,21,22] },
                { className: "survey", "targets": [ 10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26 ] },
            ],
        });
        $('#btn_search').click(function() {
            dataTable.draw();
        });

        //on click radio button get data 
        $('#survey_done').click(function() {
            $(".survey").show();
             dataTable.draw();
        });
        $('#survey_no_done').click(function() {
            dataTable.draw();
            $(".survey").hide();
        });

    });
   
</script>

 <script>
    $('#survey_done').click(function() {
        $(".survey").show();
            // dataTable.draw();
        });
    $('#survey_no_done').click(function() {
            $(".survey").hide();
            // dataTable.draw();
        });
 </script>