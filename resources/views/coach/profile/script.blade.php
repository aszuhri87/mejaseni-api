<script type="text/javascript">
    var map;
    let coachLocation, newCoachLocation, mapRadius;

    var Page = function() {
        var _componentPage = function(){
            var init_select_expertise;
            $(document).ready(() => {
                initAction();
                formSubmit();

                $('#kt_form').trigger("reset");
                $('#kt_form').attr('action',`{{url('coach/profile')}}/{{ Auth::guard('coach')->user()->id }}`);
                $('#kt_form').attr('method','POST');

                init_select_expertise = new SlimSelect({
                    select: '#expertise'
                });

                const homeCourseAvailable = `{{ Auth::guard('coach')->user()->home_course_available }}`;
                $('#home_course_available').prop('checked', homeCourseAvailable).change();
            });

            const initAction = () => {
                $(document).on('click', '#account', function(event){
                    event.preventDefault();

                    $('#kt_form').trigger("reset");
                    $('#kt_form').attr('action',`{{url('coach/profile')}}/{{ Auth::guard('coach')->user()->id }}`);
                    $('#kt_form').attr('method','POST');

                });

                $(document).on('click', '#password', function(event){
                    event.preventDefault();

                    $('#kt_form').trigger("reset");
                    $('#kt_form').attr('action',`{{url('coach/profile/change-password')}}/{{ Auth::guard('coach')->user()->id }}`);
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

                $(document).on('keyup', '#coach_coordinate_radius', (e) => {
                    let radius = $('#coach_coordinate_radius').val();
                    const isNumber = !isNaN(radius);

                    if (isNumber && map) {
                        if (typeof newCoachLocation != 'undefined') {
                            // create radius on new coach location
                            initRadius(
                                newCoachLocation.position.lat(),
                                newCoachLocation.position.lng(),
                                radius
                            );
                        } else if (typeof coachLocation != 'undefined') {
                            // create radius on coach location
                            initRadius(
                                coachLocation.position.lat(),
                                coachLocation.position.lng(),
                                radius
                            );
                        }
                    }
                })

                $(document).on('change', '#home_course_available', (e) => {
                    const isChecked = $('#home_course_available').is(':checked');
                    if (isChecked) {
                        $('#home_course_available-label').text('Yes');
                    } else {
                        $('#home_course_available-label').text('No');
                    }
                })
            }
            formSubmit = () => {
                $('#kt_form').submit(function(event){
                    event.preventDefault();

                    btn_loading_profile_coach('start')
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
                        btn_loading_profile_coach('stop','Simpan Perubahan')
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
            !empty(Auth::guard('coach')->user()->lat) &&
            !empty(Auth::guard('coach')->user()->lng)
        )
        const myLatLng = { lat: {{ Auth::guard('coach')->user()->lat }}, lng: {{ Auth::guard('coach')->user()->lng}} };
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

        let radius = $('#coach_coordinate_radius').val();
        const isNumberRadius = !isNaN(radius);

        @if (
            !empty(Auth::guard('coach')->user()->lat) &&
            !empty(Auth::guard('coach')->user()->lng)
        )
        coachLocation = new google.maps.Marker({
            map: map,
            title: 'Coach Location',
            position: myLatLng,
        });
        if (isNumberRadius) {
            initRadius(myLatLng.lat, myLatLng.lng, radius);
        }
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

                if (typeof newCoachLocation != 'undefined') {
                    newCoachLocation.setMap(null);
                }

                // Create a marker for each place.
                newCoachLocation = new google.maps.Marker({
                    map: map,
                    // icon: icon,
                    title: place.name,
                    position: place.geometry.location,
                    draggable: true
                });

                // update coach coordinate location 
                $('#coach_coordinate_lat').val(place.geometry.location.lat());
                $('#coach_coordinate_lng').val(place.geometry.location.lng());

                // update radius 
                if (isNumberRadius) {
                    initRadius(
                        place.geometry.location.lat(),
                        place.geometry.location.lng(),
                        radius
                    );
                }

                // listener draggable marker 
                newCoachLocation.addListener('dragend', (e) => {
                    $('#coach_coordinate_lat').val(e.latLng.lat());
                    $('#coach_coordinate_lng').val(e.latLng.lng());

                    if (isNumberRadius) {
                        initRadius(
                            e.latLng.lat(),
                            e.latLng.lng(),
                            radius
                        );
                    }
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

    const initRadius = (lat, lng, radiusKm) => {
        // remove active radius
        if (typeof mapRadius != 'undefined') {
            mapRadius.setMap(null);
        }

        mapRadius = new google.maps.Circle({
            strokeColor: "#0000EE",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#0000EE",
            fillOpacity: 0.35,
            map,
            center: { 
                lat: lat, 
                lng: lng
            },
            radius: radiusKm * 1000,
        });
    }

</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&libraries=geometry,places&callback=initMap"></script>
