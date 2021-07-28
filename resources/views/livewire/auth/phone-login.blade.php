<div>
  @if(!$showEmailForm)
    <form wire:submit.prevent="sendCode" style="display: flex; flex-direction: column; margin-bottom: 20px">
      <label for="phone">
        Номер телефона
      </label>
      <input id="phone" wire:model="phoneNumber">
      @error('phoneNumber')
      <div>
        {{ $message }}
      </div>
      @enderror



      @if($this->nextTryingSecondsInterval > 0)
        <div wire:poll.visible.1000ms>
          Еще раз код можно запросить через {{ $this->nextTryingSecondsInterval }}
        </div>

      @else
        <button type="submit">
          Запросить код
        </button>
      @endif
    </form>

    @if($showCodeForm)
      <form wire:submit.prevent="verifyCode" style="display: flex; flex-direction: column; margin-bottom: 20px">
        <label for="code">
          Проверочный код
        </label>
        <input type="text" id="code" wire:model="code">
        @error('code')
        <div>
          {{ $message }}
        </div>
        @enderror

        <button type="submit">
          Проверить
        </button>
      </form>
    @endif
  @endif

  @if(session()->has('criticalError'))
    <div style="margin: 20px">
      {{ session('criticalError') }}
    </div>
  @endif

  @if($showEmailForm)
    <form wire:submit.prevent="registerEmailAndLogin"
          style="display: flex; flex-direction: column; margin-bottom: 20px">
      <label for="email">
        Email
      </label>
      <input type="email" id="email" wire:model="email">
      @error('email')
      {{ $message }}
      @enderror

      <button type="submit">
        Продолжить
      </button>
    </form>
  @endif
</div>