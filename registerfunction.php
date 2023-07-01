<?php

ini_set('display_errors',true);
// session_start();
require_once "./dbconnect.php";
require_once('./sessionconfig.php');
// sudo chmod 777 -R assets/

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $getfirstname = textfilter($_POST['firstname']);
    $getlastname = textfilter($_POST['lastname']);
    $getemail = textfilter($_POST['email']);
    $getpassword = MD5(textfilter($_POST['password']));
    $getdob = textfilter($_POST['dob']);
    $getphone = textfilter($_POST['phone']);
    $getaddress = textfilter($_POST['address']);
    $getnewsletter = textfilter($_POST['newsletter']);


    // echo $getfirstname;
    // echo $getlastname;
    // echo $getemail;
    // echo $getpassword;
    // echo $getdob;
    // echo $getphone;
    // echo $getaddress;
    
    if($getemail && $getpassword){

        try{
            $stmt = $conn->prepare("INSERT INTO users(profile,firstname,lastname,email,password,dob,phone,address,newsletter,documents) VALUE(:profile,:firstname,:lastname,:email,:password,:dob,:phone,:address,:newsletter,:documents) ");
            
            // $stmt->bindParam(":profile",$profile);
            $stmt->bindParam(":profile",$profile);
            $stmt->bindParam(":firstname",$firstname);
            $stmt->bindParam(":lastname",$lastname);
            $stmt->bindParam(":email",$email);
            $stmt->bindParam(":password",$password);
            $stmt->bindParam(":dob",$dob);
            $stmt->bindParam(":phone",$phone);
            $stmt->bindParam(":address",$address);
            $stmt->bindParam(":newsletter",$newsletter);
            $stmt->bindParam(":documents",$documents);
            
           $countfiles = count($_FILES['profile']['name']);

           if($countfiles){
             for($x=0; $x<$countfiles; $x++){
                
                $uploaddir = "./assets/";
                $filename = $_FILES['profile']['name'][$x];
                
                // $uploadfile = $uploaddir.basename($filename); //assets/dog1.jpg
                
                // $getformat = explode('.',$filename);
                // $newfilename = round(microtime(true)).'_'.current($getformat).".".end($getformat);
                // $uploadfile = $uploaddir.basename($newfilename);
                   

                $getfilecode = uniqid()."_".time();
                $getextension = pathinfo($filename,PATHINFO_EXTENSION);
                $newfilename = $getfilecode.".".basename($getextension);
                $uploadfile = $uploaddir.basename($newfilename);

                  // $uploadfile = $uploaddir.basename($filename);
                $allowextensions = ['jpg','jpeg','png','gif'];
                $uploadtype = end(explode('.',$filename));
                $uploadtype = strtolower($uploadtype);
                $filesize = $_FILES['profile']['size'][$x];
                $filetmp = $_FILES['profile']['tmp_name'][$x];
                
                $errors = [];
                
                // check file extensions
                if(in_array($uploadtype,$allowextensions) === false){
                    $errors[] = "Sorry,we just allowed jpg,jpeg,png and gif files";
                }
                // check file size
                 if($filesize > 30000000){
                    $errors[] = "Sorry,your file is too large.";
                 }
                // upload
                if(empty($errors)){
                   move_uploaded_file($filetmp,$uploadfile);
                   echo "File Successfully Uploaded";
                   $profile = $uploadfile;
                }else{
                    echo "<pre>".print_r($errors,true)."</pre>";
                }
                
             }
           }

            $firstname = $getfirstname;
            $lastname = $getlastname;
            $email = $getemail;
            $password = $getpassword;
            $dob = $getdob;
            $phone = $getphone;
            $address = $getaddress;
            $newsletter = $getnewsletter;
            
            $getdocuments = NULL;
            
            if(isset($_POST['documents'])){
                $docs = $_POST['documents'];

                foreach($docs as $doc){
                    $getdocuments .= $doc.',';
                    echo $getdocuments;
                }
            }

            $documents = $getdocuments;

            // $getdocuments = textfilter($_POST['documents']);
        
            // $stmt->execute();
            // echo "New Records Created Successfully ";   
            
            if($stmt->execute()){
                // $_SESSION['email'] = $email;
                // $_SESSION['password'] = $password;
                // header("Location:./planncohomedecoration/index.php");
                setsession('email',$email);
                setseeion('password',$password);
                redirectto('./planncohomedecoration/index.php');
            }else{
                echo "Try Again";
            }
        }catch(PDOException $e){
            echo "Error found: ".$e->getMessage();
        }

        $conn = null; 
    }

  
}

function textfilter($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);

    return $data;
}


// CREATE TABLE IF NOT EXISTS users(
//     id INT  AUTO_INCREMENT PRIMARY KEY,
//     profile VARCHAR(255),
//     firstname VARCHAR(20),
//     lastname VARCHAR(20),
//     email VARCHAR(30) UNIQUE,
//     password VARCHAR(255),
//     dob DATE,
//     phone VARCHAR(13),
//     address VARCHAR(100)
// );


?>