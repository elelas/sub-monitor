<div>
  <form wire:submit.prevent="resetPassword" style="display: flex;flex-direction: column">
    <label for="email">
      Email
    </label>
    <input type="email" id="email" wire:model="email" readonly>
    @error('email')
    <div>
      {{ $message }}
    </div>
    @enderror
    <label for="password">
      Пароль
    </label>
    <input type="password" id="password" wire:model="password">
    @error('password')
    <div>
      {{ $message }}
    </div>
    @enderror
    <label for="password_confirmation">
      Подтверждение пароля
    </label>
    <input type="password" id="password_confirmation" wire:model="password_confirmation">
    @error('password_confirmation')
    <div>
      {{ $message }}
    </div>
    @enderror

    <button type="submit">
      Сбросить пароль
    </button>
  </form>
</div>
