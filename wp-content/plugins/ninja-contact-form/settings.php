<?php

add_action( 'admin_init', 'ncf_register_settings' );

function ncf_register_settings() {


	register_setting( 'ncf_options', 'ncf_options', 'ncf_options_validate' );

	add_settings_section('ncf_mode', 'Plugin mode', 'ncf_colors_text', 'ncf');
	add_settings_field('ncf_test_mode', "Test mode during setup", 'ncf_test_mode_str', 'ncf', 'ncf_mode');

	add_settings_section('ncf_form_settings', 'Form Settings', 'ncf_colors_text', 'ncf');
	add_settings_field('ncf_email', "Email address to send messages to:", 'ncf_email_str', 'ncf', 'ncf_form_settings');
	add_settings_field('ncf_email_title', "Title for emails received from NKS Contact Form:", 'ncf_email_title_str', 'ncf', 'ncf_form_settings');
	add_settings_field('ncf_enable_test', "Human Test:", 'ncf_test_str', 'ncf', 'ncf_form_settings');
    //echo '</div>';

	add_settings_section('ncf_theme', 'Theme Settings', 'ncf_colors_text', 'ncf');
	add_settings_field('ncf_layout_theme', "Form Theme", 'ncf_layout_theme_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_base_color', "Sidebar Base Color", 'ncf_base_color_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_image_bg', 'Choose Background Image:', 'ncf_image_bg_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_custom_bg', 'Your custom background:', 'ncf_custom_bg_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_userpic_style', "Profile picture style:", 'ncf_userpic_style_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_invert_style', "Button style:", 'ncf_invert_style_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_flat_socialbar', 'Social bar position:', 'ncf_flat_socialbar_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_show_social', "Show social bar:", 'ncf_show_social_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_custom_css', "Custom CSS (stored during updates):", 'ncf_custom_css_str', 'ncf', 'ncf_theme');
	add_settings_field('ncf_color_schema', "Schema", 'ncf_color_schema_str', 'ncf', 'ncf_theme', array('hidden' => true));
	add_settings_field('ncf_rgba', "Rgba", 'ncf_rgba_str', 'ncf', 'ncf_theme', array('hidden' => true));

	add_settings_section('ncf_profile', 'Profile Settings', 'ncf_colors_text', 'ncf');
	add_settings_field('ncf_userpic', 'Your Profile picture or logo (automatically resized to 110x110px):', 'ncf_userpic_str', 'ncf', 'ncf_profile');
	add_settings_field('ncf_user_firstname', 'Profile thin first line (ex. First Name):', 'ncf_user_firstname_str', 'ncf', 'ncf_profile');
	add_settings_field('ncf_user_lastname', 'Profile bold second line (ex. Last Name or Company Name):', 'ncf_user_lastname_str', 'ncf', 'ncf_profile');
	add_settings_field('ncf_user_title', 'Profile third line (ex. your Title or Company Motto):', 'ncf_user_title_str', 'ncf', 'ncf_profile');
	add_settings_field('ncf_user_bio', 'Message to sender (ex. text with your short bio or company history):', 'ncf_user_bio_str', 'ncf', 'ncf_profile');

  add_settings_section('ncf_social', 'Social Settings', 'ncf_colors_text', 'ncf');
	add_settings_field('ncf_facebook', "Facebook URL:", 'ncf_facebook_str', 'ncf', 'ncf_social');
	add_settings_field('ncf_twitter', "Twitter URL:", 'ncf_twitter_str', 'ncf', 'ncf_social');
	add_settings_field('ncf_pinterest', "Pinterest URL:", 'ncf_pinterest_str', 'ncf', 'ncf_social');
  add_settings_field('ncf_youtube', "YouTube URL:", 'ncf_youtube_str', 'ncf', 'ncf_social');
	add_settings_field('ncf_vimeo', "Vimeo URL:", 'ncf_vimeo_str', 'ncf', 'ncf_social');
	add_settings_field('ncf_linkedin', "Linkedin URL:", 'ncf_linkedin_str', 'ncf', 'ncf_social');
	add_settings_field('ncf_gplus', "Google Plus URL:", 'ncf_gplus_str', 'ncf', 'ncf_social');
	add_settings_field('ncf_rss', "Dribbble URL:", 'ncf_rss_str', 'ncf', 'ncf_social');

//	add_settings_section('ncf_text', 'Text Settings', 'ncf_colors_text', 'ncf');
//	add_settings_field('ncf_calltoaction', "Call to Action Text", 'ncf_calltoaction_str', 'ncf', 'ncf_text');
//	add_settings_field('ncf_success_message', "Success Message", 'ncf_success_message_str', 'ncf', 'ncf_text');

	add_settings_section('ncf_other', 'Other Settings', 'ncf_colors_text', 'ncf');
	add_settings_field('ncf_sidebar_pos', "Sidebar position:", 'ncf_sidebar_pos_str', 'ncf', 'ncf_other');
	add_settings_field('ncf_label_style', "Trigger (label with email icon) style:", 'ncf_label_style_str', 'ncf', 'ncf_other');
	add_settings_field('ncf_label_top', "Label CSS 'top' value (please enter value valid for CSS ex. '50%' or '200px'):", 'ncf_label_top_str', 'ncf', 'ncf_other');
	add_settings_field('ncf_label_vis', "Label visibility:", 'ncf_label_vis_str', 'ncf', 'ncf_other');
	add_settings_field('ncf_label_mouseover', "Mouseover opening:", 'ncf_label_mouseover_str', 'ncf', 'ncf_other');
  add_settings_field('ncf_fade_content', "Fade out main content when sidebar is exposed:", 'ncf_fade_content_str', 'ncf', 'ncf_other');
	add_settings_field('ncf_success_message', "Success message:", 'ncf_success_message_str', 'ncf', 'ncf_other');
}

$ncf_cached_opts;

function ncf_get_options()
{
	global $ncf_cached_opts;

	if (isset($ncf_cached_opts)) return $ncf_cached_opts;

	$options = get_option('ncf_options');

	if (empty($options['ncf_test_mode'])) {
		$options['ncf_test_mode'] = '';
	}

	if (empty($options['ncf_position'])) {
		$options['ncf_position'] = "right";
	}
	if (empty($options['ncf_email'])) {
		$options['ncf_email'] = get_bloginfo('admin_email');
	}
	if (empty($options['ncf_enable_test'])) {
		$options['ncf_enable_test'] = false;
	}

	if (empty($options['ncf_email_title'])) {
		$options['ncf_email_title'] = __( " Contact Form Submission", 'ninja-contact-form' );
	}

	if (empty($options['ncf_success_message'])) {
		$options['ncf_success_message'] = __("Your message was successfully sent!", 'ninja-contact-form' );
	}


	// PROFILE
	if (empty($options['ncf_userpic'])) {
		//$options['ncf_userpic'] = plugins_url('/img/wolf.jpg', __FILE__);
		$options['ncf_userpic'] = '';
	}

	if (empty($options['ncf_user_firstname'])) {
		$options['ncf_user_firstname'] = '';
	}

	if (empty($options['ncf_user_lastname'])) {
		$options['ncf_user_lastname'] = '';
	}

	if (empty($options['ncf_user_title'])) {
		$options['ncf_user_title'] = '';
	}

	if (empty($options['ncf_user_bio'])) {
		$options['ncf_user_bio'] = '';
	}

	// SOCIAL
	if (empty($options['ncf_facebook'])) {
		$options['ncf_facebook'] = '';
	}
	if (empty($options['ncf_twitter'])) {
		$options['ncf_twitter'] = '';
	}
	if (empty($options['ncf_pinterest'])) {
		$options['ncf_pinterest'] = '';
	}
	if (empty($options['ncf_youtube'])) {
		$options['ncf_youtube'] = '';
	}
	if (empty($options['ncf_vimeo'])) {
		$options['ncf_vimeo'] = '';
	}
	if (empty($options['ncf_linkedin'])) {
		$options['ncf_linkedin'] = '';
	}
	if (empty($options['ncf_gplus'])) {
		$options['ncf_gplus'] = '';
	}
	if (empty($options['ncf_rss'])) {
		$options['ncf_rss'] = '';
	}
	// THEME
	if (empty($options['ncf_theme'])) {
		$options['ncf_theme'] = 'flat';
	}

	if (empty($options['ncf_flat_socialbar'])) {
		$options['ncf_flat_socialbar'] = 'top';
	}

	if (empty($options['ncf_invert_style'])) {
		$options['ncf_invert_style'] = '';
	}

	if (empty($options['ncf_show_social'])) {
		$options['ncf_show_social'] = 'yes';
	}

	if (empty($options['ncf_custom_css'])) {
		$options['ncf_custom_css'] = '';
	}

	if (empty($options['ncf_base_color'])) {
		$options['ncf_base_color'] = '{"flat": "#c0392b", "cube": "#c0392b", "minimalistic": "#0f9267", "aerial": "#292929"}';
	}

	if (empty($options['ncf_color_schema'])) {
		$options['ncf_color_schema'] = '#c0392b,#cf4739,#cd3424,#d9593e,#c84c3f,#bb2d1f,#e96d3d,#e94e3d,#2f1420';
	}

	if (empty($options['ncf_rgba'])) {
		$options['ncf_rgba'] = '';
	}

	if (empty($options['ncf_image_bg'])) {
		$options['ncf_image_bg'] = 'none';
	}

	if (empty($options['ncf_custom_bg'])) {
		$options['ncf_custom_bg'] = '';
	}

	// OTHER
	if (empty($options['ncf_fade_content'])) {
		$options['ncf_fade_content'] = 'none';
	}

	if (empty($options['ncf_sidebar_pos'])) {
		$options['ncf_sidebar_pos'] = 'left';
	}

	if (empty($options['ncf_label_style'])) {
		$options['ncf_label_style'] = 'circle';
	}

	if (empty($options['ncf_label_top'])) {
		$options['ncf_label_top'] = '50%';
	}

	if (empty($options['ncf_label_vis'])) {
		$options['ncf_label_vis'] = 'visible';
	}
	if (empty($options['ncf_label_vis_selector'])) {
		$options['ncf_label_vis_selector'] = '';
	}

	if (empty($options['ncf_label_mouseover'])) {
		$options['ncf_label_mouseover'] = '';
	}

	if (empty($options['ncf_userpic_style'])) {
		$options['ncf_userpic_style'] = 'theme_custom';
	}
	$ncf_cached_opts = $options;
	return $options;
}

function ncf_colors_text() {
}

function ncf_email_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_email' name='ncf_options[ncf_email]' size='40' type='text' value='{$options['ncf_email']}' style='' />";
}

function ncf_test_mode_str() {
	$options = ncf_get_options();
	$style = $options['ncf_test_mode'];
	$first_checked = $style == 'yes' ? 'checked="checked"' : '';

	echo "
	<p><input id='ncf_test_mode' name='ncf_options[ncf_test_mode]' type='checkbox' value='yes' {$first_checked} style='' /> <label for='ncf_test_mode'>Visible only for logged-in admins</label></p>
	";
}

function ncf_email_title_str () {
	$options = ncf_get_options();
 echo get_bloginfo('name') . " <input id='ncf_email_title' name='ncf_options[ncf_email_title]' size='100' type='text' value='{$options['ncf_email_title']}' style='' />";
}


function ncf_base_color_str() {
	$options = ncf_get_options();
    $basecolor = json_decode($options['ncf_base_color']);
    $theme = $options['ncf_theme'];
		$currentBase = $basecolor -> $theme;
    $previewpic = empty($options['ncf_userpic']) ? plugins_url('/img/wolf.jpg', __FILE__) : $options['ncf_userpic'];
    $previewname = empty($options['ncf_user_firstname']) ? 'John' : $options['ncf_user_firstname'];
    $previewname2 = empty($options['ncf_user_firstname']) ? '' : $options['ncf_user_firstname'];
    $previewlastname = empty($options['ncf_user_lastname']) ? 'Appleseed' : $options['ncf_user_lastname'];
    $previewtitle = empty($options['ncf_user_title']) ? 'Blog Awesome Author' : $options['ncf_user_title'];
    $previewbio = empty($options['ncf_user_bio']) ? 'Hello lovely visitor! Send me a message and you will have my answer.' : $options['ncf_user_bio'];
    $position = $options['ncf_flat_socialbar'];
		$url = plugins_url('/img', __FILE__);
		$bgimage = $options['ncf_image_bg'];
		$userpic = $options['ncf_userpic_style'];

		if ($bgimage !== 'none') {
			if($bgimage === 'custom') {
				$bgstyle = 'background-image: url(' . $options['ncf_custom_bg'] . ')';
			} else {
				$bgstyle = 'background-image: url(' . plugins_url('/img/bg/' . $options['ncf_image_bg']. '.jpg', __FILE__) . ')';
			}
		} else {
			$bgstyle = '';
		}

		echo "<div class='colorswrap'><p>Choose theme base color...</p><span class='colorsliders cs_flat' data-theme='flat'></span><span class='colorsliders cs_minimalistic' data-theme='minimalistic'></span><span class='colorsliders cs_aerial' data-theme='aerial'></span><p>...or enter color in HEX format here:</p></div>";
    echo "<input id='ncf_base_color' name='ncf_options[ncf_base_color]' type='hidden' value='{$options['ncf_base_color']}' style='' />";
    echo "<input id='base_color_flat' name='base_color_flat' data-color-format='hex' size='40' type='text' value='{$basecolor -> flat}' style='display: none;' />";
    echo "<input id='base_color_minimalistic' name='base_color_minimalistic' data-color-format='hex' size='40' type='text' value='{$basecolor -> minimalistic}' style='display: none;' />";
    echo "<input id='base_color_aerial' name='base_color_aerial' data-color-format='hex' size='40' type='text' value='{$basecolor -> aerial}' style='display: none;' />
	<div id='ncf_theme_preview' class='ncf_up_style_{$userpic}'>
        <p>Theme demo:</p>
        <div class='ncf_theme_preview_flat'>
            <div class='ncf_flat' style='{$bgstyle}'>
                <div class='ncf_sidebar_cont_scrollable'>
                    <div class='ncf_sidebar_cont shrinked'>
                        <div class='ncf_sidebar_header'>
                            <div class='ncf_sidebar_socialbar'>
                                <ul>
                                    <li class='ncf_bg_color1'><a href='' class='ncf_rss'></a></li>
                                    <li class='ncf_bg_color2'><a href='' class='ncf_gplus'></a></li>
                                    <li class='ncf_bg_color3'><a href='' class='ncf_linkedin'></a></li>
                                    <li class='ncf_bg_color4'><a href='' class='ncf_vimeo'></a></li>
                                    <li class='ncf_bg_color5'><a href='' class='ncf_youtube'></a></li>
                                    <li class='ncf_bg_color6'><a href='' class='ncf_pinterest'></a></li>
                                    <li class='ncf_bg_color7'><a href='' class='ncf_twitter'></a></li>
                                    <li class='ncf_bg_color8'><a href='' class='ncf_facebook'></a></li>
                                </ul>
                            </div>
                            <div class='ncf_sidebar_header_userinfo ncf_bg_color1'>
                                <div class='ncf_userpic'>
                                        <img src='{$previewpic}' alt=''>

                                </div>
                                <div class='ncf_user_credentials'>
                                    <span class='ncf_user_firstname'>{$previewname}</span>
                                    <span class='ncf_user_lastname'>{$previewlastname}</span>
                                    <span class='ncf_user_title ncf_text_color9'>{$previewtitle}</span>
                                </div>
                            </div>
                        </div>
                        <div class='ncf_sidebar_content'>
                            <div class='ncf_user_bio'>{$previewbio}</div>
                            <div class='ncf_form_input_wrapper ncf_bg_color8 ncf_name_field'>
                                <input type='text' name='ncf_name_field' id='ncf_name_field' placeholder='Your name *' data-rules='required|min[2]|max[16]'>
                            </div>
                            <div class='ncf_form_btn_wrapper'>
                                <a class='ncf_button ncf_bg_color8 ncf_boxshadow_color3' data-box-shadow='0 2px 0px 2px' href='#'>Send</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class='ncf_theme_preview_minimalistic'>
            <div class='ncf_minimalistic' style='{$bgstyle}'>
                <div class='ncf_sidebar_cont_scrollable'>
                    <div class='ncf_sidebar_cont shrinked'>
        								<div class='ncf_sidebar_header'>
                        <div class='ncf_sidebar_header_userinfo'>
                            <div class='ncf_userpic'>
                                <img src='{$previewpic}' alt=''>
                            </div>
                            <div class='ncf_user_credentials'>
                                <span class='ncf_user_firstname ncf_text_color1'>{$previewname2}</span>
                                <span class='ncf_user_lastname ncf_text_color1'>{$previewlastname}</span>
                                <span class='ncf_user_title'>{$previewtitle}</span>
                            </div>
		                        </div>
                              <div class='ncf_line_sep'></div>
                              <div class='ncf_sidebar_socialbar'>
	                               <ul>
	                               		<li><a class='ncf_facebook' href='https://www.facebook.com/'></a></li><li><a class='ncf_twitter ncf_bg_color1' href='https://www.facebook.com/'></a></li><li><a class='ncf_pinterest' href='https://www.facebook.com/'></a></li><li><a class='ncf_youtube' href='https://www.facebook.com/'></a></li><li><a class='ncf_vimeo' href='https://www.facebook.com/'></a></li><li><a class='ncf_linkedin' href='https://www.facebook.com/'></a></li><li><a class='ncf_gplus' href='https://www.facebook.com/'></a></li><li><a class='ncf_rss' href='https://www.facebook.com/'></a></li>
	                               </ul>
	                           </div>
                    			</div>
        				        <div class='ncf_line_sep'></div>

                    <div class='ncf_sidebar_content'>
                        <div class='ncf_user_bio'>{$previewbio}</div>

                            <div class='ncf_form_input_wrapper ncf_name_field ncf_bg_color1'>
                                <input type='text' class='ncf_border_color3 ncf_outline_color4' name='ncf_name_field' id='ncf_name_field2' placeholder='Your name *'>
                            </div>
                            <div class='ncf_form_btn_wrapper'>
                                <a class='ncf_button ncf_bg_color1 ncf_boxshadow_color5' data-box-shadow='0 2px 0px 2px' href='#'>Send</a>
                            </div>

                    </div>
	</div>
	</div>
	</div>
	</div>

	<div class='ncf_theme_preview_aerial'>
            <div class='ncf_aerial' style='{$bgstyle}'>
                <div class='ncf_sidebar_cont_scrollable'>
                    <div class='ncf_sidebar_cont'>
        								<div class='ncf_sidebar_header'>
        								<div class='ncf_sidebar_socialbar'>
                           <ul>
                              <li><a class='ncf_facebook' href='https://www.facebook.com/'></a></li><li><a class='ncf_twitter' href='https://www.facebook.com/'></a></li><li><a class='ncf_pinterest' href='https://www.facebook.com/'></a></li><li><a class='ncf_youtube' href='https://www.facebook.com/'></a></li><li><a class='ncf_vimeo' href='https://www.facebook.com/'></a></li><li><a class='ncf_linkedin' href='https://www.facebook.com/'></a></li><li><a class='ncf_gplus' href='https://www.facebook.com/'></a></li><li><a class='ncf_rss' href='https://www.facebook.com/'></a></li>
                           </ul>
                        </div>
                        <div class='ncf_sidebar_header_userinfo'>
                            <div class='ncf_userpic'>
                                <img src='{$previewpic}' alt=''>
                            </div>
                            <div class='ncf_user_credentials'>
                                <span class='ncf_user_firstname ncf_text_color1'>{$previewname2}</span>
                                <span class='ncf_user_lastname ncf_text_color1'>{$previewlastname}</span>
                                <span class='ncf_user_title ncf_text_color2'>{$previewtitle}</span>
                            </div>
		                        </div>
                    			</div>

                    <div class='ncf_sidebar_content'>
                        <div class='ncf_user_bio ncf_text_color1'>{$previewbio}</div>

                            <div class='ncf_form_input_wrapper ncf_name_field'>
                                <input type='text' class='ncf_text_color1 ncf_bg_rgb_color1' name='ncf_name_field' id='ncf_name_field3' placeholder='Your name *' value='Your name *'>
                            </div>
                            <div class='ncf_form_input_wrapper ncf_email_field'>
                                <input type='text' class='ncf_text_color1 ncf_bg_rgb_color1' name='ncf_email_field' id='ncf_email_field' placeholder='Your name *' value='Your email *'>
                            </div>
                            <div class='ncf_form_btn_wrapper'>
                                <a class='ncf_button ncf_bg_color1 ncf_boxshadow_color5' data-box-shadow='0 2px 0px 2px' href='#'>Send</a>
                            </div>

                    </div>
	</div>
	</div>
	</div>
	</div>
	</div>
	<script>
	jQuery('.colorswrap').closest('.settings-form-wrapper').addClass('{$theme}');
	jQuery(document).ready(function($) {

        var colors = {
        	flat: {
        	 	baseColor: '{$basecolor->flat}',
        		colorSchema: {
              'color1' : {h: 0, s: 0, v: 0},
              'color2' : {h: 0, s: -5, v: +6},
              'color3' : {h: 0, s: +5, v: +5},
              'color4' : {h: +5, s: -6, v: +10},
              'color5' : {h: 0, s: -9, v: +3},
              'color6' : {h: 0, s: +6, v: -2},
              'color7' : {h: +11, s: -4, v: +16},
              'color8' : {h: 0, s: -4, v: +16},
              'color9' : {h: 0, s: +18, v: -39}
            },
            swatches: [
              '#c0392b',
              'a3503c',
              '925873',
              '927758',
              '589272',
              '588c92',
              '2bb1c0',
              '2b8ac0',
              'e96701',
              'c02b74'
            ]
        	},

        	minimalistic: {
        	  baseColor: '{$basecolor->minimalistic}',
        		colorSchema: {
              'color1' : {h: 0, s: 0, v: 0},
              'color2' : {h: 0, s: +10, v: -13},
              'color3' : {h: 0, s: -72, v: +30},
              'color4' : {h: -1, s: -82, v: +33},
              'color5' : {h: -3, s: -9, v: -8}
            },
            swatches: [
              '#0f9267',
              '#7f2566',
              '#9c9a2d',
              '#2d9c92',
              '#920f2a'
            ]
        	},
        	aerial: {
        	  baseColor: '{$basecolor->aerial}',
        		colorSchema: {
              'color1' : {h: 0, s: 0, v: 0},
              'color2' : {h: 0, s: 0, v: +5}
            },
            swatches: [
              '#292929',
              '#ffffff'
            ]
        	}
        }

        var theme = '{$theme}';
        var baseColorInput = $('#ncf_base_color');
        var baseColorRGB = $('#ncf_rgba');
        var colorSchemaInput = $('#ncf_color_schema');

				$('.colorsliders').each(function(i, el){

						var slidertheme = $(this).attr('data-theme');
						var colorInput = $('input#base_color_' + slidertheme);
						var opts = {
		            flat: true,
		            previewformat: 'hex',
		            color:  colors[slidertheme]['baseColor'],
		            connectedinput: colorInput,
		            order: {
		                hsl: 1,
		                preview: 2
		            },
		            customswatches: 'swatches_' + slidertheme,
		            swatches: colors[slidertheme]['swatches'],
		            onchange: function(container, color) {
		                var currentBaseColor = colorInput.val();
		                var RGB = tinycolor(currentBaseColor).toRgb();
		                try {
		                    var colorJSON = JSON.parse(baseColorInput.val());
		                    colorJSON[slidertheme] = currentBaseColor;
		                    baseColorInput.val(JSON.stringify(colorJSON))

		                } catch (e) {
		                }

		                colorSchemaInput.val(applySchema(currentBaseColor, slidertheme));
		                baseColorRGB.val([RGB.r, RGB.g, RGB.b].join(','));

		            }
		        };
						$(this).ColorPickerSliders(opts);
				});

				colorSchemaInput.val(applySchema($('input#base_color_' + theme).val(), theme));

        function applySchema (baseColor, theme) {
            var colorItemsSelector = '#ncf_theme_preview .ncf_theme_preview_' + theme + ' [class*=color]';
            var colorItems = $(colorItemsSelector);

            var hsvBase = tinycolor(baseColor).toHsv();
            var colorArr = [];

            if (!hsvBase) return;

						var themeSchemas = colors[theme]['colorSchema'];
						var color, newHsv, newColor, schema;
            for (color in themeSchemas) {

               schema = themeSchemas[color];
               newHsv = {
                 h: hsvBase.h + schema.h,
                 s: hsvBase.s + schema.s * 0.01,
                 v: hsvBase.v + schema.v * 0.01
               };

               // normalize
               newHsv = normalize(newHsv);
               newColor = '#' + tinycolor(newHsv).toHex();
               colorArr.push(newColor);
            };
						colorItems.each(function(i,el){
                var jqEl = $(el);
                var classN = el.className;
                var color = classN.match(/color(\d{1,2})/g);
                var i, len, index;
                var newColor;

                if (color) {
                	for (i = 0, len = color.length; i < len;i++) {
                    index = color[i].match(/\d{1,2}/);
                    if (index && (index = index[0])){
                    	colorize(jqEl, colorArr[index - 1], index);
                    }
                  }
                }
            });
            //console.log('SCHEMA', colorArr);
            return colorArr;
        }

        function colorize (jqEl, color, index){

           if (!color) return;

           if (jqEl.is('.ncf_text_color' + index)) {
               jqEl.css('color', color);
           }

           else if (jqEl.is('.ncf_bg_color' + index)) {
               jqEl.css('backgroundColor', color);
           }

           else if (jqEl.is('.ncf_border_color' + index)) {
               jqEl.css('borderColor', color);
           }

           else if (jqEl.is('.ncf_boxshadow_color' + index)) {
               jqEl.css('boxShadow', jqEl.attr('data-box-shadow') + ' ' + color);
           }

           else if (jqEl.is('.ncf_outline_color' + index)) {
               jqEl.css('outlineColor', color);
           }
           if (jqEl.is('.ncf_bg_rgb_color' + index)) {
           			var RGB = tinycolor(color).toRgb();
               jqEl.css('backgroundColor', 'rgba(' + [RGB.r, RGB.g, RGB.b].join(',') + ',0.1)');
           }
        }

        function normalize (hsvObj) {
            if (hsvObj.h > 360) hsvObj.h = 360;
            else if (hsvObj.h < 0) hsvObj.h = 0;

            if (hsvObj.s > 1) hsvObj.s = 1;
            else if (hsvObj.s < 0) hsvObj.s = 0;

            if (hsvObj.v > 1) hsvObj.v = 1;
            else if (hsvObj.v < 0) hsvObj.v = 0;

            return hsvObj;
        }

				var preview = $('#ncf_theme_preview');
        var sidebars = $('.ncf_theme_preview_flat .ncf_flat, .ncf_theme_preview_minimalistic .ncf_minimalistic, .ncf_theme_preview_aerial .ncf_aerial')
        var socialbarpos = '{$position}';
        var cont = $('.ncf_theme_preview_flat .ncf_sidebar_header, .ncf_theme_preview_aerial .ncf_sidebar_header');
        var custom = $('.ncf_custom_bg');

        if (socialbarpos === 'bottom') {
        		cont.each(function(){
	            var t = $(this);
	            t.append(t.find('.ncf_sidebar_socialbar'));
        		})
        } else {
        		cont.each(function(){
	            var t = $(this);
	            t.prepend(t.find('.ncf_sidebar_socialbar'));
        		})
        }

        $('#ncf_flat_socialbar').change(function(){
            var val = $(this).val();
            if (val === 'bottom') {
              cont.each(function(){
		            var t = $(this);
		            t.append(t.find('.ncf_sidebar_socialbar'));
	            })
            } else if (val === 'top') {
              cont.each(function(){
		            var t = $(this);
		            t.prepend(t.find('.ncf_sidebar_socialbar'));
	            })
            }
       	})

          $('#ncf_image_bg').change(function(){
               var val = $(this).val();
               var style;

               if (val === 'none') {
                 	sidebars.css('backgroundImage', '');
                 	custom.fadeOut(200);

               } else if (val === 'custom') {
               		style = 'url({$options['ncf_custom_bg']})';
               		if (/jpeg|jpg|png|gif/.test(style)) {
               		   sidebars.css('backgroundImage', style);
               		} else {
               		   sidebars.css('backgroundImage', 'none');
               		}
                  custom.fadeIn(200);
               } else {
									sidebars.css('backgroundImage', 'url({$url}/bg/' + val + '.jpg)');
									custom.fadeOut(200);

               }
          });

          showActiveTheme(theme);
          $('#ncf_theme').change(function(){
          	var selectedTheme = $(this).val();
          	showActiveTheme(selectedTheme);
          	colorSchemaInput.val(applySchema($('input#base_color_' + selectedTheme).val(), selectedTheme));

          });

          function showActiveTheme (theme) {
            $('#ncf_theme_preview [class*=ncf_theme_preview], .colorsliders, input[id^=base_color]').hide();
        		$('.ncf_theme_preview_' + theme + ', .colorsliders.cs_' + theme + ', #base_color_' + theme).show();
        		//console.log(theme)
        		$('.ncf_theme').removeClass('minimalistic aerial flat').addClass(theme);
          }

          $('input[id*=ncf_userpic_style]').change(function(){
	          var val = $(this).val();
	          if (val === 'theme_custom') {
							preview.addClass('ncf_up_style_theme_custom');
	          } else {
	          	preview.removeClass('ncf_up_style_theme_custom');
	          }
          })
	});
	</script>
	";
}

function ncf_color_schema_str () {
    $options = ncf_get_options();
    echo "<input id='ncf_color_schema' name='ncf_options[ncf_color_schema]' type='text' value='{$options['ncf_color_schema']}' style='' />";
}

function ncf_rgba_str () {
    $options = ncf_get_options();
    echo "<input id='ncf_rgba' name='ncf_options[ncf_rgba]' type='text' value='{$options['ncf_rgba']}' style='' />";
}

function ncf_layout_theme_str () {
    $options = ncf_get_options();
		$theme = $options['ncf_theme'];
		echo "
		<select id='ncf_theme' name='ncf_options[ncf_theme]'>
	    <option value='flat' " . ($theme === 'flat' ? 'selected="selected"' : '') . ">Flat Dark</option>" .
/*	  <option value='cube' " . ($theme === 'cube' ? 'selected="selected"' : '') . ">Cube</option> */
		  "<option value='minimalistic' " . ($theme === 'minimalistic' ? 'selected="selected"' : '') . ">Minimalistic White</option>" .
		  "<option value='aerial' " . ($theme === 'aerial' ? 'selected="selected"' : '') . ">Aerial</option>
	  </select>
    ";
}

function ncf_test_str() {
	$options = ncf_get_options();

	if(@$options['ncf_enable_test'] == "enable") {
		$ncf_enable_test = "checked='checked'" ;
	} else {
        $ncf_enable_test = '';
    }

	echo "	<input id='ncf_enable_test' name='ncf_options[ncf_enable_test]' type='checkbox' value='enable' {$ncf_enable_test} style='' /> <label for='ncf_enable_test'>Enable simple test (math question) to help prevent spam</label><br>
	";
}



function ncf_calltoaction_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_calltoaction' name='ncf_options[ncf_calltoaction]' size='40' type='text' value='{$options['ncf_calltoaction']}' style='' />";
}

function ncf_success_message_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_success_message' name='ncf_options[ncf_success_message]' size='100' type='text' value='{$options['ncf_success_message']}' style='' />";
}

function ncf_userpic_str() {
    $options = ncf_get_options();
    if (empty($options['ncf_userpic'])) {
        echo "<input id='ncf_userpic_file' type='file' name='ncf_pic' value='{$options['ncf_userpic']}' /> <input name='Submit' type='submit' class='button-primary' value='Upload' />";
    } else {
        echo '<div class="ncf_userpic"><img width="110" height="110" src="' . $options['ncf_userpic'] . '" alt=""/></div>';
        echo '<p><input  style="margin-top: 0;" type="submit" class="button-secondary" id="ncf_remove_pic" value="Remove this pic"/></p>
                           <script>
                   jQuery("#ncf_remove_pic").on("click keydown", function(){
                        jQuery("#ncf_userpic").val("");
                   })
                   </script>
               ';
        echo "<span>...or upload new one</span><br><input id='ncf_userpic_file' type='file' name='ncf_pic' value='{$options['ncf_userpic']}' /> <input name='Submit' type='submit' class='button-primary' value='Upload' />";
    }
    echo " <input id='ncf_userpic' name='ncf_options[ncf_userpic]' size='100' type='hidden' value='{$options['ncf_userpic']}' style='' />";
}

function ncf_image_bg_str() {
    $options = ncf_get_options();
    $bg = $options['ncf_image_bg'];
		$isCustom = $bg === 'custom';
    echo "<select id='ncf_image_bg' name='ncf_options[ncf_image_bg]'>
    <option value='none' " . ($bg === 'none' ? 'selected="selected"' : '') . ">Default</option>
    <option value='custom' " . ($bg === 'custom' ? 'selected="selected"' : '') . ">My custom background</option>
    <option value='blur1' " . ($bg === 'blur1' ? 'selected="selected"' : '') . ">Blurred #1</option>
    <option value='blur2' " . ($bg === 'blur2' ? 'selected="selected"' : '') . ">Blurred #2</option>
    <option value='blur3' " . ($bg === 'blur3' ? 'selected="selected"' : '') . ">Blurred #3</option>
    <option value='blur4' " . ($bg === 'blur4' ? 'selected="selected"' : '') . ">Blurred #4</option>
    <option value='blur5' " . ($bg === 'blur5' ? 'selected="selected"' : '') . ">Blurred #5</option>
    <option value='blur6' " . ($bg === 'blur6' ? 'selected="selected"' : '') . ">Blurred #6</option>
    <option value='blur7' " . ($bg === 'blur7' ? 'selected="selected"' : '') . ">Blurred #7</option>
    <option value='blur8' " . ($bg === 'blur8' ? 'selected="selected"' : '') . ">Blurred #8</option>
    <option value='blur9' " . ($bg === 'blur9' ? 'selected="selected"' : '') . ">Blurred #9</option>
    <option value='blur10' " . ($bg === 'blur10' ? 'selected="selected"' : '') . ">Blurred #10</option>
    <option value='blur11' " . ($bg === 'blur11' ? 'selected="selected"' : '') . ">Blurred #11</option>
    <option value='blur12' " . ($bg === 'blur12' ? 'selected="selected"' : '') . ">Blurred #12</option>
    <option value='blur13' " . ($bg === 'blur13' ? 'selected="selected"' : '') . ">Blurred #13</option>
    <option value='blur14' " . ($bg === 'blur14' ? 'selected="selected"' : '') . ">Blurred #14</option>
    <option value='blur15' " . ($bg === 'blur15' ? 'selected="selected"' : '') . ">Blurred #15</option>
    <option value='pattern1' " . ($bg === 'pattern1' ? 'selected="selected"' : '') . ">Pattern #1</option>
    <option value='pattern2' " . ($bg === 'pattern2' ? 'selected="selected"' : '') . ">Pattern #2</option>
    <option value='pattern3' " . ($bg === 'pattern3' ? 'selected="selected"' : '') . ">Pattern #3</option>
    <option value='pattern4' " . ($bg === 'pattern4' ? 'selected="selected"' : '') . ">Pattern #4</option>
    <option value='pattern5' " . ($bg === 'pattern5' ? 'selected="selected"' : '') . ">Pattern #5</option>
    <option value='pattern6' " . ($bg === 'pattern6' ? 'selected="selected"' : '') . ">Pattern #6</option>
    <option value='pattern7' " . ($bg === 'pattern7' ? 'selected="selected"' : '') . ">Pattern #7</option>
    <option value='pattern8' " . ($bg === 'pattern8' ? 'selected="selected"' : '') . ">Pattern #8</option>
    <option value='pattern9' " . ($bg === 'pattern9' ? 'selected="selected"' : '') . ">Pattern #9</option>
    <option value='pattern10' " . ($bg === 'pattern10' ? 'selected="selected"' : '') . ">Pattern #10</option>
    <option value='pattern11' " . ($bg === 'pattern11' ? 'selected="selected"' : '') . ">Pattern #11</option>
    <option value='pattern12' " . ($bg === 'pattern12' ? 'selected="selected"' : '') . ">Pattern #12</option>
    <option value='pattern13' " . ($bg === 'pattern13' ? 'selected="selected"' : '') . ">Pattern #13</option>
    <option value='pattern14' " . ($bg === 'pattern14' ? 'selected="selected"' : '') . ">Pattern #14</option>
    <option value='pattern15' " . ($bg === 'pattern15' ? 'selected="selected"' : '') . ">Pattern #15</option>
    </select>";
		echo "
	  <script>
	  jQuery(function($){
        var isCustomBG = !!'{$isCustom}';
        var custom = $('.ncf_custom_bg');
				if (isCustomBG) {
					custom.show();
				}
	  })

    </script>
    ";
}

function ncf_custom_bg_str() {
    $options = ncf_get_options();
    if (empty($options['ncf_custom_bg'])) {
        echo "<input id='ncf_custom_bg' type='file' name='ncf_custom_bg' value='{$options['ncf_custom_bg']}' /> <input name='Submit' type='submit' class='button-primary' value='Upload' />";
        echo "<br><br><label for='ncf_custom_bg_url'>or use URL:</label> <input id='ncf_custom_bg_url' name='ncf_options[ncf_custom_bg]' size='100' type='text' value='{$options['ncf_custom_bg']}' style='' />";
    } else {
        echo '<div class="ncf_custom_bg" ><img src="' . $options['ncf_custom_bg'] . '" alt=""/></div>';
        echo "<span>...or upload new one</span><br><input id='ncf_custom_bg' type='file' name='ncf_custom_bg' value='{$options['ncf_custom_bg']}' /><input name='Submit' type='submit' class='button-primary' value='Upload' />";
	      echo "<br><br><label for='ncf_custom_bg_url'>Background image URL:</label><br><input id='ncf_custom_bg_url' name='ncf_options[ncf_custom_bg]' size='100' type='text' value='{$options['ncf_custom_bg']}' style='' />";
    }
    //echo " <input id='ncf_custom_bg' name='ncf_options[ncf_custom_bg]' size='100' type='hidden' value='{$options['ncf_custom_bg']}' style='' />";
}

function ncf_flat_socialbar_str() {
    $options = ncf_get_options();
    $position = $options['ncf_flat_socialbar'];
    echo "<select id='ncf_flat_socialbar' name='ncf_options[ncf_flat_socialbar]'>
    <option value='top' " . ($position === 'top' ? 'selected="selected"' : '') . ">Top</option>
    <option value='bottom' " . ($position === 'bottom' ? 'selected="selected"' : '') . ">Bottom</option>
    </select>";
}

function ncf_user_firstname_str()
{
    $options = ncf_get_options();
    echo " <input id='ncf_user_firstname' name='ncf_options[ncf_user_firstname]' size='100' type='text' value='{$options['ncf_user_firstname']}' style='' />";
}

function ncf_user_lastname_str()
{
    $options = ncf_get_options();
    echo " <input id='ncf_user_lastname' name='ncf_options[ncf_user_lastname]' size='100' type='text' value='{$options['ncf_user_lastname']}' style='' />";
}

function ncf_user_title_str()
{
    $options = ncf_get_options();
    echo " <input id='ncf_user_title' name='ncf_options[ncf_user_title]' size='100' type='text' value='{$options['ncf_user_title']}' style='' />";
}

function ncf_user_bio_str()
{
    $options = ncf_get_options();
    echo "<textarea cols='100' rows='10' id='ncf_user_bio' name='ncf_options[ncf_user_bio]' >" . $options['ncf_user_bio'] . "</textarea>";
}

function ncf_avatar_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_avatar' name='ncf_options[ncf_avatar]' size='100' type='text' value='{$options['ncf_avatar']}' style='' />";
}

function ncf_facebook_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_facebook' name='ncf_options[ncf_facebook]' size='100' type='text' value='{$options['ncf_facebook']}' style='' />";
}

function ncf_twitter_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_twitter' name='ncf_options[ncf_twitter]' size='100' type='text' value='{$options['ncf_twitter']}' style='' />";
}


function ncf_pinterest_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_pinterest' name='ncf_options[ncf_pinterest]' size='100' type='text' value='{$options['ncf_pinterest']}' style='' />";
}
function ncf_youtube_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_youtube' name='ncf_options[ncf_youtube]' size='100' type='text' value='{$options['ncf_youtube']}' style='' />";
}
function ncf_vimeo_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_vimeo' name='ncf_options[ncf_vimeo]' size='100' type='text' value='{$options['ncf_vimeo']}' style='' />";
}
function ncf_linkedin_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_linkedin' name='ncf_options[ncf_linkedin]' size='100' type='text' value='{$options['ncf_linkedin']}' style='' />";
}

function ncf_gplus_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_gplus' name='ncf_options[ncf_gplus]' size='100' type='text' value='{$options['ncf_gplus']}' style='' />";
}
function ncf_rss_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_rss' name='ncf_options[ncf_rss]' size='100' type='text' value='{$options['ncf_rss']}' style='' />";
}


function ncf_label_style_str() {
	$options = ncf_get_options();
	$val = $options['ncf_label_style'];
	$first_checked = $val == 'circle' ? 'checked="checked"' : '';
  $sec_checked = $val == 'circle_fade' ? 'checked="checked"' : '';
  $third_checked = $val == 'rectangle' ? 'checked="checked"' : '';


	echo "
	<p><input id='ncf_label_style_circle' name='ncf_options[ncf_label_style]' type='radio' value='circle' {$first_checked} style='' /> <label for='ncf_label_style_circle'>Base Color Circle</label></p>
	<p><input id='ncf_label_style_circle_fade' name='ncf_options[ncf_label_style]' type='radio' value='circle_fade' {$sec_checked} style='' /> <label for='ncf_label_style_circle_fade'>Semi-transparent Circle</label></p>
	<p><input id='ncf_label_style_rectangle' name='ncf_options[ncf_label_style]'  type='radio' value='rectangle' {$third_checked} style='' /> <label for='ncf_label_style_rectangle'>Rectangle</label></p>

	";
}

function ncf_label_top_str() {
	$options = ncf_get_options();
	echo " <input id='ncf_label_top' name='ncf_options[ncf_label_top]' size='10' type='text' value='{$options['ncf_label_top']}' style='' />";
}

function ncf_label_vis_str() {
	$options = ncf_get_options();
	$val = $options['ncf_label_vis'];
	$first_checked = $val == 'visible' ? 'checked="checked"' : '';
  $sec_checked = $val == 'hidden' ? 'checked="checked"' : '';
	$third_checked = $val == 'hidden_500' ? 'checked="checked"' : '';
	$forth_checked = $val == 'scroll' ? 'checked="checked"' : '';
	$fifth_checked = $val == 'scroll_into' ? 'checked="checked"' : '';

	echo "
	<p><input id='ncf_label_vis_visible' name='ncf_options[ncf_label_vis]'  type='radio' value='visible' {$first_checked} style='' /> <label for='ncf_label_vis_visible'>Visible</label></p>
	<p><input id='ncf_label_vis_hidden' name='ncf_options[ncf_label_vis]'  type='radio' value='hidden' {$sec_checked} style='' /> <label for='ncf_label_vis_hidden'>Don't show it</label></p>
	<p><input id='ncf_label_vis_hidden_500' name='ncf_options[ncf_label_vis]'  type='radio' value='hidden_500' {$third_checked} style='' /> <label for='ncf_label_vis_hidden_500'>Don't show label when screen is less than 500px wide</label></p>
	<p><input id='ncf_label_vis_scroll' name='ncf_options[ncf_label_vis]'  type='radio' value='scroll' {$forth_checked} style='' /> <label for='ncf_label_vis_scroll'>Fade in label only after user scrolls</label></p>
	<p><input id='ncf_label_vis_scroll_into' name='ncf_options[ncf_label_vis]'  type='radio' value='scroll_into' {$fifth_checked} style='' /> <label for='ncf_label_vis_scroll_into'>Fade in label only after element with selector scrolls into view.</label><br>
	<p style='padding-left: 20px;line-height: 26px;'>Please use any valid CSS selector like #id and .class (if field is empty label will be always visible)<br><input type='text' id='ncf_label_vis_selector' value='{$options['ncf_label_vis_selector']}' name='ncf_options[ncf_label_vis_selector]' value></p></p>
	";
}

function ncf_label_mouseover_str() {
	$options = ncf_get_options();
	$style = $options['ncf_label_mouseover'];
	$first_checked = $style == 'yes' ? 'checked="checked"' : '';

	echo "
	<p><input id='ncf_label_mouseover' name='ncf_options[ncf_label_mouseover]' type='checkbox' value='yes' {$first_checked} style='' /> <label for='ncf_label_mouseover'>Expose form on trigger mouseover</label></p>
	";
}
function ncf_userpic_style_str() {
	$options = ncf_get_options();
	$style = $options['ncf_userpic_style'];
	$first_checked = $style == 'theme_custom' ? 'checked="checked"' : '';
  $sec_checked = $style == 'none' ? 'checked="checked"' : '';

	echo "
	<p><input id='ncf_userpic_style_theme_custom' name='ncf_options[ncf_userpic_style]' type='radio' value='theme_custom' {$first_checked} style='' /> <label for='ncf_userpic_style_theme_custom'>Theme Custom</label></p>
	<p><input id='ncf_userpic_style_none' name='ncf_options[ncf_userpic_style]' type='radio' value='none' {$sec_checked} style='' /> <label for='ncf_userpic_style_none'>None</label></p>
	";
}

function ncf_invert_style_str() {
	$options = ncf_get_options();
	$style = $options['ncf_invert_style'];
	$first_checked = $style == 'invert' ? 'checked="checked"' : '';

	echo "
	<p><input id='ncf_invert_style' name='ncf_options[ncf_invert_style]' type='checkbox' value='invert' {$first_checked} style='' /> <label for='ncf_invert_style'>Invert button font color (useful with light color schemes)</label></p>
	";
	echo "<script>
	jQuery('#ncf_invert_style').change(function() {
	var t = jQuery(this).closest('.settings-form-wrapper');
	    if(this.checked) {
	        t.addClass('ncf_invert');
	    } else {
	        t.removeClass('ncf_invert');
	    }
	}).change();
	</script>";
}

function ncf_show_social_str() {
	$options = ncf_get_options();
  $vis = $options['ncf_show_social'];
 echo "<select id='ncf_show_social' name='ncf_options[ncf_show_social]'>
 <option value='show' " . ($vis === 'show' ? 'selected="selected"' : '') . ">Yes</option>
 <option value='hide' " . ($vis === 'hide' ? 'selected="selected"' : '') . ">No</option>
 </select>";
	echo "<script>
	jQuery('#ncf_show_social').change(function() {
	var val = jQuery(this).val();
		var t = jQuery(this).closest('.settings-form-wrapper');
	    if(val === 'show') {
	        t.removeClass('ncf_hide_social');
	    } else {
	        t.addClass('ncf_hide_social');
	    }
	}).change();
	</script>";
}

function ncf_custom_css_str()
{
    $options = ncf_get_options();
    echo "<textarea cols='100' rows='10' id='ncf_custom_css' name='ncf_options[ncf_custom_css]' >" . $options['ncf_custom_css'] . "</textarea>";
}

function ncf_fade_content_str () {
    $options = ncf_get_options();
	  $light_checked = $options['ncf_fade_content'] == 'light' ? 'checked="checked"' : '';
    $dark_checked = $options['ncf_fade_content'] == 'dark' ? 'checked="checked"' : '';
    $none_checked = $options['ncf_fade_content'] == 'none' ? 'checked="checked"' : '';
	echo "<p><input id='ncf_fade_content_none' name='ncf_options[ncf_fade_content]' type='radio' {$none_checked} value='none' style='' /> <label for='ncf_fade_content_none'>Don't fade (recommended to optimize animations in Chrome browser on Windows)</label></p>";
	  echo "<p><input id='ncf_fade_content_light' name='ncf_options[ncf_fade_content]' type='radio' {$light_checked} value='light' style='' /> <label for='ncf_fade_content_light'>Light overlay</label></p>";
   	echo "<p><input id='ncf_fade_content_dark' name='ncf_options[ncf_fade_content]' type='radio' {$dark_checked} value='dark' style='' /> <label for='ncf_fade_content_dark'>Dark overlay</label></p>";
}
function ncf_sidebar_pos_str () {
    $options = ncf_get_options();
    $left_checked = $options['ncf_sidebar_pos'] == 'left' ? 'checked="checked"' : '';
    $right_checked = $options['ncf_sidebar_pos'] == 'right' ? 'checked="checked"' : '';

   	echo "<p><input id='ncf_sidebar_pos_left' name='ncf_options[ncf_sidebar_pos]' type='radio' {$left_checked} value='left' style='' /> <label for='ncf_sidebar_pos_left'></label></p>";
   	echo "<p><input id='ncf_sidebar_pos_right' name='ncf_options[ncf_sidebar_pos]' type='radio' {$right_checked} value='right' style='' /> <label for='ncf_sidebar_pos_right'></label></p>";
}

function ncf_options_validate($plugin_options) {
    $options = get_option('plugin_options');

    if (isset($_FILES['ncf_pic']) && ($_FILES['ncf_pic']['size'] > 0)) {

        // Get the type of the uploaded file. This is returned as "type/extension"
        $arr_file_type = wp_check_filetype(basename($_FILES['ncf_pic']['name']));
        $uploaded_file_type = $arr_file_type['type'];

        // Set an array containing a list of acceptable formats
        $allowed_file_types = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

        // If the uploaded file is the right format
        if (in_array($uploaded_file_type, $allowed_file_types)) {

            // Options array for the wp_handle_upload function. 'test_upload' => false
            $upload_overrides = array('test_form' => false);

	          add_filter('wp_handle_upload_prefilter', 'custom_upload_filter' );


	  		    function custom_upload_filter( $file ){
	  			      $arr = wp_check_filetype(basename($file['name']));
	  			      $type = $arr['type'];
	  		        $file['name'] = 'ncf_userpic.' . str_replace('image/', '', $type);
			        return $file;
	  		    }

	        	//delete previous
		        //if (isset($plugin_options['ncf_userpic'])) unlink($plugin_options['ncf_userpic']);

            // Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
            $uploaded_file = wp_handle_upload($_FILES['ncf_pic'], $upload_overrides);

            // If the wp_handle_upload call returned a local path for the image
            if (isset($uploaded_file['file'])) {
                // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
                $file_name_and_location = $uploaded_file['file'];
                $wp_upload_dir = wp_upload_dir();
                $plugin_options['ncf_userpic'] = $wp_upload_dir['url'] . '/' . basename ($file_name_and_location);
            } else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
                $upload_feedback = 'There was a problem with your upload.';
            }

        } else { // wrong file type
            $upload_feedback = 'Please upload only image files (jpg, gif or png).';
        }

    } else if (isset($_FILES['ncf_custom_bg']) && ($_FILES['ncf_custom_bg']['size'] > 0)) {

	    // Get the type of the uploaded file. This is returned as "type/extension"
	    $arr_file_type = wp_check_filetype(basename($_FILES['ncf_custom_bg']['name']));
	    $uploaded_file_type = $arr_file_type['type'];

	    // Set an array containing a list of acceptable formats
	    $allowed_file_types = array('image/jpg', 'image/jpeg', 'image/gif', 'image/png');

	    // If the uploaded file is the right format
	    if (in_array($uploaded_file_type, $allowed_file_types)) {

		    // Options array for the wp_handle_upload function. 'test_upload' => false
		    $upload_overrides = array('test_form' => false);


				//delete previous
		    //if (isset($plugin_options['ncf_custom_bg'])) unlink($plugin_options['ncf_custom_bg']);

		    $uploaded_file = wp_handle_upload($_FILES['ncf_custom_bg'], $upload_overrides);

		    // If the wp_handle_upload call returned a local path for the image
		    if (isset($uploaded_file['file'])) {
			    // The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
			    $file_name_and_location = $uploaded_file['file'];
			    $wp_upload_dir = wp_upload_dir();
			    $plugin_options['ncf_custom_bg'] = $wp_upload_dir['url'] . '/' . basename($file_name_and_location);
		    } else { // wp_handle_upload returned some kind of error. the return does contain error details, so you can use it here if you want.
			    $upload_feedback = 'There was a problem with your upload.';
		    }

	    } else { // wrong file type
		    $upload_feedback = 'Please upload only image files (jpg, gif or png).';
	    }

    } else { // No file was passed
	    $upload_feedback = false;
    }
    return $plugin_options;
}



/*
Plugin Name: Imsanity
Plugin URI: http://verysimple.com/products/imsanity/
Description: Imsanity stops insanely huge image uploads
Author: Jason Hinkle
Version: 2.2.5
Author URI: http://verysimple.com/
*/

define('IMSANITY_VERSION','2.2.5');
define('IMSANITY_SCHEMA_VERSION','1.1');

define('IMSANITY_DEFAULT_MAX_WIDTH',110);
define('IMSANITY_DEFAULT_MAX_HEIGHT',110);
define('IMSANITY_DEFAULT_BMP_TO_JPG',1);
define('IMSANITY_DEFAULT_QUALITY',90);

define('IMSANITY_SOURCE_POST',1);
define('IMSANITY_SOURCE_LIBRARY',2);
define('IMSANITY_SOURCE_OTHER',4);


/**
 * Inspects the request and determines where the upload came from
 * @return IMSANITY_SOURCE_POST | IMSANITY_SOURCE_LIBRARY | IMSANITY_SOURCE_OTHER
 */
function imsanity_get_source()
{
	return array_key_exists('post_id', $_REQUEST)
		?  ($_REQUEST['post_id'] == 0 ? IMSANITY_SOURCE_LIBRARY : IMSANITY_SOURCE_POST)
		: IMSANITY_SOURCE_OTHER;
}

/**
 * Given the source, returns the max width/height
 *
 * @example:  list($w,$h) = imsanity_get_max_width_height(IMSANITY_SOURCE_LIBRARY);
 * @param int IMSANITY_SOURCE_POST | IMSANITY_SOURCE_LIBRARY | IMSANITY_SOURCE_OTHER
 */
function imsanity_get_max_width_height($source)
{
	$w = IMSANITY_DEFAULT_MAX_WIDTH;
	$h = IMSANITY_DEFAULT_MAX_HEIGHT;
	return array($w,$h);
}

/**
 * Handler after a file has been uploaded.  If the file is an image, check the size
 * to see if it is too big and, if so, resize and overwrite the original
 * @param Array $params
 */
function imsanity_handle_upload($params)
{
	if (isset($_FILES['ncf_pic']) && ($_FILES['ncf_pic']['size'] > 0)) {

		/* debug logging... */
		// file_put_contents ( "debug.txt" , print_r($params,1) . "\n" );

	/*	$option_convert_bmp = IMSANITY_DEFAULT_BMP_TO_JPG;

		if ($params['type'] == 'image/bmp' && $option_convert_bmp)
		{
			$params = imsanity_bmp_to_jpg($params);
		}*/

		// make sure this is a type of image that we want to convert and that it exists
		// @TODO when uploads occur via RPC the image may not exist at this location
		$oldPath = $params['file'];

		if ( (!is_wp_error($params)) && file_exists($oldPath) && in_array($params['type'], array('image/png','image/gif','image/jpeg')))
		{

			// figure out where the upload is coming from
			$source = imsanity_get_source();

			list($maxW,$maxH) = imsanity_get_max_width_height($source);

			list($oldW, $oldH) = getimagesize( $oldPath );

			/* HACK: if getimagesize returns an incorrect value (sometimes due to bad EXIF data..?)
			$img = imagecreatefromjpeg ($oldPath);
			$oldW = imagesx ($img);
			$oldH = imagesy ($img);
			imagedestroy ($img);
			//*/

			/* HACK: an animated gif may have different frame sizes.  to get the "screen" size
			$data = ''; // TODO: convert file to binary
			$header = unpack('@6/vwidth/vheight', $data );
			$oldW = $header['width'];
			$oldH = $header['width'];
			//*/

			if (($oldW > $maxW && $maxW > 0) || ($oldH > $maxH && $maxH > 0))
			{
				$quality = IMSANITY_DEFAULT_QUALITY;


				list($newW, $newH) = wp_constrain_dimensions($oldW, $oldH, $maxW, $maxH);

				// this is wordpress prior to 3.5 (image_resize deprecated as of 3.5)
				//$resizeResult = imsanity_image_resize( $oldPath, $newW, $newH, true, null, null, $quality);
				$resizeResult = imsanity_image_resize( $oldPath, $maxW, $maxH, true, null, null, $quality);

				/* uncomment to debug error handling code: */
				// $resizeResult = new WP_Error('invalid_image', __(print_r($_REQUEST)), $oldPath);

				// regardless of success/fail we're going to remove the original upload
				unlink($oldPath);

				if (!is_wp_error($resizeResult))
				{
					$newPath = $resizeResult;

					// remove original and replace with re-sized image
					rename($newPath, $oldPath);
				}
				else
				{
					// resize didn't work, likely because the image processing libraries are missing
					$params = wp_handle_upload_error( $oldPath ,
						sprintf( __("Oh Snap! Imsanity was unable to resize this image "
						. "for the following reason: '%s'
						.  If you continue to see this error message, you may need to either install missing server"
						. " components or disable the Imsanity plugin."
						. "  If you think you have discovered a bug, please report it on the Imsanity support forum.", 'imsanity' ) ,$resizeResult->get_error_message() ) );

				}
			}

		}
	}

	return $params;

}


/**
 * If the uploaded image is a bmp this function handles the details of converting
 * the bmp to a jpg, saves the new file and adjusts the params array as necessary
 *
 * @param array $params
 */
function imsanity_bmp_to_jpg($params)
{

	// read in the bmp file and then save as a new jpg file.
	// if successful, remove the original bmp and alter the return
	// parameters to return the new jpg instead of the bmp

	//include_once('libs/imagecreatefrombmp.php');

	$bmp = imagecreatefrombmp($params['file']);

	// we need to change the extension from .bmp to .jpg so we have to ensure it will be a unique filename
	$uploads = wp_upload_dir();
	$oldFileName = basename($params['file']);
	$newFileName = basename(str_ireplace(".bmp", ".jpg", $oldFileName));
	$newFileName = wp_unique_filename( $uploads['path'], $newFileName );

	$quality = IMSANITY_DEFAULT_QUALITY;

	if (imagejpeg($bmp,$uploads['path'] . '/' . $newFileName, $quality))
	{
		// conversion succeeded.  remove the original bmp & remap the params
		unlink($params['file']);

		$params['file'] = $uploads['path'] . '/' . $newFileName;
		$params['url'] = $uploads['url'] . '/' . $newFileName;
		$params['type'] = 'image/jpeg';
	}
	else
	{
		unlink($params['file']);

		return wp_handle_upload_error( $oldFileName,
			__("Oh Snap! Imsanity was Unable to process the BMP file.  "
			."If you continue to see this error you may need to disable the BMP-To-JPG "
			."feature in Imsanity settings.", 'imsanity' ) );
	}

	return $params;
}

/**
 * ################################################################################
 * UTILITIES
 * ################################################################################
 */

/**
 * Util function returns an array value, if not defined then returns default instead.
 * @param Array $array
 * @param string $key
 * @param variant $default
 */
function imsanity_val($arr,$key,$default='')
{
	return isset($arr[$key]) ? $arr[$key] : $default;
}

/**
 * output a fatal error and optionally die
 *
 * @param string $message
 * @param string $title
 * @param bool $die
 */
function imsanity_fatal($message, $title = "", $die = false)
{
	echo ("<div style='margin:5px 0px 5px 0px;padding:10px;border: solid 1px red; background-color: #ff6666; color: black;'>"
		. ($title ? "<h4 style='font-weight: bold; margin: 3px 0px 8px 0px;'>" . $title . "</h4>" : "")
		. $message
		. "</div>");

	if ($die) die();
}

/**
 * Replacement for deprecated image_resize function
 * @param string $file Image file path.
 * @param int $max_w Maximum width to resize to.
 * @param int $max_h Maximum height to resize to.
 * @param bool $crop Optional. Whether to crop image or resize.
 * @param string $suffix Optional. File suffix.
 * @param string $dest_path Optional. New image file path.
 * @param int $jpeg_quality Optional, default is 90. Image quality percentage.
 * @return mixed WP_Error on failure. String with new destination path.
 */
function imsanity_image_resize( $file, $max_w, $max_h, $crop, $suffix = null, $dest_path = null, $jpeg_quality = 90 ) {

	if (function_exists('wp_get_image_editor'))
	{
		// WP 3.5 and up use the image editor

		$editor = wp_get_image_editor( $file );
		if ( is_wp_error( $editor ) )
			return $editor;
		$editor->set_quality( $jpeg_quality );

		$resized = $editor->resize( $max_w, $max_h, $crop );
		if ( is_wp_error( $resized ) )
			return $resized;

		$dest_file = $editor->generate_filename( $suffix, $dest_path );

		// FIX: make sure that the destination file does not exist.  this fixes
		// an issue during bulk resize where one of the optimized media filenames may get
		// used as the temporary file, which causes it to be deleted.
		while (file_exists($dest_file)) {
			$dest_file = $editor->generate_filename('TMP', $dest_path );
		}

		$saved = $editor->save( $dest_file );

		if ( is_wp_error( $saved ) )
			return $saved;

		return $dest_file;
	}
	else
	{
		// wordpress prior to 3.5 uses the old image_resize function
		return image_resize( $file, $max_w, $max_h, $crop, $suffix, $dest_path, $jpeg_quality);
	}
}

/* add filters to hook into uploads */
add_filter( 'wp_handle_upload', 'imsanity_handle_upload' );
