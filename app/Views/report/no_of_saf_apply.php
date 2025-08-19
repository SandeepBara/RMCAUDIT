<?= $this->include('layout_vertical/header');?>

<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">

<!--CONTENT CONTAINER-->
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
       
        <div class="panel panel-dark">
            <div class="panel-heading">
				<div class="panel-control">
				</div>
                <h5 class="panel-title">No. Of Saf Apply</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>                                        
                                        <th>Assessment Type</th>
                                        <th>No. of SAF apply</th>     
                                    </tr>
                                </thead>
                                <tbody>
								<?php $i=1; $no_of_saf = 0; ?>
								<?php foreach($apply_saf_dtl as $apply_saf): ?>
									<tr>
										<td><?=$i++; ?></td> 
                                        <td><?=$apply_saf['assessment_type']; ?></td>
                                        <td><?php $no_of_saf += $apply_saf['no_of_saf']; ?><?=$apply_saf['no_of_saf']; ?></td>
                                    </tr>
								<?php endforeach; ?>
                                </tbody>
                                <tfooter>
                                	<th></th>
                                	<th>Total</th>
                                	<th><?=$no_of_saf;?></th>
                                </tfooter>
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
