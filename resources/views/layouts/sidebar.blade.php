@php
    $segment1 = Request::segment(1);
    $segment2 = Request::segment(2);
    $segment3 = Request::segment(3);
    $segment4 = Request::segment(4);
@endphp
<!--begin::Aside Menu-->
<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

    <!--begin::Menu Container-->
    <div id="kt_aside_menu" class="aside-menu my-4 " data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">

        <!--begin::Menu Nav-->
        <ul class="menu-nav ">
            @foreach ($list_menu as $menu)
                @if (isset($menu['children']))
                    @can($menu['permission'])
                    <li class="menu-item  menu-item-submenu @if($segment2 == $menu['key']){{'menu-item-here menu-item-open'}}@endif" aria-haspopup="true" data-menu-toggle="hover">
                        <a href="javascript:;" class="menu-link menu-toggle">
                            {!!$menu['icon']!!}
                            <span class="menu-text">{{$menu['title']}}</span>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="menu-submenu">
                            <i class="menu-arrow"></i>
                            <ul class="menu-subnav">
                                @foreach ($menu['children'] as $children)
                                    @if (isset($children['children']))
                                        @can($children['permission'])
                                        <li class="menu-item  menu-item-submenu @if($segment3 == $children['key']){{'menu-item-here menu-item-open'}}@endif" aria-haspopup="true" data-menu-toggle="hover">
                                            <a href="javascript:;" class="menu-link menu-toggle">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">{{$children['title']}}</span>
                                                <i class="menu-arrow"></i>
                                            </a>
                                            <div class="menu-submenu">
                                                <i class="menu-arrow"></i>
                                                <ul class="menu-subnav">
                                                    @foreach ($children['children'] as $item)
                                                        @can($item['permission'])
                                                        <li class="menu-item  @if($segment4 == $item['key']){{'menu-item-active'}}@endif" aria-haspopup="true">
                                                            <a href="{{$item['url']}}" class="menu-link ">
                                                                <i class="menu-bullet menu-bullet-dot">
                                                                    <span></span>
                                                                </i>
                                                                <span class="menu-text">{{$item['title']}}</span>
                                                            </a>
                                                        </li>
                                                        @endcan
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                        @endcan
                                    @else
                                        @can($children['permission'])
                                        <li class="menu-item @if($segment3 == $children['key']){{'menu-item-active'}}@endif" aria-haspopup="true">
                                            <a href="{{$children['url']}}" class="menu-link ">
                                                <i class="menu-bullet menu-bullet-dot">
                                                    <span></span>
                                                </i>
                                                <span class="menu-text">{{$children['title']}}</span>
                                            </a>
                                        </li>
                                        @endcan
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @endcan
                @else
                    @can($menu['permission'])
                    <li class="menu-item @if($segment2 == $menu['key']){{'menu-item-here menu-item-open'}}@endif" aria-haspopup="true">
                        <a href="{{$menu['url']}}" class="menu-link ">
                            {!!$menu['icon']!!}
                            <span class="menu-text">{{$menu['title']}}</span>
                        </a>
                    </li>
                    @endcan
                @endif
            @endforeach
        </ul>
        <!--end::Menu Nav-->
    </div>
    <!--end::Menu Container-->
</div>
<!--end::Aside Menu-->
