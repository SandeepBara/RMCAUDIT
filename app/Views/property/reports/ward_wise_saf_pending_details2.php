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
                <h5 class="panel-title">Ward Wise SAF,SAM,GEO,BTC,FAM Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form method="post">
                        <div class="row">
                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_mstr_id' name='ward_mstr_id' class="form-control">
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
                            <div class="col-md-2"></div>
                            <div class="col-md-2 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary btn-block" value="SEARCH" />                                
                            </div>
                        </div>
                    </form>
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
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th colspan="7" class="text-center">SAF, SAM, Geotagging and Back to citizen</th>
                                        <th colspan="4" class="text-center">Pending At Level</th>
                                        <th>FAM</th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>SAF No.</th>
                                        <th>SAF Digitized</th>
                                        <th>SAM</th>
                                        <th>Geotagging</th>
                                        <th>Back To Citizen</th>
                                        <th>DA</th>
                                        <th>UTC</th>
                                        <th>SI</th>
                                        <th>EO</th>
                                        <th>Final Memo</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                if (isset($pending_dtl))
                                {
                                    ?>
                                    <tbody>
                                    <?php
                                    $no_of_saf=0;
                                    $no_of_digitized=0;
                                    $no_of_sam=0;
                                    $no_of_geotagging=0;
                                    $no_of_pending_back_to_citizen=0;
                                    $no_of_pending_by_dealing_assistant=0;
                                    $no_of_pending_by_ulb_tc=0;
                                    $no_of_pending_by_section_incharge=0;
                                    $no_of_pending_by_executive_officer=0;
                                    $no_of_fam=0;
                                    $i = 0;
                                    foreach ($pending_dtl as $list)
                                    {
                                        ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$list['ward_no'];?></td>
                                            <td><?=$list['no_of_saf'];?></td>
                                            <td><?=$list['no_of_digitized'];?></td>
                                            <td><?=$list['no_of_sam'];?></td>
                                            <td><?=$list['no_of_geotagging'];?></td>
                                            <td><?=$list['no_of_pending_back_to_citizen'];?></td>
                                            <td><?=$list['no_of_pending_by_dealing_assistant'];?></td>
                                            <td><?=$list['no_of_pending_by_ulb_tc'];?></td>
                                            <td><?=$list['no_of_pending_by_section_incharge'];?></td>
                                            <td><?=$list['no_of_pending_by_executive_officer'];?></td>
                                            <td><?=$list['no_of_fam'];?></td>
                                        </tr>
                                        <?php
                                        $no_of_saf+=$list['no_of_saf'];
                                        $no_of_digitized+=$list['no_of_digitized'];
                                        $no_of_sam+=$list['no_of_sam'];
                                        $no_of_geotagging+=$list['no_of_geotagging'];
                                        $no_of_pending_back_to_citizen+=$list['no_of_pending_back_to_citizen'];
                                        $no_of_pending_by_dealing_assistant+=$list['no_of_pending_by_dealing_assistant'];
                                        $no_of_pending_by_ulb_tc+=$list['no_of_pending_by_ulb_tc'];
                                        $no_of_pending_by_section_incharge+=$list['no_of_pending_by_section_incharge'];
                                        $no_of_pending_by_executive_officer+=$list['no_of_pending_by_executive_officer'];
                                        $no_of_fam+=$list['no_of_fam'];
                                    }
                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th><?=$no_of_saf;?></th>
                                            <th><?=$no_of_digitized;?></th>
                                            <th><?=$no_of_sam;?></th>
                                            <th><?=$no_of_geotagging;?></th>
                                            <th><?=$no_of_pending_back_to_citizen;?></th>
                                            <th><?=$no_of_pending_by_dealing_assistant;?></th>
                                            <th><?=$no_of_pending_by_ulb_tc;?></th>
                                            <th><?=$no_of_pending_by_section_incharge;?></th>
                                            <th><?=$no_of_pending_by_executive_officer;?></th>
                                            <th><?=$no_of_fam;?></th>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript">
$("#btn_search").click(function(){
    loadingAnimation();
    return true;
});
const loadingAnimation = function() {
    var timeLap = 2;
    $("#btn_search").val("LOADING .");
    window.setInterval(function(){ 
        if(timeLap==1){
            $("#btn_search").val("LOADING .");
            timeLap++;
        }
        else if(timeLap==2){
            $("#btn_search").val("LOADING ..");
            timeLap++;
        }
        else if(timeLap==3){
            $("#btn_search").val("LOADING ...");
            timeLap = 1;
        }
    }, 1000);
}

$(document).ready(function(){
    var dataTable = $('#dataTableID').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        "paging": false,
        "info": false,
        "searching":false,
        "aaSorting": [],
        /* "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0, 1, 2 ] }, 
            { "bSearchable": false, "aTargets": [ 0, 1, 2 ] }
        ], */
        buttons: [
            {
            text: 'Excel',
            extend: "excel",
            title: "Ward Wise SAF,SAM,GEO,BTC,FAM Details (<?=date ("Y-m-d H:i:s A")?>)",
            footer: { text: '' },
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10, 11 ] }
        }, {
            text: 'Print',
            extend: 'print',
            title: "Ward Wise SAF,SAM,GEO,BTC,FAM Details (<?=date ("Y-m-d H:i:s A")?>)",
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11 ] }
        }]
    });
});
</script>
