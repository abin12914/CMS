@extends('layouts.app')
@section('title', 'Student Details')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Student
            <small>Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('student.index') }}"> Students</a></li>
            <li class="active"> Details</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user-2">
                        @if(!empty($student))
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="/images/public/default_student.jpg" alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">Student Details</h3>
                                <h5 class="widget-user-desc">{{ $student->student_code }}</h5>
                            </div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-paperclip margin-r-5"></i> Reference Code
                                            </strong>
                                            <p class="text-muted multi-line">
                                                #{{ $student->student_code }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-book margin-r-5"></i> Student Name
                                            </strong>
                                            <p class="text-muted multi-line">
                                                @if(!empty(config('constants.studentTitles'))&& isset(config('constants.studentTitles')[$student->title]))
                                                    {{ config('constants.studentTitles')[$student->title] }}
                                                @endif
                                                {{ $student->name }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-file-text-o margin-r-5"></i> Address
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $student->address or "-" }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-phone margin-r-5"></i> Phone
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $student->phone ?: '-' }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-user-o margin-r-5"></i> Gender
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ !empty(config('constants.genderTypes')[$student->gender]) ? config('constants.genderTypes')[$student->gender] : '-' }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-map-marker margin-r-5"></i> Course & University
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $student->batch->course->course_name or "-" }} / {{ $student->batch->course->university->university_name }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-user-o margin-r-5"></i> Batch
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $student->batch->batch_name }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-link margin-r-5"></i> Year
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $student->batch->from_year }} - {{ $student->batch->to_year }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-map-pin margin-r-5"></i> Registration Number
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $student->registration_number ?? 'N/A' }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-inr margin-r-5"></i> Total Fee
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $student->batch->fee_amount }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="clearfix"> </div>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="col-md-{{ $student->status == 1 ? '12' : '6' }}">
                                                @if($student->relation == 1)
                                                    <a href="{{ route('employee.show', $student->employee->id) }}">
                                                        <button type="button" class="btn btn-info btn-block btn-flat">Employee Details</button>
                                                    </a>
                                                @else
                                                    <form action="{{ route('student.edit', $student->id) }}" method="get" class="form-horizontal">
                                                        <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                                    </form>
                                                @endif
                                            </div>
                                            @if($student->status != 1)
                                                <div class="col-md-6">
                                                    <form action="#" method="get" class="form-horizontal">
                                                        <button type="button" class="btn btn-warning btn-block btn-flat">Activate</button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
@endsection
