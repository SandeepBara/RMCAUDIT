<?= $this->include('layout_vertical/header'); ?>
<!--DataTables [ OPTIONAL ]-->
<link href="<?= base_url(''); ?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(''); ?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">System</a></li>
            <li class="active">Employee Details List</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <?php if(in_array($user_type_mstr_id, [1,2,38])) { ?>
                        <div class="panel-control">
                            <a href="<?= base_url() ?>/EmpDetails/empList?action=refreshEmp" class="btn btn-mint">Refresh Employees</a>
                        </div>
                        <?php } ?>
                        <h5 class="panel-title">Employee Details List</h5>
                    </div>
                    <div class="panel-body">
                        <?php if(in_array($user_type_mstr_id, [1,2])) { ?>                        
                            <div class="pad-btm">
                                <a href="<?= base_url('EmpDetails/add_update') ?>"><button id="demo-foo-collapse" class="btn btn-primary">Add New <i class="fa fa-arrow-right"></i></button></a>
                            </div>
                        <?php } ?>
                        <table id="demo_dt_basic" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Employee Code</th>
                                    <th>User Name</th>
                                    <th>Employee Name</th>
                                    <th>Guardian Name</th>
                                    <th>Phone No</th>
                                    <th>Email Id</th>
                                    <th>User Type</th>
                                    <?php if(in_array($user_type_mstr_id, [1,2,38])) { ?>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Reset</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!isset($emplist)) :
                                ?>
                                    <tr>
                                        <td colspan="10" style="text-align: center;">Data Not Available!!</td>
                                    </tr>
                                    <?php else :
                                    $i = 0;
                                    foreach ($emplist as $value) :
                                    ?>
                                        <tr>
                                            <td><?= ++$i; ?></td>
                                            <td><?= $value['employee_code'] != "" ? $value['employee_code'] : ""; ?></td>
                                            <td><?= $value['user_name'] != "" ? $value['user_name'] : ""; ?></td>
                                            <td><?= $value['emp_name'] != "" ? $value['emp_name'] : ""; ?>
                                                <?= $value['middle_name'] != "" ? $value['middle_name'] : ""; ?>
                                                <?= $value['last_name'] != "" ? $value['last_name'] : ""; ?>
                                            </td>
                                            <td><?= $value['guardian_name'] != "" ? $value['guardian_name'] : ""; ?></td>
                                            <td><?= $value['personal_phone_no'] != "" ? $value['personal_phone_no'] : ""; ?></td>
                                            <td><?= $value['email_id'] != "" ? $value['email_id'] : ""; ?></td>
                                            <td><?= $value['user_type'] != "" ? $value['user_type'] : ""; ?></td>
                                            <?php if(in_array($user_type_mstr_id, [1,2,38])) { ?>
                                            <td>
                                                <?php if ($value['lock_status'] != 1) { ?>
                                                    <a class="btn btn-primary" href="<?php echo base_url('EmpDetails/add_update/' . md5($value['id'])); ?>" role="button">Edit</a>
                                                <?php } else { ?>
                                                    Services Lock
                                                <?php }

                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($value['lock_status'] == 1) { ?>
                                                    <button type="button" class="btn btn-primary" onclick="unlockfun(<?= $value['user_mstr_id']; ?>)">Unlock</button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-primary" onclick="lockfun(<?= $value['user_mstr_id']; ?>)">Lock</button>
                                                <?php }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if($value['lock_status']==0){?>  
                                                    <button type="button" class="btn btn-primary" onclick="resetPassword('<?=$value['user_mstr_id'];?>')">Password Reset</button> 
                                                <?php } ?>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                    <?php endforeach; ?>
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
<?= $this->include('layout_vertical/footer'); ?>
<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url(''); ?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#demo_dt_basic').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'excel',
                    extend: "excel",
                    title: "Report",
                    footer: {
                        text: ''
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Report",
                    download: 'open',
                    footer: {
                        text: ''
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                }
            ]
        });
    });

    function lockfun(user_mstr_id) {
        var result = confirm("Do You Want To Lock Employee Services");
        if (result)
            window.location.replace("<?= base_url(); ?>/EmpDetails/lockEmployee/" + user_mstr_id);
    }

    function unlockfun(user_mstr_id) {
        var result = confirm("Do You Want To Unlock Employee Services");
        if (result)
            window.location.replace("<?= base_url(); ?>/EmpDetails/unlockEmployee/" + user_mstr_id);
    }

    function modelInfo(msg) {
        $.niftyNoty({
            type: 'info',
            icon: 'pli-exclamation icon-2x',
            message: msg,
            container: 'floating',
            timer: 5000
        });
    }
    function resetPassword(id){
        if(confirm('Are you sure you want to reset password?')){

            $.ajax({
                url:"<?=base_url();?>/ChangePassword/resetPassword/"+id,
                type:"post",
                dataType:"json",
                beforeSend:function(){
                    $("#loadingDiv").show();
                },
                success:function(response){
                    $("#loadingDiv").hide();
                    modelInfo(response?.message);
                },
                error:function(errors){
                    console.log("error:",errors);
                    modelInfo("server error");
                }
            });
        }
    }
    <?php
    if ($empList = flashToast('empList')) {
        echo "modelInfo('" . $empList . "');";
    }
    if ($lock = flashToast('lock')) {
        echo "modelInfo('" . $lock . "');";
    }
    if ($fail_lock = flashToast('fail_lock')) {
        echo "modelInfo('" . $fail_lock . "');";
    }
    if ($unlock = flashToast('unlock')) {
        echo "modelInfo('" . $unlock . "');";
    }
    if ($fail_unlock = flashToast('fail_unlock')) {
        echo "modelInfo('" . $fail_unlock . "');";
    }
    if ($empUpdate = flashToast('empUpdate')) {
        echo "modelInfo('" . $empUpdate . "');";
    }
    ?>
</script>