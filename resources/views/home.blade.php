@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::id())
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-success text-white">
                <div class="card-header">What's new?</div>

                <div class="card-body">
                    {{-- @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif --}}
                    @include('includes.message')
                    <form method="POST" action="{{ route('postCreate') }}">
                        @csrf
                        <div class="form-group">
                            <textarea class="form-control" name="new-post" id="new-post" row="5" placeholder="Write here"></textarea>
                        </div>
                       <button type="submit" class="btn btn-danger float-right">Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        @if (!empty($posts))
            @foreach ($posts as $item)
                <div class="col-sm-4" style="margin-top:1rem;">
                    <div class="card bg-light text-dark">
                        @if(Auth::id())
                        <div class="card-header">
                            <div class="float-left">
                                <a class="btn btn-link" id="unlike_{{ $item->id }}" @if(count(\App\Like::where(["id_post"=>$item->id, "id_user"=>Auth::id()])->get())==0) style="display:none" @endif onclick="unlikeClick({{ $item->id }})">
                                    <span id="likeCount_{{ $item->id }}">{{ count(\App\Like::where(["id_post"=>$item->id])->get()) }}</span> <i class="fa fa-heart" style="color:red;" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-link" id="like_{{ $item->id }}" @if(count(\App\Like::where(["id_post"=>$item->id, "id_user"=>Auth::id()])->get())>0) style="display:none" @endif onclick="likeClick({{ $item->id }})">
                                    <span id="likeCount_{{ $item->id }}">{{ count(\App\Like::where(["id_post"=>$item->id])->get()) }}</span> <i class="fa fa-heart-o" aria-hidden="true"></i>
                                </a>
                            </div>
                                                                    
                            @if(Auth::id() == $item->id_user)
                            <div class="float-right">
                                <a class="btn btn-link" id="editForm" onclick="updateModal('{{ $item->body }}', {{ $item->id }})" data-body="{{ $item->body }}" data-bodyId="{{ $item->id }}" data-toggle="modal" data-target="#myModal">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </a>
                                <a class="btn btn-link" href="{{ route('postDelete', ['id'=>$item->id]) }}">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </a>
                            </div>
                            @endif
                        </div>
                        @endif
                        <div class="card-body">
                        <blockquote class="blockquote mb-0">
                            <p id="postBody_{{ $item->id  }}">{{ $item->body }}</p>
                            <footer class="blockquote-footer">{{ $item->user->name }} <cite class="float-right" title="Source Title">{{ $item->created_at->diffForHumans() }}</cite></footer>
                        </blockquote>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="row justify-content-center">
        {{ $posts->links() }}
    </div>
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit</h4>
      </div>
      <div class="modal-body">
            {{-- <div id="updateMessage"></div> --}}
            <form method="POST" action="{{ route('postCreate') }}">
                @csrf
                <div class="form-group">
                    <textarea class="form-control" id="update-post" name="new-post" id="new-post" row="5" placeholder="What's new?"></textarea>
                </div>
                <input type="hidden" id="update-post-id" name="post-id" value="">
            </form>
      </div>
      <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="saveEdit" onclick="updatePost()" data-dismiss="modal">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
@endsection

<script>
     var token = '{{ Session::token() }}';
     var url = '{{ route('edit') }}';

        function updateModal(body, bodyId) {
            $("#update-post").val( body );
            $("#update-post-id").val( bodyId );
        }

        function updatePost() {
            $post = $("#update-post").val();
            $postid = $("#update-post-id").val();

            $.ajax({
                method: "POST",
                url: url,
                data: {body : $post, postId: $postid, _token: token}
            }).done(function(msg){
                // $("#updateMessage").text(JSON.stringify(msg.message));
                $("#postBody_"+$postid).text($post);
            });
        }

        function likeClick($id) {
            // var count = $("#likeCount_"+$id).text();
            $.ajax({
                method: "POST",
                url: '{{ route('likePost') }}',
                data: {postId: $id, _token: token}
            }).done(function(msg){
                $("#like_"+$id).hide();
                // $("#likeCount_"+$id).empty();
                // $("#likeCount_"+$id).text(count+1);
                $("#unlike_"+$id).show();
            });
        }

        function unlikeClick($id) {
            // var count = $("#likeCount_"+$id).text();
            $.ajax({
                method: "POST",
                url: '{{ route('unLikePost') }}',
                data: {postId: $id, _token: token}
            }).done(function(msg){
                $("#unlike_"+$id).hide();
                // $("#likeCount_"+$id).empty();
                // $("#likeCount_"+$id).text(count-1);
                $("#like_"+$id).show();
            });
        }
</script>