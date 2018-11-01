<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select batch</option>
    @if(!empty($batchesCombo) && (count($batchesCombo) > 0))
        @foreach($batchesCombo as $batch)
            <option value="{{ $batch->id }}" {{ (old($selectName) == $batch->id || $selectedCourseId == $batch->id) ? 'selected' : '' }} data-duration_type="">{{ $batch->batch_name }} - {{ $batch->university }}</option>
        @endforeach
    @endif
</select>