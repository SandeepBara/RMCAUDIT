<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
    
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
        <li><a href="#"><i class="demo-pli-home"></i></a></li>
        <li><a href="#">Property</a></li>
        <li class="active">Property Bulk Payment Receipt</li>
        </ol>
    </div>
    <!--Page content-->
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Property Bulk Payment Receipt</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" method="post" action="<?=base_url('');?>/PropertyBulkPaymentReceipt/bulkPrint">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                        <label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?=(isset($from_date))?$from_date:date('Y-m-d');?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label" for="to_date"><b>To Date</b><span class="text-danger">*</span> </label>
                                        <div class="input-group">
                                            <input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?=(isset($to_date))?$to_date:date('Y-m-d');?>">
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
                           <p style="color: red;"><strong>Data Not Available!!</strong></p>
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
<script src="<?= base_url();?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url();?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url();?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url();?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url();?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',{
                    text: 'excel',
                    extend: "excel",
                    title: "Report",
                    footer: { text: '' },
                    exportOptions: { columns: [ 0, 1,2] }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Report",
                    download: 'open',
                    footer: { text: '' },
                    exportOptions: { columns: [ 0, 1,2] }
                }
            ]
        });
    });
    $('#btn_search').click(function(){
        var proccess =  true;
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        
        if(from_date==""){
            $("#from_date").css({"border-color":"red"});
            $("#from_date").focus();
            proccess = false;
        }
        if(to_date==""){
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            proccess = false;
        }
        if(to_date<from_date){
            alert("To Date Should Be Greater Than Or Equals To From Date");
            $("#to_date").css({"border-color":"red"});
            $("#to_date").focus();
            proccess = false;
        }
        return proccess;
    });
    $("#from_date").change(function(){$(this).css('border-color','');});
    $("#to_date").change(function(){$(this).css('border-color','');});
</script>