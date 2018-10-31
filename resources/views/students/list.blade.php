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
                                            <label for="relation_type" class="control-label">Relation : </label>
                                            <select class="form-control select2" name="relation_type" id="relation_type" style="width: 100%" tabindex="1">
                                                <option value="">Select relation type</option>
                                                @if(!empty($relationTypes) && (count($relationTypes) > 0))
                                                    @foreach($relationTypes as $key => $relationType)
                                                        <option value="{{ $key }}" {{ (old('relation_type') == $key || $params['relation'] == $key) ? 'selected' : '' }}>{{ $relationType }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'relation_type'])
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
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 4])
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
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="6">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="5"><i class="fa fa-search"></i> Search</button>
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
                                            <th style="width: 20%;">Student Name</th>
                                            <th style="width: 15%;">Relation</th>
                                            <th style="width: 20%;">Student Holder</th>
                                            <th style="width: 15%;">Phone</th>
                                            <th style="width: 10%;">Opening Credit</th>
                                            <th style="width: 10%;">Opening Debit</th>
                                            <th style="width: 5%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($students))
                                            @foreach($students as $index => $student)
                                                <tr>
                                                    <td>{{ $index + $students->firstItem() }}</td>
                                                    <td title="Inative/Suspended">
                                                        {{ $student->student_name }}
                                                        @if($student->status != 1)
                                                            &emsp;<i class="fa fa-exclamation-triangle text-orange no-print"></i>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ (!empty($relationTypes) && !empty($relationTypes[$student->relation])) ? $relationTypes[$student->relation] : "Error!" }}
                                                    </td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>{{ $student->phone }}</td>
                                                    <td>{{ $student->financial_status == 1 ? $student->opening_balance : "-" }}</td>
                                                    <td>{{ $student->financial_status == 2 ? $student->opening_balance : "-" }}</td>
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