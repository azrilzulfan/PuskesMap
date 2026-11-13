<?php

use App\Models\User;

test('guest can view login page and authenticates according to role', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin-login@puskesmap.local',
        'password' => 'secret1234',
    ]);
    $dinkes = User::factory()->pemerintah()->create([
        'email' => 'dinkes-login@puskesmap.local',
        'password' => 'secret1234',
    ]);

    $this->get(route('login'))->assertOk();

    $this->post(route('login.attempt'), [
        'email' => $admin->email,
        'password' => 'secret1234',
    ])->assertRedirect(route('admin.puskesmas.index'));

    $this->assertAuthenticatedAs($admin);

    $this->post(route('logout'))->assertRedirect(route('home'));
    $this->assertGuest();

    $this->post(route('login.attempt'), [
        'email' => $dinkes->email,
        'password' => 'secret1234',
    ])->assertRedirect(route('analitik.index'));

    $this->assertAuthenticatedAs($dinkes);
});

test('inactive account cannot authenticate even with valid password', function () {
    User::factory()->pemerintah()->inactive()->create([
        'email' => 'nonaktif@puskesmap.local',
        'password' => 'secret1234',
    ]);

    $response = $this->from(route('login'))->post(route('login.attempt'), [
        'email' => 'nonaktif@puskesmap.local',
        'password' => 'secret1234',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('login endpoint throttles repeated failed attempts per minute', function () {
    User::factory()->admin()->create([
        'email' => 'throttle-check@puskesmap.local',
        'password' => 'secret1234',
    ]);

    for ($attempt = 0; $attempt < 5; $attempt++) {
        $this->post(route('login.attempt'), [
            'email' => 'throttle-check@puskesmap.local',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');
    }

    $this->post(route('login.attempt'), [
        'email' => 'throttle-check@puskesmap.local',
        'password' => 'wrong-password',
    ])->assertTooManyRequests();
});

test('protected web and api routes enforce authentication and role authorization', function () {
    $dinkes = User::factory()->pemerintah()->create();
    $admin = User::factory()->admin()->create();

    $this->get(route('analitik.index'))->assertRedirect(route('login'));
    $this->get(route('admin.puskesmas.index'))->assertRedirect(route('login'));
    $this->get(route('api.v1.analisis.coverage'))->assertUnauthorized();

    $this->actingAs($dinkes)->get(route('analitik.index'))->assertOk();
    $this->actingAs($dinkes)->get(route('api.v1.analisis.equity'))->assertOk();
    $this->actingAs($dinkes)->get(route('admin.puskesmas.index'))->assertForbidden();
    $this->actingAs($dinkes)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($dinkes)->get(route('admin.audit-log.index'))->assertForbidden();

    $this->actingAs($admin)->get(route('admin.puskesmas.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.users.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.audit-log.index'))->assertOk();
    $this->actingAs($admin)->get(route('analitik.index'))->assertOk();
});
