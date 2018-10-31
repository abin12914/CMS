<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select student</option>
    @if(!empty($nonAccountFlag))
        <option value="-1" {{ old($selectName) == '-1' ? 'selected' : '' }}>New credit Account</option>
    @endif
    @if(!empty($studentsCombo) && (count($studentsCombo) > 0))
        @foreach($studentsCombo as $student)
            @if(!$cashAccountFlag && $student->type != 3) {{-- type != 3 means not personal student --}}
                @continue
            @endif
            @if($activeFlag && $student->status != 1)
                @continue
            @endif
            <option value="{{ $student->id }}" {{ (old($selectName) == $student->id || $selectedAccountId == $student->id) ? 'selected' : '' }} data-phone="{{ $student->phone }}">
                {{ $student->student_name. ((($student->status != 1 || config('settings.display_phone_flag')) && $student->type == 3) ? ' - '. $student->phone : '')  }}
            </option>
        @endforeach
    @endif
</select>