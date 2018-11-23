<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select address</option>
    @if($activeFlag)
        <option value="-1">To whomever it may concern</option>
    @endif
    @if(!empty($addressesCombo) && (count($addressesCombo) > 0))
        @foreach($addressesCombo as $address)
            <option value="{{ $address->id }}" {{ (old($selectName) == $address->id || $selectedAddressId == $address->id) ? 'selected' : '' }}>{{ $address->name }} - {{ $address->designation }}, {{ $address->address }}</option>
        @endforeach
    @endif
</select>