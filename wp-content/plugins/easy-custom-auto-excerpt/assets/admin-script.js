jQuery(document).ready(function($) {
    /**
     * Tabs
     */
    if ( $('.nav-tab-wrapper').length > 0 ) {
        js_tabs();
    }

    function js_tabs() {

        var group = $('.group'),
            navtabs = $('.nav-tab-wrapper a'),
            active_tab = '';

        /* Hide all group on start */
        group.hide();
        
        if(window.location.hash) 
        {
            /* Find if a hash link selected */
            active_tab = window.location.hash;
        }        
        else if(typeof(localStorage) != 'undefined') 
        {
            /* Find if a selected tab is saved in localStorage */
            active_tab = localStorage.getItem('active_tab');
        }

        /* If active tab is saved and exists, load it's .group */
        if ( active_tab != '' && $(active_tab).length ) {
            $(active_tab).fadeIn();
            $(active_tab + '-tab').addClass('nav-tab-active');
        } else {
            $('.group:first').fadeIn();
            $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
            active_tab = $('.nav-tab-wrapper a:first');
        }

        /* Bind tabs clicks */
        navtabs.click(function(e) {

            e.preventDefault();

            /* Remove active class from all tabs */
            navtabs.removeClass('nav-tab-active');

            $(this).addClass('nav-tab-active').blur();

            if (typeof(localStorage) != 'undefined' ) {
                localStorage.setItem('active_tab', $(this).attr('href') );
            }

            var selected = $(this).attr('href');

            group.hide();
            $(selected).fadeIn();
        });
    }
    // activate navbar tab via button
    $('.navbar-button').click(function(){
        $('.nav-tab-wrapper a').trigger('click');

        setTimeout(function() {
            window.scrollTo(0, 0);
        }, 1);
    });
    // disable hash jump
    if (window.location.hash) {
        setTimeout(function() {
            window.scrollTo(0, 0);
        }, 1);
    }   



    /**
	 * Ace editor
	 */
	var editor = ace.edit("ace-editor");
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/css");
    editor.getSession().on('change', function(e) {
    	var code = editor.getSession().getValue();

    	jQuery("#ace_editor_value").val(code);        
        preview_button();
	});

    /**
     * Select2 Button
     */
	function format(state) {
        if (!state.id) return state.text; // optgroup

        var name_select = state.id.toLowerCase();
        var name_select_array = name_select.split('-premium');

        if(name_select_array[1] == 'true')
        {
            var button = ecae_button_premium_dir_name + name_select_array[0];
        }
        else
        {
            var button = ecae_button_dir_name + name_select_array[0];
        }

        return "<div><img class='images' src='" + button + ".png'/></div>" + "<p>" + state.text + "</p>";
    }
    $("select[name='tonjoo_ecae_options[button_skin]']").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function(m) { return m; }
    }).on("change", function(e) {
            preview_button(); 
    });

    /**
     * Select2 Dropdown Page
     */
    $('.excerpt-in-select2').select2();

    /**
     * Readmore preview in options
     */
    $("input[name='tonjoo_ecae_options[read_more]']").on('keyup',function(){
        preview_button(); 
    })
    $("input[name='tonjoo_ecae_options[read_more_text_before]']").on('keyup',function(){
        preview_button(); 
    })
    $("select[name='tonjoo_ecae_options[read_more_align]']").on('change',function(){
        preview_button(); 
    })
    $("select[name='tonjoo_ecae_options[button_font]']").on('change',function(){
        if(ecae_premium_enable == false)
        {
            alert('Please purchase the premium edition to enable this feature');
            $("select[name='tonjoo_ecae_options[button_font]']").val('');
        }
        else
        {
            preview_button(); 
        }
    })
    $("input[name='tonjoo_ecae_options[button_font_size]']").on('keyup mouseup',function(){
        if(ecae_premium_enable == false)
        {
            alert('Please purchase the premium edition to enable this feature');
            $("input[name='tonjoo_ecae_options[button_font_size]']").val('14');
        }
        else
        {
            preview_button(); 
        }
    })
    $("select[name='tonjoo_ecae_options[excerpt_method]']").on('change',function(){
        if(ecae_premium_enable == false && $(this).val() != 'paragraph' && $(this).val() != 'word')
        {
            alert('The excerpt method below is only enable in premium edition. Please purchase the premium edition to enable this feature \n\n+ Show First Paragraph \n+ Show 1st - 2nd Paragraph \n+ Show 1st - 3rd');
            $("select[name='tonjoo_ecae_options[excerpt_method]']").val('paragraph');
        }
    })

    function preview_button()
    {
        var button_skin = $("select[name='tonjoo_ecae_options[button_skin]']").val();
        var lasSubstring = button_skin.substr(button_skin.length - 12);

        if(ecae_premium_enable == false && lasSubstring == "-PREMIUMtrue")
        {
            alert('Please purchase the premium edition to enable this feature');
            $("select[name='tonjoo_ecae_options[button_skin]']").select2("val", "ecae-buttonskin-none");

            button_skin = "ecae-buttonskin-none";
        }
        
        data = {
            action: 'ecae_preview_button',
            read_more: $("input[name='tonjoo_ecae_options[read_more]']").val(),
            read_more_text_before: $("input[name='tonjoo_ecae_options[read_more_text_before]']").val(),
            read_more_align: $("select[name='tonjoo_ecae_options[read_more_align]']").val(),
            button_font: $("select[name='tonjoo_ecae_options[button_font]']").val(),
            button_font_size: $("input[name='tonjoo_ecae_options[button_font_size]']").val(),
            button_skin: button_skin,
            custom_css: editor.getSession().getValue()
        }

        // $('#ecae_ajax_preview_button').html("<img src='" + ecae_dir_name + "/assets/loading.gif'>");

        $.post(ajaxurl, data,function(response){
            $('#ecae_ajax_preview_button').html(response);
        });
    }

    /**
     * location_settings_type
     */
    var location_settings_type = $('input[name="tonjoo_ecae_options[location_settings_type]"]').val();
    $('#' + location_settings_type).addClass('button-primary');

    $('.location-settings-btn').click(function(){
        var id = $(this).attr('id');

        $('.location-settings-form').hide();
        $('#' + id + '-form').fadeIn();
        $('.location-settings-btn').removeClass('button-primary');
        $(this).addClass('button-primary');
        $('input[name="tonjoo_ecae_options[location_settings_type]"]').val(id);
    });

    /**
     * CloneYa
     */ 
    $("#page-excerpt-clone .ordinary-select-2").children("select").select2();

    var number = 0;

    if($("#page-excerpt-clone .toclone").length > 0)
    {
        number = $("#page-excerpt-clone .toclone").length;
    }

    $('#page-excerpt-clone').cloneya()
    .on('clone_before_clone', function(event, toclone, newclone) {
        $("#page-excerpt-clone .ordinary-select-2")
            .children("select")
            .select2("destroy")
            .end();
    })
    .on('clone_after_append', function(event, toclone, newclone) {
        $("#page-excerpt-clone .ordinary-select-2").children("select").select2();

        $(newclone).find("select[class='page_category_select']").attr('name', 'page_category['+ number +'][]');
        $(newclone).find("select[class='page_post_type_select']").attr('name', 'page_post_type['+ number +'][]');

        number++;
    });

    /**
     * Toogle display advanced options
     */
    $('select[name = "tonjoo_ecae_options[advanced_home]"]').on('change', function(){
        if($(this).val() == 'selection')
        {
            $('.advanced_home').show();
            $('.advanced_home_width').show();
        }
        else if($(this).val() == 'all')
        {
            $('.advanced_home').hide();
            $('.advanced_home_width').show();
        }
        else
        {
            $('.advanced_home').hide();
            $('.advanced_home_width').hide();
        }
    })

    $('select[name = "tonjoo_ecae_options[advanced_frontpage]"]').on('change', function(){
        if($(this).val() == 'selection')
        {
            $('.advanced_frontpage').show();
        }
        else if($(this).val() == 'all')
        {
            $('.advanced_frontpage').hide();
            $('.advanced_frontpage_width').show();
        }
        else
        {
            $('.advanced_frontpage').hide();
            $('.advanced_frontpage_width').hide();
        }
    })

    $('select[name = "tonjoo_ecae_options[advanced_archive]"]').on('change', function(){
        if($(this).val() == 'selection')
        {
            $('.advanced_archive').show();
        }
        else if($(this).val() == 'all')
        {
            $('.advanced_archive').hide();
            $('.advanced_archive_width').show();
        }
        else
        {
            $('.advanced_archive').hide();
            $('.advanced_archive_width').hide();
        }
    })

    $('select[name = "tonjoo_ecae_options[advanced_search]"]').on('change', function(){
        if($(this).val() == 'selection')
        {
            $('.advanced_search').show();
        }
        else if($(this).val() == 'all')
        {
            $('.advanced_search').hide();
            $('.advanced_search_width').show();
        }
        else
        {
            $('.advanced_search').hide();
            $('.advanced_search_width').hide();
        }
    })

    $('select[name = "tonjoo_ecae_options[advanced_page_main]"]').on('change', function(){
        if($(this).val() == 'all')
        {
            $('.page_all').show();
            $('.page_selection').hide();
        }
        else if($(this).val() == 'selection')
        {
            $('.page_all').show();
            $('.page_selection').show();
        }
        else
        {
            $('.page_all').hide();
            $('.page_selection').hide();
        }
    })


    /**
     * Run on start
     */
    preview_button();
});