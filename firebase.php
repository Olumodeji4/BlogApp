<?php
include_once 'config_file.php';
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

/**
 * Class Firebase
 */
class Firebase{
    // This is our firebase instance to be used throughout our blog app
    protected $firebase;

    /**
     * Firebase constructor.
     */
    public function __construct(){
// before anything is loaded, we are loading our firebase and giving it the service account to be used by our system.
    $this->firebase = (new Factory)
        ->withServiceAccount(service_account??[])
        ->withDatabaseUri('https://blogapp-554b0-default-rtdb.firebaseio.com/');
    }


    /**fetching the firebase auth
     * @return \Kreait\Firebase\Contract\Auth
     */
    public function firebase_auth(){
       return $this->firebase->createAuth();
    }


    /** our firebase class can only be used when we call this get_firebase method
     * @return Factory
     */
    public function get_firebase(){
        return $this->firebase;
    }


    /**
     * @return \Kreait\Firebase\Database\Reference
     */
    public function get_blog_database(){
        return $this->firebase
            ->createDatabase()
            ->getReference('blogs')
            ;
    }

    /**
     * Update Blog Post
     * @param $data
     * @return \Kreait\Firebase\Database\Reference
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    public function update_blog($data){
        return $this->get_blog_database()->push($data);
    }

    /** Upload Image to Firebase
     * @param $file_name
     * @return string
     */
    public function upload_image($file_name){
        $storage = $this->firebase->createStorage();
        if (isset($_FILES[$file_name]) && $_FILES[$file_name]['error'] === UPLOAD_ERR_OK) {
            $image = $_FILES[$file_name];
            $imagePath = 'uploads/' . basename($image['name']);
            $imageTempPath = $image['tmp_name'];

            // Upload the image to Firebase Storage
            $storageBucket = $storage->getBucket();
            $imageStream = fopen($imageTempPath, 'r');
            $storageBucket ->upload($imageStream, [
                'name' => $imagePath
            ]);

            // Get the URL of the uploaded image
            return $storageBucket->object($imagePath)
                ->signedUrl(new \DateTime('+1 hour'));
        }

        return '';
    }


    /**
     * @throws \Kreait\Firebase\Exception\DatabaseException
     */
    public function delete_blog($blogId){
        $blogReference = $this->firebase
            ->createDatabase()
            ->getReference('blogs/' . $blogId);

        // Remove the blog post
        $blogReference->remove();
    }

}


