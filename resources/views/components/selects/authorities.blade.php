<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select authority</option>
    @if(!empty($authoritiesCombo) && (count($authoritiesCombo) > 0))
        @foreach($authoritiesCombo as $authority)
            <option value="{{ $authority->id }}" {{ (old($selectName) == $authority->id || $selectedAuthorityId == $authority->id) ? 'selected' : '' }}>{{ $authority->name }} - {{ $authority->designation }}</option>
        @endforeach
    @endif
</select>