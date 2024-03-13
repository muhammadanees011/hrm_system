<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">

        @can('Manage Payslip Type')
            <a href="{{ route('bonus.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('bonus*') ? 'active' : '' }}">{{ __('Bonus Type') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endcan
        @can('Manage Payslip Type')
            <a href="{{ route('bonus.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('taxrules*') ? 'active' : '' }}">{{ __('Tax Rules') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endcan
        @can('Manage Payslip Type')
            <a href="{{ route('bonus.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('providentfundspolicy*') ? 'active' : '' }}">{{ __('Provident Funds Policy') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endcan
        @can('Manage Payslip Type')
            <a href="{{ route('bonus.index') }}"
                class="list-group-item list-group-item-action border-0 {{ request()->is('overtimepolicy*') ? 'active' : '' }}">{{ __('OverTime Policy') }}
                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        @endcan
    </div>
</div>
