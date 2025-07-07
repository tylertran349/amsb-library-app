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

        $rules['cover_image'] = 'if_exist|uploaded[cover_image]|max_size[cover_image,1024]|is_image[cover_image]'; // Image validation rules

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
        // Define validation rules
        $rules = [
            'title'            => 'required|max_length[255]',
            'author'           => 'required|max_length[255]',
            'publication_year' => 'required|exact_length[4]|numeric'
        ];
        
        // Image validation rules
        $imageRules = [
            'cover_image' => 'if_exist|uploaded[cover_image]|max_size[cover_image,2048]|is_image[cover_image]|mime_in[cover_image,image/jpg,image/jpeg,image/png,image/webp]'
        ];
        
        if (! $this->validate(array_merge($rules, $imageRules))) {
            return redirect()->to('/books/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }
        $postData = $this->request->getPost(); // Get all the POST data
        $img = $this->request->getFile('cover_image'); // Check for a new image upload
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $bookModel = new BookModel(); // A new image has been uploaded, so we process it
            
            // Get the old image name from the database to delete it
            $oldBook = $bookModel->find($id);
            $oldImage = $oldBook['cover_image'];

            $newName = $img->getRandomName(); // Generate a new random name for the image
            
            $img->move(FCPATH . 'uploads', $newName); // Move the new image to the 'public/uploads' directory
            
            $postData['cover_image'] = $newName; // Update the cover_image in our data to be saved
            
            // Delete the old image file if it exists
            if ($oldImage && file_exists(FCPATH . 'uploads/' . $oldImage)) {
                unlink(FCPATH . 'uploads/' . $oldImage);
            }
        }

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
        
        // First, get the book record to find the image filename
        $book = $bookModel->find($id);
        
        if ($book) {
            // Get the image filename
            $imageFile = $book['cover_image'];
            
            // Now, delete the database record
            if ($bookModel->delete($id)) {
                // If the database record was deleted successfully, delete the image file
                if ($imageFile && file_exists(FCPATH . 'uploads/' . $imageFile)) {
                    unlink(FCPATH . 'uploads/' . $imageFile);
                }
                return redirect()->to('/')->with('message', 'Book deleted successfully!');
            }
        }

        // If something went wrong
        return redirect()->to('/')->with('error', 'Failed to delete book.');
    }
}
