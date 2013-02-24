<?php

if ( ! defined( 'ABSPATH' ) )
	die( "Can't load this file directly" );

class ArveButton
{
	function __construct() {
		add_action( 'admin_init', array( $this, 'action_admin_init' ) );
		add_action( 'wp_ajax_get_arve_form', array( $this, 'get_mce_form' ) );
	}
	
	function action_admin_init() {
		// only hook up these filters if we're in the admin panel, and the current user has permission
		// to edit posts and pages
		if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
			add_filter( 'mce_buttons', array( $this, 'filter_mce_button' ) );
			add_filter( 'mce_external_plugins', array( $this, 'filter_mce_plugin' ) );
		}
	}
	
	function filter_mce_button( $buttons ) {
		// add a separation before our button, here our button's id is "arve_button"
		array_push( $buttons, '|', 'arve_button' );
		return $buttons;
	}
	
	function filter_mce_plugin( $plugins ) {
		// this plugin file will work the magic of our button
		$plugins['arve'] = plugin_dir_url( __FILE__ ) . 'js/mce-plugin.js';
		return $plugins;
	}

	function get_mce_form() {
		?>
		<div id="arve-form">
			<table id="arve-table" class="form-table">
				<colgroup style="width: 45%;"></colgroup>
				<colgroup style="width: 55%;"></colgroup>
				<tr>
					<th>
						<label for="arve-url">URL</label><br>
						<small class="description">
							<?php _e('For Blip.tv, Videojug, Movieweb, Gametrailers, Yahoo!, Spike and Comedycentral paste the embed code, for all others paste the URL!<br>', 'arve-plugin'); ?><br>
							<a href="#" id="arve-open-url-info"><?php _e('More info', 'arve-plugin'); ?></a>
						</small>

						<div id="arve-url-info" style="display: none; padding: 0 15px;">
							<p>
								<?php _e('Paste the URL of the video here.', 'arve-plugin'); ?>
							</p>
							<p>
								<?php _e('Exeption: for Blip.tv, Videojug, Movieweb, Gametrailers, Yahoo!, Spike and Comedycentral you <strong>must</strong> paste the embed code here!', 'arve-plugin'); ?>
							</p>
							<p>
								<?php _e('Ustream: If your Address bar URL not contains a number. Click Share->URL-icon and paste the URL you get there here.', 'arve-plugin'); ?>
							</p>
							<p>
								<?php _e("For Youtube, Archiveorg, Metacafe and Viddler embed codes and URL's should work.", 'arve-plugin'); ?>
							</p>
						</div>
					</th>
					<td>
						<textarea id="arve-url" rows="4" value="" style="width: 100%;"></textarea><br>
					</td>
				</tr>
				<tr>
					<th>
						<label for="arve-mode"><?php _e('Mode', 'arve-plugin'); ?></label><br>
						<small class="description"><?php _e('Only use it of you want some vidoes have a mode that differs from the one you set in the options.', 'arve-plugin');?></small>
					</th>
					<td>
						<select id="arve-mode">
							<option value=""></option>
							<option value="normal"><?php _e('Normal', 'arve-plugin'); ?></option>
							<option value="thumbnail"><?php _e('Thumbnail', 'arve-plugin'); ?></option>
						</select>
					</td>				
				</tr>
				<tr>
					<th>
						<label for="arve-align"><?php _e('Align', 'arve-plugin'); ?></label><br>
						<small class="description"><?php _e('');?></small>
					</th>
					<td>
						<select id="arve-align">
							<option value=""></option>
							<option value="left"><?php _e('left', 'arve-plugin'); ?></option>
							<option value="right"><?php _e('right', 'arve-plugin'); ?></option>
							<option value="center"><?php _e('center', 'arve-plugin'); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th></th>
					<td>
						<input type="button" id="arve-show-more" class="button-secondary" value="Show More Options" name="arve-show-more" />
					</td>
				</tr>
				<tr style="display: none;" class="arve-hidden">
					<th>
						<label for="arve-maxw">Maximal width</label><br>
						<small class="description"><?php _e('Only use it of you want some vidoes have a maximal width that differs from the one you set in the options.', 'arve-plugin'); ?></small>
					</th>
					<td>
						<input type="text" id="arve-maxw" />	
					</td>
				</tr>
				<tr style="display: none;" class="arve-hidden">
					<th>
						<label for="arve-provider">Provider</label>
					</th>
					<td>
						<select id="arve-provider">
							<option value=""></option>
							<?php
							$options = get_option('arve_options');
							$providers = arve_filter_shortcode_options( $options );
							foreach( $providers as $provider )
								echo '<option value="' . $provider . '">' . $provider . '</option>';
							?>
						</select>
					</td>
				</tr>
				<tr style="display: none;" class="arve-hidden">
					<th>
						<label for="arve-id">Video ID</label><br>
						<small class="description">If not filled in automatically after pasting the url above you have to insert the video ID in here.</small>
					</th>
					<td>
						<input type="text" id="arve-id" value="" /><br>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding: 15px; font-size: 17px; text-align: center;" id="arve-shortcode">
						-
					</td>
				</tr>	
				<tr>
					<th><label for="arve-submit">Ready?</label></th>
					<td>
						<input type="button" id="arve-submit" class="button-primary" value="Insert Shortcode" name="submit" />
					</td>
				</tr>
			</table>
		</div>
<?php
	exit;
	}
}

$arve_buttom = new ArveButton();