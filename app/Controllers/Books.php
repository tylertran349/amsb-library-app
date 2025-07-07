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

    public function new() 
    {
        return view('books/create');
    }

    public function create() 
    {
        // Get the form data
        $postData = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'genre' => $this->request->getPost('genre'),
            'publication_year' => $this->request->getPost('publication_year'),
        ];
        $bookModel = new BookModel(); // Instantiate the model

        // Save the data
        if ($bookModel->save($postData)) {
            return redirect()->to('/')->with('message', 'Book added successfully!'); // Set a success message and redirect
        } else {
            return redirect()->back()->with('error', 'Failed to add book.'); // If save fails, redirect back with an error
        }
    }

    public function edit($id = null)
    {
        $bookModel = new BookModel();
        $data['book'] = $bookModel->find($id); // Fetch the book data from the model

        if (empty($data['book'])) { // If no book is found, show a 404 error
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cannot find the book with ID: ' . $id);
        }
        return view('books/edit', $data); // Load the edit form view, passing the book data
    }

    public function update($id = null)
    {
        $postData = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'genre' => $this->request->getPost('genre'),
            'publication_year' => $this->request->getPost('publication_year'),
        ];

        $bookModel = new BookModel();

        // The `update` method needs the ID as the first parameter
        if ($bookModel->update($id, $postData)) {
            return redirect()->to('/')->with('message', 'Book updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update book.');
        }
    }

    public function delete($id = null)
    {
        $bookModel = new BookModel();

        if ($bookModel->delete($id)) {
            return redirect()->to('/')->with('message', 'Book deleted successfully!');
        } else {
            return redirect()->to('/')->with('error', 'Failed to delete book.');
        }
    }
}
