<!DOCTYPE html>
<html lang="en">

{{-- head --}}
@include('admin.auth.partials.head')

<body>
    <!-- Page-wrapper-Start -->
    <div class="page_wrapper">
        <!-- Preloader -->
        <div id="preloader">
            <div id="loader"></div>
        </div>

        <div class="full_bg">
            <section class="signup_section">
                <div class="container">
                    <div class="top_part">
                        <a href={{ route('admin.index') }} class="back_btn"><i class="icofont-arrow-left"></i> Back to
                            home</a>
                        <a class="navbar-brand" href={{ route('admin.index') }}>
                            <img src="{{ asset('assets-front') }}/images/logo.svg" alt="image" />
                        </a>
                    </div>

                    <!-- Sign In Form -->
                    @yield('content')
                </div>
            </section>
        </div>
    </div>
    <!-- Page-wrapper-End -->
    <!-- scripts -->
    @include('admin.auth.partials.scripts')
</body>

</html>