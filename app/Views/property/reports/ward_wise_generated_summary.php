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
                <h5 class="panel-title">Date & Ward wise generated notice & payment</h5>
            </div>
            <div class="panel-body">
				<form method="post" >
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-2 text-bold" for="fromDate">From Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="fromDate" name="fromDate" class="form-control" value="<?=isset($fromDate)? $fromDate:date('Y-m-d');?>" />
                            </div>
                            <label class="col-md-2 text-bold" for="uptoDate" >Upto Date</label>
                            <div class="col-md-3 has-success pad-btm">
                                <input type="date" id="uptoDate" class="form-control" name="uptoDate" value="<?=isset($uptoDate) ? $uptoDate : date('Y-m-d');?>" />
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-md-2 text-bold" for="ward_id">Ward No.</label>
                            <div class="col-md-3 has-success pad-btm">
                                <select id='ward_id' class="form-control" name="ward_id">
                                    <option value=''>ALL</option>
                                    <?php
                                    if (isset($wardList))
                                    {
                                        foreach ($wardList as $list){
                                            ?>
                                            <option value='<?=$list['id'];?>' <?=isset($ward_id) && $ward_id== $list['id'] ? "selected" : "" ;?>><?=$list['ward_no'];?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <input type="submit" id="btn_search" class="btn btn-primary" value="SEARCH" />    &nbsp;&nbsp;&nbsp;
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
                <h5 class="panel-title">Result <span id="footerResult"></span></h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="empTable" class="table table-striped table-bordered text-sm">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Ward No.</th>
                                        <th colspan="2"><a href="#" id="notice_generated">Demand Notice to be issued to defaulter</a></th>
                                        <th colspan="2">Demand Notice Issued to ULB for approval</th>
                                        <th colspan="2">Demand Notice approved by ULB</th>
                                        <th colspan="2">Demand Notice served to defaulter</th>
                                        <th colspan="2"><a href="#" id="notice_generated_payment">Defaulters against which payment has been received</a></th>
                                        <th colspan="2"><a href="#" id="notice_generated_payment_expired">Demand Notice against which no payment has been received and 30 days expired from the date of serving of demand notice</a></th>
                                    </tr>
                                    <tr>
                                        <th> No.</th>
                                        <th>Amount</th>
                                        <th> No.</th>
                                        <th>Amount</th>
                                        <th> No.</th>
                                        <th>Amount</th>
                                        <th> No.</th>
                                        <th>Amount</th>
                                        <th> No.</th>
                                        <th>Amount</th>
                                        <th> No.</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach($reports as $key=>$val){
                                            ?>
                                                <tr>
                                                    <td><?=$key+1;?></td>
                                                    <td><?=$val["ward_no"];?></td>
                                                    <td><?=$val["generated_notice_count"];?></td>
                                                    <td><?=$val["generated_notice_demand_amount"];?></td>
                                                    <td><?=$val["generated_notice_by_ulb_count"];?></td>
                                                    <td><?=$val["generated_notice_by_ulb_demand_amount"];?></td>
                                                    <td><?=$val["generated_notice_by_ulb_approved_count"];?></td>
                                                    <td><?=$val["generated_notice_by_ulb_approved_amount"];?></td>

                                                    <td><?=$val["generated_notice_count"];?></td>
                                                    <td><?=$val["generated_notice_demand_amount"];?></td>
                                                    <td><?=$val["payment_agents_notice_count"];?></td>
                                                    <td><?=$val["payment_agents_notice_payable_amt"];?></td>
                                                    <td><?=$val["expiry_not_pay_count"];?></td>
                                                    <td><?=$val["expiry_not_pay_demand_amount"];?></td>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                    <?php
                                        foreach($summary as$kay=>$val){
                                            ?>
                                                <tr>
                                                    <th><?=$val["id"];?></th>
                                                    <th><?=$val["ward_no"];?></th>
                                                    <th><?=$val["generated_notice_count"];?></th>
                                                    <th><?=$val["generated_notice_demand_amount"];?></th>
                                                    <th><?=$val["generated_notice_by_ulb_count"];?></th>
                                                    <th><?=$val["generated_notice_by_ulb_demand_amount"];?></th>
                                                    <th><?=$val["generated_notice_by_ulb_approved_count"];?></th>
                                                    <th><?=$val["generated_notice_by_ulb_approved_amount"];?></th>

                                                    <th><?=$val["generated_notice_count"];?></th>
                                                    <th><?=$val["generated_notice_demand_amount"];?></th>
                                                    <th><?=$val["payment_agents_notice_count"];?></th>
                                                    <th><?=$val["payment_agents_notice_payable_amt"];?></th>
                                                    <th><?=$val["expiry_not_pay_count"];?></th>
                                                    <th><?=$val["expiry_not_pay_demand_amount"];?></th>
                                                </tr>
                                            <?php
                                        }
                                    ?>
                                </tfoot>
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
<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/jszip.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/pdfmake.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/vfs_fonts.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/buttons.html5.min.js"></script>
<script src="<?= base_url('');?>/public/assets/datatables/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function (){
        $('#myform').validate({ // initialize the plugin
            rules: {
                ward_id: {
                    required: "#keyword:blank",
                },
                keyword: {
                    required: "#ward_id:blank",
                }
            }
        });

        $('#empTable').DataTable({
            responsive: false,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 100, -1 ],
                [ '10 rows', '25 rows', '100 rows', 'Show all' ]
            ],
            buttons: [
                'pageLength',
                {
                    text: 'excel',
                    extend: "excel",
                    title: "Report",                    
                    footer: { text: '' },
                    // exportOptions: { 
                    //     header:[0,1,2,3],
                    //     columns:[ 0, 1,2,3, 4, 5,6,7,8,9,10,11,12,13] 
                    // }
                    customizeData: function(data) {
                            var namatabel = "empTable";
                            var colLength = $("#" + namatabel + " thead:first tr:last th").length;
                            var jmlheader = $('#'+namatabel+' thead:first tr').length;
                        
                            if (jmlheader > 1) {
                                data.body.unshift(data.header);
                                data.header=[""];
                                var j=0,rspan=[];
                                for(j=0;j<jmlheader;j++){
                                        rspan[j]=[];
                                    for(var i=0;i<colLength;i++){
                                        rspan[j][i]=0;
                                    }
                                }
                                var colSpan=0,rowSpan=0;
                                var topHeader = [],thisHead=[],thiscol=0,thisrow=0,jspan=0;
                                for(j=1;j<=(jmlheader-1);j++){
                                    thisHead=[],thiscol=0;jspan=0;
                                    $('#'+namatabel).find("thead:first>tr:nth-child("+j+")>th").each(function (index, element) {
                                        colSpan = parseInt(element.getAttribute("colSpan"));
                                        rowSpan = parseInt(element.getAttribute("rowSpan"));
                                        jspan=jspan+colSpan;
                                        if(rspan[thisrow][thiscol]>0){
                                            for(var i=0;i<rspan[thisrow][thiscol];i++){
                                                thisHead.push("");    
                                            }
                                        }
                                        if(rowSpan>1){
                                            jspan=jspan-colSpan;
                                            for (var i=thisrow+1; i < jmlheader; i++) {
                                                rspan[i][jspan]=colSpan;   
                                            }
                                        }
                                        thisHead.push(element.innerHTML.toUpperCase());
                                        for (var i = 0; i < colSpan - 1; i++) {
                                            thisHead.push("");
                                        }
                                        thiscol++;
                                    });
                                    thisrow++;
                                    if(j==1){
                                        data.header=thisHead;
                                    }else{
                                        topHeader.push(thisHead);
                                    }
                                    
                                };
                                thiscol=topHeader.length;
                                for(j=(thiscol-1);j>=0;j--){
                                    data.body.unshift(topHeader[j]);
                                };    
                            }
                        }
                }, {
                    text: 'pdf',
                    extend: "pdf",
                    title: "Report",
                    download: 'open',
                    footer: { text: '' },
                    exportOptions: { columns:[ 0, 1,2,3, 4, 5,6,7,8,9,10,11,12,13] }
                
                }
            ]
        });
        $("#notice_generated").on("click",function(){
            url = '<?=base_url("prop_report/demandNoticeGeneratedList?1=1");?>';
            fromDate = '<?=isset($fromDate)? $fromDate: date("Y-m-d");?>';
            uptoDate = '<?=isset($uptoDate)? $uptoDate: date("Y-m-d");?>';
            ward_id = '<?=isset($ward_id)? $ward_id: '';?>';
            url = url+"&fromDate="+fromDate+"&uptoDate="+uptoDate+"&ward_id="+ward_id;
            myPopup(url);
        });
        $("#notice_generated_payment").on("click",function(){
            url = '<?=base_url("prop_report/demandNoticeGeneratedList?1=1");?>';
            fromDate = '<?=isset($fromDate)? $fromDate: date("Y-m-d");?>';
            uptoDate = '<?=isset($uptoDate)? $uptoDate: date("Y-m-d");?>';
            ward_id = '<?=isset($ward_id)? $ward_id: '';?>';
            url = url+"&fromDate="+fromDate+"&uptoDate="+uptoDate+"&ward_id="+ward_id+"&noticeType=payment_done";
            myPopup(url);
        });
        $("#notice_generated_payment_expired").on("click",function(){
            url = '<?=base_url("prop_report/demandNoticeGeneratedList?1=1");?>';
            fromDate = '<?=isset($fromDate)? $fromDate: date("Y-m-d");?>';
            uptoDate = '<?=isset($uptoDate)? $uptoDate: date("Y-m-d");?>';
            ward_id = '<?=isset($ward_id)? $ward_id: '';?>';
            url = url+"&fromDate="+fromDate+"&uptoDate="+uptoDate+"&ward_id="+ward_id+"&noticeType=payment_not_done";
            myPopup(url);
        });
        
    });

    function myPopup(myURL, title='xtf', myWidth='1500', myHeight='700')
    {
        var left = (screen.width - myWidth) / 2;
        var top = (screen.height - myHeight) / 4;
        var myWindow = window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
    }
</script>