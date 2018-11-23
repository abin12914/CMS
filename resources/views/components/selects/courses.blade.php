<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select course</option>
    @if(!empty($coursesCombo) && (count($coursesCombo) > 0))
        @foreach($coursesCombo as $course)
            <option value="{{ $course->id }}" {{ (old($selectName) == $course->id || $selectedCourseId == $course->id) ? 'selected' : '' }} data-duration_type="">{{ $course->course_name }} - {{ $course->university->university_name }}</option>
        @endforeach
    @endif
</select>