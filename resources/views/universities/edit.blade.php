@extends('layouts.app')
@section('title', 'University Edit')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            University
            <small>Edit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('university.index') }}"> Authorities</a></li>
            <li class="active">Edit</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header">
                            <div class="widget-user-image">
                                <img class="img-circle" src="/images/public/defa.jpg" alt="University">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username text-capitalize">&emsp;University Edit</h3>
                            <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('university.update', $university->id)}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="_method" value="PUT">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <hr>
                                        <h4 class="text-info">&emsp;&emsp;University Info</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="university_name" class="col-md-3 control-label"><b style="color: red;">* </b> University Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="university_name" class="form-control" id placeholder="University name" value="{{ old('university_name', $university->university_name) }}" tabindex="1" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'university_name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="center_code" class="col-md-3 control-label"><b style="color: red;">* </b> Center Code : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="center_code" class="form-control" id="center_code" placeholder="Center Code" value="{{ old('center_code', $university->center_code) }}" tabindex="2" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'center_code'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="university_grade" class="col-md-3 control-label"><b style="color: red;">* </b> University Grade : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="university_grade" class="form-control" id="university_grade" placeholder="University Grade" value="{{ old('university_grade', $university->university_grade) }}" tabindex="3" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'university_grade'])
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="4">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" class="btn btn-warning btn-block btn-flat update_button" tabindex="3">Update</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                            </div>
                        </form>
                    </div>
                    <!-- /.box primary -->
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
@endsection
