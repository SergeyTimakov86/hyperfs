@props(['name', 'label' => '', 'value' => '', 'vo' => null, 'minLength' => null, 'maxLength' => null, 'pattern' => null])
@php
    if ($vo !== null) {
        $minLength = $vo::minLength();
        $maxLength = $vo::maxLength();
        $pattern = $vo::pattern();
    }
@endphp
<div class="mb-3">
    @if($label)
        <label class="form-label">{{ $label }}</label>
    @endif
    <input type="text" class="form-control" name="{{ $name }}" value="{{ $value }}"
           @if($minLength !== null) minlength="{{ $minLength }}" @endif
           @if($maxLength !== null) maxlength="{{ $maxLength }}" @endif
           @if($pattern !== null) pattern="{{ $pattern }}" @endif
           {{ $attributes }}>
</div>
