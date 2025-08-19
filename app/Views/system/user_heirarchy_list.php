<!--DataTables [ OPTIONAL ]-->
<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
            <!--===================================================-->
            <div id="content-container">
                <div id="page-head">

                    <!--Page Title-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div id="page-title">
                       <!-- <h1 class="page-header text-overflow">Department List</h1>//-->
                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End page title-->


                    <!--Breadcrumb-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <ol class="breadcrumb">
					<li><a href="#"><i class="demo-pli-home"></i></a></li>
					<li><a href="#">System</a></li>
					<li class="active">User Hierarchy List</li>
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
					                <h5 class="panel-title">User Hierarchy List</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="pad-btm">
                                        <a href="<?php echo base_url('UserHeirarchy/add_update');?>"><button id="demo-foo-collapse" class="btn btn-primary">Add New <i class="fa fa-arrow-right"></i></button></a>
                                    </div>
					                <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Type</th>
                                                <th>Under User Type</th>
                                                <th>Edit</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php
                                     if($userHierarchyList):
                                            $i=0;
                                            foreach($userHierarchyList as $value):
                                    ?>
                                            <tr>
                                                <td><?=++$i;?></td>
                                                <td><?=$value['user_type']!=""?$value['user_type']:"";?></td>
                                                <td>
                                                   <?php
                                                    if(isset($value['under_user'])){
                                                        foreach ($value['under_user'] as $value2) {
                                                            echo $value2['under_user_type'];
                                                            echo "<br />";
                                                        }
                                                    }
                                                ?>   
                                                </td>
                                                <td>
                                                    <a class="btn btn-primary" href="<?php echo base_url('UserHeirarchy/add_update/'.$value['user_type_mstr_id']);?>" role="button">Edit</a>
                                                </td>
                                                <td>
                                                     <button type="button" class="btn btn-primary" onclick="deletefun(<?=$value["user_type_mstr_id"];?>);">Delete</button> 
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                         <?php endif; ?>
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
				exportOptions: { columns: [ 0,1,2,3] }
			}]
		});
	});
 function deletefun(ID)
{
    var result = confirm("Do You Want To Delete!!!");
    if(result)
     window.location.replace("<?=base_url();?>/UserHeirarchy/DeleteUserType/"+ID);
}
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php 
    if($user_heirarchy_list=flashToast('user_heirarchy_list'))
    {
        echo "modelInfo('".$user_heirarchy_list."');";
    }
    if($update=flashToast('update'))
    {
        echo "modelInfo('".$update."');";
    }
?>
</script>