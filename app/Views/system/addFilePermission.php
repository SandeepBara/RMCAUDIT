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
    		<li><a href="#">File</a></li>
    		<li class="active">File Add/Edist</li>
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
                        <h5 class="panel-title">File Add/Edit</h5>
		            </div>
                    <form method="POST" action="<?=base_url('MenuPermission2/addFilePermission');?>">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Controller Name</label>
                                        <select name="controller_name" id="controller_name" class="form-control" onchange="getMethods(this.value)">
                                            <option value="">Selects</option>
                                            <?php
                                            if(isset($controllers))
                                            {
                                                foreach($controllers as $val)
                                                {
                                                    ?>
                                                    <option value="<?=$val?>"<?=isset($controller_name) && $controller_name==$val?"selected":"";?>><?=$val?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                 <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Method Name</label>
                                        <select name="methods" id="methods" class="form-control" onchange="getuserType(this.value)">
                                            <option value="">#</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="control-label"><b><u>Permission To</u></b></label>
                                </div>
                                <?php
                                if(isset($user_type_list))
                                {
                                    foreach ($user_type_list as $values) 
                                    {
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
$(document).ready(function(){
    $("#btn_submit").click(function(){
        var process = true;
        if($("#controller_name").val()==""){
            $("#controller_name").css('border-color', 'red');
            process=false;
        }
        if($("#methods").val()==""){
            $("#methods").css('border-color', 'red');
            process=false;
        }
        return process;
    });
    <?php
    if(isset($controller_name)&& $controller_name)
    {
        ?>
        getMethods("<?=$controller_name;?>");
        <?php
    }
    if(isset($methods) && $methods)
    {
        ?>
        getuserType("<?=$methods;?>");
        <?php
    }
    ?>
    <?php
    if(isset($list) && $list)
    {
        foreach($user_type_list as $val)
        {
            ?>
            $("#user_type_mstr_id"+<?=$val['id']?>).prop("checked", false);
            <?php
        }
        foreach($list as $val)
        {
            ?>
            $("#user_type_mstr_id"+<?=$val?>).prop("checked", true);
            <?php
        }
    }
    ?>
});
function getMethods(className)
{
    controller_name = "<?=$controller_name??"";?>";
    if(className!="undefined" && className!="")
    {
        $.ajax({
            type:"GET",
            url: "<?=base_url('')?>/GetClassMethods.php",
            data: {
                "class": className,
            },
            beforeSend: function() {
                $("#loadingDiv").show();
            },
            success:function(data){
                data = JSON.parse(data);
                if(data.response==true) 
                { 
                    $("#methods").html(data.data);
                    <?php 
                    if(isset($methods) && $methods)
                    {
                        ?>  
                       if(controller_name ==className)
                       {
                           selectElement = document.getElementById("methods");      
                           len = selectElement.length;
                           var options= selectElement.options;
                           for(i=1;i<=len;i++)
                           {
                               if (options[i].value=="<?=$methods?>") {
                                   options[i].selected= true;
                                   break;
                               }
                           }

                       }
                        <?php
                    }
                    ?>
                } 
                else 
                {
                    $("#methods").html('<option value="">#</option>');
                }
                $("#loadingDiv").hide();
            }
        });
    }
}
function getuserType(methodsName)
{
    className =$("#controller_name").val();
    if(className!="undefined" && className!="")
    {
        $.ajax({
            type:"post",
            url: "<?=base_url('')?>/MenuPermission2/PermitedFileUserType",
            dataType: "json",
            data: {
                "className": className,
                "methodName": methodsName,
            },
            beforeSend: function() {
                $("#loadingDiv").show();
            },
            success:function(data){
                if(data.response==true) 
                {  
                    // console.log(data.data);
                    <?php 
                        foreach($user_type_list as $val)
                        {
                            ?>
                            $("#user_type_mstr_id"+<?=$val['id']?>).prop("checked", false);
                            <?php
                        }
                    ?>
                    data.data.forEach(function(userData){

                        // console.log(userData);
                        $("#user_type_mstr_id"+userData["user_type_mstr_id"]).prop("checked", true);
                    });
                    
                } 
                else 
                {
                    // $("#methods").html('<option value="">#</option>');
                }
                $("#loadingDiv").hide();
            }
        });
    }
}
</script>