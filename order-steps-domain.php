<?php defined('CORE_FOLDER') OR exit('You can not get in here!');
    $hoptions = [
        'page' => "order-steps-domain",
        'jquery-ui',
    ];
?>
<script>
        $( function() {
            $( "#accordion" ).accordion({
                heightStyle: "content"
            });
        });
    </script>
<div id="wrapper">

    <?php if(isset($steps) && sizeof($steps)>0): ?>
        <div class="asamaline"></div>
        <div class="ilanasamalar">
            <?php
                foreach ($steps AS $r=>$s){
                    $rank = $r+1;
                    ?>
                    <div class="ilanasamax"<?php echo $step == $s["id"] ? 'id="asamaaktif"' : ''; ?>><div align="center"><h3><?php echo $rank; ?></h3><div class="clear"></div><?php echo $s["name"]; ?></div></div>
                    <?php
                }
            ?>
        </div>
    <?php endif; ?>



    <?php if($step == "hosting"): ?>
    <div class="pakettitle" style="margin-top:0px;">
        <h1><strong><?php echo __("website/osteps/identify-your-web-hosting"); ?></strong></h1>
        <div class="line"></div>
        <h2><?php echo __("website/osteps/identify-your-web-hosting-note2"); ?></h2>
    </div>


    <div class="clear"></div>
    <div class="siparisbilgileri">

        <div class="domainsec">
            <div id="accordion">


                <h3><strong><?php echo __("website/osteps/our-web-hosting-packets"); ?></strong></h3>
                <div>
                    <table width="100%" border="0" align="center">
                        <tr>
                            <td style="border:none;" colspan="2">
                                <form action="<?php echo $links["step"]; ?>" method="post" id="StepForm1">
                                    <?php echo Validation::get_csrf_token('order-steps'); ?>

                                    <input type="hidden" name="type" value="selection">
                                    <div class="alanadisorgu">
                                        <select name="selection">
                                            <option value=""><?php echo __("website/osteps/select-your-hosting"); ?></option>
                                            <?php
                                                if(isset($hosting_list) && is_array($hosting_list) && $hosting_list){
                                                    foreach($hosting_list AS $row){
                                                        $products = isset($row["products"]) ? $row["products"] : [];
                                                        if($products){
                                                            ?>
                                                            <optgroup label="<?php echo $row["title"]; ?>">
                                                                <?php
                                                                    foreach($products AS $p){
                                                                        $dlimit = __("website/osteps/unlimited-disk");
                                                                        if(isset($p["module_data"]["create_account"]["reseller"]))
                                                                            $dlimit = $p["module_data"]["create_account"]["disk_limit"] != "unlimited" ? FileManager::formatByte(FileManager::converByte($p["module_data"]["create_account"]["disk_limit"]."MB")) : __("website/osteps/unlimited-disk");
                                                                        elseif(isset($p["options"]["disk_limit"]))
                                                                            $dlimit = $p["options"]["disk_limit"] != "unlimited" ? FileManager::formatByte(FileManager::converByte($p["options"]["disk_limit"]."MB")) : __("website/osteps/unlimited-disk");
                                                                        $price = Money::formatter_symbol($p["price"]["amount"],$p["price"]["cid"],!$p["override_usrcurrency"]);
                                                                        if($p["price"]["amount"]==0)
                                                                            $price = ___("needs/free-amount");
                                                                        $disk   = isset($p["options"]["disk_limit"]) ? $dlimit." - " : '';
                                                                        $name = $p["title"]." (".$disk.$price.")";
                                                                        ?>
                                                                        <option value="<?php echo $p["id"]; ?>"><?php echo $name; ?></option>
                                                                        <?php
                                                                    }
                                                                ?>
                                                            </optgroup>
                                                            <?php
                                                        }
                                                        if(isset($row["categories"]) && is_array($row["categories"]) && $row["categories"]){
                                                            foreach($row["categories"] AS $r){
                                                                $products2 = isset($r["products"]) ? $r["products"] : [];
                                                                ?>
                                                                <optgroup label="<?php echo $row["title"]." - ".$r["title"]; ?>">
                                                                    <?php
                                                                        foreach($products2 AS $p2){
                                                                            $dlimit = __("website/osteps/unlimited-disk");
                                                                            if(isset($p2["module_data"]["create_account"]["reseller"]))
                                                                                $dlimit = $p2["module_data"]["create_account"]["disk_limit"] != "unlimited" ? FileManager::formatByte(FileManager::converByte($p2["module_data"]["create_account"]["disk_limit"]."MB")) : __("website/osteps/unlimited-disk");
                                                                            elseif(isset($p2["options"]["disk_limit"]))
                                                                                $dlimit = $p2["options"]["disk_limit"] != "unlimited" ? FileManager::formatByte(FileManager::converByte($p2["options"]["disk_limit"]."MB")) : __("website/osteps/unlimited-disk");
                                                                            $price = Money::formatter_symbol($p2["price"]["amount"],$p2["price"]["cid"],!$p2["override_usrcurrency"]);
                                                                            if($p2["price"]["amount"]==0)
                                                                                $price = ___("needs/free-amount");
                                                                            $disk   = isset($p2["options"]["disk_limit"]) ? $dlimit." - " : '';
                                                                            $name = $p2["title"]." (".$disk.$price.")";
                                                                            ?>
                                                                            <option value="<?php echo $p2["id"]; ?>"><?php echo $name; ?></option>
                                                                            <?php
                                                                        }
                                                                    ?>
                                                                </optgroup>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                                        </select>
                                        <a href="javascript:void(0);" class="gonderbtn mio-ajax-submit" mio-ajax-options='{"result":"StepForm1_submit","waiting_text":"<?php echo addslashes(__("website/others/button1-pending")); ?>"}'><?php echo __("website/osteps/use-button"); ?></a>

                                        <h5><a href="<?php echo $hosting_link; ?>" target="_blank"><?php echo __("website/osteps/all-hosting-packages"); ?></a></h5>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>


                <h3><strong><?php echo __("website/osteps/idont-want-hosting"); ?></strong></h3>
                <div>
                    <table width="100%" border="0" align="center">
                        <tr>
                            <td style="border:none;" colspan="2">
                                <form action="<?php echo $links["step"]; ?>" method="post" id="StepForm2">
                                    <?php echo Validation::get_csrf_token('order-steps'); ?>

                                    <input type="hidden" name="type" value="none">
                                    <table width="100%" border="0" align="center">
                                        <tr>
                                            <td style="border:none;" colspan="2">
                                                <div class="alanadisorgu">
                                                    <h5 style="float:none;"><?php echo __("website/osteps/idont-want-hosting-note"); ?></h5>
                                                    <input name="ns1" type="text"  placeholder="<?php echo __("website/osteps/nohostingns1"); ?>" value="<?php echo isset($dns["ns1"]) ? $dns["ns1"] : ''; ?>">
                                                    <input name="ns2" type="text"  placeholder="<?php echo __("website/osteps/nohostingns2"); ?>" value="<?php echo isset($dns["ns2"]) ? $dns["ns2"] : ''; ?>">
                                                    <input name="ns3" type="text"  placeholder="<?php echo __("website/osteps/nohostingns3"); ?>" value="<?php echo isset($dns["ns3"]) ? $dns["ns3"] : ''; ?>">
                                                    <input name="ns4" type="text"  placeholder="<?php echo __("website/osteps/nohostingns4"); ?>" value="<?php echo isset($dns["ns4"]) ? $dns["ns4"] : ''; ?>">
                                                    <strong><a href="javascript:void(0);" class="btn mio-ajax-submit" mio-ajax-options='{"result":"StepForm2_submit","waiting_text":"<?php echo addslashes(__("website/others/button1-pending")); ?>"}'><?php echo __("website/osteps/nohostingbtn"); ?></a></strong>

                                                    <div class="error" id="result" style="display: none;  margin-top: 15px;"></div>

                                                </div>
                                            </td>
                                        </tr>
                                    </table>


                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            function StepForm1_submit(result) {
                if(result != ''){
                    var solve = getJson(result);
                    if(solve !== false){
                        if(solve.status == "error"){
                            if(solve.for != undefined && solve.for != ''){
                                $("#StepForm1 "+solve.for).focus();
                                $("#StepForm1 "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                $("#StepForm1 "+solve.for).change(function(){
                                    $(this).removeAttr("style");
                                });
                            }
                            if(solve.message != undefined && solve.message != '')
                                alert_error(solve.message,{timer:3000});
                        }else if(solve.status == "successful"){
                            if(solve.redirect != undefined && solve.redirect != '')
                                window.location.href = solve.redirect;
                        }
                    }else
                        console.log(result);
                }
            }

            function StepForm2_submit(result) {
                if(result != ''){
                    var solve = getJson(result);
                    if(solve !== false){
                        if(solve.status == "error"){
                            if(solve.for != undefined && solve.for != ''){
                                $("#StepForm2 "+solve.for).focus();
                                $("#StepForm2 "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                $("#StepForm2 "+solve.for).change(function(){
                                    $(this).removeAttr("style");
                                });
                            }
                            if(solve.message != undefined && solve.message != '')
                                alert_error(solve.message,{timer:3000});
                        }else if(solve.status == "successful"){
                            if(solve.redirect != undefined && solve.redirect != '')
                                window.location.href = solve.redirect;
                        }
                    }else
                        console.log(result);
                }
            }
        </script>
        <?php endif; ?>

        <?php if($step == "additional-services"): ?>
            <div class="pakettitle" style="margin-top:0px;">
                <h1><strong><?php echo __("website/osteps/additional-services"); ?></strong></h1>
                <div class="line"></div>
                <h2><?php echo __("website/osteps/additional-services-note"); ?></h2>
            </div>


            <div class="siparisbilgileri">

                <form action="<?php echo $links["step"]; ?>" method="post" id="StepForm1" enctype="multipart/form-data">
                    <?php echo Validation::get_csrf_token('order-steps'); ?>

                    <table width="100%" border="0" align="center">

                        <tr>
                            <td bgcolor="#ebebeb"><strong><?php echo __("website/osteps/additional-service"); ?></strong></td>
                            <td bgcolor="#ebebeb"><strong><?php echo __("website/osteps/additional-service-amount"); ?></strong></td>
                        </tr>

                        <?php
                            foreach($additional_services AS $service){
                                if($service["type"] == "none"){
                                    $options    = $service["data1"];
                                    $amount     = Money::formatter_symbol($options[0]["amount"],$options[0]["cid"],true);
                                    $periodic = View::period($options[0]["period_time"],$options[0]["period"]);
                                    ?>
                                    <tr>
                                        <td>
                                            <input id="additional-<?php echo $service["id"]; ?>" class="checkbox-custom" name="additional[<?php echo $service["id"]; ?>]" value="0" type="checkbox">
                                            <label for="additional-<?php echo $service["id"]; ?>" class="checkbox-custom-label"><?php echo $service["name"]; ?></label>
                                        </td>
                                        <td><h4><strong><?php echo $amount." (".$periodic.")"; ?></strong></h4></td>
                                    </tr>
                                    <?php
                                }
                            }
                        ?>

                        <tr>
                            <td style="border:none;" align="center" colspan="2">
                                <a href="javascript:void(0);" class="btn mio-ajax-submit" mio-ajax-options='{"result":"StepForm1_submit","waiting_text":"<?php echo addslashes(__("website/others/button1-pending")); ?>"}'><strong><?php echo __("website/osteps/continue-button"); ?> <i class="ion-android-arrow-dropright"></i></strong></a>

                                <div id="result" class="error" style="display: none;margin-top: 5px;"></div>
                            </td>
                        </tr>
                    </table>
                </form>



            </div>
            <script type="text/javascript">
                function StepForm1_submit(result) {
                    if(result != ''){
                        var solve = getJson(result);
                        if(solve !== false){
                            if(solve.status == "error"){
                                if(solve.for != undefined && solve.for != ''){
                                    $("#StepForm1 "+solve.for).focus();
                                    $("#StepForm1 "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                    $("#StepForm1 "+solve.for).change(function(){
                                        $(this).removeAttr("style");
                                    });
                                }
                                if(solve.message != undefined && solve.message != '')
                                    $("#StepForm1 #result").fadeIn(300).html(solve.message);
                                else
                                    $("#StepForm1 #result").fadeOut(300).html('');
                            }else if(solve.status == "successful"){
                                $("#StepForm1 #result").fadeOut(300).html('');
                                if(solve.redirect != undefined && solve.redirect != '') window.location.href = solve.redirect;
                            }
                        }else
                            console.log(result);
                    }
                }
            </script>
        <?php endif; ?>


    </div>
</div>