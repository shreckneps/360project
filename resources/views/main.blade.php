<!DOCTYPE html>

@php 
    $defaultPages = array(
        url("/") => "Dashboard",
        url("/listings") => "Your Listings",
        url("/offers") => "Your Offers",
        url("/add") => "Add Listing"
    );
    if(isset($user) && $user->type == 'customer') {
        $defaultPages[url("/exactSearch")] = "Exact Search";
        $defaultPages[url("/rankedSearch")] = "Ranked Search";
        $defaultPages[url("/needSearch")] = "Need-based Search";
    }
@endphp


<html>
<head>
    <title> @yield('title')  </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"> </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"> </script>
</head>

<body>
    <div class="container"> <div class="row">
        <div id="side-nav" class="col-2">
            <nav class="nav flex-column"> <div class="nav-pills">
                @section('navLinks')
                    @foreach ($defaultPages as $url => $title)
                        @if ($url == url()->current())
                            <a class="nav-link active" href="{{ $url }}">{{ $title }}</a>
                        @else
                            <a class="nav-link" href="{{ $url }}">{{ $title }}</a>
                        @endif
                    @endforeach
                @show
            </div> </nav>
        </div>

        <div id="main-content-wrapper" class="col-md">
            <div id="feedback-wrapper"> </div>
            
            <div class="alert alert-dismissible" id="feedback-prototype" hidden>
                <div></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"> </button>
            </div>

            <script>
                var alertsShown = 0;
                var useQuery;
                var useRoute;
                function showAlert(contents, type = "secondary") {
                    $("#feedback-wrapper").prepend($("#feedback-prototype").clone()
                        .removeAttr("hidden").attr("id", "feedback-" + (alertsShown++))
                        .addClass("alert-" + type).children().first().html(contents).parent()
                    );
                }
            </script>

            <div id="main-content">
                @yield('mainContent')
            </div>

        </div>

    </div> </div>
</body>
</html>
