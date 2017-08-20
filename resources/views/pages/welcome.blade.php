@extends('main')

@section('title', '| Homepage')

@section('stylesheets')
    <link rel="stylesheet" type="text/css" href="styles.css">
@endsection

@section('content')
       <div class="row">
           <div class="col-md-12">
               <div class="jumbotron">
                   <h1>Welcome to My New Blog!</h1>
                   <p class="lead">Thank you so much for visiting. This is my test website built with Laravel. Please read my popular post!</p>
                   <p><a href="#" class="btn btn-primary btn-lg" role="button">Popular Post</a></p>
               </div>
           </div>
           <div class="row">
               <div class="col-md-8">

                   @foreach($posts as $post)

                       <div class="post">
                            <h3>{{$post->title}}</h3>
                            <p>{{substr( $post->body, 0, 300)}}{{ strlen($post->body) > 300 ? "..." : "" }}</p>
                             <a href="{{ url('blog/' .$post->slug) }}" class="btn btn-primary">Read More</a>
                        </div>

                   @endforeach
               </div>  <!-- end of .col-md-8 -->
               <div class="col-md-3 col-md-offset-1">
                   <h2>Sidebar</h2>
               </div>
           </div>
       </div>

@endsection

