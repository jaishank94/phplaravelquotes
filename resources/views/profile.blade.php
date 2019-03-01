@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profile</div>

                <div class="card-body">
                    @include('includes.message')
                    <form method="POST" action="{{ route('updateProfile') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" id="name" placeholder="{{ $user->name }}" value="{{ $user->name }}">
                        </div>

                        <div class="form-group">
                            <label>Image (.jpg)</label>
                            <input type="file" class="form-control" name="image" id="image">
                        </div>
                       <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if (Storage::disk('local')->has($user->name . '-' . $user->id . '.jpg'))
                    <img style="height:350px;width:100%;" src="{{ route('userimage', ['filename' => $user->name . '-' . $user->id . '.jpg']) }}" alt="" class="img-responsive">  
                @endif
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
</script>