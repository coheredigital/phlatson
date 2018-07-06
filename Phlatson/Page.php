<?php
namespace Phlatson;

class Page extends DataObject
{

    const BASE_FOLDER = 'pages/';
    const BASE_URL = '';

    protected $parent;
    protected $children;

    public function children() : PageCollection
    {
        $url = $this->url;
        $children = $this->children;

        // skip if already stored
        if ($children instanceof PageCollection) {
            return $children;
        }

        // create empty collection
        $children = new PageCollection();

        $folder_index = [];
        // $folder_index = Filemanager::getData($this->folder, "index");

        if (count($folder_index)) {
            $children->import($folder_index);
        } else {
            $index_array = [];
            $dir = new \FilesystemIterator($this->path);

            foreach ($dir as $file) {
                if ($file->isDir()) {
                    $name = $file->getFilename();
                    $url = "{$this->url}{$name}";
                    $index_array[] = $url;
                    $children->append($url);
                }
            }

            Filemanager::saveData($index_array, $this->folder, "index");
        }

        $this->children = $children;
        return $children;
    }

    public function child(string $name) : Page
    {
        $name = trim($name, "/");
        $path = "{$this->url}{$name}/";

        $page = new Page($path);
        return $page;
    }


}
