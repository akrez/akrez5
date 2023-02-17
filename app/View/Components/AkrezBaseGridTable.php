<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Container\Container;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\View\ComponentAttributeBag;

class AkrezBaseGridTable extends Component
{
    const PART_HEADER_STRING = 'header';
    const PART_HEADER_DATA = 'headerData';

    const PART_CONTENT_STRING = 'contentString';
    const PART_CONTENT_DATA = 'contentData';
    const PART_CONTENT_ATTRIBUTES = 'contentAttributes';

    protected $index = 0;

    public $models = [];
    public $columns = [];

    protected function newColumn()
    {
        $this->index = $this->index + 1;
        return $this;
    }

    protected function setPart($part, $data)
    {
        $this->columns[$this->index][$part] = $data;
        return $this;
    }

    protected function header($string)
    {
        $this->setPart(static::PART_HEADER_STRING, $string);
        return $this;
    }

    protected function headerData($data)
    {
        $this->setPart(static::PART_HEADER_DATA, $data);
        return $this;
    }

    protected function content($string)
    {
        $this->setPart(static::PART_CONTENT_STRING, $string);
        return $this;
    }

    protected function contentData($data)
    {
        $this->setPart(static::PART_CONTENT_DATA, $data);
        return $this;
    }

    public function contentAttributes($attributes)
    {
        $this->setPart(static::PART_CONTENT_ATTRIBUTES, $attributes);
        return $this;
    }

    public function renderHeader($column, $model, $index, $modelKey, $columnKey)
    {
        if (empty($column[static::PART_HEADER_DATA])) {
            $data = [];
        } elseif ($column[static::PART_HEADER_DATA] instanceof \Closure) {
            $data = $column[static::PART_HEADER_DATA]($model, compact('columnKey', 'index', 'modelKey'), $this);
        } else {
            $data = $column[static::PART_HEADER_DATA];
        }

        if (isset($column[static::PART_HEADER_STRING])) {
            return $this->blade($column[static::PART_HEADER_STRING], $data + [
                'model' => $model,
                'data' => compact('columnKey', 'index', 'modelKey'),
                'grid' => $this,
            ]);
        }
        return '';
    }

    public function renderContent($column, $model, $index, $modelKey, $columnKey)
    {
        if (empty($column[static::PART_CONTENT_DATA])) {
            $data = [];
        } elseif ($column[static::PART_CONTENT_DATA] instanceof \Closure) {
            $data = $column[static::PART_CONTENT_DATA]($model, compact('columnKey', 'index', 'modelKey'), $this);
        } else {
            $data = $column[static::PART_CONTENT_DATA];
        }

        if (isset($column[static::PART_CONTENT_STRING])) {
            return $this->blade($column[static::PART_CONTENT_STRING], $data + [
                'model' => $model,
                'data' => compact('columnKey', 'index', 'modelKey'),
                'grid' => $this,
            ]);
        }

        return '';
    }

    public function renderContentAttributes($column, $model, $index, $modelKey, $columnKey)
    {
        if (empty($column[static::PART_CONTENT_ATTRIBUTES])) {
            $contentAttributes = null;
        } elseif ($column[static::PART_CONTENT_ATTRIBUTES] instanceof \Closure) {
            $contentAttributes = $column[static::PART_CONTENT_ATTRIBUTES]($model, compact('columnKey', 'index', 'modelKey'), $this);
        } else {
            $contentAttributes = $column[static::PART_CONTENT_ATTRIBUTES];
        }

        if ($contentAttributes) {
            return new ComponentAttributeBag($contentAttributes);
        }
        return null;
    }

    protected function __construct($models)
    {
        $this->models = $models;
    }

    public static function build($models)
    {
        return new static($models);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.gridTable', ['gridTable' => $this]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Evaluate and render a Blade string to HTML.
     *
     * @param  string  $string
     * @param  array  $data
     * @param  bool  $deleteCachedView
     * @return string
     */
    protected static function blade($string, $data = [], $deleteCachedView = false)
    {
        $component = new class($string) extends Component
        {
            protected $template;

            public function __construct($template)
            {
                $this->template = $template;
            }

            public function render()
            {
                return $this->template;
            }
        };

        $view = Container::getInstance()
            ->make(ViewFactory::class)
            ->make($component->resolveView(), $data);

        return tap($view->render(), function () use ($view, $deleteCachedView) {
            if ($deleteCachedView) {
                unlink($view->getPath());
            }
        });
    }
}
