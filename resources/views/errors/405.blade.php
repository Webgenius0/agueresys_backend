@extends('errors::minimal')

@section('content')
    <!-- main section start -->
    <main>
        <section class="container error-container">
            <div class="row">
                <div class="col-md-6">
                    <figure class="error-img">
                        <img src="{{asset('frontend/assets')}}/images/404.png" alt="">
                    </figure>
                </div>
                <div class="col-md-6">
                    <h2 class="title">Oops! Page Not Found</h2>
                    <p class="des">
                        You must have picked the worng door because I haven’t been able to
                        lay my eye on the page you’ve been searching for.
                    </p>
                    <a href="/" class="button">Back to home</a>
                </div>
            </div>
        </section>
    </main>
    <!-- main section end -->
@endsection

