<?= $this->include('layout_vertical/header');?>
<!--CONTENT CONTAINER-->
<div id="content-container">
    <div id="page-head">
        <div id="page-title"><!--Page Title-->
            <h1 class="page-header text-overflow"></h1>
        </div><!--End page title-->
        <ol class="breadcrumb"><!--Breadcrumb-->
    		<li><a href="#"><i class="demo-pli-home"></i></a></li>
    		<li><a href="#">Menu</a></li>
    		<li class="active">Menu Add/Edit</li>
        </ol><!--End breadcrumb-->
    </div>
    <!--Page content-->
    <div id="page-content">
		<div class="row">
		    <div class="col-sm-12">
		        <div class="panel">
		            <div class="panel-heading">
                        <div class="panel-control">
                            <a href="<?=base_url();?>/MenuPermission/menuList" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                        <h5 class="panel-title">Menu Add/Edit</h5>
		            </div>
                    <form method="POST" action="<?=base_url('MenuPermission/menu_add_update/<?=(isset($menu["id"]))?$menu["id"]:"";?>');?>">
                        <div class="panel-body">
                            <input type="hidden" name="id" value="<?=(isset($menu['id']))?$menu['id']:'';?>">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Menu Name</label>
                                        <input type="text" maxlength="100" id="menu_name" name="menu_name" class="form-control" placeholder="Enter Menu Name" value="<?=(isset($menu['menu_name']))?$menu['menu_name']:'';?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Menu Path</label>
                                        <input type="text" id="menu_path" name="menu_path" class="form-control" placeholder="Enter Menu Path" value="<?=(isset($menu['menu_path']))?$menu['menu_path']:'';?>"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Under Menu Name ID</label>
                                        <select id="parent_menu_mstr_id" name="parent_menu_mstr_id" class="form-control">
                                            <option value="0">#</option>
                                        <?php
                                        if(isset($underMenuNameList)){
                                            foreach ($underMenuNameList as $values){
                                        ?>
                                            <option value="<?=$values['id']?>" <?=(isset($menu['parent_menu_mstr_id']))?($values['id']==$menu['parent_menu_mstr_id'])?'selected':'':'';?> ><?=$values['menu_name']?>
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
                            <button type="submit" id="btn_submit" name="btn_submit" class="btn btn-success"><?=(isset($menu['id']))?($menu['id']=='')?'Submit':'Update':'Submit';?></button>
                        </div>
                    </form>
		        </div>
		    </div>
		</div>
    <!--End page content-->
<!--END CONTENT CONTAINER-->
<?= $this->include('layout_vertical/footer');?>
<script type="text/javascript">
$(document).ready(function(){
      var parent_menu_mstr_id = $('#parent_menu_mstr_id').val();
    if(parent_menu_mstr_id == 0)
    {
       $('#menu_path').attr("readonly", true);
    }
     $('#parent_menu_mstr_id').change(function(){
       var parent_menu_mstr_id = $('#parent_menu_mstr_id').val();
       if(parent_menu_mstr_id == 0)
        {
         //$('#menu_path').var("");
          $('#menu_path').attr("readonly", true);
            $('#menu_path').var('');
        }
        else
        {
            $('#menu_path').attr("readonly", false);
        }
    });
    $("#btn_submit").click(function(){
        var process = true;
        if($("#menu_name").val()==""){
            $("#menu_name").css('border-color', 'red');
            process=false;
        }
        if($("#parent_menu_mstr_id").val()!=0){
            if($("#menu_path").val()==""){
                $("#menu_path").css('border-color', 'red');
                process=false;
            }
        }
        var checkedLen = $("input[name='user_type_mstr_id[]']:checked").length;
        if(!checkedLen) {
            alert("You must check at least one designation!!");
            process = false;
        }
        return process;
    });
    $("#menu_name").keyup(function(){ $(this).css('border-color',''); });
    $("#menu_path").keyup(function(){ $(this).css('border-color',''); });
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