<?= $this->include('layout_vertical/header');?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<!--CONTENT CONTAINER-->
<!--===================================================-->
<div id="content-container">
    <div id="page-head">
        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <!-- <h1 class="page-header text-overflow">Add/Update Company Location</h1>//-->
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->
        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="#"><i class="demo-pli-home"></i></a></li>
            <li><a href="#">System</a></li>
            <li class="active">Employee Details</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->
    </div>
    <!--Page content-->
    <!--===================================================-->

 
    <div id="page-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="panel-title">Add/Update Employee Details</h3>
                            </div>
                            <div class="col-sm-6 text-lg-right" style="padding-right: 30px; padding-top:10px;">
                                 <a href="<?php echo base_url('EmpDetails/empList');?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i>Back</a>
                            </div>
                    </div>
                    <!--Horizontal Form-->
                    <!--===================================================-->
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="<?=base_url('EmpDetails/add_update/');?><?=isset($id)?'/'.md5($id):"";?>" enctype="multipart/form-data">
                            <input type="hidden" id="id" name="id" value="<?=(isset($id))?$id:'';?>"/>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label class="control-label" for="First_name">First Name <span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="50" placeholder="Enter First Name" id="emp_name" name="emp_name" class="form-control" value="<?=(isset($emp_name))?$emp_name:"";?>"onkeypress="return isAlpha(event);">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label" for="middle_name">Middle Name</label>
                                        <input type="text" maxlength="50" placeholder="Enter Last Name" id="middle_name" name="middle_name" class="form-control" value="<?=(isset($middle_name))?$middle_name:"";?>" onkeypress="return isAlpha(event);">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label" for="last_name">Last Name<span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="50" placeholder="Enter Last Name" id="last_name" name="last_name" class="form-control" value="<?=(isset($last_name))?$last_name:"";?>" onkeypress="return isAlpha(event);">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label class="control-label" for="guardian_name">Guardian Name<span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="50" placeholder="Enter Guardian Name" id="guardian_name" name="guardian_name" class="form-control" value="<?=(isset($guardian_name))?$guardian_name:"";?>" onkeypress="return isAlpha(event);">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label" for="personal_phone_no">Phone No<span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="10" placeholder="Enter Phone Number" id="personal_phone_no" name="personal_phone_no" class="form-control" value="<?=(isset($personal_phone_no))?$personal_phone_no:"";?>" onkeypress="return isNum(event);">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                     <div class="col-sm-3">
                                        <label class="control-label" for="email_id">Email Id<span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="50" placeholder="Enter Email Id" id="email_id" name="email_id" class="form-control" value="<?=(isset($email_id))?$email_id:"";?>" >
                                    </div>
                                  <div class="col-sm-3">
                                        <label class="control-label" for="user_type_mstr_id">User Type<span class="text-danger"> *</span></label>
                                        <select id="user_type_mstr_id" name="user_type_mstr_id" class="form-control">
                                            <option value="">--select--</option>
                                            <?php
                                            if($user_type_list){
                                                foreach ($user_type_list as $value){
                                                    if($_SESSION['emp_details']['user_type_mstr_id']==2){
                                                        if($value['id']==2){
                                                            continue;
                                                        }
                                                    }
                                                    
                                            ?>
                                            <option value="<?=$value['id'];?>" <?=(isset($user_type_mstr_id))?($user_type_mstr_id==$value['id'])?"selected='selected'":"":"";?>><?=$value['user_type']?>
                                                </option>
                                            <?php
                                                }
                                            }?>
                                        </select>
                                    </div>
                             </div>
                         </div>
                          <div class="row">
                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label class="control-label" for="report_to">Report To </label>
                                    <select id="report_to" name="report_to" class="form-control">
                                        <option value="">--select--</option>
                                    </select>
                                </div>
                            </div>
                         </div>
                          <div class="row select_ulb">
                             <div class="col-sm-12">
                                 <label><b><u>Select Ulb </u></b></label>
                             </div>
                         </div>
                         <div class="row ulb_data" id="ulb">
                             
                         </div>
                         <div class="row select_data">
                             <div class="col-sm-12">
                                 <label><b><u>Select Ward </u></b></label>
                                  <br><b>Select ALL</b>
                                 <input type="checkbox" name="select_all" id="select_all" onclick="select_all_ward()">
                             </div>
                         </div>
                         <div class="row ward_data" id="ward">
                         </div>
                            <div class="row">
                             <div class="col-sm-12">
                                <label class="col-sm-2">&nbsp;</label>
                             </div>
                         </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-2">Upload Image </label>
                                <div class="col-sm-3">
                                    <span>Preferred Image : Maximum size of 1MB</span>
                                    <input type="file" id="photo_path" name="photo_path" class="form-control m-t-xxs" />
                                    <input type="hidden" id="is_image" name="is_image" value="<?=(isset($photo_path))?($photo_path!="")?"is_image":"":"";?>" />
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2">&nbsp;</label>
                                <div class="col-sm-3">
                                    <div class="div_photo_path_preview" style="width:100%; text-align:center;">
                                        <img id="photo_path_preview" src="<?=(isset($photo_path))?($photo_path!="")?base_url()."/getImageLink.php?path=emp_image"."/".$photo_path:base_url()."/public/assets/img/avatar/default_avatar.png":base_url()."/public/assets/img/avatar/default_avatar.png";?>" alt="" style="height:230px; width:220px;"/>
                                    </div>
                                </div>
                            </div>
                             <div class="form-group">
                                <label class="col-sm-2" >&nbsp;</label>
                                <div class="col-sm-3">
                                    <input type="button" id="image_path_remove" class="btn btn-dark btn-rounded btn-block" value="Remove Photo">
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <div class="col-md-6 text-lg-left" style="padding-right: 20px; padding-top:10px;">
                                    <button class="btn btn-primary" id="btn_empDetails" name="btn_empDetails" type="submit"><?=(isset($id))?"Edit EmpDetails":"Add Emp Details";?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                <div class="row">
                <div class="col-md-12" style="color: red; text-align: center;">
                    <?php
                    if(isset($error))
                    {
                        foreach ($error as $value)
                        {
                            echo $value;
                            echo "<br />";
                        }
                    }
                    ?>
                </div>
                    <!--===================================================-->
                    <!--End Horizontal Form-->
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
<script type="text/javascript">
    $(document).ready( function () {
        $('.select_data,.ward_data,.ulb_data,.select_ulb').hide();
            $("#photo_path").change(function() {
            var input = this;
            var ext = $(this).val().split('.').pop().toLowerCase();
            if($.inArray(ext, ['png','jpg','jpeg']) == -1) {
                $("#photo_path").val("");
                $('#photo_path_preview').attr('src', "<?=base_url();?>/public/assets/img/avatar/default_avatar.png");
                alert('invalid image type');
            }if (input.files[0].size > 1048576) { // 1MD = 1048576
                $("#photo_path").val("");
                $('#photo_path_preview').attr('src', "<?=base_url();?>/public/assets/img/avatar/default_avatar.png");
                alert("Try to upload file less than 1MB!"); 
            }else{
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#photo_path_preview').attr('src', e.target.result);
                      $("#is_image").val("is_image");

                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
        //Update Data Ajax Call
         var user_type_mstr_id = $("#user_type_mstr_id").val();
        if(user_type_mstr_id==4 || user_type_mstr_id==5 || user_type_mstr_id=='11')
        {
            var id = $('#id').val();
            $.ajax({
                    url: "<?=base_url('EmpDetails/ajax_wardList');?>",
                    method:"POST",
                    data:{'id':id},
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        if(data.response==true){
                            $('#ward').html(data.data);
                            $('.select_data').show();
                            $('.ward_data').show();
                        }
                    },
                   /* error: function(jqXHR, textStatus, errorThrown) {
                        alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }*/
                });
        }
        if(user_type_mstr_id!="")
        {
          if(user_type_mstr_id!=4 && user_type_mstr_id!=5 && user_type_mstr_id!='11')
          {
            var id = $('#id').val();
            $.ajax({
                    url: "<?=base_url('EmpDetails/ajax_ulbList');?>",
                    method:"POST",
                    data:{'id':id},
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        if(data.response==true){
                            $('#ulb').html(data.data);
                            $('.ulb_data').show();
                            $('.select_ulb').show();
                        }
                    },
                });
           }  
        }
         if(user_type_mstr_id!=""){
            var id = $('#id').val();
            $.ajax({
                    url: "<?=base_url('EmpDetails/ajax_repotingList');?>",
                    method:"POST",
                    data:{'user_type_mstr_id':user_type_mstr_id,'id':id},
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        if(data.response==true){
                            $('#report_to').html(data.data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
            });
        }
        $("#photo_path").click(function(){
            $("#photo_path").val("");
            $('#photo_path_preview').attr('src', "<?=base_url();?>/public/assets/img/avatar/default_avatar.png");
            $("#is_image").val("");
        });
        $("#image_path_remove").click(function(){
            $("#photo_path").val("");
            $('#photo_path_preview').attr('src', "<?=base_url();?>/public/assets/img/avatar/default_avatar.png");
            $("#is_image").val("");
        });
        $("#btn_empDetails").click(function() {
            var exp = /^[A-Za-z0-9\s]+$/;
            var process = true;
            var emp_name = $("#emp_name").val().trim();
            if(emp_name=="")
            {
                $("#emp_name").css({"border-color":"red"});
                $("#emp_name").focus();
                process = false;
            }
            var last_name = $("#last_name").val().trim();
            if(last_name=="")
            {
                $("#last_name").css({"border-color":"red"});
                $("#last_name").focus();
                process = false;
            }
            var guardian_name = $("#guardian_name").val().trim();
            if(guardian_name=="")
            {
                $("#guardian_name").css({"border-color":"red"});
                $("#guardian_name").focus();
                process = false;
            }
            var personal_phone_no = $("#personal_phone_no").val();
            if(personal_phone_no==0)
            {
                $("#personal_phone_no").css({"border-color":"red"});
                $("#personal_phone_no").focus();
                process = false;
            }
            var contact_exp = /^\d{10}$/;
            if(!contact_exp.test(personal_phone_no))
            {
                alert("Invalid Mobile Number");
                $("#personal_phone_no").css({"border-color":"red"});
                $("#personal_phone_no").focus();
                process = false;
            }
            var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            var email = $("#email_id").val();
            var emailval = $.trim(email).match(pattern);
            if(!emailval)
            {
                alert(" Invalid Email Id");
                $("#email_id").css({"border-color":"red"});
                $("#email_id").focus();
                process = false;
            }
            var user_type_mstr_id = $("#user_type_mstr_id").val();
            if(user_type_mstr_id=="")
            {
                $("#user_type_mstr_id").css({"border-color":"red"});
                $("#user_type_mstr_id").focus();
                process = false;
            }
            else
            {
                if(user_type_mstr_id ==4 || user_type_mstr_id==5 || user_type_mstr_id=='11')
                {
                    var checkedLen = $("input[name='ward_mstr_id[]']:checked").length;
                    if(!checkedLen)
                    {
                        alert("You must check at least one Ward!!!");
                        process = false;
                    }
                }
                if(user_type_mstr_id !=4 && user_type_mstr_id!=5 && user_type_mstr_id!='11')
                {
                    var checkedLen = $("input[name='ulb_mstr_id[]']:checked").length;
                    if(!checkedLen)
                    {
                        alert("You must check at least one Ulb!!!");
                        process = false;
                    }
                }
            }
            var ulb_mstr_id = $("#ulb_mstr_id").val();
            if(ulb_mstr_id=="")
            {
                $("#ulb_mstr_id").css({"border-color":"red"});
                $("#ulb_mstr_id").focus();
                process = false;
            }
            return process;
        });
        $("#emp_name").keyup(function(){$(this).css('border-color','');});
        $("#last_name").keyup(function(){$(this).css('border-color','');});
        $("#guardian_name").keyup(function(){$(this).css('border-color','');});
        $("#personal_phone_no").keyup(function(){$(this).css('border-color','');});
        $("#email_id").keyup(function(){$(this).css('border-color','');});
        $("#user_type_mstr_id").change(function(){$(this).css('border-color','');});
        $("#ulb_mstr_id").change(function(){$(this).css('border-color','');});
    });
    $("#user_type_mstr_id").change(function(){
        var user_type_mstr_id = $("#user_type_mstr_id").val();
        if(user_type_mstr_id==4 || user_type_mstr_id==5 || user_type_mstr_id=='11')
        {
            $.ajax({
                    url: "<?=base_url('EmpDetails/ajax_wardList');?>",
                    method:"POST",
                    data:{'user_type_mstr_id':user_type_mstr_id},
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        if(data.response==true){
                            $('#ward').html(data.data);
                            $('.select_data').show();
                            $('.ward_data').show();
                            $('.ulb_data').hide();   
                            $('.select_ulb').hide();
                            $('#ulb').val('');
                        }
                    },
                    /*error: function(jqXHR, textStatus, errorThrown) {
                        alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }*/
                });
        }
        else
        {
            $('.select_data').hide();
            $('.ward_data').hide();
            $('#ward').val('');
        }
        if(user_type_mstr_id!=""){
            $.ajax({
                    url: "<?=base_url('EmpDetails/ajax_repotingList');?>",
                    method:"POST",
                    data:{'user_type_mstr_id':user_type_mstr_id},
                    dataType:"json",
                    success:function(data){
                        if(data.response==true){
                            $('#report_to').html(data.data);
                        }
                    },
                    /*error: function(jqXHR, textStatus, errorThrown) {
                        alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }*/
            });
        }
        //user_type_id= parseInt(user_type_mstr_id);
        //alert(user_type_mstr_id);
        if(user_type_mstr_id!="")
        {
          if(user_type_mstr_id!=4 && user_type_mstr_id!=5 && user_type_mstr_id!='11')
          {
            $.ajax({
                    url: "<?=base_url('EmpDetails/ajax_ulbList');?>",
                    method:"POST",
                    data:{'user_type_mstr_id':user_type_mstr_id},
                    dataType:"json",
                    success:function(data){
                        console.log(data);
                        if(data.response==true){
                            $('#ulb').html(data.data);
                            $('.ulb_data').show();
                            $('.select_ulb').show();
                            $('.select_data').hide();
                            $('.ward_data').hide();
                            $('#ward').val('');
                        }
                    },
                });
           }  
        }
        else
        {
          $('.ulb_data').hide();   
          $('.select_ulb').hide();  
          $('#ulb').val('');
        }
    });
</script>


<script type="text/javascript">
    
    function select_all_ward(str)
    {

        var checked=$("#select_all").is(":checked");
        // alert(checked);
        if(checked==true)
        {
              //alert('ssssss');
              $("input[name='ward_mstr_id[]']").attr('checked',true);

        }
        else
        {
             $("input[name='ward_mstr_id[]']").attr('checked',false);

        }
    }

</script>