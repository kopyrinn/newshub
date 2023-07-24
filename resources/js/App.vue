<template>
    <div class="app-page flex-column flex-column-fluid" data-kt-app-header-fixed="true" data-kt-app-sidebar-hoverable="true" :data-kt-app-layout="isDark? 'dark-sidebar': 'light-sidebar'">
        <!--begin::Header-->
        <div class="app-header">
            <!--begin::Header container-->
            <div class="app-container container-fluid d-flex flex-stack">
                <!--begin::Sidebar toggle-->
                <div class="d-flex align-items-center d-block d-lg-none">
                    <button type="button" @click="toggleSidebar" class="btn btn-icon btn-active-color-primary w-35px h-35px me-2">
		                <i class="ki-duotone ki-abstract-14 fs-1"><i class="path1"></i><i class="path2"></i></i>
                    </button>
                    <!--begin::Logo image-->
                    <!-- <app-link to="/" class="text-reset">
                        <inline-svg src="/img/logo.svg" class="h-30px"/>
                    </app-link> -->
                    <!--end::Logo image-->
                </div>
                <!--end::Sidebar toggle-->
                <!--begin::Header wrapper-->
                <div class="d-flex justify-content-end justify-lg-content-between align-items-center w-100">
                    <search v-if="!isLap" class="w-100 mw-350px"/>

                    <div class="d-flex justify-content-end align-items-center flex-lg-row-fluid">
                        <div class="app-navbar-item align-items-stretch ms-1 ms-md-3">
                            <Popper v-if="token && user && user.email_verified_at" placement="bottom-end"  class="d-block">
                                <button class="btn btn-custom px-3 btn-color-gray-700 btn-active-light-primary btn-active-color-primary d-flex flex-center h-30px h-lg-40px">
                                    <i class="ki-duotone ki-plus-square fs-2"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>{{ $t('Create') }}
                                </button>

                                <template #content="{ close }">
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 mw-225px min-w-200px w-100 show">
                                        <div v-if="!user.is_journalist" class="menu-item px-5">
                                            <a href="" @click.prevent="post = null, openModal('post-editor'), close()" class="menu-link px-3">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone fs-2 ki-subtitle"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i></i>
                                                </span>
                                                <span class="menu-title">{{ $t('Post') }}</span>
                                            </a>
                                        </div>
                                        <div v-if="!user.is_journalist" class="menu-item px-5">
                                            <a href="" @click.prevent="post = null, postEditorEvent = true, openModal('post-editor'), close()" class="menu-link px-3">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone fs-2 ki-calendar-tick"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i><i class="path6"></i></i>
                                                </span>
                                                <span class="menu-title">{{ $t('Event') }}</span>
                                            </a>
                                        </div>
                                        <div class="menu-item px-5">
                                            <a href="" @click.prevent="vacancy = null, openModal('vacancy-editor'), close()" class="menu-link px-3">
                                                <span class="menu-icon">
                                                    <i class="ki-duotone ki-brifecase-timer fs-2"><i class="path1"></i><i class="path2"></i><i class="path3"></i></i>
                                                </span>
                                                <span class="menu-title">{{ $t('Vacancy') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </Popper>
                        </div>
                        <div v-if="isLap" class="app-navbar-item align-items-stretch ms-1 ms-md-3">
                            <search/>
                        </div>
                        <div v-if="user" class="app-navbar-item align-items-stretch ms-1 ms-md-3">
                            <Popper placement="bottom-end" class="d-block">
                                <button type="button" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" @click="getNotifications">
                                    <i class="ki-duotone ki-notification-status fs-2 fs-lg-1"><i class="path1" :class="{'text-danger': user.notifications_count}"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i></i>
                                </button>

                                <template #content="{ close }">
                                    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px w-lg-375px show">
                                        <div class="d-flex flex-column bgi-no-repeat rounded-top" :style="{backgroundImage: 'url(' + $media('patterns/menu-header-bg.jpg') + ')'}">
                                            <h3 class="text-white d-flex align-items-center fw-semibold px-9 mt-10 mb-9">
                                                {{ $t('Notifications') }} <span v-if="user.notifications_count" class="fs-8 badge badge-danger  opacity-75 ms-3">{{ user.notifications_count }}</span>
                                            </h3>

                                            <!-- <ul class="nav nav-line-tabs nav-line-tabs-2x nav-stretch fw-semibold px-9">
                                                <li class="nav-item">
                                                    <a @click.prevent="" class="nav-link text-white opacity-75 opacity-state-100 pb-4" href="">Обновления</a>
                                                </li>
                                            </ul> -->
                                        </div>

                                        <div class="tab-content">
                                            <div class="tab-pane fade active show">
                                                <!--begin::Items-->
                                                <div class="scroll-y mh-325px my-5 px-8">
                                                    <div v-for="notification in notifications" :key="notification.id" class="d-flex flex-stack py-4">
                                                        <div class="d-flex align-items-center">
                                                            <app-link :to="notification.url? notification.url: ''" class="mb-0 me-2" @click="close">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="fs-6 text-gray-800 text-hover-primary fw-bold me-3">{{ notification.title }}</span>
                                                                    <span class="fw-medium text-muted fs-8"><VDate :datetime="new Date(notification.created_at)"/></span>
                                                                </div>
                                                                <div class="text-gray-700 fs-7">{{ notification.message }}</div>
                                                            </app-link>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Items-->

                                                <div class="py-3 text-center border-top">
                                                    <app-link :to="{name: 'user-notifications', params: {slug: user.id}}" class="btn btn-color-gray-600 btn-active-color-primary" @click="close">{{ $t('View all') }} <i class="ki-duotone ki-arrow-right fs-5"><span class="path1"></span><span class="path2"></span></i>
                                                    </app-link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </Popper>
                        </div>
                        <div class="app-navbar-item align-items-stretch ms-1 ms-md-3">
                            <button @click="toggleDarkMode" type="button" class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px">
                                <i class="ki-duotone ki-night-day theme-light-show fs-2 fs-lg-1"><i class="path1"></i><i class="path2"></i><i class="path3"></i><i class="path4"></i><i class="path5"></i><i class="path6"></i><i class="path7"></i><i class="path8"></i><i class="path9"></i><i class="path10"></i></i>
                                <i class="ki-duotone ki-moon theme-dark-show fs-2 fs-lg-1"><i class="path1"></i><i class="path2"></i></i>
                            </button>
                        </div>
                        <div class="app-navbar-item align-items-stretch ms-1 ms-md-3">
                            <Popper v-if="user" placement="bottom-end" :hover="!$root.isMobile" class="d-block">
                                <app-link :to="$root.isMobile? '': {name: 'user', params: {slug: user.id}}" class="d-flex align-items-center">
                                    <div class="d-flex flex-center cursor-pointer symbol symbol-30px symbol-md-40px">
                                        <img :src="$url('/storage/' + user.avatar)" class="object-fit-cover rounded-3" />
                                    </div>
                                </app-link>

                                <template #content="{ close }">
                                    <!--begin::User account menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 mw-250px min-w-200px w-100 show">
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <div class="symbol symbol-75px me-5">
                                                    <img :src="$url('/storage/' + user.avatar)" class="object-fit-cover rounded-3" />
                                                </div>
                                                <div class="d-flex flex-column text-truncate">
                                                    <div class="fw-bold d-flex align-items-center fs-5 text-truncate">{{ user.name }}
                                                        <!-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> -->
                                                    </div>
                                                    <a v-if="user.telegram_username" :href="'https://t.me/' + user.telegram_username" class="fw-semibold text-muted text-hover-primary fs-7" target="_blank">@{{ user.telegram_username }}</a>
                                                    <a v-else-if="user.email" href="" @click.prevent="" class="fw-semibold text-muted text-hover-primary fs-7">{{ user.email }}</a>
                                                    <div>
                                                        <span class="badge mt-2 badge-primary fs-8 fw-bold">₸ {{ $decimal(user.balance) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5">
                                            <app-link @click="close" :to="{name: 'user', params: {slug: user.id}}" class="menu-link px-5">{{ $t('Profile') }}</app-link>
                                        </div>
                                        <div v-if="!user.is_journalist" class="menu-item px-5">
                                            <app-link @click="close" :to="{name: 'user-workspace', params: {slug: user.id}}" class="menu-link px-5">{{ $t('Workspace') }}</app-link>
                                        </div>
                                        <div class="menu-item px-5">
                                            <app-link @click="close" :to="{name: 'feed'}" class="menu-link px-5">{{ $t('My Feed') }}</app-link>
                                        </div>
                                        <div class="menu-item px-5">
                                            <app-link @click="close" :to="{name: 'user-actions', params: {slug: user.id}}" class="menu-link px-5">{{ $t('Actions') }}</app-link>
                                        </div>
                                        <div v-if="!user.is_journalist" class="menu-item px-5">
                                            <app-link @click="close" :to="{name: 'user-package', params: {slug: user.id}}" class="menu-link px-5">{{ $t('Package') }}</app-link>
                                        </div>
                                        <div class="menu-item px-5">
                                            <app-link @click="close" :to="{name: 'user-settings', params: {slug: user.id}}" class="menu-link px-5">{{ $t('Settings') }}</app-link>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5">
                                            <a href="" class="menu-link text-bolder text-danger px-5" @click.prevent="close(), logout()">{{ $t('Logout') }}</a>
                                        </div>
                                    </div>
                                </template>
                            </Popper>
                            <app-link v-else to="/login" class="btn btn-primary d-flex flex-center h-30px h-lg-40px">
                                {{ $t('Login') }}
                            </app-link>
                        </div>
                    </div>
                </div>
                <!--end::Header wrapper-->
            </div>
            <!--end::Header container-->
        </div>
        <!--end::Header-->

        <!--begin::Wrapper-->
        <div class="app-wrapper flex-column flex-row-fluid">
            <!--begin::Sidebar-->
            <OnClickOutside @trigger="maybeCloseSidebar" class="app-sidebar flex-column" :class="{'drawer drawer-start': isLap, 'drawer-on': sideOpen}">
                <!--begin::Header-->
                <div class="app-sidebar-header d-flex flex-column px-10 pt-8">
                    <!--begin::Brand-->
                    <div class="d-flex flex-stack mb-5">
                        <app-link to="/" class="fs-2">
                            <span class="bg-primary text-white fw-bolder me-1 p-1 rounded">NEWS</span><span class="fw-bolder text-primary">HUB.KZ</span>
                        </app-link>

                        <Popper placement="bottom-end" hover class="d-block">
                            <button type="button" class="btn btn-sm text-gray-700 fs-8 py-2 px-3 btn-active-light-secondary btn-active-color-primary d-flex align-items-center">
                                {{ languages[locale].name }}
                            </button>

                            <template #content="{ close }">
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-2 fs-7 mw-150px min-w-150px w-100 show">
                                    <div class="px-5 fw-bold text-gray-700 fs-6 py-2">{{ $t('System language') }}</div>
                                    <div v-for="(lang, key) in languages" class="menu-item py-0">
                                        <a href="" @click.prevent="setLocale(key)" class="menu-link rounded-0 px-5">
                                            <span class="menu-title">{{ lang.name }}</span>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </Popper>
                    </div>
                    <!--end::Brand-->
                </div>
                <!--end::Header-->
                <!--begin::Navs-->
                <div class="app-sidebar-navs flex-column-fluid">
                    <div class="hover-scroll-y h-100 pt-5">
                        <!--begin::Sidebar menu-->
                        <div class="menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary">
                            <div class="menu-item" :class="{'here': $route.name == 'index'}">
                                <app-link to="/" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-home-2 fs-2"><i class="path1 text-primary"></i><i class="path2 text-primary"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Home') }}</span>
                                </app-link>
                            </div>

                            <div v-if="token" class="menu-item" :class="{'here': $route.name == 'index'}">
                                <app-link to="/feed" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-flash-circle fs-2"><i class="path1 text-warning"></i><i class="path2 text-warning"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('My Feed') }}</span>
                                </app-link>
                            </div>

                            <div
                                v-for="category in config.categories"
                                class="menu-item"
                                :class="{'here': $route.name == 'category' && $route.params.slug == category.slug}"
                            >
                                <app-link :to="{name: 'category', params: {slug: category.slug}}" class="menu-link">
                                    <span class="menu-icon" v-html="category.icon"></span>
                                    <span class="menu-title">{{ category.name }}</span>
                                </app-link>
                            </div>

                            <!--begin:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'polls'}">
                                <!--begin:Menu link-->
                                <app-link to="/polls" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-award fs-2"><i class="path1 text-warning"></i><i class="path2 text-warning"></i><i class="path3 text-warning"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Polls') }}</span>
                                </app-link>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            
                            <!-- <div class="separator"></div> -->

                            <!--begin::Heading-->
                            <div class="menu-item mt-2">
                                <div class="menu-content menu-heading text-uppercase fs-7">{{ $t('Overview') }}</div>
                            </div>
                            <!--end::Heading-->
                            
                            
                            <!--begin:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'users'}">
                                <!--begin:Menu link-->
                                <app-link to="/users" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-security-user fs-2"><i class="path1 text-success"></i><i class="path2 text-success"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Press Center') }}</span>
                                </app-link>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'map'}">
                                <!--begin:Menu link-->
                                <app-link to="/map" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-map fs-2"><i class="path1 text-primary"></i><i class="path2 text-success"></i><i class="path3 text-danger"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Media Map') }}</span>
                                </app-link>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->

                            <!--begin:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'vacancies'}">
                                <!--begin:Menu link-->
                                <app-link to="/vacancies" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-brifecase-timer fs-2"><i class="path1 text-success"></i><i class="path2 text-success"></i><i class="path3 text-success"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Vacancies') }}</span>
                                </app-link>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->

                            
                            <!-- <div class="separator"></div> -->

                            <!--begin::Heading-->
                            <div class="menu-item mt-2">
                                <div class="menu-content menu-heading text-uppercase fs-7">{{ $t('Useful information') }}</div>
                            </div>
                            <!--end::Heading-->
                            
                            <!--begin:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'page' && $route.params.slug == 'about-project'}">
                                <!--begin:Menu link-->
                                <app-link to="/page/about-project" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-message-question fs-2"><i class="path1 text-success"></i><i class="path2 text-success"></i><i class="path3 text-success"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('About the project') }}</span>
                                </app-link>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'packages'}">
                                <app-link to="/packages" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-package fs-2"><i class="path1 text-success"></i><i class="path2 text-success"></i><i class="path3 text-success"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Packages') }}</span>
                                </app-link>
                            </div>
                            <!--begin:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'page' && $route.params.slug == 'ads'}">
                                <!--begin:Menu link-->
                                <app-link to="/page/ads" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-tag fs-2"><i class="path1 text-warning"></i><i class="path2 text-warning"></i><i class="path3 text-danger"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Advertising') }}</span>
                                </app-link>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            <!--begin:Menu item-->
                            <div class="menu-item" :class="{'here': $route.name == 'page' && $route.params.slug == 'contact'}">
                                <!--begin:Menu link-->
                                <app-link to="/page/contact" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-address-book"><i class="path1 text-primary"></i><i class="path2 text-primary"></i><i class="path3 text-primary"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Contacts') }}</span>
                                </app-link>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            <div v-if="token && user.is_admin" class="menu-item" :class="{'here': $route.name == 'journalists'}">
                                <app-link to="/journalists" class="menu-link">
                                    <span class="menu-icon">
                                        <i class="ki-duotone ki-shield-search fs-2"><i class="path1 text-primary"></i><i class="path2 text-primary"></i><i class="path3 text-primary"></i></i>
                                    </span>
                                    <span class="menu-title">{{ $t('Journalists') }}</span>
                                </app-link>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Navs-->
                <div class="app-sidebar-footer flex-column-auto py-5 px-6">
                    <div class="d-flex align-items-center justify-content-start fw-medium ps-6">
                        <div v-if="config.rates.USD" class="d-flex align-items-center me-4">
                            <img class="w-20px h-20px rounded-1 me-2" :src="icons.en" alt="">

                            <div class="d-flex flex-column align-items-start">
                                <span class="lh-1 text-gray-800">{{ $decimal(config.rates.USD.price) }}</span>
                                <span class="fs-8 fw-medium lh-sm" :class="{'text-muted': config.rates.USD.change == 0, 'text-danger': config.rates.USD.change < 0, 'text-success': config.rates.USD.change > 0}">
                                    {{ $decimal(Math.abs(config.rates.USD.change)) }}%
                                    <i v-if="config.rates.USD.change != 0" class="ki-duotone fs-9 ms-n1" :class="{'ki-down text-danger': config.rates.USD.change < 0, 'ki-up text-success': config.rates.USD.change > 0}"><i class="path1"></i><i class="path2"></i></i>
                                </span>
                            </div>
                        </div>
                        <div v-if="config.rates.EUR" class="d-flex align-items-center me-4">
                            <img class="w-20px h-20px rounded-1 me-2" :src="icons.eu" alt="">

                            <div class="d-flex flex-column align-items-start">
                                <span class="lh-1 text-gray-800">{{ $decimal(config.rates.EUR.price) }}</span>
                                <span class="fs-8 fw-medium lh-sm" :class="{'text-muted': config.rates.EUR.change == 0, 'text-danger': config.rates.EUR.change < 0, 'text-success': config.rates.EUR.change > 0}">
                                    {{ $decimal(Math.abs(config.rates.EUR.change)) }}%
                                    <i v-if="config.rates.EUR.change != 0" class="ki-duotone fs-9 ms-n1" :class="{'ki-down text-danger': config.rates.EUR.change < 0, 'ki-up text-success': config.rates.EUR.change > 0}"><i class="path1"></i><i class="path2"></i></i>
                                </span>
                            </div>
                        </div>
                        <div v-if="config.rates.RUB" class="d-flex align-items-center">
                            <img class="w-20px h-20px rounded-1 me-2" :src="icons.ru" alt="">

                            <div class="d-flex flex-column align-items-start">
                                <span class="lh-1 text-gray-800">{{ $decimal(config.rates.RUB.price) }}</span>
                                <span class="fs-8 fw-medium lh-sm" :class="{'text-muted': config.rates.RUB.change == 0, 'text-danger': config.rates.RUB.change < 0, 'text-success': config.rates.RUB.change > 0}">
                                    {{ $decimal(Math.abs(config.rates.RUB.change)) }}%
                                    <i v-if="config.rates.RUB.change != 0" class="ki-duotone fs-9 ms-n1" :class="{'ki-down text-danger': config.rates.RUB.change < 0, 'ki-up text-success': config.rates.RUB.change > 0}"><i class="path1"></i><i class="path2"></i></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </OnClickOutside>
            <!--end::Sidebar-->

            <!--begin::Main-->
            <div class="app-main flex-column flex-row-fluid position-relative">
                <div v-if="$route.meta.toolbar" class="app-toolbar d-flex pt-4 pt-lg-6 border-0">
                    <div class="app-container container-xxl d-flex flex-stack ">
                        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                            <h3 class="text-gray-900 fw-bolder m-0">{{ title }}</h3>
                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                <li class="breadcrumb-item text-muted"><app-link to="/" class="text-muted text-hover-primary">{{ $t('Home') }}</app-link></li>
                                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                                <li class="breadcrumb-item text-muted">{{ title }}</li>
                            </ul>
                        </div>
                        <div class="d-flex align-items-center gap-2 gap-lg-3">
                            <app-link v-if="token && $route.name == 'feed' && !user.is_journalist" :to="{name: 'user-workspace', params: {slug: user.id}}" class="btn btn-sm fw-bold btn-primary">{{ $t('Workspace') }}</app-link>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex flex-column flex-column-fluid">
                    <div v-if="$route.name != 'index'" class="app-content flex-column-fluid">
                        <div class="app-container container-xxl">
                            <div v-if="user && !user.email_verified_at" class="alert alert-dismissible bg-light-danger border-dashed border-danger d-flex flex-column flex-sm-row p-5 mb-10">
                                <i class="ki-duotone ki-notification-bing fs-2hx text-danger me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                <div class="d-flex flex-column pe-0 pe-sm-10">
                                    <h5 class="mb-1">{{ $t('Please verify your email') }}</h5>
                                    <span class="fw-semibold">{{ $t('To get the full functionality of the platform, you need to confirm your Email. If the letter was not received, check the spam directory or send the letter again by clicking on the "resend" button') }}</span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <button type="button" class="position-absolute position-sm-relative mt-3 me-3 mt-sm-0 me-sm-0 top-0 end-0 btn btn-sm btn-light-danger ms-sm-auto" @click="resendVerificationEmail" :disabled="resending">
                                        {{ $t('Resend') }}
                                    </button>
                                </div>
                            </div>

                            <router-view v-slot="{ Component }">
                                <transition name="fade" mode="out-in" appear>
                                    <component :is="Component" :key="$route.meta.animate? $route.path: ''"/>
                                </transition>
                            </router-view>
                            <!-- <router-view/> -->
                        </div>
                    </div>
                    <div v-else>
                        <router-view/>
                    </div>
                </div>
                <div class="app-footer">
                    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-end py-3">
                        <div class="text-dark">
                            <span class="text-muted fw-semibold ms-1">NewsHub.kz {{ (new Date).getFullYear() }} &copy;</span>
                        </div>
                    </div>
                </div>

                <!--begin::Cookie alert-->
                <!-- <div class="d-flex d-lg-block flex-column bg-light-primary bottom-0 text-center rounded-0 cookiealert py-5 position-absolute w-100" style="z-index: 105;">
                    Here's an example of a cookie alert pop up from the bottom of the screen.
                    Click the "Agree" button to accept the cookie.
                    <a href="#">Learn more</a>

                    <button type="button" class="btn btn-primary d-inline mx-auto ms-lg-5 acceptcookies mt-5 mt-lg-0">
                        I agree
                    </button>
                </div> -->
                <!--end::Cookie alert-->
            </div>
            <!--end:::Main-->
        </div>
        <!--end::Wrapper-->
    </div>
    <fullscreen v-model="fullscreen" class="d-flex align-items-center justify-content-center">
        <img v-if="fullscreenImage" :src="fullscreenImage" loading="lazy" class="mh-100 mw-100 cursor-zoom-out rounded-3" @click="fullscreen = false"/>
    </fullscreen>
    <Confirm ref="confirm"/>
    <div v-if="sideOpen" style="z-index: 105;" class="drawer-overlay"></div>
    <div v-show="confirmation" class="modal-backdrop show"></div>
    <div v-show="modal" class="modal-backdrop show"></div>
    <PostEditor v-if="modalType == 'post-editor'"/>
    <VacancyEditor v-if="modalType == 'vacancy-editor'"/>
</template>




<script>
import { defineComponent, defineAsyncComponent } from "vue"
import Popper from "vue3-popper"
import { OnClickOutside } from '@vueuse/components'
import { ElNotification } from "element-plus"
// import Swal from "sweetalert2/dist/sweetalert2.js"
import showErrors from "@/helpers/notify"
import Search from "@/components/Search.vue"
import Confirm from "@/components/Confirm.vue"
import window from 'global'
// import ruIcon from "@/media/flags/russia.svg"
// import kkIcon from "@/media/flags/kazakhstan.svg"
// import enIcon from "@/media/flags/united-states.svg"
// import euIcon from "@/media/flags/european-union.svg"

export default defineComponent({
    name: "app",
    components: {
        Popper,
        OnClickOutside,
        Search,
        Confirm,
        [!import.meta.env.VITE_SSR && 'PostEditor']: defineAsyncComponent(() =>
            import('@/components/Post/Editor.vue')
        ),
        [!import.meta.env.VITE_SSR && 'VacancyEditor']: defineAsyncComponent(() =>
            import('@/components/Vacancy/Editor.vue')
        ),
    },
    data() {
        return {
            icons: {
                eu: this.$media('flags/european-union.svg'),
                ru: this.$media('flags/russia.svg'),
                en: this.$media('flags/united-nations.svg'),
                kk: this.$media('flags/kazakhstan.svg'),
            },
            isMobile: window.innerWidth < 576,
            isTab: window.innerWidth < 768,
            isLap: window.innerWidth < 992,
            isLg: window.innerWidth < 1200,
            isXl: window.innerWidth < 1400,
            isXxl: window.innerWidth < 1800,
            isXxxl: window.innerWidth >= 1800,
            width: window.innerWidth,
            tabFocus: true,
            fullscreen: false,
            fullscreenImage: false,
            darkMode: false,
            modal: false,
            confirmation: false,
            modalType: '',
            upToTop: false,
            sideOpen: false,
            langMenu: false,
            languages: {
                ru: {
                    name: 'Русский',
                },
                kk: {
                    name: 'Қазақ Тілі',
                },
                en: {
                    name: 'English',
                },
            },
            post: '',
            postEditorEvent: false,
            vacancy: '',
            notifications: [],
        }
    },
    watch: {
        fullscreen(value) {
            if (!value) this.fullscreenImage = false
        },
        $route (to, from) {
            if (!this.modal) return

            this.modal = false
            this.modalType = ''
            if (!import.meta.env.SSR) {
                document.body.classList.remove('modal-open')
            }
        }
    },
    created() {
        this.$i18n.locale = this.locale

        this.$router.beforeEach((to, from, next) => {
            if (this.sideOpen) {
                this.sideOpen = false
            }

            next()
        })

        if (!import.meta.env.SSR) {
            // if (this.$app) {
            //     this.$app.addListener('backButton', data => {
            //         if (window.history && window.history.state && window.history.state.back) {
            //             this.$router.go(-1)
            //         } else {
            //             this.$app.exitApp()
            //         }
            //     })
            // }

            this.detectFocusOut()
            this.initUser()

            this.darkMode = this.isDark

            try {
                this.onResize()
                window.addEventListener('resize', this.onResize, { passive: true })
            } catch (e) {

            }

            if (import.meta.env.VITE_APP_ENV != 'production') {
                this.getConfig()
            } else {
                setInterval(this.getConfig, 60 * 1000)
            }
        }
    },
    async serverPrefetch() {
        await this.$api('config').then(({data}) => {
            this.$store.commit('setConfig', data)
        }).catch((e) => {})
    },
    computed: {
        menu() {
            return this.$route.name? this.$store.getters.getMenu: ''
        },
        locale() {
            return this.$locale()
        },
        config() {
            return this.$store.getters.getConfig
        },
        meta() {
            return this.$store.getters.getMeta
        },
        title() {
            return this.$store.getters.getTitle
        },
        theme() {
            return this.$store.getters.getTheme
        },
        feeds() {
            return this.$store.getters.getFeeds
        },
        isDark() {
            if (!import.meta.env.SSR) {
                if (this.theme == 'system' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return true
                }
            }

            return this.theme == 'dark'
        },
        token() {
            return this.$store.getters.getToken
        },
        user() {
            return this.$store.getters.getUser
        },
        referral() {
            return this.$store.getters.getRef
        },
    },
    beforeDestroy() {
        if (typeof window !== 'undefined') {
            window.removeEventListener('resize', this.onResize, { passive: true })
        }
    },
    methods: {
        toggleSidebar() {
            this.sideOpen = !this.sideOpen
        },
        maybeCloseSidebar() {
            if (this.sideOpen) {
                this.sideOpen = false
            }
        },
        getRandom() {
            return Math.random().toString(36).slice(2, 7)
        },
        confirm(opts) {
            this.confirmation = true
            return this.$refs.confirm.open(opts)
        },
        setLocale(locale) {
            if (!import.meta.env.SSR) {
                const url = new URL(window.location.href)

                let path = url.pathname

                for (let lang of ['en', 'kk']) {
                    if (path.startsWith(`/${lang}`)) {
                        path = path.slice(3)
                    }
                }

                if (['en', 'kk'].includes(locale)) {
                    path = `/${locale}${path}`
                }

                if (path && path != this.$route.fullPath) {
                    window.location.href = path
                }
            }
        },
        getConfig() {
            this.$api('config').then(({data}) => {
                this.$store.commit('setConfig', data)
            }).catch((e) => {})
        },
        initUser() {
            if (!this.token) return

            this.getUser()

            setInterval(() => {
                if (this.tabFocus) {
                    this.getUser()
                }
            }, 30 * 1000)

            setTimeout(this.getSubscriptions, 1 * 1000)
        },
        getNotifications() {
            this.$api('account/notifications', true)
            .then(({data}) => {
                if (!data.ok) return

                this.notifications = data.notifications.data
            })
        },
        async getUser() {
            return await this.$api('user', true)
                .then(({data}) => {
                    this.$store.commit('setUser', data.user)

                    // if (this.user && !this.user.org) {
                    //     Swal.fire({
                    //         title: 'Введите название вашей организации',
                    //         input: 'text',
                    //         customClass: {
                    //             input: 'form-control rounded-3 border-1 border-secondary',
                    //             confirmButton: 'btn btn-success rounded-3',
                    //         },
                    //         confirmButtonColor: '#50cd89',
                    //         inputPlaceholder: 'Название организации',
                    //         confirmButtonText: 'Сохранить',
                    //         showLoaderOnConfirm: true,
                    //         allowOutsideClick: false,
                    //         showCancelButton: false,
                    //         preConfirm: (org) => {
                    //             const {name, avaId, newsletter} = this.user;
                    //             const params = {name, avaId, newsletter, org}

                    //             return this.$api("account/settings", true, {
                    //                 method: 'POST',
                    //                 data: params
                    //             })
                    //             .then(({ data }) => {
                    //                 if (data.status) {
                    //                     this.$store.commit('setUser', data.user)
                    //                 }

                    //                 if (!this.user.org) {
                    //                     Swal.showValidationMessage(
                    //                         `Ошибка сохраненния`
                    //                     )

                    //                     return false
                    //                 }

                    //                 return data
                    //             })
                    //             .catch(({ response }) => {
                    //                 showErrors(response)
                    //             });
                    //         },
                    //         inputValidator: (value) => {
                    //             if (!value) {
                    //                 return 'Название организации обязательно для продолжения работы.'
                    //             }
                    //         }
                    //     }).then((result) => {
                    //         if (!result.value.status) {
                    //             Swal.showValidationMessage(
                    //                 `Ошибка сохраненния`
                    //             )
                    //         }
                    //     })
                    // }
                })
                .catch(({response}) => {
                    if (response && response.status === 401) {
                        this.logout()
                    }
                })
        },
        resendVerificationEmail() {
            this.$post('account/verify-resend', {}, true).then(({data}) => {
                ElNotification({
                    type: 'success',
                    title: this.$t('Notification'),
                    message: data.message,
                    duration: 2000,
                })
            }).catch((e) => {})
        },
        onResize() {
            if (!import.meta.env.SSR) {
                const doc = document.documentElement
                const height = window.innerHeight

                doc.style.setProperty('--doc-height', `${height}px`)
            }

            this.width = window.innerWidth
            this.isMobile = window.innerWidth < 576
            this.isTab = window.innerWidth < 768
            this.isLap = window.innerWidth < 992
            this.isLg = window.innerWidth < 1200
            this.isXl = window.innerWidth < 1400
            this.isXxl = window.innerWidth < 1800
            this.isXxxl = window.innerWidth >= 1800
        },
        handleScroll() {
            if (window.scrollY > 100) {
                this.upToTop = true
            } else {
                this.upToTop = false
            }
        },
        scrollToTop(behavior) {
            this.scrollTo(0, behavior)
            // window.scrollTo({ top: 0, behavior: "smooth" })
        },
        scrollTo(offset, behavior, callback) {
            const fixedOffset = offset.toFixed();
            const onScroll = () => {
                if (window.pageYOffset.toFixed() === fixedOffset) {
                    window.removeEventListener('scroll', onScroll)
                    if (callback) callback()
                }
            }

            if (!import.meta.env.SSR) {
                window.addEventListener('scroll', onScroll)
                onScroll()
                window.scrollTo({
                    top: offset,
                    behavior: behavior
                })
            }
        },
        setTheme(theme) {
            if (!import.meta.env.SSR) {
                this.$store.commit('setTheme', theme)

                if (theme === "system") {
                    theme = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }

                localStorage.setItem('data-bs-theme', theme);
                document.documentElement.setAttribute("data-bs-theme", theme)
            }
        },
        toggleDarkMode() {
            if (!import.meta.env.SSR) {
                let theme = this.theme

                if (this.theme === "system") {
                    theme = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }

                theme = theme == 'dark'? 'light': 'dark'

                this.$store.commit('setTheme', theme)
                localStorage.setItem('data-bs-theme', theme);
                document.documentElement.setAttribute("data-bs-theme", theme)
            }
        },
        logoutConfirm() {
            this.confirm({
                title: this.$t('Confirmation'),
                message: this.$t('Are you sure you want to log out?'),
                ok: this.$t('Yes'),
            }).then((value) => {
                if (value) {
                    this.logout()
                }
            })
        },
        getSubscriptions() {
            this.$api('account/subscriptions', true)
                .then(({data}) => {
                    this.$store.commit('setFeeds', data.feeds)
                })
                .catch(({response}) => {
                    if (response && response.status === 401) {
                        this.logout()
                    }
                })
        },
        follow(type, id) {
            if (type) {
                this.$store.commit('addFeed', id)
            } else {
                this.$store.commit('delFeed', id)
            }

            this.$api(`user/${id}/follow`, true, {
                method: 'post',
                data: {
                    type
                }
            }).then(({data}) => {
                if (data.ok) {
                    ElNotification({
                        type: 'success',
                        title: this.$t('Notification'),
                        message: data.message,
                        duration: 4000,
                    })
                }
            }).catch((e) => {
                
            })
        },
        logout() {
            if (this.$isApp) {
                this.$api('logout', true, {
                    method: 'post',
                    data: {
                        appToken: this.appToken
                    }
                }).then(({data}) => {
                    this.clearSession()
                }).catch((e) => {
                    this.clearSession()
                })
            } else {
                this.clearSession()
                this.$router.push('/')
            }
        },
        clearSession() {
            this.$store.commit('setUser', false)
            this.$store.commit('setToken', false)
        },
        copyText(text) {
            if (this.$clipboard) {
                const writeToClipboard = async () => {
                    await this.$clipboard.write({
                        string: text
                    });
                };

                writeToClipboard()
            } else {
                try {
                    const clipboardData =
                        event.clipboardData ||
                        window.clipboardData ||
                        event.originalEvent?.clipboardData ||
                        navigator.clipboard

                    clipboardData.writeText(text)
                } catch (e) {

                }
            }
        },
        openModal(type) {
            this.modal = true
            this.modalType = type
            if (!import.meta.env.SSR) {
                document.body.classList.add('modal-open')
            }
            // this.onResize()
        },
        closeModal(type, force = false) {
            if ((this.$root.confirmation && !force) || type !== this.modalType) return

            this.modal = false
            this.modalType = ''
            if (!import.meta.env.SSR) {
                document.body.classList.remove('modal-open')
            }
        },
        detectFocusOut() {
            let inView = false;

            const onWindowFocusChange = (e) => {
                if ({ focus: 1, pageshow: 1 }[e.type]) {
                    if (inView) return;
                    this.tabFocus = true;
                    inView = true;
                } else if (inView) {
                    this.tabFocus = !this.tabFocus;
                    inView = false;
                }
            };

            if (!import.meta.env.SSR) {
                window.addEventListener('focus', onWindowFocusChange)
                window.addEventListener('blur', onWindowFocusChange)
                window.addEventListener('pageshow', onWindowFocusChange)
                window.addEventListener('pagehide', onWindowFocusChange)
            }
        },
        shareWith(net, url, title, summary) {
            url = encodeURI(url)

            if (title) title = encodeURI(title)
            if (summary) summary = encodeURI(summary)

            if (net == 'fb') {
                return `https://www.facebook.com/sharer/sharer.php?u=${url}`
            } else if (net == 'vk') {
                return `http://vk.com/share.php?url=${url}`
            } else if (net == 'tg') {
                return `https://t.me/share/url?url=${url}`
            } else if (net == 'tw') {
                return `http://twitter.com/share?url=${url}`
            }
        },
    }
});
</script>