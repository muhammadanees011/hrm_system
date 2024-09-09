@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Account') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Account') }}</li>
@endsection

@section('action-button')
    @can('Create Account List')
        <a href="#" data-url="{{ route('accountlist.create') }}" data-ajax-popup="true"
            data-title="{{ __('Create New Account') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')

<style>

.employees-actions{
    display:flex;
    justify-content:end;
}

/* Dropdown container */
.dropdown {
    position: relative;
    display: inline-block;
  }
  
  /* Dropdown button */
  .dropbtn {
    background-color: orange;
    color: white;
    padding: 6px;
    padding-left: 1.3rem;
    padding-right: 1.3rem;
    font-size: 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
  }
  
  /* Dropdown content */
  .dropdown-content {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 1px;
    font-size: 12px;
  }
  
  /* Links inside the dropdown */
  .dropdown-content a {
    color: black !important;
    padding: 5px 12px;
    text-decoration: none;
    display: block;
  }
  
  /* Change color of links on hover */
  .dropdown-content a:hover {
    background-color: orange;
    color:white !important;
}
  
  /* Show the dropdown menu on hover */
  .dropdown:hover .dropdown-content {display: block;}
  
  /* Change the background color of the dropdown button when the dropdown content is shown */
  .dropdown:hover .dropbtn {color: white;}

</style>
<div class="employees-actions">
    <div class="employees-nav me-2 mb-2">
        <div class="nav-titles">
            <div class="dropdown">
            <button class="dropbtn">Finance &#9660;</button>
            <div class="dropdown-content">
            @can('View Balance Account List')
            <a class="dash-link" href="{{ route('accountbalance') }}">{{ __('Account Balance') }}</a>
            @endcan
            @can('Manage Payee')
            <a class="dash-link" href="{{ route('payees.index') }}">{{ __('Payees') }}</a>
            @endcan
            @can('Manage Payer')
            <a class="dash-link" href="{{ route('payer.index') }}">{{ __('Payers') }}</a>
            @endcan
            @can('Manage Deposit')
            <a class="dash-link" href="{{ route('deposit.index') }}">{{ __('Deposit') }}</a>
            @endcan
            @can('Manage Expense')
            <a class="dash-link" href="{{ route('expense.index') }}">{{ __('Expense') }}</a>
            @endcan
            @can('Manage Transfer Balance')
            <a class="dash-link" href="{{ route('transferbalance.index') }}">{{ __('Transfer Balance') }}</a>
            @endcan
            </div>
            </div>
        </div>
    </div>
</div>

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Account Name') }}</th>
                                <th>{{ __('Initial Balance') }}</th>
                                <th>{{ __('Account Number') }}</th>
                                <th>{{ __('Branch Code') }}</th>
                                <th>{{ __('Bank Branch') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accountlists as $accountlist)
                                <tr>
                                    <td>{{ $accountlist->account_name }}</td>
                                    <td>{{ \Auth::user()->priceFormat($accountlist->initial_balance) }}</td>
                                    <td>{{ $accountlist->account_number }}</td>
                                    <td>{{ $accountlist->branch_code }}</td>
                                    <td>{{ $accountlist->bank_branch }}</td>
                                    <td class="Action">

                                        <span>
                                            @can('Edit Account List')
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('accountlist/' . $accountlist->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Account List') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            @can('Delete Account List')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['accountlist.destroy', $accountlist->id], 'id' => 'delete-form-' . $accountlist->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            @endcan
                                        </span>

                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
