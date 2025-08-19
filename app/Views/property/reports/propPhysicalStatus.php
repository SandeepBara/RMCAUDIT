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
            <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->

        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">Report</a></li>
            <li class="active">Property Physical Status</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Physical Status</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="myForm" class="form-horizontal" method="post">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label class="control-label" for="fromDate"><b>From Date</b> <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="date" id="fromDate" name="fromDate" class="form-control" placeholder="From Date" value="<?= (isset($fromDate)) ? $fromDate : date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="control-label" for="uptoDate"><b>To Date</b><span class="text-danger">*</span> </label>
                                            <div class="input-group">
                                                <input type="date" id="uptoDate" name="uptoDate" class="form-control" placeholder="To Date" value="<?= (isset($uptoDate)) ? $uptoDate : date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                            <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                    
                                    <tbody>
                                        <?php
                                        if (!isset($result)):
                                        ?>
                                            <tr>
                                                <td colspan="11" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                            <?php else:
                                            ?>
                                                <tr>
                                                    <th colspan="3">Section 1</th>
                                                </tr>
                                                <tr>
                                                    <td>Sl</td>
                                                    <td>Particulars</td>
                                                    <td>No of House Hold</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Opening Assessed House Hold on Start Date</td>
                                                    <td><?=$result["active_holding"]??0;?></td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>New Assessment Done During the Period</td>
                                                    <td><?=$result["approved_saf"]??0;?></td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>Deactivated During the Period</td>
                                                    <td><?=$result["deactivated_hh"]??0;?></td>
                                                </tr>
                                                <tr <?=((($result["active_holding"]??0)+($result["approved_saf"]??0)-($result["deactivated_hh"]??0))>0) ? 'style="cursor:pointer" onclick="openWindow(1,4,'."'Section 1'".')" ':"";?>>
                                                    
                                                    <td>4</td>
                                                    <td>Total Assessed House Hold on End Date (1+2-3)</td>
                                                    <td><?=($result["active_holding"]??0)+($result["approved_saf"]??0)-($result["deactivated_hh"]??0);?></td>
                                                </tr>

                                                <tr>
                                                    <th colspan="3">Section 2</th>
                                                </tr>
                                                <tr>
                                                    <td>Sl</td>
                                                    <td>Particulars</td>
                                                    <td>No Of House Hold </td>
                                                </tr>
                                                <tr <?=((($result["opening_assess_hh"]??0) - ($result["total_sam"]??0) )>0) ? 'style="cursor:pointer" onclick="openWindow(2,1,'."'SAM Not Generated'".')" ':"";?>>
                                                    <td>1</td>
                                                    <td>SAM Not Generated</td>
                                                    <td><?=((($result["opening_assess_hh"]??0) - ($result["total_sam"]??0) ));?></td>
                                                </tr>
                                                <tr <?=(($result["not_geotag_done"]??0)>0) ? 'style="cursor:pointer" onclick="openWindow(2,2,'."'Geo Tagging Not Done'".')" ':"";?>>
                                                    <td>2</td>
                                                    <td>Geo Tagging Not Done</td>
                                                    <td><?=($result["not_geotag_done"]??0);?></td>
                                                </tr>
                                                <tr <?=(($result["full_paid_hh"]??0)>0) ? 'style="cursor:pointer" onclick="openWindow(2,3,'."'Fully Paid House Hold'".')" ':"";?>>
                                                    <td>3</td>
                                                    <td>Fully Paid House Hold</td>
                                                    <td><?=($result["full_paid_hh"]??0);?></td>
                                                </tr>
                                                <tr <?=(($result["btc"]??0)>0) ? 'style="cursor:pointer" onclick="openWindow(2,4,'."'Back to Citizens Case'".')" ':"";?>>
                                                    <td>4</td>
                                                    <td>Back to Citizens Case</td>
                                                    <td><?=($result["btc"]??0);?></td>
                                                </tr>
                                                <tr <?=(($result["pending_at_level"]??0)>0) ? 'style="cursor:pointer" onclick="openWindow(2,5,'."'Pending At Officer Level'".')" ':"";?>>
                                                    <td>5</td>
                                                    <td>Pending At Officer Level</td>
                                                    <td><?=($result["pending_at_level"]??0);?></td>
                                                </tr>
                                                <tr>
                                                    <td>6</td>
                                                    <td>Total House Hold assessed under PMAY Category</td>
                                                    <td>0</td>
                                                </tr>
                                                <tr>
                                                    <td>7</td>
                                                    <td>Municipal Properties Assessed</td>
                                                    <td>0</td>
                                                </tr>
                                        <?php endif;  ?>
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
    $(document).ready(function() {
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'excel',
                    extend: "excel",
                    title: "Report",
                    footer: {
                        text: ''
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12]
                    }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Report",
                    download: 'open',
                    footer: {
                        text: ''
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12]
                    }
                }
            ]
        });
    });
    $('#btn_search').click(function() {
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        if (from_date == "") {
            $("#from_date").css({
                "border-color": "red"
            });
            $("#from_date").focus();
            return false;
        }
        if (to_date == "") {
            $("#to_date").css({
                "border-color": "red"
            });
            $("#to_date").focus();
            return false;
        }
        if (to_date < from_date) {
            alert("To Date Should Be Greater Than Or Equals To From Date");
            $("#to_date").css({
                "border-color": "red"
            });
            $("#to_date").focus();
            return false;
        }
    });
    $("#from_date").change(function() {
        $(this).css('border-color', '');
    });
    $("#to_date").change(function() {
        $(this).css('border-color', '');
    });
    function myPopup(myURL, title, myWidth, myHeight)
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }
    function openWindow(section,row,heading=""){
        var gerUrl = "<?=base_url("/prop_report/propPhysicalStatus")?>"+"?section="+section+"&row="+row+"&heading="+heading+"&";
        var formData = $("#myForm").serializeArray();
        $.each(formData, function(i, field) {
            gerUrl += (field.name+'='+field.value)+"&";
        });
        console.log(gerUrl);
        myPopup(gerUrl,'xtf','900','700')
    }
</script>