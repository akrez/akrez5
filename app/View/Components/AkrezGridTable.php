<?php

namespace App\View\Components;

use Illuminate\View\ComponentAttributeBag;

class AkrezGridTable extends AkrezBaseGridTable
{
    public function newFieldColumn($field, $header = null)
    {
        $this->newColumn();

        if (null === $header) {
            $this->header('{{ $headerString }}');
            $this->headerData(['headerString' => __('validation.attributes.' . $field)]);
        }

        $this->content('
        @if (is_object($model) and isset($model->{$field}))
            {{ $model->{$field} }}
        @elseif (is_array($model) and isset($model[$field]))
            {{ $model[$field] }}
        @endif
        ');
        $this->contentData(function ($model, $data, $grid) use ($field) {
            return [
                'field' => $field,
            ];
        });

        return $this;
    }

    public function newRawColumn($content, $data = [], $header = '')
    {
        $this->newColumn();

        $this->header('{{ $headerString }}', ['headerString' => $header]);
        $this->headerData(['headerString' => $header]);

        $this->content($content);
        $this->contentData($data);

        return $this;
    }

    public function newTagColumn($tag, $content, $attributes, $header = '')
    {
        $this->newColumn();

        $this->header('{{ $headerString }}');
        $this->headerData(['headerString' => $header]);

        $this->content('<{{ $tag }} {{ $attributes }}>' . $content . '</{{ $tag }}>');
        if ($attributes instanceof \Closure) {
            $this->contentData(function ($model, $data, $grid) use ($tag, $content, $attributes) {
                return [
                    'tag' => $tag,
                    'content' => $content,
                    'attributes' => new ComponentAttributeBag($attributes($model, $data, $grid)),
                ];
            });
        } else {
            $this->contentData($attributes);
        }

        return $this;
    }
}
