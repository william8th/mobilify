<?php
/*
Plugin Name: Mobilify
Plugin URI: http://mobilify.williamheng.com
Description: Switch theme and mobilify your WordPress site.
Author: William Heng
Author URI: http://williamheng.com
License: GPLv2
*/

/*  Copyright 2012  WILLIAM HENG  (email : william@williamheng.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


    Extension Mobile_Detect from https://code.google.com/p/php-mobile-detect/ is used.
    It is subject to its inherent MIT license and credit is given herewith.
    Supporting GCWCID movement. For more info please visit http://is.gd/gcwcid/
*/




/*
    Change the template if mobile device detected
*/
include_once( 'Mobile_Detect.php' );
$detector = new Mobile_Detect();

if( $detector->isMobile() ) {
    add_filter( 'stylesheet', 'get_template_fn' );
    add_filter( 'template', 'get_template_fn' );
}

function get_template_fn() {
    $theme_selected = get_option( 'mobilify_option' );
    $themes = wp_get_themes();
    foreach ($themes as $theme) {
        if( $theme_selected == $theme->Name ) {
            return $theme->Template;
        }
    }
}

/*
    The options page for the plugin
*/
//Show the options of plugin in menu
add_action( 'admin_menu', 'mobilify_add_page_fn' );

function mobilify_add_page_fn() {
    add_theme_page( 'Mobilify Options', 'Mobilify', 'manage_options', __FILE__, 'mobilify_construct_html_fn' );
} //mobilify_add_page_fn

function mobilify_construct_html_fn() {
    if( ! current_user_can( 'manage_options' ) ) {
        wp_die( __('Access denied. You do not have sufficient permission to access this page.') );
    }
?>
    <div class="wrap">
        <h2>Mobilify</h2>
        <form method="post" action="options.php">
            <?php settings_fields( 'mobilify_options_group' ); ?>
            <?php do_settings_sections( __FILE__ ) ?>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
} //mobilify_construct_html

//Add hook to define settings
add_action( 'admin_init', 'mobilify_init_settings_fn' );

function mobilify_init_settings_fn() {
    register_setting( 'mobilify_options_group', 'mobilify_option' );
    add_settings_section( 'mobilify_section', 'Mobilify', 'section_text_fn', __FILE__ );
    add_settings_field( 'mobilify_field', __( 'Select Mobilify Theme' ), 'mobilify_display_input_fn', __FILE__, 'mobilify_section' );
} //mobilify_init_settings_fn

function section_text_fn() {
    return;
} //section_text_fn

function mobilify_display_input_fn() {
    $options = get_option( 'mobilify_option' );
    $themes = wp_get_themes();

    echo "<select name='mobilify_option'>";
    foreach( $themes as $theme ) {
        $selected = ( $options == $theme->Name ) ? ' selected="selected"' : '';
        echo "<option value='$theme->Name'$selected>$theme->Name</option>";
    }
    echo "</select>";
} //mobilify_display_input_fn

?>