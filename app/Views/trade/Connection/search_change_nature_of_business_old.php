
<?= $this->include('layout_vertical/header');?>
<style type="text/css">
    .error {
        color: red;
    }
</style>
<!--Select2 [ OPTIONAL ]-->
<link href="<?=base_url();?>/public/assets/plugins/select2/css/select2.min.css" rel="stylesheet">  
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Initialize the tooltips and popovers [ SAMPLE ] -->

<?php $session = session(); ?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <!--Breadcrumb-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#"> Trade </a></li>
            <li><a href="#" class="active">Apply Licence </a></li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
        <form id="formname" name="form" method="post">

            <?php if(isset($validation)){ ?>
                <?= $validation->listErrors(); ?>
            <?php } ?>


            <div class="panel panel-bordered panel-dark">
                <div class="panel-heading">
                   <h3 class="panel-title">Search Renewable Licence</h3>
               </div>
               <div class="panel-body">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3 pad-btm">
                        <input type="text" name="licenceno" value="<?= isset($license_data)?$license_data['license_no']:""; ?>" class="form-control" placeholder="Enter Licence No">    
                    </div>
                    <button class="col-md-2 btn btn-primary" type="submit" name="search_license" >Search </button>



                </div>


            </div>
        </div>
    </form>

    <div class="row ">
        <div>
            <table class="table ">
                <thead class="bg-primary ">
                    <tr>
                        <td>License No</td>
                        <td>Firm Name</td>
                        <td>Application No</td>
                        <td>Valid From</td>
                        <td>valid Upto</td>
                        <td>Business code</td>
                        <td>Action</td>
                    </tr>
                </thead>
                <tbody>
                    <?php if(isset($license_data)){ ?>

                        <tr>
                            <td><?= $license_data['license_no'] ?></td>
                            <td style="overflow: hidden;"><?= $license_data['firm_name'] ?></td>
                            <td><?= $license_data['application_no'] ?></td>
                            <td><?= $license_data['valid_from'] ?></td>
                            <td><?= $license_data['valid_upto'] ?></td>
                            <td>
                              <a href="#popover" class="btn btn-md btn-info add-popover font-bold" data-original-title="Business Code : <?= $license_data['nature_of_bussiness'] ?>" data-content="<?= $license_data['trade_item'] ?>!" data-placement="top" data-trigger="focus" data-toggle="popover">View Business</a>
                          </td>
                          <td><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">Change NOB</button></td>
                      </tr>
                  <?php }else{echo '<tr><td style="color:red;" colspan="6">----------------------No Renewable license Data found---------------------------</td></tr>';} ?>
              </tbody>
          </table>

          <!-- Modal -->
          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Basic License Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <p class="text-primary text-bold text-uppercase">Firm Name :</p>  
                    </div>
                    <div class="col-md-9">
                        <p><?= $license_data['firm_name'] ?></p>
                    </div>  
                    <div class="col-md-3">
                        <p class="text-primary text-bold text-uppercase">License No :</p>  
                    </div>
                    <div class="col-md-9">
                        <p><?= $license_data['license_no'] ?></p>
                    </div>
                      
                    <div class="col-md-3">
                        <p class="text-primary text-bold text-uppercase">Application No :</p>  
                    </div>
                    <div class="col-md-9">
                        <p><?= $license_data['application_no'] ?></p>
                    </div>

                    <hr>
                    <form method="post">
                    <div class="col-md-9">
                        <h4>Update Nature of Business</h4>
                        
                            <select class="form-control py-2" name="nob">
                                <option value="">SELECT NATURE OF BUSINESS</option>
                                <?php if(isset($nature_of_business_list)){
                                    foreach($nature_of_business_list as $val){ ?>
                                        <option class="py-5" value="<?= $val['id'] ?>" <?= ($val['trade_code'] == $license_data['nature_of_bussiness'])?"selected":""; ?>><span>( <?= $val['trade_code'] ?> ) </span><?= $val['trade_item']; ?></option>
                                    <?php  }} ?>
                                </select>

                                <input type="hidden" name="apply_id" value="<?= $license_data['id'] ?>">
                                <input type="hidden" name="license_no" value="<?= $license_data['license_no'] ?>">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" onclick="return submitAlert()" name="update_nob" class="btn btn-primary">Save changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div><!--End page content-->
</div><!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<!--Select2 [ OPTIONAL ]-->
<script src="<?=base_url();?>/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="<?=base_url();?>/public/assets/js/jquery.validate.js"></script>


<script>
    $(document).ready(function(){
        function modelInfo(msg){
            $.niftyNoty({
                type: 'info',
                icon : 'pli-exclamation icon-2x',
                message : msg,
                container : 'floating',
                timer : 5000
            });
        }
        <?php if($result = flashToast('nob_success')) { ?>
            modelInfo('<?=$result;?>');
        <?php }?>

        // $('.demo-select2-multiple-selects').select2();
        $("#formname").validate({
            rules:{
                firmtype_id:{
                    required:true
                },
                ownership_type_id:{
                    required:true
                }

            },
            messages:{
                firmtype_id:{
                    required:"Please select Firm Type"
                },
                ownership_type_id:{
                    required:"Please select Ownership Type"
                }

            }
        });
    });



    function isAlpha(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
            return false;

        return true;
    }

    function isNum(e){
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    }

    function isNumDot(e){
        var charCode = (e.which) ? e.which : e.keyCode;
        if(charCode==46){
            var txt = e.target.value;
            if ((txt.indexOf(".") > -1) || txt.length==0) {
                return false;
            }
        }else{
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        }
    }



    function isAlphaNum(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isAlphaNumCommaSlash(e){
        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && (keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }
    function isAlphaNumCommaSlashAmperstandApos(e){

        var keyCode = (e.which) ? e.which : e.keyCode
        if (e.which != 44 && e.which != 47 && e.which != 92 && e.which != 38 && e.which != 39 && (keyCode < 65 || keyCode > 90 || keyCode != 38 || e.which != 39) && (keyCode < 97 || keyCode > 123) && keyCode != 32 && (e.which < 48 || e.which > 57))
            return false;
    }

    function isDateFormatYYYMMDD(value){
        var regex = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))+$/;
        if (!regex.test(value) ) {
            return false;
        }else{
            return true;
        }
    }

    function submitAlert(){
        var conf  = confirm('Are you sure ?\nChanging the nature of business will directly affect the tax !');
        if(conf){
            return true;
        }else{
            return false; 
        }

        


        
    }

    
    

</script>



