<?= $this->include('layout_vertical/header');?>
<!--CSS Loaders [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">
<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?= base_url(); ?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">
<!--CONTENT CONTAINER-->


<div id="content-container">
    <!--Page content-->
    <div id="page-content">
        <div class="panel panel-mint">
            <div class="panel-heading">
                <h5 class="panel-title">Bulk Notice</h5>
            </div>
            <div class="panel-body">
				<form id="myForm" method="get" >
                    <div class="col-md-12">
                        <div class="row">
                            
                            <label class="col-md-1 text-bold" for="ward_id">Ward No</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select name="ward_id" id="ward_id" class="form-control">
                                    <option value="">All</option>
                                    <?php
                                        foreach($wardList as $val){
                                            ?>
                                                <option value="<?=$val["id"];?>"><?=$val["ward_no"];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            

                            <label class="col-md-1 text-bold" for="property_type_id">Property Type</label>
                            <div class="col-md-2 has-success pad-btm">
                                <select name="property_type_id" id="property_type_id" class="form-control">
                                    <option value="">All</option>
                                    <?php
                                        foreach($propertyTypeList as $val){
                                            ?>
                                                <option value="<?=$val["id"];?>"><?=$val["property_type"];?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            
                        </div>
                        <div class = "row">
                            
                            <label class="col-md-1 text-bold" >Report Type</label>
                            <div class="col-md-11 pad-btm radio">
                                <input type="radio" id="notice_to_be_generate" class="magic-radio" name="report_type" value="notice_to_be_generate" checked /> <label for="notice_to_be_generate">Notice To Be Generate</label>
                                <input type="radio" id="defaulter_list" class="magic-radio" name="report_type" value="defaulter_list" /> <label for="defaulter_list">Defaulter List</label>
                                <input type="radio" id="notice_be_generated" class="magic-radio" name="report_type" value="notice_be_generated" /> <label for="notice_be_generated">Notice Generated</label>
                                <input type="radio" id="notice_be_closed" class="magic-radio" name="report_type" value="notice_be_closed" /> <label for="notice_be_closed">Collection From Notice</label>
                                <input type="radio" id="notice_summary" class="magic-radio" name="report_type" value="notice_summary" /> <label for="notice_summary">Notice Summary</label>
                            </div>
                        </div>
                        <div class="row">
                            <span id="notice_serial" style="display: none;">
                                <label class="col-md-1 text-bold" for="serialNo" >Notice Serial</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <select name="serialNo" id="serialNo" class="form-control">
                                        <option value="">All</option>
                                        <option value="1">1'st</option>
                                        <option value="2">2'nd</option>
                                        <option value="3">3'rd</option>
                                        <option value="4">4'th</option>
                                        <option value="5">5'th</option>
                                    </select>
                                </div>

                                <label class="col-md-1 text-bold" for="closeStatus" >Notice Status</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <select name="closeStatus" id="closeStatus" class="form-control">
                                        <option value="">ALL</option>
                                        <option value="open">OPEN</option>
                                        <option value="close">CLOSE</option>
                                    </select>
                                </div>
                                <label class="col-md-1 text-bold" for="servedStatus" >Served Status</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <select name="servedStatus" id="servedStatus" class="form-control">
                                        <option value="">ALL</option>
                                        <option value="served">Served</option>
                                        <option value="not_served">Not Served</option>
                                    </select>
                                </div>
                            </span>

                            <span id="date_div" style="display: none;">
                                <label class="col-md-1 text-bold" for="fromDate" >From Date</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <input type="date" value="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>" name="fromDate" id="fromDate" class="form-control"/>
                                </div>

                                <label class="col-md-1 text-bold" for="uptoDate" >Upto Date</label>
                                <div class="col-md-2 has-success pad-btm">
                                    <input type="date"  value="<?=date('Y-m-d')?>" max="<?=date('Y-m-d')?>"  name="uptoDate" id="uptoDate" class="form-control" />
                                        
                                </div>
                            </span>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <input type="button" id="btn_search" class="btn btn-primary" value="SEARCH" />&nbsp;&nbsp;&nbsp;
                            <span id="print_btn">
                            </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-dark">
            <div class="panel-heading">
                
                <h5 class="panel-title">Result</h5>
            </div>
            <div class="panel-body">
	                <div class="row">
	                    <div class="col-sm-12">
							<div class="table-responsive" id="individual_view">
								<table id="tbl_by_individual" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
											<th id="selectAll">#</th>
                                            <th>Ward No</th> 
                                            <th>Holding No</th>
											<th>Property Type</th>
											<th>Address</th>
                                            <th>Owner Name</th>
                                            <th>Mobile No.</th>	
                                            <th>From</th>
                                            <th>Upto</th>
                                            <th>Demand Amount</th>
                                            <th>View</th>										
										</tr>
									</thead>
									<tbody>
										
									</tbody> 
								</table>
							</div>

                            <div class="table-responsive" id="defaulter_view" style="display: none;">
								<table id="tbl_by_defaulter" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
                                            <th>#</th>
                                            <th>Ward No</th> 
                                            <th>Holding No</th>
											<th>Property Type</th>
											<th>Address</th>
                                            <th>Owner Name</th>
                                            <th>Mobile No.</th>	
                                            <th>From</th>
                                            <th>Upto</th>
                                            <th>Demand Amount</th>
                                            <th>View</th>										
										</tr>
									</thead>
									<tbody>
										
									</tbody>  
								</table>
							</div>

                            <div class="table-responsive" id="generated_view" style="display: none;">
								<table id="tbl_by_generated" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
                                            <th id="selectAll2">#</th>
                                            <th>Ward No</th>
                                            <th>Notice No</th> 
                                            <th>Holding No</th>
											<th>Property Type</th>
											<th>Address</th>
                                            <th>Owner Name</th>
                                            <th>Mobile No.</th>	
                                            <th>From</th>
                                            <th>Upto</th>
                                            <th>Demand Amount</th>
                                            <th>Notice Date</th>
                                            <th>Notice Serial</th>
                                            <th>Notice Status</th>
                                            <th>Is Notice Serveds</th>
                                            <th>View</th>									
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>

                            <div class="table-responsive" id="close_view" style="display: none;">
								<table id="tbl_by_close" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
                                            <th>#</th>
                                            <th>Ward No</th> 
                                            <th>Notice No</th>  
											<th>Holding No</th>
											<th>Property Type</th>
											<th>Address</th>
                                            <th>Owner Name</th>
                                            <th>Mobile No.</th>	
                                            <th>From</th>
                                            <th>Upto</th>
                                            <th>Demand Amount</th>
                                            <th>Notice Date</th>
                                            <th>Paid Amount</th>
                                            <th>Tran Date</th>
                                            <th>Tran No.</th>
                                            <th>Payment Mode</th>
                                            <th>View</th>									
										</tr>
									</thead>
									<tbody>
										
									</tbody>  
								</table>
							</div>

                            <div class="table-responsive" id="summary_view" style="display: none;">
								<table id="tbl_by_summary" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead class="bg-trans-dark text-dark">
										<tr>
                                            <th>#</th>
                                            <th>Total Notice</th> 
                                            <th>Total Demand</th>  
											<th>Collection From Notice</th>
											<th>Collection Amount</th>								
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
		<!--===================================================-->
		<!--End page content-->
		</div>
		<!--===================================================-->
		<!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>

<!--DataTables [ OPTIONAL ]-->
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/public/assets/plugins/select2/js/select2.min.js"></script>


<script type="text/javascript">
   //debugger;
   let selectAll=false;
    $(document).ready(function(){        

        var individual_view_tbl = $('#tbl_by_individual').DataTable({
            // 'responsive': true,
            'processing': true,
            "ordering": false,
            
            "deferLoading": 0, // default ajax call prevent
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [20,  50, 100,-1],
                ['20 rows', '50 rows', '100 rows', 'ALL rows']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var gerUrl = 'true?';
                        var formData = $("#myForm").serializeArray();
                        $.each(formData, function(i, field) {
                            gerUrl += (field.name+'='+field.value)+"&";
                        });
                        window.open('<?=base_url();?>/propDtl/generateBulkNotice/'+gerUrl).opener = null;
                    }
                },
                {
                    text: 'Generate Notice',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var formData = $("#myForm").serializeArray();
                        console.log(formData);
                        var data = [];
                        $.each(formData, function(i, field) {
                            console.log(field.name,i,field.name.match(/[]/));
                            if(field.name.match(/\[\]/)){
                                if(field.name in data){
                                    data[field.name].push(field.value);
                                }else{
                                    data[field.name] = [field.value];
                                }
                            }
                            else{
                                data[field.name] = field.value;
                            }
                        });
                        var selecteditems = [];
                        $('input[name="check[]"]').each(function() { 
                            if ($(this).is(':checked')) {
                                selecteditems.push($(this).val());
                            }
                        });
                        console.log(selecteditems);
                        $.ajax({
                            type:'post',
                            url:'<?= base_url('propDtl/generateBulkNotice') ?>',
                            data:{
                                selecteditems:selecteditems,
                                gen_notice:'gen_notice',
                                "notice_type":"Demand",
                                "notice_date":'<?= date('Y-m-d'); ?>',
                                "ajaxNotice":true,
                            },
                            beforeSend:function(){
                                $("#loadingDiv").show();
                            },
                            success:function(result){
                                $("#loadingDiv").hide();
                                modelInfo('Bulk Notice Successfully Generated !');
                                individual_view_tbl.draw();
                            }
                        })
                    }
                }
            ],

            'ajax': {
                "type": "POST",
                'url':'<?=base_url('propDtl/generateBulkNotice');?>',            
                                
                "deferRender": true,
                "dataType": "json",
                'dataSrc': function ( data ) {
                    summary = data.summary;
                    return data.data;
                },
                'data': function(data){
                    var formData = $("#myForm").serializeArray();
                    console.log(formData);
                    $.each(formData, function(i, field) {
                        console.log(field.name,i,field.name.match(/[]/));
                        if(field.name.match(/\[\]/)){
                            if(field.name in data){
                                data[field.name].push(field.value);
                            }else{
                                data[field.name] = [field.value];
                            }
                        }
                        else{
                            data[field.name] = field.value;
                        }
                    });
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
                { 'data': 'property_type' },
                { 'data': 'prop_address' },
                { 'data': 'owner_name'},
                { 'data': 'mobile_no' },
                { 'data': 'from' },
                { 'data': 'upto' },
                { 'data': 'balance' },
                { 'data': 'link' },
                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    var api = this.api();
                    $(api.column(3).footer() ).html(summary.total_saf);
                    $(api.column(5).footer() ).html(summary.outstanding_demand);
                    $(api.column(6).footer() ).html(summary.current_demand);
                    $(api.column(7).footer() ).html(summary.total_demand);
                    $(api.column(8).footer() ).html(summary.arrear_collection);
                    $(api.column(9).footer() ).html(summary.current_collection);
                    $(api.column(10).footer() ).html(summary.total_collection);
                    $(api.column(11).footer() ).html(summary.arrear_balance);
                    $(api.column(12).footer() ).html(summary.current_balance);
                    $(api.column(13).footer() ).html(summary.total_balance);
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        var defaulter_view_tbl = $('#tbl_by_defaulter').DataTable({
            'processing': true,
            "ordering": false,
            
            "deferLoading": 0, // default ajax call prevent
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 20, 50, -1],
                ['10 rows', '20 rows','50 rows',  'ALL rows']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var gerUrl = 'true?';
                        var formData = $("#myForm").serializeArray();
                        $.each(formData, function(i, field) {
                            gerUrl += (field.name+'='+field.value)+"&";
                        });
                        window.open('<?=base_url();?>/propDtl/generateBulkNotice/'+gerUrl).opener = null;
                    }
                },
            ],

            'ajax': {
                "type": "POST",
                'url':'<?=base_url('propDtl/generateBulkNotice');?>',            
                                
                "deferRender": true,
                "dataType": "json",
                'dataSrc': function ( data ) {
                    summary = data.summary;
                    return data.data;
                },
                'data': function(data){
                    var formData = $("#myForm").serializeArray();
                    console.log(formData);
                    $.each(formData, function(i, field) {
                        console.log(field.name,i,field.name.match(/[]/));
                        if(field.name.match(/\[\]/)){
                            if(field.name in data){
                                data[field.name].push(field.value);
                            }else{
                                data[field.name] = [field.value];
                            }
                        }
                        else{
                            data[field.name] = field.value;
                        }
                    });
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
                { 'data': 'property_type' },
                { 'data': 'prop_address' },
                { 'data': 'owner_name'},
                { 'data': 'mobile_no' },
                { 'data': 'from' },
                { 'data': 'upto' },
                { 'data': 'balance' },
                { 'data': 'link' },
                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    var api = this.api();
                    $(api.column(3).footer() ).html(summary.total_saf);
                    $(api.column(5).footer() ).html(summary.outstanding_demand);
                    $(api.column(6).footer() ).html(summary.current_demand);
                    $(api.column(7).footer() ).html(summary.total_demand);
                    $(api.column(8).footer() ).html(summary.arrear_collection);
                    $(api.column(9).footer() ).html(summary.current_collection);
                    $(api.column(10).footer() ).html(summary.total_collection);
                    $(api.column(11).footer() ).html(summary.arrear_balance);
                    $(api.column(12).footer() ).html(summary.current_balance);
                    $(api.column(13).footer() ).html(summary.total_balance);
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        var generated_view_tbl = $('#tbl_by_generated').DataTable({
            'processing': true,
            "ordering": false,
            
            "deferLoading": 0, // default ajax call prevent
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 20, 50, -1],
                ['10 rows', '20 rows','50 rows',  'ALL rows']
            ],
            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var gerUrl = 'true?';
                        var formData = $("#myForm").serializeArray();
                        $.each(formData, function(i, field) {
                            gerUrl += (field.name+'='+field.value)+"&";
                        });
                        window.open('<?=base_url();?>/propDtl/generateBulkNotice/'+gerUrl).opener = null;
                    }
                },
                // {
                //     text: 'Generate Notice',
                //     className: 'btn btn-primary',
                //     action: function ( e, dt, node, config ) {
                //         var formData = $("#myForm").serializeArray();
                //         console.log(formData);
                //         var data = [];
                //         $.each(formData, function(i, field) {
                //             console.log(field.name,i,field.name.match(/[]/));
                //             if(field.name.match(/\[\]/)){
                //                 if(field.name in data){
                //                     data[field.name].push(field.value);
                //                 }else{
                //                     data[field.name] = [field.value];
                //                 }
                //             }
                //             else{
                //                 data[field.name] = field.value;
                //             }
                //         });
                //         var selecteditems = [];
                //         $('input[name="noticeCheck[]"]').each(function() { 
                //             if ($(this).is(':checked')) {
                //                 selecteditems.push($(this).val());
                //             }
                //         });
                //         console.log(selecteditems);
                //         $.ajax({
                //             type:'post',
                //             url:'<?= base_url('propDtl/generateBulkNotice') ?>',
                //             data:{
                //                 selecteditems:selecteditems,
                //                 gen_notice:'gen_notice',
                //                 "notice_type":"Demand",
                //                 "notice_date":'<?= date('Y-m-d'); ?>',
                //                 "nextNotice":true,
                //                 "ajaxCall":true,
                //             },
                //             beforeSend:function(){
                //                 $("#loadingDiv").show();
                //             },
                //             success:function(result){
                //                 $("#loadingDiv").hide();
                //                 modelInfo('Bulk Notice Successfully Generated !');
                //                 generated_view_tbl.draw();
                //             }
                //         });
                //     }
                // },
            ],

            'ajax': {
                "type": "POST",
                'url':'<?=base_url('propDtl/generateBulkNotice');?>',            
                                
                "deferRender": true,
                "dataType": "json",
                'dataSrc': function ( data ) {
                    summary = data.summary;
                    return data.data;
                },
                'data': function(data){
                    var formData = $("#myForm").serializeArray();
                    console.log(formData);
                    $.each(formData, function(i, field) {
                        console.log(field.name,i,field.name.match(/[]/));
                        if(field.name.match(/\[\]/)){
                            if(field.name in data){
                                data[field.name].push(field.value);
                            }else{
                                data[field.name] = [field.value];
                            }
                        }
                        else{
                            data[field.name] = field.value;
                        }
                    });
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
                { 'data': 'notice_no' },
                { 'data': 'holding_no' },
                { 'data': 'property_type' },
                { 'data': 'prop_address' },
                { 'data': 'owner_name'},
                { 'data': 'mobile_no' },
                { 'data': 'from' },
                { 'data': 'upto' },
                { 'data': 'balance' },
                { 'data': 'notice_date' },
                { 'data': 'notice_serial'},
                { 'data': 'is_close'},
                { 'data': 'is_served'},
                { 'data': 'link' },
                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        var close_view_tbl = $('#tbl_by_close').DataTable({
            'responsive': false,
            'processing': true,
            
            "deferLoading": 0, // default ajax call prevent
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [10, 20, 50, -1],
                ['10 rows', '20 rows','50 rows',  'ALL rows']
            ],

            buttons: [
                'pageLength',
                {
                    text: 'Excel Export',
                    className: 'btn btn-primary',
                    action: function ( e, dt, node, config ) {
                        var gerUrl = 'true?';
                        var formData = $("#myForm").serializeArray();
                        $.each(formData, function(i, field) {
                            gerUrl += (field.name+'='+field.value)+"&";
                        });
                        window.open('<?=base_url();?>/propDtl/generateBulkNotice/'+gerUrl).opener = null;
                    }
                }
            ],


            'ajax': {
                "type": "POST",
                'url':'<?=base_url('propDtl/generateBulkNotice');?>',            
                                
                "deferRender": true,
                "dataType": "json",
                'dataSrc': function ( data ) {
                    summary = data.summary;
                    return data.data;
                },
                'data': function(data){
                    var formData = $("#myForm").serializeArray();
                    console.log(formData);
                    $.each(formData, function(i, field) {
                        console.log(field.name,i,field.name.match(/[]/));
                        if(field.name.match(/\[\]/)){
                            if(field.name in data){
                                data[field.name].push(field.value);
                            }else{
                                data[field.name] = [field.value];
                            }
                        }
                        else{
                            data[field.name] = field.value;
                        }
                    });
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
                { 'data': 'notice_no' },
                { 'data': 'holding_no' },
                { 'data':'property_type'},
                { 'data': 'prop_address' },
                { 'data': 'owner_name' },
                { 'data': 'mobile_no' },
                { 'data': 'from' },
                { 'data': 'upto' },
                { 'data': 'balance' },
                { 'data': 'notice_date' },
                { 'data': 'paid_amount' },
                { 'data': 'tran_date' },
                { 'data': 'tran_no' },
                { 'data': 'payment_mode' },
                { 'data': 'link' },
                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    var api = this.api();
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        var summary_view_tbl = $('#tbl_by_summary').DataTable({
            'responsive': false,
            'processing': true,
            
            "deferLoading": 0, // default ajax call prevent
            'serverSide': true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ -1],
                ['ALL rows']
            ],

            buttons: [
                'pageLength',  // Button for adjusting page length
                {
                    extend: 'excelHtml5',  // Built-in Excel export button
                    text: 'Export to Excel',  // Text for the button
                    className: 'btn btn-success',  // Add some styling (optional)
                    title: 'Data Export',  // Title of the Excel file
                    exportOptions: {
                        columns: ':visible'  // Export only visible columns
                    }
                }
            ],


            'ajax': {
                "type": "POST",
                'url':'<?=base_url('propDtl/generateBulkNotice');?>',            
                                
                "deferRender": true,
                "dataType": "json",
                
                'data': function(data){
                    var formData = $("#myForm").serializeArray();
                    console.log(formData);
                    $.each(formData, function(i, field) {
                        console.log(field.name,i,field.name.match(/[]/));
                        if(field.name.match(/\[\]/)){
                            if(field.name in data){
                                data[field.name].push(field.value);
                            }else{
                                data[field.name] = [field.value];
                            }
                        }
                        else{
                            data[field.name] = field.value;
                        }
                    });
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
                { 'data': 'total_property' },
                { 'data': 'total_demand' },
                { 'data': 'collection_from_prop' },
                { 'data':'total_collection_demand'},
                
            ],
            drawCallback: function( settings )
            {
                try
                {
                    // var api = this.api();
                }
                catch(err)
                {
                    console.log(err.message);
                }
            }

        });

        let tbl = individual_view_tbl;

        $('input[type="radio"][name="report_type"]').change(function() {
            var selectedValue = $('input[type="radio"][name="report_type"]:checked').val();
            $("#individual_view").hide();
            $("#defaulter_view").hide();
            $("#generated_view").hide();
            $("#close_view").hide();
            $("#summary_view").hide();
            $("#notice_serial").hide();
            $("#date_div").hide();
            
            if(selectedValue=="notice_be_closed"){
                
                $("#close_view").show();
                tbl = close_view_tbl;
            }else if(selectedValue=="notice_be_generated"){
                
                $("#generated_view").show();
                $("#notice_serial").show();
                tbl = generated_view_tbl;
            }else if(selectedValue=="defaulter_list"){
                $("#defaulter_view").show();
                tbl = defaulter_view_tbl
            }else if(selectedValue=="notice_summary"){                
                $("#summary_view").show();
                $("#date_div").show();
                tbl = summary_view_tbl
            }
            else{
                $("#individual_view").show();
                tbl = individual_view_tbl;
            }
        });


        $('#btn_search').click(function()
        {
            tbl.draw();
            selectAll =  false;
        });

        $("#selectAll").click(function(){
            if(selectAll)
            {
                $('input[name="check[]"]').prop("checked",false);             
                selectAll = false;
            }
            else
            {
                $('input[name="check[]"]').prop("checked",true);
                selectAll = true;
            }
        });

        $("#selectAll2").click(function(){
            if(selectAll)
            {
                $('input[name="noticeCheck[]"]').prop("checked",false);                
                selectAll = false;
            }
            else
            {
                $('input[name="noticeCheck[]"]').prop("checked",true);
                selectAll = true;
            }
        });

    });
    
</script>