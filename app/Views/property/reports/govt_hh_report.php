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
                <h5 class="panel-title">MPL Govt legacy HH Report</h5>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive" style="overflow: hidden;">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name of ULB</th>
                                        <th>No. of HH in legacy <br> (<?=date('d.m.Y');?>)</th>
										<th>Demand of HH <br/> (Rs. in Lakhs) </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr>
                                        <td>1</td>
                                        <td><?=$data['ulb_name']; ?></td>
                                        <td><?=$data['gb_saf_hh']; ?></td>
                                        <td><?=$data['gb_demand']; ?></td>
                                    </tr>
                                    <tr style="font-weight:bold;">
                                        <td colspan="2" align="right">Total :-</td>
                                        <td><?=$data['gb_saf_hh']; ?></td>
                                        <td><?=$data['gb_demand']; ?></td>
                                    </tr>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
