@php
    use App\Models\Utility;
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = Utility::languages();
    // $profile = asset(Storage::url('uploads/avatar/'));
    $profile = \App\Models\Utility::get_file('uploads/avatar');

    $mode_setting = \App\Models\Utility::mode_layout();

    $initial = strtoupper(substr($users->name, 0, 2));

@endphp
<header
    class="dash-header  {{ isset($mode_setting['is_sidebar_transperent']) && $mode_setting['is_sidebar_transperent'] == 'on' ? 'transprent-bg' : '' }}">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">
                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="theme-avtar">
                            <!-- <img alt="#"
                                src="{{ !empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png' }}"
                                class="logo logo-lg" style="width: 100%; border-radius: 50%;"> -->
                                @if($users->avatar && $users->avatar != 'avatar.png' && $users->avatar != "")
                                <img src="{{ !empty($users->avatar) ? $profile . '/' . $users->avatar : $profile . '/avatar.png' }}" alt="{{ env('APP_NAME') }}" class="logo logo-lg" style="width: 100%; border-radius: 50%;" />
                                @else
                                <span class="logo logo-lg" style="width: 100%; border-radius: 50%; background-color: #f1f2f7; color: #000; display: flex; justify-content: center; align-items: center;">{{ $initial }}</span>
                                @endif
                        </span>
                        <span class="hide-mob ms-2"> {{ __('Hi, ') }}{{ Auth::user()->name }}!
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('My Profile') }}</span>
                        </a>

                        <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf</form>
                    </div>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">

                {{-- unread & unseen message --}}
                @php
                    $unseenCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
                        ->where('seen', 0)
                        ->count();
                @endphp

                <li class="dropdown dash-h-item drp-notification">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false" id="notification-btn">
                        <i class="ti ti-bell"></i>
                        <span class="bg-danger dash-h-badge message-counter custom_messanger_counter">{{ $notifications_count }}
                            <span class="sr-only"></span>
                        </span>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" id="notification-list">
                        <div class="noti-header">
                            <h5 class="m-0">{{ __('Notifications') }}</h5>
                            <a href="{{route('notifications.clear')}}"
                            class="dash-head-link mark_all_as_read_message">{{ __('Clear All') }}</a>
                        </div>
                        <div class="notification-body dropdown-list-notifications">
                            <div>
                                <a href="#" class="show-listView"></a>
                                @foreach($notifications as $notification)
                                <div class="mb-1 d-flex">
                                <i class="fas fa-arrow-right ms-4 mt-1"></i><p style="font-size:12px;" class="ms-2">{{$notification->title}}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="noti-footer">
                            <div class="d-grid">
                                <a href="{{route('notifications.seen')}}"
                                class="btn dash-head-link justify-content-center text-primary mx-0">Mark Seen</a>
                            </div>
                        </div>
                    </div>
                </li>

                @if (Auth::user()->type != 'super admin')
                    <li class="dash-h-item">
                        <a class="dash-head-link me-0" href="{{ url('/chats') }}">
                            <i class="ti ti-message-circle"></i>
                            <span
                                class="bg-danger dash-h-badge message-counter custom_messanger_counter">{{ $unseenCounter }}<span
                                    class="sr-only"></span>
                        </a>
                    </li>
                @endif

                @if (\Auth::user()->type != 'super admin')
                    <li class="dropdown dash-h-item drp-notification">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0 " data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false" id="msg-btn">
                            <i class="ti ti-message-2"></i>
                            <span
                                class="bg-danger dash-h-badge message-counter custom_messanger_counter">{{ $unseenCounter }}
                                <span class="sr-only"></span>
                            </span>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                            <div class="noti-header">
                                <h5 class="m-0">{{ __('Messages') }}</h5>
                                <a href="#"
                                    class="dash-head-link mark_all_as_read_message">{{ __('Clear All') }}</a>
                            </div>

                            <div class="noti-body dropdown-list-message-msg">
                                <div style="display: flex;">
                                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                                    {{-- unread messages --}}
                                    <div class="count-listOfContacts">
                                    </div>
                                </div>
                            </div>
                            <div class="noti-footer">
                                <div class="d-grid">
                                    <a href="{{ route('chats') }}"
                                        class="btn dash-head-link justify-content-center text-primary mx-0">View all</a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
                @php
                    // $currantLang = basename(\App::getLocale());
                    // $languages = \App\Models\Utility::languages();
                    // $lang = isset($users->lang) ? $users->lang : 'en';
                    // if ($lang == null) {
                    //     $lang = 'en';
                    // }
                    // $LangName = \App\Models\Languages::where('code', $lang)->first();

                    $lang = isset($users->lang) ? $users->lang : 'en';
                    if ($lang == null) {
                        $lang = 'en';
                    }
                    $LangName = \App\Models\Languages::where('code', $lang)->first();
                    if (empty($LangName)) {
                        $LangName = new App\Models\Utility();
                        $LangName->fullName = 'English';
                    }
                @endphp

                @if(count(Auth::user()->roles) > 1)
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                            <i class="ti ti-users nocolor"></i>
                            <span class="drp-text hide-mob">Assigned Roles</span>
                            <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                            @foreach(Auth::user()->roles as $role)
                                <a href="{{ route('change.role', $role) }}"
                                    class="dropdown-item {{ Auth::user()->type == $role->name ? 'text-primary' : '' }}">
                                    <span>{{ ucFirst($role->name) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </li>
                @endif

                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ Str::ucfirst($LangName->fullName) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" aria-labelledby="dropdownLanguage">
                        {{-- @foreach (App\Models\Utility::languages() as $lang)
                            <a href="{{ route('change.language', $lang) }}"
                                class="dropdown-item {{ basename(App::getLocale()) == $lang ? 'text-danger' : '' }}">{{ Str::upper($lang) }}</a>
                        @endforeach --}}
                        @foreach (App\Models\Utility::languages() as $code => $lang)
                            <a href="{{ route('change.language', $code) }}"
                                class="dropdown-item {{ $currantLang == $code ? 'text-primary' : '' }}">
                                <span>{{ ucFirst($lang) }}</span>
                            </a>
                        @endforeach
                        @can('Create Language')
                            @if (\Auth::user()->type == 'company')
                                <div class="dropdown-divider m-0"></div>
                                <a href="#" class="dropdown-item text-primary" data-size="md"
                                    data-url="{{ route('create.language') }}" data-ajax-popup="true"
                                    data-title="{{ __('Create New Language') }}"
                                    data-bs-toggle="tooltip">{{ __('Create Language') }}</a>
                                <div class="dropdown-divider m-0"></div>
                                <a href="{{ route('manage.language', [basename(App::getLocale())]) }}"
                                    class="dropdown-item text-primary">{{ __('Manage Language') }}</a>
                            @endif
                        @endcan
                        {{-- <div class="dropdown-divider m-0"></div>
                        @can('Create Language')
                            <a class="dropdown-item text-primary"
                                href="{{ route('manage.language', [$currantLang]) }}">{{ __('Manage Language') }}</a>
                        @endcan --}}
                    </div>
                </li>

            </ul>
        </div>
    </div>
</header>

@push('scripts')
    {{-- @include('Chatify::layouts.modals') --}}
    <script>
        // console.log($('#msg-btn'));
        $('#msg-btn').click(function() {
            let contactsPage = 1;
            let contactsLoading = false;
            let noMoreContacts = false;
            $.ajax({
                url: url + "/getContacts",
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    page: contactsPage,
                    type: 'custom',
                },
                dataType: "JSON",
                success: (data) => {

                    if (contactsPage < 2) {
                        $(".count-listOfContacts").html(data.contacts);

                    } else {
                        $(".count-listOfContacts").append(data.contacts);
                    }
                    $('.count-listOfContacts').find('.messenger-list-item').each(function(e) {
                        $('.noti-body .activeStatus').remove()
                        $('.noti-body .avatar').remove()
                        $(this).find('span').remove()
                        $(this).find('p').addClass("d-inline")
                        $(this).find('b').css({
                            "position": "absolute",
                            "right": "50px"
                        });
                        $(this).find('tr').remove('td')

                    })
                },
                error: (error) => {
                    setContactsLoading(false);
                    console.error(error);
                },
            });
        })
    </script>

    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     var dropdown = document.getElementById('notification-btn');
        //     dropdown.addEventListener('show.bs.dropdown', function () {
        //         getNotifications();
        //     });
        // });

        // function getNotifications() {
        //     $.ajax({
        //         url: '/notifications', // Replace with your actual notifications endpoint
        //         method: 'GET',
        //         success: function(response) {
        //             const data = response.notifications; // Ensure this matches the structure of your response
        //             console.log(data); // Debug: Log the data to verify it's correct

        //             const notificationList = $('#notification-list');
        //             const notificationBody = notificationList.find('.notification-body .dropdown-list-notifications');
                    
        //             // Clear existing notifications
        //             notificationBody.empty();

        //             // Check if there are any notifications
        //             if (data.length === 0) {
        //                 notificationBody.html('<div class="count-listOfNotifications pb-3"><small style="position: absolute; left: 30%">Your notification list is empty</small></div>');
        //             } else {
        //                 // Append new notifications
        //                 data.forEach(notification => {
        //                     const notificationItem = $(`
        //                         <div class="notification-item">
        //                             <a href="${notification.url}" class="dropdown-item">
        //                                 <strong>${notification.title}</strong>
        //                                 <p>${notification.body}</p>
        //                                 <small>${notification.created_at}</small>
        //                             </a>
        //                         </div>
        //                     `);
        //                     console.log('notificationItem:', notificationItem); // Debug: Log the item to verify it's being created
        //                     notificationBody.append(notificationItem);
        //                 });
        //             }
        //         },
        //         error: function(error) {
        //             console.error('Error fetching notifications:', error);
        //         }
        //     });
        // }

    </script>
@endpush
