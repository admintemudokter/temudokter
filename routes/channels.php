<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('doctor.{id}', function ($user, $id) {
    // The user will be authenticated via 'doctor' guard
    return (int) $user->id === (int) $id;
}, ['guards' => ['doctor']]);
