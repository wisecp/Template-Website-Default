<?php defined('CORE_FOLDER') OR exit('You can not get in here!');
    $module_config  = isset($module_con) && $module_con ? $module_con->config : [];
    $supported      = isset($module_config["supported"]) ? $module_config["supported"] : [];
    $wide_content   = true;
    $proanse_amount = $amount;
    $panel_type     = isset($options["panel_type"]) ? $options["panel_type"] : false;
    $disk_limit     = isset($options["disk_limit"]) ? Filter::numbers($options["disk_limit"]) : false;
    $bandwidth_limit = isset($options["bandwidth_limit"]) ? Filter::numbers($options["bandwidth_limit"]) : false;
    $email_limit     = isset($options["email_limit"]) ? Filter::numbers($options["email_limit"]) : false;
    $database_limit  = isset($options["database_limit"]) ? Filter::numbers($options["database_limit"]) : false;
    $addons_limit    = isset($options["addons_limit"]) ? Filter::numbers($options["addons_limit"]) : false;
    $subdomain_limit = isset($options["subdomain_limit"]) ? Filter::numbers($options["subdomain_limit"]) : false;
    $ftp_limit       = isset($options["ftp_limit"]) ? Filter::numbers($options["ftp_limit"]) : false;
    $park_limit      = isset($options["park_limit"]) ? Filter::numbers($options["park_limit"]) : false;
    $max_email_per_hour = isset($options["max_email_per_hour"]) ? Filter::numbers($options["max_email_per_hour"]) : false;
    $cpu_limit = isset($options["cpu_limit"]) ? Filter::html_clear($options["cpu_limit"]) : false;
    $server_features = isset($options["server_features"]) ? $options["server_features"] : [];
    $dns             = isset($options["dns"]) ? $options["dns"] : [];
    $ftp_raw         = isset($options["ftp_raw"]) ? nl2br($options["ftp_raw"]) : NULL;
    $ftp_info        = isset($options["ftp_info"]) && $options["ftp_info"] ? $options["ftp_info"] : [];
    $domain          = isset($options["domain"]) ? $options["domain"] : false;
    $creation_info   = isset($options["creation_info"]) ? $options["creation_info"] : [];
    if(isset($creation_info["reseller"]) && $creation_info["reseller"]){
        $reseller         = true;
        if(isset($creation_info["disk_limit"])) $disk_limit = Filter::numbers($creation_info["disk_limit"]);
        if(isset($creation_info["bandwidth_limit"])) $bandwidth_limit = Filter::numbers($creation_info["bandwidth_limit"]);
    }else
        $reseller = false;

    if($disk_limit) $disk_limit = FileManager::formatByte(FileManager::converByte($disk_limit."MB"));
    if($bandwidth_limit) $bandwidth_limit = FileManager::formatByte(FileManager::converByte($bandwidth_limit."MB"));

    $panel_name      = $panel_type;
    $panel_logo      = false;
    $buttons         = [];

    if(isset($module_con) && method_exists($module_con,"panel_links_for_client"))
        $buttons    = $module_con->panel_links_for_client();

    if($panel_name == "other" || !isset($server)){
        $panel_name = __("admin/products/add-new-product-hosting-panel-type-other");
        $panel_link = isset($options["panel_link"]) ? $options["panel_link"] : '';
        if($panel_link)
            $buttons["panel"] = [
                'url' => $panel_link,
                'name' =>__("website/account_products/login-panel"),
                'class' => "mavibtn",
            ];

    }
    elseif($panel_name == "cPanel"){
        if($reseller){
            $panel_logo = $tadress."images/whmlogo.jpg";
            $panel_name = "WHM";
        }else{
            $panel_logo = $tadress."images/cpanel.jpg";
        }

        if(isset($buttons["panel"])) $buttons["panel"]['class'] = "turuncbtn";
        if(isset($buttons["mail"])) $buttons["mail"]['class'] = "mavibtn";

    }
    elseif($panel_name == "Plesk"){
        $panel_logo = $tadress."images/plesk-logo.png";
        if(isset($buttons["panel"])) $buttons["panel"]['class'] = "mavibtn";
        if(isset($buttons["mail"])) $buttons["mail"]['class'] = "yesilbtn";
    }
    elseif($panel_name == "DirectAdmin" && $buttons){
        $panel_logo = $tadress."images/directadmin.png";
        if(isset($buttons["panel"])) $buttons["panel"]['class'] = "mavibtn";
    }else{
        if(isset($buttons["panel"])) $buttons["panel"]['class'] = "mavibtn";
        if(isset($buttons["mail"])) $buttons["mail"]['class'] = "yesilbtn";
    }

    include $tpath."common-needs.php";
    $hoptions = ["datatables","jquery-ui"];
?>

<link rel="stylesheet" type="text/css" href="<?php echo $sadress; ?>assets/style/progress-circle.css">
<style type="text/css">
    #nserverinfo {
        font-size: 15px;
        padding: 12px;
        background: none;
        border-bottom: 1px solid #eee;
        color: #009595;
        background: #efefef;
        background: -moz-linear-gradient(top,#efefef 0%,#fff 100%);
        background: -webkit-linear-gradient(top,#efefef 0%,#fff 100%);
        background: linear-gradient(to bottom,#efefef 0%,#fff 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#efefef',endColorstr='#ffffff',GradientType=0);
    }
    #hostserverblok {width:48%;text-align:center;border:none;}
    @media only screen and (min-width:320px) and (max-width:1025px) {
        #hostserverblok {width:100%;    margin-bottom: 20px;}
    }
</style>
<script type="text/javascript">
    function password_check(id){
        var value = $("#"+id).val();
        var check = checkStrength(value);
        $("#strength_"+id+" div").css("display","none");
        $("#strength_"+id+" #"+check).css("display","block");
    }

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

    function deleteEmail(address) {
        swal({
            title: '<?php echo __("website/account_products/delete-email-modal-title"); ?>',
            html: "<?php echo __("website/account_products/delete-email-modal-desc"); ?> "+address,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?php echo __("website/account_products/delete-email-modal-ok"); ?>',
            cancelButtonText: '<?php echo __("website/account_products/delete-email-modal-no"); ?>'
        }).then(function(){

            var res = MioAjax({
                action:"<?php echo $links["controller"];?>",
                method:"POST",
                data:{operation:"hosting_delete_email",address:address}
            },true);

            if(res != ''){
                var solve = getJson(res);
                if(solve && typeof solve == "object"){
                    if(solve.status == "error"){
                        swal({
                            title: '<?php echo __("website/account_products/delete-email-modal-error") ?>',
                            text: solve.message,
                            type: 'error',
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    }else if(solve.status == "successful"){
                        var timer = 1500;
                        setTimeout(function(){
                            var link = "<?php echo $links["controller"]; ?>";
                            link = sGET("tab","emails",link);
                            link = sGET("accordion",2,link);
                            window.location.href = link;
                        },timer);
                        swal({
                            title: '<?php echo __("website/account_products/delete-email-modal-deleted") ?>',
                            text: '<?php echo __("website/account_products/delete-email-modal-successful"); ?>',
                            type: 'success',
                            showConfirmButton: false,
                            timer: timer,
                        });
                    }
                }else
                    console.log(res);
            }


        });
    }

    function editEmail(k){
        open_modal("EmailManage_modal");
        var email = $("#editEmail_"+k+" input[name=email]").val();
        var quota = $("#editEmail_"+k+" input[name=quota]").val();
        $("#EmailManage-email").html(email);
        $("#EmailManage input[name=email]").val(email);
        $("#EmailManage input[name=quota]").val(quota);
        $("#edit_quota_unlimited").prop("checked",quota == '');
    }

    var emails_table,forwards_table,unlimited_text = '<?php echo __("website/account_products/email-quota-unlimited"); ?>';
    $(document).ready(function(){

        emails_table = $("#emails_table").DataTable({
            "columnDefs": [
                {
                    "targets": [0],
                    "visible":false,
                }
            ],
            responsive: true,
            paging: false,
            info:     false,
            searching: false,
            "language":{
                "url":"<?php echo APP_URI."/".___("package/code")."/datatable/lang.json";?>"
            }
        });

        forwards_table = $("#forwards_table").DataTable({
            "columnDefs": [
                {
                    "targets": [0],
                    "visible":false,
                }
            ],
            responsive: true,
            paging: false,
            info:     false,
            searching: false,
            "language":{
                "url":"<?php echo APP_URI."/".___("package/code")."/datatable/lang.json";?>"
            }
        });


        var tab = gGET("tab");
        if(tab == '' || tab == undefined){
            $(".tablinks:eq(0)").click();
        }else{
            $(".tablinks[data-tab='"+tab+"']").click();
        }

        var accordionx = gGET("accordion");
        if(accordionx == null){
            accordionx = false;
        }else
            accordionx -=1;

        $( "#accordion" ).accordion({
            heightStyle: "content",
            active:accordionx,
            collapsible: true,
        });

        setTimeout(function(){
            var request = MioAjax({
                action:"<?php echo $links["controller"]; ?>",
                method:"POST",
                data:{inc:"get_hosting_informations"},
            },true,true);

            request.done(function(result){
                if(result != ''){
                    var solve = getJson(result);
                    if(solve !== false){
                        if(solve.usage != undefined && solve.usage){
                            var bar_wrap;

                            if(solve.usage.disk_used_percent != undefined){
                                $("#disk_used_percent").html(solve.usage.disk_used_percent);
                                $("#disk_bar .bar-loading").replaceWith('{used} / {limit}');
                                bar_wrap     = $("#disk_bar").html();
                                bar_wrap     = bar_wrap.replace("{percent}",solve.usage.disk_used_percent);
                                bar_wrap     = bar_wrap.replace("{used}",solve.usage.disk_used_format);
                                bar_wrap     = bar_wrap.replace("{limit}",solve.usage.disk_limit_format);
                                $("#disk_bar").html(bar_wrap);
                            }

                            if(solve.usage.bandwidth_used_percent != undefined){
                                $("#bandwidth_bar .bar-loading").replaceWith('{used} / {limit}');
                                $("#bandwidth_used_percent").html(solve.usage.bandwidth_used_percent);
                                bar_wrap     = $("#bandwidth_bar").html();

                                bar_wrap     = bar_wrap.replace("{percent}",solve.usage.bandwidth_used_percent);
                                bar_wrap     = bar_wrap.replace("{used}",solve.usage.bandwidth_used_format);
                                bar_wrap     = bar_wrap.replace("{limit}",solve.usage.bandwidth_limit_format);
                                $("#bandwidth_bar").html(bar_wrap);
                            }
                        }else
                            $(".use-progressbar").css("display","none");

                        if(solve.mail_domains != undefined){
                            $(".mailDomains").html('');
                            $(solve.mail_domains).each(function(k,v){
                                $(".mailDomains").append('<option value="'+v+'">'+v+'</option>');
                            });
                        }

                        if(solve.emails != undefined){
                            emails_table.clear().draw();
                            $(solve.emails).each(function(k,v){

                                var limit = v.limit == "unlimited" ? unlimited_text : v.limit;
                                var quota = v.limit_mb == "unlimited" ? '' : v.limit_mb;

                                emails_table.row.add([
                                    k,
                                    v.email+
                                    '<div id="editEmail_'+k+'">'+
                                    '<input type="hidden" name="email" value="'+v.email+'" >'+
                                    '<input type="hidden" name="quota" value="'+quota+'" >'+
                                    '</div>',
                                    v.used+"  / "+limit,
                                    '<a href="javascript:editEmail('+k+');void 0;" title="<?php echo __("website/account_products/hosting-emails-table-manage"); ?>" class="incelebtn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a href="javascript:void 0;" onclick="deleteEmail(\''+v.email+'\');" style="margin-left:5px;" title="<?php echo __("website/account_products/hosting-emails-table-delete"); ?>" class="incelebtn"><i class="fa fa-trash" aria-hidden="true"></i></a>',
                                ]).draw();
                            });
                        }

                        if(solve.forwards != undefined){
                            forwards_table.clear().draw();
                            $(solve.forwards).each(function(k,v){
                                forwards_table.row.add([
                                    k,
                                    v.dest,
                                    v.forward,
                                    '<a href="javascript:void(0);" onclick="deleteForward(\''+v.dest+'\',\''+v.forward+'\');" style="margin-left:5px;" title="<?php echo __("website/account_products/hosting-emails-table-delete"); ?>" class="incelebtn"><i class="fa fa-trash" aria-hidden="true"></i></a>'
                                ]).draw();
                            });
                        }

                    }else
                        console.log(result);
                }
            });

        },10);

    });
</script>
<div id="EmailManage_modal" style="display: none;" data-izimodal-title="<span id='EmailManage-email'>?</span> - <?php echo __("website/account_products/hosting-emails-table-manage"); ?>">
    <div class="padding20">

        <form action="<?php echo $links["controller"]; ?>" method="post" id="EmailManage">
            <input type="hidden" name="operation" value="hosting_update_email">
            <input type="hidden" name="email" value="">

            <div class="formcon">
                <div class="yuzde30">
                    <?php echo __("website/account_products/email-set-password"); ?>
                </div>

                <div class="yuzde70">
                    <input name="password" onFocus="this.select()" type="text" placeholder="<?php echo __("website/account_products/hosting-add-new-email-password"); ?>" id="EmailManage_password" rel="aselect">

                    <a style="margin-top:3px;" href="javascript:void(0);" onclick="$('#EmailManage_password').val(randString({characters:'A-Z,a-z,0-9,#'}));password_check('EmailManage_password');" class="incelebtn"><i class="fa fa-refresh"></i> <?php echo __("website/account_products/new-random-password"); ?></a>
                    <div id="strength_EmailManage_password" class="yuzde50" style="margin-top:-3px;">
                        <div id="weak" style="display:none;"><?php echo ___("needs/password-weak"); ?></div>
                        <div id="good" style="display:none"><?php echo ___("needs/password-good"); ?></div>
                        <div id="strong" style="display:none;"><?php echo ___("needs/password-strong"); ?></div>
                    </div>
                </div>

            </div>

            <div class="formcon">

                <div class="yuzde30">
                    <?php echo __("website/account_products/email-set-quota"); ?>
                </div>

                <div class="yuzde70">
                    <input name="quota" class="yuzde50" placeholder="<?php echo __("website/account_products/hosting-add-new-email-quota"); ?>" type="number" onkeydown="return FilterInput(event)" onpaste="handlePaste(event)" value="">

                    <input id="edit_quota_unlimited" class="checkbox-custom" name="unlimited" value="1" type="checkbox">
                    <label for="edit_quota_unlimited" class="checkbox-custom-label"><?php echo __("website/account_products/email-quota-unlimited"); ?></label>
                </div>

            </div>
            <div class="clear"></div>
            <div class="mailyonbtn">
                <a href="javascript:void(0);" id="EmailManage_submit" style="float:right;" class="yesilbtn gonderbtn"><?php echo __("website/account_products/email-update-button"); ?></a>
            </div>

            <div class="clear"></div>
        </form>
        <script type="text/javascript">
            $(document).ready(function(){

                $("#EmailManage_submit").on("click",function(){
                    MioAjaxElement($(this),{
                        waiting_text:'<?php echo addslashes(__("website/others/button5-pending")); ?>',
                        result:"EmailManage_handler",
                    });
                });

            });
            function EmailManage_handler(result) {
                if(result != ''){
                    var solve = getJson(result);
                    if(solve !== false){
                        if(solve.status == "error"){
                            if(solve.for != undefined && solve.for != ''){
                                $("#EmailManage "+solve.for).focus();
                                $("#EmailManage "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                $("#EmailManage "+solve.for).change(function(){
                                    $(this).removeAttr("style");
                                });
                            }
                            if(solve.message != undefined && solve.message != '')
                                alert_error(solve.message,{timer:5000});
                        }else if(solve.status == "successful"){
                            alert_success(solve.message,{timer:3000});
                            setTimeout(function(){
                                var link = "<?php echo $links["controller"]; ?>";
                                link = sGET('tab',"emails",link);
                                link = sGET('accordion',2,link);
                                window.location.href = link;
                            },3000);
                        }
                    }else
                        console.log(result);
                }
            }
            $(document).ready(function(){
                $('#EmailManage_password').keyup(function(){
                    password_check("EmailManage_password");
                });
            });
        </script>

        <div class="clear"></div>

        <div class="clear"></div>
    </div>
</div>

<div class="mpanelrightcon">

    <div class="mpaneltitle">
        <div class="sayfayolu"><?php include $tpath."inc".DS."panel-breadcrumb.php"; ?></div>
        <h4><strong><i class="fa fa-cube"></i> <?php echo $proanse["name"]; ?></strong></h4>
    </div>


    <ul class="tab">
        <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'ozet')" data-tab="1"><i class="fa fa-info" aria-hidden="true"></i> <?php echo __("website/account_products/hosting-tab1"); ?></a></li>

        <?php if(isset($addons) && $addons): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'addons')" data-tab="addons"><i class="fa fa-rocket" aria-hidden="true"></i> <?php echo __("website/account_products/tab-addons"); ?></a></li>
        <?php endif; ?>

        <?php if(isset($requirements) && $requirements): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'requirements')" data-tab="requirements"><i class="fa fa-check-square" aria-hidden="true"></i> <?php echo __("website/account_products/tab-requirements"); ?></a></li>
        <?php endif; ?>


        <?php if($proanse["status"] == "active" && $upgrade): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'upgrade')" data-tab="upgrade"><i class="ion-speedometer" aria-hidden="true"></i> <?php echo __("website/account_products/hosting-tab-upgrade"); ?></a></li>
        <?php endif; ?>

        <?php if($proanse["status"] == "active" && isset($server) && $server["status"] == "active"): ?>
            <?php if(in_array("manage-email-account",$supported)): ?>
                <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'epostayonet')" data-tab="emails"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?php echo __("website/account_products/hosting-tab-emails"); ?></a></li>
            <?php endif; ?>
            <?php if(in_array("change-password",$supported)): ?>
                <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'sifredegistir')" data-tab="password"><i class="fa fa-key" aria-hidden="true"></i> <?php echo __("website/account_products/hosting-tab-password"); ?></a></li>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($proanse["status"] == "active" && $proanse["period"] != "none"): ?>
            <li><a href="javascript:void(0)" class="tablinks" onclick="openTab(this, 'iptaltalebi')" data-tab="cancellation"><i class="fa fa-ban" aria-hidden="true"></i> <?php echo __("website/account_products/cancellation-request"); ?></a></li>
        <?php endif; ?>

    </ul>

    <div id="ozet" class="tabcontent">

        <div class="hizmetblok" id="hostserverblok">
            <div class="cpanelebmail">

                <?php
                    if($panel_logo){
                        ?>
                        <img style="width:210px;" src="<?php echo $panel_logo; ?>" width="auto" height="auto">
                        <?php
                    }else{
                        ?>
                        <i style="font-size:100px;" class="ion-ios-cloud"></i><div class="clear"></div>
                        <?php
                    }
                ?>
                <h4><?php echo $domain; ?></h4>
                <?php
                    if(isset($options["server_features"]) && is_array($options["server_features"]))
                        echo '<h5 style="font-weight:normal;margin:0px;">('.implode(" + ",$options["server_features"]).')</h5><br>';

                    if($buttons){
                        foreach($buttons AS $b_type=>$b_value){
                            ?>
                            <a target="_blank" href="<?php echo $b_value["url"]; ?>" class="<?php echo isset($b_value["class"]) ? $b_value["class"].' ' : ''; ?>gonderbtn"><?php echo $b_value["name"]; ?></a><br>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>

        <?php if(isset($server) && ($proanse["status"] == "active" || $proanse["status"] == "suspended") && in_array("disk-bandwidth-usage",$supported)): ?>
            <div class="hizmetblok use-progressbar" id="hostserverblok">

                <div style="margin-bottom:20px;font-size:16px;margin:0px 15px;display:inline-block" id="disk_bar">
                    <h5 style="font-size:16px;"><strong><?php echo __("admin/orders/hosting-usage-disk"); ?></strong></h5>
                    <div class="clear"></div>
                    <div class="progress-circle progress-{percent}"><span id="disk_used_percent">0</span></div>
                    <div class="clear"></div>
                    <div style="-webkit-filter: grayscale(100%);filter: grayscale(100%);" class="bar-loading"><img src="<?php echo $sadress; ?>assets/images/loading.gif"></div>
                </div>

                <div style="margin-bottom:20px;font-size:16px;margin:0px 15px;display:inline-block" id="bandwidth_bar">
                    <h5 style="font-size:16px;"><strong><?php echo __("admin/orders/hosting-usage-bandwidth"); ?></strong></h5>
                    <div class="clear"></div>
                    <div class="progress-circle progress-{percent}"><span id="bandwidth_used_percent">0</span></div>
                    <div class="clear"></div>
                    <div style="-webkit-filter: grayscale(100%);filter: grayscale(100%);" class="bar-loading"><img src="<?php echo $sadress; ?>assets/images/loading.gif"></div>
                </div>

                <div class="clear"></div>

                <?php if($proanse["period"] != "none" && $upgrade && $proanse["status"] == "active"): ?>
                    <a style="width:50%;padding: 12px 0px;margin-top:30px;" href="javascript:$('a[data-tab=upgrade]').click();void 0;" id="updownbtn" class="graybtn gonderbtn"><i class="ion-speedometer"></i> <?php echo __("website/account_products/hosting-button-go-to-upgrade"); ?></a>
                <?php endif; ?>
            </div>

            <div class="clear"></div>
        <?php endif; ?>


        <div class="hizmetblok">
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

                <?php if(substr($proanse["renewaldate"],0,4) != "1881"): ?>
                    <tr>
                        <td><strong><?php echo __("website/account_products/renewal-date"); ?></strong></td>
                        <td><?php echo DateManager::format("d/m/Y",$proanse["renewaldate"]); ?></td>
                    </tr>
                <?php endif; ?>

                <?php if(substr($proanse["duedate"],0,4) != "1881"): ?>
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


        <div class="hizmetblok">
            <table width="100%" border="0">
                <tr>
                    <td colspan="2" bgcolor="#ebebeb" ><strong><?php echo __("website/account_products/hosting-features"); ?></strong></td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/hosting-disk-limit"); ?></strong></td>
                    <td><?php echo $disk_limit ? $disk_limit : __("website/account_products/unlimited"); ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/bandwidth-limit"); ?></strong></td>
                    <td><?php echo $bandwidth_limit ? $bandwidth_limit : __("website/account_products/unlimited"); ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/email-limit"); ?></strong></td>
                    <td><?php echo $email_limit ? $email_limit." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/database-limit"); ?></strong></td>
                    <td><?php echo $database_limit || ($database_limit != '' && $database_limit == 0) ? ((int) $database_limit)." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/addons-limit"); ?></strong></td>
                    <td><?php echo $addons_limit || ($addons_limit != '' && $addons_limit == 0) ? ((int) $addons_limit)." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                </tr>

                <tr>
                    <td><strong><?php echo __("website/account_products/subdomain-limit"); ?></strong></td>
                    <td><?php echo $subdomain_limit != '' ? ((int) $subdomain_limit)." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                </tr>


                <tr align="center" class="tutartd">
                    <td colspan="2">
                        <a href="javascript:open_modal('otherLimits');void 0;"><?php echo __("website/account_products/view-all"); ?></a>
                    </td>
                </tr>

            </table>

            <div id="otherLimits" data-izimodal-title="<?php echo __("website/account_products/hosting-all-features"); ?>" style="display: none;">
                <div class="padding20">

                    <table width="100%" border="0">

                        <tr>
                            <td><strong><?php echo __("website/account_products/hosting-disk-limit"); ?></strong></td>
                            <td><?php echo $disk_limit ? $disk_limit : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/bandwidth-limit"); ?></strong></td>
                            <td><?php echo $bandwidth_limit ? $bandwidth_limit : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/email-limit"); ?></strong></td>
                            <td><?php echo $email_limit ? $email_limit." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/database-limit"); ?></strong></td>
                            <td><?php echo $database_limit || ($database_limit != '' && $database_limit == 0) ? $database_limit." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/addons-limit"); ?></strong></td>
                            <td><?php echo $addons_limit || ($addons_limit != '' && $addons_limit == 0) ? ((int) $addons_limit)." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/subdomain-limit"); ?></strong></td>
                            <td><?php echo $subdomain_limit != '' ? ((int) $subdomain_limit)." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/ftp-limit"); ?></strong></td>
                            <td><?php echo $ftp_limit != '' ? ((int) $ftp_limit)." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/park-limit"); ?></strong></td>
                            <td><?php echo $park_limit || ($park_limit != '' && $park_limit == 0) ? ((int) $park_limit)." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/max-email-per-hour"); ?></strong></td>
                            <td><?php echo $max_email_per_hour ? $max_email_per_hour." ".__("website/account_products/count") : __("website/account_products/unlimited"); ?></td>
                        </tr>

                        <tr>
                            <td><strong><?php echo __("website/account_products/cpu-limit"); ?></strong></td>
                            <td><?php echo $cpu_limit; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="clear"></div>
            </div>


        </div>

        <div class="hizmetblok">
            <table width="100%" border="0">
                <?php if($ftp_info || $ftp_raw): ?>
                    <tr>
                        <td bgcolor="#ebebeb" ><strong><?php echo __("website/account_products/ftp-informations"); ?></strong></td>
                    </tr>

                    <?php if($ftp_info): ?>
                        <?php if(isset($options["ftp_info"]["host"])): ?>
                            <tr>
                                <td><strong><?php echo __("website/account_products/ftp-server"); ?></strong>: <span class="selectalltext"><?php echo $options["ftp_info"]["host"]; ?></span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($options["ftp_info"]["port"])): ?>
                            <tr>
                                <td><strong><?php echo __("website/account_products/ftp-port"); ?></strong>: <?php echo $options["ftp_info"]["port"]; ?></td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($options["ftp_info"]["username"])): ?>
                            <tr>
                                <td><strong><?php echo __("website/account_products/ftp-username"); ?>: </strong><span class="selectalltext"><?php echo $options["ftp_info"]["username"]; ?></span></td>
                            </tr>
                        <?php endif; ?>

                        <?php if(isset($options["ftp_info"]["password"])): ?>
                            <tr>
                                <td><strong><?php echo __("website/account_products/ftp-password"); ?></strong>: <span class="selectalltext"><?php echo $options["ftp_info"]["password"]; ?></span></td>
                            </tr>
                        <?php endif; ?>
                    <?php else: ?>
                        <tr>
                            <td><?php echo $ftp_raw; ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>
                <tr>
                    <td bgcolor="#ebebeb" id="nserverinfo"><strong><?php echo __("website/account_products/name-servers"); ?></strong></td>
                </tr>

                <?php if(isset($dns["ns1"]) && $dns["ns1"]): ?>
                    <tr>
                        <td><strong>NS1</strong>: <?php echo $dns["ns1"]; ?></td>
                    </tr>
                <?php endif; ?>

                <?php if(isset($dns["ns2"]) && $dns["ns2"]): ?>
                    <tr>
                        <td><strong>NS2</strong>: <?php echo $dns["ns2"]; ?></td>
                    </tr>
                <?php endif; ?>

                <?php if(isset($dns["ns3"]) && $dns["ns3"]): ?>
                    <tr>
                        <td><strong>NS3</strong>: <?php echo $dns["ns3"]; ?></td>
                    </tr>
                <?php endif; ?>

                <?php if(isset($dns["ns4"]) && $dns["ns4"]): ?>
                    <tr>
                        <td><strong>NS4</strong>: <?php echo $dns["ns4"]; ?></td>
                    </tr>
                <?php endif; ?>

            </table>
        </div>

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
                        <?php echo __("website/account_products/hosting-upgrade-info"); ?>
                    </div>
                </div>

                <div class="formcon">
                    <div class="yuzde30" style="vertical-align:top;"><?php echo __("website/account_products/upgrade-order-statistics"); ?>:
                        <div class="clear"></div>
                        <span class="kinfo"><?php echo __("website/account_products/upgrade-order-statistics-info"); ?></span>
                    </div>
                    <div class="yuzde70">

                        <div class="yuzde50">
                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-product-name"); ?>:</span>
                                <strong><?php echo $proanse["name"]; ?></strong>
                            </div>

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-renewaldate"); ?>:</span>
                                <strong><?php echo DateManager::format("d.m.Y",$proanse["renewaldate"]); ?></strong>
                            </div>

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-duedate"); ?>:</span>
                                <strong> <?php echo DateManager::format("d.m.Y",$proanse["duedate"]); ?></strong>
                            </div>

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-times-used"); ?>:</span>
                                <strong><?php echo $upgrade_times_used ." ". ___("date/day"); ?></strong> <!--<?php echo $upgrade_times_used_amount; ?>)-->
                            </div>

                            <div class="formcon">
                                <span><?php echo __("website/account_products/upgrade-remaining-day"); ?>:</span>
                                <strong><?php echo $upgrade_remaining_day ." ". ___("date/day"); ?></strong> <!--(<strong style="color:red;"><?php echo $upgrade_remaining_amount; ?></strong>)-->
                            </div>
                        </div>

                    </div>
                </div>


                <?php if(isset($upgproducts) && $upgproducts["products"]): ?>

                    <div id="upgradeConfirm" data-izimodal-title="<?php echo __("website/account_products/hosting-tab-upgrade"); ?>" style="display: none">
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

                            var confirm_text = '<?php echo str_replace("\n","<br>",__("website/account_products/hosting-upgrade-confirm-text")); ?>';
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
                            if($upgproducts["products"]){
                            $keys = array_keys($upgproducts["products"]);
                            ?>$('#upgrade_selected_product option[value=<?php echo $keys[0]; ?>]').attr("selected",true).trigger("change");<?php
                            }
                            ?>
                        });
                    </script>

                    <div class="formcon">
                        <div class="yuzde30"><?php echo __("website/account_products/upgrade-select-packages"); ?>:
                            <div class="clear"></div>
                        </div>
                        <div class="yuzde70">
                            <select name="product" id="upgrade_selected_product" onchange="selected_category(this.options[this.selectedIndex].value);">
                                <?php
                                    if(isset($upgproducts) && $upgproducts){
                                        if($upgproducts["categories"]){
                                            foreach($upgproducts["categories"] AS $caid=>$val){
                                                ?>
                                                <option<?php echo isset($val["non-product"]) ? ' disabled' : ''; ?> value="<?php echo $caid; ?>"><?php echo $val["title"]; ?></option>
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
                            foreach($upgproducts["categories"] AS $caid=>$val){
                                $products   = isset($upgproducts["products"][$caid]) ? $upgproducts["products"][$caid] : false;
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

                                                <?php
                                                    $features = $product["features"];
                                                ?>
                                                <div class="clear"></div>
                                                <div class="products_features">
                                                    <?php echo nl2br($features); ?>
                                                </div>
                                                <div class="clear"></div>
                                                <?php
                                                    if($features){
                                                        ?>
                                                        <div class="paketline"></div>
                                                        <?php
                                                    }
                                                ?>

                                                <input type="hidden" id="product_<?php echo $product["id"]; ?>_name" value="<?php echo $product["title"]; ?>">
                                                <select id="product_<?php echo $product["id"]; ?>_price">
                                                    <?php
                                                        foreach($prices AS $k=>$price){

                                                            if($foreign_user) $payable =$price["payable"];
                                                            else $payable = $price["taxed_payable"];
                                                            $payable = Money::formatter_symbol($payable,$price["cid"]);

                                                            if($price["amount"]>0){
                                                                $period = View::period($price["time"],$price["period"]);
                                                                $amount     = Money::formatter_symbol($price["amount"],$price["cid"],!$product["override_usrcurrency"]);
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
                        <?php echo __("website/account_products/ugrade-package-none"); ?>
                    </div>
                <?php endif; ?>

            </div>

        </div>
    <?php endif; ?>

    <?php if($proanse["status"] == "active" && isset($server) && in_array("manage-email-account",$supported)): ?>
        <div id="epostayonet" class="tabcontent">
            <div class="tabcontentcon">

                <h5 style="margin:20px 0px;"><strong><?php echo __("website/account_products/hosting-emails-desc1"); ?></strong></h5>
                <ul>
                    <li><?php echo __("website/account_products/hosting-emails-feature1"); ?></li>
                    <li><?php echo __("website/account_products/hosting-emails-feature2"); ?></li>
                    <li><?php echo __("website/account_products/hosting-emails-feature3"); ?></li>
                </ul>
                <div id="accordion">

                    <h3><?php echo __("website/account_products/hosting-add-new-email"); ?></h3>
                    <div>
                        <form action="<?php echo $links["controller"]; ?>" method="post" id="addNewEmail">
                            <input type="hidden" name="operation" value="hosting_add_new_email">
                            <h5 style="margin:20px 0px;"><?php echo __("website/account_products/hosting-add-new-email-desc"); ?></h5>
                            <input name="username" class="yuzde50inpt" type="text" placeholder="<?php echo __("website/account_products/hosting-add-new-email-prefix"); ?>">
                            <select name="domain" class="yuzde50inpt mailDomains">
                            </select>
                            <input name="password" class="yuzde50inpt" type="text" rel="aselect" placeholder="<?php echo __("website/account_products/hosting-add-new-email-password"); ?>" id="email_password">
                            <div class="yuzde50" style="margin-top:7px;">
                                <a href="javascript:void(0);" onclick="$('#email_password').val(randString({characters:'A-Z,a-z,0-9,#'}));password_check('email_password');" class="incelebtn"><i class="fa fa-refresh"></i> <?php echo __("website/account_products/new-random-password"); ?></a>
                                <div id="strength_email_password" class="yuzde50">
                                    <div id="weak" style="display:none;"><?php echo ___("needs/password-weak"); ?></div>
                                    <div id="good" style="display:none"><?php echo ___("needs/password-good"); ?></div>
                                    <div id="strong" style="display:none;"><?php echo ___("needs/password-strong"); ?></div>
                                </div>
                            </div>
                            <input name="quota" class="yuzde50inpt" placeholder="<?php echo __("website/account_products/hosting-add-new-email-quota"); ?>" type="number" onkeydown="return FilterInput(event)" onpaste="handlePaste(event)">
                            <input id="quota_unlimited" class="checkbox-custom" name="unlimited" value="1" type="checkbox" >
                            <label style="margin-top:7px;" for="quota_unlimited" class="checkbox-custom-label"><span class="checktext"><?php echo __("website/account_products/email-quota-unlimited"); ?></span></label>
                            <span style="    font-size: 14px;  display:none;  margin: 15px 0px;    float: left;"><?php echo __("website/account_products/hosting-add-new-email-password-warning"); ?></span>
                            <a href="javascript:void(0);" class="yesilbtn gonderbtn mio-ajax-submit" mio-ajax-options='{"result":"addNewEmail_submit","waiting_text":"<?php echo addslashes(__("website/others/button5-pending")); ?>"}'><?php echo __("website/account_products/hosting-add-new-email-button"); ?></a>
                            <div class="clear"></div>
                        </form>
                        <div id="addNewEmail_success" style="display: none;">
                            <div style="margin-top:30px;margin-bottom:70px;text-align:center;">
                                <i style="font-size:80px;" class="fa fa-check"></i>
                                <h4 style="font-weight:bold;"><?php echo __("website/account_products/hosting-add-new-email-created"); ?></h4>
                                <br>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function(){
                                $('#email_password').keyup(function(){
                                    password_check("email_password");
                                });
                            });

                            function addNewEmail_submit(result){
                                if(result != ''){
                                    var solve = getJson(result);
                                    if(solve !== false){
                                        if(solve.status == "error"){
                                            if(solve.for != undefined && solve.for != ''){
                                                $("#addNewEmail "+solve.for).focus();
                                                $("#addNewEmail "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                                $("#addNewEmail "+solve.for).change(function(){
                                                    $(this).removeAttr("style");
                                                });
                                            }
                                            if(solve.message != undefined && solve.message != '')
                                                alert_error(solve.message,{timer:3000});
                                        }else if(solve.status == "successful"){
                                            $("#addNewEmail").fadeOut(400,function(){
                                                $("html,body").animate({scrollTop:200},600);
                                                $("#addNewEmail_success").fadeIn(400);
                                                setTimeout(function(){
                                                    var link = sGET("accordion",2);
                                                    window.location.href = link;
                                                },1000);
                                            });
                                        }
                                    }else
                                        console.log(result);
                                }

                            }
                            function FilterInput(event) {
                                var keyCode = ('which' in event) ? event.which : event.keyCode;

                                var isNotWanted = (keyCode == 69);
                                return !isNotWanted;
                            };
                            function handlePaste (e) {
                                var clipboardData, pastedData;

                                // Get pasted data via clipboard API
                                clipboardData = e.clipboardData || window.clipboardData;
                                pastedData = clipboardData.getData('Text').toUpperCase();

                                if(pastedData.indexOf('E')>-1) {
                                    //alert('found an E');
                                    e.stopPropagation();
                                    e.preventDefault();
                                }
                            };
                        </script>
                    </div>


                    <h3><?php echo __("website/account_products/hosting-emails"); ?></h3>
                    <div>
                        <table id="emails_table">
                            <thead style="background:#ebebeb;">
                            <tr>
                                <th>#</th>
                                <th align="center"><?php echo __("website/account_products/hosting-emails-table-email"); ?></th>
                                <th align="center"><?php echo __("website/account_products/hosting-emails-table-quota"); ?></th>
                                <th width="100" align="center"><?php echo __("website/account_products/hosting-emails-table-operation"); ?></th>
                            </tr>
                            </thead>
                            <tbody align="center" style="border-top:none;">

                            </tbody>
                        </table>


                    </div>

                    <?php
                        if(in_array("manage-email-forwards",$supported)){
                            ?>
                            <h3><?php echo __("website/account_products/hosting-email-forwards"); ?></h3>
                            <div>
                                <a href="javascript:open_modal('addNewMailForward');void 0;" class="green destekolsbtn lbtn"><?php echo __("website/account_products/hosting-email-forwards-add-new"); ?></a>
                                <div class="clear"></div>
                                <br />

                                <div id="addNewMailForward" data-izimodal-title="<?php echo __("website/account_products/hosting-email-forwards-add-new-title"); ?>" style="display: none">
                                    <div class="padding20">

                                        <form action="<?php echo $links["controller"]; ?>" method="post" id="addNewForward">
                                            <input type="hidden" name="operation" value="hosting_add_new_email_forward">

                                            <h5 style="margin:20px 0px;"><?php echo __("website/account_products/hosting-add-new-email-forward-desc"); ?></h5>
                                            <input name="email" class="yuzde50inpt" type="text" placeholder="<?php echo __("website/account_products/hosting-add-new-email-prefix"); ?>">
                                            <select name="domain" class="yuzde50inpt mailDomains">
                                                <?php
                                                    if(isset($mail_domains) && is_array($mail_domains)){
                                                        foreach ($mail_domains as $domain) {
                                                            ?><option value="<?php echo $domain; ?>">@<?php echo $domain; ?></option><?php
                                                        }
                                                    }
                                                ?>
                                            </select>

                                            <input name="forward" class="yuzde100" type="text" placeholder="<?php echo __("website/account_products/hosting-add-new-email-forward-label"); ?>">

                                            <div class="line"></div>

                                            <div class="mailyonbtn">
                                                <a href="javascript:void(0);" style="float:right;" class="yesilbtn gonderbtn" id="addNewMailForward_submit"><?php echo __("website/account_products/email-forward-update-button"); ?></a>
                                            </div>

                                            <div class="clear"></div>

                                        </form>
                                        <div id="addNewForward_success" style="display: none;">
                                            <div style="margin-top:30px;margin-bottom:70px;text-align:center;">
                                                <i style="font-size:80px;" class="fa fa-check"></i>
                                                <h4 style="font-weight:bold;"><?php echo __("website/account_products/hosting-email-forward-added"); ?></h4>
                                                <br>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $("#addNewMailForward_submit").on("click",function(){
                                                    MioAjaxElement($(this),{
                                                        waiting_text: '<?php echo addslashes(__("website/others/button5-pending")); ?>',
                                                        result:"addNewForward_submit",
                                                    });
                                                });
                                            });
                                            function deleteForward(dest, forward) {
                                                swal({
                                                    title: '<?php echo __("website/account_products/delete-email-forward-modal-title"); ?>',
                                                    text: "<?php echo __("website/account_products/delete-email-forward-modal-desc"); ?> "+dest,
                                                    type: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: '<?php echo __("website/account_products/delete-email-forward-modal-ok"); ?>',
                                                    cancelButtonText: '<?php echo __("website/account_products/delete-email-forward-modal-no"); ?>'
                                                }).then(function(){

                                                    var res = MioAjax({
                                                        action:"<?php echo $links["controller"];?>",
                                                        method:"POST",
                                                        data:{operation:"hosting_delete_email_forward",dest:dest,forward:forward}
                                                    },true);

                                                    if(res != ''){
                                                        var solve = getJson(res);
                                                        if(solve && typeof solve == "object"){
                                                            if(solve.status == "error"){
                                                                swal({
                                                                    title: '<?php echo __("website/account_products/delete-email-forward-modal-error") ?>',
                                                                    text: solve.message,
                                                                    type: 'error',
                                                                    showConfirmButton: false,
                                                                    timer: 3000,
                                                                });
                                                            }else if(solve.status == "successful"){
                                                                var timer = 1500;
                                                                setTimeout(function(){
                                                                    var link = "<?php echo $links["controller"]; ?>";
                                                                    link = sGET("tab","emails",link);
                                                                    link = sGET("accordion",3,link);
                                                                    window.location.href = link;
                                                                },timer);
                                                                swal({
                                                                    title: '<?php echo __("website/account_products/delete-email-forward-modal-deleted") ?>',
                                                                    text: '<?php echo __("website/account_products/delete-email-forward-modal-successful"); ?>',
                                                                    type: 'success',
                                                                    showConfirmButton: false,
                                                                    timer: timer,
                                                                });
                                                            }
                                                        }else
                                                            console.log(res);
                                                    }


                                                });
                                            }
                                            function addNewForward_submit(result) {
                                                if(result != ''){
                                                    var solve = getJson(result);
                                                    if(solve !== false){
                                                        if(solve.status == "error"){
                                                            if(solve.for != undefined && solve.for != ''){
                                                                $("#addNewForward "+solve.for).focus();
                                                                $("#addNewForward "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                                                $("#addNewForward "+solve.for).change(function(){
                                                                    $(this).removeAttr("style");
                                                                });
                                                            }
                                                            if(solve.message != undefined && solve.message != '')
                                                                alert_error(solve.message,{timer:3000});
                                                        }else if(solve.status == "successful"){
                                                            $("#addNewForward").fadeOut(400,function(){
                                                                $("#addNewForward_success").fadeIn(400);
                                                                setTimeout(function(){
                                                                    var link = sGET("tab","emails");
                                                                    link = sGET("accordion",3,link);
                                                                    window.location.href = link;
                                                                },1000);
                                                            });
                                                        }
                                                    }else
                                                        console.log(result);
                                                }
                                            }
                                        </script>
                                        <div class="clear"></div>
                                    </div>
                                </div>

                                <table id="forwards_table">
                                    <thead style="background:#ebebeb;">
                                    <tr>
                                        <th>#</th>
                                        <th align="center"><?php echo __("website/account_products/hosting-email-forwards-dest"); ?></th>
                                        <th align="center"><?php echo __("website/account_products/hosting-email-forwards-forward"); ?></th>
                                        <th align="center"><?php echo __("website/account_products/hosting-email-forwards-operation"); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody align="Center" style="border-top:none;">

                                    </tbody>
                                </table>

                            </div>
                            <?php
                        }
                    ?>


                </div>


            </div>


        </div>
    <?php endif; ?>

    <?php if($proanse["status"] == "active" && isset($server) && in_array("change-password",$supported)): ?>
        <div id="sifredegistir" class="tabcontent">
            <div class="tabcontentcon">
                <form action="<?php echo $links["controller"]; ?>" method="post" id="HostingChangePassword">
                    <input type="hidden" name="operation" value="hosting_change_password">
                    <div class="green-info" style="margin-bottom:20px;">
                        <div class="padding15">
                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                            <p><?php echo __("website/account_products/hosting-change-password-desc1"); ?></p>
                        </div>
                    </div>
                    <input name="password" class="yuzde50inpt" type="text" placeholder="<?php echo __("website/account_products/hosting-change-password-input"); ?>" rel="aselect" id="hosting_password">
                    <div class="yuzde50">
                        <a href="javascript:void(0);" onclick="$('#hosting_password').val(randString({characters:'A-Z,a-z,0-9,#'}));password_check('hosting_password');" class="incelebtn"><i class="fa fa-refresh"></i> <?php echo __("website/account_products/new-random-password"); ?></a>
                        <div id="strength_hosting_password" class="yuzde50">
                            <div id="weak" style="display:none;"><?php echo ___("needs/password-weak"); ?></div>
                            <div id="good" style="display:none"><?php echo ___("needs/password-good"); ?></div>
                            <div id="strong" style="display:none;"><?php echo ___("needs/password-strong"); ?></div>
                        </div>
                    </div>
                    <span style="    font-size: 14px;    margin: 15px 0px;    float: left;"><?php echo __("website/account_products/hosting-change-password-warning"); ?></span>
                    <a href="javascript:void(0);" class="yesilbtn gonderbtn mio-ajax-submit" mio-ajax-options='{"result":"HostingChangePassword_submit","waiting_text":"<?php echo addslashes(__("website/others/button5-pending")); ?>"}'><?php echo __("website/account_products/hosting-change-password-button"); ?></a>
                    <div class="clear"></div>
                </form>
                <div id="HostingChangePassword_success" style="display: none;">
                    <div style="margin-top:30px;margin-bottom:70px;text-align:center;">
                        <i style="font-size:80px;" class="fa fa-check"></i>
                        <h4><?php echo __("website/account_products/hosting-change-password-changed"); ?></h4>
                        <br>
                    </div>
                </div>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $('#hosting_password').keyup(function(){
                            password_check("hosting_password");
                        });
                    });

                    function HostingChangePassword_submit(result) {
                        if(result != ''){
                            var solve = getJson(result);
                            if(solve !== false){
                                if(solve.status == "error"){
                                    if(solve.for != undefined && solve.for != ''){
                                        $("#HostingChangePassword "+solve.for).focus();
                                        $("#HostingChangePassword "+solve.for).attr("style","border-bottom:2px solid red; color:red;");
                                        $("#HostingChangePassword "+solve.for).change(function(){
                                            $(this).removeAttr("style");
                                        });
                                    }
                                    if(solve.message != undefined && solve.message != '')
                                        alert_error(solve.message,{timer:3000});
                                }else if(solve.status == "successful"){
                                    $("#HostingChangePassword").fadeOut(700,function(){
                                        $("html,body").animate({scrollTop:200},600);
                                        $("#HostingChangePassword_success").fadeIn(700);
                                        setTimeout(function(){
                                            var link = sGET("tab","password");
                                            window.location.href = link;
                                        },4000);
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
                        <i style="font-size:80px;" class="fa fa-check"></i>
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