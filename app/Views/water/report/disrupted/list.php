<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!-- <h1 class="page-header text-overflow">Department List</h1> -->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->

        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Water</a></li>
            <li class="active"> Service Disrupted Area With Issue </li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Service Disrupted Consumer</h5>
                    </div>
                    <div class="panel-body">
                        <div id="page-content">
                            <div class="row">
                                <div class="">
                                    <table id="demo_dt_basic"
                                        class="table table-striped table-bordered table-responsive" cellspacing="0"
                                        width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Consumer No.</th>
                                                <th>Ward No</th>
                                                <th>Address</th>
                                                <th>Remarks</th>
                                                <th>Tc Name</th>
                                                <th>Action</th>
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
        </div>
        <!--===================================================-->
        <!--End page content-->
    </div>
    <!--===================================================-->
    <!--END CONTENT CONTAINER-->
    <?= $this->include('layout_vertical/footer'); ?>
    <!--DataTables [ OPTIONAL ]-->
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
    <script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript">


    </script>
    <script type="text/javascript">
        function myPopup(myURL, title, myWidth, myHeight) {
            var left = (screen.width - myWidth) / 2;
            var top = (screen.height - myHeight) / 4;
            var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
        }

        $(document).ready(function(){
            let table = $("#demo_dt_basic").DataTable({
                            responsive: false,
                            processing: true,
                            serverSide:true,
                            ajax: {
                                type: "POST",
                                url:'<?=base_url('water_report/serviceDisruptedData');?>',            
                                                
                                deferRender: true,
                                dataType: "json",
                                data: function(data){
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
                                },
                            },

                            'columns': [
                                { 'data': 's_no' },
                                { 'data': 'consumer_name' },
                                { 'data': 'consumer_no' },
                                { 'data': 'ward_no' },
                                { 'data': 'address' },
                                { 'data': 'remarks' },
                                { 'data': 'full_emp_name' },
                                { 'data': 'link' },                                
                                
                            ],
                            dom: 'Bfrtip',
                            lengthMenu: [
                                [ 10, 25, 50, -1 ],
                                [ '10 rows', '25 rows', '50 rows', 'Show All' ]
                            ],
                            buttons: [
                                'pageLength',
                                {
                                    text: 'excel',
                                    extend: "excel",
                                    action: function ( e, dt, node, config ) {
                                        var gerUrl = 'true?';
                                        var formData = $("#myForm").serializeArray();
                                        $.each(formData, function(i, field) {
                                            gerUrl += (field.name+'='+field.value)+"&";
                                        });
                                        window.open('<?=base_url();?>/water_report/serviceDisruptedData/'+gerUrl).opener = null;
                                    }
                                }
                            ]
            });

            $('#btn_search').click(function()
            {
                $("#demo_dt_basic").DataTable().draw();
            });
        })
    </script>