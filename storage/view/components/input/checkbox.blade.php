@props(['name', 'label' => '', 'checked' => false])
<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" name="{{ $name }}" {{ $checked ? 'checked' : '' }} {{ $attributes }}>
    @if($label)
        <label class="form-check-label">{{ $label }}</label>
    @endif
</div>
