    <div class="bell">
      @if(auth()->user()->badge_count > 0)
      <a href="{{ route('notification.read') }}" class="bell">
        @if(!$isAndroid)
        <img src="{{ asset('image/bell.png') }}" alt="">
        @else
        <img src="{{ asset('image/bell.png') }}" alt="">
        @endif
        
          <span class="badge">
            @if(!$isAndroid)
              {{ auth()->user()->badge_count }}
            @endif
          </span>
        
      </a>
      @endif
    </div>