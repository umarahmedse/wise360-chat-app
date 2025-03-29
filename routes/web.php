<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $users = User::where('id', '!=', Auth::user()->id)->get();
    return view('dashboard', [
        'users' => $users
    ]);
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::get('/chat/{id}', function ( $id) {
        $user = User::find($id);
        return view('chat', [
            'id'=> $id,
            'name'=> $user->name,
            'sender_id'=> Auth::user()->id,
            'receiver_id'=> $id
        ]);
    })
        ->middleware(['auth', 'verified'])
        ->name('chat');




Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
