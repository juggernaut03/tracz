<?php

use Leantime\Core\Events\EventDispatcher;
use Leantime\Domain\Auth\Listeners\ShowPersonalTokenContent;
use Leantime\Domain\Auth\Listeners\ShowPersonalTokenTab;

// Register Personal Access Tokens tab in user account settings
EventDispatcher::add_event_listener(
    'leantime.domain.users.templates.editOwn.tabs',
    ShowPersonalTokenTab::class
);

EventDispatcher::add_event_listener(
    'leantime.domain.users.templates.editOwn.tabsContent',
    ShowPersonalTokenContent::class
);

EventDispatcher::add_filter_listener('leantime.domain.auth.template.userInvite.welcomeText', function ($content, $params) {
    $language = app()->make(\Leantime\Core\Language::class);

    return $language->__('text.welcome_to_leantime_content');
});

EventDispatcher::add_filter_listener('leantime.domain.auth.template.userInvite2.welcomeText', function ($content, $params) {
    $language = app()->make(\Leantime\Core\Language::class);

    return $language->__('text.welcome_to_leantime_content');
});

EventDispatcher::add_filter_listener('leantime.domain.auth.template.userInvite3.welcomeText', function ($content, $params) {
    $language = app()->make(\Leantime\Core\Language::class);

    return $language->__('text.welcome_to_leantime_content');
});

EventDispatcher::add_filter_listener('leantime.domain.auth.template.userInvite4.welcomeText', function ($content, $params) {
    $language = app()->make(\Leantime\Core\Language::class);

    return $language->__('text.welcome_to_leantime_content');
});

EventDispatcher::add_filter_listener('leantime.domain.auth.template.userInvite5.welcomeText', function ($content, $params) {
    $language = app()->make(\Leantime\Core\Language::class);

    return $language->__('text.welcome_to_leantime_content');
});
