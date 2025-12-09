<?php

use App\Actions\Users\CreateUser;
use App\Actions\Users\GetAllUsers;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Table users exists in the schema (DONE)
it('can check if the users table is being exists in the database', function () {
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $tableNames = array_column($tables, 'name');

    expect($tableNames)->toContain('users');
});


// Table has exactly 11 columns (no more, no less) (DONE)
function keys_are_exact(array $array1, array $array2): bool
{
    return !array_diff_key($array1, $array2) && !array_diff_key($array2, $array1);
}


it('can check if number of columns in the users are exactly 11', function () {
    $expectedKeys = Schema::getColumnListing('users');
    $actualKeys = ['id', 'username', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];

    expect(keys_are_exact($expectedKeys, $actualKeys))->toBeTrue();
});

// Column id exists and is of type bigIncrements / bigint unsigned and primary key (DONE) 
it('can check if the id is bigIncrements and bigUnsignedint and primary key', function () {
    $user = User::factory()->create();
    $columnInfo = DB::select("PRAGMA table_info(users)");

    foreach ($columnInfo as $column) {
        if ($column->name === 'id') {
            $columnIdDetails = $column;
        }
    }

    expect($user->getKeyType())->toBe('int');
    expect($user->getKeyName())->toBe('id');
    expect($columnIdDetails->type)->toBe('INTEGER');
    expect($columnIdDetails->pk)->toBe(1);
});

// Column username exists, is string/varchar (DONE)
it('can check if the username column is exists and the datatype of it is string or varchar', function () {
    $columns = Schema::getColumnListing('users');
    $columnInfo = DB::select("PRAGMA table_info(users)");

    foreach($columnInfo as $column) {
        if($column->name === 'username') {
            $columnUsernameDetails = $column;
        }
    }


    expect(in_array('username', $columns))->toBeTrue();
    expect($columnUsernameDetails->type)->toBe('varchar');
});
// Column email exists, is string/varchar, length 255, and is unique
it('can check if the email column is exists and the datatype of it is string or varchar with length 255 and unique', function () {
    $columns = Schema::getColumnListing('users');
    $columnInfo = DB::select("PRAGMA table_info(users)");
    $indexes = DB::select("PRAGMA index_list('users')");

    foreach($columnInfo as $column) {
        if($column->name === 'email') {
            $columnEmailDetails = $column;
        }
    }

    $isUnique = false;
    foreach($indexes as $index) {
        if($index->name === 'users_email_unique') {
            $isUnique = true;
        }
    }

    expect(in_array('email', $columns))->toBeTrue();
    expect($columnEmailDetails->type)->toBe('varchar');
    expect($columnEmailDetails->dflt_value)->toBeNull();
    expect($columnEmailDetails->notnull)->toBe(1);
    expect($isUnique)->toBeTrue();
});
// Column email_verified_at exists and is nullable datetime
it('can check if the email_verified_at column is exists and the datatype of it is datetime and nullable', function () {
    $columns = Schema::getColumnListing('users');
    $columnInfo = DB::select("PRAGMA table_info(users)");

    foreach($columnInfo as $column) {
        if($column->name === 'email_verified_at') {
            $columnEmailVerifiedAtDetails = $column;
        }
    }

    expect(in_array('email_verified_at', $columns))->toBeTrue();
    expect($columnEmailVerifiedAtDetails->type)->toBe('datetime');
    expect($columnEmailVerifiedAtDetails->notnull)->toBe(0);
});
// Column password exists and is string
it('can check if the password column is exists and the datatype of it is string or varchar', function () {
    $columns = Schema::getColumnListing('users');
    $columnInfo = DB::select("PRAGMA table_info(users)");

    foreach($columnInfo as $column) {
        if($column->name === 'password') {
            $columnPasswordDetails = $column;
        }
    }

    expect(in_array('password', $columns))->toBeTrue();
    expect($columnPasswordDetails->type)->toBe('varchar');
});
// Column remember_token exists, is string, length 100, and is nullable
it('can check if the remember_token column is exists and the datatype of it is string or varchar with length 100 and nullable', function () {
    $columns = Schema::getColumnListing('users');
    $columnInfo = DB::select("PRAGMA table_info(users)");

    foreach($columnInfo as $column) {
        if($column->name === 'remember_token') {
            $columnRememberTokenDetails = $column;
        }
    }

    expect(in_array('remember_token', $columns))->toBeTrue();
    expect($columnRememberTokenDetails->type)->toBe('varchar');
    expect($columnRememberTokenDetails->notnull)->toBe(0);
});
// Columns created_at and updated_at exist and are timestamps (nullable by default)
it('can check if the created_at and updated_at columns are exists and the datatype of it is timestamp and nullable', function () {
    $columns = Schema::getColumnListing('users');
    $columnInfo = DB::select("PRAGMA table_info(users)");

    foreach($columnInfo as $column) {
        if($column->name === 'created_at') {
            $columnCreatedAtDetails = $column;
        }
        if($column->name === 'updated_at') {
            $columnUpdatedAtDetails = $column;
        }
    }

    expect(in_array('created_at', $columns))->toBeTrue();
    expect($columnCreatedAtDetails->type)->toBe('datetime');
    expect($columnCreatedAtDetails->notnull)->toBe(0);

    expect(in_array('updated_at', $columns))->toBeTrue();
    expect($columnUpdatedAtDetails->type)->toBe('datetime');
    expect($columnUpdatedAtDetails->notnull)->toBe(0);
});
// Index users_username_unique exists on username column
it('can check if the users_username_unique index exists on username column', function () {
    $indexes = DB::select("PRAGMA index_list('users')");

    $isUnique = false;
    foreach($indexes as $index) {
        if($index->name === 'users_username_unique') {
            $isUnique = true;
        }
    }

    expect($isUnique)->toBeTrue();
});
// Index users_email_unique exists on email column
it('can check if the users_email_unique index exists on email column', function () {
    $indexes = DB::select("PRAGMA index_list('users')");

    $isUnique = false;
    foreach($indexes as $index) {
        if($index->name === 'users_email_unique') {
            $isUnique = true;
        }
    }

    expect($isUnique)->toBeTrue();
});
// No duplicate column names
it('can check if there are no duplicate column names in users table', function () {
    $columns = Schema::getColumnListing('users');
    $uniqueColumns = array_unique($columns);

    expect(count($columns))->toBe(count($uniqueColumns));
});
// No unexpected/extra columns exist
it('can check if there are no unexpected or extra columns in users table', function () {
    $expectedKeys = Schema::getColumnListing('users');
    $actualKeys = ['id', 'username', 'email', 'email_verified_at', 'password', 'remember_token', 'created_at', 'updated_at', 'two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at'];
    expect(keys_are_exact($expectedKeys, $actualKeys))->toBeTrue();
});
// Table uses default Laravel timestamps behavior (created_at & updated_at nullable)
it('can check if the users table uses default Laravel timestamps behavior with created_at and updated_at being nullable', function () {
    $columnInfo = DB::select("PRAGMA table_info(users)");

    foreach($columnInfo as $column) {
        if($column->name === 'created_at') {
            $columnCreatedAtDetails = $column;
        }
        if($column->name === 'updated_at') {
            $columnUpdatedAtDetails = $column;
        }
    }

    expect($columnCreatedAtDetails->notnull)->toBe(0);
    expect($columnUpdatedAtDetails->notnull)->toBe(0);
});



// Get All the users order by created_at descending
it('can get all users ordered by created_at descending', function () {
    User::factory()->count(5)->create();
    $action = app(GetAllUsers::class);
    $users = $action->get();

    expect($users->count())->toBe(5);
    expect($users->first()->created_at)->toBeGreaterThanOrEqual($users->last()->created_at);
});

// Ensure that usernam column is unique when creating multiple users
it('can ensure that username column is unique when creating multiple users', function () {
    $users = User::factory(5)->create();
    $usernames = $users->pluck('username')->toArray();
    $uniqueUsernames = array_unique($usernames);
    $originalUsernames = $usernames;

    expect(count($originalUsernames))->toBe(count($uniqueUsernames));
});

// Ensure that email column is unique when creating multiple users
it('can ensure that email columns is unique when creating multiple users', function () {
    $users = User::factory(5)->create();
    $emails = $users->pluck('email')->toArray();
    $uniqueEmails = array_unique($emails);
    $originalEmails = $emails;

    expect(count($originalEmails))->toBe(count($uniqueEmails));
});

// Ensure that password is hashed when creating a user
it('can ensure that all passswords stored in the password columnn is not plain-text', function () {
    $action = app(CreateUser::class);
    $user = $action->create([
        'username' => 'tooshort01',
        'email' => 'test@example.com',
        'password' => 'my-plain-text-password'
    ]);

    expect($user->password)->not->toBe('my-plain-text-password');
});
