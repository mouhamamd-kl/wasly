
@extends('admin.auth.master')
@section('title','Reset Password')
@section('content')
<!-- Reset Password Form -->
<div class="form_block">
    <div class="form_side">
        <div class="section_title">
            <span class="title_badge">Forget Password</span>
            <h2>
                <span>Set</span> a new password
            </h2>
            <p>
                Enter a new password to access your account. Ensure it meets the required criteria.
            </p>
        </div>
        {{-- Display the status message --}}
        <x-auth-session-status class="mb-4" :status="$test" />
        
        {{-- <form method="POST" action="{{ route('password.update') }}"> --}}

            <form method="POST" action="{{ route('customer.password.store') }}">
                @csrf
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <input readonly type="email" name="email" class="form-control" placeholder="Enter your email"
                        value="{{ request()->query('email') }}" required />
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Enter your new password"
                        required />
                </div>

                <!-- Password Confirmation -->
                <div class="form-group">
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Confirm your new password" required />
                </div>

                <div class="btn_block">
                    <button type="submit" class="btn puprple_btn ml-0">
                        Reset Password
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
@endsection