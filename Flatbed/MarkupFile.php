<?php
namespace Flatbed;


class MarkupFile
{

    protected $file;
    protected $savedDir;

    public function __construct($file)
    {
        if (is_file($file)) {
            $this->file = $file;
        }
    }

    public function render()
    {

        if (!$this->file || !is_file($this->file)) {
            return '';
        }

        $this->savedDir = getcwd();

        chdir(dirname($this->file));

        extract($this->api()); // make api variables accessible

        ob_start();
        require $this->file;
        $output = "\n" . ob_get_contents() . "\n";
        ob_end_clean();

        if ($this->savedDir) {
            chdir($this->savedDir);
        }

        return trim($output);
    }

} 