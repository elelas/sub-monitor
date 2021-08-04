<?php


namespace App\Services\AuthService;


use App\Models\User;
use App\Repositories\UserRepository\IUserRepository;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

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
        $result = Auth::attempt(['email' => $email, 'password' => $password]);

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

    public function loginBySocialiteUser(SocialiteUser $socialiteUser, string $providerName): User
    {
        if ($this->isUserRegistered($socialiteUser->getEmail())) {
            $innerUser = $this->userRepository->findByEmail($socialiteUser->getEmail());

            if (!$this->isSocialiteUserLinked($socialiteUser, $innerUser, $providerName)) {
                $this->linkSocialiteUserToUser($socialiteUser, $innerUser, $providerName);
            }
        } else {
            $innerUser = $this->registerBySocialiteUser($socialiteUser, $providerName);
        }

        Auth::login($innerUser);

        return $innerUser;
    }

    public function registerBySocialiteUser(SocialiteUser $socialiteUser, string $provider): User
    {
        $innerUser = new User([
            'email' => $socialiteUser->getEmail(),
            'name' => $socialiteUser->getName(),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10)
        ]);

        $innerUser = $this->userRepository->save($innerUser);

        $this->linkSocialiteUserToUser($socialiteUser, $innerUser, $provider);

        return $innerUser;
    }

    public function isUserRegistered(string $email): bool
    {
        return User::whereEmail($email)->exists();
    }

    public function isSocialiteUserLinked(SocialiteUser $socialiteUser, User $innerUser, string $provider): bool
    {
        return DB::table('socialite_users')
            ->where('socialite_user_id', $socialiteUser->getId())
            ->where('user_id', $innerUser->id)
            ->where('provider_name', $provider)
            ->exists();
    }

    public function linkSocialiteUserToUser(SocialiteUser $socialiteUser, User $innerUser, string $provider): void
    {
        DB::table('socialite_users')->insert([
            'socialite_user_id' => $socialiteUser->getId(),
            'provider_name' => $provider,
            'user_id' => $innerUser->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}