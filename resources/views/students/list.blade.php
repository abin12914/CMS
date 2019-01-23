@extends('layouts.app')
@section('title', 'Student List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Student
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Students</a></li>
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
                        <form action="{{ route('student.index') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-4">
                                            <label for="batch_id" class="control-label">Batch : </label>
                                            {{-- adding course select component --}}
                                                @component('components.selects.batches', ['selectedBatchId' => old('batch_id'), 'selectName' => 'batch_id', 'activeFlag' => false, 'tabindex' => 1])
                                                @endcomponent
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'batch_id'])
                                                @endcomponent
                                        </div>
                                        <div class="col-md-4">
                                            <label for="student_id" class="control-label">Student : </label>
                                            {{-- adding student select component --}}
                                            @component('components.selects.students', ['selectedStudentId' => $params['id'], 'cashStudentFlag' => false, 'selectName' => 'student_id', 'activeFlag' => false, 'tabindex' => 2])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'student_id'])
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
                        @if(!empty($params['relation']) || !empty($params['id']))
                            <b>Filters applied!</b>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12" style="overflow-x: scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 30%;">Student Name</th>
                                            <th style="width: 15%;">Code</th>
                                            <th style="width: 30%;">Course</th>
                                            <th style="width: 15%;">Year</th>
                                            <th style="width: 5%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($students))
                                            @foreach($students as $index => $student)
                                                <tr>
                                                    <td>{{ $index + $students->firstItem() }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->student_code }}</td>
                                                    <td>{{ $student->batch->course->course_name }}<br>{{ $student->batch->course->university->university_name }}</td>
                                                    <td>{{ $student->batch->from_year }} - {{ $student->batch->to_year }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('student.show', $student->id) }}">
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
                                @if(!empty($students))
                                    <div>
                                        Showing {{ $students->firstItem(). " - ". $students->lastItem(). " of ". $students->total() }}
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $students->appends(Request::all())->links() }}
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