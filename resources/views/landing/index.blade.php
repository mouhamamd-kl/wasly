<!DOCTYPE html>
<html lang="en">

@include('landing.partials.head')

<body>

  <!-- Page Wraper -->
  <div class="page_wrapper">
    <!-- Preloader -->
    <div id="preloader">
      <div id="loader"></div>
    </div>

    <!-- Header-Section- -->
    @include('landing.partials.header')


    <!-- Banner-Section- -->
    @include('landing.partials.banner')

    <!-- Our Client -->
    @include('landing.partials.ourClient')

    <!-- Key Feature -->

    @include('landing.partials.keyFeature')
  </div>

  <!-- Page Wraper -->
  <div class="page_wrapper">

    <!-- About Us -->
    @include('landing.partials.aboutUs')

    <!-- Why Choose Our App   -->

    @include('landing.partials.whychooseOurApp')

    <!-- Service Section  -->
    @include('landing.partials.service')

    <!-- How It Work Section  -->

    @include('landing.partials.howItWork')
  </div>
  <!-- Wraper End -->

  <!-- Positive Reviews Section -->
  @include('landing.partials.positiveReviews')

  <!-- Page Wraper -->
  <div class="page_wrapper">
    <!-- Pricing-Section -->
    @include('landing.partials.pricing')

    <!-- Beautifull-interface-Section  -->
    @include('landing.partials.beautifullInterface')

    <!-- Download Section Start -->
    @include('landing.partials.download_1')

    <!-- Blog Section  -->
    {{-- @include('landing.partials.blogSection') --}}

    <!-- Footer-Section -->
    @include('landing.partials.footer')

    <!-- go top button -->
    <div class="go_top" id="Gotop">
      <span><i class="icofont-arrow-up"></i></span>
    </div>

    <!-- Video Model  -->
    @include('landing.partials.videoModal')
  </div>
  <!-- Page-wrapper-End -->

  {{-- Scripts --}}
  @include('landing.partials.scripts')
</body>

</html>