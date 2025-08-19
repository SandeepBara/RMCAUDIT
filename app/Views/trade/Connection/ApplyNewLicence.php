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
                <h5 class="panel-title">Filter With</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-2 text-bold">From Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="from_date" class="form-control" value="<?=date('Y-m-d');?>" />
                            </div>
                            <label class="col-md-2 text-bold">To Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="upto_date" class="form-control" value="<?=date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 text-right">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="panel panel-dark">
            <div class="panel-heading">
                <?php if(isset($application_type["id"]) && isset($user_type["user_type_mstr_id"]) && in_array($user_type["user_type_mstr_id"],['8','11','1','2','4'])){ ?>
                <div class="panel-control">
                	<?php if($application_type["id"]==1){?>
					<a href="<?=base_url('');?>/tradeapplylicence/tobaccoapplynewlicence/<?=md5(1);?>" class="btn btn-default">Tobacco Apply</a>
                    <a href="<?=base_url('');?>/tradeapplylicence/applynewlicence/<?=md5(1);?>" class="btn btn-default">Apply</a>
				<?php }elseif($application_type["id"]==2){?>
					<a href="<?=base_url('');?>/tradeapplylicence/searchLicense/<?=md5(2);?>" class="btn btn-default">Apply</a>
				<?php }elseif($application_type["id"]==3){?>
					<a href="<?=base_url('');?>/tradeapplylicence/searchLicense/<?=md5(3);?>" class="btn btn-default">Apply</a>
				<?php }elseif($application_type["id"]==4){?>
					<a href="<?=base_url('');?>/tradeapplylicence/searchLicense/<?=md5(4);?>" class="btn btn-default">Apply</a>
				<?php }?>
				</div>
                <?php } ?>
				<h5 class="panel-title">
                    <?php 
                    if(isset($application_type["id"]))
                    {
                        if($application_type["id"]==1)
                        {
                            echo 'NEW LICENSE';
                        }elseif($application_type["id"]==2)
                        {
                            echo 'RENEWAL';
                        }elseif($application_type["id"]==3)
                        {
                            echo 'AMENDMENT';
                        }elseif($application_type["id"]==4)
                        {
                            echo 'SURRENDER';
                        }
                    }
                    ?>                    
                </h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>                                        
                                        <th>Ward No.</th>
                                        <th>Application No.</th>
                                        <?php if($application_type["id"]>1){?>
                                        <th>License No.</th><?php }?>
                                        <th>Firm Name</th>
                                        <th>Application Type</th>
                                        <th>Apply Date</th>
                                        <th>Apply By</th>
                                        <th>View</th>     
                                    </tr>
                                </thead>
                                <tbody>

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
<script type="text/javascript">
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#empTable').DataTable({
        'responsive': true,
        'processing': true,
        'language': {
            'processing': '<div class="load8"><div class="loader"></div></div>...',
        },
        'serverSide': true,
        dom: 'Bfrtip',
        lengthMenu: [
            [10, 25, 50, 5000],
            ['10 rows', '25 rows', '50 rows', '5000 rows']
        ],
        buttons: [
            'pageLength',
            
        ],

        /* "columnDefs": [
            { "orderable": false, "targets": [4, 5] }
        ], */
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('tradeapplylicence/newapplicationlistAjax');?>',
            "dataType": "json",
            'data': function(data){
                // console.log(data);return;
                data.search_from_date = $('#from_date').val();
                data.search_upto_date = $('#upto_date').val();
                data.application_type_id = <?=$application_type["id"]?>;

                console.log(data.application_type_id);
               
            }
        },
        /*'drawCallback': function (settings) { 
            // Here the response
            var response = settings.json;
            console.log(response);
        },*/
        order: [[0, 'DESC']],
        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'application_no' },
            <?php if($application_type["id"]>1){?>{ 'data': 'license_no' },<?php }?>
            { 'data': 'firm_name' },
            { 'data': 'application_type' },
            { 'data': 'apply_date' },
            { 'data': 'emp_name'},
            { 'data': 'view' },
                        
        ]
    });
    $('#btn_search').click(function(){
        from_date_val = $('#from_date').val()
        to_date_val = $('#upto_date').val()
        if(from_date_val==''){
            alert('please select from date !!!')
            return
        }
        if(to_date_val==''){
            alert('please select to date !!!')
            return
        }

        if(new Date(from_date_val) > new Date(to_date_val))
        {
            alert('from data cannot be greater than to data')
            return
        }
        dataTable.draw();
    });
});
</script>
