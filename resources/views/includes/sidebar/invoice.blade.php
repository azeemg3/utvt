<?php
$invoiceMenu = ['invoice', 'product'];
?>
<li class="nav-item has-treeview @if(in_array(Request::segment(2), $invoiceMenu)) menu-open @endif">
    <a href="#" class="nav-link">
        <i class="nav-icon fas fa-file-invoice-dollar"></i>
        <p>
            Invoice
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('invoice.index') }}" class="nav-link @if(Request::segment(2) == 'invoice' && Request::segment(3) != 'create') active @endif">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>All Invoices</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('invoice.create') }}" class="nav-link @if(Request::segment(3) == 'create') active @endif">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>Create Invoice</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('product.index') }}" class="nav-link @if(Request::segment(2) == 'product') active @endif">
                <i class="nav-icon fas fa-angle-double-right fa-xs"></i>
                <p>Products</p>
            </a>
        </li>
    </ul>
</li>
