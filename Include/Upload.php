<?php

use Upload\ {
    File, Storage\FileSystem, Validation\Size as Size
};

class Upload {
    protected $file;
    protected $error;

    function __construct(array $config) {
        if(!file_exists($config['path'])) {
            mkdir($config['path'], 0775, true);
        }
        $storage = new FileSystem($config['path']);
        $this->file = new File($config['key'], $storage);
        $new_filename = uniqid();
        $this->file->setName($new_filename);
        if(!empty($config['size'])) {
            $this->file->addValidations(array(
                // Ensure file is no larger than 5M (use "B", "K", M", or "G")
                new Size($config['size'])
            ));
        } else {
            exit(json_encode("Invalid file size"));
        }
        if(!empty($config['mimetype'])) {
            $mimetype = new Mimetype($config['mimetype']);
            if (!$mimetype->validate($_FILES[$config['key']]['type'])) {
                exit(json_encode("Invalid mimetype: {$_FILES[$config['key']]['type']}"));
            }
        }
    }

    function upload() {
        return $this->file->upload();
    }

    function getErrors() {
        $errors = $this->file->getErrors();
        $errors = [$this->error,$errors];
        return $errors;
    }

    function getFileInfo() {
        return [
            'name'       => $this->file->getNameWithExtension(),
            'extension'  => $this->file->getExtension(),
            'mime'       => $this->file->getMimetype(),
            'size'       => $this->file->getSize(),
            'md5'        => $this->file->getMd5(),
            'dimensions' => $this->file->getDimensions()
        ];
    }
}