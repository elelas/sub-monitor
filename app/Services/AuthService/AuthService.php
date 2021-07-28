<?php


namespace App\Services\AuthService;


use App\Models\User;
use App\Repositories\UserRepository\IUserRepository;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

    public function loginByEmail(string $email, string $password): User
    {
        $result = Auth::attempt(['email' => $email, 'password' => 'password']);

        if ($result) {
            return Auth::user();
        }

        throw ValidationException::withMessages([
            'email' => 'Некорректный email или пароль',
        ]);
    }

    public function loginWithUser(User $user): User
    {
        Auth::login($user);

        return $user;
    }
}