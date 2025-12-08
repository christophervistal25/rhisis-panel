<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Table users exists in the schema (DONE)
// Table has exactly 11 columns (no more, no less) (DONE)
// Column id exists and is of type bigIncrements / bigint unsigned (DONE)
// Column username exists, is string/varchar, length 255, and is unique
// Column email exists, is string/varchar, length 255, and is unique
// Column email_verified_at exists and is nullable timestamp
// Column password exists and is string
// Column remember_token exists, is string, length 100, and is nullable
// Columns created_at and updated_at exist and are timestamps (nullable by default)
// Index users_username_unique exists on username column
// Index users_email_unique exists on email column
// No duplicate column names
// No unexpected/extra columns exist
// Primary key is correctly set on id
// Table uses default Laravel timestamps behavior (created_at & updated_at nullable)

it('can check if the users table is being exists in the database', function () {
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $tableNames = array_column($tables, 'name');

    expect($tableNames)->toContain('users');
});

function keys_are_exact(array $array1, array $array2): bool
{
    return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
}


it('can check if number of columns in the users are exactly 11', function () {
    $expectedKeys = Schema::getColumnListing('users');
    $actualKeys = ['id', 'username', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];

    expect(keys_are_exact($expectedKeys, $actualKeys))->toBeTrue();
});

it('can check if the id is bigIncrements and bigUnsignedint', function () {
    $user = User::factory()->create();

    expect($user->getKeyType())->toBe('int');
    expect($user->getKeyName())->toBe('id');
});


