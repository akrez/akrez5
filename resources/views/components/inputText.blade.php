<div class="form-group">
    <label class="form-label">{{ $label }}</label>
    <input type="text"
        class="form-control {{ $errors->any() ? ($errors->has($name) ? 'is-invalid' : 'is-valid') : '' }}"
        name="{{ $name }}" value="{{ $value }}">
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
