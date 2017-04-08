<?php
if(! function_exists('is_ecae_premium_exist') && strpos($options['excerpt_method'],'-paragraph')) {
	$options['excerpt_method'] = 'paragraph';
}

$excerpt_method_ar = array(
	'0' => array(
		'value' =>	'paragraph',
		'label' =>  __('Paragraph',TONJOO_ECAE)
	),
	'1' => array(
		'value' =>	'word',
		'label' =>  __('Character',TONJOO_ECAE) 
	),
	'2' => array(
		'value' =>	'1st-paragraph',
		'label' =>  __('Show First Paragraph',TONJOO_ECAE) 
	),
	'3' => array(
		'value' =>	'2nd-paragraph',
		'label' =>  __('Show 1st - 2nd Paragraph',TONJOO_ECAE) 
	),
	'4' => array(
		'value' =>	'3rd-paragraph',
		'label' =>  __('Show 1st - 3rd Paragraph',TONJOO_ECAE)
	)
);

$excerpt_method = array(
	"name"=>"tonjoo_ecae_options[excerpt_method]",
	"description" => __("Paragraph method will cut per paragraph. Character method will cut per word",TONJOO_ECAE),
	"label" => __("Excerpt method",TONJOO_ECAE),
	"value" => $options['excerpt_method'],
	"select_array" => $excerpt_method_ar,
);


echo tj_print_select_option($excerpt_method);

?>

<tr valign="top">
	<th><?php _e("Excerpt size",TONJOO_ECAE) ?></th>
	<td><input type="number" name="tonjoo_ecae_options[width]" value="<?php echo $options['width'] ?>"></td>
	<td><?php _e("Number of character preserved",TONJOO_ECAE) ?></td>
</tr>

<?php

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

$yes_no_select = array(
	"name"=>"tonjoo_ecae_options[strip_shortcode]",
	"description" => __("If you select 'yes' any shortcode will be eliminated from the excerpt",TONJOO_ECAE),
	"label" => __("Strip shortcode",TONJOO_ECAE),
	"value" => $options['strip_shortcode'],
	"select_array" => $yes_no_options,
);

echo tj_print_select_option($yes_no_select);


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

$yes_no_select = array(
	"name"=>"tonjoo_ecae_options[strip_empty_tags]",
	"description" => __("If you select 'yes' any empty HTML tags will be eliminated from the excerpt",TONJOO_ECAE),
	"label" => __("Strip empty HTML tags",TONJOO_ECAE),
	"value" => $options['strip_empty_tags'],
	"select_array" => $yes_no_options,
);

echo tj_print_select_option($yes_no_select);


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

$yes_no_select = array(
	"name"=>"tonjoo_ecae_options[disable_on_feed]",
	"description" => __("Disable any excerpt on RSS feed",TONJOO_ECAE),
	"label" => __("Disable on RSS Feed",TONJOO_ECAE),
	"value" => $options['disable_on_feed'],
	"select_array" => $yes_no_options,
);

echo tj_print_select_option($yes_no_select);


$yes_no_select = array(
	"name"=>"tonjoo_ecae_options[special_method]",
	"description" => __("Use this method only if there are any problem with the excerpt",TONJOO_ECAE),
	"label" => __("Special method",TONJOO_ECAE),
	"value" => $options['special_method'],
	"select_array" => $yes_no_options,
);

echo tj_print_select_option($yes_no_select);


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


$excerpt_yes_options = array(
	'0' => array(
		'value' =>	'no',
		'label' =>  __('No',TONJOO_ECAE)
	),
	'1' => array(
		'value' =>	'yes',
		'label' =>  __('Yes',TONJOO_ECAE) 
	)
);

echo '<tr><td colspan=3><h3 class="meta-subtitle">'.__('Content Options',TONJOO_ECAE).'</h3></td></tr>';

$justify_options = array(
	'0' => array(
		'value' =>	'no',
		'label' =>  __('No',TONJOO_ECAE)
	),
	'1' => array(
		'value' =>	'left',
		'label' =>  __('Left',TONJOO_ECAE) 
	),
	'2' => array(
		'value' =>	'right',
		'label' =>  __('Right',TONJOO_ECAE) 
	),
	'3' => array(
		'value' =>	'justify',
		'label' =>  __('Justify',TONJOO_ECAE)
	),
	'4' => array(
		'value' =>	'center',
		'label' =>  __('Center',TONJOO_ECAE)
	)
);

$justify_select = array(
	"name"=>"tonjoo_ecae_options[justify]",
	"description" => __("The plugin will try to align the text on the excerpt page",TONJOO_ECAE),
	"label" => __("Text align",TONJOO_ECAE),
	"value" => $options['justify'],
	"select_array" => $justify_options,
);

$extra_html_markup = array(
	'label'=>__('Extra HTML markup',TONJOO_ECAE),
	'name'=>'tonjoo_ecae_options[extra_html_markup]',
	'value'=>$options['extra_html_markup'],
	'description'=>__('Extra HTML markup to save. Use "|" (without quote) between markup',TONJOO_ECAE),
);

tj_print_select_option($justify_select);
tj_print_text_option($extra_html_markup);


echo '<tr><td colspan=3><h3 class="meta-subtitle">'.__('Display Image Options',TONJOO_ECAE).'</h3></td></tr>';

$yes_no_options = array(
	'0' => array(
		'value' =>	'no',
		'label' =>  __("No",TONJOO_ECAE)
	),
	'1' => array(
		'value' =>	'yes',
		'label' =>  __("Show All Images",TONJOO_ECAE)
	),
	'2' => array(
		'value' =>	'first-image',
		'label' =>  __('Show only First Image',TONJOO_ECAE) 
	),
	'3' => array(
		'value' =>	'featured-image',
		'label' =>  __('Use Featured Image',TONJOO_ECAE)
	)
);

$image_select = array(
	"name"=>"tonjoo_ecae_options[show_image]",
	"description" => "",
	"label" => __("Content image",TONJOO_ECAE),
	"value" => $options['show_image'],
	"select_array" => $yes_no_options,
	"description" => __("Display Image in excerpt",TONJOO_ECAE) 
);

echo tj_print_select_option($image_select);

// premium anouncement
if(! function_exists('is_ecae_premium_exist'))
{
	echo "<tr><td colspan=3><h3 class='meta-subtitle'>";
	printf(__('Purchase the %1$s Premium Edition %2$s to enable all display image options below',TONJOO_ECAE),"<a href='http://wpexcerptplugin.com/' target='_blank'>","</a>");
	echo "</h3></td></tr>";
}

echo "<tr><th colspan=3><i>".__('Image options below is only work for Content Image: Show Only First Image and Use Featured Image',TONJOO_ECAE)."</i></th></tr>";


$yes_no_options = array(
	'0' => array(
		'value' =>	'none',
		'label' =>  __("None",TONJOO_ECAE)
	),
	'1' => array(
		'value' =>	'left',
		'label' =>  __("Left",TONJOO_ECAE)
	),
	'2' => array(
		'value' =>	'right',
		'label' =>  __("Right",TONJOO_ECAE)
	),
	'3' => array(
		'value' =>	'center',
		'label' =>  __('Center',TONJOO_ECAE) 
	),
	'4' => array(
		'value' =>	'float-left',
		'label' =>  __('Float Left',TONJOO_ECAE)
	),
	'5' => array(
		'value' =>	'float-right',
		'label' =>  __('Float Right',TONJOO_ECAE)
	)
);

$image_select = array(
	"name"=>"tonjoo_ecae_options[image_position]",
	"description" => "",
	"label" => __("Image position",TONJOO_ECAE),
	"value" => $options['image_position'],
	"select_array" => $yes_no_options,
	"description" => __("Image position option",TONJOO_ECAE) 
);

echo tj_print_select_option($image_select);

?>

<tr valign="top">
	<th><?php _e("Image width",TONJOO_ECAE) ?></th>
	<td>
		<input type="radio" name="tonjoo_ecae_options[image_width_type]" value="auto" <?php if($options['image_width_type'] == 'auto') echo 'checked' ?> >
		Auto
	</td>
	<td>&nbsp;</td>
</tr>

<tr valign="top">
	<th>&nbsp;</th>
	<td>
		<input type="radio" name="tonjoo_ecae_options[image_width_type]" value="manual" <?php if($options['image_width_type'] == 'manual') echo 'checked' ?> >
		<input type="number" name="tonjoo_ecae_options[image_width]" value="<?php echo $options['image_width'] ?>" style="width: 175px;margin-top: -5px;">
	</td>
	<td>&nbsp;</td>
</tr>

<tr valign="top">
	<th><?php _e("Image margin",TONJOO_ECAE) ?></th>
	<td>
		<p style="padding-top:0px;float:left;"><?php _e("Top",TONJOO_ECAE) ?></p>
		<input type="number" name="tonjoo_ecae_options[image_padding_top]" value="<?php echo $options['image_padding_top'] ?>" style="float: right;width: 100px;" >
	</td>
	<td>px</td>
</tr>

<tr valign="top">
	<th>&nbsp;</th>
	<td>
		<p style="padding-top:0px;float:left;"><?php _e("Right",TONJOO_ECAE) ?></p>
		<input type="number" name="tonjoo_ecae_options[image_padding_right]" value="<?php echo $options['image_padding_right'] ?>" style="float: right;width: 100px;" >
	</td>
	<td>px</td>
</tr>

<tr valign="top">
	<th>&nbsp;</th>
	<td>
		<p style="padding-top:0px;float:left;"><?php _e("Bottom",TONJOO_ECAE) ?></p>
		<input type="number" name="tonjoo_ecae_options[image_padding_bottom]" value="<?php echo $options['image_padding_bottom'] ?>" style="float: right;width: 100px;" >
	</td>
	<td>px</td>
</tr>

<tr valign="top">
	<th>&nbsp;</th>
	<td>
		<p style="padding-top:0px;float:left;"><?php _e("Left",TONJOO_ECAE) ?></p>
		<input type="number" name="tonjoo_ecae_options[image_padding_left]" value="<?php echo $options['image_padding_left'] ?>" style="float: right;width: 100px;" >
	</td>
	<td>px</td>
</tr>

<?php 

echo "<tr><th colspan=3><i>".__('Image thumbnail size options below is only work for Content Image: Use Featured Image',TONJOO_ECAE)."</i></th></tr>";

$image_thumbnail_size = array();
foreach (get_intermediate_image_sizes() as $key => $value) {
	$image_thumbnail_size[] = array(
		'value' => $value,
		'label' => $value
	);
}

$image_select = array(
	"name"=>"tonjoo_ecae_options[image_thumbnail_size]",
	"description" => "",
	"label" => __("Image thumbnail size",TONJOO_ECAE),
	"value" => $options['image_thumbnail_size'],
	"select_array" => $image_thumbnail_size
);

echo tj_print_select_option($image_select);

?>