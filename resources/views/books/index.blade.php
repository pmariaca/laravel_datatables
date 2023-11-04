@extends('layouts.app')


@section('content')
@include('books._category')
<br>
@include('books._edit')

<div class="container contbl" style="margin-top: 50px">
    <table class="table books-table">
        <thead>
            <tr>
                <!-- <th>id</th> -->
                <th>Name</th>
                <th>Cover</th>
                <!-- <th>description</th> -->
                <th>Categories</th>
                <th>Published</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- ======================================================== -->
<script type="module">
    $(document).ready(function() {
        var table = $('.books-table').DataTable({
            buttons: [
                'copy', 'csv', 'excel', 'pdf'
            ],
            processing: true,
            serverSide: true,
            ajax: "{{route('books.index')}}",
            'iDisplayLength': 6,
            "lengthMenu": [
                [5, 6, 10, 25, 50, -1],
                [5, 6, 10, 25, 50, "All"]
            ],
            columns: [
                // {data: 'id',name: 'id'},
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'image_url',
                    "render": function(data) {
                        if (data != null) {
                            return '<img src="' + data + '" width="60px">';
                        }
                        return '';
                    }
                },
                // {
                //     data: 'description',
                //     name: 'description'
                // },
                {
                    data: 'categorias',
                    render: function(data) {
                        var arr = jQuery.parseJSON(data);
                        var ar = [];
                        $.each(arr, function(key, value) {
                            ar[key] = value.title;
                        });
                        return ar.join(', ');
                    },
                },
                {
                    data: 'published_at',
                    name: 'published_at'
                },
                {
                    data: null,
                    render: function(data) {
                        var edit_button = '<button type="button" name="edit" id="' + data.id + '" class="edit btn btn-primary btnmin">Edit</button>';
                        var delete_button = '<button type="button" id="deleteBook" onclick=dltClk("' + data.id + '") class="delete btn btn-danger btnmin">Delete</button>';
                        return '<div class="d-flex flex-row">' + edit_button + '&nbsp;&nbsp;' + delete_button + '</div>';
                    },
                    sWidth: '5%',
                    orderable: false,
                    searchable: false
                },
            ]
        });
        //<!-- ======================================================== -->
        $('#add_category').on('click', function(event) {
            event.preventDefault();
            var action_url = "{{ route('categories.store') }}";
            var formData = $('#category_form').serialize();
            doAjax2(action_url, formData, 'post', 1);
        });

        $('#update_category').on('click', function(event) {
            event.preventDefault();
            var action_url = "{{ route('categories.update') }}";
            var formData = $('#category_edit_form').serialize();
            doAjax2(action_url, formData, 'post', 1);
        });

        $('#delete_category').on('click', function(event) {
            event.preventDefault();
            var action_url = "/categories/destroy/" + $('#hidden_idcat').val() + "/";
            var formData = '';
            doAjax2(action_url, formData, 'get', 1);
        });

        function doAjax2(aUrl, formData, atype, flg) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: aUrl,
                type: atype,
                dataType: 'json',
                data: formData,
                success: function(data) {
                    if (data.success) {
                        if (flg == 1) {
                            $("#category_idcat option").remove();
                            $("#category_id option").remove();
                            $('#titlecat').val('');
                            $('#titledit').val('');
                            $.each(data.categories, function(i, value) {
                                $("#category_idcat").append('<option onclick="onClk();" value="' + value.id + '">' + value.title + '</option>');
                                $("#category_id").append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        } else {
                            $('#delete_record_close').trigger('click');
                            $('.books-table').DataTable().ajax.reload();
                        }
                    }
                    if (data.error) {
                        alert(data.error);
                    }
                },
                error: function(e) {
                    var errors = e.responseJSON;
                },
            });
        }
        //<!-- ======================================================== -->
        $('#create_book_btn').click(function() {
            $('.modal-title').text('Addnew record');
            $('#action_button').text('Add');
            $('#action').val('Add');
            $('#name').val('');
            $('#description').val('');
            $('#image_url').val('');
            $('#imgpreview').attr('src', '');
            $("#category_id").val([]);
            $('#published_at').val('');
            $('#form_result').html('');
            if($("#collapseOne").hasClass("show")){
                $(".accordion-button").trigger("click");
            }
        });

        $('#action_button').on('click', function(event) {
            event.preventDefault();
            var action_url;
            if ($('#action').val() == "Add") {
                action_url = "{{ route('books.store') }}";
            }
            if ($('#action').val() == "Edit") {
                action_url = "{{ route('books.update') }}";
            }
            doAjax(action_url, 'post', 1);
        });

        $(document).on('click', '.edit', function(event) {
            event.preventDefault();
            var id = $(this).attr('id');
            $('#imgpreview').attr('src', '');
            $('#image_url').val('');
            $("#category_id").val([]);
            $('#form_result').html('');
            $('#hidden_id').val(id);
            $('.modal-title').html('Edit record');
            $('#action_button').html('Update');
            $('#action').val('Edit');
            var action_url = "/books/edit/" + id + "/";
            doAjax(action_url, 'get', 2);
        });

        window.dltClk = function(id) {
            event.preventDefault();
            $('#hiddenBook_id').val(id);
            $('#delete_book_dml').trigger('click');
        }
        $('#delete_book').on('click', function(event) {
            event.preventDefault();
            var action_url = "/books/destroy/" + $('#hiddenBook_id').val() + "/";
            var formData = '';
            doAjax2(action_url, formData, 'get', 2);
        });

        function doAjax(aUrl, atype, flg) {
            var formData = '';
            if (flg == 1) {
                const form = document.getElementById("edit_form");
                const submitter = document.getElementById("action_button");
                formData = new FormData(form, submitter);
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: aUrl,
                type: atype,
                dataType: 'json',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if (flg == 1) {
                        var html = '';
                        if (data.error) {
                            html = '<div class="alert alert-warning">';
                            $.each(data.error, function(index, value) {
                                html += '<p>' + value + '</p>';
                            });
                            html += "</div>";
                        };
                        if (data.success) {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#edit_form')[0].reset();
                            $('#imgpreview').attr('src', '');
                            $('.books-table').DataTable().ajax.reload();
                            $("#close_action_button").trigger('click');
                        }
                        $('#form_result').html(html);
                    }
                    //-------------------
                    if (flg == 2) {
                        $('#name').val(data.result.name);
                        $('#description').val(data.result.description);
                        $('#imgpreview').attr('src', '/storage/' + data.result.image_url);
                        $('#published_at').val(data.result.published_at);
                        $('#category_id').val(data.result.category_id);
                        $("#edit_book_btn").trigger('click');
                        $.each(data.result.categories, function(i, value) {
                            $("#category_id option[value='" + value.id + "']").prop("selected", true);
                        });
                    }
                },
                error: function(e) {
                    var errors = e.responseJSON;
                },
            });
        }
    });
    //<!-- ======================================================== -->

    $('#category_idcat option').on('click', function() {
        event.preventDefault();
        $('#hidden_idcat').val(this.value);
        $('#titledit').val($(this).text());
    });

    window.onClk = function() {
        var txt = $("#category_idcat option:selected").text();
        var id = $("#category_idcat option:selected").val();
        $('#hidden_idcat').val(id);
        $('#titledit').val(txt);
    }
    window.previewFile = function() {
        const preview = document.querySelector("img");
        const file = document.querySelector("input[type=file]").files[0];
        const reader = new FileReader();
        reader.addEventListener(
            "load",
            function() {
                // convierte la imagen a una cadena en base64
                preview.src = reader.result;
            },
            false,
        );
        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection