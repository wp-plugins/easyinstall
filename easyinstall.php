<?php
/*
Plugin Name: EasyInstall
Plugin URI: http://dev.ejner69.net/easyinstall/
Description: Install plugins and themes from WordPress Extend without enter to dashboard. ¡Easy!
Version: 0.1.1
Author: Ejner Galaz
Author URI: http://ejner69.net/
*/
define('EASYINSTALL_VERSION', '0.1.1');
define('EASYINSTALL_URL', plugin_dir_url( __FILE__ ));
add_action('admin_menu', 'easyinstall_config_page');
add_action('tool_box', 'easyinstall_tools_box');
load_plugin_textdomain('easyinstall', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/lang', dirname(plugin_basename(__FILE__)).'/lang');

function easyinstall_generate_bookmark() {
	$wpurl = get_bloginfo('wpurl');
	$name = get_bloginfo('name');
	echo "javascript:void(window.open('$wpurl/wp-admin/tools.php?page=easyinstall&url='+window.location.href, '$name', 'width=800,height=500,location=0,scrollbars=1'))";
}

function easyinstall_lookup() {
	if(current_user_can('install_plugins') && current_user_can('install_themes') && current_user_can('update_plugins') && current_user_can('update_themes')) {
		if(!empty($_GET['url'])) {
			$install = $_GET['url'];
			$wporgurl = 'http://wordpress.org/extend/';
			if(eregi($wporgurl, $install)) {
				$newurl = str_replace('http://wordpress.org/extend/', '', $_GET['url']);
				if(eregi('plugins', $newurl)) {
					$clean_plugin_1 = str_replace('plugins/', '', $newurl);
					$clean_plugin_2 = easyinstall_clean_url($clean_plugin_1);
					$plugin_url = str_replace('/', '', $clean_plugin_2);
					echo '<meta http-equiv="Refresh" content="0;url=' . get_bloginfo('wpurl') . wp_nonce_url("/wp-admin/update.php?action=install-plugin&amp;plugin=$plugin_url", 'install-plugin_' . $plugin_url) . '">';
					echo 'Espere un momento.';
				} elseif(eregi('themes', $newurl)) {
					$clean_theme_1 = str_replace('themes/', '', $newurl);
					$clean_theme_2 = easyinstall_clean_url($clean_theme_1);
					$theme_url = str_replace('/', '', $clean_theme_2);
					echo '<meta http-equiv="Refresh" content="0;url=' . get_bloginfo('wpurl') . wp_nonce_url("/wp-admin/update.php?action=install-theme&amp;theme=$theme_url", 'install-theme_' . $theme_url) . '">';
					_e('Espere un momento', 'easyinstall');
				} else {
					echo '<div id="message" class="error"><p>';
					_e('EasyInstall solo funciona con plugins y themes alojados en el repositorio oficial de WordPress.org', 'easyinstall');
					echo '</p></div>';
					easyinstall_menu();
				}
			} else {
				echo '<div id="message" class="error"><p>';
				_e('EasyInstall solo funciona con plugins y themes alojados en el repositorio oficial de WordPress.org', 'easyinstall');
				echo '</p></div>';
				easyinstall_menu();
			}
		} else {
			easyinstall_menu();
		}
	} else {
	_e('No tienes permisos suficientes para instalar y/o actualizar plugins/themes en este sitio', 'easyinstall');
	}
}

function easyinstall_clean_url($url) {
	if(eregi('/stats/', $url)) {
		$return = str_replace('/stats/', '', $url);
	} elseif(eregi('/installation/', $url)) {
		$return = str_replace('/installation/', '', $url);
	} elseif(eregi('/faq/', $url)) {
		$return = str_replace('/faq/', '', $url);
	} elseif(eregi('/other_notes/', $url)) {
		$return = str_replace('/other_notes/', '', $url);
	} elseif(eregi('/screenshots/', $url)) {
		$return = str_replace('/screenshots/', '', $url);	
	} elseif(eregi('/changelog/', $url)) {
		$return = str_replace('/changelog/', '', $url);
	} else {
		$return = $url;
	}
	return $return;
}

function easyinstall_menu() { ?>
<div class="wrap">
	<?php screen_icon('tools'); ?>
	<h2><?php printf( __('EasyInstall %s', 'easyinstall'), EASYINSTALL_VERSION); ?></h2>
	<p><?php printf( __('Arrastra el siguiente bookmarklet a tu barra de marcadores. Cuando navegues en el <a href="%s" rel="external">Repositorio oficial de WordPress</a> y te guste algún plugin o theme, haz click en el bookmarklet y se instalara automáticamante en esta instalación de WordPress.', 'easyinstall'), 'http://wordpress.org/extend/' ); ?></p>
	<p class="easyinstall"><a onClick="return false;" href="<?php easyinstall_generate_bookmark(); ?>"><?php printf( __('Instalar en %s', 'easyinstall'), get_bloginfo('name')); ?></a></p>
	<h3><?php _e('¿Que hace EasyInstall?', 'easyinstall'); ?></h3>
	<p><?php _e('Da la posibilidad al usuario de instalar plugins y themes desde el repositorio oficial <strong>sin necesidad de ingresar nada</strong>. Solo se accede a la página del plugin/theme (siempre y cuando sea en WordPress.org), se hace click en el bookmarklet. <em>EasyInstall</em> detectará automáticamente si se trata de un theme o plugin, y comenzará la instalación.', 'easyinstall'); ?></p>
	<p><?php printf( __('<strong>¿Te gustó el plugin?</strong> Haz una <a href="%1$s">donación</a> para que este, y todos mis proyectos, sigan en pie. Programado en su totalidad por <a href="%2$s">Ejner Galaz</a>', 'easyinstall'), 'http://ejner69.net/donar/', 'http://ejner69.net/'); ?></p>
</div>
<?php }

function easyinstall_tools_box() { ?>
<div class="tool-box">
	<h3 class="title"><?php printf( __('EasyInstall %s', 'easyinstall'), EASYINSTALL_VERSION); ?></h2>
	<p><?php printf( __( 'Arrastra el siguiente bookmarklet a tu barra de marcadores. Cuando navegues en el <a href="%s" rel="external">Repositorio oficial de WordPress</a> y te guste algún plugin o theme, haz click en el bookmarklet y se instalara automáticamante en esta instalación de WordPress.', 'easyinstall' ), 'http://wordpress.org/extend/' ); ?></p>
	<p class="easyinstall"><a onClick="return false;" href="<?php easyinstall_generate_bookmark(); ?>"><?php printf( __('Instalar en %s', 'easyinstall'), get_bloginfo('name')); ?></a></p>
</div>
<?php }

function easyinstall_config_page() {
	
	if ( function_exists('add_submenu_page') )
		add_submenu_page('tools.php', __('EasyInstall'), __('EasyInstall'), 'manage_options', 'easyinstall', 'easyinstall_lookup');
}