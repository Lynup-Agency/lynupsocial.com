<?php
    $colorSchema = $options['ncf_color_schema'];
    if (empty($colorSchema)) {
        $colorSchema = array();
    } else {
        $colorSchema = explode(',', $colorSchema);
    }
	  $bg = $options['ncf_image_bg'];
		$theme = $options['ncf_theme'];
		$opacityLevel = $options['ncf_fade_content'] === 'light' ? 0.3 : ($options['ncf_fade_content'] === 'dark' ? 0.7 : 0);

?>
<style>

<?php if(isset($bg) && strpos($bg, 'none') === FALSE): ?>
		#ncf_sidebar.ncf_imagebg_<?php echo $bg; ?> {
		    background-image: url(<?php echo plugins_url('/img/bg/' . $bg . '.jpg', __FILE__) ; ?>);
		}
	<?php if(strpos($bg, 'blur') !== FALSE || ($theme === 'aerial' && strpos($bg, 'none') !== FALSE)): ?>
		#ncf_sidebar {
			background-repeat: no-repeat;
			-webkit-background-size: cover;
      -moz-background-size: cover;
      background-size: cover;
      background-position: 0 0;
		}
	<?php endif; ?>
<?php endif; ?>

<?php if(isset($colorSchema[0])): ?>
.ncf_color1 {
	background-color: <?php echo $colorSchema[0]; ?> !important ;
}
.ncf_minimalistic .ncf_user_firstname , .ncf_minimalistic .ncf_user_lastname,
.ncf_aerial .ncf_user_firstname,
.ncf_aerial .ncf_user_lastname,
#ncf_sidebar.ncf_aerial .ncf_user_bio,
#ncf_sidebar.ncf_aerial input[type=text],
#ncf_sidebar.ncf_aerial input[type=email],
#ncf_sidebar.ncf_aerial input[type=tel],
#ncf_sidebar.ncf_aerial textarea,
#ncf_sidebar #ncf_question {
		color:  <?php echo $colorSchema[0]; ?> !important;
		}

#ncf_sidebar.ncf_aerial input::-webkit-input-placeholder,
#ncf_sidebar.ncf_aerial textarea::-webkit-input-placeholder{
    color: <?php echo $colorSchema[0]; ?> !important;
}

#ncf_sidebar.ncf_aerial input:-moz-placeholder,
#ncf_sidebar.ncf_aerial textarea:-moz-placeholder{
    color: <?php echo $colorSchema[0]; ?> !important;
}

#ncf_sidebar.ncf_aerial input:-ms-input-placeholder,
#ncf_sidebar.ncf_aerial textarea:-ms-input-placeholder{
    color: <?php echo $colorSchema[0]; ?> !important;
}

.ncf_minimalistic .ncf_form_res_message {
		color:  <?php echo $colorSchema[0]; ?> !important;
}

.ncf_minimalistic .ncf_sidebar_socialbar li a:hover {
		background-color: <?php echo $colorSchema[0]; ?> !important ;
}
.ncf_minimalistic input:focus,
.ncf_minimalistic textarea:focus
{
    color: <?php echo $colorSchema[0]; ?> !important;
}

#ncf_sidebar .ncf_err_msg, #ncf_sidebar .ncf_form_btn_wrapper .ncf_btn_close {
		color: <?php echo $colorSchema[0]; ?> !important;
}

#ncf_sidebar.ncf_aerial input::-webkit-input-placeholder,
#ncf_sidebar.ncf_aerial textarea::-webkit-input-placeholder{
    color: <?php echo $colorSchema[0]; ?> !important;
}

#ncf_sidebar.ncf_aerial input:-moz-placeholder,
#ncf_sidebar.ncf_aerial textarea:-moz-placeholder{
    color: <?php echo $colorSchema[0]; ?> !important;
}

#ncf_sidebar.ncf_aerial input:-ms-input-placeholder,
#ncf_sidebar.ncf_aerial textarea:-ms-input-placeholder{
    color: <?php echo $colorSchema[0]; ?> !important;
}
<?php endif; ?>

<?php if(isset($colorSchema[1])): ?>
.ncf_color2 {
	background-color: <?php echo $colorSchema[1]; ?> !important ;
}
.ncf_minimalistic .ncf_user_bio{
		color:  <?php echo $colorSchema[1]; ?> !important;
}

#ncf_sidebar.ncf_aerial .ncf_user_title{
    color: <?php echo $colorSchema[1]; ?>;
}

<?php endif; ?>

<?php if(isset($colorSchema[2])): ?>
.ncf_color3 {
    background-color: <?php echo $colorSchema[2]; ?> !important;
}
#ncf_sidebar.ncf_flat a.ncf_button {
    -webkit-box-shadow: 0 2px 0px 2px <?php echo $colorSchema[2]; ?>;
    -moz-box-shadow: 0 2px 0px 2px <?php echo $colorSchema[2]; ?>;
    box-shadow: 0 2px 0px 2px <?php echo $colorSchema[2]; ?>;
}

#ncf_sidebar.ncf_flat  a.ncf_button:active {
    -webkit-box-shadow: 0 1px 0px 2px <?php echo $colorSchema[2]; ?>;
    -moz-box-shadow: 0 1px 0px 2px <?php echo $colorSchema[2]; ?>;
    box-shadow: 0 1px 0px 2px <?php echo $colorSchema[2]; ?>;
}

<?php endif; ?>

<?php if(isset($colorSchema[3])): ?>
.ncf_color4 {
	background-color: <?php echo $colorSchema[3]; ?> !important ;
}

#ncf_sidebar.ncf_minimalistic input[type=text]:focus,
#ncf_sidebar.ncf_minimalistic input[type=email]:focus,
#ncf_sidebar.ncf_minimalistic input[type=tel]:focus,
#ncf_sidebar.ncf_minimalistic input[type=tel]:focus,
#ncf_sidebar.ncf_minimalistic input[type=tel]:focus,
#ncf_sidebar.ncf_minimalistic textarea:focus {
    border-color: <?php echo $colorSchema[3]; ?>;
    outline: 1px solid <?php echo $colorSchema[2]; ?>;
    outline-offset: 0;
}

<?php endif; ?>

<?php if(isset($colorSchema[4])): ?>
.ncf_color5 {
	background-color: <?php echo $colorSchema[4]; ?> !important ;
}


#ncf_sidebar.ncf_minimalistic a.ncf_button {
    -webkit-box-shadow: 0 2px 0px 2px <?php echo $colorSchema[4]; ?>;
    -moz-box-shadow: 0 2px 0px 2px <?php echo $colorSchema[4]; ?>;
    box-shadow: 0 2px 0px 2px <?php echo $colorSchema[4]; ?>;
}

#ncf_sidebar.ncf_minimalistic  a.ncf_button:active {
    -webkit-box-shadow: 0 1px 0px 2px <?php echo $colorSchema[4]; ?>;
    -moz-box-shadow: 0 1px 0px 2px <?php echo $colorSchema[4]; ?>;
    box-shadow: 0 1px 0px 2px <?php echo $colorSchema[4]; ?>;
}

<?php endif; ?>


<?php if(isset($colorSchema[5])): ?>
.ncf_color6 {
	background-color: <?php echo $colorSchema[5]; ?> !important ;
}


<?php endif; ?>


<?php if(isset($colorSchema[6])): ?>
.ncf_color7 {
	background-color: <?php echo $colorSchema[6]; ?> !important ;
}
<?php endif; ?>


<?php if(isset($colorSchema[7])): ?>
.ncf_color8 {
	background-color: <?php echo $colorSchema[7]; ?> !important ;
}
#ncf_sidebar.ncf_flat .ncf_form input[type=text]:focus,
#ncf_sidebar.ncf_flat .ncf_form input[type=email]:focus,
#ncf_sidebar.ncf_flat .ncf_form input[type=tel]:focus,
#ncf_sidebar.ncf_flat .ncf_form textarea:focus {
    border: 1px solid <?php echo $colorSchema[7]; ?>;
}
<?php endif; ?>

<?php if(isset($colorSchema[8])): ?>
.ncf_color9 {
	background-color: <?php echo $colorSchema[8]; ?> !important ;
}
.ncf_flat .ncf_user_title {
    color: <?php echo $colorSchema[8]; ?> !important;
}
<?php endif; ?>

<?php if(isset($options['ncf_custom_bg'])): ?>
.ncf_imagebg_custom {
		background-image: url(<?php echo $options['ncf_custom_bg']; ?>) !important;
}
<?php endif; ?>
<?php if(isset($options['ncf_show_social']) && $options['ncf_show_social'] === 'hide'): ?>
.ncf_flat .ncf_sidebar_socialbar ul li a,
.ncf_minimalistic .ncf_sidebar_socialbar,
.ncf_minimalistic .ncf_sidebar_cont > .ncf_line_sep,
.ncf_aerial .ncf_sidebar_socialbar {
    display: none !important;
}
<?php endif; ?>

<?php if($theme === 'aerial' && !empty($options['ncf_rgba'])): ?>
#ncf_sidebar.ncf_aerial input[type=text],
#ncf_sidebar.ncf_aerial input[type=email],
#ncf_sidebar.ncf_aerial input[type=tel],
#ncf_sidebar.ncf_aerial textarea,
#ncf_sidebar.ncf_aerial #ncf_answer_field {
    background-color: rgba(<?php echo $options['ncf_rgba']; ?>, 0.1) !important;
}
<?php endif; ?>
<?php if(isset($options['ncf_custom_css'])): ?>
   <?php echo $options['ncf_custom_css']; ?>
<?php endif; ?>
.ncf_exposed #ns-overlay {
    opacity: <?php echo $opacityLevel; ?>;
}
</style>

<div id="ncf_sidebar" class="ncf_<?php echo $theme . ' ncf_imagebg_' . $bg . ' ncf_up_style_'  . $options['ncf_userpic_style'] . (!empty($options['ncf_invert_style']) ? ' ncf_' . $options['ncf_invert_style'] : '' ) ;?>">
    <div class="ncf_sidebar_cont_scrollable">
        <div class="ncf_sidebar_cont shrinked">
				<?php if($options['ncf_theme'] === 'flat'): ?>
            <div class="ncf_sidebar_header ncf_color1">
                <div class="ncf_sidebar_socialbar">
                    <ul>
                        <li class="ncf_color1"></li>
                        <li class="ncf_color2"></li>
                        <li class="ncf_color3"></li>
                        <li class="ncf_color4"></li>
                        <li class="ncf_color5"></li>
                        <li class="ncf_color6"></li>
                        <li class="ncf_color7"></li>
                        <li class="ncf_color8"></li>
                    </ul>
                </div>
                <?php if(!empty($options['ncf_user_firstname']) ||
                !empty($options['ncf_user_lastname']) ||
                !empty($options['ncf_userpic']) ||
                !empty($options['ncf_user_title'])): ?>
                <div class="ncf_sidebar_header_userinfo ncf_color1">
                    <div class="ncf_userpic">
                        <?php if(!empty($options['ncf_userpic'])): ?>
                        <img src="<?php echo $options['ncf_userpic']; ?>" alt="">
                        <?php endif; ?>

                    </div>
                    <div class="ncf_user_credentials">
                        <span class="ncf_user_firstname"><?php echo $options['ncf_user_firstname']; ?></span>
                        <span class="ncf_user_lastname"><?php echo $options['ncf_user_lastname']; ?></span>
                        <span class="ncf_user_title"><?php echo $options['ncf_user_title']; ?></span>
                    </div>
                </div>
                <?php endif; ?>

            </div>
	        <?php endif; ?>
	        <?php if($theme === 'minimalistic'): ?>
		        <div class="ncf_sidebar_header">

                <?php if(!empty($options['ncf_user_firstname']) ||
                !empty($options['ncf_user_lastname']) ||
                !empty($options['ncf_userpic']) ||
                !empty($options['ncf_user_title'])): ?>
                <div class="ncf_sidebar_header_userinfo">
                    <div class="ncf_userpic">
                        <?php if(!empty($options['ncf_userpic'])): ?>
                        <img src="<?php echo $options['ncf_userpic']; ?>" alt="">
                        <?php endif; ?>

                    </div>
                    <div class="ncf_user_credentials">
                        <span class="ncf_user_firstname"><?php echo $options['ncf_user_firstname']; ?></span>
                        <span class="ncf_user_lastname"><?php echo $options['ncf_user_lastname']; ?></span>
                        <span class="ncf_user_title"><?php echo $options['ncf_user_title']; ?></span>
                    </div>
                </div>
                <?php endif; ?>

                  <div class="ncf_line_sep"></div>
                  <div class="ncf_sidebar_socialbar">
                       <ul>
                           <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
                       </ul>
                   </div>

            </div>
				        <div class="ncf_line_sep"></div>
	        <?php endif; ?>

	        <?php if($theme === 'aerial'): ?>
		        <div class="ncf_sidebar_header">
				        <div class="ncf_sidebar_socialbar">
	                 <ul>
	                     <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
	                 </ul>
	             </div>
                <?php if(!empty($options['ncf_user_firstname']) ||
                !empty($options['ncf_user_lastname']) ||
                !empty($options['ncf_userpic']) ||
                !empty($options['ncf_user_title'])): ?>
                <div class="ncf_sidebar_header_userinfo">
                    <div class="ncf_userpic">
                        <?php if(!empty($options['ncf_userpic'])): ?>
                        <img src="<?php echo $options['ncf_userpic']; ?>" alt="">
                        <?php endif; ?>

                    </div>
                    <div class="ncf_user_credentials">
                        <span class="ncf_user_firstname"><?php echo $options['ncf_user_firstname']; ?></span>
                        <span class="ncf_user_lastname"><?php echo $options['ncf_user_lastname']; ?></span>
                        <span class="ncf_user_title"><?php echo $options['ncf_user_title']; ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
	        <?php endif; ?>

            <div class="ncf_sidebar_content">
                <div class="ncf_user_bio"><?php echo $options['ncf_user_bio']; ?>
                </div>
                <form class="ncf_form" action="">
                    <input type="hidden" name="action" value="ncf_send_message" />

                    <div class="ncf_form_input_wrapper <?php echo ($theme === 'flat' ? 'ncf_color8' : ''); ?> ncf_name_field">
                        <input type="text" name="ncf_name_field" id="ncf_name_field" placeholder="<?php _e( "Your name", 'ninja-contact-form' ); ?> *" data-rules="required|min[2]|max[32]" data-name="<?php _e( "Your name", 'ninja-contact-form' ); ?>">
                    </div>
                    <div class="ncf_form_input_wrapper <?php echo ($theme === 'flat' ? 'ncf_color8' : ''); ?> ncf_email_field" >
                        <input type="text" name="ncf_email_field" id="ncf_email_field" placeholder="<?php _e( "Your e-mail", 'ninja-contact-form' ); ?> *" data-rules="required|email" data-name="<?php _e( "Your e-mail", 'ninja-contact-form' ); ?>">
                    </div>
                    <div class="ncf_form_input_wrapper <?php echo ($theme === 'flat' ? 'ncf_color8' : ''); ?> ncf_message_field">
                        <textarea name="ncf_message_field" id="ncf_message_field" cols="30" rows="10" placeholder="<?php _e( "Your message", 'ninja-contact-form' ); ?> *"  data-rules="required" data-name="<?php _e( "Your message", 'ninja-contact-form' ); ?>"></textarea>
                    </div>
                    <div class="ncf_form_btn_wrapper">
											<?php if(!empty($options['ncf_enable_test'])): ?>
		                    <div class="ncf_question_wrapper"><label id="ncf_question" for="ncf_answer_field">3 + 4 = </label><input type="text" name="ncf_answer_field" id="ncf_answer_field"  maxlength="2" data-rules="required|numeric" data-name="Answer">
												</div>
	                    <?php endif; ?>
                        <a class="ncf_button <?php echo $theme === 'flat' ? 'ncf_color8' : 'ncf_color1'; ?>" href="#"><span><?php _e( "Send", 'ninja-contact-form' ); ?></span></a>
		                    <input type="submit" value="Send"/>
                    </div>
                </form>
<!--								--><?php //echo do_shortcode('[contact-form-7 id="12" title="Contact form 1_copy"]'); ?>
		            <div class="ncf_form_result"></div>
            </div>

        </div>
    </div>
</div>