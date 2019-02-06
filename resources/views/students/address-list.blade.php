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
                        <form action="{{ route('student.address.list') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-6">
                                            <label for="batch_id" class="control-label">Batch : </label>
                                            {{-- adding course select component --}}
                                                @component('components.selects.batches', ['selectedBatchId' => old('batch_id'), 'selectName' => 'batch_id', 'activeFlag' => false, 'tabindex' => 1])
                                                @endcomponent
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'batch_id'])
                                                @endcomponent
                                        </div>
                                        <div class="col-md-6">
                                            <label for="student_id" class="control-label">Student : </label>
                                            {{-- adding student select component --}}
                                            @component('components.selects.students', ['selectedStudentId' => $params['id'], 'cashStudentFlag' => false, 'selectName' => 'student_id', 'activeFlag' => false, 'tabindex' => 2])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'student_id'])
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
        <div class="box">
            <div class="box-body">
                @if(!empty($students))
                    @php
                        $i = 1;
                    @endphp
                    @foreach($students as $index => $student)
                        @if($i % 3 == 1)
                            <div class="row">
                                <div class="col-md-12">
                        @endif
                        <div class="col-md-4 col-xs-4 col-lg-4 col-sm-4" style="border: double; border-color: black; height: 100px;">
                            <b>{{ $student->name }}</b><br>
                            <p>{{ $student->address }}</p>
                        </div>
                        @if($i % 3 == 0)
                                </div>
                            </div>
                        @endif

                        @php
                            $i++;
                        @endphp
                    @endforeach
                @endif
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection