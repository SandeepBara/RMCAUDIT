<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<!-- <link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet"> -->
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
                <h5 class="panel-title">SAF, SAM & GEO Tagging</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form method="post">
                        <div class="row">
                            <div class="col-md-3" id="from_date_cont">
								<label class="control-label" for="from_date"><b>From Date</b> <span class="text-danger">*</span></label>
								<input type="date" id="from_date" name="from_date" class="form-control" placeholder="From Date" value="<?= (isset($from_date)) ? $from_date : date('Y-m-d'); ?>">
							</div>
							<div class="col-md-3" id="to_date_cont">
								<label class="control-label" for="to_date"><b>To Date</b> <span class="text-danger">*</span></label>
								<input type="date" id="to_date" name="to_date" class="form-control" placeholder="To Date" value="<?= (isset($to_date)) ? $to_date : date('Y-m-d'); ?>">
							</div>

                            
                            <div class="col-md-3 pad-btm">
                                <label class="control-label"><b>Ward No.</b></label>
                                <select id='ward_mstr_id' name='ward_mstr_id' class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($wardList))
                                {
                                    foreach ($wardList as $list)
                                    {
                                        ?>
                                            <option value='<?=$list['id'];?>' <?=(isset($ward_mstr_id))?($ward_mstr_id==$list['id'])?"selected":"":"";?>><?=$list['ward_no'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <div class="col-md-3 text-right">
                                <label class="control-label">&nbsp;&nbsp;&nbsp;&nbsp;</label><br/>
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
                                        <th>Ward No.<br> (1)</th>
                                        <th>No. of SAF<br> (2)</th>
                                        <th>No. of SAM<br>(3)</th>
                                        <th>No. of FAM<br>(4)</th>
                                        <th>No. of Geotagging<br>(5)</th>
                                        <th>No. of Back to Citizen<br>(6)</th>
                                        <th>No. of SAM Pending<br>(7)</th>
                                        <th>No. of FAM Pending<br>(8)</th>
                                    </tr>
                                </thead>
                                
                                <?php

                                if (isset($pending_dtl))
                                {
                                    ?>
                                    <tbody>
                                    <?php
                                    $no_of_saf=0;
                                    $no_of_sam=0;
                                    $no_of_fam=0;
                                    $no_of_geotagging=0;
                                    $no_of_back_to_citizen=0;
                                    $no_of_sam_pending=0;
                                    $no_of_fam_pending=0;
                                    $i = 0;
                                    foreach ($pending_dtl as $list)
                                    {
										if($list['no_of_saf']>0)
										{
                                        ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$list['ward_no'];?></td>
                                            <td><?=$list['no_of_saf'];?></td>
                                            <td><?=$list['no_of_sam'];?></td>
                                            <td><?=$list['no_of_fam'];?></td>
                                            <td><?=$list['no_of_geotagging'];?></td>
                                            <td><?=$list['no_of_back_to_citizen'];?></td>
                                            <td><?=$list['no_of_sam_pending'];?></td>
                                            <td><?=$list['no_of_fam_pending'];?></td>
                                        </tr>
                                        <?php
                                        $no_of_saf+=$list['no_of_saf'];
                                        $no_of_sam+=$list['no_of_sam'];
                                        $no_of_fam+=$list['no_of_fam'];
                                        $no_of_geotagging+=$list['no_of_geotagging'];
                                        $no_of_back_to_citizen+=$list['no_of_back_to_citizen'];
                                        $no_of_sam_pending+=$list['no_of_sam_pending'];
                                        $no_of_fam_pending+=$list['no_of_fam_pending'];
										}
									}

                                    ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th><?=$no_of_saf;?></th>
                                            <th><?=$no_of_sam;?></th>
                                            <th><?=$no_of_fam;?></th>
                                            <th><?=$no_of_geotagging;?></th>
                                            <th><?=$no_of_back_to_citizen;?></th>
                                            <th><?=$no_of_sam_pending;?></th>
                                            <th><?=$no_of_fam_pending;?></th>
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
    $("#btn_search").val("LOADING ...");
});
$(document).ready(function(){
    var dataTable = $('#dataTableID').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        "paging": false,
        "info": false,
        "searching":false,
        "aaSorting": [],
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5 ] }, 
            { "bSearchable": false, "aTargets": [ 0, 1, 2, 3, 4, 5 ] }
        ],
        buttons: [
            {
            text: 'Excel',
            extend: "excel",
            title: "Ward Wise SAF Pending Details (<?=date ("Y-m-d H:i:s A")?>)",
            footer: { text: '' },
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7] }
        }, {
            text: 'Print',
            extend: 'print',
            footer: true,
            title: "Ward Wise SAF Pending Details (<?=date ("Y-m-d H:i:s A")?>)",
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7] }
        }]
    });
});
</script>
