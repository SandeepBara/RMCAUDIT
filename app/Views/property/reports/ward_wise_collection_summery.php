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
                <h5 class="panel-title">Ward Wise Collection Summary</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form method="post">
                        <div class="row">
                            <label class="col-md-2 text-bold">From Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="from_date" name="from_date" class="form-control" value="<?=$from_date;?>" />
                            </div>
                            <label class="col-md-2 text-bold">Upto Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=$upto_date;?>" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 text-bold"></label>
                            <div class="col-md-4">
                                <div class="radio">
                                    <input type="radio" id="ward_type_report1" name="ward_type_report" class="magic-radio" value="all_ward" <?=($ward_type_report=='all_ward')?"checked":"";?> >
                                    <label for="ward_type_report1">All Ward</label>
        
                                    <input type="radio" id="ward_type_report2" name="ward_type_report" class="magic-radio" value="collected_ward" <?=($ward_type_report=='collected_ward')?"checked":"";?>>
                                    <label for="ward_type_report2">Hide Wards With Zero Collection</label>        
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result <span id="footerResult"></span></h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th colspan="2"></th>
                                        <th colspan="3" class="text-center">Collection</th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Total Holding</th>
                                        <th>Current</th>
                                        <th>Arrear</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                
                                <?php
								$total_holding=0;
								$current_collection=0;
								$arrear_collection=0;
								$total_collection=0;
                                if (isset($report_list))
                                {
                                    
                                    ?>
                                    <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ($report_list as $list)
                                    {
                                        ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$list['ward_no']?></td>
                                            <td><?=$list['total_holding'];?></td>
                                            <td><?=round($list['current_collection'], 2);?></td>
                                            <td><?=round($list['arrear_collection'], 2);?></td>
                                            <td><?=round($list['total_collection'], 2);?></td>
                                        </tr>
                                        <?php
                                        $total_holding+=$list['total_holding'];
                                        $current_collection+=$list['current_collection'];
                                        $arrear_collection+=$list['arrear_collection'];
                                        $total_collection+=$list['total_collection'];
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th><?=$total_holding;?></th>
                                            <th><?=round($current_collection,2);?></th>
                                            <th><?=round($arrear_collection,2);?></th>
                                            <th><?=round($total_collection,2);?></th>
                                        </tr>
                                    </tfoot>
                                    <?php
                                }
                                ?>
                                
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
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript">
$("#btn_search").click(function(){
    const date1 = new Date($("#from_date").val());
    const date2 = new Date($("#upto_date").val());
    if (date1>date2) {
        modelInfo("Invalid upto date");
        return false;
    }
    $("#btn_search").val("LOADING");
    return true;
}); 

$(document).ready(function(){   
    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "formatted-num-pre": function ( a ) {
            a = (a === "-" || a === "") ? 0 : a.replace( /[^\d\-\.]/g, "" );
            return parseFloat( a );
        }, 
        "formatted-num-asc": function ( a, b ) {
            return a - b;
        }, 
        "formatted-num-desc": function ( a, b ) {
            return b - a;
        }
    } );
    var dataTable = $('#dataTableID').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        lengthMenu: [
            [ -1, 10, 25, 50, ],
            [ 'Show all', '10 rows', '25 rows', '50 rows' ]
        ],
        columnDefs: [{ type: 'formatted-num', targets: 0 }],
        buttons: [
            'pageLength',
            {
            text: 'Excel',
            extend: "excel",
            title: "Ward Wise Collection Summary (<?=date ("Y-m-d H:i:s A")?>)",
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1, 2, 3, 4] }
        }, {
            text: 'PDF',
            extend: "pdf",
            title: "Ward Wise Collection Summary (<?=date ("Y-m-d H:i:s A")?>)",
            download: 'open',
            footer: { text: '' },
            exportOptions: { columns: [ 0, 1, 2, 3, 4] }
        }, {
            text: 'Print',
            extend: 'print',
            footer: true,
            title: "Ward Wise Collection Summary (<?=date ("Y-m-d H:i:s A")?>)",
            exportOptions: { columns: [ 0, 1, 2, 3, 4] }
        }],
		drawCallback: function( settings )
        {
            try
            {
                $("#footerResult").html(" (Total Holding - "+<?=$total_holding?>+", Current Collection - "+<?=round($current_collection,2)?>+", Arrear Collection - "+<?=round($arrear_collection,2)?>+", Total Collection - "+<?=round($total_collection,2)?>+")");
            }
            catch(err)
            {
                console.log(err.message);
            }
        }
    });
});
</script>
