<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('two factor challenge redirects to login when not authenticated', function () {
    $this->markTestSkipped('Two-factor authentication is disabled in User model.');
});

test('two factor challenge can be rendered', function () {
    $this->markTestSkipped('Two-factor authentication is disabled in User model.');
});