<?php

class eace_content_regex
{
    var $key;
    var $holder;
    var $regex;
    var $unique_char;
    var $image;
    var $options;

    public function __construct($unique_char, $regex,$options,$image=false)
    {
        $this->regex = $regex;
        $this->unique_char = $unique_char;

        $this->image = $image;
        $this->options = $options;
    }

    public function replace(&$content, &$width, $number = false, &$total_width=0)
    {
        //get all image in the content
        preg_match_all($this->regex, $content, $this->holder, PREG_PATTERN_ORDER);

        $this->key = 0;

        //only cut bellow the $number variabel treshold ( to limit the number of replacing)
        if($number) array_slice($this->holder[0], 0, $number);

        foreach ($this->holder[0] as $text)
        {
            $unique_key = "{$this->unique_char}{$this->key}{$this->unique_char}";

            $content   = str_replace($text, $unique_key, $content);

            if(!$this->image&&strpos($content,'<!--STOP THE EXCERPT HERE-->')===false)
            {
                $total_width = $total_width + strlen(wp_kses($text,array()));

                if($total_width > $width && !strpos($this->options['excerpt_method'],'-paragraph'))
                {
                    //tell plugin to stop at this point
                    $content = str_replace($unique_key, "{$unique_key}<!--STOP THE EXCERPT HERE--><!--- SECRET END TOKEN ECAE --->",$content);
                    //exit loop

                    if($this->options['excerpt_method'] == 'word')
                    {
                        //if use word cut technique
                        $overflow = $total_width - $width;

                        $current_lenght =  strlen(wp_kses($text,array()));

                        $overflow = $current_lenght-$overflow;

                        // $pos = get cut position based on fixed position ($overflow), but without break last word
                        $pos = strpos($text, ' ', $overflow);
                        $holder_str = substr($text,0,$pos);

                        // delete last non alphanumeric character (save the ">", because it's html end markup)
                        $holder_str = preg_replace('/[`!@#$%^&*()_+=\-\[\]\';,.\/{}|":<?~\\\\]$/', '', $holder_str);

                        $holder_str = wp_kses($holder_str,array());

                        $holder_str  = "<p>{$holder_str}<!-- READ MORE TEXT --></p>";

                        $this->holder[0][$this->key] = $holder_str;
                    }
                    else
                    {
                        //if use preserve paragraph technique
                        $this->holder[0][$this->key]  = "{$this->holder[0][$this->key]}<!-- READ MORE TEXT -->";
                    }

                    //strip the text
                    $content = substr($content, 0, strpos($content,'<!--- SECRET END TOKEN ECAE --->'));

                    break;
                }
            }

            $this->key = $this->key + 1;
        }
    }

    function restore(&$content, $maximal = false)
    {
        //maximal number to restore
        if (!$maximal) $maximal = $this->key;

        //serves as counter, how many replace are made
        $i = 0;

        for ($i; $i < $maximal; $i++) {
            if (isset($this->holder[0][$i]))
            {
                $content = str_replace("{$this->unique_char}{$i}{$this->unique_char}", $this->holder[0][$i], $content);
            }
        }
    }

    function remove(&$content)
    {
        $content = preg_replace($this->regex, "", $content);
    }
}

function ecae_convert_caption($content,$options)
{
    $results[0] = array();

    $pattern = '/\[(\[?)(caption)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)/s';

    preg_match_all($pattern, $content, $results);

    $img_num = 0;

    foreach ($results[0] as $result)
    {
        $img_num++;

        $caption = do_shortcode($result);

        if($options['show_image'] == 'first-image' && $img_num == 1)
        {
            $content = str_replace($result,$caption,$content);
        }
        else if($options['show_image'] == 'yes')
        {
            $content = str_replace($result,$caption,$content);
        }
        else
        {
            $content = str_replace($result,'',$content);
        }
    }

    return $content;
}

function strip_empty_tags($str, $repto = NULL)
{
    //** Return if string not given or empty.
    if (!is_string ($str) || trim ($str) == '')
        return $str;

    //** Recursive empty HTML tags.
    return preg_replace (

        //** Pattern written by Junaid Atari.
        '/<([^<\/>]*)>([\s|&nbsp;]*?|(?R))<\/\1>/imsU',

        //** Replace with nothing if string empty.
        !is_string ($repto) ? '' : $repto,

        //** Source string
        $str
    );
}