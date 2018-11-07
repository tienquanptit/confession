@extends ('user.layouts.master')

@section ('style')
    <link href="{{ asset('bower_components/demo-bower/confession/user/css/jasny-bootstrap.min.css') }}"
          rel="stylesheet">
    <link href="{{ asset('bower_components/demo-bower/confession/user/css/selectize.default.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('bower_components/demo-bower/confession/user/css/sweet-alert.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('bower_components/demo-bower/confession/user/css/toastr.min.css') }}">

@endsection

@section ('content')
    <!-- Content Wrapper START -->
    <div class="main-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <ul class="list-media">
                                <li class="list-item">
                                    <div class="p-b-15">
                                        <div class="media-img">
                                            {{ Html::image(asset(config('common.img') . 'icon.png'), '') }}
                                        </div>
                                        <div class="info">
                                            <span class="title">F_Confession</span>
                                            <span class="sub-title">@f_confession</span>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <p>{{ __('message.info') }}</p>
                        </div>
                        <div class="card-footer">
                            <p class="text-dark font-size-13"><b>{{ __('message.follow_me') }}</b></p>
                            <ul class="list-inline">
                                <li class="m-r-15">
                                    <a class="text-gray" href="">
                                        <i class="mdi mdi-instagram font-size-25"></i>
                                    </a>
                                </li>
                                <li class="m-r-15">
                                    <a class="text-gray" href="">
                                        <i class="mdi mdi-facebook font-size-25"></i>
                                    </a>
                                </li>
                                <li class="m-r-15">
                                    <a class="text-gray" href="">
                                        <i class="mdi mdi-twitter font-size-25"></i>
                                    </a>
                                </li>
                                <li class="m-r-15">
                                    <a class="text-gray" href="">
                                        <i class="mdi mdi-dribbble font-size-25"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title m-b-25">{{ __('message.topic') }}</h4>
                            <div class="row">
                                @foreach ($topics as $key => $topic)
                                    <div class="box8">
                                        {{ Html::image(asset(config('common.topic') . $topic->images), '') }}
                                        <h3 class="title">{{ $topic->name }}</h3>
                                        <div class="box-content">
                                            <ul class="icon">
                                                <li>
                                                    {{ Form::button('<i class="fa fa-plus"></i>' . __('message.follow'), ['id' => 'follow_topic', 'class' => 'btn btn-info btn-rounded btn-xs']) }}
                                                </li>
                                                <li>
                                                    {{ Form::button(__('message.following'), ['id' => 'un_follow_topic', 'class' => 'btn btn-info btn-rounded btn-xs']) }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="border top p-v-15 p-h-20 text-center">
                            <a href="" class="text-semibold text-dark d-block">{{ __('message.more') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="social-footer">

                            <div class="social-comment">
                                <a href="" class="pull-left">
                                    {{ Html::image(asset(config('common.img') . 'avatar-5.png'), '') }}
                                </a>
                                <div class="media-body">
                                    @if (Auth::check())
                                        {{ Form::open(['route' => 'posts.store', 'method' => 'POST', 'files' => true]) }}
                                        <div class="m-b-5">
                                            {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => __('message.title')]) }}
                                            {{ Form::hidden('slug', null, ['id' => 'slug']) }}
                                        </div>
                                        <div class="m-b-5">
                                            {{ Form::textarea('body', null, ['class' => 'form-control', 'placeholder' => __('message.your_mean'), 'rows' => '3']) }}
                                        </div>
                                        <div class="row m-b-5">
                                            <div class="col-md-4">
                                                {{ Form::select('topic', $topicAll, '', ['class' => 'form-control', 'placeholder' => __('message.select_topic')]) }}
                                            </div>
                                            <div class="col-md-4">
                                                {{ Form::select('type', ['0' => __('message.anomyous'), '1' => __('message.not_anomyous')], '', ['class' => 'form-control', 'placeholder' => __('message.select_type')]) }}
                                            </div>
                                            <div class="col-md-4">
                                                {{ Form::file('filename[]', ['id' => 'images', 'multiple']) }}
                                            </div>
                                        </div>
                                        <div class="row" id="image_preview"></div>
                                        <br>
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success btn-rounded']) }}
                                        {{ Form::close() }}
                                    @else
                                        {{ Form::open(['route' => 'posts.store', 'method' => 'POST', 'files' => true]) }}
                                        <div class="m-b-5">
                                            {{ Form::text('title', null, ['onkeyup' => 'ChangeToSlug()', 'id' => 'title', 'class' => 'form-control', 'placeholder' => __('message.title')]) }}
                                            {{ Form::hidden('slug', null, ['id' => 'slug']) }}
                                        </div>
                                        <div class="m-b-5">
                                            {{ Form::textarea('body', null, ['class' => 'form-control', 'placeholder' => __('message.your_mean'), 'rows' => '3']) }}
                                        </div>
                                        <div class="row m-b-5">
                                            <div class="col-md-4">
                                                {{ Form::select('type', ['0' => __('message.anomyous')], '0', ['class' => 'form-control', 'placeholder' => __('message.select_type')]) }}
                                            </div>
                                            <div class="col-md-4">
                                                {{ Form::file('filename[]', ['id' => 'images', 'multiple']) }}
                                            </div>
                                        </div>
                                        <div class="row" id="image_preview"></div>
                                        <br>
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success btn-rounded']) }}
                                        {{ Form::close() }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        @foreach ($posts as $key => $post)
                            <div class="feed-header">
                                <ul class="list-media">
                                    <li class="list-item">
                                        <div class="p-h-30 p-t-30">
                                            @if ($post->type == 0)
                                                <div class="media-img">
                                                    {{ Html::image(asset(config('common.img') . 'avatar-5.png'), '') }}
                                                </div>
                                                <div class="info">
                                                    <span class="title">F-Confession</span>
                                                    <span class="sub-title">@Anomyous</span>
                                                    <div class="float-item">
                                                        <span>{{ $post->created_at }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="media-img">
                                                    {{ Html::image(asset(config('common.post') . $post->users->images), $post->users->name) }}
                                                </div>
                                                <div class="info">
                                                    <span class="title">{{ $post->users->name }}</span>
                                                    <span class="sub-title"><span>@</span>{{ $post->users->nick_name }}</span>
                                                    <div class="float-item">
                                                        <span>{{ $post->created_at }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="p-15">
                                <a href=""><p class="m-b-5">{{ $post->title }}</p></a>
                                <p class="m-b-15">{{ $post->body }}</p>
                                {{ Html::image(asset(config('common.images') . 'avatar-5.png'), '', ['class' => 'img-fluid w-100']) }}
                                <ul class="list-inline m-t-20 p-v-15">
                                    <li class="m-r-25">
                                        <a id="like" class="text-gray font-size-16" title="" onclick="like()">
                                            <i class="fa fa-thumbs-o-up text-info p-r-5"></i>
                                            <span>168</span>
                                        </a>
                                        <a id="unlike" class="text-gray font-size-16" title="" onclick="unLike()">
                                            <i class="fa fa-thumbs-up text-info p-r-5"></i>
                                            <span>168</span>
                                        </a>
                                    </li>
                                    <li class="m-r-20">
                                        <a class="text-gray font-size-16" title="Comment">
                                            <i class="ti-comments text-success p-r-5"></i>
                                            <span>18</span>
                                        </a>
                                    </li>
                                    <li class="m-r-20">
                                        <a id="report" class="text-gray font-size-16" title="" onclick="report()">
                                            <i class="fa fa-flag-o text-primary p-r-5"></i>
                                            <span>5</span>
                                        </a>
                                        <a id="reported" class="text-gray font-size-16" title="" onclick="reported()">
                                            <i class="fa fa-flag text-primary p-r-5"></i>
                                            <span>5</span>
                                        </a>
                                    </li>
                                    <li class="m-r-20">
                                        <a href="" class="text-gray font-size-16" title="Delete">
                                            <i class="ti-trash text-danger p-r-5"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="social-footer">
                                <div class="social-comment" id="comment">
                                    <a href="" class="pull-left">
                                        {{ Html::image(asset(config('common.images') . 'avatar-5.png'), '', ['class' => 'img-fluid w-100']) }}
                                    </a>
                                    <div class="media-body">
                                        <a href="">name</a> -
                                        <small class="text-muted">created_at</small>
                                        - <a data-id="id" class="text-danger btnDelete" title="Delete"
                                             onclick="deleteComment()"><i class="fa fa-trash"></i></a>
                                        <br>
                                        content
                                        <br>
                                    </div>
                                </div>
                                <div id="load_comment"></div>
                                @if (Auth::check())
                                    {{ Form::open(['method' => 'POST', 'id' => 'comment_form_'. $post->id]) }}
                                    {!! Form::hidden('post_id', $post->id, ['id' => 'post_id']) !!}
                                    {!! Form::hidden('user_id', Auth::user()->id, ['id' => 'user_id']) !!}
                                    <div class="social-comment">
                                        <a href="#" class="pull-left">
                                            {{ Html::image(asset(config('common.images') . 'avatar-5.png'), '', ['class' => 'img-fluid w-100']) }}
                                        </a>
                                        <div class="media-body">
                                            {{ Form::textarea('body', null, ['id' => 'body', 'class' => 'form-control content', 'placeholder' => __('message.write_comment'), 'rows' => '2']) }}
                                            <br>
                                            {!! Form::button('Comment', ['name' => 'comment', 'class' => 'btn btn-success btnComment', 'id' => 'comment', 'onclick' => 'postComment(' . $post->id .')']) !!}
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title m-b-25">{{ __('message.friends') }}</h4>
                            <ul class="list-media">
                                <li class="list-item">
                                    <div class="p-b-15">
                                        <div class="media-img">
                                            {{ Html::image(asset(config('common.images') . 'avatar-5.png'), '') }}
                                        </div>
                                        <div class="info">
                                            <a href=""><span class="title">name</span></a>
                                            <span class="sub-title"><span>@</span>nick_name</span>
                                            <div id="follow_user">
                                                {{ Form::button('<i class="fa fa-plus"></i>' . __('message.follow'), ['onclick' => 'followUser()', 'class' => 'btn btn-info btn-rounded btn-outline btn-xs']) }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="border top p-v-15 p-h-20 text-center">
                            <a href="" class="text-semibold text-dark d-block">{{ __('message.more') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content Wrapper END -->
@endsection

@section ('script')

    <script src="{{ asset('bower_components/demo-bower/confession/user/js/jasny-bootstrap.min.js') }}"></script>
    <script src="{{ asset('bower_components/demo-bower/confession/user/js/selectize.min.js') }}"></script>
    <script src="{{ asset('bower_components/demo-bower/confession/user/js/sweet-alert.min.js') }}"></script>
    <script src="{{ asset('bower_components/demo-bower/confession/user/js/toastr.min.js') }}"></script>
    <script src="{{ asset('js/user.js') }}" type="text/javascript"></script>
@endsection
