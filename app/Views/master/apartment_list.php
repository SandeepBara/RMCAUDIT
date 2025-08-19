<?= $this->include('layout_vertical/header');?>
<?php 
$session = session();
$emp_details = $session->get("emp_details");
$user_type_mstr_id = $emp_details["user_type_mstr_id"];
?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
            <div id="content-container">
                <div id="page-head">
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Masters</a></li>
					<li class="active">Apartment List List</li>
                    </ol>
                </div>
                <div id="page-content">


					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel">
					            <div class="panel-heading">
					                <h5 class="panel-title">Apartment List</h5>
					            </div>
                                <div class="panel-body">
                                     <div class="pad-btm">
                                        <a href="<?php echo base_url('Apartment/create') ?>"><button id="demo-foo-collapse" class="btn btn-purple">Add New  <i class="fa fa-arrow-right"></i></button></a>
                                    </div>
					                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Apartment Name</th>
                                                <th>Apartment Code</th>
                                                <th>Ward No.</th>
                                                <th>Road Type</th>
                                                <th>Apartment Address</th>
                                                <th>Water Harvesting</th>
                                                <th>Is Blocks</th>
                                                <th>No of Block</th>
                                                <?php if (in_array($user_type_mstr_id, [1,2])) {?>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                                <?php }?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(isset($apt_list)):
                                          if(empty($apt_list)):
                                    ?>
                                         <tr>
                                            <td colspan="3" style="text-align: center;">Data Not Available!!</td>
                                        </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($apt_list as $value):
                                    ?>
                                        <tr>
                                            <td><?=++$i;?></td>
                                            <td><?=$value["apartment_name"]!=""?$value["apartment_name"]:"N/A";?></td>
                                            <td><?=$value["apt_code"]!=""?$value["apt_code"]:"N/A";?></td>
                                            <td><?=$value["ward_no"]!=""?$value["ward_no"]:"N/A";?></td>
                                            <td><?=$value["road_type"]!=""?$value["road_type"]:"N/A";?></td>
                                            <td><?=$value["apartment_address"]!=""?$value["apartment_address"]:"N/A";?></td>
                                            <td><?=$value["water_harvesting_status"]!=""?$value["water_harvesting_status"]:"N/A";?></td>
                                            <td><?=$value["is_blocks"]!=""?$value["is_blocks"]:"N/A";?></td>
                                            <td><?=$value["no_of_block"]!=""?$value["no_of_block"]:"N/A";?></td>
                                            <?php if (in_array($user_type_mstr_id, [1,2])) {?>
                                            <td><a class="btn btn-primary" href="<?php echo base_url('Apartment/create/'.$value['id']);?>" role="button">Edit</a></td>
                                            <td><button type="button" class="btn btn-primary" onclick="deletefun(<?=$value["id"];?>);">Delete</button> </td>
                                            <?php } ?>
                                        </tr>
                                        <?php endforeach;?>
                                         <?php endif;  ?>
                                    <?php endif;  ?>
 					                    </tbody>
					                </table>
                                </div>
					        </div>
					    </div>
					</div>
                </div>
                <!--===================================================-->
                <!--End page content-->

            </div>
            <!--===================================================-->
            <!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script> 
<script type="text/javascript">
	$(document).ready(function() {
		$('#demo_dt_basic').DataTable({
			responsive: true,
			dom: 'Bfrtip',
	        lengthMenu: [
	            [ 10, 25, 50, -1 ],
	            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
	        ],
	        buttons: [
	        	'pageLength',
	          {
				text: 'excel',
				extend: "excel",
				title: "Report",
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
			}]
		});
	});
   function deletefun(ID)
{
    var result = confirm("Do You Want To Delete");
    if(result)
     window.location.replace("<?=base_url();?>/Apartment/delete/"+ID);
}

</script>
