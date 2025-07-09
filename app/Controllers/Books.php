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

        $rules['cover_image'] = 'if_exist|uploaded[cover_image]|max_size[cover_image,4096]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png,image/webp]'; // Image validation rules

        $messages = [
            'publication_year' => [
                'greater_than_equal_to' => 'The publication year cannot be a negative number.',
                'less_than_equal_to'    => 'The publication year cannot be in the future.',
                'max_size'   => 'The image cannot be larger than 4MB.',
                'required' => 'The publication year field is required.',
            ],
            'cover_image' => [
                'is_image'   => 'The uploaded file is not a valid image.',
                'mime_in'    => 'Please upload a valid image type (jpg, jpeg, png, webp).',
            ]
        ];
        $img = $this->request->getFile('cover_image');
        if ($img && $img->isValid()) {
            $rules['cover_image'] = 'is_image[cover_image]|max_size[cover_image,4096]|mime_in[cover_image,image/jpg,image/jpeg,image/png,image/webp]';
        }

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
        // Define base validation rules 
        $rules = [
            'title'            => 'required|max_length[255]',
            'author'           => 'required|max_length[255]',
            'genre'            => 'permit_empty|max_length[100]',
            'publication_year' => 'required|integer|less_than_equal_to[' . date('Y') . ']'
        ];

        // Define custom error messages
        $messages = [
            'publication_year' => [
                'less_than_equal_to' => 'The publication year cannot be in the future.',
                'required' => 'The publication year field is required.',
            ],
            'cover_image' => [
                'is_image'   => 'The uploaded file is not a valid image.',
                'max_size'   => 'The image cannot be larger than 4MB.',
                'mime_in'    => 'Please upload a valid image type (jpg, jpeg, png, webp).',
            ]
        ];

        // Conditionally add image rules only if a new file is uploaded
        $img = $this->request->getFile('cover_image');
        if ($img && $img->isValid()) {
            $rules['cover_image'] = 'is_image[cover_image]|max_size[cover_image,4096]|mime_in[cover_image,image/jpg,image/jpeg,image/png,image/webp]';
        }

        // Run validation
        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validation passed
        $bookModel = new BookModel();
        $postData = [
            'title'            => $this->request->getPost('title'),
            'author'           => $this->request->getPost('author'),
            'genre'            => $this->request->getPost('genre'),
            'publication_year' => $this->request->getPost('publication_year'),
        ];

        // Handle the new file upload if it exists
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $oldBook = $bookModel->find($id);
            $oldImage = $oldBook['cover_image'];
            
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads', $newName);
            $postData['cover_image'] = $newName;

            // Delete the old image file if it exists
            if ($oldImage && file_exists(FCPATH . 'uploads/' . $oldImage)) {
                unlink(FCPATH . 'uploads/' . $oldImage);
            }
        }

        $bookModel->update($id, $postData);
        return redirect()->to('/')->with('message', 'Book updated successfully!');
    }

    public function delete($id = null)
    {
        $bookModel = new BookModel();
        $book = $bookModel->find($id); // Get the book record to find the image filename
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
