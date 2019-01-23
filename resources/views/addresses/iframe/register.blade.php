@extends('layouts.iframe-layout')
@section('title', 'Address Registration')
@section('content')
<div class="content-wrapper" style="margin-left: 0px !important;">
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-2"></div>
                {{-- @foreach($errors->all() as $er) 
                {{ $er }}<br>
                @endforeach --}}
                <div class="col-md-8">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header">
                            <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('address.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="iframe_flag" value="1">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label">Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name') }}" tabindex="1" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="designation" class="col-md-3 control-label"><b style="color: red;">* </b> Designation Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="designation" class="form-control" id="designation" placeholder="Designation name" value="{{ old('designation') }}" tabindex="2" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'designation'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-md-3 control-label"><b style="color: red;">* </b> Address : </label>
                                            <div class="col-md-9">
                                                @if(!empty(old('address')))
                                                    <textarea class="form-control" name="address" id="address" rows="2" placeholder="Address" style="resize: none;" tabindex="3" maxlength="255">{{ old('address') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="address" id="address" rows="2" placeholder="Address" style="resize: none;" tabindex="3" maxlength="200"></textarea>
                                                @endif
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'address'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="title" class="col-md-3 control-label"><b style="color: red;">* </b> Title : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="title" class="form-control" id="title" placeholder="Title" value="{{ old('title') }}" tabindex="4" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'title'])
                                                @endcomponent
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="clearfix"> </div><br>
                                <div class="row">
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3"></div>
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3">
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="6">Clear</button>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-lg-3 col-xs-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="5">Submit</button>
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
