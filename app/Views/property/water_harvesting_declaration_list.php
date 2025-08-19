<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">

<div id="content-container">
    <div id="page-head">
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li class="active">Water Harvesting Declaration List</li>
        </ol>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">Search Parameter</h5>
                    </div>
                    <div class="panel-body">
                        <form id="form_id" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="from_date">From Date <span class="text-danger">*</span></label>
                                    <input type="date" id="from_date" name="from_date" class="form-control" value="<?=isset($from_date)?$from_date:'';?>" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="upto_date">Upto Date <span class="text-danger">*</span></label>
                                    <input type="date" id="upto_date" name="upto_date" class="form-control" value="<?=isset($upto_date)?$upto_date:'';?>" />
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="upto_date">Search Keyword</label>
                                    <input type="text" id="search_param" name="search_param" class="form-control" value="<?=isset($search_param)?$search_param:""?>" />
                                </div>
                                <div class="form-group col-md-offset-1 col-md-2">
                                    <label for="upto_date">&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block" id="btn_submit">SEARCH</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h5 class="panel-title">List</h5>
                    </div>
                    <div class="panel-body">
                        <div class ="row">
                            <div class="col-md-12">
                                <div class="table-responsive" style="overflow:hidden;">
                                    <table id="water_harvesting" class="table table-striped table-bordered text-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Ward No.</th>
                                                <th>15 Digits Holding No./SAF No.</th>
                                                <th>Reference No.</th>
                                                <th>Owner Name</th>
                                                <th>Mobile No.</th>                                                
                                                <th>Address</th>
                                                <th>Water Harvesting Completion Date</th>
                                                <th>Apply Date</th>
                                                <th>Current Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if (isset($result)) {
                                            $count = isset($offset)?$offset:0;
                                            foreach ($result as $key => $value) {
                                        ?>
                                            <tr>
                                                <td><?=++$count;?></td>
                                                <td><?=$value["ward_no"];?></td>
                                                <td><?=$value["holding_saf_sam_no"];?></td>
                                                <td><?=$value["water_hrvting_application_no"];?></td>
                                                <td><?=$value["owner_name"];?></td>
                                                <td><?=$value["mobile_no"];?></td>
                                                <td><?=$value["prop_address"];?></td>
                                                <td><?=$value["water_harvesting_completion_date"];?></td>
                                                <td><?=date("Y-m-d", strtotime($value["created_on"]));?></td>
                                                <?php
                                                    $color_code = 'class="text-success"';
                                                    if ($value["current_status"]=="Approved") {
                                                        $color_code = 'class="text-success"';
                                                    } else if ($value["current_status"]=="Rejected") {
                                                        $color_code = 'class="text-danger"';
                                                    } else {
                                                        $color_code = 'class="text-warning"';
                                                    }
                                                ?>
                                                <td <?=$color_code;?>><?=$value["current_status"];?></td>
                                                <td><a href="<?=base_url();?>/WaterHarvesting/declaration_view/<?=$value["id"];?>" class="btn btn-primary">VIEW</a></td>
                                            </tr>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <tr>
                                                <td colspan="8">Data Not Available!!</td>
                                            </tr>
                                        <?php
                                        }

                                        ?>
                                        </tbody>
                                    </table>
                                    <?= pagination(isset($pager)?$pager:0); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('layout_vertical/footer');?>

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    //$.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#water_harvesting').DataTable({
        'responsive': true,
        'processing': true,
        "scrollX": false,
        "paging": false,
        "aaSorting": [],
        "searching": false,
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ,10 ] }, 
            { "bSearchable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ,10 ] }
        ],
        "deferLoading": 0, // default ajax call prevent
        'serverSide': true,
        dom: 'Bfrtip',
        lengthMenu: [
            [10, 25, 50, 5000],
            ['10 rows', '25 rows', '50 rows', '5000 rows']
        ],
        buttons: [
            'pageLength',
            {
                text: 'Excel Export',
                className: 'btn btn-primary',
                action: function ( e, dt, node, config ) {
                    var from_date = $('#from_date').val();
                    var upto_date = $('#upto_date').val();
                    var search_param = $('#search_param').val();
                    if (search_param=='') {
                        search_param = "ALL";
                    }
                    var gerUrl = from_date+'/'+upto_date+'/'+search_param;
                    window.open('<?=base_url();?>/WaterHarvesting/declarationReportExcel/'+gerUrl).opener = null;
                }
            }
        ],
    });
    
});
</script>