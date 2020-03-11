<?php defined('CORE_FOLDER') OR exit('You can not get in here!');
    $wide_content   = true;

    $renewalDateSub     = substr($proanse["renewaldate"],0,4);
    $duedateSub         = substr($proanse["duedate"],0,4);

    include $tpath."common-needs.php";
    $hoptions = ["datatables"];




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
<div class="mpanelrightcon">

    <div class="mpaneltitle">
        <div class="sayfayolu"><?php include $tpath."inc".DS."panel-breadcrumb.php"; ?></div>
        <h4><strong><i class="fa fa-check" aria-hidden="true"></i> <?php echo $proanse["name"];?></strong></h4>
    </div>

    <ul class="tab">
        <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'detail')" data-tab="1"><i class="fa fa-info" aria-hidden="true"></i> <?php echo __("website/account_products/tab-detail"); ?></a></li>

        <?php if(isset($addons) && $addons): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'addons')" data-tab="addons"><i class="fa fa-rocket" aria-hidden="true"></i> <?php echo __("website/account_products/tab-addons"); ?></a></li>
        <?php endif; ?>

        <?php if(isset($requirements) && $requirements): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'requirements')" data-tab="requirements"><i class="fa fa-check-square" aria-hidden="true"></i> <?php echo __("website/account_products/tab-requirements"); ?></a></li>
        <?php endif; ?>

        <?php if($proanse["status"] == "active" && $proanse["period"] != "none"): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'iptaltalebi')" data-tab="cancellation"><i class="fa fa-ban" aria-hidden="true"></i> <?php echo __("website/account_products/cancellation-request"); ?></a></li>
        <?php endif; ?>

    </ul>

    <div id="detail" class="tabcontent">

        <div<?php echo $change_domain ? ' id="block_modulewidth50"' : ''; ?> class="hizmetblok">

            <div class="cpanelebmail">
                <i style="font-size:100px;line-height:50px;color:#8bc34a;" class="ion-ribbon-b"></i>
                <h4><strong><?php echo $proanse["name"]; ?></strong></h4>

                <?php if(isset($options["domain"]) && $options["domain"] != ''): ?>
                    <H5 style="margin-top:15px;"><?php echo __("website/account_products/license-domain"); ?><br>
                        <strong><?php echo $options["domain"]; ?></strong></H5>
                <?php endif; ?>

                <?php if(isset($options["code"]) && $options["code"] != ''): ?>
                    <H5 style="margin-top:15px;"><?php echo __("website/account_products/license-code"); ?><br>
                        <strong><?php echo $options["code"]; ?></strong></H5>
                <?php endif; ?>


                <?php if(isset($download_link) && $download_link != ''): ?>
                    <a <?php echo $proanse["status"] != "active" ? "" : 'href="'.$download_link.'"'; ?> class="<?php echo $proanse["status"] != "active" ? "graybtn" : "yesilbtn"; ?> gonderbtn"><?php echo __("website/account_products/download-button"); ?></a>
                <?php endif; ?>

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

        <div<?php echo $change_domain ? ' id="block_modulewidth50"' : ''; ?> class="hizmetblok">
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
                    <td><strong><?php echo __("website/account_products/license-status"); ?></strong></td>
                    <td><?php echo $product_situations[$proanse["status"]]; ?></td>
                </tr>
                <tr>
                    <td><strong><?php echo __("website/account_products/payment-period"); ?></strong></td>
                    <td><?php echo View::period($proanse["period_time"],$proanse["period"]); ?></td>
                </tr>

                <?php if($proanse["period"] != "none" && $renewalDateSub != "1881" && $renewalDateSub != "1970"): ?>
                    <tr>
                        <td><strong><?php echo __("website/account_products/last-paid-date"); ?></strong></td>
                        <td><?php echo DateManager::format("d/m/Y",$proanse["renewaldate"]); ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><strong><?php echo __("website/account_products/purchase-date"); ?></strong></td>
                    <td><?php echo DateManager::format("d/m/Y",$proanse["cdate"]); ?></td>
                </tr>

                <?php if($duedateSub != "1881"): ?>
                    <tr>
                        <td><strong><?php echo __("website/account_products/last-payment-date"); ?></strong></td>
                        <td><?php echo DateManager::format("d/m/Y",$proanse["duedate"]); ?></td>
                    </tr>
                <?php endif; ?>

                </tr>
                <tr align="center" class="tutartd">
                    <td colspan="2"><strong><?php echo __("website/account_products/amount"); ?> : <?php echo $amount; ?></strong></td>
                </tr>
            </table>

        </div>

        <?php
            if($change_domain){
                ?>
                <div class="clear"></div>
                <div class="block_module_details">
                    <div class="hizmetblok" id="block_module_details_con">
                        <div class="block_module_details-title formcon">
                            <h4><?php echo __("website/account_products/block-change-domain"); ?></h4>
                        </div>

                        <form action="<?php echo $links["controller"]; ?>" method="post" id="changeDomainForm" onsubmit="$('#changeDomainForm_btn').click(); return false;">
                            <input type="hidden" name="operation" value="change_software_domain">
                            <div class="formcon">
                                <div class="yuzde30"><?php echo __("website/account_products/block-change-domain-define-new-domain"); ?></div>
                                <div class="yuzde70">
                                    <input type="text" name="domain" placeholder="example.com">
                                </div>
                            </div>

                            <div class="clear"></div>
                            <a class="gonderbtn yesilbtn" href="javascript:void 0;" id="changeDomainForm_btn"><?php echo ___("needs/button-apply"); ?></a>
                        </form>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $("#changeDomainForm_btn").click(function(){
                                    MioAjaxElement(this,{
                                        "result":"changeDomainForm_handler",
                                        "waiting_text":"<?php echo addslashes(__("website/others/button5-pending")); ?>",
                                    });
                                });
                            });
                            
                            function changeDomainForm_handler(result){
                                if(result !== ''){
                                    var solve = getJson(result);
                                    if(solve !== false){
                                        if(solve.status == "error"){
                                            if(solve.for != undefined && solve.for != ''){
                                                $("#changeDomainForm "+solve.for).focus();
                                                $("#changeDomainForm "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                                $("#changeDomainForm "+solve.for).change(function(){
                                                    $(this).removeAttr("style");
                                                });
                                            }
                                            if(solve.message != undefined && solve.message !== '')
                                                alert_error(solve.message,{timer:3000});
                                        }else if(solve.status == "successful"){
                                            window.location.href = '<?php echo $links["controller"]; ?>';
                                        }
                                    }else
                                        console.log(result);
                                }
                            }
                        </script>


                    </div>
                </div>
                <?php
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
                        <i style="font-size:80px;color:green;" class="fa fa-check"></i>
                        <h4><?php echo __("website/account_products/canceled-sent"); ?></h4>
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