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
                <h5 class="panel-title">Ward Wise SAF Pending Details</h5>
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
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Total HH</th>
                                        <th>No. of SAF</th>
                                        <th>No. of SAM</th>
                                        <th>No. of FAM</th>
                                        <th>No. of Geotagging</th>
                                        <th>Pending SAF</th>
                                        <th>No. of Pending By <br /> Dealing Assistant</th>
                                        <th>No. of Pending By <br /> ULB Tax Collector</th>
                                        <th>No. of Pending By <br /> Section Incharge</th>
                                        <th>No. of Pending By <br /> Executive Officer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (isset($pending_dtl)) {
                                    $i = 0;
                                    foreach ($pending_dtl as $list) {
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$list['ward_no'];?></td>
                                        <td><?=$list['no_of_prop'];?></td>
                                        <td><?=$list['no_of_saf'];?></td>
                                        <td><?=$list['no_of_sam'];?></td>
                                        <td><?=$list['no_of_fam'];?></td>
                                        <td><?=$list['no_of_geotagging'];?></td>
                                        <td><?=$list['no_of_saf_pending'];?></td>
                                        <td><?=$list['no_of_pending_by_dealing_assistant'];?></td>
                                        <td><?=$list['no_of_pending_by_ulb_tc'];?></td>
                                        <td><?=$list['no_of_pending_by_section_incharge'];?></td>
                                        <td><?=$list['no_of_pending_by_executive_officer'];?></td>
                                    </tr>
                                <?php
                                    }
                                }
                                ?>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var dataTable = $('#dataTableID').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        "paging": false,
        "info": false,
        "searching":false,
        "aaSorting": [],
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0, 1, 2 ] }, 
            { "bSearchable": false, "aTargets": [ 0, 1, 2 ] }
        ],
        buttons: [
            {
            text: 'Excel',
            extend: "excel",
            title: "Ward Wise SAF Pending Details (<?=date ("Y-m-d H:i:s A")?>)",
            footer: { text: '' },
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11 ] }
        }, {
            text: 'Print',
            extend: 'print',
            title: "Ward Wise SAF Pending Details (<?=date ("Y-m-d H:i:s A")?>)",
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10,11 ] }
        }]
    });
});
</script>
