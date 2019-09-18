<?php
    class Posts extends Controller {
        public function __construct() {
            if (!isLoggedIn()) {
                redirect('users/login');
            }

            $this->postModel = $this->model('Post');
            $this->userModel = $this->model('User');
        }

        public function index() {
            //Get posts
            $posts = $this->postModel->getPosts();

            $data = [
                'posts' => $posts
            ];

            $this->view('posts/index', $data);
        }

        public function add() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST array
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Check for mime type
                $fileTypes = array('image/jpeg','image/jpg','image/png','image/x-png','image/gif');

                //Upload path
                $fileUploadPath = 'img';

                $data = [
                    'title' => trim($_POST['title']),
                    'body' => trim($_POST['body']),
                    'user_id' => $_SESSION['user_id'],
                    'post_image' => $_FILES['post_image']['name'],
                    'title_err' => '',
                    'body_err' => '',
                    'post_image_err' => ''
                ];

                // Validate data
                if (empty($data['title'])) {
                    $data['title_err'] = 'Please enter title';
                }

                if (empty($data['body'])) {
                    $data['body_err'] = 'Please enter body text';
                }

                //Validate Upload Image
                if(!in_array(strtolower($_FILES['post_image']['type']), $fileTypes)){ 
                  $data['post_image_err'] = 'Please upload Image. Acceptder Image Type : "jpg","png", "gif" and "jpeg"';
                }

                // Make sure no errors
                if (empty($data['title_err']) && empty($data['body_err']) && empty($data['post_image_err'])) {
                    //Validated
                    move_uploaded_file($_FILES['post_image']['tmp_name'],"./$fileUploadPath/{$_FILES['post_image']['name']}");
                    

                    if ($this->postModel->addPost($data)) {
                        flash('post_message', 'Post Added');
                        redirect('posts');
                    } else {
                        die('Something is wrong');
                    }

                } else {
                    $this->view('posts/add', $data);
                }

            } else {
                $data = [
                    'title' => '',
                    'body' => ''
                ];
    
                $this->view('posts/add', $data);
            }
        }

        public function edit($id){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){
              // Sanitize POST array
              $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      
              $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'body' => trim($_POST['body']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'body_err' => ''
              ];
      
              // Validate data
              if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
              }
              if(empty($data['body'])){
                $data['body_err'] = 'Please enter body text';
              }
      
              // Make sure no errors
              if(empty($data['title_err']) && empty($data['body_err'])){
                // Validated
                if($this->postModel->updatePost($data)){
                  flash('post_message', 'Post Updated');
                  redirect('posts');
                } else {
                  die('Something went wrong');
                }
              } else {
                // Load view with errors
                $this->view('posts/edit', $data);
              }
      
            } else {
              // Get existing post from model
              $post = $this->postModel->getPostById($id);
      
              // Check for owner
              if($post->user_id != $_SESSION['user_id']){
                redirect('posts');
              }
      
              $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
              ];
        
              $this->view('posts/edit', $data);
            }
          }

        public function show($id) {
            $post = $this->postModel->getPostById($id);
            $user = $this->userModel->getUserById($post->user_id);

            $data = [
                'post' => $post,
                'user' => $user
            ];

            $this->view('posts/show', $data);
        }

        public function delete($id) {
            // Get existing post from model
            $post = $this->postModel->getPostById($id);
      
            // Check for owner
            if($post->user_id != $_SESSION['user_id']){
              redirect('posts');
            }
            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if($this->postModel->deletePost($id)) {
                    flash('post_message', 'Post Removed');
                    redirect('posts');
                } else {
                    die('Something went wrong');
                }
            } else {
                redirect('posts');
            }
        }
    }