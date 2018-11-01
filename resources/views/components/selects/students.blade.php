<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select student</option>
    @if(!empty($studentsCombo) && (count($studentsCombo) > 0))
        @foreach($studentsCombo as $student)
            @if($activeFlag && $student->status != 1)
                @continue
            @endif
            <option value="{{ $student->id }}" {{ (old($selectName) == $student->id || $selectedStudentId == $student->id) ? 'selected' : '' }}>{{ $student->name  }}</option>
        @endforeach
    @endif
</select>