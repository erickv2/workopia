<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;
use Framework\Authorization;



class ListingController {
    
    protected $db;

    public function __construct() {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    public function index() {

            // inspectAndDie(Validation::match('test', 'test'));
        
            $listings = $this->db->query('SELECT * FROM listings ORDER BY created_at DESC')->fetchAll();

            loadView('listings/index', [
            'listings' => $listings
        ]); 
    }

    public function create() {
        loadView('listings/create');
    }

    public function show($params) {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];
        
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();
        

        //check if listing exist

        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        loadView('listings/show', [
            'listing' => $listing
        ]);
    }

    /**
     * store data in database
     * 
     * @return void
     */

    public function store() {
        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));

        $newListingData['user_id'] = Session::get('user')['id'];

        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'description', 'email', 'city', 'state', 'salary'];

        $errors = [];

        foreach($requiredFields as $field) {
            if(empty($newListingData[$field]) || !Validation::string($newListingData[$field])) {
                $errors[$field] = ucfirst($field) . ' is required';
            }
        }

        if(!empty($errors)) {
            //reload view with errors
            loadview('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
        } else {
            //submit data
            
            $fields = [];

            foreach($newListingData as $field => $value) {
                $fields[] = $field;
            }

            $fields = implode(', ', $fields);

            $values = [];

            foreach($newListingData as $field => $value) {
                //convert empty string to null

                if($value === '') {
                    $newListingData[$field] = null;
                }

                $values[] = ':' . $field;
            }

            $values = implode(', ', $values);

            $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";

            $this->db->query($query, $newListingData);

            Session::setFlashMessage('success_message', 'Listing created succesfully');

            redirect('/listings');
        }

    }

    /**
     * delete a listing
     * 
     * @param array $params
     * @return void
     */

     public function destroy($params) {
        $id = $params['id'];

        $params = [
            'id' => $id
        ];

        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();
        
        // check if listing exists

        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        // authorization
        if(!Authorization::isOwner($listing['user_id'])) {

            Session::setFlashMessage('error_message', 'You are not authorized to delete this listing');

            return redirect('/listings/' . $listing['id']);
        }

        $this->db->query('DELETE FROM listings WHERE id = :id', $params);

        // set flash message

        Session::setFlashMessage('success_message', 'Listing deleted succesfully!');

        redirect('/listings');

     }
    
     //show the listing edit form

     public function edit($params) {
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];
        
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();
        

        //check if listing exist

        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }


        loadView('listings/edit', [
            'listing' => $listing
        ]);
    }

    //update a listing

    public function update($params) {
        
        $id = $params['id'] ?? '';

        $params = [
            'id' => $id
        ];
        
        $listing = $this->db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();
        

        //check if listing exist

        if(!$listing) {
            ErrorController::notFound('Listing not found');
            return;
        }

        $allowedFields = ['title', 'description', 'salary', 'tags', 'company', 'address', 'city', 'state', 'phone', 'email', 'requirements', 'benefits'];

        $updateValues = array_intersect_key($_POST, array_flip($allowedFields));

        $updateValues = array_map('sanitize', $updateValues);

        $requiredFields = ['title', 'description', 'salary', 'email', 'city', 'state'];

        $errors = [];

        foreach($requiredFields as $field) {
            if(empty($updateValues[$field]) || !Validation::string($updateValues[$field])) {
                $errors[$field] = ucfirst($field) . ' is required.';
            }
        }

        if(!empty($errors)) {
            loadView('listings/edit', [
                'listing' => $listing,
                'errors' => $errors
            ]);
            
            exit;
        } else {
            // submit to database

            $updateFields = [];

            foreach(array_keys($updateValues) as $field) {
                $updateFields[] = "{$field} = :{$field}";
            }

            $updateFields = implode(', ', $updateFields);

            $updateQuery = "UPDATE listings SET $updateFields WHERE id = :id";

            $updateValues['id'] = $id;

            $this->db->query($updateQuery, $updateValues);

            Session::setFlashMessage('success_message', 'Listing updated succesfully');

            redirect('/listings/' . $id);
        }
    }
}