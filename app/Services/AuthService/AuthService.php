<?php


namespace App\Services\AuthService;


use App\Models\User;
use App\Repositories\UserRepository\IUserRepository;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthService implements IAuthService
{
    public function __construct(private IUserRepository $userRepository)
    {
    }

    public function registerAndLoginWithPhoneAndEmail(string $email, string $phoneNumber): User
    {
        Validator::validate([
            'email' => $email,
            'phoneNumber' => $phoneNumber,
        ], [
            'email' => ['required', 'email', 'unique:users'],
            'phoneNumber' => ['required', new PhoneNumberRule()],
        ]);

        $user = new User([
            'email' => $email,
            'email_verified_at' => now(),
            'phone' => utils()->formatPhoneNumber($phoneNumber),
            'password' => Hash::make(Str::random()),
        ]);

        $user = $this->userRepository->save($user);

        auth()->login($user, true);

        return $user;
    }
}