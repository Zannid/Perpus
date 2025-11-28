<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('private-user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin-notifikasi', function ($user) {
    return in_array($user->role, ['admin', 'petugas']);
});
