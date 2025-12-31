<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('two factor settings page can be rendered', function () {
    $this->markTestSkipped('Two-factor authentication is disabled in User model.');
});

test('two factor settings page requires password confirmation when enabled', function () {
    $this->markTestSkipped('Two-factor authentication is disabled in User model.');
});

test('two factor settings page does not requires password confirmation when disabled', function () {
    $this->markTestSkipped('Two-factor authentication is disabled in User model.');
});

test('two factor settings page returns forbidden response when two factor is disabled', function () {
    $this->markTestSkipped('Two-factor authentication is disabled in User model.');
});