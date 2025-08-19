<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <div id="page-title"><!--Page Title-->
            <h1 class="page-header text-overflow"></h1>
        </div><!--End page title-->
        <ol class="breadcrumb"><!--Breadcrumb-->
    		<li><a href="#"><i class="demo-pli-home"></i></a></li>
    		<li><a href="#">System</a></li>
    		<li class="active">User Hierarchy Add/Edit</li>
        </ol><!--End breadcrumb-->
    </div>
	
	<div id="page-content">
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel">
		            <div class="panel-heading">
                        <div class="panel-control">
                            <a href="<?=base_url();?>/UserHeirarchy/userHeirarchyList" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                        <h5 class="panel-title">User Hierarchy Add/Edit</h5>
		            </div>
					<form method="POST" action="<?=base_url('UserHeirarchy/add_update');?>">
                        <div class="panel-body">
                            <input type="hidden" name="id" value="<?=(isset($id))?$id:'';?>">
                            <!-- <input type="" name="id" value="<?=(isset($id))?$id:'';?>"> -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">User Type<span class="text-danger"> *</span></label>
                                        <select id="user_type_mstr_id" name="user_type_mstr_id" class="form-control">
                                        <option value="">--Select--</option>
                                        <?php
                                        if(isset($userTypeList)){
                                            foreach ($userTypeList as $value){
                                        ?>
                                        <option value="<?=$value['id']?>" <?=(isset($user_type_mstr_id))?($value['id']==$user_type_mstr_id)?'selected':'':'';?> ><?=$value['user_type']?>
                                            </option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label"><b><u>Select Under User Type</u></b></label>
                                </div>
                            </div>
                            <div class="row" id="abcd">
                                <!-- <div class="col-sm-3">
                                    <div class="checkbox">
                                        <input type="checkbox" id="under_user_type_mstr_id" name="under_user_type_mstr_id[]" class="magic-checkbox" value="" />
                                        <label for="under_user_type_mstr_id">2222</label>
                                    </div>
                                </div> -->
                            </div>
                        <div class="panel-footer text-left">
                            <button type="submit" id="btn_submit" name="btn_submit" class="btn btn-primary"><?=(isset($id))?($id=='')?'Add User Hierarchy':'Update User Hierarchy':'Add User Heirarchy';?></button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
    <!--Page content-->
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
$(document).ready(function(){  
    var user_type_mstr_id = $("#user_type_mstr_id").val();
    if(user_type_mstr_id!="")
    {
        $.ajax({
            url: "<?=base_url('UserHeirarchy/ajax_data');?>",
            method:"POST",
            data:{'user_type_mstr_id':user_type_mstr_id},
            dataType:"json",
             /*beforeSend: function() {
                alert(data);
                },*/
            success:function(data){
                console.log(data);
                if(data.response==true){
                    $('#abcd').html(data.data);
                }
            }
        });
    }
    $("#btn_submit").click(function(){
        var process = true;
        if($("#user_type_mstr_id").val()==""){
                $("#user_type_mstr_id").css('border-color', 'red');
                process=false;
        }
        var checkedLen = $("input[name='under_user_type_mstr_id[]']:checked").length;
        if(!checkedLen) {
            alert("You must check at least one User Type!!!");
            process = false;
        }
        return process;
    });
    $("#user_type_mstr_id").change(function(){ $(this).css('border-color',''); });
    function modelDanger(msg){
        $.niftyNoty({
            type: 'danger',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
});
$("#user_type_mstr_id").change(function(){
    var user_type_mstr_id = $("#user_type_mstr_id").val();
   /* alert(user_type_mstr_id);*/
    if(user_type_mstr_id==""){
        alert("Please Select User Type");
               $('#abcd').hide();
    }else{
         $.ajax({
            url: "<?=base_url('UserHeirarchy/ajax_data');?>",
            method:"POST",
            data:{'user_type_mstr_id':user_type_mstr_id},
            dataType:"json",
             /*beforeSend: function() {
                alert(data);
                },*/
            success:function(data){
                console.log(data);
                if(data.response==true){
                    $('#abcd').html(data.data);
                    $('#abcd').show();
                }
            },
            /*error: function(jqXHR, textStatus, errorThrown) {
                alert(JSON.stringify(jqXHR));
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            }*/
        });
    }
});
</script>
