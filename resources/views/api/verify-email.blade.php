<x-guest-layout>
    <div class="max-w-md mx-auto p-6 bg-white shadow-md rounded-lg mt-12">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">
            {{ __('Email Verification Required') }}
        </h2>
        
        <p class="text-sm text-gray-600 mb-6">
            {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking on the link we sent to your email. Didn\'t receive the email? We can send another verification link to your email address.') }}
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <form method="POST" action="{{ route('merchant.verification.send') }}">
                @csrf

                <x-primary-button class="w-full sm:w-auto">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('merchant.logout') }}" class="mt-4 sm:mt-0 sm:ml-4">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
