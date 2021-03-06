
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


	<title>{{ config('app.name', 'Pet Allert') }}</title>
	<link rel="shortcut icon" href="{{ asset('image/favicon.ico') }}">
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/custom.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini" >
<div class="wrapper" id="app">

    @include('layouts.components.navbar')

    @include('layouts.components.sidebar')

    @include('layouts.components.content')

    @include('layouts.components.footer')
</div>

@auth
    <script type="text/javascript">
        window.user = @json(auth()->user())

  

    </script>
@endauth

    <script src="/js/app.js"></script>
    <script type="text/javascript">
        $("#logout").on("click", function() {
            swal.fire({
                    title: 'Are you sure?',
                    text: "You're about to Logout",
                    icon: 'error',
                    position: 'top-end',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Logout',
                    backdrop:true,
                    allowOutsideClick: false,
                    }).then((result) => {
                         if (result.value) {
                            if (result.value===true) { 
                                    $('#logout-form').submit() // this submits the form 
                                } 
                         }
                    })
        })  
    </script> 
</body>
</html>
