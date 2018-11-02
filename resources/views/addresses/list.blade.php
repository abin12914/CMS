@extends('layouts.app')
@section('title', 'Address List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Address
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Address</a></li>
            <li class="active"> List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row  no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('address.index') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label for="wage_type" class="control-label">Wage Type : </label>
                                            <select class="form-control select2" name="wage_type" id="wage_type" style="width: 100%" tabindex="1">
                                                <option value="">Select wage type</option>
                                                @if(!empty($wageTypes) && (count($wageTypes) > 0))
                                                    @foreach($wageTypes as $key => $wageType)
                                                        <option value="{{ $key }}" {{ (old('wage_type') == $key || $params['wage_type'] == $key) ? 'selected' : '' }}>{{ $wageType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'wage_type'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="address_id" class="control-label">Address : </label>
                                            {{-- adding address select component --}}
                                            @component('components.selects.addresses', ['selectedAddressId' => $params['id'], 'selectName' => 'address_id', 'tabindex' => 2])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'address_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            {{-- adding no of records text component --}}
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 3])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'no_of_records'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="5">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="4"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    {{-- page header for printers --}}
                    @include('sections.print-head')
                    <div class="box-header no-print">
                        @if(!empty($params['wage_type']) || !empty($params['address_id']))
                            <b>Filters applied!</b>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12" style="overflow-x:scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 25%;">Address Name</th>
                                            <th style="width: 15%;">Wage Type</th>
                                            <th style="width: 15%;">Wage</th>
                                            <th style="width: 25%;">Account Name</th>
                                            <th style="width: 15%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($addresses))
                                            @foreach($addresses as $index => $address)
                                                <tr>
                                                    <td>{{ $index + $addresses->firstItem() }}</td>
                                                    <td>{{ $address->name }}</td>
                                                    @if(!empty($wageTypes))
                                                        <td>
                                                            {{ !empty($wageTypes[$address->wage_type]) ? $wageTypes[$address->wage_type] : "Error!" }}
                                                        </td>
                                                    @else
                                                        <td>Error</td>
                                                    @endif
                                                    <td>{{ $address->wage_rate }}</td>
                                                    <td>{{ $address->account_name }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('address.show', ['id' => $address->id]) }}">
                                                            <button type="button" class="btn btn-info">Details</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($addresses))
                                    <div>
                                        Showing {{ $addresses->firstItem(). " - ". $addresses->lastItem(). " of ". $addresses->total() }}
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $addresses->appends(Request::all())->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.boxy -->
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection