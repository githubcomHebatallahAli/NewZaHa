<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        <div class="flex items-center justify-end mt-4 align-middle ">

            <a href="{{ route('auth.google') }}">

                <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" style="margin-left: 3em;">

            </a>

        </div>
    </form>
</x-guest-layout>
<script>
    import Echo from 'laravel-echo';

    window.Echo.channel('new-comment')
        .listen('NewCommentEvent', (event) => {
            // Handle the event, e.g., update the UI
            console.log('New comment:', event.comment);
            // Update UI as needed
        });
</script>

<script>Echo.private(`App.User.${userId}`)
.notification((notification) => {
console.log(notification);
// Handle the notification event
});
</script>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>

@php
$userId = auth()->check() ? auth()->user()->id:1;
@endphp

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

var pusher = new Pusher('PUSHER_APP_KEY', {
cluster: 'yourcluster'
});

var channel = pusher.subscribe('my-channel');
channel.bind("Illuminate\\Notifications\\Events\\BroadcastNotificationCreated", function(data) {

if(data.user_id == {{$userId}}){

alert(`Hi ${data.comment}`) //here you can add you own logic
}
});
</script>
