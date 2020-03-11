<?php defined('CORE_FOLDER') OR exit('You can not get in here!');
    $wide_content   = true;
    $proanse_amount = $amount;

    include $tpath."common-needs.php";
    $hoptions = ["datatables","jquery-ui"];

    if(isset($module) && $module && isset($proanse["hide_module_details"]) && $proanse["hide_module_details"]) $module = false;

?>
<style type="text/css"></style>
<script type="text/javascript">
    function openTab(evt, tabName) {
        var gtab,dtab,link,tab;
        $(".tabcontent").css("display","none");
        $(".tablinks").removeClass("active");
        $("#"+tabName).css("display","block");
        $(evt).addClass("active");
        gtab     = gGET("tab");
        dtab     = $(evt).attr("data-tab");
        if((gtab == '' || gtab == null || gtab == undefined) && dtab == 1){
            link    = window.location.href;
        }else{
            link     = sGET("tab",dtab);
        }
        window.history.pushState("object or string", $("title").html(),link);
    }

    $(document).ready(function(){
        var tab = gGET("tab");
        if(tab == '' || tab == undefined){
            $(".tablinks:eq(0)").click();
        }else{
            $(".tablinks[data-tab='"+tab+"']").click();
        }
    });
</script>
<?php
    if(isset($module) && $module){
        ?>
        <script type="text/javascript">
            $(document).ready(function(){
                setTimeout(function(){
                    var request = MioAjax({
                        action:"<?php echo $links["controller"]; ?>",
                        method:"POST",
                        data:{action:"get_details"},
                    },true,true);

                    request.done(function(result){
                        $("#get_details_module_content").html(result);
                    });

                },500);
            });
        </script>
        <?php
    }
?>
<div class="mpanelrightcon">

    <div class="mpaneltitle">
        <div class="sayfayolu"><?php include $tpath."inc".DS."panel-breadcrumb.php"; ?></div>
        <h4><strong><i class="fa fa-cube" aria-hidden="true"></i> <?php echo $proanse["name"]; ?></strong></h4>
    </div>


    <ul class="tab">
        <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'ozet')" data-tab="1"><i class="fa fa-info" aria-hidden="true"></i> <?php echo __("website/account_products/detail"); ?></a></li>

        <?php if(isset($addons) && $addons): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'addons')" data-tab="addons"><i class="fa fa-rocket" aria-hidden="true"></i> <?php echo __("website/account_products/tab-addons"); ?></a></li>
        <?php endif; ?>

        <?php if(isset($requirements) && $requirements): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'requirements')" data-tab="requirements"><i class="fa fa-check-square" aria-hidden="true"></i> <?php echo __("website/account_products/tab-requirements"); ?></a></li>
        <?php endif; ?>

        <?php if($proanse["status"] == "active" && $proanse["period"] != "none" && $upgrade): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'upgrade')" data-tab="upgrade"><i class="ion-speedometer" aria-hidden="true"></i> <?php echo __("website/account_products/special-tab-upgrade"); ?></a></li>
        <?php endif; ?>



        <?php if($proanse["status"] == "active" && $proanse["period"] != "none"): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'iptaltalebi')" data-tab="cancellation"><i class="fa fa-ban" aria-hidden="true"></i> <?php echo __("website/account_products/cancellation-request"); ?></a></li>
        <?php endif; ?>
    </ul>


    <div id="ozet" class="tabcontent">

        <div<?php echo isset($module) && $module ? ' id="block_modulewidth50"' : ''; ?> class="hizmetblok">

            <div class="cpanelebmail">
                <?php if(isset($options["product_image"]) && $options["product_image"]): ?>
                    <img style="  max-height: 90px;width:auto;" src="<?php echo $options["product_image"]; ?>" width="200" height="auto">
                <?php else: ?>
                    <i style="font-size:100px;" class="fa fa-rocket"></i>
                <?php endif; ?>

                <?php
                    if(isset($options["domain"]) && $options["domain"]){
                        ?>
                        <h4><strong><?php echo $options["domain"]; ?></strong></h4>
                        <?php
                    }

                    if(isset($options["ip"]) && $options["ip"]){
                        ?>
                        <h4><strong><?php echo $options["ip"]; ?></strong></h4>
                        <?php
                    }
                ?>

                <?php
                    if(isset($options["delivery_title_name"]) && isset($options["delivery_title_description"]) && $options["delivery_title_name"] && $options["delivery_title_description"]){
                        ?>
                        <H5><br><strong><?php echo $options["delivery_title_name"]; ?></strong><br><?php echo $options["delivery_title_description"]; ?></H5>
                        <?php
                    }elseif(isset($options["delivery_title_name"]) && $options["delivery_title_name"]){
                        ?>
                        <H5><strong><?php echo $options["delivery_title_name"]; ?></strong></H5>
                        <?php
                    }elseif(isset($options["delivery_title_description"]) && $options["delivery_title_description"]){
                        ?>
                        <H5><?php echo $options["delivery_title_description"]; ?></H5>
                        <?php
                    }

                    if(isset($options["delivery_file_button_title"]) && isset($download_link) && $options["delivery_file_button_title"] && $download_link){
                        ?>
                        <a<?php echo $proanse["status"] != "active" ? "" : ' href="'.$download_link.'"'; ?> class="<?php echo $proanse["status"] != "active" ? "graybtn" : "yesilbtn"; ?> gonderbtn"><?php echo $options["delivery_file_button_title"]; ?></a>
                        <?php
                    }elseif(isset($download_link) && $download_link != ''){
                        ?>
                        <a <?php echo $proanse["status"] != "active" ? "" : 'href="'.$download_link.'"'; ?> class="<?php echo $proanse["status"] != "active" ? "graybtn" : "yesilbtn"; ?> gonderbtn"><?php echo __("website/account_products/download-button"); ?></a>
                        <?php
                    }
                ?>

                <?php
                    if(isset($product) && $product && $proanse["period"] != "none" && ($proanse["status"] == "active" || $proanse["status"] == "suspended") && !isset($proanse["disable_renewal"])){
                        ?>
                        <div class="clear"></div>
                        <div id="renewal_list" style="display:none;">
                            <select id="selection_renewal">
                                <option value=""><?php echo __("website/account_products/renewal-list-option"); ?></option>
                                <?php
                                    if(isset($product["price"])){
                                        foreach($product["price"] AS $k=>$v){
                                            ?>
                                            <option value="<?php echo $k; ?>"><?php
                                                    echo View::period($v["time"],$v["period"]);
                                                    echo " ";
                                                    echo Money::formatter_symbol($v["amount"],$v["cid"],true);
                                                ?></option>
                                            <?php
                                        }
                                    }
                                ?>
                            </select>
                            <script type="text/javascript">
                                $(document).ready(function () {
                                    $("#selection_renewal").change(function () {
                                        var selection = $(this).val();
                                        if (selection != '') {
                                            var result = MioAjax({
                                                action: "<?php echo $links["controller"]; ?>",
                                                method: "POST",
                                                data: {operation: "order_renewal", period: selection}
                                            }, true);

                                            if (result) {
                                                var solve = getJson(result);
                                                if (solve) {
                                                    if (solve.status == "successful") {
                                                        window.location.href = solve.redirect;
                                                    }
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <a href="javascript:$('#renewal_list').slideToggle(400);void 0;"
                           class="mavibtn gonderbtn"><?php echo __("website/account_products/renewal-now-button"); ?></a>
                        <div class="clear"></div>
                        <?php
                    }
                ?>

            </div>
        </div>

        <div<?php echo isset($module) && $module ? ' id="block_modulewidth50"' : ''; ?> class="hizmetblok">
            <table width="100%" border="0">

                <tr>
                    <td colspan="2" bgcolor="#ebebeb">
                        <strong style="float: left;"><?php echo __("website/account_products/general-info"); ?></strong>

                        <span style="float: right"><?php echo __("website/account_products/table-ordernum"); ?>: #<?php echo $proanse["id"]; ?></span>
                    </td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/service-group"); ?></strong></td>
                    <td><?php echo $options["group_name"]; ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/service-name"); ?></strong></td>
                    <td><?php echo $proanse["name"]; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo __("website/account_products/service-status"); ?></strong></td>
                    <td><?php echo $product_situations[$proanse["status"]]; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo __("website/account_products/payment-period"); ?></strong></td>
                    <td><?php echo View::period($proanse["period_time"],$proanse["period"]); ?></td>
                </tr>

                <?php if(substr($proanse["renewaldate"],0,4) != "1881" && substr($proanse["renewaldate"],0,4) != "1970"): ?>
                    <tr>
                        <td><strong><?php echo __("website/account_products/renewal-date"); ?></strong></td>
                        <td><?php echo DateManager::format("d/m/Y",$proanse["renewaldate"]); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if(substr($proanse["duedate"],0,4) != "1881" && substr($proanse["duedate"],0,4) != "1970"): ?>
                    <tr>
                        <td><strong><?php echo __("website/account_products/due-date"); ?></strong></td>
                        <td><?php echo DateManager::format("d/m/Y",$proanse["duedate"]); ?></td>
                    </tr>
                <?php endif; ?>

                <tr align="center" class="tutartd">
                    <td colspan="2"><strong><?php echo __("website/account_products/amount"); ?> : <?php echo $proanse_amount; ?></strong></td>
                </tr>
            </table>

        </div>

        <?php
            if(isset($module) && $module){
                ?>
                <div class="clear"></div>
                <div class="block_module_details" id="get_details_module_content">
                    <div class="hizmetblok" id="block_module_details_con">
                        <div id="block_module_loader">
                            <div class="load-wrapp">
                                <p style="margin-bottom:20px;font-size:17px;"><strong><?php echo ___("needs/processing"); ?>...</strong><br><?php echo ___("needs/please-wait"); ?></p>
                                <div class="load-7">
                                    <div class="square-holder">
                                        <div class="square"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>


        <?php
            if(isset($options["blocks"]) && $options["blocks"]){
                foreach($options["blocks"] AS $block){
                    ?>
                    <div class="hizmetblok block-item">
                        <div class="block_module_details-title formcon"><h4><?php echo $block["title"]; ?></h4></div>
                        <div class="block-item-desc"><div class="padding10"><?php echo Filter::link_convert(nl2br($block["description"])); ?></div></div>
                    </div>
                    <?php
                }
            }
        ?>

    </div>

    <?php if(isset($addons) && $addons): ?>
        <div id="addons" class="tabcontent">

            <script type="text/javascript">
                $(document).ready(function(){

                    $('#addons_table').DataTable({
                        "columnDefs": [
                            {
                                "targets": [0],
                                "visible":false,
                                "searchable": false
                            },
                        ],
                        paging: false,
                        info:     false,
                        searching: false,
                        responsive: true,
                        "language":{
                            "url":"<?php echo APP_URI."/".___("package/code")."/datatable/lang.json";?>"
                        }
                    });
                });
            </script>

            <table width="100%" id="addons_table" class="table table-striped table-borderedx table-condensed nowrap">
                <thead style="background:#ebebeb;">
                <tr>
                    <th align="left">#</th>
                    <th align="left"><?php echo __("website/account_products/addons-table-addon-info"); ?></th>
                    <th align="center"><?php echo __("website/account_products/addons-table-date"); ?></th>
                    <th align="center"><?php echo __("website/account_products/addons-table-amount"); ?></th>
                    <th align="center"><?php echo __("website/account_products/addons-table-status"); ?></th>
                </tr>
                </thead>
                <tbody align="center" style="border-top:none;">
                <?php
                    foreach($addons AS $k=>$row){
                        $list_cdatetime     = substr($row["cdate"],0,4)!="1881" ? DateManager::format("d/m/Y",$row["cdate"]) : '-';
                        $list_rdatetime     = substr($row["renewaldate"],0,4)!="1881" ? DateManager::format("d/m/Y",$row["renewaldate"]) : '-';
                        $list_duedatetime   = substr($row["duedate"],0,4)!="1881" ? DateManager::format("d/m/Y",$row["duedate"]) : '-';
                        $status = $product_situations[$row["status"]];

                        $amount = $row["amount"]>0 ? Money::formatter_symbol($row["amount"],$row["cid"]) : ___("needs/free-amount");
                        $period = NULL;

                        if($row["amount"]>0){
                            $period = View::period($row["period_time"],$row["period"]);
                        }
                        $amount_period  = "<strong>".$amount."</strong>";
                        if($period) $amount_period.= "<br>".$period."";

                        ?>
                        <tr>
                            <td align="left"><?php echo $k; ?></td>
                            <td align="left"><?php echo $row["addon_name"]."<br>".$row["option_name"]; ?></td>
                            <td align="center"><?php echo $list_rdatetime."<br>".$list_duedatetime; ?></td>
                            <td align="center"><?php echo $amount_period; ?></td>
                            <td align="center"><?php echo $status; ?></td>
                        </tr>
                        <?php
                    }
                ?>
                </tbody>
            </table>



        </div>
    <?php endif; ?>

    <?php if(isset($requirements) && $requirements): ?>
        <div id="requirements" class="tabcontent">

            <?php
                foreach($requirements AS $requirement){
                    $response = $requirement["response"];
                    $rtype    = $requirement["response_type"];

                    if($rtype == "select" || $rtype == "radio" || $rtype == "checkbox" || $rtype == "file")
                        $response_j   = Utility::jdecode($response,true);

                    if(($rtype == "select" || $rtype == "radio") && is_array($response_j))
                        $response = htmlentities($response_j[0], ENT_QUOTES);
                    else
                        $response = htmlentities($response,ENT_QUOTES);


                    ?>
                    <div class="formcon">
                        <div class="yuzde30"><?php echo $requirement["requirement_name"]; ?></div>
                        <div class="yuzde70">
                            <?php
                                if($rtype == "file"){
                                    foreach($response_j AS $k=>$re){
                                        $link = $links["controller"]."?operation=requirement-file-download&rid=".$requirement["id"]."&key=".$k;
                                        ?><a href="<?php echo $link; ?>" target="_blank" class="lbtn"><i class="fa fa-external-link" aria-hidden="true"></i> <?php echo Utility::short_text($re["file_name"],0,30,true); ?></a> <?php
                                    }
                                }elseif($rtype == "checkbox"){
                                    if($response_j) echo implode(" , ",$response_j);
                                }else
                                    echo nl2br(Filter::link_convert($response));
                            ?>
                        </div>
                    </div>
                    <?php
                }
            ?>


        </div>
    <?php endif; ?>

    <?php if($proanse["status"] == "active" && $proanse["period"] != "none" && $upgrade): ?>
        <div id="upgrade" class="tabcontent">

            <div class="tabcontentcon content-updown">

                <div class="green-info" style="margin-bottom:25px;">
                    <div class="padding20">
                        <i class="ion-speedometer" aria-hidden="true"></i>
                        <?php echo __("website/account_products/special-upgrade-info"); ?>
                    </div>
                </div>

                <div class="formcon">
                    <div class="yuzde30" style="vertical-align:top;"><?php echo __("website/account_products/upgrade-order-statistics"); ?>
                        <div class="clear"></div>
                        <span class="kinfo"><?php echo __("website/account_products/upgrade-order-statistics-info"); ?></span>
                    </div>
                    <div class="yuzde70">

                        <div class="yuzde50" style="vertical-align:top;">

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-product-name"); ?>:</span>
                                <strong><?php echo $proanse["name"]; ?></strong>
                            </div>

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-renewaldate"); ?>:</span>
                                <strong> <?php echo DateManager::format("d.m.Y",$proanse["renewaldate"]); ?></strong>
                            </div>

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-duedate"); ?>:</span>
                                <strong><?php echo DateManager::format("d.m.Y",$proanse["duedate"]); ?>     </strong>
                            </div>


                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-times-used"); ?>:</span>
                                <strong> <?php echo $upgrade_times_used ." ". ___("date/day"); ?></strong> <!-- <?php echo $upgrade_times_used_amount; ?> -->
                            </div>

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-remaining-day"); ?>:</span>
                                <strong><?php echo $upgrade_remaining_day ." ". ___("date/day"); ?></strong> <!--<?php echo $upgrade_remaining_amount; ?>-->
                            </div>

                        </div>

                    </div>
                </div>


                <?php if(isset($upgproducts) && $upgproducts["categories"]): ?>

                    <div id="upgradeConfirm" data-izimodal-title="<?php echo __("website/account_products/special-tab-upgrade"); ?>" style="display: none">
                        <div class="padding20">
                            <div align="center">
                                <span id="upgradeConfirm_text"></span>

                                <div class="clear"></div>

                                <a style="float:none" href="javascript:void(0);" id="upgradeConfirm_ok" class="gonderbtn yesilbtn"><i class="fa fa-check"></i> <?php echo ___("needs/iconfirm"); ?></a>
                                <div class="clear"></div>

                            </div>

                        </div>

                    </div>

                    <script type="text/javascript">
                        function selected_category(id){
                            $(".upgrade-products").fadeOut(300);
                            $("#category_"+id).fadeIn(300);
                        }

                        function upgradeProduct(id){
                            var selectedPrice = $("#product_"+id+"_price").val();
                            var name          = $("#product_"+id+"_name").val();
                            var payable       = $("#product_"+id+"_price option:selected").data("payable");

                            open_modal("upgradeConfirm");

                            var confirm_text = '<?php echo str_replace("\n","<br>",__("website/account_products/special-upgrade-confirm-text")); ?>';
                            confirm_text     = confirm_text.replace("{name}",name);
                            confirm_text     = confirm_text.replace("{payable}",payable);

                            $("#upgradeConfirm_text").html(confirm_text);

                            $("#upgradeConfirm_ok").on("click",function(){

                                var request       = MioAjax({
                                    button_element:$("#upgradeConfirm_ok"),
                                    waiting_text: '<?php echo addslashes(__("website/others/button5-pending")); ?>',
                                    action:"<?php echo $links["controller"]; ?>",
                                    method:"POST",
                                    data:{operation:"set_upgrade_product",product_id:id,pirce_id:selectedPrice},
                                },true,true);

                                request.done(function(result){
                                    if(result != ''){
                                        var solve = getJson(result);
                                        if(solve !== false){
                                            if(solve.status == "error"){
                                                if(solve.message != undefined && solve.message != '')
                                                    alert_error(solve.message,{timer:5000});
                                            }else if(solve.status == "successful"){
                                                if(solve.message != undefined) alert_success(solve.message,{timer:2000});
                                                if(solve.redirect != undefined && solve.redirect != ''){
                                                    window.location.href = solve.redirect;
                                                }
                                            }
                                        }else
                                            console.log(result);
                                    }
                                });

                            });

                        }

                        $(document).ready(function(){
                            <?php
                            if($upgproducts["categories"]){
                            $keys = array_keys($upgproducts["categories"]);
                            ?>$('#upgrade_selected_product option[value=<?php echo $keys[0]; ?>]').attr("selected",true).trigger("change");<?php
                            }
                            ?>
                        });
                    </script>

                    <div class="formcon">
                        <div class="yuzde30"><?php echo __("website/account_products/upgrade-select-packages"); ?>
                            <div class="clear"></div>
                        </div>
                        <div class="yuzde70">
                            <select name="product" id="upgrade_selected_product" onchange="selected_category(this.options[this.selectedIndex].value);">
                                <?php
                                    if(isset($upgproducts) && $upgproducts){
                                        if($upgproducts["categories"]){
                                            foreach($upgproducts["categories"] AS $caid=>$val){
                                                ?>
                                                <option value="<?php echo $caid; ?>"><?php echo $val["title"]; ?></option>
                                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </select>

                        </div>
                    </div>

                    <div class="tablopaketler" style="background: none;">

                        <?php
                            foreach($upgproducts["categories"] AS $caid=>$cat){
                                $products   = isset($upgproducts["products"][$caid]) ? $upgproducts["products"][$caid] : false;
                                $cat["options"]     = Utility::jdecode($cat["options"],true);
                                $cat["optionsl"]    = Utility::jdecode($cat["optionsl"],true);

                                if(isset($cat["optionsl"]["columns"]))
                                    $columns = $cat["optionsl"]["columns"];
                                else
                                    $columns = [];

                                ?>

                                <div id="category_<?php echo $caid; ?>" style="display:none;" class="upgrade-products">
                                    <?php
                                        foreach($products AS $product){
                                            $prices     = $upgproducts["prices"][$product["id"]];

                                            $opt  = Utility::jdecode($product["options"],true);
                                            $optl = Utility::jdecode($product["options_lang"],true);
                                            $isCurrent = $product["id"] == $proanse["product_id"];
                                            if($isCurrent) continue;

                                            $popular            = false;

                                            if(isset($opt["popular"]) && $opt["popular"]) $popular = true;

                                            ?>
                                            <div class="tablepaket<?php echo $popular ? ' active' : '' ?>">
                                                <?php
                                                    if($popular){
                                                        ?>
                                                        <div class="tablepopular"><?php echo __("website/products/popular"); ?></div>
                                                        <?php
                                                    }
                                                ?>
                                                <div class="tpakettitle"><?php echo $product["title"]; ?></div>
                                                <div class="paketline"></div>
                                                <div class="clear"></div>
                                                <div class="products_features">
                                                    <?php
                                                        $json       = Utility::jdecode($product["features"],true);
                                                        if($json){
                                                            if(isset($columns) && $columns){
                                                                foreach($columns AS $column){
                                                                    $val = isset($json[$column["id"]]) ? $json[$column["id"]] : NULL;
                                                                    if($val != NULL){
                                                                        ?><span><?php echo $column["name"]." <strong>".$val."</strong>"; ?></span><?php
                                                                    }
                                                                }
                                                            }
                                                        }else{
                                                            $features = ($product["features"]) ? explode("\n",$product["features"]) : [];
                                                            foreach($features AS $fea){
                                                                ?><span><?php echo $fea; ?></span><?php
                                                            }
                                                        }

                                                        if($features){
                                                            ?>
                                                            <div class="paketline"></div>
                                                            <?php
                                                        }
                                                    ?>
                                                </div>
                                                <div class="clear"></div>

                                                <input type="hidden" id="product_<?php echo $product["id"]; ?>_name" value="<?php echo $product["title"]; ?>">
                                                <select id="product_<?php echo $product["id"]; ?>_price">
                                                    <?php
                                                        foreach($prices AS $k=>$price){

                                                            if($foreign_user) $payable =$price["payable"];
                                                            else $payable = $price["taxed_payable"];
                                                            $payable = Money::formatter_symbol($payable,$price["cid"]);

                                                            if($price["amount"]>0){
                                                                $amount     = Money::formatter_symbol($price["amount"],$price["cid"],!$product["override_usrcurrency"]);
                                                                $period = View::period($price["time"],$price["period"]);
                                                            }else{
                                                                $amount = ___("needs/free-amount");
                                                                $period = NULL;
                                                            }
                                                            $show_price = $amount;
                                                            if($period) $show_price .= " (".$period.")";
                                                            ?>
                                                            <option value="<?php echo $k; ?>" data-payable="<?php echo $payable; ?>"><?php echo $show_price; ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                                <?php if($product["haveStock"]): ?>
                                                    <a href="javascript:upgradeProduct(<?php echo $product["id"]; ?>);void 0;" class="gonderbtn" id="product_upgrade_<?php echo $product["id"]; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?php echo __("website/account_products/button-upgrade"); ?></a>
                                                <?php else: ?>
                                                    <a id="sunucutukenbtn" class="gonderbtn"><i class="fa fa-ban" aria-hidden="true"></i> <?php echo __("website/products/out-of-stock2"); ?></a>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <?php
                            }
                        ?>

                    </div>

                <?php else: ?>
                    <div class="clear"></div>
                    <div id="upgrade-product-none">
                        <?php echo __("website/account_products/ugrade-product-none"); ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    <?php endif; ?>

    <?php if($proanse["status"] == "active" && $proanse["period"] != "none"): ?>
        <div id="iptaltalebi" class="tabcontent">
            <div class="tabcontentcon">
                <div class="red-info" style="margin-bottom:20px;">
                    <div class="padding15">
                        <i class="fa fa-meh-o" aria-hidden="true"></i>
                        <p><?php echo __("website/account_products/canceled-desc"); ?></p>
                    </div>
                </div>
                <form action="<?php echo $links["controller"]; ?>" method="post" id="CanceledProduct">
                    <input type="hidden" name="operation" value="canceled_product">

                    <textarea name="reason" cols="" rows="3" placeholder="<?php echo __("website/account_products/canceled-reason"); ?>"></textarea>
                    <select name="urgency">
                        <option value="now"><?php echo __("website/account_products/canceled-urgency-now"); ?></option>
                        <option value="period-ending"><?php echo __("website/account_products/canceled-urgency-period-ending"); ?></option>
                    </select>
                    <a href="javascript:void(0);" class="redbtn gonderbtn mio-ajax-submit" mio-ajax-options='{"result":"CanceledProduct_submit","waiting_text":"<?php echo addslashes(__("website/others/button5-pending")); ?>"}'><?php echo __("website/account_products/canceled-button"); ?></a>
                    <div class="clear"></div>
                </form>
                <div id="CanceledProduct_success" style="display: none;">
                    <div style="margin-top:30px;margin-bottom:70px;text-align:center;">
                        <i style="font-size:70px;" class="fa fa-check"></i>
                        <h5><?php echo __("website/account_products/canceled-sent"); ?></h5>
                        <br>
                    </div>
                </div>
                <script type="text/javascript">
                    function CanceledProduct_submit(result) {
                        if(result != ''){
                            var solve = getJson(result);
                            if(solve !== false){
                                if(solve.status == "error"){
                                    if(solve.for != undefined && solve.for != ''){
                                        $("#CanceledProduct "+solve.for).focus();
                                        $("#CanceledProduct "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                        $("#CanceledProduct "+solve.for).change(function(){
                                            $(this).removeAttr("style");
                                        });
                                    }
                                    if(solve.message != undefined && solve.message != '')
                                        alert_error(solve.message,{timer:3000});
                                }else if(solve.status == "successful"){
                                    $("#CanceledProduct").fadeOut(400,function(){
                                        $("#CanceledProduct_success").fadeIn(400);
                                        $("html,body").animate({scrollTop:200},600);
                                    });
                                }
                            }else
                                console.log(result);
                        }
                    }
                </script>
            </div>
        </div>
    <?php endif; ?>


    <div class="clear"></div>
</div>