<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->
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
					<li><a href="#">Trade</a></li>
					<li class="active">Trade Licence Status </li>
                    </ol>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End breadcrumb-->
                </div>
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Search Licence Number</h5>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" id="myform" method="post" >
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label" for="keyword">
                                    <b>Enter Keywords</b>
                                    <i class="fa fa-info-circle" data-placement="bottom" data-toggle="tooltip" title="" data-original-title="Enter Licence No or Mobile No. or Firm Name"></i>                            
                                </label>
                            </div>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="text" id="licence" name="licence" class="form-control" />
                            </div>
                            <div class="col-md-1">
								<label class="control-label" for="ward_id"><b>Ward No.</b><span class="text-danger">*</span> </label>
							</div>
                            <div class="col-md-2">
                                <select class="form-control" id="ward_id" >
                                    <option value="All">Select</option>
                                    <?php
                                        foreach($ward_list as $ward)
                                        {
                                            ?>
                                                <option value="<?=$ward['id'];?>"><?=$ward["ward_no"];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-1">
								<label class="control-label" for="from_date"><b>From Date.</b><span class="text-danger"></span> </label>
							</div>  
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="from_date" name="from_date" class="form-control" />
                            </div>      
                            <div class="col-md-1">
								<label class="control-label" for="upto_date"><b>Upto Date.</b><span class="text-danger"></span> </label>
							</div>  
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="upto_date" name="upto_date" class="form-control" />
                            </div>                  
                        </div>
                        <div class="row">
                            <div class="form-check form-check-inline col-md-6 text-center">                                
                                <input   style="margin-left:44px;" checked class="form-check-input valid" type="radio" name="valid" id="valid1" value="valid"/>
                                <label class="form-check-label" for="inlineRadio1">Valid</label>&nbsp;
                                <input   class="form-check-input valid" type="radio" name="valid" id="valid2" value="expiry" />
                                <label class="form-check-label" for="inlineRadio2">Expire</label>
                                
                                <input   class="form-check-input valid" type="radio" name="valid" id="valid3" value="tobeExpir" />
                                <label class="form-check-label" for="inlineRadio3">To Be Expire</label>
                            </div>                                                        
                            <input  type="button" id="btn_search" class="btn btn-primary" value="SAERCH" />
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col_sm-12" id="total" style="float:right;color: #26a69a;">
                
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                <h5 class="panel-title">Result</h5>
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
                                        <th>Licence No.</th>
                                        <th>Application No.</th>
                                        <th>Firm Name</th>
                                        <th>Application Type</th>
                                        <th>Apply Date</th>
                                        <th>Valid Upto</th>
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
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script> 
<script type="text/javascript">
$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#empTable').DataTable({
        'responsive': true,
        'processing': true,
        
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
                    var valid = $('input[name="valid"]:checked').val();
                    var licence = $('#licence').val(); 
                    var ward_id = $('#ward_id').val();    
                    var from_date = $('#from_date').val(); 
                    var upto_date = $('#upto_date').val();                                     
                    
                    if (from_date=="") {
                        from_date="from_date";
                    }
                    if (upto_date=="") {
                        upto_date = "upto_date";
                    }
                    var gerUrl = valid+"/"+ward_id+"/"+from_date+"/"+upto_date;
                    if (licence!="") {
                        gerUrl = gerUrl+'/'+licence;
                    }
                    window.open("<?=base_url("/trade_da/licence_statusExcel/");?>/"+gerUrl);
                }
            }, {
                text: 'pdf',
                extend: "pdf",
                title: "Licence Status",
                download: 'open',
                footer: { text: '' },
                exportOptions: { columns: [ 0, 1,2,3,4,5,6] }
            }
            
        ],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('Trade_DA/licence_status');?>',
            "dataType": "json",
            'data': function(data){
                // Append to data
                data.valid = $('input[name="valid"]:checked').val();
                data.licence = $('#licence').val();
                data.ward_id = $('#ward_id').val(); 
                data.from_date = $('#from_date').val(); 
                data.upto_date = $('#upto_date').val(); 

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
        'drawCallback': function (settings) { 
            // Here the response
            var response = settings.json;
            var total = response.total;
            $("#total").html(total)
            console.log(response);
        },
        
        'columns': [
            { 'data': 's_no' },
            { 'data': 'ward_no' },
            { 'data': 'license_no' },
            { 'data': 'application_no' },
            { 'data': 'firm_name' },
            { 'data': 'application_type' },
            { 'data': 'apply_date' },
            { 'data': 'valid_upto' },
            { 'data': 'view' },
                        
        ]
    });
    $('#btn_search').click(function(){
        var ward_id = ($('#ward_id').val());
        if(ward_id =="All" )
        {
            $(".valid").prop('checked', false);
        }    
        dataTable.draw();
    });

     //on click radio button get data 
    $('#valid2').click(function(){
        $("#licence").val("");
        dataTable.draw();
    });
    $('#valid1').click(function(){
        $("#licence").val("");
        dataTable.draw();
    });
    $('#valid3').click(function(){
        $("#licence").val("");
        dataTable.draw();
    });
      
});
</script>
 
