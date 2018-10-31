@extends('layouts.app')
@section('title', 'Student Registration')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Student
            <small>Registration</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('student.index') }}"> Students</a></li>
            <li class="active">Registration</li>
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
                                <img class="img-circle" src="/images/public/default_student.jpg" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
                            <h3 class="widget-user-username text-capitalize">&emsp;Student Registration</h3>
                            <div class="widget-user-desc">&nbsp;&nbsp;&nbsp;&emsp; Fields marked with <i class="text-red">* </i> are mandatory.
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="{{route('student.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" autocomplete="off">
                            <div class="box-body">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="row">
                                    <div class="col-md-11">
                                        <hr>
                                        <h4 class="text-info">&emsp;&emsp;Personal Info</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="name" class="col-md-3 control-label"><b style="color: red;">* </b> Name : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="name" class="form-control alpha_only" id="name" placeholder="Student name" value="{{ old('name') }}" tabindex="1" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'name'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="address" class="col-md-3 control-label">Address : </label>
                                            <div class="col-md-9">
                                                @if(!empty(old('address')))
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="2" maxlength="200">{{ old('address') }}</textarea>
                                                @else
                                                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" style="resize: none;" tabindex="2" maxlength="200"></textarea>
                                                @endif
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'address'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="phone" class="col-md-3 control-label">Phone : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="phone" class="form-control number_only" id="phone" placeholder="Phone number" value="{{ old('phone') }}" tabindex="3" minlength="10" maxlength="13">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'phone'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="gender" class="col-md-3 control-label"><b style="color: red;">* </b> Gender : </label>
                                            <div class="col-md-9">
                                                <select class="form-control select2" name="gender" id="gender" tabindex="4" style="width: 100%;">
                                                    <option value="" {{ empty(old('gender')) ? 'selected' : '' }}>Select gender</option>
                                                    @if(!empty($genderTypes))
                                                        @foreach($genderTypes as $key => $genderType)
                                                            <option value="{{ $key }}" {{ (old('gender') == $key) ? 'selected' : '' }}>
                                                                {{ $genderType }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'gender'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="title" class="col-md-3 control-label"><b style="color: red;">* </b> Title : </label>
                                            <div class="col-md-9">
                                                <input type="text" title="title" class="form-control" id="title" placeholder="Addressing title" value="{{ old('title') }}" tabindex="5" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'title'])
                                                @endcomponent
                                            </div>
                                        </div><br>
                                        <hr>
                                        <h4 class="text-info">&emsp;&emsp;Academic Info</h4>
                                        <hr>
                                        <div class="form-group">
                                            <label for="course" class="col-md-3 control-label"><b style="color: red;">* </b> Course : </label>
                                            <div class="col-md-9">
                                                {{-- adding course select component --}}
                                                @component('components.selects.courses', ['selectedCourseId' => old('course_id'), 'selectName' => 'course_id', 'activeFlag' => false, 'tabindex' => 6])
                                                @endcomponent
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'course_id'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="year" class="col-md-3 control-label"><b style="color: red;">* </b> Year : </label>
                                            <div class="col-md-4">
                                                <input type="text" name="year" class="form-control alpha_only" id="year" placeholder="Starting Year" value="{{ old('year') }}" tabindex="7" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'year'])
                                                @endcomponent
                                            </div>
                                            <label for="year" class="col-md-1 control-label">To : </label>
                                            <div class="col-md-4">
                                                <input type="text" name="year" class="form-control alpha_only" id="year" placeholder="Ending Year" value="{{ old('year') }}" tabindex="7" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'year'])
                                                @endcomponent
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="year" class="col-md-3 control-label"><b style="color: red;">* </b> Fee : </label>
                                            <div class="col-md-9">
                                                <input type="text" name="year" class="form-control alpha_only" id="year" placeholder="Fee per Year" value="{{ old('year') }}" tabindex="8" maxlength="100">
                                                {{-- adding error_message p tag component --}}
                                                @component('components.paragraph.error_message', ['fieldName' => 'year'])
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
                                        <button type="reset" class="btn btn-default btn-block btn-flat" tabindex="10">Clear</button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="9">Submit</button>
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
