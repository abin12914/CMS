@extends('layouts.app')
@section('title', 'Certificate List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Certificate
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Certificate</a></li>
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
                                            <th style="width: 20%;">Certificate Name</th>
                                            <th style="width: 20%;">Description</th>
                                            <th style="width: 15%;">Authority</th>
                                            <th style="width: 10%;">Type</th>
                                            <th style="width: 20%;">Certificate Content</th>
                                            <th style="width: 10%;"  class="no-print">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($certificates))
                                            @foreach($certificates as $index => $certificate)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $certificate->name }}</td>
                                                    <td>{{ $certificate->description }}</td>
                                                    <td>{{ $certificate->authority->name }}, {{ $certificate->authority->designation }}</td>
                                                    <td>{{ $certificate->certificate_type == 1 ? 'For Single Student' : 'For Group Of Students' }}</td>
                                                    <td>
                                                        <div>
                                                            <{{ implode(' ', array_slice(explode(' ', $certificate->certificate_content), 0, 5)) }}....
                                                        </div>
                                                    </td>
                                                    <td class="no-print">
                                                        <a href="{{ route('certificate.show', $certificate->id) }}">
                                                            <button type="button" class="btn btn-info"><i class="fa fa-edit"></i> View</button>
                                                        </a>
                                                        <a href="{{ route('certificate.edit', $certificate->id) }}">
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