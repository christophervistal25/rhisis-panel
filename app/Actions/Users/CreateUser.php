<?php

namespace App\Actions\Users;

use App\Models\User;

final class CreateUser
{
  /**
   * @return User
   */
  public function create(array $data = []) :User
  {
      return User::create([
          'username' => $data['username'],
          'email' => $data['email'],
          'password' => $data['password'],
      ]);
  }
}
