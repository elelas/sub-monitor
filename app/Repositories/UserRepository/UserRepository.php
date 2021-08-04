<?php


namespace App\Repositories\UserRepository;


use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Socialite\Contracts\User as SocialiteUser;

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

    public function findBySocialiteUser(SocialiteUser $socialiteUser): ?User
    {
        return User::join('socialite_users', 'users.id', '=', 'socialite_users.user_id')
            ->where('users.email', $socialiteUser->getEmail())
            ->first();
    }

    public function findBy(array $conditions): Collection
    {
        $query = User::query();

        foreach ($conditions as $field => $condition) {
            $query->where($field, $condition);
        }

        return $query->get();
    }

    public function findOneBy(array $conditions): ?User
    {
        $query = User::query();

        foreach ($conditions as $field => $condition) {
            $query->where($field, $condition);
        }

        return $query->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::whereEmail($email)->first();
    }
}