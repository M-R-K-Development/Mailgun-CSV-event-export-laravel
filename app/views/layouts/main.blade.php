<!doctype html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="/assets/vendors/bootstrap/dist/css/bootstrap.min.css">
    <style>
      body {
        background-color: lightgrey;
        height: auto;
      }
      section.container {
        background-color: white;
        min-height: 800px;
        padding-top:40px;
      }

    </style>
    @yield('styles')
  </head>
  <body>

      <div id="container" >
        @yield('content')
        <section class="footer">

        </section>
      </div>

  </body>
    <script src='/assets/vendors/jquery/dist/jquery.min.js'></script>
    <script src='/assets/vendors/bootstrap/dist/js/bootstrap.min.js'></script>
    @yield('scripts')
</html>
