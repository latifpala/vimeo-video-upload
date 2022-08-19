// Copyright Darko Gjorgjijoski <info@codeverve.com>
// 2020. All Rights Reserved.
// This file is licensed under the GPLv2 License.
// License text available at https://opensource.org/licenses/gpl-2.0.php

var notice = function (message, type) {
    return '<div class="alert alert-' + type + ' is-dismissible dgv-clear-padding">' + message + '</div>\n';
};

(function ($) {
    /**
     * Ajax select plugin
     * @param url
     * @param opts
     * @returns {*|jQuery|HTMLElement}
     */
    $.fn.ajaxSelect = function (url, opts) {

        if(!jQuery.fn.select2) {
            console.log('Video Uploads for Vimeo: Select2 library is not initialized.');
            return false;
        }

        var translated = {
            errorLoading: function () {
                return DGV.phrases.select2.errorLoading;
            },
            inputTooLong: function (args) {
                var overChars = args.input.length - args.maximum;
                var message = DGV.phrases.select2.inputTooShort;
                message = message.replace('{number}', overChars);
                if (overChars != 1) {
                    message += 's';
                }
                return message;
            },
            inputTooShort: function (args) {
                var remainingChars = args.minimum - args.input.length;
                var message = DGV.phrases.select2.inputTooShort;
                message = message.replace('{number}', remainingChars);
                return message;
            },
            loadingMore: function () {
                return DGV.phrases.select2.loadingMore;
            },
            maximumSelected: function (args) {
                var message = DGV.phrases.select2.maximumSelected;
                message = message.replace('{number}', args.maximum);
                if (args.maximum != 1) {
                    message += 's';
                }
                return message;
            },
            noResults: function () {
                return DGV.phrases.select2.noResults;
            },
            searching: function () {
                return DGV.phrases.select2.searching;
            },
            removeAllItems: function () {
                return DGV.phrases.select2.removeAllItems;
            },
            removeItem: function () {
                return DGV.phrases.select2.removeItem;
            },
            search: function () {
                return DGV.phrases.select2.search;
            }
        }

        var params = {
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                type: 'POST',
                headers: {'Accept': 'application/json'},
                data: function (params) {
                    return {
                        s: params.term,
                    };
                },
                processResults: function (response) {
                    var options = [];
                    if (response.success) {
                        for (var i in response.data) {
                            var id = response.data[i].id;
                            var name = response.data[i].name;
                            options.push({id: id, text: name});
                        }
                    }
                    return {results: options};
                },
                cache: true
            },
            language: translated,
            minimumInputLength: 2,
            width: '100%'
        };

        $.extend(params, opts);
        $(this).select2(params);
        return $(this);
    }


    // Initialize
    var url = DGV.ajax_url + '?action=dgv_user_search&_wpnonce='+ DGV.nonce;
    $(document).find('.dgv-select2').each(function () {
        console.log('initializing select2');
        var params = {};
        var placehodler = $(this).data('placeholder');
        if(placehodler) {
            params.placeholder = placehodler;
        }
        $(this).ajaxSelect(url, params);
    });
    $(document).on('change', '.dgv-select2', function(){
        var value = $(this).val();
        if(value) {
            $('.dgv-clear-selection').show();
        } else {
            $('.dgv-clear-selection').hide();
        }
    });
    $(document).on('click', '.dgv-clear-selection', function(e){
        e.preventDefault();
        var target = $(this).data('target');
        $(target).each(function(e){
            $(this).val(null).trigger('change');
        })
    })

})(jQuery);

// Handle vimeo upload
(function ($) {
    jQuery('.wvv-video-upload').submit(function (e) {

        var $self = $(this);
        var $loader = $self.find('.dgv-loader');
        var $submit = $self.find('button[type=submit]');
        var $progressBar = $self.find('#progress-bar-wrapper');
        var file_upload_done = false;

        var formData = new FormData(this);
        //formData.append('action', 'geeker_store_data');

        var videoFile = formData.get('vimeo_video');
        var title = formData.get('vimeo_video_title');
        var description = formData.get('vimeo_video_description');
        var privacy = DGV.default_privacy;

        if (!WPVimeoVideos.Uploader.validateVideo(videoFile)) {
            swal.fire(DGV.sorry, DGV.upload_invalid_file, 'error');
            return false;
        }

        var errorHandler = function ($eself, error) {
            $('#geeker-vimeo-submit').html('Upload');
            var type = 'error';
            var $_notice = $eself.find('.wvv-notice-wrapper');
            if ($_notice.length > 0) {
                $_notice.remove();
            }
            var message = '';
            var error_msg = '';
            try {
                var errorObject = JSON.parse(error);
                if(errorObject.hasOwnProperty('invalid_parameters')) {
                    for(var i in errorObject.invalid_parameters) {
                        var msg = errorObject.invalid_parameters[i].error + ' ' + errorObject.invalid_parameters[i].developer_message;
                        error_msg += '<li>'+msg+'</li>';
                    }
                }
                message = '<p style="margin-bottom: 0;font-weight: bold;">'+DGV.correct_errors+':</p>' + '<ul style="list-style: circle;padding-left: 20px;">'+error_msg+'</ul>';
            } catch (e) {
                message = error;
            }

            $eself.prepend(notice(message, type));
            $eself.find('.dgv-loader').css({'display': 'none'});
            $eself.find('button[type=submit]').prop('disabled', false);
        };

        var updateProgressBar = function($pbar, value) {
            if($pbar.is(':hidden')) {
                $pbar.show();
            }
            $pbar.find('#progress-bar').css({width: value + '%'})
            $pbar.find('#progress-bar').text(value + '%');
            $pbar.find('#progress-bar').attr('aria-valuenow', value);
        };


        /* Store data before upload */
        var data_id = 0;
        $.ajax({
            url: DGV.ajax_url + '?action=geeker_store_data_before_upload',
            data: formData,
            type: "POST",
            dataType: 'json',
            enctype: 'multipart/form-data',
            timeout: 600000,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $submit.prop('disabled', true);
                $('#geeker-vimeo-submit').html('Please wait..');
            },
            success: function (response) {
                data_id = response.data.id;
                file_upload_done = true;

                // first store data then process video
                var uploader = new WPVimeoVideos.Uploader(DGV.access_token, videoFile, {
                    'title': title,
                    'description': description,
                    'data_id' : data_id,
                    'privacy': privacy,
                    'action' : 'dgv_store_upload',
                    'wp': {
                        'notify_endpoint': DGV.ajax_url,
                    },
                    'beforeStart': function () {
                        $loader.css({'display': 'inline-block'});
                        $submit.prop('disabled', true);
                        //var option_id = storeData(formData);
                    },
                    'onProgress': function (bytesUploaded, bytesTotal) {
                        $('#geeker-vimeo-submit').html('Uploading..');
                        var percentage = (bytesUploaded / bytesTotal * 100).toFixed(2);
                        updateProgressBar($progressBar, percentage);
                    },
                    'onSuccess': function (response, currentUpload) {
                        var type = response.success ? 'success' : 'error';
                        var message = response.data.message;
                        var $_notice = $self.find('.wvv-notice-wrapper');
                        if ($_notice.length > 0) {
                            $_notice.remove();
                        }
                        
                        $self.append(notice(message, type));
                        setTimeout(function(){
                            $self.get(0).reset();
                            $loader.css({'display': 'none'});
                            $submit.prop('disabled', false);
                            $('#geeker-vimeo-submit').html('Upload');
                            updateProgressBar($progressBar, 0);
                            $progressBar.hide();
                        }, 1000);
                    },
                    'onError': function (error) {
                        errorHandler($self, error);
                    },
                    'onVideoCreateError': function (error) {
                        errorHandler($self, error);
                    },
                    'onWPNotifyError': function (error) {
                        errorHandler($self, error);
                    }
                });
                uploader.start();
            },
            error:function (response){
                
            }
        });
        return false;
    });
})(jQuery);

// Handle vimeo settings
(function ($) {
    $('#dg-vimeo-settings').submit(function (e) {
        var $self = $(this);
        var $btn = $self.find('button[type=submit]');
        var data = $self.serialize();
        $.ajax({
            url: DGV.ajax_url + '?action=dgv_handle_settings&_wpnonce=' + DGV.nonce,
            type: 'POST',
            data: data,
            beforeSend: function(){
                $btn.prepend('<span class="dashicons dashicons-update dgv-spin"></span>');
            },
            success: function (response) {
                var message;
                var type;
                if (response.success) {
                    message = response.data.message;
                    type = 'success';
                    if (response.data.hasOwnProperty('api_info')) {
                        $self.find('.vimeo-info-wrapper').html(response.data.api_info);
                    }
                } else {
                    message = response.data.message;
                    type = 'error';
                }
                var $_nwrapper = $self.closest('.wrap').find('.wvv-notice-wrapper');
                if ($_nwrapper.length > 0) {
                    $_nwrapper.html('');
                }
                $_nwrapper.prepend(notice(message, type));
            },
            complete: function() {
                setTimeout(function(){
                    $btn.find('.dashicons-update').remove().detach();
                }, 200);
            }
        });
        return false;
    });
})(jQuery);

// Handle video update
(function($){
    jQuery('.wvv-video-upload-edit').submit(function (e) {
        e.preventDefault();
        var $self = $(this);
        var $loader = $self.find('.dgv-loader');
        var $submit = $self.find('button[type=submit]');
        var $progressBar = $self.find('#progress-bar-wrapper');

        var formData = new FormData(this);
        var videoFile = formData.get('vimeo_video');
        var data_id = formData.get('data-id');
        
        if (!WPVimeoVideos.Uploader.validateVideo(videoFile)) {
            // New video file is not selected. Just update other content.
            $.ajax({
                url: DGV.ajax_url + '?action=geeker_update_data_without_video',
                data: formData,
                type: "POST",
                dataType: 'json',
                enctype: 'multipart/form-data',
                timeout: 600000,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $submit.prop('disabled', true);
                    $('#geeker-vimeo-submit').html('Please wait..');
                },
                success: function (response) {
                    data_id = response.data.id;
                    var message = response.data.message;
                    var type = response.success ? 'success' : 'error';
                    
                    var $_notice = $self.find('.wvv-notice-wrapper');
                    if ($_notice.length > 0) {
                        $_notice.remove();
                    }
                    
                    $self.append(notice(message, type));
                    $('#geeker-vimeo-submit').removeAttr('disabled');
                    $('#geeker-vimeo-submit').html('Upload');
                    // redirect to listing page
                },
            });
        }else{
            
        }
    });
})(jQuery);

// Fix problems
(function($){
    $(document).on('click', '.wvv-problem-fix-trigger', function(e){
        e.preventDefault();
        var $wrap = $(this).closest('.wvv-problem-wrapper');
        var $fixWrap = $wrap.find('.wvv-problem--fix')
        var text = $fixWrap.text();
        swal.fire({
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false,
            html: '<div class="wvv-problem-solution">\n' +
                '\t<h2>'+DGV.problem_solution+'</h2>\n' +
                '\t<p>'+text+'</p>\n' +
                '</div>',
        });
    });
})(jQuery);

(function($){
    $(document).on('click', '.geeker-delete-video', function(e){
        var video_id = $(this).data('video');

        swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: DGV.ajax_url + '?action=geeker_delete_video',
                    data: {
                        id:video_id
                    },
                    type: "POST",
                    dataType: 'json',
                    success:function(response){
                        if(response.data.status=='failed'){
                            Swal.fire(response.data.message, '', 'error');
                        }else if(response.data.status=='success'){
                            Swal.fire({
                                icon: 'success',
                                title: response.data.message,
                            }).then((result2) => {
                                window.location.href = '';
                            });
                        }
                    },
                    error:function(){
                        Swal.fire('Something went wrong. Please try again.', '', 'error')
                    }
                });
                
            } else if (result.isDenied) {
              //Swal.fire('Something went wrong. Please try again.', '', 'info')
            }
        });
    });
})(jQuery);
