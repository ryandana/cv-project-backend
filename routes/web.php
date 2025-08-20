<?php

use Illuminate\Support\Facades\Route;
use App\Models\Blog;

Route::get('/blog/{slug}', function ($slug) {
    $blog = Blog::where('slug', $slug)->first();

    if (! $blog) {
        abort(404, 'Blog not found');
    }

    return view('blog.show', compact('blog'));
});

// === DEBUG ROUTES FOR LOGIN ISSUE ===

// 1. Check overall authentication status
Route::get('/debug-all', function () {
    $user = \App\Models\User::first();
    
    return response()->json([
        'user_exists' => $user ? true : false,
        'user_email' => $user ? $user->email : null,
        'auth_check' => auth()->check(),
        'auth_user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_started' => session()->isStarted(),
        'csrf_token' => csrf_token(),
        'config_session_driver' => config('session.driver'),
        'config_auth_guard' => config('auth.defaults.guard'),
        'filament_auth_guard' => auth('web')->check(),
        'can_access_panel' => $user ? $user->canAccessPanel(app(\Filament\Panel::class)) : false,
        'session_data' => session()->all(),
        'cookies' => request()->cookies->all(),
    ]);
})->middleware('web');

// 2. Manual login test
Route::get('/manual-login', function () {
    return '
    <form method="POST" action="/manual-login">
        ' . csrf_field() . '
        <input type="email" name="email" value="admin@test.com" placeholder="Email"><br><br>
        <input type="password" name="password" value="password" placeholder="Password"><br><br>
        <button type="submit">Login</button>
    </form>';
});

Route::post('/manual-login', function (\Illuminate\Http\Request $request) {
    $email = $request->input('email', 'admin@test.com');
    $password = $request->input('password', 'password');
    
    // Create user if doesn't exist
    $user = \App\Models\User::firstOrCreate(
        ['email' => $email],
        ['name' => 'Admin', 'password' => bcrypt($password)]
    );
    
    // Try authentication
    $attempt = auth()->attempt(['email' => $email, 'password' => $password]);
    
    \Log::info('Manual login attempt:', [
        'email' => $email,
        'user_exists' => $user ? true : false,
        'attempt_result' => $attempt,
        'auth_after' => auth()->check(),
        'session_id' => session()->getId(),
        'user_can_access_panel' => $user->canAccessPanel(app(\Filament\Panel::class)),
    ]);
    
    if ($attempt) {
        session()->regenerate();
        return redirect('/admin');
    }
    
    return back()->with('error', 'Login failed');
})->middleware('web');

// 3. Force login and redirect to admin
Route::get('/force-admin', function () {
    $user = \App\Models\User::first();
    if ($user) {
        auth()->login($user);
        session()->regenerate();
        session()->save();
        
        // Check if we can access admin after login
        return redirect('/admin');
    }
    return 'No user found - creating one now...<br><a href="/create-user">Create User</a>';
})->middleware('web');

// 4. Create a test user
Route::get('/create-user', function () {
    $user = \App\Models\User::firstOrCreate(
        ['email' => 'admin@test.com'],
        ['name' => 'Admin', 'password' => bcrypt('password')]
    );
    
    return response()->json([
        'user_created' => true,
        'email' => $user->email,
        'message' => 'User created! Now try <a href="/force-admin">Force Admin</a>'
    ]);
});

// 5. Test admin panel access
Route::get('/test-admin-access', function () {
    try {
        // Try to access the admin panel directly
        $panel = \Filament\Facades\Filament::getPanel('admin');
        
        return response()->json([
            'panel_exists' => $panel ? true : false,
            'panel_path' => $panel ? $panel->getPath() : null,
            'current_user_can_access' => auth()->check() ? auth()->user()->canAccessPanel($panel) : false,
            'auth_guard' => $panel ? $panel->getAuthGuard() : null,
            'auth_check' => auth()->check(),
            'user' => auth()->user(),
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
})->middleware('web');