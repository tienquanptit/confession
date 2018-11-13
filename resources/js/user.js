$('#images').on('change', function () {
    var total_file = document.getElementById('images').files.length;
    for (var i = 0; i < total_file; i++) {
        $('#image_preview').append("<div class='col-md-3'><img width='100%' class='img-responsive' src='" + URL.createObjectURL(event.target.files[i]) + "'></div>");
    }
});

$('#title').on('keyup', function () {
    var title, slug;

    //Lấy text từ thẻ input title
    title = document.getElementById("title").value;

    //Đổi chữ hoa thành chữ thường
    slug = title.toLowerCase();

    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');

    //Xóa các ký tự đặt biệt
    slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');

    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");

    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');

    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');

    //In slug ra textbox có id “slug”
    document.getElementById('slug').value = slug;
});

function postComment(post_id) {
    var form = $('#comment_form_' + post_id);
    var formdata = form.serialize();
    var config = $('#config').val();

    $.ajaxSetup({
        headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')}
    });

    $.ajax({
        type: 'POST',
        url: route('comments.store'),
        data: formdata,
        success: function (res) {
            if (res.info.images == null) {
                $avatar = config;
            } else {
                $avatar = res.info.images;
            }

            $('#load_comment_' + res.post_id).append(`<div class="social-comment" id="comment` + res.comment_id + `">
                                                                    <a href="" class="pull-left">
                                                                        <img alt="` + res.info.name + `" src="` + $avatar + `">
                                                                    </a>
                                                                    <div class="media-body">
                                                                    <a href=""> ` + res.info.name + ` </a> - <small class="text-muted">` + res.created_at + `</small> - <a data-id="` + res.comment_id + `" class="btnDelete" title="Delete" onclick="deleteComment(` + res.comment_id + `)"><i class="fa fa-trash"></i></a>
                                                                    <br>
                                                                    ` + res.data.body + `
                                                                    <br>
                                                                    </div>
                                                                </div>`);

            $('.body').val('');
        }
    });
}

function deleteComment(comment_id) {
    var delete_comment = $('#message_delete_comment').val();
    var yes = $('#message_yes').val();
    var no = $('#message_no').val();

    swal({
            title: delete_comment,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            cancelButtonText: no,
            confirmButtonText: yes,
        },
        function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'DELETE',
                url: 'cfs/comments/' + comment_id,
                success: function (res) {
                    $('#comment' + comment_id).remove();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    //
                }
            });
        });
}

/* Like */
$(document).on('click', '.like', function () {
    var post_id = $(this).data('postid');
    var user_id = $(this).data('userid');
    var like_id = $(this).data('likeid');
    var type_id = $(this).data('typeid');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'PUT',
        url: '/cfs/likes/' + post_id,
        data: {
            user_id: user_id,
            post_id: post_id,
            like_id: like_id
        },
        success: function (res) {
            if (!res.error) {
                $('#like_' + post_id).replaceWith(`<i id="unlike_` + post_id + `" class="text-gray font-size-16 dislike" title="" data-typeid="1" data-postid="` + post_id + `" data-userid="` + user_id + `" data-likeid="` + like_id + `">
                                                                        <i class="fa fa-thumbs-up text-info p-r-5"></i>
                                                                        <span>168</span>
                                                                    </i>`);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //
        }
    });
});

$(document).on('click', '.dislike', function () {
    var type_id = $(this).data('typeid');
    var post_id = $(this).data('postid');
    var user_id = $(this).data('userid');
    var like_id = $(this).data('likeid');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'PUT',
        url: 'cfs/likes/' + post_id,
        data: {
            post_id: post_id,
            user_id: user_id,
            like_id: like_id,
            type_id: type_id
        },
        success: function (res) {
            if (!res.error) {
                $('#unlike_' + post_id).replaceWith(`<a id="like_` + post_id + `" class="text-gray font-size-16 like" title="" data-typeid="0" data-postid="` + post_id + `" data-userid="` + user_id + `" data-likeid="` + like_id + `">
                                                                        <i class="fa fa-thumbs-o-up text-info p-r-5"></i>
                                                                        <span>168</span>
                                                                    </a>`);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //
        }
    });
});

/* Report */
$(document).on('click', '.report', function () {
    var post_id = $(this).data('postid');
    var user_id = $(this).data('userid');
    var report_id = $(this).data('reportid');
    var type_id = $(this).data('typeid');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'PUT',
        url: '/cfs/reports/' + post_id,
        data: {
            post_id: post_id,
            user_id: user_id,
            report_id: report_id
        },
        success: function (res) {
            if (!res.error) {
                $('#report_' + post_id).replaceWith(`<a id="reported_` + post_id + `" class="text-gray font-size-16 reported" title="" data-typeid="1" data-postid="`+ post_id +`" data-userid="`+ user_id +`" data-reportid="`+ report_id +`">
                                                                        <i class="fa fa-flag text-primary p-r-5"></i>
                                                                        <span>168</span>
                                                                    </a>`);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //
        }
    });
});

$(document).on('click', '.reported', function () {
    var post_id = $(this).data('postid');
    var user_id = $(this).data('userid');
    var report_id = $(this).data('reportid');
    var type_id = $(this).data('typeid');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'PUT',
        url: '/cfs/reports/' + post_id,
        data: {
            post_id: post_id,
            user_id: user_id,
            type_id: type_id,
            report_id: report_id
        },
        success: function (res) {
            if (!res.error) {
                $('#reported_' + post_id).replaceWith(`<a id="report_` + post_id + `" class="text-gray font-size-16 report" title="" data-typeid="0" data-postid="`+ post_id +`" data-userid="`+ user_id +`" data-reportid="`+ report_id +`">
                                                                        <i class="fa fa-flag-o text-primary p-r-5"></i>
                                                                        <span>168</span>
                                                                    </a>`);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            //
        }
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url(` + e.target.result + `)');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$('#imageUpload').change(function() {
    readURL(this);
});
