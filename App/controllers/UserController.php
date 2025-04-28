<?php 

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;

class UserController {
    protected $db;

    public function __construct() {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * show the login page
     * 
     * @return void
     */

     public function login() {
        loadView('users/login');
     }

         /**
     * show the register page
     * 
     * @return void
     */

     public function create() {
        loadView('users/create');
     }

     /**
      * store user in db @return void
      */

      public function store() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['password_confirmation'];

        $errors = [];

        // Validation

        if(!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if(!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 50 characters';
        }

        if(!Validation::string($password, 6)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        
        if(!Validation::match($password, $passwordConfirmation)) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if(!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ]
            ]);

            exit;
        }

        // check if email exists

        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params);

        if($user) {
            $errors['email'] = 'Tha email already exists';
            loadView('users/create', [
                'errors' => $errors
            ]);

            exit;
        }
      }

}