<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select course</option>
    @if(!empty($coursesCombo) && (count($coursesCombo) > 0))
        @foreach($coursesCombo as $course)
            <option value="{{ $course->id }}" {{ (old($selectName) == $course->id || $selectedCourseId == $course->id) ? 'selected' : '' }}>{{ $course->course_name }} - {{ $course->university }}</option>
        @endforeach
    @endif
</select>