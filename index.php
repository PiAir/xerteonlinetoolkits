<?php
/**
 * Licensed to The Apereo Foundation under one or more contributor license
 * agreements. See the NOTICE file distributed with this work for
 * additional information regarding copyright ownership.

 * The Apereo Foundation licenses this file to you under the Apache License,
 * Version 2.0 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at:
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.

 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
 
// Load the plugin files and fire a startup action
require_once(dirname(__FILE__) . "/plugins.php");

startup();

require_once(dirname(__FILE__) . "/config.php");

_load_language_file("/index.inc");

/**
 *
 * Login page, self posts to become management page
 *
 * @author Patrick Lockley
 * @version 1.0
 * @package
 */
include $xerte_toolkits_site->php_library_path . "display_library.php";


require_once(dirname(__FILE__) . "/website_code/php/login_library.php");

if ($xerte_toolkits_site->altauthentication != "" && isset($_GET['altauth']))
{
    $xerte_toolkits_site->authentication_method = $xerte_toolkits_site->altauthentication;
    $authmech = Xerte_Authentication_Factory::create($xerte_toolkits_site->authentication_method);
    $_SESSION['altauth'] = $xerte_toolkits_site->altauthentication;
}

login_processing();
login_processing2();

/*If the authentication method isn't set to Moodle
* the code in the required file below is simply skipped
*/
require_once(dirname(__FILE__) . "/moodle_restrictions.php");

recycle_bin();

$version = getVersion();

/*
 * Output the main page, including the user's and blank templates
 */
?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <?php head_start(); ?>
    <!--

    HTML to use to set up the template management page

    Version 1.0

    -->
    <title><?PHP echo apply_filters("head_title", $xerte_toolkits_site->site_title); ?></title>
    <link rel="stylesheet" href="editor/css/jquery-ui.css">
    <link rel="stylesheet" href="editor/js/vendor/themes/default/style.css?version=<?php echo $version;?>" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="editor/js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
    <script type="text/javascript" src="editor/js/vendor/jquery.ui-1.10.4.js"></script>
    <script type="text/javascript" src="editor/js/vendor/jquery.layout-1.3.0-rc30.79.min.js"></script>
    <script type="text/javascript" src="editor/js/vendor/jquery.ui.touch-punch.min.js"></script>
    <script type="text/javascript" src="editor/js/vendor/modernizr-latest.js"></script>
    <script type="text/javascript" src="editor/js/vendor/jstree.js?version=<?php echo $version;?>"></script>
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
    <link href="website_code/styles/bootstrap.css?version=<?php echo $version;?>" media="screen" type="text/css" rel="stylesheet"/>
    <link href="website_code/styles/nv.d3.css?version=<?php echo $version;?>" media="screen" type="text/css" rel="stylesheet"/>
    <link href="website_code/styles/xapi_dashboard.css?version=<?php echo $version;?>" media="screen" type="text/css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Cabin' rel='stylesheet' type='text/css'>
    <link href="website_code/styles/folder_popup.css?version=<?php echo $version;?>" media="screen" type="text/css" rel="stylesheet"/>
    <link href="website_code/styles/jquery-ui-layout.css?version=<?php echo $version;?>" media="screen" type="text/css" rel="stylesheet"/>
    <link href="website_code/styles/xerte_buttons.css?version=<?php echo $version;?>" media="screen" type="text/css" rel="stylesheet"/>
    <link href="website_code/styles/frontpage.css?version=<?php echo $version;?>" media="screen" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="modules/xerte/parent_templates/Nottingham/common_html5/font-awesome/css/font-awesome.min.css?version=<?php echo $version;?>">
    <link rel="stylesheet" type="text/css" href="modules/xerte/parent_templates/Nottingham/common_html5/font-awesome-4.3.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>

    <?php
    if (file_exists($xerte_toolkits_site->root_file_path . "branding/branding.css"))
    {
        ?>
        <link href='branding/branding.css' rel='stylesheet' type='text/css'>
        <?php
    }
    if (isset($_SESSION['toolkits_language']))
    {
        $languagecodevar = "var language_code = \"" . $_SESSION['toolkits_language'] . "\"";
    }
    else
    {
        $languagecodevar = "var language_code = \"en-GB\"";
    }
    echo "
        <script type=\"text/javascript\"> // JAVASCRIPT library for fixed variables\n // management of javascript is set up here\n // SITE SETTINGS
            var site_url = \"{$xerte_toolkits_site->site_url}\";
            var site_apache = \"{$xerte_toolkits_site->apache}\";
            var properties_ajax_php_path = \"website_code/php/properties/\";
            var management_ajax_php_path = \"website_code/php/management/\";
            var ajax_php_path = \"website_code/php/\";
            {$languagecodevar};
        </script>";
    ?>
    <script type="text/javascript" language="javascript" src="website_code/scripts/validation.js?version=<?php echo $version;?>"></script>
    <?php
    _include_javascript_file("website_code/scripts/file_system.js?version=" . $version);
    _include_javascript_file("website_code/scripts/screen_display.js?version=" . $version);
    _include_javascript_file("website_code/scripts/ajax_management.js?version=" . $version);
    _include_javascript_file("website_code/scripts/folders.js?version=" . $version);
    _include_javascript_file("website_code/scripts/template_management.js?version" . $version);
    _include_javascript_file("website_code/scripts/logout.js?version=" . $version);
    _include_javascript_file("website_code/scripts/import.js?version=" . $version);
    ?>
    <script type="text/javascript" src="website_code/scripts/tooltip.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="website_code/scripts/popper.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="website_code/scripts/bootstrap.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="modules/xerte/xAPI/xapicollection.min.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="modules/xerte/xAPI/xapidashboard.min.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="modules/xerte/xAPI/xapiwrapper.min.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="website_code/scripts/moment.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="website_code/scripts/jquery-ui-i18n.min.js?version=<?php echo $version;?>"></script>
    <script type="text/javascript" src="website_code/scripts/result.js?version=<?php echo $version;?>"></script>

    <?php
    _include_javascript_file("website_code/scripts/xapi_dashboard_data.js?version=" . $version);
    _include_javascript_file("website_code/scripts/xapi_dashboard.js?version=" . $version);

    ?>
    <?php head_end(); ?></head>

<!--

code to sort out the javascript which prevents the text selection of the templates (allowing drag and drop to look nicer

body_scroll handles the calculation of the documents actual height in IE.

-->

<body >
<?php body_start(); ?>
<!--

Folder popup is the div that appears when creating a new folder

-->

<div class="folder_popup" id="message_box">
    <div class="main_area" id="dynamic_section">
        <p style="color:white"><?PHP echo INDEX_FOLDER_PROMPT; ?></p>

        <form id="foldernamepopup" action="javascript:create_folder()" method="post" enctype="text/plain">
            <input type="text" width="200" id="foldername" name="foldername"
                   style="margin:0px; margin-right:5px; padding:3px"/>
            <button type="submit" class="xerte_button_c">
                <img src="website_code/images/Icon_Folder_15x12.gif"/>
                <?php echo INDEX_BUTTON_NEWFOLDER_CREATE; ?>
            </button>
            <button type="button" class="xerte_button_c"
                    onclick="javascript:popup_close()"><?php echo INDEX_BUTTON_CANCEL; ?>
            </button>
        </form>
        <p><span id="folder_feedback"></span></p>
    </div>
</div>

<div class="dashboard-wrapper" id="dashboard-wrapper">

    <div class="dashboard" id="dashboard">
        <div id="options-div">
            <div class="row dash-row">
                <div class="dash-col unanonymous-view" >
                    <label for="dp-unanonymous-view">
                        <?php echo INDEX_XAPI_DASHBOARD_SHOW_NAMES; ?>
                    </label>
                    <input type="checkbox" id="dp-unanonymous-view" >
                </div>

                <div class="dash-col">
                    <label for="dp-start">
                        <?php echo INDEX_XAPI_DASHBOARD_FROM; ?>
                    </label>
                    <input type="text" id="dp-start" value="2018/03/24 21:23" data-test="2018/03/24 21:23">
                </div>
                <div class="dash-col-1">
                    <label for="dp-end">
                        <?php echo INDEX_XAPI_DASHBOARD_UNTIL; ?>
                    </label>
                    <input type="text" id="dp-end">
                </div>
                <div class="close-button">
                    <button type="button" class="xerte_button_c_no_width"
                            onclick="javascript:close_dashboard()"><?php echo INDEX_XAPI_DASHBOARD_CLOSE; ?>
                    </button>
                </div>
            </div>
        </div>
        <div id="dashboard-title"></div>
        <div id="journeyData" class="journeyData container-fluid container"></div>
    </div>
</div>

<div class="ui-layout-north">
    <div class="content" id="mainHeader">

        <div class="topbar">
            <?php
            if (file_exists($xerte_toolkits_site->root_file_path . "branding/logo_right.png"))
            {
            ?>
                <div
                    style="width:50%; height:100%; float:right; position:relative; background-image:url(branding/logo_right.png); background-repeat:no-repeat; background-position:right; margin-right:10px; float:right">
                </div>
            <?php
            }
            else {
            ?>
                <div
                    style="width:50%; height:100%; float:right; position:relative; background-image:url(website_code/images/apereoLogo.png); background-repeat:no-repeat; background-position:right; margin-right:10px; float:right">
                </div>
            <?php
            }
            if (file_exists($xerte_toolkits_site->root_file_path . "branding/logo_left.png"))
            {
            ?>
                <img src="branding/logo_left.png" style="margin-left:10px; float:left"/>
            <?php
            }
            else {
            ?>
                <img src="website_code/images/logo.png" style="margin-left:10px; float:left"/>
            <?php
            }
            ?>
        </div>

        <div class="buttonbar">
            <div class="file_mgt_area_top">
                

            </div>

           <div class="userbar">
                <?PHP //echo "&nbsp;&nbsp;&nbsp;" . INDEX_LOGGED_IN_AS . " " .;
                echo $_SESSION['toolkits_firstname'] . " " . $_SESSION['toolkits_surname']; ?>
               <div style="display: inline-block"><?php display_language_selectionform("general"); ?></div>
               <?PHP if($xerte_toolkits_site->authentication_method != "Guest") {
               ?><button title="<?PHP echo INDEX_BUTTON_LOGOUT; ?>" type="button" class="xerte_button_c_no_width"
                        onclick="javascript:logout(<?php echo($xerte_toolkits_site->authentication_method == "Saml2" ? "true" : "false"); ?>)">
                    <i class="fa fa-sign-out xerte-icon"></i><?PHP echo INDEX_BUTTON_LOGOUT; ?>
                </button><?PHP } ?>
            </div>
            <div style="clear:both;"></div>
            <div class="separator"></div>
        </div>

    </div>
</div>
<!--

    Main part of the page

-->
<div class="ui-layout-center" id="pagecontainer">

    <div class="ui-layout-west" id="workspace_layout">
        <div class="header" id="inner_left_header">
			<div class="file_mgt_area_buttons">
				<!--Workspace buttons-->

				<div class="file_mgt_area_middle_button_left">
					<button title="<?php echo INDEX_BUTTON_EDIT; ?>" type="button" class="xerte_workspace_button disabled" disabled="disabled"
							id="edit"><i class="fa fa-pencil-square-o xerte-icon"></i></button>
					<button title="<?php echo INDEX_BUTTON_PROPERTIES; ?>" type="button" class="xerte_workspace_button disabled" disabled="disabled"
							id="properties"><i class="fa fa-info-circle xerte-icon"></i></button>
					<button title="<?php echo INDEX_BUTTON_PREVIEW; ?>" type="button" class="xerte_workspace_button disabled" disabled="disabled"
							id="preview"><i class="fa fa-play xerte-icon"></i></button>
				</div>
				
				<div class="file_mgt_area_middle_button_left">
					<button title="<?php echo INDEX_BUTTON_NEWFOLDER; ?>" type="button" class="xerte_workspace_button" id="newfolder" onClick="javascript:make_new_folder()">
						<i class="fa fa-folder xerte-icon"></i>
					</button>
				</div>
				
				<div class="file_mgt_area_middle_button_right">
					<button title="<?php echo INDEX_BUTTON_DELETE; ?>" type="button" class="xerte_workspace_button disabled" disabled="disabled"
							id="delete"><i class="fa  fa-trash xerte-icon"></i></button>
					<button title="<?php echo INDEX_BUTTON_DUPLICATE; ?>" type="button" class="xerte_workspace_button disabled" disabled="disabled"
							id="duplicate"><i class="fa fa-copy xerte-icon"></i></button>
					<button title="<?php echo INDEX_BUTTON_PUBLISH; ?>" type="button" class="xerte_workspace_button disabled" disabled="disabled"
							id="publish"><i class="fa  fa-share xerte-icon"></i></button>
				</div>
			</div>
        </div>
        <div class="content">
            <div id="workspace"></div>
        </div>
        <div class="footer" id="sortContainer">
            <div class="file_mgt_area_bottom">
				<div class="sorter">
					<form name="sorting" style="float:left;margin:7px 5px 5px 10px;">
						<i class="fa  fa-sort xerte-icon"></i>&nbsp;<?PHP echo INDEX_SORT; ?>
						<select id="sort-selector" name="type" onChange="refresh_workspace()">>
							<option value="alpha_up"><?PHP echo INDEX_SORT_A; ?></option>
							<option value="alpha_down"><?PHP echo INDEX_SORT_Z; ?></option>
							<option value="date_down" selected><?PHP echo INDEX_SORT_NEW; ?></option>
							<option value="date_up"><?PHP echo INDEX_SORT_OLD; ?></option>
						</select>
					</form>
				</div>
				<div class="workspace_search_outer">
					<div class="workspace_search">
						<i class="fa  fa-search"></i>&nbsp;<?PHP echo INDEX_SEARCH; ?>
						<input type="text" id="workspace_search" label="Search">
					</div>
				</div>
			</div>
        </div>
    </div>

    <div class="ui-layout-center">
        <div class="header" id="inner_center_header">
			<p class="heading"><i class="fa  icon-info-sign xerte-icon"></i>&nbsp;<?PHP echo INDEX_DETAILS; ?></p>
        </div>
        <div class="content">
            <div class="projectInformationContainer" id="project_information">

            </div>
        </div>
        <div class="footer" id="inner_center_footer"></div>
    </div>

    <div class="ui-layout-east">
	
        <div class="header" id="inner_right_header">
            <p class="heading"><i class="fa  icon-wrench xerte-icon"></i>&nbsp;<?PHP echo INDEX_CREATE; ?></p>
        </div>
		
        <div class="content">
            <div class="new_template_area_middle">
                <div id="new_template_area_middle_ajax" class="new_template_area_middle_scroll"><?PHP
                    list_blank_templates();
                    ?>
                </div>
            </div>
        </div>
        <div class="footer" id="inner_right_footer"></div>
    </div>
</div>


<div  class="ui-layout-south">
    <div class="content">
        <!-- <div class="border" style="margin:10px"></div>  -->

        <div class="help" style="width:31%;float:left;">
            <?PHP echo apply_filters('editor_pod_one', $xerte_toolkits_site->pod_one); ?>
        </div>

        <div class="help" style="width:31%;float:left;">
            <?PHP echo apply_filters('editor_pod_two', $xerte_toolkits_site->pod_two); ?>
        </div>
        <div class="highlightbox" style="width:31%;float:right;">
            <?PHP
            //echo $xerte_toolkits_site->demonstration_page;
            echo $xerte_toolkits_site->news_text;
            //echo $xerte_toolkits_site->tutorial_text;
            //echo $xerte_toolkits_site->site_text;
            ?>
        </div>

        <div class="border"></div>

        <p class="copyright">
            <!--<img src="website_code/images/lt_logo.gif" /><br/>-->
            <?PHP
            echo $xerte_toolkits_site->copyright;
            ?> <i class="fa fa-info-circle xerte_info_button" aria-hidden="true" style=" cursor: help;" title="<?PHP echo $version;?>"></i></p><div class="footerlogos"><a href="http://opensource.org/" target="_blank" title="Open Source Initiative: http://opensource.org/"><img src="website_code/images/osiFooterLogo.png" border="0"></a> <a href="https://www.apereo.org" target="_blank" title="Apereo: https://www.apereo.org"><img src="website_code/images/apereoFooterLogo.png" border="0"></a> <a href="http://xerte.org.uk" target="_blank" title="Xerte: http://xerte.org.uk"><img src="website_code/images/xerteFooterLogo.png" border="0"></a></div>

        <div style="clear:both;"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        setupMainLayout();
        refresh_workspace();
    });
</script>
<?php body_end(); ?></body>
</html>
<?php shutdown(); ?>
