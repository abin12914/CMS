<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select university</option>
    @if(!empty($universitiesCombo) && (count($universitiesCombo) > 0))
        @foreach($universitiesCombo as $university)
            <option value="{{ $university->id }}" {{ (old($selectName) == $university->id || $selectedUniversityId == $university->id) ? 'selected' : '' }}>{{ $university->university_name }}</option>
        @endforeach
    @endif
</select>