(function ($) {
    jQuery('input:radio[name="vimeo_video_price"]').change(function(){
        if (this.checked && this.value == 'vimeo_video_price_other') {
            jQuery('.other_price').show();
        }else{
            jQuery('.other_price').hide();
        }
    });

    jQuery('input:radio[name="who_can_shop"]').change(function() {
        if (this.checked && this.value == 'group_only') {
            jQuery('.max_vimeo_groups').show();
            jQuery('.max_vimeo_denomination').hide();
        }
    });

    jQuery('input:radio[name="who_can_shop"]').change(function() {
        if (this.checked && this.value == 'denomination_only') {
            jQuery('.max_vimeo_denomination').show();
            jQuery('.max_vimeo_groups').hide();
        }
    });

    jQuery('input:radio[name="who_can_shop"]').change(function() {
        if (this.checked && this.value == 'anyone' || this.value == 'members_only') {
            jQuery('.max_vimeo_denomination').hide();
            jQuery('.max_vimeo_groups').hide();
        }
    });

    $('.select2').select2({
        theme: "bootstrap"
    });

    // fetch organization details
    $('#video_organization').change(function(e){
        var organization_id = $(this).val();
        $.ajax({
            url: geeker_obj_script.ajax_url,
            type: 'POST',
            data: {
                'action' : 'geeker_get_organization_details',
                'organization_id' : organization_id
            },
            dataType: 'json',
            beforeSend: function(){
                //$btn.prepend('<span class="dashicons dashicons-update dgv-spin"></span>');
            },
            success: function (response) {
                $('#video_organization_city').val(response.data.city);
                $('#video_organization_state').val(response.data.state);
                $('#video_organization_denomination').val(response.data.category_names);
                $('#video_organization_denomination_ids').val(response.data.category_ids);
            },
        });
    });

    $('#vimeo_folder').change(function(e){
        if($(this).val() == 'vimeo_add_new'){
            $('#new_vimeo_folder_wrapper').show();
        }else{
            $('#new_vimeo_folder_wrapper').hide();
        }
    });

    $('.payment_type').click(function(e){
        var payment_type = $(this).val();
        if(payment_type=='one-time' || payment_type=='recurring'){
            $('#video_price_wrapper').show(100);
        }else{
            $('#video_price_wrapper').hide(100);
        }
    });

    $('.select-speaker').change(function(e){
        var speaker_type = $(this).data('speaker-type');
        var speaker = $(this).val();
        var field_class = speaker_type+'-speaker-field';
        var field_class2 = speaker_type+'-speaker-enter-field';

        if(speaker==""){
            $('.'+field_class).hide();
            $('.'+field_class2).hide();
        }else if(speaker==0){
            $('.'+field_class).hide();
            $('.'+field_class2).show();
        }else{
            var firstname = $('option:selected', this).data('firstname');
            var lastname = $('option:selected', this).data('lastname');
            var firstname_key = speaker_type+'_speaker_first_name';
            var lastname_key = speaker_type+'_speaker_last_name';

            $('#'+firstname_key).val(firstname);
            $('#'+lastname_key).val(lastname);
            $('.'+field_class2).hide();
            $('.'+field_class).show();
        }
    });
})(jQuery);