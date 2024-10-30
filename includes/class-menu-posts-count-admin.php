<?php

/**
 * Handle Setting API for plugin in admin area.
 *
 * @link       http://www.sanjayojha.com
 * @since      1.0.0
 *
 * @package    Menu_Posts_Count
 */

class Menu_Posts_Count_Admin
{
    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'mpc_sa_settings_init' ), 99 );
		add_action( 'admin_menu', array( $this,  'mpc_sa_options_page'));
    }

    public function mpc_sa_settings_init()
    {
        // register a new setting
		register_setting('mpc-sa-set', 'mpc_sa_options', array( 'sanitize_callback' => [$this, 'mpc_sa_sanitize_value']));

        // register a new section
		add_settings_section(
			'mpc_sa_section',
			__('Available Settings', 'mpc-sa'),
			array( $this, 'mpc_sa_section_cb' ),
			'menu-posts-count-settings'
		);

        // register a new field in the "mpc_sa_section" section. Filed 1
		add_settings_field(
			'mpc_sa_field_top_level_menu', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__('Show counts in first level menu?', 'mpc-sa'),
			array( $this, 'mpc_sa_field_top_level_menu_cb' ),
			'menu-posts-count-settings',
			'mpc_sa_section',
			[
				'label_for'         => 'mpc_sa_field_top_level_menu',
				'class'             => 'mpc_sa_row'
			]
		);

        // register a new field in the "mpc_sa_section" section. Filed 2
		add_settings_field(
			'mpc_sa_field_count_greater', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__('Show only for count greater than', 'mpc-sa'),
			array( $this, 'mpc_sa_field_count_greater_cb' ),
			'menu-posts-count-settings',
			'mpc_sa_section',
			[
				'label_for'         => 'mpc_sa_field_count_greater',
				'class'             => 'mpc_sa_row'
			]
		);

        // register a new field in the "mpc_sa_section" section. Filed 3
		add_settings_field(
			'mpc_sa_field_which_menu', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__('Which menus?', 'mpc-sa'),
			array( $this, 'mpc_sa_field_which_menu_cb' ),
			'menu-posts-count-settings',
			'mpc_sa_section',
			[
				'label_for'         => 'mpc_sa_field_which_menu',
				'class'             => 'mpc_sa_row'
			]
		);

        // register a new field in the "mpc_sa_section" section. Filed 4
		add_settings_field(
			'mpc_sa_field_select_taxonomy', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__('Select taxonomies', 'mpc-sa'),
			array( $this, 'mpc_sa_field_select_taxonomy_cb' ),
			'menu-posts-count-settings',
			'mpc_sa_section',
			[
				'label_for'         => 'mpc_sa_field_select_taxonomy',
				'class'             => 'mpc_sa_row'
			]
		);

		// register a new field in the "mpc_sa_section" section. Filed 4
		add_settings_field(
			'mpc_sa_field_bracket', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__('Select bracket type', 'mpc-sa'),
			array( $this, 'mpc_sa_field_bracket_cb' ),
			'menu-posts-count-settings',
			'mpc_sa_section',
			[
				'label_for'         => 'mpc_sa_field_bracket',
				'class'             => 'mpc_sa_row'
			]
		);

		// register a new field in the "mpc_sa_section" section. Filed 4
		add_settings_field(
			'mpc_sa_field_bracket_space', // as of WP 4.6 this value is used only internally
			// use $args' label_for to populate the id inside the callback
			__('Keep space around the counts', 'mpc-sa'),
			array( $this, 'mpc_sa_field_bracket_space_cb' ),
			'menu-posts-count-settings',
			'mpc_sa_section',
			[
				'label_for'         => 'mpc_sa_field_bracket_space',
				'class'             => 'mpc_sa_row'
			]
		);

    }

    public function mpc_sa_section_cb( $args )
    {
        echo '<p id="'. esc_attr($args['id']) .'">'. esc_html__('Please select your choice from below options', 'mpc-sa') .'</p>';
    }

    public function mpc_sa_field_top_level_menu_cb( $args )
    {
        // get the value of the setting we've registered with register_setting()
    	$options = get_option('mpc_sa_options');
		$topmenu = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';

		?>
		<fieldset>
			<label>
				<input type="checkbox" name="mpc_sa_options[<?php echo esc_attr($args['label_for']) ?>]" value="1" <?php checked( $topmenu == 1 ); ?>> <?php _e( 'Yes', 'mpc-sa' ); ?>
			</label>
			<p class="description"><?php _e( 'Check this box if you want to show the counts in first level menu items.', 'mpc-sa' ); ?></p>
		</fieldset>
		<?php
    }

	public function mpc_sa_field_count_greater_cb( $args )
	{
		// get the value of the setting we've registered with register_setting()
    	$options = get_option('mpc_sa_options');
		$countl = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '0';

		?>
		<fieldset>
			<label>
				<input type="text" name="mpc_sa_options[<?php echo esc_attr($args['label_for']) ?>]" value="<?php echo $countl ?>"> 
			</label>
			<p class="description"><?php _e( 'Please enter a non-negative integer number. Post counts will show only if it is greater than this value', 'mpc-sa' ); ?></p>
		</fieldset>
		<?php
	}

	public function mpc_sa_field_which_menu_cb( $args )
	{
		// get the value of the setting we've registered with register_setting()
    	$options = get_option('mpc_sa_options');
		$menuArr = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';

		$menuArr = explode(',', $menuArr);
		
		$menus = get_registered_nav_menus();
		?>
		<fieldset>
			<?php
			$i = 0;
			foreach ($menus as $menuid => $menudesc) {
				?>
				<label>
					<input type="checkbox" name="mpc_sa_options[<?php echo esc_attr($args['label_for']) ?>][<?php echo $i ?>]" value="<?php echo $menuid ?>" <?php checked( in_array( $menuid, $menuArr) ); ?>> <?php _e( $menudesc, 'mpc-sa' ); ?>
				</label>&nbsp;&nbsp;&nbsp;
				
			<?php 
			$i++;
			}   
			if( count($menus) > 0) {
			?>       
			
			<p class="description"><?php _e( 'Select menus available in your theme for which you want to show posts count.', 'mpc-sa' ); ?></p>

			<?php } else { ?>
			<p class="description"><?php _e( 'Your current theme does not support any navigation menu.', 'mpc-sa' ); ?></p>
			<?php } ?>
		
		</fieldset>
		<?php
		
	}

	public function mpc_sa_field_select_taxonomy_cb( $args )
	{
		// get the value of the setting we've registered with register_setting()
    	$options = get_option('mpc_sa_options');
		$taxonomyArr = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
		$taxonomyArr = explode(',', $taxonomyArr);
		
		$taxonomies = get_taxonomies( array('public' => true, 'show_ui' => true, 'show_in_menu' => true), 'objects' );
		?>
		<fieldset>
			<?php 
			$i = 0;
			foreach ($taxonomies as $taxonomy) {
				
				?>
				<label>
					<input type="checkbox" name="mpc_sa_options[<?php echo esc_attr($args['label_for']) ?>][[<?php echo $i ?>]]" value="<?php echo $taxonomy->name ?>" <?php checked( in_array( $taxonomy->name, $taxonomyArr) ); ?>> <?php _e( $taxonomy->label, 'mpc-sa' ); ?>
				</label>&nbsp;&nbsp;&nbsp;
				
			<?php 
			$i++;
			} ?>       
			
			<p class="description"><?php _e( 'Select those taxonomies for which you want to show counts', 'mpc-sa' ); ?></p>
		
		</fieldset>
		<?php
	}
	public function mpc_sa_field_bracket_cb( $args )
	{
		// get the value of the setting we've registered with register_setting()
    	$options = get_option('mpc_sa_options');
		$bracket = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '0';

		?>
		<fieldset>
			<label>
				<select name="mpc_sa_options[<?php echo esc_attr($args['label_for']) ?>]">
					<option value="0">None</option>
					<option value="1" <?php selected( $bracket, 1 ); ?>>parentheses ( )</option>
					<option value="2" <?php selected( $bracket, 2 ); ?>>square brackets [ ]</option>
					<option value="3" <?php selected( $bracket, 3 ); ?>>Curly brackets { }</option>
					<option value="4" <?php selected( $bracket, 4 ); ?>>Angle brackets < ></option>
				</select>
				
			</label>
			<p class="description"><?php _e( 'Select type of bracket inside which you want to show the counts', 'mpc-sa' ); ?></p>
		</fieldset>
		<?php
	}

	public function mpc_sa_field_bracket_space_cb( $args )
    {
        // get the value of the setting we've registered with register_setting()
    	$options = get_option('mpc_sa_options');
		$brspace = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';

		?>
		<fieldset>
			<label>
				<input type="checkbox" name="mpc_sa_options[<?php echo esc_attr($args['label_for']) ?>]" value="1" <?php checked( $brspace == 1 ); ?>> <?php _e( 'Yes', 'mpc-sa' ); ?>
			</label>
			<p class="description"><?php _e( 'If you wants to keep space around the counts inside bracket tick this box. It works only if you have selected bracket from above dropdown', 'mpc-sa' ); ?></p>
		</fieldset>
		<?php
    }

	// admin menu hook for option page
	public function mpc_sa_options_page()
	{
		// add top level menu page
		add_menu_page(
			'Menu Posts Count settings',
			__('Menu Posts Count', 'mpc-sa'),
			'manage_options',
			'menu-posts-count-settings-page',
			[$this, 'mpc_sa_options_page_cb']
		);
	}

	//page attached with admin menu
	public function mpc_sa_options_page_cb()
	{
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}
		
		// show error/update messages
		settings_errors('mpc_sa_messages');

		//Show forms
		echo '<div class="wrap"><h1>'. esc_html(get_admin_page_title()) .'</h1>
        	<form action="options.php" method="post">';
			settings_fields('mpc-sa-set');

			// output setting sections and their fields		
			do_settings_sections('menu-posts-count-settings');
			?>
			<br/>
			<h4 class="description"><i><strong style="color: #a00; font-size: 16px">NOTE:</strong> Counts are shown inside above selected barckets in <code>&lt;span&gt;</code> HTML tag with class <code>mpc-count</code> just after the menu text . You can use this class to style the count using CSS.</i></h4>
			<?php
			// output save settings button
			submit_button('Save Settings');

		echo '</form>
		</div>';
	}

	public function mpc_sa_sanitize_value( $val )
	{

		$newval = array();
		$type = 'updated';
		$msg = 'Setting is saved!';
		if( isset($val['mpc_sa_field_top_level_menu']) ) {
			$newval['mpc_sa_field_top_level_menu'] = 1;
		}

		if( isset($val['mpc_sa_field_count_greater']) ) {
			if(is_numeric($val['mpc_sa_field_count_greater']) && $val['mpc_sa_field_count_greater'] >= 0) {
				$newval['mpc_sa_field_count_greater'] = intval($val['mpc_sa_field_count_greater']);
			} else {
				$type = 'error';
				$msg = 'Please enter positive integer for count greater than!';				
			}
		}

		if( isset($val['mpc_sa_field_which_menu']) ) {
			$newval['mpc_sa_field_which_menu'] = implode( ',', (array)$val['mpc_sa_field_which_menu']);			
			
		}
		if( isset($val['mpc_sa_field_select_taxonomy']) ) {
			$newval['mpc_sa_field_select_taxonomy'] = implode( ',', (array)$val['mpc_sa_field_select_taxonomy']);
		}
		if( isset($val['mpc_sa_field_bracket']) ) {
			$newval['mpc_sa_field_bracket'] = intval($val['mpc_sa_field_bracket']);
		}

		if( isset($val['mpc_sa_field_bracket_space']) ) {
			$newval['mpc_sa_field_bracket_space'] = 1;
		}

		add_settings_error('mpc_sa_messages', 'mpc_sa_message', __($msg, 'mpc-sa'), $type);
		
		if( $type == 'updated' ){			
			return $newval;
		} else{
			return get_option('mpc_sa_options');
		}
	}
}