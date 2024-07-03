<?php
$accounts=['root_accounts', 'dashboard', 'head_accounts', 'subhead_accounts',
    'trans_accounts', 'payment_vouchers', 'receipt_vouchers','journal_vouchers','ledger',
    'financial_year','agent_wallet','service_providors'];
?>
<li class="nav-item has-treeview <?php if(in_array(Request::segment(2), $accounts)) echo 'menu-open';
                    elseif(in_array(Request::segment(3), $accounts)) echo 'menu-open';?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-key fa-xs"></i>
                            <p>{{ __('accounts.account') }}
                                <i class="nav-icon fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('dashboard.index') }}" class="nav-link {{ (request()->is('Accounts/dashboard')) ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                        <p>Accounts Dashboard</p>
                                    </a>
                                </li>


                                <li class="nav-item has-treeview <?php if(in_array(Request::segment(2), $accounts)) echo 'menu-open'; ?>">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-cog fa-xs"></i>
                                        <p>
                                            Master Account
                                            <i class="nav-icon fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('financial_year.index') }}" class="nav-link {{ (request()->is('Accounts/financial_year')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Financial Years</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('root_accounts.index') }}" class="nav-link {{ (request()->is('Accounts/root_accounts')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Root Accounts</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('head_accounts.index') }}" class="nav-link {{ (request()->is('Accounts/head_accounts')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Head Accounts</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('subhead_accounts.index') }}" class="nav-link {{ (request()->is('Accounts/subhead_accounts')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Subhead Accounts</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('trans_accounts.index') }}" class="nav-link {{ (request()->is('Accounts/trans_accounts')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Transaction Accounts</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>


                                <li class="nav-item has-treeview <?php if(in_array(Request::segment(3), $accounts)) echo 'menu-open'; ?>">
                                    <a href="#" class="nav-link">
                                        <i class="nav-icon fas fa-dollar-sign"></i>
                                        <p>
                                            Vouchers
                                            <i class="nav-icon fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        
                                            <li class="nav-item">
                                                <a href="{{ route('receipt_vouchers.index') }}" class="nav-link {{ (request()->is('Accounts/vouchers/receipt_vouchers')) ? 'active' : '' }}">
                                                    <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                    <p>Receipt Voucher</p>
                                                </a>
                                            </li>


                                            <li class="nav-item">
                                                <a href="{{ route('payment_vouchers.index') }}" class="nav-link {{ (request()->is('Accounts/vouchers/payment_vouchers')) ? 'active' : '' }}">
                                                    <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                    <p>Payment Voucher</p>
                                                </a>
                                            </li>


                                            <li class="nav-item">
                                                <a href="{{ route('journal_vouchers.index') }}" class="nav-link {{ (request()->is('Accounts/vouchers/journal_vouchers'))? 'active':'' }}">
                                                    <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                    <p>Journal Voucher</p>
                                                </a>
                                            </li>
                                    </ul>
                                </li>

                            @can('sale_invoices_view')
                                <li class="nav-item has-treeview <?php if(in_array(Request::segment(1), $sale)) echo 'menu-open'; ?>">
                                    <a href="#" class="nav-link">
                                        <i class='nav-icon fas fa-ticket-alt fa-xs'></i>
                                        <p>
                                            Invoice
                                            <i class="nav-icon fas fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url('Sale') }}" class="nav-link {{ (request()->is('Sale')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>{{ __('sale_invoice.sale_invoice') }}</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan
                            @can('general_ledger_view')
                                <li class="nav-item">
                                    <a href="{{ url('Accounts/ledger') }}" class="nav-link {{ (request()->is('Accounts/ledger')) ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                        <p>General Ledger</p>
                                    </a>
                                </li>
                            @endcan
{{--                            @can('refund_view')--}}
{{--                                <li class="nav-item has-treeview <?php if(in_array(Request::segment(1), $sale)) echo 'menu-open'; ?>">--}}
{{--                                    <a href="#" class="nav-link">--}}
{{--                                        <i class="nav-icon fas fa-undo"></i>--}}
{{--                                        <p>--}}
{{--                                            Refunds--}}
{{--                                            <i class="nav-icon fas fa-angle-left right"></i>--}}
{{--                                        </p>--}}
{{--                                    </a>--}}
{{--                                    <ul class="nav nav-treeview">--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="#" class="nav-link {{ (request()->is('Accounts/ledger')) ? 'active' : '' }}">--}}
{{--                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>--}}
{{--                                                <p>Refunds</p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
{{--                            @endcan--}}
                            @can('account_reports_view')
                                <li class="nav-item <?php if(in_array(Request::segment(3), $account_reports)) echo 'menu-open'; ?>">
                                    <a href="{{ route('users.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                        <p>Account Reports
                                            <i class="nav-icon right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ url('Accounts/reports/ledger_report') }}" class="nav-link {{ (request()->is('Accounts/reports/ledger_report')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Ledger Reports</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('trail_balance.index') }}" class="nav-link {{ (request()->is('Accounts/reports/trail_balance')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Traial Balance</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('account_day_book.index') }}" class="nav-link {{ (request()->is('Accounts/reports/account_day_book')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Accounts Day Book</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('income_statement.index') }}" class="nav-link {{ (request()->is('Accounts/reports/income_statement')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Income Statement</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('balance_sheet.index') }}" class="nav-link {{ (request()->is('Accounts/reports/balance_sheet')) ? 'active' : '' }}">
                                                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                                                <p>Balance Sheet</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan
                        </ul>
                    </li>
