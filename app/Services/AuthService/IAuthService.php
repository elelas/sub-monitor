<?php


namespace App\Services\AuthService;


use App\Models\User;

interface IAuthService
{
    public function registerAndLoginWithPhoneAndEmail(string $email, string $phoneNumber): User;
}