@extends('layouts.app')
@section('title', 'Batch Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Batch
            <small>Registration</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('batch.index') }}"> Batches</a></li>
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
                                <img class="img-circle" src="/images/public/default_batch.jpg" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username text-capitalize">&emsp;Batch Registration</h3>
                            <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('batch.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <hr>
                                        <div class="form-group">
                                            <label for="batch_name" class="col-md-3 control-label"><b style="color: red;">* </b> Batch Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="batch_name" class="form-control" id="batch_name" placeholder="Batch name" value="{{ old('batch_name') }}" tabindex="2" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'batch_name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="course_id" class="col-md-3 control-label"><b style="color: red;">* </b> Course : </label>
                                            <div class="col-md-9">
                                                {{-- adding course select component --}}
                                                @component('components.selects.courses', ['selectedCourseId' => old('course_id'), 'selectName' => 'course_id', 'activeFlag' => false, 'tabindex' => 7])
                                                @endcomponent
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'course_id'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="from_year" class="col-md-3 control-label">Duration : From : </label>
                                            <div class="col-md-4">
                                                <input type="text" name="from_year" class="form-control number_only" id="from_year" placeholder="From year" value="{{ old('from_year') }}" tabindex="4" minlength="10" maxlength="13">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'from_year'])
                                                @endcomponent
                                            </div>
                                            <label for="to_year" class="col-md-1 control-label">To : </label>
                                            <div class="col-md-4">
                                                <input type="text" name="to_year" class="form-control number_only" id="to_year" placeholder="To year" value="{{ old('to_year') }}" tabindex="4" minlength="10" maxlength="13">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'to_year'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="fee_amount" class="col-md-3 control-label"><b style="color: red;">* </b> Fee : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="fee_amount" class="form-control" id="fee_amount" placeholder="Fee amount" value="{{ old('fee_amount') }}" tabindex="6" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'fee_amount'])
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
