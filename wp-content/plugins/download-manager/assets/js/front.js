jQuery(function ($) {

    $('body').on('click', function (e) {
        //did not click a popover toggle or popover
        if ($(e.target).data('toggle') !== 'popover'
            && $(e.target).parents('.popover.in').length === 0) {
            try {
                //$('.popover').remove();
                //$('.wpdm-download-link').popover('hide');
                //$('.wpdm-download-link').attr('data-ready', 'hide');
                $('.wpdm-download-locked.pop-over').each(function(){
                    if($(this).data('ready') == 'show'){
                        $(this).popover('hide');
                        $(this).data('ready', 'hide');
                    }
                });
            }catch(e){}
        }
    });

    $('.input-group input').on('focus', function () {
        $(this).parent().find('.input-group-addon').addClass('input-group-addon-active');
    });
    $('.input-group input').on('blur', function () {
        $(this).parent().find('.input-group-addon').removeClass('input-group-addon-active');
    });

    $('body').on('click', '.inddl', function () {
        var tis = this;
        $.post( wpdm_site_url, {
            wpdmfileid: $(this).data('pid'),
            wpdmfile: $(this).data('file'),
            actioninddlpvr: 1,
            filepass: $($(this).data('pass')).val()
        }, function (res) {
            res = res.split('|');
            var ret = res[1];
            if (ret == 'error') $($(tis).data('pass')).addClass('input-error');
            if (ret == 'ok') location.href = $(tis).attr('rel') + '&_wpdmkey=' + res[2];
        });
    });

    $('body').on('click', '.wpdm-download-locked.pop-over', function () {
        var $dc = $($(this).attr('href'));
        var prts = $(this).attr('href').split('_');
        var pid = prts[1];

        if ($(this).attr('data-ready') == undefined || $(this).attr('data-ready') == 'hide') {

            $(this).popover({
                placement: 'bottom',
                html: true,
                content: function () {

                    if(wpdm_ajax_popup == 1)
                    return "<div id='popcnt_"+pid+"'><i class='fa fa-refresh fa-spin'></i> Loading...</div>";
                    else
                    return $dc.html();

                }
            });
            $(this).popover('show');

            if(wpdm_ajax_popup == 1)
                $("#popcnt_"+pid).load(ajax_url,{action:'showLockOptions',id:pid});

            $(this).data('ready', 'show');
        } else {
            $(this).popover('hide');
            $(this).date('ready', 'hide');
        }

        return false;
    });



    $('body').on('click', '.wpdm-indir', function (e) {
        e.preventDefault();
        $('#xfilelist').load(location.href, {
            action: 'wpdmfilelistcd',
            pid: $(this).data('pid'),
            cd: $(this).data('dir')
        });
    });



    $('body').on('click', '.wpdm-btn-play', function (e) {
        e.preventDefault();
        var player = $('#' + $(this).data('player'));
        var btn = $('#' + this.id);

        if (btn.data('state') == 'playing') {
            $(this).data('state', 'paused');
            player.trigger('pause');
            $(this).html("<i class='fa fa-play'></i>");
            return false;
        }

        if (btn.data('state') == 'paused') {
            $(this).data('state', 'playing');
            player.trigger('play');
            $('.wpdm-btn-play').html("<i class='fa fa-play'></i>");
            $(this).html("<i class='fa fa-pause'></i>");
            return false;
        }


        player.attr('src', $(this).data('song') + "&play=song.mp3");
        player.slideDown();
        $('.wpdm-btn-play').data("state", "stopped");
        $('.wpdm-btn-play').html("<i class='fa fa-play'></i>");
        btn.html("<i class='fa fa-spinner fa-spin'></i>");
        player.unbind('loadedmetadata');
        player.on('loadedmetadata', function () {
            console.log("Playing " + this.src + ", for: " + this.duration + "seconds.");
            btn.html("<i class='fa fa-pause'></i>");
            btn.data('state', 'playing');
            //audio.play();
        });
    });


    // Uploading files
    var file_frame, dfield;


    jQuery('body').on('click', '.wpdm-media-upload', function (event) {
        event.preventDefault();
        dfield = jQuery(jQuery(this).attr('rel'));

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery(this).data('uploader_title'),
            button: {
                text: jQuery(this).data('uploader_button_text')
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on('select', function () {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            dfield.val(attachment.url);

        });

        // Finally, open the modal
        file_frame.open();
    });

    try {
        /*
         FB.login(function(response) {
         if (response.session) {

         var user_id = response.session.uid;
         var page_id = "40796308305"; //coca cola
         var fql_query = "SELECT uid FROM page_fan WHERE page_id = "+page_id+"and uid="+user_id;
         var the_query = FB.Data.query(fql_query);

         the_query.wait(function(rows) {

         if (rows.length == 1 && rows[0].uid == user_id) {
         $("#container_like").show();

         //here you could also do some ajax and get the content for a "liker" instead of simply showing a hidden div in the page.

         } else {
         $("#container_notlike").show();
         //and here you could get the content for a non liker in ajax...
         }
         });


         } else {
         // user is not logged in
         }
         });
         */
    } catch (err) {
    }

});
