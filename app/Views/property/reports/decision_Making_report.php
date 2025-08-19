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
                <h5 class="panel-title">Decision Making Report</h5>
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
                                        <th>ULB Name</th>
                                        <th>HH as per Census Date 2011(1)</th>
										<th>Projected HH (2)</th>
                                        <th>ULB Provided Legacy Data(3)</th>
                                        <th>New Assessment (4.1)</th>
                                        <th>Re Assessment (4.2)</th>
										<th>Mutation (4.3)</th>
										<th>Total SAF 5=4</th>
                                        <th>To Be Reassessed From DB(6)</th>
                                        <th>Total HH as per Records(7=5+6)</th>
                                        <th> % of Non Reassessed(8=3/6)</th>
                                        <th>Fully Digitized SAF From DB(9)</th>
                                        <th>SAM(10)</th>
                                        <th>SAM % 11=10/9%</th>
                                        <th>Geo Tagging From DB(12)</th>
                                        <th>Geo Tagging % (13=12/9)</th>
										<th>Pure No Of Comm. HH (14.1)</th>
										<th>Mixed (14.2)</th>
										<th>Govt Building(14.3)</th>
										<th>Vacant Land (14.4)</th>
										<th>Pure No of Res. HH (14.5)</th>
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
