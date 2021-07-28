<div>
  <form wire:submit.prevent="login" style="display: flex;flex-direction: column">
    <label for="email">
      Email
    </label>
    <input type="email" id="email" wire:model="email">
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

    <div style="margin: 10px 0">
      <a href="">
        Забыли пароль?
      </a>
    </div>

    <button type="submit">
      Войти
    </button>
  </form>
</div>
