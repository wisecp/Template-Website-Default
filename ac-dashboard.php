<?php defined('CORE_FOLDER') OR exit('You can not get in here!'); ?><div class="wclientblockscon">

    <div class="moderncliendblock mclientlastblocks">
        <div class="mpanelrightcon">

            <div class="mpaneltitle">
                <h4><strong><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo __("website/account/text5"); ?></strong></h4>
                <a href="<?php echo $links["create-ticket-request"]; ?>" class="sbtn"><?php echo __("website/account/text6"); ?></a>
            </div>

            <?php
                if(isset($tickets) && $tickets){
                    ?>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <thead>
                        <tr>
                            <th align="left"><?php echo __("website/account/text9"); ?></th>
                            <th align="center"><?php echo __("website/account/text10"); ?></th>
                            <th align="center"><?php echo __("website/account/text11"); ?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                            foreach($tickets AS $row){
                                $title  = $row["userunread"] ? $row["title"] : '<strong>'.$row["title"].'</strong>';
                                ?>
                                <tr>
                                    <td align="left">
                                        <strong><a href="<?php echo $row["detail_link"]; ?>"><?php echo $title; ?></a></strong>
                                        <?php
                                            if($row["service"]){
                                                ?>
                                                <br>
                                                <span class="productinfo"><?php echo $row["service"]; ?></span>
                                                <?php
                                            }
                                        ?>
                                    </td>
                                    <td align="center">
                                        <?php echo $ticket_situations[$row["status"]]; ?>
                                    </td>
                                    <td align="center">
                                        <a href="<?php echo $row["detail_link"]; ?>" class="sbtn"><i class="fa fa-search" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                                <?php

                            }
                        ?>
                        </tbody>
                    </table>
                    <?php
                }else{
                    ?>
                    <div class="noentryblock">
                        <i class="fa fa-smile-o" aria-hidden="true"></i>
                        <h2><?php echo __("website/account/text7"); ?></h2>
                        <h4><?php echo __("website/account/text8"); ?></h4>
                    </div>
                    <?php
                }
            ?>
            <div class="clear"></div>
        </div>
    </div>
    <div class="moderncliendblock mclientlastblocks">
        <div class="mpanelrightcon">

            <div class="mpaneltitle">
                <h4><strong><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo __("website/account/text12"); ?></strong></h4>
                <a href="<?php echo $links["all-orders"]; ?>" class="sbtn"><?php echo __("website/account/text13"); ?></a>
            </div>

            <?php
                if(isset($orders) && $orders){

                    ?>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                        <thead>
                        <tr>
                            <th align="left"><?php echo __("website/account/text9"); ?></th>
                            <th align="center"><?php echo __("website/account/text10"); ?></th>
                            <th align="center"><?php echo __("website/account/text11"); ?></th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php
                            foreach($orders AS $row){
                                if($row["status"] == "waiting" || $row["status"] == "inprocess" || $row["status"] == "cancelled"){
                                    $name = $row["name"];
                                }else
                                    $name = '<a href="'.$row["detail_link"].'">'.$row["name"].'</a>';

                                ?>
                                <tr>
                                    <td align="left">
                                        <strong><?php echo $name; ?></strong>
                                        <br>
                                        <span class="productinfo">
                                            <?php
                                                if(isset($row["options"]["domain"])){
                                                    echo $row["options"]["domain"];
                                                }elseif($row["type"] == "sms"){
                                                    echo $row["options"]["name"]." - ".$row["options"]["identity"];
                                                }elseif($row["type"] == "special"){
                                                    if(isset($row["options"]["category_name"]) && $row["options"]["category_name"])
                                                        echo $row["options"]["category_name"];
                                                    else
                                                        echo $row["options"]["group_name"];
                                                }elseif(isset($row["options"]["hostname"]) && $row["options"]["hostname"]){
                                                    echo $row["options"]["hostname"];
                                                    if(isset($row["options"]["ip"]) && $row["options"]["ip"])
                                                        echo " - ".$row["options"]["ip"];
                                                }
                                            ?>
                                        </span>
                                    </td>
                                    <td align="center">
                                        <?php echo $product_situations[$row["status"]]; ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                            if($row["status"] == "waiting" || $row["status"] == "inprocess" || $row["status"] == "cancelled"){
                                                ?>
                                                <a title="<?php echo __("website/account_products/manage"); ?>" style="-webkit-filter:grayscale(100%);filter: grayscale(100%);color: #777;opacity: 0.5;filter: alpha(opacity=50);" class="incelebtn"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                                <?php
                                            }else{
                                                ?>
                                                <a href="<?php echo $row["detail_link"]; ?>" title="<?php echo __("website/account_products/manage"); ?>" class="incelebtn"><i class="fa fa-cog" aria-hidden="true"></i></a>
                                                <?php
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        ?>
                        </tbody>
                    </table>
                    <?php

                }else{

                    ?>
                    <div class="noentryblock">
                        <i class="fa fa-meh-o" aria-hidden="true"></i>
                        <h2><?php echo __("website/account/text14"); ?></h2>
                        <h4><?php echo __("website/account/text15"); ?></h4>
                    </div>
                    <?php
                }
            ?>

            <div class="clear"></div>
        </div>
    </div>


    <?php if(isset($dashboard_activity) && is_array($dashboard_activity)): ?>
        <div class="moderncliendblock">
            <div class="mpanelrightcon">

                <div class="mpaneltitle">
                    <h4><strong><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo __("website/account/dashboard-activity-title"); ?></strong></h4>
                </div>

                <?php
                    foreach($dashboard_activity AS $activity){
                        ?>
                        <div class="mpanelhaber">
                            <strong><?php echo is_array($activity["description"]) ? "arrray - ".$activity["detail"] : $activity["description"]; ?></strong> <br><?php echo __("website/account/activity-date"); ?>: <?php echo $activity["ctime"]; ?> - <?php echo __("website/account/activity-ip"); ?>: <?php echo $activity["ip"]; ?>
                        </div>
                        <?php
                    }
                ?>

                <?php echo isset($dashboard_activity_pagination) ? $dashboard_activity_pagination : false; ?>

                <div class="clear"></div>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($dashboard_news) && is_array($dashboard_news)): ?>
        <div class="moderncliendblock">
            <div class="mpanelrightcon">
                <div class="mpaneltitle">
                    <h4><strong><i class="fa fa-bullhorn" aria-hidden="true"></i> <?php echo __("website/account/dashboard-news-title"); ?></strong></h4>
                </div>

                <?php
                    $folder = Config::get("pictures/page-news/folder");
                    foreach($dashboard_news AS $news){
                        if(!$news["image"]) $news["image"] = Utility::image_link_determiner("default.svg",$folder);
                        ?>
                        <div class="mpanelhaber">
                        <img width="100" height="100" src="<?php echo $news["image"]; ?>"/>
                        <a href="<?php echo $news["route"]; ?>"><h5 style="font-size:17px;" ><strong><?php echo $news["title"]; ?></strong> </h5></a>
                        <span style="font-size:14px;"><?php echo $news["content"]; ?> <a style="font-weight:600;" href="<?php echo $news["route"]; ?>"><?php echo __("website/account/continue-link"); ?></a> (<?php echo $news["date"]; ?>)</span>
                        </div><?php
                    }
                ?>

                <?php echo (isset($dashboard_news_pagination)) ? $dashboard_news_pagination : false; ?>

                <div class="clear"></div>
            </div>
        </div>
    <?php endif; ?>

</div>