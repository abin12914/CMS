<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select batch</option>
    @if(!empty($batchesCombo) && (count($batchesCombo) > 0))
        @foreach($batchesCombo as $batch)
            <option value="{{ $batch->id }}" {{ (old($selectName) == $batch->id || $selectedBatchId == $batch->id) ? 'selected' : '' }} data-duration_type="">{{ $batch->batch_name }} [{{ $batch->course->course_name }} : {{ $batch->from_year }}-{{ $batch->to_year }}]</option>
        @endforeach
    @endif
</select>