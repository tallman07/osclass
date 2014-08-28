<?php

if (india_is_fineuploader()) {
    if (!OC_ADMIN) {
        osc_enqueue_style('fine-uploader-css', osc_assets_url('js/fineuploader/fineuploader.css'));
    }
    osc_enqueue_script('jquery-fineuploader');
}

function india_is_fineuploader() {
    return Scripts::newInstance()->registered['jquery-fineuploader'] && method_exists('ItemForm', 'ajax_photos');
}

osc_add_hook('init_admin', 'theme_india_actions_admin');
osc_add_hook('init_admin', 'theme_india_regions_map_admin');
if (function_exists('osc_admin_menu_appearance')) {
    osc_admin_menu_appearance(__('Header logo', 'india'), osc_admin_render_theme_url('oc-content/themes/india/admin/header.php'), 'header_india');
    osc_admin_menu_appearance(__('Theme settings', 'india'), osc_admin_render_theme_url('oc-content/themes/india/admin/settings.php'), 'settings_india');
    osc_admin_menu_appearance(__('Map settings', 'india'), osc_admin_render_theme_url('oc-content/themes/india/admin/map_settings.php'), 'map_settings_india');
} else {

    function india_admin_menu() {
        echo '<h3><a href="#">' . __('Brasil theme', 'india') . '</a></h3>
            <ul>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/india/admin/header.php') . '">&raquo; ' . __('Header logo', 'india') . '</a></li>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/india/admin/settings.php') . '">&raquo; ' . __('Theme settings', 'india') . '</a></li>
                <li><a href="' . osc_admin_render_theme_url('oc-content/themes/india/admin/map_settings.php') . '">&raquo; ' . __('Map settings', 'india') . '</a></li>

            </ul>';
    }

    osc_add_hook('admin_menu', 'india_admin_menu');
}

function theme_india_regions_map_admin() {
    $regions = json_decode(osc_get_preference('region_maps', 'india'), true);
    switch (Params::getParam('action_specific')) {
        case('edit_region_map'):
            $regions[Params::getParam('target-id')] = Params::getParam('region');
            osc_set_preference('region_maps', json_encode($regions), 'india');
            osc_add_flash_ok_message(__('Region saved correctly', 'india'), 'admin');
            header('Location: ' . osc_admin_render_theme_url('oc-content/themes/india/admin/map_settings.php'));
            exit;
            break;
    }
}

function map_region_url($region_id) {
    $regionData = Region::newInstance()->findByPrimaryKey($region_id);
    if( function_exists('osc_subdomain_type') ) {
        if(osc_subdomain_type()=='region' || osc_subdomain_type()=='category' || osc_subdomain_type()=='country') {
            return osc_update_search_url(array('sRegion' => $regionData['s_name']));
        } else {
            // If osc_subdomain_type == 'city', redirect to base domain.
            if(osc_rewrite_enabled()) {
                $url    = osc_base_url();
            } else {
                $url    = osc_base_url(true);
            }

            // remove subdomain from url
            if(osc_subdomain_type()!='') {
                $aParts = explode('.', $url);
                unset($aParts[0]);
                // http or https
                $url = 'http://';
                if( isset($_SERVER['HTTPS']) ) {
                    if( strtolower($_SERVER['HTTPS']) == 'on' ){
                        $url = 'https://';
                    }
                }
                $url .= implode('.', $aParts);
            }
            if(osc_rewrite_enabled()) {
                if (osc_get_preference('seo_url_search_prefix') != '') {
                    $url .= osc_get_preference('seo_url_search_prefix') . '/';
                }
                $url .= osc_sanitizeString($regionData['s_name']) . '-r' . $regionData['pk_i_id'];

            } else {
                $url .= '?page=search&sRegion='. $regionData['s_name']; // osc_update_search_url(array('sRegion' => $regionData['s_name']));

            }
            return $url;
        }
    } else {
        return osc_search_url(array('sRegion' => $regionData['s_name']));
    }
}

function theme_india_admin_regions_message() {
    $regions = json_decode(osc_get_preference('region_maps', 'india'), true);
    if (count($regions) < 31) {
        echo '</div><div class="flashmessage flashmessage-error" style="display:block">' . sprintf(__('Wait! There are unassigned map areas in the map. <a href="%s">Click here</a> to assign regions to the map.', 'india'), osc_admin_render_theme_url('oc-content/themes/india/admin/map_settings.php')) . '<a class="btn ico btn-mini ico-close">x</a>';
    }
}

osc_add_hook('admin_page_header', 'theme_india_admin_regions_message', 10);
?>