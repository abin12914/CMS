@extends('layouts.app')
@section('title', 'Certification')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Certification
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('certification.index') }}"> Certification</a></li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        @foreach($certification->students as $index => $student)
            <!-- Main row -->
            <div class="row" style="page-break-after: always;">
                <div class="col-md-12">
                    <div class="box box-widget widget-user-2">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-header visible-print-block" style="margin-top: 200px;"></div>
                                    @if($certification->address_id != -1)
                                        <strong>To</strong><p class="pull-right">{{ $certification->issue_date }}</p><br>
                                        {{ $certification->address->name }}<br>
                                        {{ $certification->address->designation }}<br>
                                        {{ $certification->address->address }}<br><br><br>
                                        {{ $certification->address->title }},<br><br>
                                    @else
                                        <p class="pull-right">{{ $certification->issue_date }}</p><br>
                                        <h4 class="text-center">TO WHOMESOEVER IT MAY CONCERN</h4>
                                    @endif
                                    &emsp;&emsp;{!! $studentCertification[$student->id] ?: '' !!}
                                    <br><br><br>
                                    Thanking You,<br>
                                    Yours faithfully,<br><br>
                                    {{ $certification->certificate->hide_name_flag != 0 ? $certification->certificate->authority->name : '' }}
                                    {{ $certification->certificate->authority->designation }}
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box primary -->
                </div>
            </div>
            <!-- /.row (main row) -->
        @endforeach
    </section>
    <!-- /.content -->
</div>
@endsection