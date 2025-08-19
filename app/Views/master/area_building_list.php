<?= $this->include('layout_vertical/header');?>
<!--DataTables [ OPTIONAL ]-->

<!--CONTENT CONTAINER-->
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                        <!--<h1 class="page-header text-overflow">Designation List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">Masters</a></li>
					<li class="active">Area Building List</li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->

                </div>


                <!--Page content-->
                <!--===================================================-->
                <div id="page-content">


					<div class="row">
					    <div class="col-sm-12">
					        <div class="panel">
					            <div class="panel-heading">
					                <h5 class="panel-title">Area Building List</h5>

					            </div>
                                <div class="panel-body">
                                     <div class="pad-btm">
                                        <a href="<?php echo base_url('Area_Building/add_update') ?>"><button id="demo-foo-collapse" class="btn btn-purple">Add New  <i class="fa fa-arrow-right"></i></button></a>
                                    </div>
					                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Road Type</th>
                                                <th>Construnction Type</th>
                                                <th>Given Rate</th>
                                                <th>Calculated Rate</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                    if(!isset($areaBuildingList)):
                                    ?>
                                            <tr>
                                                <td colspan="6" style="text-align: center;">Data Not Available!!</td>
                                            </tr>
                                    <?php else:
                                            $i=0;
                                            foreach ($areaBuildingList as $value):
                                    ?>
                                        <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value["road_type"]!=""?$value['road_type']:"";?></td>
                                            <td><?=$value["construction_type"]!=""?$value['construction_type']:"";?></td>
                                                <td><?=$value["given_rate"]!=""?$value['given_rate']:"";?></td>
                                                <td><?=$value["cal_rate"]!=""?$value['cal_rate']:"";?></td>
                                                <td><?=$value["date_of_effect"]!=""?$value['date_of_effect']:"";?></td>
                                              <td>
                                               <a class="btn btn-primary" href="<?php echo base_url('Area_Building/add_update/'.$value['id']);?>" role="button">Edit</a>
                                                        &nbsp;&nbsp;
                                                <button type="button" class="btn btn-primary" onclick="deletefun(<?=$value["id"];?>);">Delete</button> 
                                              </td>
                                        </tr>
                                        <?php endforeach;?>
                                         <?php endif;?>
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
<script src="<?=base_url();?>/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url();?>/assets/datatables/js/jszip.min.js"></script>
<script src="<?=base_url();?>/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?=base_url();?>/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?=base_url();?>/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?=base_url();?>/assets/datatables/js/dataTables.responsive.min.js"></script>
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
				exportOptions: { columns: [ 0, 1,2,3] }
			}, {
				text: 'pdf',
				extend: "pdf",
				title: "Report",
				download: 'open',
				footer: { text: '' },
				exportOptions: { columns: [ 0, 1,2,3] }
			}]
		});
        
	});
function deletefun(ID)
{
    var result = confirm("Do You Want To Delete");
    if(result)
     window.location.replace("<?=base_url();?>/Area_Building/deleteAreaBuilding/"+ID);
}
</script>
