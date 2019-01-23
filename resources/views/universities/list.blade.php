@extends('layouts.app')
@section('title', 'University List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            University
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> University</a></li>
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
                            <div class="col-md-12" style="overflow-x:scroll;">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 30%;">University Name</th>
                                            <th style="width: 25%;">Center Code</th>
                                            <th style="width: 30%;">University Grade</th>
                                            <th style="width: 10%;" class="no-print">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($universities))
                                            @foreach($universities as $index => $university)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $university->university_name }}</td>
                                                    <td>{{ $university->center_code }}</td>
                                                    <td>{{ $university->university_grade }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('university.edit', $university->id) }}">
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