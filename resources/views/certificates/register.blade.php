@extends('layouts.app')
@section('title', 'Certificate Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Certificate
            <small>Registration</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('certificate.index') }}"> Certificates</a></li>
            <li class="active">Registration</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row no-print">
            <div class="col-md-12">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <div class="box box-widget widget-user-2">
                        <div class="widget-user-header">
                            <div class="widget-user-image">
                                <img class="img-circle" src="/images/public/default_certificate.jpeg" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username text-capitalize">&emsp;Certificate Registration</h3>
                            <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('certificate.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <hr>
                                <h4 class="text-info">&emsp;&emsp;Certificate Info</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="name" class="control-label"><b style="color: red;">* </b> Name : </label>
                                                    <input type="text" name="name" class="form-control" id="name" placeholder="Certificate name" value="{{ old('name') }}" tabindex="2" maxlength="100">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="description" class="control-label"><b style="color: red;">* </b> Description : </label>
                                                    <input type="text" name="description" class="form-control" id="description" placeholder="Description" value="{{ old('description') }}" tabindex="2" maxlength="100">
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'description'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="authority_id" class="control-label"><b style="color: red;">* </b> Issuing authority : </label>
                                                    {{-- adding authority select component --}}
                                                    @component('components.selects.authorities', ['selectedAuthorityId' => old('authority_id'), 'selectName' => 'authority_id', 'activeFlag' => false, 'tabindex' => 7])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'authority_id'])
                                                    @endcomponent
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="certificate_type" class="control-label"><b style="color: red;">* </b> Type : </label>
                                                    {{-- adding certificate type select component --}}
                                                    @component('components.selects.certificate_type', ['selectedType' => old('certificate_type'), 'tabindex' => 7])
                                                    @endcomponent
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'certificate_type'])
                                                    @endcomponent
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h4 class="text-info">&emsp;&emsp;Certificate Content</h4>
                                <hr>
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <textarea id="certificate_content" name="certificate_content" rows="10" cols="80"></textarea>
                                                    {{-- adding error_message p tag component --}}
                                                    @component('components.paragraph.error_message', ['fieldName' => 'content'])
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
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="12">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="11">Submit</button>
                                    </div>
                                    <!-- /.col -->
                                </div><br>
                            </div>
                        </form>
                    </div>
                    <!-- /.box primary -->
                </div>
            </div>
            <!-- /.row (main row) -->
        </div>
    </section>
    <!-- /.content -->
</div>
@endsection
@section('scripts')
    <script src="/bower_components/ckeditor/ckeditor.js"></script>
    <script>
        $(function () {
            // Replace the <textarea id="certificate_content"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('certificate_content');

            CKEDITOR.on('dialogDefinition', function(event, placeholders) {
                if ('placeholder' == event.data.name) {
                    var input = event.data.definition.getContents('info').get('name');
                    input.type = 'select';
                    //input.items = [ ['Company'], ['Email'], ['First Name'], ['Last Name'] ];
                    input.items = [ ['CourseName'], ['DescriptiveName'], ['University'], ['CenterCode'], ['UniversityGrade'], ['CourseFrom'], ['CourseTo'], ['CourseFeeAmount'], ['CourseFeePerYear'], ['CourseFeePerSem'], ['CourseFeePerMonth'], ['StudentName'], ['StudentAddress'], ['StudentRegistrationNumber'] ];
                }
            });
        })
    </script>
@endsection