<div>
  @if($successMessage)
    <div style="margin: 10px 0">
      {{ $successMessage }}
    </div>
  @endif
  <form style="display: flex;flex-direction: column" wire:submit.prevent="sendResetLink">
    <label for="email">
      Email
    </label>
    <input type="email" id="email" wire:model="email">
    @error('email')
    <div>
      {{ $message }}
    </div>
    @enderror

    <button type="submit">
      Отправить
    </button>
  </form>
</div>