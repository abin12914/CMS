<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select address</option>
    @if(!empty($addressesCombo) && (count($addressesCombo) > 0))
        @foreach($addressesCombo as $address)
            <option value="{{ $address->id }}" {{ (old($selectName) == $address->id || $selectedAddressId == $address->id) ? 'selected' : '' }}>{{ $address->name }}</option>
        @endforeach
    @endif
</select>