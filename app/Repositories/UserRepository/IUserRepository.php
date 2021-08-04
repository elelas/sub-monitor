<?php


namespace App\Repositories\UserRepository;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Socialite\Contracts\User as SocialiteUser;

interface IUserRepository
{
    public function findByPhone(string $phone): ?User;

    public function save(User $user): User;

    public function findBySocialiteUser(SocialiteUser $socialiteUser): ?User;

    public function findBy(array $conditions): Collection;

    public function findOneBy(array $conditions): ?User;

    public function findByEmail(string $email): ?User;
}