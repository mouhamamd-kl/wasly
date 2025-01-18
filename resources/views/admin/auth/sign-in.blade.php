@extends('admin.auth.master')
@section('title','Sign In')
@section('content')
@if ($errors->has('session'))
    <div class="alert alert-danger">
        {{ $errors->first('session') }}
    </div>
@endif
<div class="form_block">
    <div class="form_side">
        <div class="section_title">
            <span class="title_badge">Welcome Back</span>
            <h2>
                <span>Sign in</span> to your account
            </h2>
            <p>
                Quickly access your products and features.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <!-- Email Field -->
            <div class="form-group">
                <input 
                    type="email" 
                    name="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    placeholder="Email" 
                    value="{{ old('email') }}" 
                    required 
                />
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- Password Field -->
            <div class="form-group">
                <input 
                    type="password" 
                    name="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    placeholder="Password" 
                    required 
                />
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!-- Remember Me and Forgot Password -->
            <div class="forgate_check">
                <div class="coustome_checkbox">
                    <label for="remamber_check">
                        <input 
                            type="checkbox" 
                            id="remamber_check" 
                            name="remember" 
                            {{ old('remember') ? 'checked' : '' }} 
                        />
                        <span class="checkmark"></span>
                        Remember for 30 days
                    </label>
                </div>
                <a href="{{ route('admin.password.request') }}">Forgot password?</a>
            </div>
            <!-- Submit Button -->
            <div class="btn_block">
                <button type="submit" class="btn puprple_btn ml-0">
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
            <img src="{{ asset('assets-front') }}/images/sign_in_screen1.png" alt="image" />
        </div>
    </div>
</div>

@endsection