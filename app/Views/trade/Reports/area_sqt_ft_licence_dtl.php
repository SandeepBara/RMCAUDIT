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
                <h5 class="panel-title">Application Details List</h5>
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
                            <label class="col-md-2 text-bold">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_mstr_id' class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($ward)) 
                                {
                                    foreach ($ward as $list) 
                                    {
                                        ?>
                                            <option value='<?=$list['id'];?>'><?=$list['ward_no'];?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                            </div>
                            
                            <!-- <label class="col-md-2 text-bold" for="department_mstr_id"><b>Entry Type</b><span class="text-danger">*</span></label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id="entry_type" name="entry_type" class="form-control" required>
                                    
                                    <option value="1" <?=isset($entry_type)  && $entry_type=='1' ? 'selected':''?>>NEW</option>
                                    <option value="2" <?=isset($entry_type)  && $entry_type=='2' ? 'selected':''?>>EXISTING</option>
                                </select>
                            </div> -->

                            
                        </div>
                        <div class="row">
                            <!-- <label class="col-md-2 text-bold">Operator</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='collector_id' class="form-control">
                                    <option value=''>ALL</option>
                                <?php
                                if (isset($oprator)) 
                                {
                                    foreach ($oprator as $list) 
                                    {
                                        ?>
                                            <option value='<?=$list['id'];?>' ><?=$list['emp_name'] ."(".$list['user_type'].")";?></option>
                                        <?php
                                    }
                                }
                                ?>
                                </select>
                            </div> -->
                            <div class="col-md-10 text-right">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading ">
                <!-- <h5 class="panel-title">List <span id="footerResult">00</span></h5> -->
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- <div class="text-center">
                            <p>Operator Wise Application Entry Report of Municipal License</p>
                            <p>From <span id='from'><?=$from_date?></span> To <span id='to'><?=$to_date?></span></p>
                        </div> -->
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Holding No.</th>
                                        <th>Holding Builtup Area <sub>(in Decimal)</sub></th>
                                        <!-- <th>House No.</th> -->
										<th>Ward No.</th>
                                        <th>License No.</th>
                                        <!-- <th>Is License</th> -->
                                        <th>Area Sq. Feet</th>
                                        <th>Area Sq. Mtr</th>
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
/*
$("#ward_mstr_id").change(function(){
    if ($("#ward_mstr_id").val()=='') 
    {
        $("#collector_id").html("<option value=''>ALL</option>"+collector_name);
    } 
    else 
    {
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
        }
        catch (err) 
        {
            alert(err.message);
        }
        $("#collector_id").html('');
    }
});
*/
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
                    var entry_type = $('#entry_type').val();
                    var search_collector_id = $('#collector_id').val();

                    if (search_ward_mstr_id=='') 
                    {
                        search_ward_mstr_id = "ALL";
                    }
                                       
                    var gerUrl = search_from_date+'/'+search_upto_date+'/'+search_ward_mstr_id ;
                    alert(gerUrl);
                    window.open('<?=base_url();?>/Trade_report/area_sqt_ft_licence_dtlExcel/'+gerUrl).opener = null;
                }
            }
        ],

        /* "columnDefs": [
            { "orderable": false, "targets": [4, 5] }
        ], */
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('Trade_report/area_sqt_ft_licence_dtl');?>',
            // 'url':'<?=base_url('Trade_report/entry_detail_reportAjax');?>',
            dataSrc: function ( data ) {
                total_collection = data.total_collection;
                recordsTotal  = data.recordsTotal;
                console.log(data);
                return data.data;
            },
            
            "deferRender": true,
            "dataType": "json",
            'data': function(data){
                // Append to data
                data.search_from_date = $('#from_date').val();
                data.search_upto_date = $('#upto_date').val();
                data.search_ward_mstr_id = $('#ward_mstr_id').val();
                // data.search_collector_id = $('#collector_id').val();
                // data.search_entry_type = $('#entry_type').val();
            },
            beforeSend: function () {
                $("#btn_search").val("LOADING ...");
				$("#loadingDiv").show();
            },
            complete: function () {
               $("#btn_search").val("SEARCH");
			   $("#loadingDiv").hide();
               $('#from').text($('#from_date').val());
               $('#to').text($('#upto_date').val());
            },
        },

        'columns': [
            { 'data': 's_no' },
            { 'data': 'holding_no' },
            { 'data': 'area_of_plot' },
            // { 'data': 'area_of_plot' },
			{ 'data': 'ward_no' },
            { 'data': 'license_no' },
            { 'data': 'area_in_sqft' },
            { 'data': 'area_in_sqmt' },
            // { 'data': 'address' },

            
        ],
        drawCallback: function( settings )
        {
            try
            {
                // $("#footerResult").html(" (Total Collection - "+total_collection+")");
                // var api = this.api();
                // $(api.column(14).footer() ).html(total_collection);
            }
            catch(err)
            {
                console.log(err.message);
            }
        }

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
