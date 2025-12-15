<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .sidebar-transition {
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside 
            :class="sidebarOpen ? 'w-64' : 'w-20'" 
            class="sidebar-transition bg-gray-800 text-white flex flex-col">
            
            <!-- Logo/Brand -->
            <div class="flex items-center justify-between p-4 border-b border-gray-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                    <span class="text-xl font-bold" x-show="sidebarOpen">Elevate Admin</span>
                    <span class="text-xl font-bold" x-show="!sidebarOpen">EA</span>
                </a>
            </div>

            <!-- Menu Items -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-home w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="sidebar-transition">Dashboard</span>
                        </a>
                    </li>

                    <!-- Properties -->
                    <li>
                        <a href="{{ route('admin.properties.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.properties.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-building w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="sidebar-transition">Properties</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sliders.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.sliders.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-images w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="sidebar-transition">Sliders</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.team.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.team.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-users w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="sidebar-transition">Team</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.newsletters.index') }}" 
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.newsletters.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-envelope w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="sidebar-transition">Nieuwsbrief</span>
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->canManageUsers())
                        <li>
                            <a href="{{ route('admin.users.index') }}" 
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-user-shield w-5 text-center"></i>
                                <span x-show="sidebarOpen" class="sidebar-transition">Users</span>
                            </a>
                        </li>
                    @endif
                    <!-- Menus -->
                    <li>
                        <a href="{{ route('admin.menus.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.menus.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-bars w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="sidebar-transition">Menus</span>
                        </a>
                    </li>

                    <!-- Settings Dropdown -->
                    <li x-data="{ settingsOpen: {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }">
                        <button @click="settingsOpen = !settingsOpen"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700' : '' }}">
                            <i class="fas fa-cog w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="sidebar-transition flex-1 text-left">Settings</span>
                            <i x-show="sidebarOpen" :class="settingsOpen ? 'fas fa-chevron-down' : 'fas fa-chevron-right'" class="text-xs sidebar-transition"></i>
                        </button>
                        
                        <ul x-show="settingsOpen && sidebarOpen" 
                            x-transition
                            class="mt-2 space-y-1 pl-8">
                            <li>
                                <a href="{{ route('admin.settings.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.index') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-sliders w-4 text-center"></i>
                                    <span>Algemeen</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.category-order') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.category-order') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-sort w-4 text-center"></i>
                                    <span>Categorie Volgorde</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.currencies.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.currencies.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-dollar-sign w-4 text-center"></i>
                                    <span>Currencies</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.districts.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.districts.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-map-marker-alt w-4 text-center"></i>
                                    <span>Districts</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.omgevingen.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.omgevingen.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-location-dot w-4 text-center"></i>
                                    <span>Omgevingen</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.object-types.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.object-types.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-building w-4 text-center"></i>
                                    <span>Object Types</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.object-subtypes.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.object-subtypes.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-building-circle-check w-4 text-center"></i>
                                    <span>Object Subtypes</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.pand-types.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.pand-types.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-building-circle-check w-4 text-center"></i>
                                    <span>Pand Types</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings.team-titletypes.index') }}" 
                                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-700 text-sm {{ request()->routeIs('admin.settings.team-titletypes.*') ? 'bg-gray-700' : '' }}">
                                    <i class="fas fa-user-tag w-4 text-center"></i>
                                    <span>Team Titles</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Divider -->
                    <li class="my-3 border-t border-gray-700"></li>

                </ul>
            </nav>

            <!-- Toggle Button -->
            <div class="border-t border-gray-700 p-4">
                <button 
                    @click="sidebarOpen = !sidebarOpen"
                    class="w-full flex items-center justify-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-700 transition-colors">
                    <i :class="sidebarOpen ? 'fas fa-angle-left' : 'fas fa-angle-right'" class="w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="sidebar-transition">Collapse</span>
                </button>
            </div>

            <!-- User Profile -->
            <div class="border-t border-gray-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-sm font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    </div>
                    <div x-show="sidebarOpen" class="flex-1 sidebar-transition">
                        <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-white">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Bar -->
            <header class="bg-white shadow-sm">
                <div class="px-6 py-4">
                    @if(isset($header))
                        {{ $header }}
                    @endif
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>