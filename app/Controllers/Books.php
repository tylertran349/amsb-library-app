<?php

namespace App\Controllers;

use App\Models\BookModel;

class Books extends BaseController
{
    public function index() 
    {
        $bookModel = new BookModel();
        $data['books'] = $bookModel->orderBy('id', 'DESC')->findAll(); // Order by descending ID to show newest books first
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
            'publication_year' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[' . date('Y') . ']' // Publication year should be between (and including) 0 and the current year
        ];

        $rules['cover_image'] = 'if_exist|uploaded[cover_image]|max_size[cover_image,2048]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png,image/webp]'; // Image validation rules

        $messages = [
            'publication_year' => [
                'greater_than_equal_to' => 'The publication year cannot be a negative number.',
                'less_than_equal_to'    => 'The publication year cannot be in the future.',
                'required' => 'The publication year field is required.',
            ]
        ];

        // Validate the input
        if (! $this->validate($rules, $messages)) { // If validation fails, redirect back to the form with the errors
            return redirect()->to('/books/new')->withInput()->with('errors', $this->validator->getErrors());
        }

        // If validation passes, proceed with saving the data
        $postData = [
            'title' => $this->request->getPost('title'),
            'author' => $this->request->getPost('author'),
            'genre' => $this->request->getPost('genre'),
            'publication_year' => $this->request->getPost('publication_year'),
        ];

        // Handle the file upload
        $img = $this->request->getFile('cover_image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads', $newName);
            $postData['cover_image'] = $newName;
        }
        
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
        $rules = [
            'title'            => 'required|max_length[255]',
            'author'           => 'required|max_length[255]',
            'publication_year' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[' . date('Y') . ']'
        ];

        $imageRules = [
            'cover_image' => 'if_exist|uploaded[cover_image]|max_size[cover_image,2048]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png,image/webp]'
        ];

        $messages = [
            'publication_year' => [
                'greater_than_equal_to' => 'The publication year cannot be a negative number.',
                'less_than_equal_to'    => 'The publication year cannot be in the future.',
                'required' => 'The publication year field is required.',
            ]
        ];

        if (! $this->validate(array_merge($rules, $imageRules), $messages)) {
            return redirect()->to('/books/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $bookModel = new BookModel();
        $postData = $this->request->getPost();
        
        $img = $this->request->getFile('cover_image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            // Get old image name to delete it after the new one is uploaded
            $oldBook = $bookModel->find($id);
            $oldImage = $oldBook['cover_image'];
            
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads', $newName);
            $postData['cover_image'] = $newName;

            if ($oldImage && file_exists(FCPATH . 'uploads/' . $oldImage)) {
                unlink(FCPATH . 'uploads/' . $oldImage);
            }
        }

        if ($bookModel->update($id, $postData)) {
            return redirect()->to('/')->with('message', 'Book updated successfully!');
        } else {
            return redirect()->to('/books/edit/' . $id)->withInput()->with('error', 'Failed to update book.');
        }
    }

    public function delete($id = null)
    {
        $bookModel = new BookModel();
        
        // First, get the book record to find the image filename
        $book = $bookModel->find($id);
        
        if ($book) {
            $imageFile = $book['cover_image']; // Get the image filename
            
            // Now, delete the database record
            if ($bookModel->delete($id)) {
                if ($imageFile && file_exists(FCPATH . 'uploads/' . $imageFile)) { // If the database record was deleted successfully, delete the image file
                    unlink(FCPATH . 'uploads/' . $imageFile);
                }
                return redirect()->to('/')->with('message', 'Book deleted successfully!');
            }
        }
        return redirect()->to('/')->with('error', 'Failed to delete book.'); // If something went wrong
    }
}
