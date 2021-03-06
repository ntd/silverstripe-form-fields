<?php

namespace Axllent\FormFields\Forms;

use Axllent\FormFields\FieldType\VideoLink;

class VideoLinkField extends URLField
{

    /**
     * @defaults
     */
    protected $display_video = false;

    protected $preview_height = false;

    /**
     * Return field attributes
     *
     * @param  Null
     * @return Array
     */
    public function getAttributes()
    {
        $attributes = [
            'class'       => 'text',
            'placeholder' => _t(__CLASS__ . '.Placeholder', 'Enter a valid YouTube or Vimeo link'),
        ];

        return array_merge(
            parent::getAttributes(),
            $attributes
        );
    }

    /**
     * Display a video preview
     *
     * @param  Varchar width
     * @param  Varchar height
     * @return VideoLinkField
     */
    public function showPreview($maxwidth = 500, $height = '56%')
    {
        $this->display_video = $maxwidth;
        $this->preview_height = $height;
        return $this;
    }

    /**
     * Return video preview
     *
     * @param  Null
     * @return VideoLink
     */
    public function getPreview()
    {
        $url = trim($this->value);
        if (!$this->display_video || !$url) {
            return false;
        }
        $obj = VideoLink::create()->setValue($url);
        if ($obj->iFrameURL) {
            return $obj->Iframe($this->display_video, $this->preview_height);
        }
    }

    /**
     * Return video title
     *
     * @param  Null
     * @return String
     */
    public function getVideoTitle()
    {
        $url = trim($this->value);
        return VideoLink::create()->setValue($url)->Title;
    }

    /**
     * Return validation result
     *
     * @param  Validator $validator
     * @return Boolean
     */
    public function validate($validator)
    {
        parent::validate($validator);

        // Don't validate empty fields
        if (empty($this->value)) {
            return true;
        }

        // Use the VideoLink object to validate
        $obj = VideoLink::create()->setValue($this->value);

        if (!$obj->getService()) {
            $validator->validationError(
                $this->name,
                _t(__CLASS__ . '.ValidationError', 'Please enter a valid YouTube or Vimeo link'),
                'validation'
            );
            return false;
        }

        return true;
    }
}
