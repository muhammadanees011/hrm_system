@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Payroll') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('setsalary') }}">{{ __('Manage Payroll') }}</a></li>
@endsection

@section('content')
<style>
    .nav-tabs .active{
        background:orange !important;
        color:white !important;
    }
    .set-card{
        height:70vh !important;
    }
</style>


<div class="col-12 mb-5">
    <div class="row">
        <ul class="nav nav-tabs ms-2" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="leave-tab" data-bs-toggle="tab" data-bs-target="#leave" type="button" role="tab" aria-controls="leave" aria-selected="true">Leave Encashment</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="salary-tab" data-bs-toggle="tab" data-bs-target="#salary" type="button" role="tab" aria-controls="salary" aria-selected="false">Advance Salary</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission" type="button" role="tab" aria-controls="commission" aria-selected="false">Commission</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="allowance-tab" data-bs-toggle="tab" data-bs-target="#allowance" type="button" role="tab" aria-controls="allowance" aria-selected="false">Allowance</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="other_payments-tab" data-bs-toggle="tab" data-bs-target="#other_payments" type="button" role="tab" aria-controls="other_payments" aria-selected="false">Deduction</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bonus-tab" data-bs-toggle="tab" data-bs-target="#bonus" type="button" role="tab" aria-controls="bonus" aria-selected="false">Bonus</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="overtime-tab" data-bs-toggle="tab" data-bs-target="#overtime" type="button" role="tab" aria-controls="overtime" aria-selected="false">Overtime</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan" type="button" role="tab" aria-controls="loan" aria-selected="false">Loan</button>
            </li>   
        </ul>
        <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="salary" role="tabpanel" aria-labelledby="salary-tab">
            <!-- Salary -->
            <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Advance Salary Request') }}</h5>
                            </div>
                            @can('Create Allowance')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('advancesalaryrequest.create',1) }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Advance Salary Request') }}" data-bs-toggle="tooltip" title=""
                                        class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Reason') }}</th>
                                        <th>{{ __('Month') }}</th>
                                        <th>{{ __('status') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($advancesalaryrequests as $advancesalaryrequest)
                                        <tr>
                                            <td>{{ !empty($advancesalaryrequest->employee()) ? $advancesalaryrequest->employee()->name : '' }}</td>
                                            <td>{{$advancesalaryrequest->title}}</td>
                                            <td>{{$advancesalaryrequest->reason}}</td>
                                            <td>{{$advancesalaryrequest->month}}</td>
                                            <td>{{$advancesalaryrequest->status}}</td>
                                            <td class="Action">
                                                <span>
                                                    @can('Edit Allowance')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('advancesalaryrequest/' . $advancesalaryrequest->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Advance Salary Request') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Allowance')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['advancesalaryrequest.destroy', $advancesalaryrequest->id],
                                                                'id' => 'delete-form-' . $advancesalaryrequest->id,
                                                            ]) !!}
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
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
        </div>
        <div class="tab-pane fade" id="allowance" role="tabpanel" aria-labelledby="allowance-tab">
            <!-- allowance -->
            <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Allowance') }}</h5>
                            </div>
                            @can('Create Allowance')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('allowancerequest.create') }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Allowance') }}" data-bs-toggle="tooltip" title=""
                                        class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Allownace Option') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allowancerequests as $allowancerequest)
                                        <tr>
                                            <td>{{ !empty($allowancerequest->employee()) ? $allowancerequest->employee()->name : '' }}</td>
                                            <td>{{ !empty($allowancerequest->allowance_option()) ? $allowancerequest->allowance_option()->name : '' }}</td>
                                            <td>{{ $allowancerequest->title }}</td>
                                            <td>{{ ucfirst($allowancerequest->type) }}</td>
                                            @if ($allowancerequest->type == 'fixed')
                                                <td>{{ \Auth::user()->priceFormat($allowancerequest->amount) }}</td>
                                            @else
                                                <td>{{ $allowancerequest->amount }}%
                                                    ({{ \Auth::user()->priceFormat($allowancerequest->tota_allow) }})
                                                </td>
                                            @endif
                                            <td>{{ $allowancerequest->description }}</td>
                                            <td>{{$allowancerequest->status}}</td>
                                            <td class="Action">
                                                <span>
                                                    @can('Edit Allowance')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('allowancerequest/' . $allowancerequest->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Allowance Request') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Allowance')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['allowancerequest.destroy', $allowancerequest->id],
                                                                'id' => 'delete-form-' . $allowancerequest->id,
                                                            ]) !!}
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
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
        </div>
        <div class="tab-pane fade" id="commission" role="tabpanel" aria-labelledby="commission-tab">
             <!-- Commission -->
             <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Commission Request') }}</h5>
                            </div>
                            @can('Create Commission')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('commissionrequest.create') }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Commission') }}" data-bs-toggle="tooltip" title=""
                                        class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>

                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>

                                    <tr>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commissionrequests as $commissionrequest)
                                        <tr>
                                            <td>{{ !empty($commissionrequest->employee()) ? $commissionrequest->employee()->name : '' }}
                                            </td>
                                            <td>{{ $commissionrequest->title }}</td>

                                            <td>{{ ucfirst($commissionrequest->type) }}</td>
                                            @if ($commissionrequest->type == 'fixed')
                                                <td>{{ \Auth::user()->priceFormat($commissionrequest->amount) }}</td>
                                            @else
                                                <td>{{ $commissionrequest->amount }}%
                                                    ({{ \Auth::user()->priceFormat($commissionrequest->tota_allow) }})
                                                </td>
                                            @endif
                                            <td>{{ $commissionrequest->description }}</td>
                                            <td>{{ $commissionrequest->status }}</td>
                                            <td class="Action">
                                                <span>
                                                    @can('Edit Commission')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('commissionrequest/' . $commissionrequest->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Commission Request') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Commission')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['commissionrequest.destroy', $commissionrequest->id],
                                                                'id' => 'delete-form-' . $commissionrequest->id,
                                                            ]) !!}
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
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
        </div>
        <div class="tab-pane fade" id="loan" role="tabpanel" aria-labelledby="loan-tab">
            <!-- loan-->
            <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Loan Request') }}</h5>
                            </div>
                            @can('Create Loan')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('loanrequest.create') }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Loan Request') }}" data-bs-toggle="tooltip" title=""
                                        data-size="lg" class="btn btn-sm btn-warning"
                                        data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>

                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee') }}</th>
                                        <th>{{ __('Loan Options') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Loan Amount') }}</th>
                                        <th>{{ __('Reason') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loanrequests as $loanrequest)
                                        <tr>
                                            <td>{{ !empty($loanrequest->employee()) ? $loanrequest->employee()->name : '' }}</td>
                                            <td>{{ !empty($loanrequest->loan_option()) ? $loanrequest->loan_option()->name : '' }}
                                            </td>
                                            <td>{{ $loanrequest->title }}</td>
                                            <td>{{ ucfirst($loanrequest->type) }}</td>
                                            @if ($loanrequest->type == 'fixed')
                                                <td>{{ \Auth::user()->priceFormat($loanrequest->amount) }}</td>
                                            @else
                                                <td>{{ $loanrequest->amount }}%
                                                    ({{ \Auth::user()->priceFormat($loanrequest->tota_allow) }})
                                                </td>
                                            @endif
                                            <td>{{ $loanrequest->reason }}</td>
                                            <td>{{ $loanrequest->status }}</td>
                                            <td class="Action">
                                                <span>
                                                    @can('Edit Loan')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('loanrequest/' . $loanrequest->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="lg"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Edit Loan Request') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Loan')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['loanrequest.destroy', $loanrequest->id],
                                                                'id' => 'delete-form-' . $loanrequest->id,
                                                            ]) !!}
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
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
        </div>
        <div class="tab-pane fade show active" id="leave" role="tabpanel" aria-labelledby="leave-tab">
            <!-- Leave Encashment -->
            <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Leave Encashment Requests') }}</h5>
                            </div>
                            @can('Create Allowance')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('leaveencashmentrequest.create') }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Leave Encashment Request') }}" data-bs-toggle="tooltip" title=""
                                        class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Days') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveencashmentrequests as $leaveencashmentrequest)
                                        <tr>
                                            <td>{{ !empty($loanrequest->employee()) ? $loanrequest->employee()->name : '' }}</td>
                                            <td>{{ $leaveencashmentrequest->title}}</td>
                                            <td>{{ $leaveencashmentrequest->description}}</td>
                                            <td>{{ $leaveencashmentrequest->days_requested}}</td>
                                            <td>{{ $leaveencashmentrequest->amount_requested}}</td>
                                            <td class="Action">
                                                <span>
                                                    @can('Edit Allowance')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('leaveencashmentrequest/' . $leaveencashmentrequest->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Leave Encashment Request') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Allowance')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['leaveencashmentrequest.destroy', $leaveencashmentrequest->id],
                                                                'id' => 'delete-form-' . $leaveencashmentrequest->id,
                                                            ]) !!}
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
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
        </div>
        <div class="tab-pane fade" id="other_payments" role="tabpanel" aria-labelledby="other_payments-tab">
            <!-- other payment-->
            <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Allowance') }}</h5>
                            </div>
                            @can('Create Allowance')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('allowances.create',1) }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Allowance') }}" data-bs-toggle="tooltip" title=""
                                        class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Allownace Option') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Type') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @for($i=0; $i< 5; $i++)
                                        <tr>
                                            <td>Muhammad Anees
                                            </td>
                                            <td>Fuel Allowance
                                            </td>
                                            <td>Fuel Allowance</td>

                                            <td>Transportation Allowance</td>
                                            <td>200
                                            </td>
                                            <td class="Action">
                                                <span>
                                                    @can('Edit Allowance')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url=""
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Allowance') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Allowance')
                                                        <div class="action-btn bg-danger ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </span>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="bonus" role="tabpanel" aria-labelledby="bonus-tab">
            <!--bonus-->
            <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Bonus') }}</h5>
                            </div>
                            @can('Create Allowance')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('bonusrequest.create',1) }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Bonus Request') }}" data-bs-toggle="tooltip" title=""
                                        class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th>{{ __('Amount') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bonusrequests as $bonusrequest)
                                        <tr>
                                            <td>Muhammad Anees
                                            </td>
                                            <td>{{$bonusrequest->title}}
                                            </td>
                                            <td>{{$bonusrequest->description}}</td>
                                            <td>${{$bonusrequest->amount}}
                                            </td>
                                            <td class="Action">
                                                <span class="d-flex">
                                                    @can('Edit Allowance')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('bonusrequest.edit', $bonusrequest->id) }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Edit Bonus Request') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Allowance')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['bonusrequest.destroy', $bonusrequest->id], 'id' => 'delete-form-' . $bonusrequest->id]) !!}
                                                        <div class="action-btn bg-danger ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
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
        </div>
        <div class="tab-pane fade" id="overtime" role="tabpanel" aria-labelledby="overtime-tab">
            <!--overtime-->
            <div class="col-md-12">
                <div class="card set-card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-11">
                                <h5>{{ __('Overtime') }}</h5>
                            </div>
                            @can('Create Overtime')
                                <div class="col-1 text-end">
                                    <a data-url="{{ route('overtimerequest.create') }}" data-ajax-popup="true"
                                        data-title="{{ __('Create Overtime Request') }}" data-bs-toggle="tooltip" title=""
                                        class="btn btn-sm btn-warning" data-bs-original-title="{{ __('Create') }}">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class=" card-body table-border-style" style=" overflow:auto">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee Name') }}</th>
                                        <th>{{ __('Overtime Title') }}</th>
                                        <th>{{ __('Number of days') }}</th>
                                        <th>{{ __('Hours') }}</th>
                                        <th>{{ __('Rate') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($overtimerequests as $overtimerequest)
                                        <tr>
                                            <td>{{ !empty($overtimerequest->employee()) ? $overtimerequest->employee()->name : '' }}
                                            </td>
                                            <td>{{ $overtimerequest->title }}</td>
                                            <td>{{ $overtimerequest->number_of_days }}</td>
                                            <td>{{ $overtimerequest->hours }}</td>
                                            <td>{{ \Auth::user()->priceFormat($overtimerequest->rate) }}</td>
                                            <td>Pending</td>
                                            <td class="Action">
                                                <span>
                                                    @can('Edit Overtime')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('overtimerequest/' . $overtimerequest->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Edit OverTime') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('Delete Overtime')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['overtimerequest.destroy', $overtimerequest->id],
                                                                'id' => 'delete-form-' . $overtimerequest->id,
                                                            ]) !!}
                                                            <a class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
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
        </div>
        </div>
    </div>
</div>


@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).on('change', '.amount_type', function() {

            var val = $(this).val();
            var label_text = 'Amount';
            if (val == 'percentage') {
                var label_text = 'Percentage';
            }
            $('.amount_label').html(label_text);
        });


        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });



        function getDesignation(did) {
            $.ajax({
                url: '{{ route('employee.json') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value="">{{ __('Select any Designation') }}</option>');
                    $.each(data, function(key, value) {
                        var select = '';
                        if (key == '{{ 1 }}') {   //employee->designation_id 
                            select = 'selected';
                        }

                        $('#designation_id').append('<option value="' + key + '"  ' + select + '>' +
                            value + '</option>');
                    });
                }
            });
        }
    </script>
@endpush
