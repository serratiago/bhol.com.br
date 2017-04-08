<?php

$readmore_align_options = array(
	'0' => array(
		'value' =>	'left',
		'label' =>  __('Left (default)',TONJOO_ECAE)
		),
	'1' => array(
		'value' =>	'center',
		'label' =>  __('Center',TONJOO_ECAE) 
		),
	'2' => array(
		'value' =>	'right',
		'label' =>  __('Right',TONJOO_ECAE) 
		)
	);		

$yes_no_options = array(
	'0' => array(
		'value' =>	'no',
		'label' =>  __('No',TONJOO_ECAE)
		),
	'1' => array(
		'value' =>	'yes',
		'label' =>  __('Yes',TONJOO_ECAE) 
		)
	);

$button_display_option = array(
	'0' => array(
		'value' =>	'normal',
		'label' =>  __('Normal',TONJOO_ECAE)
		),
	'1' => array(
		'value' =>	'always_show',
		'label' =>  __('Always Show',TONJOO_ECAE) 
		),
	'2' => array(
		'value' =>	'always_hide',
		'label' =>  __('Always Hide',TONJOO_ECAE) 
		)
	);


$text_options = array(
	'label'=>__('Read more text',TONJOO_ECAE),
	'name'=>'tonjoo_ecae_options[read_more]',
	'value'=>$options['read_more'],
	'description'=>__('If you do not want to display it, fill with "-" (without quote)',TONJOO_ECAE)
	);

$readmore_text_before_options = array(
	'label'=>__('Text before link',TONJOO_ECAE),
	'name'=>'tonjoo_ecae_options[read_more_text_before]',
	'value'=>$options['read_more_text_before'],
	'description'=>__('Text before read more link',TONJOO_ECAE)
	);

$readmore_inline = array(
	"name"=>"tonjoo_ecae_options[readmore_inline]",
	"description" => __("If you select 'yes', the readmore button will inline with the last paragraph",TONJOO_ECAE),
	"label" => __("Inline Button",TONJOO_ECAE),
	"value" => $options['readmore_inline'],
	"select_array" => $yes_no_options,
	);

$readmore_align_select = array(
	"name"=>"tonjoo_ecae_options[read_more_align]",
	"description" => '',
	"label" => __("Read more align",TONJOO_ECAE),
	"value" => $options['read_more_align'],
	"select_array" => $readmore_align_options
	);

$readmore_display = array(
	"name"=>"tonjoo_ecae_options[button_display_option]",
	"description" => __("Normal mode = show readmore button, only if content length is bigger than excerpt size",TONJOO_ECAE),
	"label" => __("Display option",TONJOO_ECAE),
	"value" => $options['button_display_option'],
	"select_array" => $button_display_option,
	);

tj_print_select_option($readmore_display);
tj_print_text_option($text_options);
tj_print_text_option($readmore_text_before_options);
tj_print_select_option($readmore_inline);
tj_print_select_option($readmore_align_select);

// premium anouncement
if(! function_exists('is_ecae_premium_exist'))
{			
	echo "<tr><td colspan=3><h3 class='meta-subtitle'>";
	printf(__('Purchase the %1$s Premium Edition %2$s to enable all button font and the premium button skins',TONJOO_ECAE),"<a href='http://wpexcerptplugin.com/' target='_blank'>","</a>");
	echo "</h3></td></tr>";
	echo "<tr><th colspan=3><i>".__('The black and white button skins are free :)',TONJOO_ECAE)."</i></th></tr>";
}

$button_font_array = array(
	'0' => array(
		'value' =>	'',
		'label' =>  'Use Content Font'
		),
	'1' => array(
		'value' =>	'Open Sans',
		'label' =>  'Open Sans'
		),
	'2' => array(
		'value' =>	'Lobster',
		'label' =>  'Lobster'
		),
	'3' => array(
		'value' =>	'Lobster Two',
		'label' =>  'Lobster Two'
		),
	'4' => array(
		'value' =>	'Ubuntu',
		'label' =>  'Ubuntu'
		),
	'5' => array(
		'value' =>	'Ubuntu Mono',
		'label' =>  'Ubuntu Mono'
		),
	'6' => array(
		'value' =>	'Titillium Web',
		'label' =>  'Titillium Web'
		),
	'7' => array(
		'value' =>	'Grand Hotel',
		'label' =>  'Grand Hotel'
		),
	'8' => array(
		'value' =>	'Pacifico',
		'label' =>  'Pacifico'
		),
	'9' => array(
		'value' =>	'Crafty Girls',
		'label' =>  'Crafty Girls'
		),
	'10' => array(
		'value' =>	'Bevan',
		'label' =>  'Bevan'
		)
);

$button_font = array(
	"name"=>"tonjoo_ecae_options[button_font]",
	"description" => "",
	"label" => __("Button Font",TONJOO_ECAE),
	"value" => $options['button_font'],
	"select_array" => $button_font_array
	);

echo tj_print_select_option($button_font);

if(! function_exists('is_ecae_premium_exist')) {
	$options['button_font_size'] = '14';
}

?>

<tr valign="top">
	<th><?php _e('Button Font Size',TONJOO_ECAE) ?></th>
	<td><input type="number" name="tonjoo_ecae_options[button_font_size]" value="<?php echo $options['button_font_size'] ?>"></td>
	<td>&nbsp;</td>
</tr>

<?php	

$dir =  dirname(__FILE__)."/buttons";
$skins = scandir($dir);
$button_skin =  array();
$button_skin_val = $options['button_skin'];

array_push($button_skin, array("label"=>"None","value"=>"ecae-buttonskin-none"));
array_push($button_skin, array("label"=>"Black","value"=>"ecae-buttonskin-black"));
array_push($button_skin, array("label"=>"White","value"=>"ecae-buttonskin-white"));

if(function_exists('is_ecae_premium_exist')) 
{                
    $dir =  ABSPATH . 'wp-content/plugins/'.ECAE_PREMIUM_DIR_NAME.'/buttons';

    $skins = scandir($dir);

    foreach ($skins as $key => $value) {

        $extension = pathinfo($value, PATHINFO_EXTENSION); 
        $filename = pathinfo($value, PATHINFO_FILENAME); 
        $extension = strtolower($extension);
        $the_value = strtolower($filename);
        $filename_ucwords = str_replace('-', ' ', $filename);
        $filename_ucwords = ucwords($filename_ucwords);
        $filename_ucwords = str_replace('Ecae Buttonskin ', '', ucwords($filename_ucwords));

        if($extension=='css'){
            $data = array(
                        "label"=>"$filename_ucwords (Premium)",
                        "value"=>"$the_value-PREMIUMtrue"
                    );

            array_push($button_skin,$data);
        }
    }
}
else
{
    $skins = scandir(ABSPATH . 'wp-content/plugins/'.ECAE_DIR_NAME.'/assets/premium-promo');

    foreach ($skins as $key => $value) {

        $extension = pathinfo($value, PATHINFO_EXTENSION); 
        $filename = pathinfo($value, PATHINFO_FILENAME); 
        $extension = strtolower($extension);
        $the_value = strtolower($filename);
        $filename_ucwords = str_replace('-', ' ', $filename);
        $filename_ucwords = ucwords($filename_ucwords);
        $filename_ucwords = str_replace('Ecae Buttonskin ', '', ucwords($filename_ucwords));

        if($extension=='png'){
            $data = array(
                        "label"=>"$filename_ucwords (Premium)",
                        "value"=>"$the_value-PREMIUMtrue"
                    );

            array_push($button_skin,$data);
        }
    }

    if(substr($button_skin_val, -12) == "-PREMIUMtrue")
    {
    	$button_skin_val = "ecae-buttonskin-none";
    }
}

$option_select = array(
                "name"=>"tonjoo_ecae_options[button_skin]",
                "description" => "",
                "label" => __("Button Skin",TONJOO_ECAE),
                "value" => $button_skin_val,
                "select_array" => $button_skin,
                "id"=>"tonjoo-ecae-button_skin"
            );

tj_print_select_option($option_select);
?>

<tr><td colspan=3><h3 class="meta-subtitle"><?php _e('Button Shortcode',TONJOO_ECAE) ?></h3></td></tr>
<tr valign="top">
	<th colspan=3>
		<?php _e('You can manually add the button by put this shortcode to your post',TONJOO_ECAE) ?>: <code>[ecae_button]</code>
		<br /><br />
		<i><?php _e('Required "strip shortcode options" = No',TONJOO_ECAE) ?></i>
	</th>
</tr>

<tr><td colspan=3><h3 class="meta-subtitle"><?php _e('Read More Live Preview',TONJOO_ECAE) ?></h3></td></tr>
<tr>
	<td colspan=3>
		<div id="ecae_ajax_preview_button"></div>
	</td>
</tr>

<tr><td colspan=3><h3 class="meta-subtitle"><?php _e('Custom CSS',TONJOO_ECAE) ?></h3></td></tr>
<tr valign="top">
	<th colspan=3>
		<p style="margin-top:-25px;font-size:14px;">
			<?php _e('Some css attribute need to use <code>!important</code> value to affect',TONJOO_ECAE) ?>
		</p>
		<div id="ace-editor"><?php echo $options["custom_css"]; ?></div>
		<textarea id="ace_editor_value" name="tonjoo_ecae_options[custom_css]" ><?php echo $options["custom_css"]; ?></textarea>
	</th>
</tr>