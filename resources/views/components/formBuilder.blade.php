@php
    $inputValue = old($name, isset($value) ? $value : '');
    
    if (!isset($type)) {
        $type = 'text';
    }
    
    if (!isset($label)) {
        $label = __('validation.attributes.' . $name);
    }
    
    if (!isset($id)) {
        $id = crc32($label . $name);
    }
    
    if (!isset($row)) {
        $row = true;
    }
    
    if (!isset($mt)) {
        $mt = 2;
    }
    
    if (!isset($size)) {
        $size = 4;
    }
    
    if (!isset($textareaRows)) {
        $textareaRows = 4;
    }
    
    if (!isset($visible)) {
        $visible = true;
    }
    
    if (!isset($errorsArray)) {
        $errorsArray = $errors->get($name);
    }
    
    if (!isset($class)) {
        $class = 'form-control';
    }
    $inputClass = [$class];
    if ($errorsArray) {
        $inputClass[] = 'is-invalid';
    }
@endphp

@if ($visible)
    @if ($row)
        <div class="row">
    @endif
    <div class="col-md-{{ $size }} mt-{{ $mt }}">
        @if ('textarea' == $type)
            <div class="form-group">
                @if ($label)
                    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
                @endif
                <textarea name="{{ $name }}" id="{{ $id }}" class="{{ implode(' ', $inputClass) }}"
                    rows="{{ $textareaRows }}">{{ $inputValue }}</textarea>
                @foreach ($errorsArray as $error)
                    <div class="invalid-feedback">{{ $error }}</div>
                @endforeach
            </div>
        @elseif ('file' == $type)
            <div class="form-group">
                @if ($label)
                    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
                @endif
                <input name="{{ $name }}" type="{{ $type }}" id="{{ $id }}"
                    class="{{ implode(' ', $inputClass) }}" value="{{ $inputValue }}" />
                @foreach ($errorsArray as $error)
                    <div class="invalid-feedback">{{ $error }}</div>
                @endforeach
            </div>
        @else
            <div class="form-group">
                @if ($label)
                    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
                @endif
                <input name="{{ $name }}" type="{{ $type }}" id="{{ $id }}"
                    class="{{ implode(' ', $inputClass) }}" value="{{ $inputValue }}" />
                @foreach ($errorsArray as $error)
                    <div class="invalid-feedback">{{ $error }}</div>
                @endforeach
            </div>
        @endif
    </div>
    @if ($row)
        </div>
    @endif
@endif
