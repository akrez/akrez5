<label class="form-label">{{ $label }}</label>
<textarea class="form-control {{ $errors->any() ? ($errors->has($name) ? 'is-invalid' : 'is-valid') : '' }}"
    name="{{ $name }}">{{ $value }}</textarea>
@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
