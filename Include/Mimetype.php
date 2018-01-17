<?php

class Mimetype {

    public function __construct($mimetypes)
    {
        if (!is_array($mimetypes)) {
            $mimetypes = array($mimetypes);
        }
        $this->mimetypes = $mimetypes;
    }

    public function validate($mimetype)
    {
        return in_array($mimetype, $this->mimetypes);
    }
}