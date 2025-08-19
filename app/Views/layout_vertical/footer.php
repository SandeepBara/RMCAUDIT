<?php
//use App\Models\model_menu_mstr;
//$model_menu_mstr = new model_menu_mstr(db_connect("db_system"));
?>
</div>
<!--ASIDE-->
<aside id="aside-container">
    <div id="aside">
        <div class="nano">
            <div class="nano-content">
                <!--Nav tabs-->
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#demo-asd-tab-1" data-toggle="tab">
                            <i class="demo-pli-speech-bubble-7 icon-lg"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#demo-asd-tab-2" data-toggle="tab">
                            <i class="demo-pli-information icon-lg icon-fw"></i> Report
                        </a>
                    </li>
                    <li>
                        <a href="#demo-asd-tab-3" data-toggle="tab">
                            <i class="demo-pli-wrench icon-lg icon-fw"></i> Settings
                        </a>
                    </li>
                </ul>
                <!--End nav tabs-->
                <!-- Tabs Content -->
                <div class="tab-content">

                    <!--First tab (Contact list)-->
                    <div class="tab-pane fade in active" id="demo-asd-tab-1">
                        Tab 1
                    </div>
                    <!--End first tab (Contact list)-->
                    <!--Second tab (Custom layout)-->
                    <div class="tab-pane fade" id="demo-asd-tab-2">
                        Tab 2
                    </div>
                    <!--End second tab (Custom layout)-->
                    <!--Third tab (Settings)-->
                    <div class="tab-pane fade" id="demo-asd-tab-3">
                        Tab 3
                    </div>
                    <!--Third tab (Settings)-->

                </div>
            </div>
        </div>
    </div>
</aside>
<!--END ASIDE-->

<!--MAIN NAVIGATION-->
<nav id="mainnav-container">
    <div id="mainnav">
        <!--Menu-->
        <!--================================-->
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content">

                    <!--Profile Widget-->
                    <!--================================-->
                    <div id="mainnav-profile" class="mainnav-profile">
                        <div class="profile-wrap text-center">
                                <?php
                                    $session = session();
                                    $emp_details = $session->get('emp_details');
                                ?>
                                <div class="pad-btm">
                                    <img class="img-circle img-md" src="<?=($emp_details['photo_path']!="")?base_url()."/writable/uploads/emp_image/".$emp_details['photo_path']:base_url()."/public/assets/img/avatar/default_avatar.png";?>" alt="Profile Picture">
                                </div>

                                <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                     <!--<span class="pull-right dropdown-toggle">
                                        <i class="dropdown-caret"></i>
                                    </span>-->
                                    <p class="mnp-name"><?=$emp_details['emp_name'];?></p>
                                    <span class="mnp-desc"><?=$emp_details['email_id'];?></span>
                                </a>
                        </div>
                    </div>

                    <!--Shortcut buttons-->
                    <!--================================-->
                    <div id="mainnav-shortcut" class="hidden">
                        <ul class="list-unstyled shortcut-wrap">
                            <li class="col-xs-3" data-content="My Profile">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-mint">
                                        <i class="demo-pli-male"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Messages">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-warning">
                                        <i class="demo-pli-speech-bubble-3"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Activity">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-success">
                                        <i class="demo-pli-thunder"></i>
                                    </div>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Lock Screen">
                                <a class="shortcut-grid" href="#">
                                    <div class="icon-wrap icon-wrap-sm icon-circle bg-purple">
                                        <i class="demo-pli-lock-2"></i>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!--End shortcut buttons-->
                    <?php
                    $session = session();
                    $client = new \Predis\Client();
                    $menuList = $client->get("menu_list_".$session->get("emp_details")["user_type_mstr_id"]);
                    $newMenuExpiry = 60;
                    $stareBlink = '<span class="blink stars-blink">
                                        <svg fill="#000000" width="15px" height="15px" viewBox="-4 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>starempty</title> <path d="M19.688 27.531l-7.531-3.906-7.531 3.906 1.469-8.344-6.094-5.906 8.406-1.219 3.75-7.625 3.75 7.625 8.406 1.219-6.063 5.906zM8.688 13.813l-4.844 0.719 3.469 3.375c0.406 0.406 0.625 1 0.531 1.563l-0.813 4.813 4.281-2.281c0.25-0.125 0.563-0.188 0.844-0.188s0.594 0.063 0.844 0.188l4.281 2.281-0.813-4.813c-0.094-0.563 0.125-1.156 0.531-1.563l3.469-3.375-4.813-0.719c-0.563-0.094-1.094-0.469-1.344-1l-2.156-4.344-2.125 4.344c-0.25 0.531-0.781 0.906-1.344 1z"></path> </g></svg>
                                    </span>
                                ';
                    //$menuList = cache("menu_list_".$session->get("emp_details")["user_type_mstr_id"]);
                    if($menuList) {
                        $menuList = json_decode($menuList, true);
                    ?>
                    <ul id="mainnav-menu" class="list-group">

                        <?php
                        //print_r($menuList);
                        foreach ($menuList as $key => $menuMstr) {
                            if($menuMstr['parent_menu_mstr_id']=='0' && $menuMstr['menu_type']==0) { ?>
                                <li id="<?=$menuMstr['id'];?>">
                                    <a href="#">
                                        <i class="<?=$menuMstr['menu_icon'];?>"></i>
                                            <span class="menu-title"><?=$menuMstr['menu_name'];?></span>
                                            <?=
                                                ($menuMstr["created_on"]??false)&& date_diff(date_create($menuMstr["created_on"]),date_create('now'))->format("%R%a")<=$newMenuExpiry 
                                                ? $stareBlink
                                                :"";
                                            ?>
                                        <i class="arrow"></i>
                                    </a>
                                <?php if ((isset($menuMstr['sub_menu']) && !empty($menuMstr['sub_menu'])) || (isset($menuMstr['link_menu']) && !empty($menuMstr['link_menu']))) {  ?>
                                    <ul class="collapse">
                                    <?php if ((isset($menuMstr['link_menu']) && !empty($menuMstr['link_menu']))) { ?>
                                        <?php foreach ($menuMstr['link_menu'] AS $keyLink => $linkMenu) { ?>
                                            <li>
                                                <a href="<?=base_url('');?>/<?=$linkMenu['url_path'];?>"><?=$linkMenu['menu_name'];?>
                                                <?=
                                                    ($linkMenu["created_on"]??false)&& date_diff(date_create($linkMenu["created_on"]),date_create('now'))->format("%R%a")<=$newMenuExpiry 
                                                    ? $stareBlink
                                                    :"";
                                                ?>
                                                </a>
                                            </li>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if ((isset($menuMstr['sub_menu']) && !empty($menuMstr['sub_menu']))) { ?>
                                        <?php foreach ($menuMstr['sub_menu'] AS $keySubMenuMstr => $SubMenuMstr) { ?>
                                            <?php if(isset($SubMenuMstr['link_menu']) && !empty($SubMenuMstr['link_menu'])) { ?>
                                            <li>
                                                <a href="#"><?=$SubMenuMstr['menu_name'];?>
                                                    <i class="arrow"></i>
                                                    <?=
                                                        ($SubMenuMstr["created_on"]??false)&& date_diff(date_create($SubMenuMstr["created_on"]),date_create('now'))->format("%R%a")<=$newMenuExpiry 
                                                        ? $stareBlink
                                                        :"";
                                                    ?>
                                                </a>
                                                <ul class="collapse">
                                                <?php foreach ($SubMenuMstr['link_menu'] AS $keyLink => $linkMenu) { ?>
                                                    <li>
                                                        <a href="<?=base_url('');?>/<?=$linkMenu['url_path'];?>"><?=$linkMenu['menu_name'];?>
                                                        <?=
                                                            ($linkMenu["created_on"]??false)&& date_diff(date_create($linkMenu["created_on"]),date_create('now'))->format("%R%a")<=$newMenuExpiry 
                                                            ? $stareBlink
                                                            :"";
                                                        ?>
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                </ul>
                                            </li>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                    </ul>
                                <?php } ?>
                                </li>
                        <?php } else if($menuMstr['parent_menu_mstr_id']=='-1' && $menuMstr['menu_type']==0) {  ?>
                            <li id="<?=$menuMstr['id'];?>">
                                <a href="<?=base_url($menuMstr['url_path']);?>" onclick="navBarMenuActive('<?=$menuMstr['id'];?>', '<?=$menuMstr['id'];?>');">
                                    <i class="<?=$menuMstr['menu_icon'];?>"></i>
                                    <span class="menu-title"><?=$menuMstr['menu_name'];?></span>
                                    <?=
                                        ($menuMstr["created_on"]??false)&& date_diff(date_create($menuMstr["created_on"]),date_create('now'))->format("%R%a")<=$newMenuExpiry 
                                        ? $stareBlink
                                        :"";
                                    ?>
                                </a>
                            </li>
                        <?php
                            }
                        } ?>
                        <!--------->
                    </ul>
                    <?php
                    }
                    ?>

                </div>
            </div>
        </div>
        <!--End menu-->
    </div>
</nav>
<!--END MAIN NAVIGATION-->

<!-- FOOTER -->
<footer id="footer">
    <!-- Visible when footer positions are fixed -->
    <div class="show-fixed pad-rgt pull-right">
        You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
    </div>
    <!-- Visible when footer positions are static -->
    <div class="hide-fixed pull-right pad-rgt">
        <!--14GB of <strong>512GB</strong> Free.//-->
    </div>
    <p class="pad-lft"><!--&#0169; 2017 Your Company//--></p>
</footer>
<!-- END FOOTER -->
<!-- SCROLL PAGE BUTTON -->
<button class="scroll-top btn">
    <i class="pci-chevron chevron-up"></i>
</button>
<div class="modal fade" id="imagePopUp-lg-modal" role="dialog" tabindex="-1" aria-hidden="true"><!--Large Bootstrap Modal-->
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <!--Modal header-->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <i class="pci-cross pci-circle"></i>
                </button>
            </div>
            <!--Modal body-->
            <div class="modal-body" style="padding: 5px;">
                <img src="" class="img-lg imagePopUpPreview" alt="Profile Picture" style="width: 100%; height: 100%;">
            </div>
        </div>
    </div>
</div>
<!--End Large Bootstrap Modal-->
</div>
<!-- END OF CONTAINER -->
</body>
</html>
<script src="<?=base_url();?>/public/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url();?>/public/assets/js/nifty.min.js"></script>
<script src="<?=base_url();?>/public/assets/plugins/masked-input/jquery.maskedinput.min.js"></script>
<script src="<?=base_url();?>/public/assets/plugins/select2/js/select2.min.js"></script>
<script src="<?=base_url();?>/public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="<?=base_url();?>/public/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="<?=base_url();?>/public/assets/bootstrap4-toggle/js/bootstrap4-toggle.min.js"></script>
<!--[ OPTIONAL ]-->
<script type="text/javascript">
    function modelInfo(msg)
    {
        $.niftyNoty({
            type: 'info',
            icon : 'pli-exclamation icon-2x',
            message : msg,
            container : 'floating',
            timer : 5000
        });
    }
    <?php 
    if($result = flashToast('message'))
    {
        ?>
        
        modelInfo('<?=$result;?>');
        <?php 
    }
    ?>


    $("#select_ulb_mstr_id").change(function(){
        var selUlb = $(this).val();
        if (selUlb!="") window.location.replace("<?=base_url();?>/Login/ulbChnage/"+selUlb);
    });
    function navBarMenuActive(menuName, subMenuName){
        if (typeof(Storage) !== "undefined") {
            sessionStorage.setItem("activeMenuName", menuName);
            sessionStorage.setItem("activeSubMenuName", subMenuName);
        }
    }
    if(typeof(Storage) !== "undefined") {
            var activeMenuName = sessionStorage.getItem('activeMenuName');
            var activeSubMenuName = sessionStorage.getItem('activeSubMenuName');
            $("#"+activeMenuName).addClass("active-sub active");
            $("#ul_"+activeSubMenuName).addClass("active-link");
    }
</script>



<script>
    function abc()
    {
           // alert('djkds');
        $.ajax({
            type:"POST",
            url: '<?php echo base_url("collection_Verification/notification");?>',
            dataType: "json",
            data: {
                    "fromUrl":"",
            },
            beforeSend: function() {
            },
            success:function(data){
                console.log(data);
                if(data.count>0)
                {
                    //alert(data.html);
                    $("#my_div").html(data.html);
                    $("#bell").html('<span class="badge badge-header badge-danger"></span>');

                }
                else
                {
                     $("#bell").html("");

                }
            }
        });

    }
/*setInterval(function(){
 abc();
}, 5000);*/
 /* time in milliseconds (ie 2 seconds)*/
</script>

<!-- not allowed white space as first character in textarea-->
<script>
$("input").on("keypress", function(e) {
    if (e.which === 32 && !this.value.length)
        e.preventDefault();
});
$("textarea").on("keypress", function(e) {
    if (e.which === 32 && !this.value.length)
        e.preventDefault();
});




/******************** For Menu Selected Start *************************/
function setCookie(cname,cvalue,exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = document.cookie;/*decodeURIComponent(document.cookie)*/
    var ca = decodedCookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


$("#mainnav-menu li a").click(function(){
    if($(this).prop("href")!=undefined && $(this).prop("href")!=""){
        setCookie("lastactivepage", $(this).prop("href"), 30);
     }
     else
     {
         setCookie("lastactivepage", null, 30);
    }
    })

var CURRENT_URL=getCookie("lastactivepage");
CURRENT_URL = CURRENT_URL.replace('#', '');
//console.log(decodeURIComponent(CURRENT_URL));

$(document).ready(function(){
    $("#mainnav-menu li ul li a").each(function()
    {
        //console.log(decodeURIComponent($(this).attr("href")));
        if(decodeURIComponent($(this).attr("href")).replace(/\\/gi, "/") == decodeURIComponent(CURRENT_URL))
        {
            $(this).parent('li').addClass('active');
            $(this).parent('li').parent("ul").addClass('collapse in').attr('aria-expanded', 'true');
            $(this).parent('li').parent("ul").parent('li').addClass('active');
            $(this).parent('li').parent("ul").parent('li').parent("ul").addClass('collapse in').attr('aria-expanded', 'true');
        }
    });
});
/******************** For Menu Selected End *************************/
function printDiv(divName)
{
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
function PopupCenter(url, title, w, h) {  
    // Fixes dual-screen position                         Most browsers      Firefox  
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;  
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;  
              
    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;  
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;  
              
    var left = ((width / 2) - (w / 2)) + dualScreenLeft;  
    var top = ((height / 2) - (h / 2)) + dualScreenTop;  
    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);  
  
    // Puts focus on the newWindow  
    if (window.focus) {  
        newWindow.focus();  
    }  
} 
</script>

<script type="text/javascript">
$(document).ready(function(){
    $("#loadingDiv").hide();
});


$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>