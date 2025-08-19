<!--CONTENT CONTAINER-->
<?= $this->include('layout_vertical/header');?>
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<div id="content-container">
    <div id="page-head">
        <div id="page-title"><!--Page Title-->
            <h1 class="page-header text-overflow"></h1>
        </div><!--End page title-->
        <ol class="breadcrumb"><!--Breadcrumb-->
    		<li><a href="#"><i class="demo-pli-home"></i></a></li>
    		<li class="active">User List</li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel">
		            <div class="panel-heading">
                        <div class="panel-control">
                            <a href="<?=base_url();?>/MenuPermission2/menuList" class="btn btn-primary">Back</a>
                        </div>
                        <h5 class="panel-title">Menu List</h5>
		            </div>
                    <form method="POST" action="<?=base_url();?>/MenuPermission/MenuAddEdit/<?=(isset($data['menu_mstr_id']))?$data['menu_mstr_id']:'';?>">
                        <div class="panel-body">
                            <!--<pre>
                            <?php //print_r($menulist); ?>
                            </pre> -->
                            <div class="table-responsive">
                                <table id="demo_dt_basic" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>User Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if(isset($user_type_list)) {
                                        $i = 0;
                                        foreach ($user_type_list as $value) {
                                            $i++;
                                    ?>
                                            <tr>
                                                <td><?=$i;?></td>
                                                <td><?=$value['user_type'];?></td>
                                                <td>
                                                    <a href="<?=base_url();?>/MenuPermission2/updateMenuUserType?user_type_id=<?=$value["id"];?>" class="btn btn-primary">Update Menu</a> 
                                                </td>
                                            </tr>
                                    <?php
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?=$i;?></td>
                                        <td>Super Admin</td>
                                        <td>
                                            <a href="<?=base_url();?>/MenuPermission2/updateMenuUserType?user_type_id=1" class="btn btn-primary">Update Menu</a> 
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
		        </div>
		    </div>
		</div>
    </div>
    <!--End page content-->
</div>
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
    $(document).ready(function(){
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
              {
                text: 'excel',
                extend: "excel",
                title: "Report",
                footer: { text: '' },
                exportOptions: { columns: [ 0,1,2,3] }
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
    var result = confirm("Do You Want To Deactivate Menu");
    if(result)
     window.location.replace("<?=base_url();?>/MenuPermission/MenuDeactivate/"+ID);
}
</script>