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
                <h5 class="panel-title">MPL Assessed HH Report</h5>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive" style="overflow: hidden;">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name of ULB</th>
                                        <th title="Total Saf Digitized upto <?php echo $data['assessed_hh_upto']; ?>" style="cursor:pointer;">Assessed HH <br/>(<?php echo $data['assessed_hh_upto']; ?>) </th>
										<th>SUDA Target <br/> As on <?php echo $data['suda_target_date']; ?> </th>
                                        <th>SAF Done<br/>(<?php echo $data['saf_done_upto'];?>)</th>
                                        <th>Balance<br /> Target</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>(1)</td>
                                        <td>(2)</td>
                                        <td>(3)</td>
                                        <td>4=(2-3)</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td><?=$data['ulb_name']; ?></td>
                                        <td><?=$data['assessed_hh']; ?></td>
                                        <td><?=$data['suda_target']; ?></td>
                                        <td><?=$data['saf_done']; ?></td>
                                        <td><?=$data['balance']; ?></td>
                                    </tr>
                                    <tr style="font-weight:bold;">
                                        <td colspan="2" align="right">Total :-</td>
                                        <td><?=$data['assessed_hh']; ?></td>
                                        <td><?=$data['suda_target']; ?></td>
                                        <td><?=$data['saf_done']; ?></td>
                                        <td><?=$data['balance']; ?></td>
                                    </tr>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<script type="text/javascript">
var collector_name = "";
<?php
if (isset($empDtlList)) {
    foreach ($empDtlList as $list) {
?>
    collector_name += '<option value="<?=$list['id'];?>" <?=($list['status']==1)?"":"style='color:red'";?>><?=$list['emp_name']." ".$list['middle_name']." ".$list['last_name']." (".$list['user_type'].")";?></option>';
<?php
    }
}
?>
$("#ward_mstr_id").change(function(){
    if ($("#ward_mstr_id").val()=='') {
        $("#collector_id").html("<option value=''>ALL</option>"+collector_name);
    } else {
        try{
            $.ajax({
                type:"POST",
                url: "<?=base_url('prop_report/getEmpListByWardPermissionAndUlb');?>",
                dataType: "json",
                data: {
                    "ward_mstr_id":$("#ward_mstr_id").val(),
                },
                beforeSend: function() {
                    $("#loadingDiv").show();
                },
                success:function(data){
                    if(data.response==true){
                        $("#collector_id").html(data.data)
                    }
                    $("#loadingDiv").hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#loadingDiv").hide();
                    alert(JSON.stringify(jqXHR));
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                }
            });
        }catch (err) {
            alert(err.message);
        }
        $("#collector_id").html('');
    }
});
</script>

<script type="text/javascript">
$(document).ready(function(){
    //$.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#empTable').DataTable({
        'responsive': true,
        'processing': true,
        
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
                    var search_from_date = $('#from_date').val();
                    var search_upto_date = $('#upto_date').val();
                    var search_ward_mstr_id = $('#ward_mstr_id').val();
                    if (search_ward_mstr_id=='') {
                        search_ward_mstr_id = "ALL";
                    }
                    var search_collector_id = $('#collector_id').val();
                    if (search_collector_id=='') {
                        search_collector_id = "ALL";
                    }
                    var search_tran_mode_mstr_id = $('#tran_mode_mstr_id').val();
                    if (search_tran_mode_mstr_id=='') {
                        search_tran_mode_mstr_id = "ALL";
                    }
                    var gerUrl = search_from_date+'/'+search_upto_date+'/'+search_ward_mstr_id+'/'+search_collector_id+'/'+search_tran_mode_mstr_id;
                    window.open('<?=base_url();?>/prop_report/collectionReportExcel/'+gerUrl).opener = null;
                }
            }
        ],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('prop_report/collectionReportAjax');?>',
            dataSrc: function ( data ) {
                total_collection = data.total_collection;
                recordsTotal  = data.recordsTotal;
                return data.data;
            },
            "deferRender": true,
            "dataType": "json",
            'data': function(data){
                console.log($('#from_date').val());
                // Append to data
                data.search_from_date = $('#from_date').val();
                data.search_upto_date = $('#upto_date').val();
                data.search_ward_mstr_id = $('#ward_mstr_id').val();
                data.search_collector_id = $('#collector_id').val();
                data.search_tran_mode_mstr_id = $('#tran_mode_mstr_id').val();
                
            },
            beforeSend: function () {
                $("#btn_search").val("LOADING ...");
				 $("#loadingDiv").show();
            },
            complete: function () {
               $("#btn_search").val("SEARCH");
			    $("#loadingDiv").hide();
            },
        },
        
        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'holding_no' },
			{ 'data': 'new_holding_no' },
            { 'data': 'owner_name' },
            { 'data': 'mobile_no' },
            { 'data': 'from_upto_fy_qtr' },
            { 'data': 'tran_date' },
            { 'data': 'transaction_mode' },
            { 'data': 'payable_amt' },
            { 'data': 'emp_name' },
            { 'data': 'tran_no' },
            { 'data': 'cheque_no' },
            { 'data': 'bank_name' },
            { 'data': 'branch_name' },
        ],
        drawCallback: function( settings )
        {
            try
            {
                $("#footerResult").html(" (Total Holding - "+recordsTotal+", Total Collection - "+total_collection+")");
                var api = this.api();
                $(api.column(2).footer() ).html(recordsTotal);
                $(api.column(8).footer() ).html(total_collection);
            }
            catch(err)
            {
                console.log(err.message);
            }
        }
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});

function openPaymentModeWiseSummery()
{
    var search_from_date = $('#from_date').val();
    var search_upto_date = $('#upto_date').val();
    var search_ward_mstr_id = $('#ward_mstr_id').val();
    if(search_ward_mstr_id!="")
    {
        search_ward_mstr_id = '/'+search_ward_mstr_id
    }
    var gerUrl = search_from_date+'/'+search_upto_date+search_ward_mstr_id;
   window.open('<?=base_url();?>/prop_report/paymentModeWiseSummery/'+gerUrl);
}
</script>
