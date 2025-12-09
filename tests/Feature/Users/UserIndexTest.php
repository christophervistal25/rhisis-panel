<?php

use App\Models\User;
use function Pest\Laravel\actingAs;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->authUser = User::factory()->create();
});

// Feature Test Checklist for Users Page

// 1. Authenticated users can access the users page
it('ensure that /users page loads successfully for an authenticated user', function () {
    actingAs($this->authUser)
        ->get(route('users.index'))
        ->assertStatus(200);
});
// 2. Guests are redirected to login
it('redirect the guest user to login when accessing authenticated routes', function () {
    $this->get(route('users.index'))
            ->assertStatus(302);
});
// 3. Correct Inertia component is rendered
it('ensure that rendering the correct inertia component for the users page', function () {
  actingAs($this->authUser)
    ->get(route('users.index'))
    ->assertInertia(function (Assert $page) {
        $page->component('admin/users/Index');
    });
});
// 4. 'users' prop exists in Inertia response
it('ensure that the component has render the correct props (users)', function () {
  actingAs($this->authUser)
    ->get(route('users.index'))
    ->assertInertia(function (Assert $page) {
        $page->component('admin/users/Index')
        ->has('users');
    });
});
// 5. All users (including newly created) are returned in props
it('ensure all the users including newly created are returned in props', function () {
    $users = User::factory(3)->create();

    actingAs($this->authUser)
        ->get(route('users.index'))
        ->assertInertia(function (Assert $page) use($users) {
            $page->has('users', 4)
                ->where('users', function ($userProps) use($users) {
                    $userIds = collect($userProps)->pluck('id')->sort()->values()->all();

                    $expectedIds = collect([$this->authUser, ...$users])
                                    ->pluck('id')
                                    ->sort()
                                    ->values()
                                    ->all();

                    return $userIds === $expectedIds;
                });
        });
});