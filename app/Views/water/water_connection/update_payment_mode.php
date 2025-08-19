<?= $this->include('layout_vertical/header');?>
<style type="text/css">
    .error{
        color: red;
    }
</style>
<link href="<?=base_url('');?>/public/assets/plugins/css-loaders/css/css-loaders.css" rel="stylesheet">

<!--DataTables [ OPTIONAL ]-->
<link href="<?=base_url('');?>/public/assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="<?=base_url('');?>/public/assets/datatables/css/responsive.bootstrap.min.css" rel="stylesheet">
    
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
<div id="page-head">
<!--Page Title-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div id="page-title">
<!-- <h1 class="page-header text-overflow">Department List</h1>//-->
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End page title-->

<!--Breadcrumb-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<ol class="breadcrumb">
<li><a href="#"><i class="demo-pli-home"></i></a></li>
<li><a href="#">Water</a></li>
<li class="active">Search Consumer</li>
</ol>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--End breadcrumb-->
</div>
<!--Page content-->
<!--===================================================-->
        <div id="page-content">
            <?php
                if(isset($_SESSION['message'])):
            ?>
            <div class="bg bg-success text-center" style="font-size: 17px;"><?php echo $_SESSION['message']; ?></div>
            <?php
                unset($_SESSION['message']);
                endif;
            ?>

            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title">Search Applicants</h5>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" id="myform" method="post" >
                        <div class="form-group">
                            
                            <div class="col-md-5">
                                 <label class="control-label" for="ward No"><b>Transaction No.</b><span class="text-danger">*</span> </label>
                                 <input type="text" name="transaction_no" id="transaction_no" class="form-control" value="<?php echo $transaction_no; ?>" style="text-transform:uppercase" placeholder="Enter Transaction No.">
                            </div>
                            <div class="col-md-2">
                                <label class="control-label" for="department_mstr_id">&nbsp;</label>
                                <button class="btn btn-primary btn-block" id="btn_search" name="btn_search" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            if(!empty($transaction_details))
            {

            ?>
           
            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title">Basic Details</h5>
                </div>
                <div class="panel-body" style="font-weight: bold; font-size: 15px; color: green;">
                     <div class="row">
                        <label class="col-md-3 bolder">Consumer / Application No. :</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo isset($consumer_details['consumer_no'])?$consumer_details['consumer_no']:$consumer_details['application_no']; ?>
                        </div>
                        <label class="col-md-2 bolder">Ward No. :</label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $consumer_details['ward_no']; ?> 
                        </div>
                    </div>
                   
                </div>
              </div>

                <div class="panel panel-bordered panel-dark">
                    <div class="panel-heading">
                        <h3 class="panel-title"> Owner Details</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-responsive">
                            <thead class="bg-trans-dark text-dark">
                                <tr>
                                    <th class="bolder">Owner Name</th>
                                    <th class="bolder">Guardian Name</th>
                                    <th class="bolder">Mobile No.</th>
                                    <th class="bolder">Email ID</th>
                                    <th class="bolder">State</th>
                                    <th class="bolder">District</th>
                                    <th class="bolder">City</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($owner_details)
                                {
                                    foreach($owner_details as $val)
                                {
                                ?>
                                <tr>
                                    <td><?php echo $val['applicant_name'];?></td>
                                    <td><?php echo $val['father_name'];?></td>
                                    <td><?php echo $val['mobile_no'];?></td>
                                    <td><?php echo $val['email_id'];?></td>
                                    <td><?php echo $val['state'];?></td>
                                    <td><?php echo $val['district'];?></td>
                                    <td><?php echo $val['city'];?></td>
                                </tr>
                                <?php
                                }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                    <h5 class="panel-title">Transaction Details</h5>
                </div>
                <div class="panel-body">
                     <div class="row">
                        <label class="col-md-2 bolder">Transaction No. </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $transaction_details['transaction_no']; ?>
                        </div>
                        <label class="col-md-2 bolder">Transaction Amount </label>
                        <div class="col-md-3 pad-btm">
                            <?php echo $transaction_details['paid_amount']; ?> 
                        </div>
                    </div>

                    <form method="post" enctype="multipart/form-data" id="payment_dtls">

                     <div class="row">
                        <label class="col-md-2 bolder">Payment Mode</label>
                        <div class="col-md-3 pad-btm">
                            
                            <select name="payment_mode" id="payment_mode" class='form-control' onchange="show_hide_cheque_details(this.value)">
                                <option value="">Select</option>
                                <option value="CASH" <?php if($transaction_details['payment_mode']=='CASH'){ echo "selected"; } ?>>CASH</option>
                                <option value="CHEQUE" <?php if($transaction_details['payment_mode']=='CHEQUE'){ echo "selected"; } ?>>CHEQUE</option>
                                <option value="DD" <?php if($transaction_details['payment_mode']=='DD'){ echo "selected"; } ?>>DD</option>
                                
                            </select>
                        </div>
                    </div>

                   
                    <div id="chq_dtls" style="display: none;">
                     <div class="row">
                        <label class="col-md-2 bolder">Cheque No.</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="cheque_no" id="cheque_no" class="form-control" value="<?php echo $cheque_details['cheque_no']; ?>">
                        </div>
                        <label class="col-md-2 bolder">Cheque Date</label>
                        <div class="col-md-3 pad-btm">
                            <input type="date" name="cheque_date" id="cheque_date" class="form-control" value="<?php echo $cheque_details['cheque_date']; ?>">
                        </div>
                       
                    </div>
                     <div class="row">
                        <label class="col-md-2 bolder">Bank Name</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="bank_name" id="bank_name" class="form-control" value="<?php echo $cheque_details['bank_name']; ?>">
                            
                        </div>
                        <label class="col-md-2 bolder">Branch Name</label>
                        <div class="col-md-3 pad-btm">
                            <input type="text" name="branch_name" id="branch_name" class="form-control" value="<?php echo $cheque_details['branch_name']; ?>">
                        </div>
                       
                    </div>
                    </div>

                     <div class="row">
                        <label class="col-md-2 bolder">Upload File</label>
                        <div class="col-md-3 pad-btm">
                            <input type="file" name="file" id="file" class="form-control" >
                            
                        </div>
                       
                       
                    </div>

                 

                    <div class="row">
                        <input type="hidden" name="transaction_no" id="transaction_no" value="<?php echo $transaction_details['transaction_no']; ?>">
                        <input type="hidden" name="transaction_id" id="transaction_id" value="<?php echo $transaction_details['id']; ?>">
                        <input type="hidden" name="cheque_dtl_id" id="cheque_dtl_id" value="<?php echo $cheque_details['id']; ?>">
                        

                        <div class="col-md-12 text-center"><input type="submit" name="update" id="update" value="Update" class="btn btn-success" ></div>
                    </div>
                </form>

                </div>
              </div>



            <?php
            }
            ?>  
        <!--===================================================-->
        <!--End page content-->
        </div>
        <!--===================================================-->
        <!--END CONTENT CONTAINER-->


<?= $this->include('layout_vertical/footer');?>
<!--DataTables [ OPTIONAL ]-->

<script src="<?=base_url();?>/public/assets/js/jquery.validate.min.js"></script>


<script>
    
    $(document).ready(function () 
    {

     <?php
        if(!empty($cheque_details))
        {
     ?>
           show_hide_cheque_details($("#payment_mode").val());
    <?php
        }
     ?>
    $('#payment_dtls').validate({ // initialize the plugin
       

        rules: {
            payment_mode: {
                required: true,
               
            },
            cheque_no: {
                required: true,
                
            },
            cheque_date: {
                required: true,
                
            },
            bank_name: {
                required: true,
                
            },
            branch_name: {
                required: true,
                
            },
            file: {
                required: true,
                
            }
            
        }


    });

     $('#myform').validate({ // initialize the plugin
       

        rules: {
            ward_id: {
                required: true,
               
            },
            keyword: {
                required: true,
                
            }
        }


    });


});
</script>



<script type="text/javascript">

      function show_hide_cheque_details(argument)
      {
          var payment_mode=argument;
          //alert(payment_mode);

          if(payment_mode!='CASH' && argument!="")
          {
              
              $("#chq_dtls").show();
            
          }
          else

          {
            
              $("#chq_dtls").hide();
             
          }

      }

/*$(document).ready(function(){
    $.fn.dataTable.ext.errMode = 'throw';
    var dataTable = $('#demo_dt_basic').DataTable({
        'responsive': true,
        'processing': true,
        'language': {
            'processing': '<div class="load8"><div class="loader"></div></div>...',
        },
        'serverSide': true,
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],
        "columnDefs": [
            { "orderable": false, "targets": [0, 4] }
        ],
        dom: 'Bfrtip',
        buttons: [
            'pageLength',
            {
                extend: "excel",
                footer: { text: '' },
                exportOptions: { columns: [0,1,2,3,4,5,6,7] }
            }],
        'ajax': {
            "type": "POST",
            'url':'<?=base_url('WaterSearchConsumer/getPagination');?>',
            "dataType": "text",
            'data': function(data){
                // Read values
                // alert(data);
                var ward_id = $('#ward_id').val();
                var keyword = $('#keyword').val();
               // alert(keyword);
                
                // Append to data
                data.search_by_from_ward_id = ward_id;
                data.search_by_upto_ward_id = keyword;
            }
        },

         'columns': [
            { 'data': 's_no' },
            { 'data': 'id' },
            { 'data': 'ward_no' },
            { 'data': 'ulb_mstr_id' },
            { 'data': 'status' },
        ]
     
    });
    $('#btn_search').click(function(){
        dataTable.draw();
    });
});*/

</script>
