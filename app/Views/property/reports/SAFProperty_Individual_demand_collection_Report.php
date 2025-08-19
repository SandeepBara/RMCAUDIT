<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">SAF Property Individual demand collection Report</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        
                        <div class="row">
                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_mstr_id' class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($wardList)) {
                                    foreach ($wardList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>'><?=$list['ward_no'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            <label class="col-md-2 text-bold">Financial Years</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='collector_id' class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($fyList)) {
                                    foreach ($fyList as $list) {
                                ?>
                                    <option value='<?=$list['id'];?>'><?=$list['fy'];?></option>
                                <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
							<div class="col-md-2">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                        
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
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.</th>
                                        <th>Holding No.</th>
										<th>SAF No.</th>
                                        <th>Owner Name</th>
                                        <th>Mobile No.</th>
                                        <th>Application Type</th>
										<th>Usage Type</th>
										<th>Construction Type</th>
                                        <th>Final Approved</th>
                                        <th>Address</th>
                                        <th>Demand Before 2019-2020</th>
                                        <th>Demand for 2019-2020</th>
                                        <th>Demand for 2020-2021</th>
                                        <th>Total Demand</th>
                                        <th>Total Collection Before 2019-2020</th>
                                        <th>Total Collection in 2019-2020</th>
										<th>Total Collection in 2020-2021</th>
										<th>Total Collection</th>
										<th>Penalty</th>
										<th>Rebate</th>
										<th>Advance</th>
										<th>Advance Not Against TXN</th>
										<th>Adjust</th>
										<th>Total Dues</th>
										<th>Demand Status</th>
                                    </tr>
                                </thead>
                                <tbody>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
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
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
