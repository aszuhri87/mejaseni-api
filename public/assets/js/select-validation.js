function ss_validate(arr = []){
    var valid = true;

    if(arr.length > 0){
        for (let index = 0; index < arr.length; index++) {
            let ssid = $('#'+arr[index]).attr('data-ssid');

            if($('#'+arr[index]).val() == ''){
                $('.'+ssid+' > .ss-single-selected').css('border-color', 'red');

                valid = false;
            }else{
                $('.'+ssid+' > .ss-single-selected').css('border-color', '#E4E6EF');
            }
        }
    }

    return valid;
}
