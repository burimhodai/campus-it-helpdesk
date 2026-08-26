@extends('layouts.app')

@section('title', 'Sign in | Campus IT Help Desk')

@section('content')
<section class="container narrow-page">
    <div class="form-card">
        <p class="eyebrow">Account access</p>
        <h1>Sign in</h1>
        <p class="muted">Use your registered email address and password.</p>
        <form method="POST" action="{{ route('login.store') }}" class="stack-form">
            @csrf
            <label><span>Email address</span><input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus></label>
            <label><span>Password</span><input type="password" name="password" autocomplete="current-password" required></label>
            <label class="checkbox-row"><input type="checkbox" name="remember" value="1"><span>Keep me signed in</span></label>
            <button class="button" type="submit">Sign in</button>
        </form>
        <p class="form-foot">No account yet? <a href="{{ route('register') }}">Create one</a>.</p>
    </div>
</section>
@endsection
