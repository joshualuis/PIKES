<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-wide " dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="front-pages">

  
  @include('layouts.landing.header')

<body>
  <script src="../../assets/vendor/js/dropdown-hover.js"></script>
  <script src="../../assets/vendor/js/mega-dropdown.js"></script>

  @include('layouts.landing.navbar')
  @yield('script')
  <div data-bs-spy="scroll" class="scrollspy-example">

    @yield('content')

  </div>



  @include('layouts.landing.footer')

  
  @include('layouts.landing.script')
  
</body>

</html>


