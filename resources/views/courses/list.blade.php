@extends('layouts.app')
@section('title', 'Course List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Course
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Course</a></li>
            <li class="active"> List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    {{-- page header for printers --}}
                    @include('sections.print-head')
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 15%;">Course Name</th>
                                            <th style="width: 25%;">Descriptive Name</th>
                                            <th style="width: 20%;">University</th>
                                            <th style="width: 15%;">Center Code</th>
                                            <th style="width: 10%;">Duration/s</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($courses))
                                            @foreach($courses as $index => $course)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $course->course_name }}</td>
                                                    <td>{{ $course->descriptive_name }}</td>
                                                    <td>{{ $course->university }}</td>
                                                    <td>{{ $course->center_code }}</td>
                                                    <td>
                                                        {{ $course->duration }}
                                                        {{ (!empty(config('constants.courseDurationTypes')) && !empty(config('constants.courseDurationTypes')[$course->duration_type])) ? config('constants.courseDurationTypes')[$course->duration_type] : '' }}
                                                    </td>
                                                    <td class="no-print">
                                                        <a href="{{ route('course.edit', $course->id) }}">
                                                            <button type="button" class="btn btn-warning"><i class="fa fa-edit"></i> Edit</button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
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