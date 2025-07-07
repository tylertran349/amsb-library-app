<?php

namespace App\Controllers;

use App\Models\BookModel;

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
        // Define validation rules
        $rules = [
            'title'            => 'required|max_length[255]',
            'author'           => 'required|max_length[255]',
            'publication_year' => 'required|exact_length[4]|numeric'
        ];

        // Validate the input
        if (! $this->validate($rules)) {
            // If validation fails, redirect back to the form with the errors
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // If validation passes, proceed with saving the data
        $postData = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'genre' => $this->request->getPost('genre'),
            'publication_year' => $this->request->getPost('publication_year'),
        ];
        
        $bookModel = new BookModel();

        if ($bookModel->save($postData)) {
            return redirect()->to('/')->with('message', 'Book added successfully!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add book.');
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
        // Define validation rules
        $rules = [
            'title'            => 'required|max_length[255]',
            'author'           => 'required|max_length[255]',
            'publication_year' => 'required|exact_length[4]|numeric'
        ];

        if (! $this->validate($rules)) { // If validation fails, redirect back to the 'edit' form with errors
            return redirect()->to('/books/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        // If validation passes, get the POST data
        $postData = [
            'title'            => $this->request->getPost('title'),
            'author'           => $this->request->getPost('author'),
            'genre'            => $this->request->getPost('genre'),
            'publication_year' => $this->request->getPost('publication_year'),
        ];

        $bookModel = new BookModel();

        if ($bookModel->update($id, $postData)) {
            return redirect()->to('/')->with('message', 'Book updated successfully!');
        } else {
            return redirect()->to('/books/edit/' . $id)->withInput()->with('error', 'Failed to update book.');
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
