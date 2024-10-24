@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Appraisal') }}
@endsection
@push('css-page')
    <style>
        @import url({{ asset('css/font-awesome.css') }});

    </style>
@endpush
@push('script-page')
    <script src="{{ asset('js/bootstrap-toggle.js') }}"></script>
    <script>
        $('document').ready(function() {
            $('.toggleswitch').bootstrapToggle();
            $("fieldset[id^='demo'] .stars").click(function() {
                alert($(this).val());
                $(this).attr("checked");
            });
        });

        $(document).ready(function() {
            var employee = $('.employee').val();
            getEmployee(employee);
        });

        $(document).on('change', 'select[name=branch]', function() {
            var branch = $(this).val();
            getEmployee(branch);
        });

        function getEmployee(did) {
            $.ajax({
                url: '{{ route('branch.employee.json') }}',
                type: 'POST',
                data: {
                    "branch": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.employee').empty();
                    var emp_selct = ` <select class="form-control  employee" name="employee" id="choices-multiple"
                                            placeholder="Select Employee" >
                                            </select>`;
                    $('.employee_div').html(emp_selct);

                    $('.employee').append('<option value="0"> {{ __('All') }} </option>');
                    $.each(data, function(key, value) {
                        $('.employee').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Appraisal') }}</li>
@endsection

@section('action-button')
    @can('Create Appraisal')
        <a href="#" data-url="{{ route('appraisal.create') }}" data-ajax-popup="true" data-size="lg"
            data-title="{{ __('Create New Appraisal') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endcan
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                {{-- <h5> </h5> --}}
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Branch') }}</th>
                                <th>{{ __('Department') }}</th>
                                <th>{{ __('Designation') }}</th>
                                <th>{{ __('Employee') }}</th>
                                <!-- <th>{{ __('Target Rating') }}</th>
                                <th>{{ __('Overall Rating') }}</th> -->
                                <th>{{ __('Appraisal Date') }}</th>
                                @if (Gate::check('Edit Appraisal') || Gate::check('Delete Appraisal') || Gate::check('Show Appraisal'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appraisals as $appraisal)
                                <tr>
                                    <td>{{ !empty($appraisal->branches) ? $appraisal->branches->name : '' }}</td>
                                    <td>{{ !empty($appraisal->employees) ?  $appraisal->employees->department->name : '-'}}
                                    </td>
                                    <td>{{ !empty($appraisal->employees) ? $appraisal->employees->designation->name : '-' }}
                                    </td>
                                    <td>{{ !empty($appraisal->employees) ? $appraisal->employees->name : '-' }}</td>
                                    <td>{{ $appraisal->appraisal_date }}</td>
                                    <td class="Action">
                                        @if (Gate::check('Edit Appraisal') || Gate::check('Delete Appraisal') || Gate::check('Show Appraisal'))
                                            <span>


                                                @can('Show Appraisal')



                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ route('appraisal.show', $appraisal->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Appraisal Detail') }}"
                                                            data-bs-original-title="{{ __('View') }}">
                                                            <i class="ti ti-eye text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan


                                                @can('Edit Appraisal')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                            data-url="{{ route('appraisal.edit', $appraisal->id) }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="{{ __('Edit Appraisal') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete Appraisal')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['appraisal.destroy', $appraisal->id], 'id' => 'delete-form-' . $appraisal->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                @endcan
                                            </span>
                                        @endif
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
