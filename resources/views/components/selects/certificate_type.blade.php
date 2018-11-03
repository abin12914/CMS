<select class="form-control select2" name="certificate_type" id="certificate_type" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old('certificate_type')) ? 'selected' : '' }}>Select status</option>
    <option value="1" {{ old('certificate_type') == '1' ? 'selected' : '' }}>For single student</option>
    <option value="2" {{ old('certificate_type') == '2' ? 'selected' : '' }}>For group of students</option>
</select>