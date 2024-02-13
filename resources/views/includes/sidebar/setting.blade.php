@php
$settings = ['business', 'country', 'city', 'source', 'service', 'user', 'role', 'permission', 'airline', 'sector'];
@endphp
@can('application_setting_view')
    <li class="nav-item has-treeview @if (in_array(Request::segment(2), $settings)) menu-open @endif
@if (in_array(Request::segment(3), $settings)) menu-open @endif">
        <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs fa-xs"></i>
            <p>
                Application Settings
                <i class="fas fa-angle-left right"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item has-treeview @if (in_array(Request::segment(3), $settings)) menu-open @endif">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        {{ __('settings.user_management') }}
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @can('user_view')
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}"
                                class="nav-link @if (Request::segment(3) == 'user') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('settings.all_users') }}</p>
                            </a>
                        </li>
                    @endcan
                    @can('user_add')
                        <li class="nav-item">
                            <a href="{{ route('user.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('settings.create_user') }}</p>
                            </a>
                        </li>
                    @endcan
                    @can('role_view')
                        <li class="nav-item">
                            <a href="{{ route('role.index') }}"
                                class="nav-link @if (Request::segment(3) == 'role') active @endif">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('settings.all_roles') }}</p>
                            </a>
                        </li>
                    @endcan
            </li>

    </ul>
</li>
<li class="nav-item">
    <a href="{{ route('business.index') }}" class="nav-link @if (Request::segment(2) == 'business') active @endif">
        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
        <p>Business Setup</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('country.index') }}" class="nav-link @if (Request::segment(2) == 'country') active @endif">
        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
        <p>{{ trans('file.country_list') }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('city.index') }}" class="nav-link @if (Request::segment(2) == 'city') active @endif">
        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
        <p>{{ trans('file.city_list') }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('source.index') }}" class="nav-link @if (Request::segment(2) == 'source') active @endif">
        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
        <p>{{ trans('settings.source_of_query') }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('sector.index') }}" class="nav-link @if (Request::segment(2) == 'sector') active @endif">
        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
        <p>{{ trans('settings.sector_list') }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('airline.index') }}" class="nav-link @if (Request::segment(2) == 'airline') active @endif">
        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
        <p>{{ trans('settings.airline_list') }}</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('service.index') }}" class="nav-link @if (Request::segment(2) == 'service') active @endif">
        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
        <p>{{ trans('settings.service_list') }}</p>
    </a>
</li>
@endcan
