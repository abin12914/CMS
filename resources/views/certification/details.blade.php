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
        @foreach($certification->students as $student)
        @for($i=0; $i < 50; $i++)
            <!-- Main row -->
            <div class="row" style="page-break-after: always;">
                <div class="col-md-12">
                    <div class="box box-widget widget-user-2">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>To</strong><br>
                                    {{ $certification->address->name }}<br>
                                    {{ $certification->address->designation }}<br>
                                    {{ $certification->address->address }}<br><br><br>
                                    {{ $certification->address->title }},<br><br>
                                    &emsp;&emsp;{!! $certification->certificate->certificate_content !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box primary -->
                </div>
            </div>
            <!-- /.row (main row) -->
        @endfor
        @endforeach
    </section>
    <!-- /.content -->
</div>
@endsection