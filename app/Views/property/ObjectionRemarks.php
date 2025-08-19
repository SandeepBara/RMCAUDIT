<div class="panel panel-bordered panel-dark">
    <div data-toggle="collapse" data-target="#demo" role="type">
        <div class="panel-heading">
            <h3 class="panel-title">Level Remarks
            </h3>
        </div>
    </div>
    <div class="panel-body collapse" id="demo" >
        <div class="nano has-scrollbar" style="height: 60vh">
            <div class="nano-content" tabindex="0" style="right: -17px;">
                <div class="panel-body chat-body media-block">
                    <?php
                    if($objection["level1_remarks"]!=null)
                    {
                        ?>
                        <div class="chat-user">
                            <div class="media-left">
                                <img src="http://modernulb.com/RMCDMC/public/assets/img/avatar_user.png" class="img-circle img-sm" alt="IT Head" title="IT Head" loading="lazy">
                            </div>
                            <div class="media-body">
                                <div>
                                    <p><?=$objection["level1_remarks"];?><small>By IT Head</small></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    if($objection["sh_remarks"]!=null)
                    {
                        ?>
                        <div class="chat-me">
                            <div class="media-left">
                                <img src="http://modernulb.com/RMCDMC/public/assets/img/Property Section Incharge.png" class="img-circle img-sm" alt="Property Section Incharge" title="Property Section Incharge" loading="lazy">
                            </div>
                            <div class="media-body">
                                <div>
                                    <p><?=$objection["sh_remarks"];?><small>By Section Head</small></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    if($objection["eo_remarks"]!=null)
                    {
                        ?>
                        <div class="chat-user">
                            <div class="media-left">
                                <img src="http://modernulb.com/RMCDMC/public/assets/img/Executive Officer.png" class="img-circle img-sm" alt="Executive Officer" title="Executive Officer" loading="lazy">
                            </div>
                            <div class="media-body">
                                <div>
                                    <p><?=$objection["eo_remarks"];?><small>By Executive Officer</small></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <div class="nano-pane">
                <div class="nano-slider" style="height: 229px; transform: translate(0px, 0px);"></div>
            </div>
        </div>
    </div>
</div>