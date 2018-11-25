@extends('layouts.app')
@section('title', 'Certification List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Certification
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Certification</a></li>
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
                                            <th style="width: 10%;">Issue Date</th>
                                            <th style="width: 25%;">Certificate</th>
                                            <th style="width: 40%;">To Address</th>
                                            <th style="width: 5%;">No Of Students</th>
                                            <th style="width: 10%;">Issued By</th>
                                            <th style="width: 5%;"  class="no-print">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($certifications))
                                            @foreach($certifications as $index => $certification)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $certification->issue_date }}</td>
                                                    <td>{{ $certification->certificate->name }}</td>
                                                    @if($certification->address_id == -1)
                                                        <td>To Whomsoever it may concern</td>
                                                    @else
                                                        <td>{{ $certification->address->designation }}, {{ $certification->address->address }}</td>
                                                    @endif
                                                    <td>{{ $certification->students_count }}</td>
                                                    <td>{{ $certification->user->name }}</td>
                                                    <td class="no-print">
                                                        <a href="{{ route('certification.show', $certification->id) }}">
                                                            <button type="button" class="btn btn-info"><i class="fa fa-edit"></i> Show</button>
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