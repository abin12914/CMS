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
                                        <div class="col-md-6">
                                            <label for="address_id" class="control-label">Address : </label>
                                            {{-- adding address select component --}}
                                            @component('components.selects.addresses', ['selectedAddressId' => $params['id'], 'selectName' => 'address_id', 'tabindex' => 1])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'address_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-6">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            {{-- adding no of records text component --}}
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 2])
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
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="4">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="3"><i class="fa fa-search"></i> Search</button>
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
                                            <th style="width: 20%;">Name</th>
                                            <th style="width: 20%;">Designation</th>
                                            <th style="width: 10%;">Title</th>
                                            <th style="width: 35%;">Address</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($addresses))
                                            @foreach($addresses as $index => $address)
                                                <tr>
                                                    <td>{{ $index + $addresses->firstItem() }}</td>
                                                    <td>{{ $address->name }}</td>
                                                    <td>{{ $address->designation }}</td>
                                                    <td>{{ $address->title }}</td>
                                                    <td><pre>{{ $address->address }}</pre></td>
                                                    <td class="no-print">
                                                        <a href="{{ route('address.edit', ['id' => $address->id]) }}">
                                                            <button type="button" class="btn btn-default"><i class="fa fa-edit"></i> Edit</button>
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