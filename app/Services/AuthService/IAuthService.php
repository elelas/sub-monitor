<?php


namespace App\Services\AuthService;


use App\Models\User;
use Illuminate\Validation\ValidationException;

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
}