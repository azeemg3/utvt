<?php
$lms=['lead','my_leads','all_leads','lead-reminder'];
?>
<li class="nav-item has-treeview @if(in_array(Request::segment(2),$lms)) menu-open @endif">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-bullhorn"></i>
        <p>
            LMS
            <i class="fas fa-angle-left right"></i>
            <span class="badge badge-info right">6</span>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{  route('lead.index') }}" class="nav-link @if(Request::segment(2)=='lead') active @endif">
                <i class="nav-icon fas fa-tachometer-alt fa-xs"></i>
                <p>{{ __('lms.lms_dashboard') }}</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('lead.create') }}" class="nav-link">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>{{ __('lms.create_lead') }}</p>
            </a>
        </li>
        @can('lms_my_leads')
        <li class="nav-item">
            <a href="{{ route('lead.my_leads') }}" class="nav-link @if(Request::segment(2)=='my_leads') active @endif">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>{{ __('lms.my_leads') }}</p>
            </a>
        </li>
        @endcan
        @can('lms_all_leads')
        <li class="nav-item">
            <a href="{{ route('lead.all_leads') }}" class="nav-link @if(Request::segment(2)=='all_leads') active @endif">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>{{ __('lms.all_leads') }}</p>
            </a>
        </li>
        @endcan
        <li class="nav-item">
            <a href="pages/layout/fixed-footer.html" class="nav-link">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>Reopen Leads</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{route("lead.lead_reminder")}}" class="nav-link @if(Request::segment(2)=='lead-reminder') active @endif">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>Reminder List</p>
            </a>
        </li>
    </ul>
</li>
