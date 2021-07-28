<?php


namespace App\Repositories\UserRepository;


use App\Models\User;

interface IUserRepository
{
    public function findByPhone(string $phone): ?User;

    public function save(User $user): User;
}