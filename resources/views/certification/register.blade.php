@extends('layouts.app')
@section('title', 'Certificate Issuing')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Certificate
            <small>Issuing</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('address.index') }}"> Certificate</a></li>
            <li class="active">Issuing</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                {{-- @foreach($errors->all() as $er) 
                {{ $er }}<br>
                @endforeach --}}
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header">
                        <div class="widget-user-image">
                            <img class="img-circle" src="/images/public/default_address.png" alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username text-capitalize">&emsp;Certificate Issuing</h3>
                        <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('address.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <h4 class="text-info">&emsp;&emsp;Certificate Info</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="certificate_date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                                        <input type="text" name="certificate_date" class="form-control datepicker" id="certificate_date" placeholder="Certificate date" value="{{ old('certificate_date') }}" tabindex="2" maxlength="100">
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'certificate_date'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="authority_id" class="control-label"><b style="color: red;">* </b> Certificate : </label>
                                                        {{-- adding authority select component --}}
                                                        @component('components.selects.authorities', ['selectedAuthorityId' => old('authority_id'), 'selectName' => 'authority_id', 'activeFlag' => false, 'tabindex' => 7])
                                                        @endcomponent
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'authority_id'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="authority_id" class="control-label"><b style="color: red;">* </b> Address : </label>
                                                        {{-- adding authority select component --}}
                                                        @component('components.selects.authorities', ['selectedAuthorityId' => old('authority_id'), 'selectName' => 'authority_id', 'activeFlag' => false, 'tabindex' => 7])
                                                        @endcomponent
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'authority_id'])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-info">&emsp;&emsp;Select Students</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="student_code" class="control-label">Student Code : </label>
                                                        <input type="text" name="student_code" class="form-control" id="student_code" placeholder="Student code" value="{{ old('student_code') }}" tabindex="2" maxlength="100">
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'student_code'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="authority_id" class="control-label">Batch : </label>
                                                        {{-- adding authority select component --}}
                                                        @component('components.selects.authorities', ['selectedAuthorityId' => old('authority_id'), 'selectName' => 'authority_id', 'activeFlag' => false, 'tabindex' => 7])
                                                        @endcomponent
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'authority_id'])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div><br><br>
                                            <div class="form-group">
                                                <div class="row">
                                                    <table class="table table-bordered table-hover dataTable">
                                                        <thead>
                                                            <th style="width: 5%;">#</th>
                                                            <th style="width: 30%;">Student Name & Code</th>
                                                            <th style="width: 20%;">Address</th>
                                                            <th style="width: 15%;">Course</th>
                                                            <th style="width: 15%;">University</th>
                                                            <th style="width: 10%;">Fee</th>
                                                            <th style="width: 5%;">Selection</th>
                                                        </thead>
                                                        <tbody id="student_table_body">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
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
                    </form>
                </div>
                <!-- /.box primary -->
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/certificationRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection