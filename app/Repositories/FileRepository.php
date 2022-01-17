<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository
{
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    public function create(array $data) : File
    {
        return $this->file->create($data);
    }
}
