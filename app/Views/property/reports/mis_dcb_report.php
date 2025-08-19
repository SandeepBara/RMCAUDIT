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
                <h5 class="panel-title">MIS DCB Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                    <form method="post">
                       <div class="row">
                            <label class="col-md-2 text-bold">Financial Year</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='fy_mstr_id' name="fy_mstr_id" class="form-control">
                                    <!-- <option value=''>ALL</option> -->
                                <?php
                                if (isset($fyList)) {
                                    foreach ($fyList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>' <?=(isset($fy_mstr_id))?($fy_mstr_id==$list['id'])?"selected":"":"";?>><?=$list['fy'];?></option>
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
                    </form>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="panel panel-dark">
            <div class="panel-heading">
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="dataTableID" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name of ULB</th>
                                        <th>Number of properties mapped</th>
                                        <th>Number of properties against which property tax bill raised</th>
                                        <th>Amount of property tax raised (As per ULb)</th>
                                        <th>Property tax collected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (isset($report_list)) {
                                    $i = 0;
                                    foreach($report_list as $list)
                                    {
                                        $i++;
                                        ?>
                                        <tr >
                                            <td><?=$i?></td>
                                            <td>Ranchi Municipal Corporation</td>
                                            <td><?=$list['prop_count'];?></td>
                                            <td><?=$list['current_holding'];?></td>
                                            <td><?=$list['current_demand'];?></td>
                                            <td><?=$list['current_collection_amount'];?></td>
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
<div class="modal" id="pie-chart-default-modal" role="dialog" tabindex="-1" aria-labelledby="demo-default-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><i class="pci-cross pci-circle"></i></button>
                <h4 class="modal-title">Pie Chart</h4>
            </div>
            <!--Modal body-->
            <div class="modal-body">
                <div id="piechart"></div>
            </div>
        </div>
    </div>
</div>
<!--End Default Bootstrap Modal-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>