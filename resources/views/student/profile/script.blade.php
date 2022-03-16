
<script type="text/javascript">
    var map;
    let studentLocation, newStudentLocation;

    var Page = function() {
        var _componentPage = function(){

            $(document).ready(function() {
                initAction();
                formSubmit();

                $('#kt_form').trigger("reset");
                $('#kt_form').attr('action',`{{url('student/profile')}}/{{ Auth::guard('student')->user()->id }}`);
                $('#kt_form').attr('method','POST');
            });

            const initAction = () => {
                $(document).on('click', '#account', function(event){
                    event.preventDefault();

                    $('#kt_form').trigger("reset");
                    $('#kt_form').attr('action',`{{url('student/profile')}}/{{ Auth::guard('student')->user()->id }}`);
                    $('#kt_form').attr('method','POST');

                });

                $(document).on('click', '#password', function(event){
                    event.preventDefault();

                    $('#kt_form').trigger("reset");
                    $('#kt_form').attr('action',`{{url('student/profile/change-password')}}/{{ Auth::guard('student')->user()->id }}`);
                    $('#kt_form').attr('method','POST');

                });

                $(document).on('click','.btn-clean',function(event){
                    event.preventDefault();
                    $('#kt_form').trigger("reset");
                })

                $('.upload').on('change', function() {
                    let sizeFile = $(this)[0].files[0].size/1024/1024;
                    sizeFile = sizeFile.toFixed(2);
                    if(sizeFile > 2){
                        setNotifyError('Maksimal Upload: 2 MB', 'Gagal')
                    }
                    readURL(this);
                });

                $(document).on('click','#account',function(){
                    $('#name').attr('required',true);
                    $('#username').attr('required',true);
                    $('#email').attr('required',true);
                    $('#current_password').removeAttr('required');
                    $('#new_password').removeAttr('required');
                    $('#confirm_password').removeAttr('required');
                })

                $(document).on('click','#password',function(event){
                    $('#current_password').attr('required',true);
                    $('#new_password').attr('required',true);
                    $('#confirm_password').attr('required',true);
                    $('#name').removeAttr('required');
                    $('#username').removeAttr('required');
                    $('#email').removeAttr('required');
                })
            }
            formSubmit = () => {
                $('#kt_form').submit(function(event){
                    event.preventDefault();

                    btn_loading_profile('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                    })
                    .done(function(res, xhr, meta) {
                        if (res.status == 200) {
                            toastr.success(res.message, 'Success')
                        }
                    })
                    .fail(function(res, error) {
                        if (res.status == 422) {
                            $.each(res.responseJSON.errors, function(index, err) {
                                if (Array.isArray(err)) {
                                    $.each(err, function(index, val) {
                                        toastr.error(val, 'Failed')
                                    });
                                }
                                else {
                                    toastr.error(err, 'Failed')
                                }
                            });
                        }
                        else {
                            toastr.error(res.responseJSON.message, 'Failed')
                        }
                    })
                    .always(function() {
                        btn_loading_profile('stop')
                    });
                });
            }

            const showModal = function (selector) {
                $('#'+selector).modal('show')
            },
            hideModal = function (selector) {
                $('#'+selector).modal('hide')
            }

            const readURL = (input) => {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {

                        $('.image').html(`
                            <img src="${e.target.result}" class="img-profile-edit rounded" style="width:194px !important; height:194px !important;">
                        `);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };

        };

        return {
            init: function(){
                _componentPage();
            }
        }

    }();

    document.addEventListener('DOMContentLoaded', function() {
        Page.init();
    });

    function initMap() {
        @if (
            !empty(Auth::guard('student')->user()->lat) &&
            !empty(Auth::guard('student')->user()->lng)
        )
        const myLatLng = { lat: {{ Auth::guard('student')->user()->lat }}, lng: {{ Auth::guard('student')->user()->lng}} };
        @else
        const myLatLng = { lat: -7.794915, lng: 110.36832 };    
        @endif

        map = new google.maps.Map(document.getElementById('gmap-div'), {
            zoom: 11,
            center: myLatLng,
        });

        // search location
        let input = document.getElementById('pac-input');
        let searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        @if (
            !empty(Auth::guard('student')->user()->lat) &&
            !empty(Auth::guard('student')->user()->lng)
        )
        studentLocation = new google.maps.Marker({
            map: map,
            title: 'Student Location',
            position: myLatLng,
        });
        @endif
        

        // searched place 
        searchBox.addListener('places_changed', function() {
            let places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // For each place, get the icon, name and location.
            let bounds = new google.maps.LatLngBounds();
            places.forEach((place) => {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                if (typeof newStudentLocation != 'undefined') {
                    newStudentLocation.setMap(null);
                }

                // Create a marker for each place.
                newStudentLocation = new google.maps.Marker({
                    map: map,
                    // icon: icon,
                    title: place.name,
                    position: place.geometry.location,
                    draggable: true
                });

                // update student coordinate location 
                $('#student_coordinate_lat').val(place.geometry.location.lat());
                $('#student_coordinate_lng').val(place.geometry.location.lng());

                // listener draggable marker 
                newStudentLocation.addListener('dragend', (e) => {
                    $('#student_coordinate_lat').val(e.latLng.lat());
                    $('#student_coordinate_lng').val(e.latLng.lng());
                });

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
    }

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&libraries=geometry,places&callback=initMap"></script>
