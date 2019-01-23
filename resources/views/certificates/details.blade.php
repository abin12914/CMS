@extends('layouts.app')
@section('title', 'Certificate Details')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Certificate
            <small>Details</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('certificate.index') }}"> Certificates</a></li>
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
                        @if(!empty($certificate))
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-yellow">
                                <div class="widget-user-image">
                                    <img class="img-circle" src="/images/public/default_certificate.jpg" alt="User Avatar">
                                </div>
                                <!-- /.widget-user-image -->
                                <h3 class="widget-user-username">{{ $certificate->name }}</h3>
                                <h5 class="widget-user-desc">
                                    {{ $certificate->certificate_type == 1 ? 'For Single Student' : 'For Group Of Students' }}
                                </h5>
                            </div>
                            <div class="box box-primary">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-paperclip margin-r-5"></i> Reference Number
                                            </strong>
                                            <p class="text-muted multi-line">
                                                #{{ $certificate->id }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-book margin-r-5"></i> Certificate Name
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $certificate->name }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-user-o margin-r-5"></i> Authority
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $certificate->authority->name. " - ". $certificate->authority->designation }}
                                            </p>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                <i class="fa fa-file-text-o margin-r-5"></i> Description
                                            </strong>
                                            <p class="text-muted multi-line">
                                                {{ $certificate->description ?? "-" }}
                                            </p>
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>
                                                <i class="text-center"></i> Content :
                                            </strong><br><br>
                                            <i class="text-justify">
                                                {!! $certificate->certificate_content !!}
                                            </i>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <div class="clearfix"> </div>
                                    <div class="row">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="col-md-12">
                                                <a href="{{ route('certificate.edit', $certificate->id) }}">
                                                    <button type="submit" class="btn btn-primary btn-block btn-flat">Edit</button>
                                                </a>
                                            </div>
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
