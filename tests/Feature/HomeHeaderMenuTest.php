<?php

use App\Models\User;

it('shows admin login and register links for guests in home header', function (): void {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('>Admin<', false);
    $response->assertSee('>Login<', false);
    $response->assertSee('>REGISTER<', false);
});

it('shows dashboard and logout links for authenticated users in home header', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertOk();
    $response->assertSee('/admin');
    $response->assertDontSee('>Login<', false);
    $response->assertDontSee('>REGISTER<', false);
});
