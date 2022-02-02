@php
    $menus = Different\DifferentCore\app\Utils\Sidebar\SidebarController::menus();   
@endphp

@isset($menus)
    @foreach ($menus as $menu)
        {{ $menu->render() }}
    @endforeach
@endisset
