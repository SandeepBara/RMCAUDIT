<br>
<br>
<br>

</div>
        <!-- FOOTER -->
        <footer id="footer">
            <div class="d-md-flex py-2 px-5" style="padding: 0 25px;border-bottom: 1px solid #4d4d54">
                <h5 style="color: #bfb8d5 !important">Important Links</h5>
                <ul class="link">
                    <li><a href="<?= base_url('home/sitemap') ?>">Sitemap</a>|</li>
                    <li><a href="<?= base_url('home/faq') ?>">FAQ</a>|</li>
                    <li><a href="<?= base_url('home/website_policy') ?>">Website Policy and Disclaimer</a>|</li>
                    <li><a href="<?= base_url('home/gallery') ?>">Gallery</a>|</li>
                    <li><a href="https://data.gov.in" target="_blank">data.gov.in</a>|</li>
                    <li><a href="<?= base_url('home/holiday') ?>">Holiday List</a>|</li>
                    <li><a href="https://gis1.jharkhand.gov.in/rsm" target="_blank">GIS Portal of RMC</a>|</li>
                    <li><a href="https://jsac.jharkhand.gov.in/gmland_portal/" target="_blank">GM Land Portal Survey</a>|</li>
                </ul>
            </div>
            <div class="d-md-flex py-2" style="padding-top: 10px;">
                <div class="container me-md-auto text-center text-md-start footer-nic-links">
                    <p style="text-align:center">
                            All Rights Reserved by Government of Jharkhand, India. &nbsp;
                        <span style=""><br> Copyright © 2024</span>
                    </p>
                </div>
            </div>
            <div class="show-fixed pad-rgt pull-right">
                You have <a href="#" class="text-main"><span class="badge badge-danger">3</span> pending action.</a>
            </div>
            <div class="hide-fixed pull-right pad-rgt">
                Developed by Sri Publication & Stationers Pvt Ltd.
            </div>
            
        </footer>
        <!-- END FOOTER -->
        <!-- SCROLL PAGE BUTTON -->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
    </div>
    <!-- END OF CONTAINER -->




<script src="<?=base_url();?>/public/assets/js/jquery.min.js"></script>
<script src="<?=base_url();?>/public/assets/js/bootstrap.min.js"></script>
<script src="<?=base_url();?>/public/assets/js/nifty.min.js"></script>

<script>
function modelInfo(msg){
    $.niftyNoty({
        type: 'info',
        icon : 'pli-exclamation icon-2x',
        message : msg,
        container : 'floating',
        timer : 5000
    });
}
<?php if($result = flashToast('message')) { ?>
	modelInfo('<?=$result;?>');
<?php }?>


function formatAMPM() {
    var d = new Date(),
    seconds = d.getSeconds().toString().length == 1 ? '0'+d.getSeconds() : d.getSeconds(),
    minutes = d.getMinutes().toString().length == 1 ? '0'+d.getMinutes() : d.getMinutes(),
    hours = d.getHours().toString().length == 1 ? '0'+d.getHours() : d.getHours(),
    ampm = d.getHours() >= 12 ? 'PM' : 'AM',
    months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
    return days[d.getDay()]+' '+months[d.getMonth()]+' '+d.getDate()+' '+d.getFullYear()+' '+hours+':'+minutes+':'+seconds+' '+ampm;
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



document.getElementById("front_show_date_time").innerHTML = formatAMPM();
window.setInterval(function(){ document.getElementById("front_show_date_time").innerHTML = formatAMPM(); }, 1000);
</script>


<script>
$(document).ready(function(){
	$("#loadingDiv").hide();
});

</script>


        <script>
            //---------Font Size increase decrease and normal--------
            var minh1 = 12;
            var maxh1 = 38;
            var min = 10;
            var max = 18;
            function increaseFontSize() {
                var p = document.getElementsByTagName('p');
                var h1 = document.getElementsByTagName('h1');
                for (i = 0; i < p.length; i++) {
                    if (p[i].style.fontSize) {
                        var s = parseInt(p[i].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != max) {
                        s += 1;
                    }
                    p[i].style.fontSize = s + "px"
                }
                for (j = 0; j < h1.length; j++) {
                    if (h1[j].style.fontSize) {
                        var s = parseInt(h1[j].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != maxh1) {
                        s += 1;
                    }
                    h1[j].style.fontSize = s + "px"
                }
                var a = document.getElementsByTagName('a');
                for (i = 0; i < a.length; i++) {
                    if (a[i].style.fontSize) {
                        var s = parseInt(a[i].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != max) {
                        s += 1;
                    }
                    a[i].style.fontSize = s + "px"
                }
                var h5 = document.getElementsByTagName('a');
                for (i = 0; i < h5.length; i++) {
                    if (h5[i].style.fontSize) {
                        var s = parseInt(h5[i].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != max) {
                        s += 1;
                    }
                    h5[i].style.fontSize = s + "px"
                }
            }
            function decreaseFontSize() {
                var p = document.getElementsByTagName('p');
                var h1 = document.getElementsByTagName('h1');
                for (i = 0; i < p.length; i++) {
                    if (p[i].style.fontSize) {
                        var s = parseInt(p[i].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != minh1) {
                        s -= 1;
                    }
                    p[i].style.fontSize = s + "px"
                }
                var h1 = document.getElementsByTagName('h1');
                for (j = 0; j < h1.length; j++) {
                    if (h1[j].style.fontSize) {
                        var s = parseInt(h1[j].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != minh1) {
                        s -= 1;
                    }
                    h1[j].style.fontSize = s + "px"
                }
                var a = document.getElementsByTagName('a');
                for (i = 0; i < a.length; i++) {
                    if (a[i].style.fontSize) {
                        var s = parseInt(a[i].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != minh1) {
                        s -= 1;
                    }
                    a[i].style.fontSize = s + "px"
                }
                var h5 = document.getElementsByTagName('h5');
                for (i = 0; i < h5.length; i++) {
                    if (h5[i].style.fontSize) {
                        var s = parseInt(h5[i].style.fontSize.replace("px", ""));
                    } else {
                        var s = 12;
                    }
                    if (s != minh1) {
                        s -= 1;
                    }
                    h5[i].style.fontSize = s + "px"
                }
            }

            size = parseInt($('p').css('font-size'));

            $("#normal").on("click", function () {
                size = 14;
                $("p").css("font-size", size + "px");
            });
            size = parseInt($('h1').css('font-size'));

            $("#normal").on("click", function () {
                size = 14;
                $("h1").css("font-size", size + "px");
            });
            size = parseInt($('a').css('font-size'));

            $("#normal").on("click", function () {
                size = 14;
                $("a").css("font-size", size + "px");
            });
            size = parseInt($('h5').css('font-size'));
            $("#normal").on("click", function () {
                size = 22;
                $("h5").css("font-size", size + "px");
            });
        </script>
</body>
</html>
