@extends('layouts.app')

@section('title', 'Create account | Campus IT Help Desk')

@section('content')
<section class="container narrow-page">
    <div class="form-card">
        <p class="eyebrow">New user</p>
        <h1>Create an account</h1>
        <p class="muted">Register to submit and track your support requests.</p>
        <form method="POST" action="{{ route('register.store') }}" class="stack-form">
            @csrf
            <label><span>Full name</span><input type="text" name="name" value="{{ old('name') }}" maxlength="100" autocomplete="name" required autofocus></label>
            <label><span>Email address</span><input type="email" name="email" value="{{ old('email') }}" maxlength="150" autocomplete="email" required></label>
            <label><span>Password</span><input type="password" name="password" minlength="8" autocomplete="new-password" required><small>Use at least eight characters.</small></label>
            <label><span>Confirm password</span><input type="password" name="password_confirmation" minlength="8" autocomplete="new-password" required></label>
            <button class="button" type="submit">Create account</button>
        </form>
        <p class="form-foot">Already registered? <a href="{{ route('login') }}">Sign in</a>.</p>
    </div>
</section>
@endsection
