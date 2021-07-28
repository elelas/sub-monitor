<?php


namespace App\Repositories\UserRepository;


use App\Models\User;

class UserRepository implements IUserRepository
{
    public function findByPhone(string $phone): ?User
    {
        return User::wherePhone(utils()->formatPhoneNumber($phone))->first();
    }

    public function save(User $user): User
    {
        $user->save();

        return $user;
    }
}