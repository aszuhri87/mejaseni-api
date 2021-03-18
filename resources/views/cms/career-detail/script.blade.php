<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {
            var docsDropzone;
            var arr_path = [];

            $(document).ready(function () {
                AOS.init();
                initFilepond();
                formSubmit();
            });

            const initFilepond = () => {
                FilePond.registerPlugin(FilePondPluginImagePreview);

                FilePond.setOptions({
                    server: {
                        process: {
                            url: "{{url('/media/file')}}",
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            onload: (response) => {
                                data = JSON.parse(response);
                                arr_path.push({id: data.data.id, path: data.data.path})
                                return data.data.id;
                            },
                        },
                        revert: (uniqueFileId, load, error) => {
                            $.each(arr_path, function(index, arr_data){
                                if(uniqueFileId == arr_data['id']){
                                    $.ajax({
                                        url: "{{url('media/file')}}/"+uniqueFileId,
                                        type: 'DELETE',
                                        dataType: 'json',
                                    })
                                    .done(function(res, xhr, meta) {
                                        arr_path.splice(index, 1);
                                        load();
                                    })
                                }
                            })
                        }
                    }
                });

                const inputElement = document.querySelector('input[type="file"]');
                const pond = FilePond.create(inputElement);
            },
            formSubmit = () => {
                $('#form-apply-career').submit(function(event){
                    event.preventDefault();

                    let form_data = new FormData(this)

                    for (var i = 0; i < arr_path.length; i++) {
                        form_data.append('files[]', arr_path[i]['path']);
                    }

                    $.ajax({
                        url: "{{url('/career')}}",
                        type: $(this).attr('method'),
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                    })
                    .done(function(res, xhr, meta) {
                        $('#modal-apply-career').modal('hide')
                        $('.send-success').show();
                        $('.send-failed').hide();
                    }).fail(function(res, error) {
                        $('.send-success').hide();
                        $('.send-failed').show();
                    });
                });
            }
        };
        return {
            init: function () {
                _componentPage();
            }
        }
    }();

    document.addEventListener('DOMContentLoaded', function () {
        Page.init();
    });

</script>
