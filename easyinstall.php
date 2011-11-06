<?php
/*
Plugin Name: EasyInstall
Plugin URI: http://dev.ejner69.net/easyinstall/
Description: Install plugins and themes from WordPress Extend without enter to dashboard. ¡Easy!
Version: 0.1
Author: Ejner Galaz
Author URI: http://ejner69.net/
*/
define('EASYINSTALL_VERSION', 0.1);
load_plugin_textdomain('easyinstall', false, basename( dirname( __FILE__ ) ) . '/lang' );
add_action('admin_menu', 'easyinstall_config_page');
add_action('tool_box', 'easyinstall_tools_box');

function easyinstall_generate_bookmark() {
	$wpurl = get_bloginfo('wpurl');
	$name = get_bloginfo('name');
	echo "javascript:void(window.open('$wpurl/wp-admin/tools.php?page=easyinstall&url='+window.location.href, '$name', 'width=800,height=500,location=0,scrollbars=1'))";
}

function easyinstall_lookup() {
	if(!empty($_GET['url'])) {
		$install = $_GET['url'];
		$wporgurl = 'http://wordpress.org/extend/';
		if(eregi($wporgurl, $install)) {
			$newurl = str_replace('http://wordpress.org/extend/', '', $_GET['url']);
			if(eregi('plugins', $newurl)) {
				$plugin1 = str_replace('plugins/', '', $newurl);
				$plugin2 = str_replace('/', '', $plugin1);
				echo '<meta http-equiv="Refresh" content="0;url=' . get_bloginfo('wpurl') . wp_nonce_url("/wp-admin/update.php?action=install-plugin&amp;plugin=$plugin2", 'install-plugin_' . $plugin2) . '">';
				echo 'Espere un momento.';
			} elseif(eregi('themes', $newurl)) {
				$theme1= str_replace('themes/', '', $newurl);
				$theme2 = str_replace('/', '', $theme1);
				echo '<meta http-equiv="Refresh" content="0;url=' . get_bloginfo('wpurl') . wp_nonce_url("/wp-admin/update.php?action=install-theme&amp;theme=$theme2", 'install-theme_' . $theme2) . '">';
				echo 'Espere un momento.';
			} else {
				echo 'EasyInstall solo funciona con plugins y themes alojados en el repositorio oficial WordPress.org';
			}
		} else {
			echo 'EasyInstall solo funciona con plugins y themes alojados en el repositorio oficial de WordPress.org';
		}
	} else {
		easyinstall_menu();
	}
}

function easyinstall_menu() { ?>
<div class="wrap">
	<?php screen_icon('tools'); ?>
	<h2>EasyInstall <?php echo EASYINSTALL_VERSION; ?></h2>
	<p>Arrastra el siguiente bookmarklet a tu barra de marcadores. Cuando navegues en el <a href="http://wordpress.org/extend/" rel="external">Repositorio oficial de WordPress</a> y te guste algún plugin o theme, haz click en el bookmarklet y se instalara automáticamante en esta instalación de WordPress.</p>
	<p class="easyinstall"><a onClick="return false;" href="<?php easyinstall_generate_bookmark(); ?>">EasyInstall (<?php bloginfo('name'); ?>)</a></p>
	<h3>¿Que hace EasyInstall?</h3>
	<p>Da la posibilidad al usuario de instalar plugins y themes desde el repositorio oficial <strong>sin necesidad de ingresar nada</strong>. Solo se accede a la página del plugin/theme (siempre y cuando sea en WordPress.org), se hace click en el bookmarklet. <em>EasyInstall</em> detectará automáticamente si se trata de un theme o plugin, y comenzará la instalación.</p>
	<p><strong>¿Te gustó el plugin?</strong> Haz una <a href="http://ejner69.net/donar/">donación</a> para que este, y todos mis proyectos, sigan en pie. Programado en su totalidad por <a href="http://ejner69.net/">Ejner Galaz</a></p>
</div>
<?php }

function easyinstall_tools_box() { ?>
<div class="tool-box">
	<h3 class="title">EasyInstall</h2>
	<p>Arrastra el siguiente bookmarklet a tu barra de marcadores. Cuando navegues en el <a href="http://wordpress.org/extend/" rel="external">Repositorio oficial de WordPress</a> y te guste algún plugin o theme, haz click en el bookmarklet y se instalara automáticamante en esta instalación de WordPress.</p>
	<p class="easyinstall"><a onClick="return false;" href="<?php easyinstall_generate_bookmark(); ?>">EasyInstall (<?php bloginfo('name'); ?>)</a></p>
</div>
<?php }

function easyinstall_config_page() {
	if ( function_exists('add_submenu_page') )
		add_submenu_page('tools.php', __('EasyInstall'), __('EasyInstall'), 'manage_options', 'easyinstall', 'easyinstall_lookup');
}
