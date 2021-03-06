<?php
global $WooPUS_Dependencies, $WooPUS_Recommended, $WooPUS_Required;
?>
<div class="wrap woopus woopus-settings">
	<h1><?php echo sprintf(__('%s - WooCommerce Plugin Update Server integration', 'woopus'), 'WooPUS'); ?></h1>

	<div class="content">
		<div class="column">
			<?php screen_icon(); ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'woopus' ); ?>
				<table class=form-table>
					<?php
					// $export_url = esc_url( add_query_arg(
					//   $export_url
					// ));
					if(!get_option('WooPUS_token')) {
						update_option('WooPUS_token', substr(md5(openssl_random_pseudo_bytes(20)),-20));
					}

					foreach ($WooPUS_options as $category => $options) {
						do_settings_sections( 'WooPUS_sections' );
						?>
						<!-- Menu Categories section -->
						<?php		if($category!='default') { ?>
							<tr><th colspan=2>
								<h2><?php _e($category);?></h2>
							</th></tr>
							<?php
						}
						foreach ($options as $option => $args) {
							$before="";
							$after="";
							$scripts="";
							// $type=($type=='boolean') ? 'checkbox' : $type;
							$default=$args['default'];
							$name=$args['name'];
							$checked=get_option($option) ? "checked" : "";
							$value=get_option($option);
							$value=$value ? $value : $default;
							$label=($args['label']) ? $args['label'] : $name;
							$label="<label for='$option'>" . __("$label", 'woopus') . "</label>";
							$type=$args['type'];
							$description=($args['description']) ? "<p id='$option-description' class=description>{$args['description']}</p>" : "";
							$readonly=($args['readonly']) ? "readonly" : "";
							if($args['copylink']) {
								$scripts.="jQuery('[id*=\"$option\"]')[0].select();document.execCommand('copy');";
								$class.=" copylink";
							}
							switch ($type) {
								case 'boolean':
								$type='checkbox';
								if($args['label']) {
									$before=$name;
									$after=$label;
								} else {
									unset($before);
									$after=$label;
								}
								if($readonly) $onclick="onclick='return false;'";
								// $before=$name;
								$input="<input id='$option' name='$option' type='$type' $checked $readonly $onclick />";
								// $after=$label;
								break;

								case 'showlink':
								$before=$name;
								$input="<a href='$value'>$value</a>";
								break;

								case 'textarea';
								$before=$name;
								$input="<textarea id='$option' name='$option' cols=40 rows=10 class=autoresize>$value</textarea>";
								break;

								case 'string';
								case '';
								case NULL;
								$type='text';

								default:
								$class.="regular-text $class";
								$input="<input id='$option' name='$option' type='$type' class='$class' value='$value' $onclick $readonly/>";
								$before=$label;
								break;
							}
							if($scripts) $scripts="<script type='text/javascript'>$scripts</script>";
							echo "
							<tr valign='top'>
								<th scope='row'>$before</th>
								<td>
									$input$after
									$description
									$scripts
								</td>
							</tr>";
						};
					};

					# Dependencies section
					if(!empty($WooPUS_Recommended)) {
						?>
						<tr><th colspan=2>
							<h2><?php _e("Recommended plugins", 'woopus');?></h2>
						</th></tr>
						<?php
						foreach ($WooPUS_Recommended as $plugin => $action) {
							echo "<tr valign='top'><th scope='row'></th><td>";
								echo $action;
								echo "</td></tr>";
							}
						}
						?>
				</table>
				<?php if ( current_user_can( 'manage_options' ) ) submit_button(); ?>
			</form>
		</div>
		<?php if(file_exists) {
			echo '<div class="column">';
			include('debug.php');
			echo '</div>';
		}	?>
	</div>
</div>
