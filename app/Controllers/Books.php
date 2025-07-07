<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookModel;
use CodeIgniter\HTTP\ResponseInterface;

class Books extends BaseController
{
    public function index()
    {
        $bookModel = new BookModel();
        $data['books'] = $bookModel->findAll();
        return view('books/index', $data);
    }
}
