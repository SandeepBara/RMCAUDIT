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
                <h5 class="panel-title">Pending Issues</h5>
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

		<?php $digitized=0;$sam=0;$legacy=0;$fam=0;
		if($pending_dtl): ?>
			<?php foreach($pending_dtl as $pend_dtl): ?>
				<?php 
				$digitized=$digitized+$pend_dtl['no_of_digitized'];
				$sam=$sam+$pend_dtl['no_of_sam'];
				$legacy=$legacy+$pend_dtl['no_of_legacy'];
				$fam=$fam+$pend_dtl['no_of_fam'];
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
						Total Digitized<br><?=$digitized;?>
					</div>
					<div class="col-md-3">
						Total SAM<br><?=$sam;?>
					</div>
					<div class="col-md-3">
						Total Legacy<br><?=$legacy;?>
					</div>
					<div class="col-md-3">
						Total Final Memo<br><?=$fam;?>
					</div>
				</div>
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
                                        <th>SAF Digitized</th>
                                        <th>SAM</th>
										<th>Legacy</th>
                                        <th>Back To Citizen</th>
                                        <th>Final Memo</th>
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
                                        <td><?=$list['no_of_digitized'];?></td>
                                        <td><?=$list['no_of_sam'];?></td>
										<td><?=$list['no_of_legacy'];?></td>
                                        <td>BACK TO CITIZEN</td>
                                        <td><?=$list['no_of_fam'];?></td>
                                    </tr>
                                <?php
                                    }
								?>
									<tr style="color:black;font-size:15px;font-weight:bold;">
                                        <td colspan="2" class="text-right">Total</td>
                                        <td><?=$digitized;?></td>
                                        <td><?=$sam;?></td>
										<td><?=$legacy;?></td>
                                        <td>BACK</td>
                                        <td><?=$fam;?></td>
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
            title: "Pending Issues (<?=date ("Y-m-d H:i:s A")?>)",
            footer: { text: '' },
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10 ] }
        }, {
            text: 'Print',
            extend: 'print',
            title: "Pending Issues (<?=date ("Y-m-d H:i:s A")?>)",
            exportOptions: { columns: [ 0,1,2,3,4,5,6,7,8,9,10 ] }
        }]
    });
});
</script>
