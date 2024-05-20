    <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <meta name="user-id" content="{{ Auth::id() }}">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('home') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('HOME') }}
                        </x-nav-link>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                    <!-- Notifications Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ms-6 relative">
                        <!-- Notification Bell Icon -->
                        <button id="notification-button" class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
                            </svg>
                            <!-- Notification Badge -->
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1 py-0.5 rounded-full bg-red-500 text-xs text-white nav-notification-badge">0</span>
                        </button>

                        <style>
                            #notification-dropdown {
                                max-height: 256px;
                                overflow-y: auto;
                                background-color: #1F2937;
                                /* Cinza azulado escuro */
                                color: white;
                                /* Letras brancas */
                            }

                            .notification-item {
                                cursor: pointer;
                                padding: 8px;
                                border-bottom: 1px solid #2d3748;
                            }

                            .notification-item:hover {
                                background-color: #374151;
                                /* Cor de fundo ao passar o mouse */
                            }
                        </style>

                        <div id="notification-dropdown" style="margin-top: 17rem;" class="hidden absolute right-0 mt-2 w-64 border border-gray-300 rounded-md shadow-lg">
                            <ul class="py-2 px-3" id="notification-list">
                                <!-- Notifications will be appended here -->

                            </ul>
                        </div>


                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('HOME') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
        <meta name="user-id" content="{{ Auth::id() }}">

        <!-- JavaScript to handle notification badge and dropdown -->

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://js.pusher.com/beams/1.0/push-notifications-cdn.js"></script>
        <script src="https://js.pusher.com/8.0.1/pusher.min.js"></script>
        <script>
            const beamsClient = new PusherPushNotifications.Client({
                instanceId: '13f8b8a9-3cad-4ec4-b5f1-54841d7d2ba2',
            });

            beamsClient.start()
                .then(() => beamsClient.addDeviceInterest('notifications'))
                .then(() => console.log('Successfully registered and subscribed!'))
                .catch(console.error);



            const userId = $('meta[name="user-id"]').attr('content');

            $(document).ready(function() {

                // Fetch unread notification count
                $.get('/notifications/unread-count', {
                    user_id: userId
                }, function(response) {
                    if (response.unread_count > 0) {
                        $('.nav-notification-badge').text(response.unread_count).show();
                    } else {
                        $('.nav-notification-badge').hide();
                    }
                });

               

                // Toggle notification dropdown visibility
                $('#notification-button').click(function() {
                    $('#notification-dropdown').toggleClass('hidden');
                    if (!$('#notification-dropdown').hasClass('hidden')) {
                        fetchNotifications(); // Fetch notifications when dropdown is shown
                    }
                });

                // Hide notification dropdown when clicking outside
                $(document).click(function(event) {
                    if (!$(event.target).closest('#notification-button').length && !$(event.target).closest('#notification-dropdown').length) {
                        $('#notification-dropdown').addClass('hidden');
                    }
                });

                function updateNotifications(data) {
                    // Atualize a contagem de notificações
                    let count = parseInt($('.nav-notification-badge').text()) || 0;
                    $('.nav-notification-badge').text(++count).show();

                    // Adicione a nova notificação à lista
                    $('#notification-list').append('<li class="py-1 border-b border-gray-200">' + data.message + '</li>');
                }

                beamsClient.bind("user-notification", function(data) {
                    updateNotifications(data);
                });
            });



            function markAsRead(notificationId, element) {
                fetch('/mark-as-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            notification_id: notificationId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the notification element from the list
                            element.remove();
                        } else {
                            console.error('Error:', data.error);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }




            function fetchNotifications() {
                console.log('fetchNotifications chamada');
                $.get('/notifications', function(response) {
                    $('#notification-list').html('');
                    response.notifications.forEach(function(notification) {
                        $('#notification-list').append(`
                <li class="notification-item py-1 border-b border-gray-200" data-id="${notification.id}">
                    ${notification.message}
                    <button class="mark-as-read text-blue-500 hover:underline" data-id="${notification.id}">Marcar como lida</button>
                </li>
            `);
                    });

                    // Adicione um evento de clique aos botões "Marcar como lida"
                    $('.mark-as-read').click(function() {
                        const notificationId = $(this).data('id');
                        markAsRead(notificationId, $(this).parent());
                    });
                });
            }
        </script>
    </nav>
