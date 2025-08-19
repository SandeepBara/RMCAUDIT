<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Government Saf Individual Demand And Collecton</h5>
            </div>
            <div class="panel-body">
                <form method="post">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_mstr_id' name="ward_mstr_id" class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($wardList)) {
                                    foreach ($wardList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>' <?=(isset($ward_mstr_id))?($ward_mstr_id==$list['id'])?"SELECTED='SELECTED'":"":"";?>><?=$list['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <div class="col-md-2 text-right">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                        <div class="row">
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <?php if (isset($result)) { ?>
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result <span id="total_head_dtl"></span></h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="demo_dt_basic" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Application No</th>
										<th>Building Colony Name</th>
                                        <th>Building Colony Address</th>
                                        <th>Total Demand</th>
                                        <th>Total Collection</th>
                                        <th>Total Remaining</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 0;
                                $total_demand = 0;
                                $total_collection = 0;
                                $total_due = 0;
                                foreach ($result AS $key=>$list) {
                                    $total_demand = $list['total_demand'];
                                    $total_collection = $list['total_collection'];
                                    $total_due = $list['total_due'];
                                ?>
                                    <tr>
                                        <td><?=++$i;?></td>
                                        <td><?=$list['ward_no']?></td>
                                        <td><?=$list['application_no']?></td>
                                        <td><?=$list['building_colony_name']?></td>
                                        <td><?=$list['building_colony_address']?></td>
                                        <td><?=number_format_ind($list['total_demand'], 2)?></td>
                                        <td><?=number_format_ind($list['total_collection'], 2)?></td>
                                        <td><?=number_format_ind($list['total_due'], 2)?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
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
<script type="text/javascript">
$(document).ready(function(){
    $('#demo_dt_basic').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all']
        ],
        buttons: [
            'pageLength',
            {
            text: 'excel',
            extend: "excel",
            title: "Government Saf Individual Demand And Collecton",
            footer: { text: '' },
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7 ] }
        }]
    });
    <?php
        if(isset($total_demand)) {
    ?>
            $("#total_head_dtl").html('(Total Demand = <?=number_format_ind($total_demand);?>, Total Collection = <?=number_format_ind($total_collection);?>, Total Remaining = <?=number_format_ind($total_due);?>)');
    <?php
        }
    ?>
});
</script>
