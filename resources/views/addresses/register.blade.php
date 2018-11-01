@extends('layouts.app')
@section('title', 'Address Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Address
            <small>Registration</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('address.index') }}"> Addresses</a></li>
            <li class="active">Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header">
                            <div class="widget-user-image">
                                <img class="img-circle" src="/images/public/default_address.png" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username text-capitalize">&emsp;Address Registration</h3>
                            <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('address.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <hr>
                                        <h4 class="text-info">&emsp;&emsp;Address Info</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Address name" value="{{ old('name') }}" tabindex="2" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-md-3 control-label"><b style="color: red;">* </b> Designation Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="descriptive_name" class="form-control alpha_only" id="descriptive_name" placeholder="Address descriptive name" value="{{ old('descriptive_name') }}" tabindex="2" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'descriptive_name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="university" class="col-md-3 control-label">Address : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="university" class="form-control" id="university" placeholder="University" value="{{ old('university') }}" tabindex="4" minlength="10" maxlength="13">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'university'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="center_code" class="col-md-3 control-label"><b style="color: red;">* </b> Center Code: </label>
                                            <div class="col-md-9">
                                                <input type="text" name="center_code" class="form-control" id="center_code" placeholder="Center code" value="{{ old('center_code') }}" tabindex="6" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'center_code'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration" class="col-md-3 control-label"><b style="color: red;">* </b> Duration : </label>
                                            <div class="col-md-5">
                                                <input type="text" name="duration" class="form-control" id="duration" placeholder="Duration" value="{{ old('duration') }}" tabindex="6" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'duration'])
                                                @endcomponent
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-control select2" name="duration_type" id="duration_type" tabindex="5" style="width: 100%;">
                                                    <option value="" {{ empty(old('duration_type')) ? 'selected' : '' }}>Select type</option>
                                                    @if(!empty($addressDurationTypes))
                                                        @foreach($addressDurationTypes as $key => $durationType)
                                                            <option value="{{ $key }}" {{ (old('duration_type') == $key) ? 'selected' : '' }}>
                                                                {{ $durationType }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'duration_type'])
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="12">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="11">Submit</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                            </div>
                        </form>
                    </div>
                    <!-- /.box primary -->
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
