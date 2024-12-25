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
                    <div class="form_block">
                        <div class="form_side">
                            <div class="section_title">
                                <span class="title_badge">Welcome Back</span>
                                <h2>
                                    <span>Sign in</span> to your account
                                </h2>
                                <p>
                                    Quickly access your products and
                                    features.
                                </p>
                            </div>
                            <form>
                                <div class="form-group">
                                    <input type="email" class="form-control" placeholder="Email" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" placeholder="Password" />
                                </div>
                                <div class="forgate_check">
                                    <div class="coustome_checkbox">
                                        <label for="remamber_check">
                                            <input type="checkbox" id="remamber_check" />
                                            <span class="checkmark"></span>
                                            Remember for 30 days
                                        </label>
                                    </div>
                                    <a href={{ route('admin.forget-password') }}>Forgot password ?</a>
                                </div>
                                <div class="btn_block">
                                    <button class="btn puprple_btn ml-0">
                                        Sign In
                                    </button>
                                    <div class="btn_bottom"></div>
                                </div>
                            </form>
                        </div>
                        <div class="side_screen">
                            <div class="dotes_blue">
                                <img src="{{ asset('assets-front') }}/images/blue_dotes.png" alt="image" />
                            </div>
                            <div class="left_icon">
                                <img src="{{ asset('assets-front') }}/images/smallStar.png" alt="image" />
                            </div>
                            <div class="right_icon">
                                <img src="{{ asset('assets-front') }}/images/bigstar.png" alt="image" />
                            </div>
                            <div class="scrren">
                                <img src="{{ asset('assets-front') }}/images/sign_in_screen.png" alt="image" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <!-- Page-wrapper-End -->
    <!-- scripts -->
    @include('admin.auth.partials.scripts')
</body>

</html>