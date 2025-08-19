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
            <li class="active">ULB Employee Details</li>
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
                                <h3 class="panel-title">Add/Update ULB Employee Details</h3>
                            </div>
                            <div class="col-sm-6 text-lg-right" style="padding-right: 30px; padding-top:10px;">
                                 <a href="<?php echo base_url('UlbEmpDetails/ulbEmpList');?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i>Back</a>
                            </div>
                    </div>
                    <!--Horizontal Form-->
                    <!--===================================================-->
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="<?=base_url('UlbEmpDetails/ulbAddUpdate/');?><?=isset($id)?'/'.md5($id):"";?>">
                            <input type="hidden" id="id" name="id" value="<?=(isset($id))?$id:'';?>"/>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label class="control-label" for="emp_name">Name <span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="50" placeholder="Enter Name" id="emp_name" name="emp_name" class="form-control" value="<?=(isset($emp_name))?$emp_name:"";?>"onkeypress="return isAlpha(event);">
                                    </div>
                                    <div class="col-sm-3">
                                        <label class="control-label" for="designation">Designation<span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="50" placeholder="Enter designation Name" id="designation" name="designation" class="form-control" value="<?=(isset($designation))?$designation:"";?>" onkeypress="return isAlpha(event);">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <label class="control-label" for="contact_no">Contact Number<span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="10" placeholder="Enter Contact Number" id="personal_phone_no" name="personal_phone_no" class="form-control" value="<?=(isset($personal_phone_no))?$personal_phone_no:"";?>" onkeypress="return isNum(event);">
                                    </div>
                                     <div class="col-sm-3">
                                        <label class="control-label" for="email_id">Email Id<span class="text-danger"> *</span></label>
                                        <input type="text" maxlength="50" placeholder="Enter Email Id" id="email_id" name="email_id" class="form-control" value="<?=(isset($email_id))?$email_id:"";?>" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                  <div class="col-sm-3">
                                        <label class="control-label" for="user_type_mstr_id">User Type<span class="text-danger"> *</span></label>
                                        <select id="user_type_mstr_id" name="user_type_mstr_id" class="form-control">
                                            <option value="">--select--</option>
                                            <?php
                                            if($user_type_list){
                                                foreach ($user_type_list as $value){
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
                                <div class="col-md-6 text-lg-left" style="padding-right: 20px; padding-top:10px;">
                                    <button class="btn btn-primary" id="btn_empDetails" name="btn_empDetails" type="submit"><?=(isset($id))?"Edit Ulb EmpDetails":"Add Ulb Emp Details";?></button>
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
        $('.select_data,.ward_data').hide();
        //Update Data Ajax Call
         var user_type_mstr_id = $("#user_type_mstr_id").val();
        if(user_type_mstr_id!=""){
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
            var designation = $("#designation").val().trim();
            if(designation=="")
            {
                $("#designation").css({"border-color":"red"});
                $("#designation").focus();
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
                alert("Invalid Contact Number");
                $("#personal_phone_no").css({"border-color":"red"});
                $("#personal_phone_no").focus();
                process = false;
            }
            var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            var email = $("#email_id").val();
            var emailval = $.trim(email).match(pattern);
            if(!emailval ){
                alert(" Invalid Email Id");
                $("#email_id").css({"border-color":"red"});
                $("#email_id").focus();
                process = false;
            }
            var user_type_mstr_id = $("#user_type_mstr_id").val();
            if(user_type_mstr_id==""){
                $("#user_type_mstr_id").css({"border-color":"red"});
                $("#user_type_mstr_id").focus();
                process = false;
            }
            else{
                /*if(user_type_mstr_id ==4 || user_type_mstr_id==5)
                {*/
                    var checkedLen = $("input[name='ward_mstr_id[]']:checked").length;
                    if(!checkedLen)
                    {
                        alert("You must check at least one Ward!!!");
                        process = false;
                    }
               // }
            }
            return process;
        });
        $("#emp_name").keyup(function(){$(this).css('border-color','');});
        $("#designation").keyup(function(){$(this).css('border-color','');});
        $("#contact_no").keyup(function(){$(this).css('border-color','');});
        $("#email_id").keyup(function(){$(this).css('border-color','');});
        $("#user_type_mstr_id").change(function(){$(this).css('border-color','');});
    });
    $("#user_type_mstr_id").change(function(){
        var user_type_mstr_id = $("#user_type_mstr_id").val();
        if(user_type_mstr_id!=""){
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
                        }
                    },
                    /*error: function(jqXHR, textStatus, errorThrown) {
                        alert(JSON.stringify(jqXHR));
                        console.log(JSON.stringify(jqXHR));
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }*/
                });
        }
        else {
            $('.select_data').hide();
            $('.ward_data').hide();
            $('#ward').val('');
        }
    });
</script>

<script type="text/javascript">
    
    function select_all_ward(str){
        var checked=$("#select_all").is(":checked");
        // alert(checked);
        if(checked==true){
              //alert('ssssss');
            $("input[name='ward_mstr_id[]']").attr('checked',true);
        }
        else{
            $("input[name='ward_mstr_id[]']").attr('checked',false);
        }
    }

</script>