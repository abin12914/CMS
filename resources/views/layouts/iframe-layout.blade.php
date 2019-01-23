<!DOCTYPE html>
<html>
    <head>
        <!-- sections/head.main.blade -->
        @include('sections.head')

        {{-- additional stylesheet includes --}}
        @section('stylesheets')
        @show
    </head>
    <body>
        <div class="wrapper">

            <!-- Content Wrapper. Contains page content -->
            @section('content')
            @show

        </div>
        <!-- ./wrapper -->

        <!-- REQUIRED JS SCRIPTS -->
        @include('sections.scripts')

        {{-- additional js scripts includes --}}
        @section('scripts')
        @show

        {{-- message type and message for sweet alert --}}
        <script type="text/javascript">
            alertType    = "{{ Session::get('alert-class') }}";
            alertMessage = "{{ Session::get('message') }}";
        </script>
    </body>
</html>
