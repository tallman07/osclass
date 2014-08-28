<?php
    /*
     *      Osclass – software for creating and publishing online classified
     *                           advertising platforms
     *
     *                        Copyright (C) 2012 OSCLASS
     *
     *       This program is free software: you can redistribute it and/or
     *     modify it under the terms of the GNU Affero General Public License
     *     as published by the Free Software Foundation, either version 3 of
     *            the License, or (at your option) any later version.
     *
     *     This program is distributed in the hope that it will be useful, but
     *         WITHOUT ANY WARRANTY; without even the implied warranty of
     *        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *             GNU Affero General Public License for more details.
     *
     *      You should have received a copy of the GNU Affero General Public
     * License along with this program.  If not, see <http://www.gnu.org/licenses/>.
     */

    define('INDIA_THEME_VERSION', '2.0');

    osc_enqueue_script('php-date');

    if( !OC_ADMIN ) {
        if( !function_exists('add_close_button_action') ) {
            function add_close_button_action(){
                echo '<script type="text/javascript">';
                    echo '$(".flashmessage .ico-close").click(function(){';
                        echo '$(this).parent().hide();';
                    echo '});';
                echo '</script>';
            }
            osc_add_hook('footer', 'add_close_button_action');
        }
    }

    function theme_india_actions_admin() {
        if( Params::getParam('file') == 'oc-content/themes/india/admin/settings.php' ) {
            if( Params::getParam('donation') == 'successful' ) {
                osc_set_preference('donation', '1', 'india');
                osc_reset_preferences();
            }
        }

        switch( Params::getParam('action_specific') ) {
            case('settings'):
                $footerLink  = Params::getParam('footer_link');
                $defaultLogo = Params::getParam('default_logo');
                osc_set_preference('keyword_placeholder', Params::getParam('keyword_placeholder'), 'india');
                osc_set_preference('footer_link', ($footerLink ? '1' : '0'), 'india');
                osc_set_preference('default_logo', ($defaultLogo ? '1' : '0'), 'india');

                osc_add_flash_ok_message(__('Theme settings updated correctly', 'india'), 'admin');
                header('Location: ' . osc_admin_render_theme_url('oc-content/themes/india/admin/settings.php')); exit;
            break;
            case('upload_logo'):
                $package = Params::getFiles('logo');
                if( $package['error'] == UPLOAD_ERR_OK ) {
                    if( move_uploaded_file($package['tmp_name'], WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) {
                        osc_add_flash_ok_message(__('The logo image has been uploaded correctly', 'india'), 'admin');
                    } else {
                        osc_add_flash_error_message(__("An error has occurred, please try again", 'india'), 'admin');
                    }
                } else {
                    osc_add_flash_error_message(__("An error has occurred, please try again", 'india'), 'admin');
                }
                header('Location: ' . osc_admin_render_theme_url('oc-content/themes/india/admin/header.php')); exit;
            break;
            case('remove'):
                if(file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) {
                    @unlink( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" );
                    osc_add_flash_ok_message(__('The logo image has been removed', 'india'), 'admin');
                } else {
                    osc_add_flash_error_message(__("Image not found", 'india'), 'admin');
                }
                header('Location: ' . osc_admin_render_theme_url('oc-content/themes/india/admin/header.php')); exit;
            break;
        }
    }

    if( !function_exists('logo_header') ) {
        function logo_header() {
            $html = '<img border="0" alt="' . osc_page_title() . '" src="' . osc_current_web_theme_url('images/logo.jpg') . '" />';
            if( file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/logo.jpg" ) ) {
                return $html;
            } else if( osc_get_preference('default_logo', 'india') && (file_exists( WebThemes::newInstance()->getCurrentThemePath() . "images/default-logo.jpg")) ) {
                return '<img border="0" alt="' . osc_page_title() . '" src="' . osc_current_web_theme_url('images/default-logo.jpg') . '" />';
            } else {
                return osc_page_title();
            }
        }
    }

    // install update options
    if( !function_exists('india_theme_install') ) {
        function india_theme_install() {
            osc_set_preference('keyword_placeholder', __('ie. PHP Programmer', 'india'), 'india');
            osc_set_preference('version', '2.0', 'india');
            osc_set_preference('footer_link', true, 'india');
            osc_set_preference('donation', '0', 'india');
            osc_set_preference('default_logo', '1', 'india');
            osc_reset_preferences();
        }
    }

    if(!function_exists('check_install_india_theme')) {
        function check_install_india_theme() {
            $current_version = osc_get_preference('version', 'india');
            //check if current version is installed or need an update
            if( !$current_version ) {
                india_theme_install();
            }
        }
    }

    require_once WebThemes::newInstance()->getCurrentThemePath() . 'inc.functions.php';

    check_install_india_theme();
?>