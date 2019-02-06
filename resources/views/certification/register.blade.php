@extends('layouts.app')
@section('title', 'Certificate Issuing')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Certificate
            <small>Issuing</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('certification.index') }}"> Certificate</a></li>
            <li class="active">Issuing</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                {{-- @foreach($errors->all() as $er) 
                {{ $er }}<br>
                @endforeach --}}
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header">
                        <div class="widget-user-image">
                            <img class="img-circle" src="/images/public/certification.png" alt="User Avatar">
                        </div>
                        <!-- /.widget-user-image -->
                        <h3 class="widget-user-username text-capitalize">&emsp;Certificate Issuing</h3>
                        <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{route('certification.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                        <div class="box-body">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                    <h4 class="text-info">&emsp;&emsp;Certificate Info</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="certificate_date" class="control-label"><b style="color: red;">* </b> Date : </label>
                                                        <input type="text" name="certificate_date" class="form-control datepicker" id="certificate_date" placeholder="Certificate date" value="{{ old('certificate_date') }}" tabindex="1" maxlength="100">
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'certificate_date'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="certificate_id" class="control-label"><b style="color: red;">* </b> Certificate : </label>
                                                        {{-- adding certificate select component --}}
                                                        @component('components.selects.certificates', ['selectedCertificateId' => old('certificate_id'), 'selectName' => 'certificate_id', 'activeFlag' => false, 'tabindex' => 2])
                                                        @endcomponent
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'certificate_id'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label for="address_id" class="control-label"><b style="color: red;">* </b> Address : </label>
                                                        {{-- adding address select component --}}
                                                        @component('components.selects.addresses', ['selectedAddressId' => old('address_id'), 'selectName' => 'address_id', 'activeFlag' => true, 'tabindex' => 3])
                                                        @endcomponent
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'address_id'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label class="control-label" style="visibility: hidden;">Add Address</label>
                                                        <button type="button" class="btn btn-success btn-block btn-flat" tabindex="-1" title="Add Address" id="add_address_button">
                                                            <i class="fa fa-plus" title="Add Address"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h4 class="text-info">&emsp;&emsp;Select Students</h4>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label for="student_code" class="control-label">Student Code : </label>
                                                        <input type="text" name="student_code" class="form-control" id="student_code" placeholder="Student code" value="{{ old('student_code') }}" tabindex="4" maxlength="100">
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'student_code'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="student_name" class="control-label">Student Name : </label>
                                                        <input type="text" name="student_name" class="form-control" id="student_name" placeholder="Student name" value="{{ old('student_name') }}" tabindex="4" maxlength="100">
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'student_name'])
                                                        @endcomponent
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="batch_id" class="control-label">Batch : </label>
                                                        {{-- adding authority select component --}}
                                                        @component('components.selects.batches', ['selectedBatchId' => old('batch_id'), 'selectName' => 'batch_id', 'activeFlag' => false, 'tabindex' => 5])
                                                        @endcomponent
                                                        {{-- adding error_message p tag component --}}
                                                        @component('components.paragraph.error_message', ['fieldName' => 'batch_id'])
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div><br><br>
                                            <div class="form-group">
                                                <div class="row">
                                                    <table class="table table-bordered table-hover dataTable">
                                                        <thead>
                                                            <th style="width: 5%;">#</th>
                                                            <th style="width: 20%;">Student Name & Code</th>
                                                            <th style="width: 5%;">Gender</th>
                                                            <th style="width: 20%;">Address</th>
                                                            <th style="width: 15%;">Course</th>
                                                            <th style="width: 15%;">University</th>
                                                            <th style="width: 10%;">Reg. Number</th>
                                                            <th style="width: 10%;"><input type="checkbox" name="select_all" id="student_id_select_all"></th>
                                                        </thead>
                                                        <tbody class="students_table_body">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="7">Clear</button>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="6">Submit</button>
                            </div>
                            <!-- /.col -->
                        </div><br>
                    </form>
                </div>
                <!-- /.box primary -->
            </div>
        </div>
        <!-- /.row (main row) -->
        <div class="modal fade" id="address_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Add Address</h4>
                    </div>
                    <div class="modal-body" style="height: 400px;">
                        <h5 id="modal_info" style="color: red; text-align: center;"></h5>
                        <form id="modal_address_form" action="{{route('address.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
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
                                        <button type="button" class="btn btn-primary btn-block btn-flat" id="modal_form_submit" tabindex="5">Save</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/js/certificationRegistration.js?rndstr={{ rand(1000,9999) }}"></script>
@endsection