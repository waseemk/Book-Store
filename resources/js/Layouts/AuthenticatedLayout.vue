<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Home
                        </NavLink>
                    </li>
                    <li class="nav-item">
                        <NavLink :href="route('books')" :active="route().current('books')">
                            Books
                        </NavLink>
                    </li>
                    <li class="nav-item">
                        <NavLink :href="route('authors')" :active="route().current('authors')">
                            Authors
                        </NavLink>
                    </li>
                    <li class="nav-item">
                        <NavLink :href="route('genres')" :active="route().current('genres')">
                            Genres
                        </NavLink>
                    </li>
                    <li class="nav-item">
                        <NavLink :href="route('publishers')" :active="route().current('publishers')">
                            Publishers
                        </NavLink>
                    </li>
                    
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $page.props.auth.user.name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
            
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <slot name="header" />
        </div>

        <div class="row">
            <slot />
        </div>
    </div>
    
</template>
