<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select certificate</option>
    @if(!empty($certificatesCombo) && (count($certificatesCombo) > 0))
        @foreach($certificatesCombo as $certificate)
            <option value="{{ $certificate->id }}" {{ (old($selectName) == $certificate->id || $selectedCertificateId == $certificate->id) ? 'selected' : '' }}>{{ $certificate->name }}</option>
        @endforeach
    @endif
</select>