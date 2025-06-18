<?php

 class Users extends Controller {

    private $userModel;
    public function __construct() {
        $this->userModel = $this->model('User');

    }

    public function register(){
        
        //loading form and execute submit to db
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //process form

            //sanitise POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
            ];
            $errors = [
                'err_name' => '',
                'err_email' => '',
                'err_password' => '',
                'err_confirm_password' => ''
            ];

            //validate name #
            if(empty($data['name'])){
                $errors['err_name'] = 'Please enter name';
            }
            //validate email
            if(empty($data['email'])){
                $errors['err_email'] = 'Please enter email';
            }   else {
                    if($this->userModel->findUserByEmail($data['email'])) {
                     $errors['err_email'] = 'There is already an account registered with this email';
                     }
                }
            //validate password
            if(empty($data['password'])){
                $errors['err_password'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6) {
                $data['err_password'] = 'Password must be at least 6 characters';
            }
            //validate confirm password
            if(empty($data['confirm_password'])){
                $errors['err_confirm_password'] = 'Please confirm password';
            } else {
                if($data['confirm_password'] != $data['password']){
                    $errors['err_confirm_password'] = 'Passwords do not match';
                }
            }
            $data = [
                'data' => $data,
                'errors' => $errors
            ];
            //check for errors
            if(empty($data['errors']['err_name']) && empty($data['errors']['err_email']) && empty($data['errors']['err_password']) && empty($data['errors']['err_confirm_password'])){
                
                //validated
                //Hash password
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

                //register user
                if($this->userModel->register($data)){
                    flash('register_success', 'You are registered and can log in');
                    redirect('users/login');
                }   else {
                    die('something went wrong');
                }

            } else {
                //load view with errors
                $this->view('users/register', $data);
                }
            

        } else {
            //init data
            $data = [
                'title' => 'Register',
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => ''
            ];
            $errors = [
                'err_name' => '',
                'err_email' => '',
                'err_password' => '',
                'err_confirm_password' => ''
            ];
            $data = [
                'data' => $data,
                'errors' => $errors
            ];
        $this->view('users/register', $data);
        }
    }


    public function login(){
            //loading form and execute submit to db
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
                //process form
                //sanitise POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
                $data = (object) array (
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                );
                $errors = (object) array (
                    'err_email' => '',
                    'err_password' => '',
                );

             //validate Email
                if(empty($data->email)){
                    $errors->err_email = 'Please enter email';
                } 
                if(empty($data->password)){
                    $errors->err_password = 'Please enter password';
                } elseif(strlen($data->password) < 6) {
                    $errors->err_password = 'Password must be at least 6 characters';
                }
             //check for user/email
                if($this->userModel->findUserByEmail($data->email)){
                 //do nothing
                }   else { 
                    $errors->err_email = 'No user found with that Email'; 
                }
             //consolidate data
                $data = [
                    'data' => $data,
                    'errors' => $errors
                ];


             //Make sure error properties are empty
                if(empty($data['errors']->err_email) && empty($data['errors']->err_password)){
                 //validated
                 //check and set logged in user
                    $loggedInUser = $this->userModel->login($data['data']->email, $data['data']->password);
                    if($loggedInUser){
                     //create session
                        $this->createUserSession($loggedInUser);
                    } else {
                        $data['errors']->err_password = 'Password incorrect';
                        $this->view('users/login', $data);
                    }
                } else {
                 //load view with errors
                    $this->view('users/login', $data);
                }
            } else {
                //init data 
                $data['data'] = (object) array (
                    'title' => 'Register',
                    'email' => '',
                    'password' => '',
                );
                $data['errors'] = (object) array (
                    'err_email' => '',
                    'err_password' => '',
                );
                $this->view('users/login', $data);
                }

    }  

    function createUserSession($user) {
            $_SESSION['user_id'] = $user->user_id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;
            redirect('pages/index');
     }

     public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        session_destroy();
        redirect('users/login');
        
     }
}
