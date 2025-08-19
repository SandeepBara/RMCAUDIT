<?= $this->include('layout_vertical/header');?>
<script src="<?=base_url();?>/public/assets/otherJs/validation.js"></script> 
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <div id="page-title"><!--Page Title-->
            <h1 class="page-header text-overflow"></h1>
        </div><!--End page title-->
        <ol class="breadcrumb"><!--Breadcrumb-->
    		<li><a href="#"><i class="demo-pli-home"></i></a></li>
    		<li><a href="#">Menu</a></li>
    		<li class="active">Menu Add/Edist</li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel">
		            <div class="panel-heading">
                        <div class="panel-control">
                            <a href="<?=base_url();?>/MenuPermission2/menuList" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                        <h5 class="panel-title">Menu Add/Edit</h5>
		            </div>
                    <form method="POST" action="<?=base_url('MenuPermission2/menu_add_update/');?>">
                        <div class="panel-body">
                            <input type="hidden" name="id" value="<?=(isset($menuDtl['id']))?$menuDtl['id']:'';?>">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Menu Name</label>
                                        <input type="text" maxlength="100" id="menu_name" name="menu_name" class="form-control" placeholder="Enter Menu Name" value="<?=(isset($menuDtl['menu_name']))?$menuDtl['menu_name']:'';?>">
                                    </div>
                                </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Order No</label>
                                        <input type="text" maxlength="2" id="order_no" name="order_no" class="form-control" placeholder="Enter Order No" value="<?=(isset($menuDtl['order_no']))?$menuDtl['order_no']:'';?>" onkeypress="return isNum(event);">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Under Menu Name</label>
                                        <select id="parent_menu_mstr_id" name="parent_menu_mstr_id" class="form-control">
                                            <option value="0">#</option>
                                            <option value="-1" <?=(isset($menuDtl['parent_menu_mstr_id']))?("-1"==$menuDtl['parent_menu_mstr_id'])?'selected':'':'';?> >DIRECT ACTIVE MENU</option>
                                        <?php
                                        if(isset($underMenuNameList)){
                                            foreach ($underMenuNameList as $values){
                                        ?>
                                            <option value="<?=$values['id']?>" <?=(isset($menuDtl['parent_menu_mstr_id']))?($values['id']==$menuDtl['parent_menu_mstr_id'])?'selected':'':'';?> ><?=$values['menu_name']?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Under Sub-Menu Name</label>
                                        <select id="parent_sub_menu_mstr_id" name="parent_sub_menu_mstr_id" class="form-control">
                                            <option value="0" <?=(isset($menuDtl['parent_sub_menu_mstr_id']))?("-1"==$menuDtl['parent_sub_menu_mstr_id'])?'selected':'':'';?>>#</option>
                                            <?php
                                            if(isset($parentSubMenuList)){
                                                foreach ($parentSubMenuList as $values){
                                            ?>
                                                <option value="<?=$values['id']?>" <?=(isset($menuDtl['parent_sub_menu_mstr_id']))?($values['id']==$menuDtl['parent_sub_menu_mstr_id'])?'selected':'':'';?> ><?=$values['menu_name']?></option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Menu Path</label>
                                        <input type="text" id="menu_path" name="url_path" class="form-control" placeholder="Enter Menu Path" value="<?=(isset($menuDtl['url_path']))?$menuDtl['url_path']:'';?>"/>
                                    </div>
                                </div>
                                <div class="col-sm-6" id="menu_icon_hide_show" style="display: none;">
                                    <div class="form-group">
                                        <label class="control-label">Menu Icon <span class="text-danger">(like : fa fa-home)</span></label>
                                        <input type="text" id="menu_icon" name="menu_icon" class="form-control" placeholder="Enter Menu Icon" value="<?=(isset($menuDtl['menu_icon']))?$menuDtl['menu_icon']:'';?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label"><b><u>Permission To</u></b></label>
                                </div>
                                <?php
                                if(isset($user_type_list)){
                                    foreach ($user_type_list as $values) {
                                ?>
                                <div class="col-sm-3">
                                    <div class="checkbox">
                                        <input type="checkbox" id="user_type_mstr_id<?=$values['id']?>" name="user_type_mstr_id[]" class="magic-checkbox" value="<?=$values['id']?>" <?=(isset($values['isChecked']))?($values['isChecked'])?"checked":"":"";?> />
                                    <label for="user_type_mstr_id<?=$values['id']?>"><?=$values['user_type']?></label>
                                    </div>
                                </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="panel-footer text-left">
                                <button type="submit" id="btn_submit" name="btn_submit" class="btn btn-success"><?=(isset($menuDtl['id']))?($menuDtl['id']=='')?'Submit':'Update':'Submit';?></button>
                            </div>
                        </div>
                    </form>
		        </div>
		    </div>
		</div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
var parent_sub_menu_mstr_id = '<?=(isset($menuDtl['parent_sub_menu_mstr_id']))?$menuDtl['parent_sub_menu_mstr_id']:"";?>';
var subMenuLoadCount = '<?=(isset($menuDtl['parent_sub_menu_mstr_id']))?0:1;?>';
var loadSubMenuMstr = () => {
    subMenuLoadCount++;
    if ($('#parent_menu_mstr_id').val()!=0 && $('#parent_menu_mstr_id').val()!=-1) {
        $.ajax({
            type:"POST",
            url: "<?=base_url('')?>/MenuPermission2/ajaxGetSubMenuDtl",
            dataType: "json",
            data: {
                "parent_menu_mstr_id": $('#parent_menu_mstr_id').val(),
            },
            beforeSend: function() {
                $("#loadingDiv").show();
            },
            success:function(data){
                if(data.response==true) {
                    $("#parent_sub_menu_mstr_id").html(data.data);
                    if (parent_sub_menu_mstr_id!='' && subMenuLoadCount==1) {
                        $("#parent_sub_menu_mstr_id").val(parent_sub_menu_mstr_id);
                    }
                } else {
                    $("#parent_sub_menu_mstr_id").html('<option value="0">#</option>');
                }
                $("#loadingDiv").hide();
            }
        });
    } else {
        $("#parent_sub_menu_mstr_id").val("0");
    }
};
$(document).ready(function(){
    $('#parent_menu_mstr_id').change(function(){
        var parent_menu_mstr_id = $('#parent_menu_mstr_id').val();
        if(parent_menu_mstr_id == 0) {
            $('#menu_path').attr("readonly", true);
            $('#menu_path').val('');
            $('#menu_icon_hide_show').show();

            $('#parent_sub_menu_mstr_id').attr("disabled", true);
        } else if(parent_menu_mstr_id=="-1") {
            $('#menu_path').attr("readonly", false);
            $('#menu_icon_hide_show').show();

            $('#parent_sub_menu_mstr_id').attr("disabled", true);
        } else {
            $('#menu_path').attr("readonly", false);
            $('#menu_icon').val('');
            $('#menu_icon_hide_show').hide();

            $('#parent_sub_menu_mstr_id').attr("disabled", false);
        }
        loadSubMenuMstr();
    });
    $("#btn_submit").click(function(){
        var process = true;
        if($("#menu_name").val()==""){
            $("#menu_name").css('border-color', 'red');
            process=false;
        }
        if($("#parent_menu_mstr_id").val()=='-1'){
            if($("#menu_path").val()==""){
                $("#menu_path").css('border-color', 'red');
                process=false;
            }
        }
        if($("#parent_menu_mstr_id").val()!=0 && $("#parent_sub_menu_mstr_id").val()!=0){
            if($("#menu_path").val()==""){
                $("#menu_path").css('border-color', 'red');
                process=false;
            }
        }
        if($("#parent_menu_mstr_id").val()==0 || $("#parent_menu_mstr_id").val()=="-1"){
            if($("#menu_icon").val()==""){
                $("#menu_icon").css('border-color', 'red');
                process=false;
            }
        }
        var checkedLen = $("input[name='user_type_mstr_id[]']:checked").length;
        if(!checkedLen) {
            alert("You must check at least one designation!!");
            process = false;
        }
        var order_no = $("#order_no").val();
        if(order_no==""){
            $("#order_no").css('border-color', 'red');
            process=false;
        }
        return process;
    });
    $("#menu_name").keyup(function(){ $(this).css('border-color',''); });
    $("#menu_path").keyup(function(){ $(this).css('border-color',''); });
    $("#menu_icon").keyup(function(){ $(this).css('border-color',''); });
    $("#order_no").keyup(function(){ $(this).css('border-color',''); });

    $("#parent_menu_mstr_id").trigger("change");

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
</script>