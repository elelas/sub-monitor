<?php


namespace App\Services\AuthService;


use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

interface IAuthService
{
    /**
     * @param string $email
     * @param string $phoneNumber
     * @return User
     * @throws ValidationException
     */
    public function registerAndLoginWithPhoneAndEmail(string $email, string $phoneNumber): User;

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws ValidationException
     */
    public function loginByEmail(string $email, string $password): User;

    public function loginWithUser(User $user): User;

    public function loginBySocialiteUser(SocialiteUser $socialiteUser, string $providerName): User;

    public function registerBySocialiteUser(SocialiteUser $socialiteUser, string $provider): User;

    public function isUserRegistered(string $email): bool;

    public function isSocialiteUserLinked(SocialiteUser $socialiteUser, User $innerUser, string $provider): bool;

    public function linkSocialiteUserToUser(SocialiteUser $socialiteUser, User $innerUser, string $provider): void;
}