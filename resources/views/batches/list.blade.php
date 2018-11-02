@extends('layouts.app')
@section('title', 'Batch List')
@section('content')
<div class="content-wrapper">
     <section class="content-header">
        <h1>
            Batch
            <small>List</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a> Batches</a></li>
            <li class="active"> List</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Main row -->
        <div class="row  no-print">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Filter List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-header">
                        <form action="{{ route('batch.index') }}" method="get" class="form-horizontal" autocomplete="off">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <div class="col-md-3">
                                            <label for="batch_id" class="control-label">Batch : </label>
                                            {{-- adding course select component --}}
                                            @component('components.selects.batches', ['selectedBatchId' => old('batch_id'), 'selectName' => 'batch_id', 'activeFlag' => false, 'tabindex' => 1])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'batch_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-3">
                                            <label for="course_id" class="control-label">Course : </label>
                                            {{-- adding batch select component --}}
                                            @component('components.selects.courses', ['selectedCourseId' => $params['course_id'], 'cashCourseFlag' => false, 'selectName' => 'course_id', 'activeFlag' => false, 'tabindex' => 2])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'course_id'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-2">
                                            <label for="batch_id" class="control-label">From : </label>
                                            {{-- adding course select component --}}
                                            <input type="text" name="from_year" class="form-control number_only" id="from_year" placeholder="From year" value="{{ old('from_year') }}" tabindex="3" minlength="4" maxlength="4">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'from_year'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-2">
                                            <label for="course_id" class="control-label">To : </label>
                                            <input type="text" name="to_year" class="form-control number_only" id="to_year" placeholder="To year" value="{{ old('to_year') }}" tabindex="4" minlength="4" maxlength="4">
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'to_year'])
                                            @endcomponent
                                        </div>
                                        <div class="col-md-2">
                                            <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                                            {{-- adding no of records text component --}}
                                            @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 5])
                                            @endcomponent
                                            {{-- adding error_message p tag component --}}
                                            @component('components.paragraph.error_message', ['fieldName' => 'no_of_records'])
                                            @endcomponent
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div><br>
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="7">Clear</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex="6"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </div>
                        </form>
                        <!-- /.form end -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    {{-- page header for printers --}}
                    @include('sections.print-head')
                    <div class="box-header no-print">
                        @if(!empty($params['relation']) || !empty($params['id']))
                            <b>Filters applied!</b>
                        @endif
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th style="width: 20%;">Batch Name</th>
                                            <th style="width: 25%;">Course</th>
                                            <th style="width: 15%;">From</th>
                                            <th style="width: 15%;">To</th>
                                            <th style="width: 10%;">Fee</th>
                                            <th style="width: 10%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($batches))
                                            @foreach($batches as $index => $batch)
                                                <tr>
                                                    <td>{{ $index + $batches->firstItem() }}</td>
                                                    <td>{{ $batch->batch_name }}</td>
                                                    <td>{{ $batch->course->course_name }}-{{ $batch->course->university }}</td>
                                                    <td>{{ $batch->from_year }}</td>
                                                    <td>{{ $batch->to_year }}</td>
                                                    <td>{{ $batch->fee_amount }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($batches))
                                    <div>
                                        Showing {{ $batches->firstItem(). " - ". $batches->lastItem(). " of ". $batches->total() }}
                                    </div>
                                    <div class=" no-print pull-right">
                                        {{ $batches->appends(Request::all())->links() }}
                                    </div>
                                @endif
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