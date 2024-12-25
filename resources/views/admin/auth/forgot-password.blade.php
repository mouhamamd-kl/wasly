@extends('admin.auth.master')
@section('title','Forget Password')
@section('content')
<!-- Forgot Password Form -->
<div class="form_block">
    <div class="form_side">
        <div class="section_title">
            <span class="title_badge">Forgot Password</span>
            <h2>
                <span>Reset</span> your password
            </h2>
            <p>
                Enter your email address to reset your password. We will send you a link to create a
                new one.
            </p>
        </div>
        <x-auth-session-status class="mb-4" :status="session('status')" />
        <form method="POST" action="{{ route('admin.password.email') }}">
            @csrf
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required  :value="old('email')"/>
            </div>
            <div class="btn_block">
                <button type="submit" class="btn puprple_btn ml-0">
                    Send Reset Link
                </button>
                <div class="btn_bottom"></div>
            </div>
        </form>
        <a href="{{ route('admin.login') }}">Sign In ?</a>
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