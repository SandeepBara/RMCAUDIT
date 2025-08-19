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
                <h5 class="panel-title">Holding Collecton</h5>
            </div>
            <div class="panel-body">
                <div class="row">
				<form action="<?php echo base_url('safsamandgeotagging_report/holding_collection');?>" method="post" role="form">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_mstr_id' name='ward_mstr_id' class="form-control">
                                    <option value=''>ALL</option>
									<?php
									if (isset($wardList)) {
                                    foreach ($wardList as $list) {
									?>
                                    <option value="<?=$list['id'];?>"<?=(isset($ward_mstr_id))?$ward_mstr_id==$list['id']?"SELECTED":"":"";?>><?=$list['ward_no'];?></option>
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
                    </div>
				</form>
                </div>
            </div>
        </div>

		<?php $demand=0;$collection=0;$onlineCollection=0;$otherThanOnline=0;
		if($holding_collection): ?>
			<?php foreach($holding_collection as $holding_col): ?>
				<?php 
				$demand=$demand+$holding_col['total_demand'];
				$collection=$collection+$holding_col['total_collection'];
				$onlineCollection=$onlineCollection+$holding_col['online_collection'];
				$otherThanOnline=$otherThanOnline+$holding_col['other_than_online'];
				?>
			<?php endforeach; ?>
		<?php endif; ?>
        <div class="panel panel-dark">
            <div class="panel-heading">
				<div class="col-md-2">
					<h5 class="panel-title">Result</h5>
				</div>
				<div class="col-md-10">
					<div class="col-md-3">
						Total Demand<br><?=$demand;?>
					</div>
					<div class="col-md-3">
						Total Collection<br><?=$collection;?>
					</div>
					<div class="col-md-3">
						Total Online Collection<br><?=$onlineCollection;?>
					</div>
					<div class="col-md-3">
						Other Than Online<br><?=$otherThanOnline;?>
					</div>
				</div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ward No.<br> 1</th>
                                        <th>Total Demand<br> 2</th>
                                        <th>Total Collection<br> 3</th>
										<th>Total Online Collection<br> 3.1</th>
                                        <th>Other Than Online<br> 3.2 = 3 - 3.1</th>
                                    </tr>
									
                                </thead>
                                <tbody>
									<?php $i=1;
									if($holding_collection): ?>
										<?php foreach($holding_collection as $holding_collection): ?>
											
										<tr>
										  <td><?=$i++; ?></td>
										  <td><?php echo $holding_collection['ward_no']; ?></td>
										  <td class="text-right"><?php echo $holding_collection['total_demand']; ?></td>
										  <td class="text-right"><?php echo $holding_collection['total_collection']; ?></td>
										  <td class="text-right"><?php echo $holding_collection['online_collection']; ?></td>
										  <td class="text-right"><?php echo $holding_collection['other_than_online']; ?></td>
										</tr>
										<?php endforeach; ?>
										<tr style="color:black;font-size:15px;font-weight:bold;">
											<td colspan="2" class="text-right">Total</td>
											<td class="text-right"><?=$demand;?></td>
											<td class="text-right"><?=$collection;?></td>
											<td class="text-right"><?=$onlineCollection;?></td>
											<td class="text-right"><?=$otherThanOnline;?></td>
										</tr>
									<?php else: ?>
										<tr>
											<td colspan="6" style="text-align:center;color:red;"> Data Are Not Available!!</td>
										</tr>
									<?php endif; ?>
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

