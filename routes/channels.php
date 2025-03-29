<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', fn ($user, $id) => (int) $user->id === (int) $id);

Broadcast::channel('chat.{userId}', fn (User $user, $userId) => (int) $user->id === (int) $userId);
