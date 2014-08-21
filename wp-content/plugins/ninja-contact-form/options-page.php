<div id="ns-options-wrap" class="widefat" style="display: none;">
<?php
    function custom_do_settings_sections($page) {
        global $wp_settings_sections, $wp_settings_fields;

        if ( !isset($wp_settings_sections) || !isset($wp_settings_sections[$page]) )
            return;

        foreach( (array) $wp_settings_sections[$page] as $section ) {
            echo "<div class='postbox'><h3 class='hndle'><span>{$section['title']}</span></h3>\n";
            call_user_func($section['callback'], $section);
            if ( !isset($wp_settings_fields) ||
                 !isset($wp_settings_fields[$page]) ||
                 !isset($wp_settings_fields[$page][$section['id']]) )
                    continue;
            echo '<div class="settings-form-wrapper '. $section['id'] . '">';
            custom_do_settings_fields($page, $section['id']);
            echo "<input name='Submit' type='submit' class='button-primary' value='Save Changes' />";
            echo '</div></div>';
        }
    }

    function custom_do_settings_fields($page, $section) {
        global $wp_settings_fields;

        if ( !isset($wp_settings_fields) ||
             !isset($wp_settings_fields[$page]) ||
             !isset($wp_settings_fields[$page][$section]) )
            return;

        foreach ( (array) $wp_settings_fields[$page][$section] as $field ) {
            echo '<div class="settings-form-row'. (!empty($field['args']['hidden']) ? ' hidden-row' : '') . ' ' . $field['id'] .'">';
            if ( !empty($field['args']['label_for']) ) {
                echo '<p><label for="' . $field['args']['label_for'] . '">' . $field['title'] . '</label><br />';
            }
            else {
                echo '<p><span class="field-title">' . $field['title'] . '</span>';
            }
            call_user_func($field['callback'], $field['args']);
            echo '</p></div>';
        }
    }
    screen_icon();
?>
<h2 class="form-title">Ninja Contact Form Settings</h2>
<form method="post" action="options.php" enctype="multipart/form-data">
<?php settings_fields('ncf_options'); ?>
<?php custom_do_settings_sections('ncf'); ?>

</form>
</div>
<script type="text/javascript">
    (function(){
        var $ = window.jQuery;
        if($ != null) {
            $(function(){
                $('#ns-options-wrap').fadeIn()
            });
        } else {
            document.getElementById('ns-options-wrap').style.display = 'block';
        }
    }())
</script>
