<?php

namespace Fau\DegreeProgram\Shares;

class Settings {

    protected $pluginFile;

    public function __construct($pluginFile) {
        $this->pluginFile = $pluginFile;
    }

    public function onLoaded() {

    }

}