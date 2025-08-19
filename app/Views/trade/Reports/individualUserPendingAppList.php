<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
<style>
    .buttons-page-length{
        display: none !important;
    }
</style>
<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">User Wise Pending Report</h5>
            </div>
            <div class="panel-body">
                <form name="myform" id = "myform" method="get">
                    <div class="row">
                        <div class="col-md-12">                       
                            <div class="row">
                                <label class="col-md-2 text-bold">Ward No.</label>
                                <div class="col-md-3 has-success pad-btm">
                                    <select id='ward_mstr_id' name = "ward_mstr_id" class="form-control">
                                        <option value=''>ALL</option>
                                    <?php
                                    if (isset($ward_list)) 
                                    {
                                        foreach ($ward_list as $list) 
                                        {
                                            ?>
                                                <option value='<?=$list['id'];?>' <?=isset($ward_mstr_id) && $ward_mstr_id==$list['id'] ? 'selected':''?>><?=$list['ward_no'];?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>  
                                <label class="col-md-2 text-bold">User Name</label> 
                                <div class="col-md-3 has-success pad-btm">
                                    <select id='emp_id' name = "emp_id" class="form-control">
                                        <option value=''>ALL</option>
                                    <?php
                                    if (isset($user_list)) 
                                    {
                                        foreach ($user_list as $list) 
                                        {
                                            ?>
                                                <option value='<?=$list['emp_id'];?>' <?=isset($emp_id) && $emp_id==$list['emp_id'] ? 'selected':''?>><?=$list['emp_name'];?> (<?=$list['user_type'];?>) </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                    </select>
                                </div>   

                            </div>
                            <div class="row">                            
                                <div class="col-md-4 text-right">
                                    <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading ">
                <h5 class="panel-title">User List</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">                        
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                       <th>User Name</th>
                                       <th>User Type</th>
                                       <th>Total Pending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if(!isset($level_pending) || empty($level_pending['result']))
                                    {
                                        ?>
                                        <tr> 
                                            <td colspan="4" class="text-center text-danger"> No Data !!!!!</td>
                                        </tr>
                                        <?php
                                    }
                                    else
                                    {
                                        $i = $level_pending['offset']??0;
                                        foreach($level_pending['result'] as $val)
                                        {
                                            ?>
                                            <tr> 
                                                <td><?=++$i??1;?></td>
                                                <td><?=$val['emp_name']??'N/A';?></td>
                                                <td><?=$val['user_type']??'N/A';?></td>
                                                <td><?=$val['total_pending']??'N/A';?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>                                                                
                            </table>
                            <?=pagination($level_pending['count']??0)?>
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
<script type="text/javascript">
var collector_name = "";
<?php
if (isset($empDtlList)) 
{
    foreach ($empDtlList as $list) 
    {
        ?>
            collector_name += '<option value="<?=$list['id'];?>" <?=($list['status']==1)?"":"style='color:red'";?>><?=$list['emp_name']." ".$list['middle_name']." ".$list['last_name']." (".$list['user_type'].")";?></option>';
        <?php
    }
}
?>

$(document).ready(function(){    
    var dataTable = $('#empTable').DataTable({
        'responsive': true,
        'processing': true,
        "searching": false,
        "deferLoading": 0, // default ajax call prevent
        'serverSide': true,
        dom: 'Bfrtip',
        
        buttons: [
            'pageLength',
            {
                text: 'Excel Export',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {                    
                    var search_ward_mstr_id = $('#ward_mstr_id').val();  
                    var emp_id = $('#emp_id').val();   
                    var lecence_no = $('#lecence_no').val();    
                    if (search_ward_mstr_id=='' || search_ward_mstr_id== undefined) 
                    {
                        search_ward_mstr_id = "ALL";
                    }
                    if (emp_id=='' || emp_id== undefined) 
                    {
                        emp_id = "ALL";
                    }
                    if (lecence_no=='' || lecence_no== undefined) 
                    {
                        lecence_no = "@@@";
                    }
                    
                    var gerUrl = search_ward_mstr_id+'/'+emp_id+'/'+lecence_no;
                    
                    window.open('<?=base_url();?>/Trade_report/individualUserPendingAppListExcel/'+gerUrl).opener = null;
                }
            }
        ],

    });
    
});
</script>
