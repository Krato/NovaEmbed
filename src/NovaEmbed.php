<?php

namespace EricLagarda\NovaEmbed;

use EricLagarda\NovaEmbed\Invokes\NovaEmbedData;
use Laravel\Nova\Fields\Field;

class Embed extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-embed';

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  mixed|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta(['ajax' => false]);
        $this->withMeta(['viewInIndex' => false]);
    }

    /**
     * Set the option to load data with ajax instead in the construct
     *
     * @return  $this
     */
    public function ajax()
    {
        return $this->withMeta(['ajax' => true]);
    }

    /**
     * Set the cache time
     *
     * @param $time
     */
    public function cache($time)
    {
        return $this->withMeta(['time' => $time]);
    }

    /**
     * Set the cache time
     *
     * @param $time
     */
    public function viewInIndex()
    {
        return $this->withMeta(['viewInIndex' => true]);
    }

    /**
     * Specify the callback that should be used to retrieve the preview URL.
     *
     * @param  callable  $previewUrlCallback
     * @return $this
     */
    public function embed()
    {
        $data = (new NovaEmbedData())(request(), $this->value, null, ($this->meta['time']) ?? null);

        return $data ? $data : null;
    }

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    public function meta()
    {
        return array_merge([
            'embed' => ($this->meta['ajax'] == false) ? $this->embed() : null,
        ], $this->meta);
    }
}
