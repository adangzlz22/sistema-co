@forelse($notifications as $item)

{{--   asset('/images/avatars/'. array_key_exists('img', $item->data))--}}


    <a href="{{ $item->data['route'] ?? '#'  }}" class="dropdown-notification-item">
        <div class="dropdown-notification-icon">
            <img style="width: 2.5rem;" class="rounded-circle me-2" src="{{  $item->data['img'] ?? ''  }}">
        </div>
        <div class="dropdown-notification-info">
            <div class="title"> {!! $item->data['title'] ?? '' !!}</div>
            <div class="time">{{ $item->created_at->diffForHumans()  }}</div>
        </div>
        <div class="dropdown-notification-arrow">
            <i class="fa fa-chevron-right"></i>
        </div>
    </a>
@empty
    <div class="empty-title-notification">
        <img class="me-2" src="{{ asset('/images/notification/empty_notification.png') }}">
        <strong>No tienes notificaciones aún.</strong>
        <p>Manténgase al tanto, las notificaciones sobre tus actividades se mostrarán aquí.</p>
    </div>
@endforelse

<div id="more_data_loading">
</div>


