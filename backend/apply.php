<?php 
    include "connect.php"; 
    include "helper.php";
    include_once "preset.php";

    

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $form_type = $_POST['form_type'];
        if($form_type === "apply") :
            try{
                
                $user_id = htmlentities($_POST['user_id']);
                $post_id = htmlentities($_POST['postid']);
                $post_title = htmlentities($_POST['post_title']);

                $preset_id = htmlentities($_POST['preset_id']);

                $status = "pending";
                $cv_name = null;
                $cover_name = null;
                $max_files_size = 10 * 1024 * 1024;
                $post_folder_path = "uploads/" . "post_" . $post_id . "/";


                if(!is_dir($post_folder_path)){
                    mkdir($post_folder_path);
                }
                if(!empty($preset_id)){
                    [$cv_name, $cover_name] = apply_preset($preset_id , $user_id, $post_id);

                }else{
                    if((isset($_FILES['cvfile']) ) && $_FILES['cvfile']['error'] == 0){
                        $tmp = $_FILES['cvfile']['tmp_name'];
                        $cv_name = "CV" . "_" . bin2hex(random_bytes(10)) . "_" . $post_id . ".pdf";

                        move_uploaded_file($tmp, $post_folder_path . $cv_name);
                    } 

                    if((isset($_FILES['coverfile']))){

                        $tmp = $_FILES['coverfile']['tmp_name'];
                        $cover_name = "Cover" . "_" . bin2hex(random_bytes(10)) . "_" . $post_id . ".pdf";

                        move_uploaded_file($tmp, $post_folder_path . $cover_name);
                    }
                }

                
                if(newapply($db , $user_id, $post_id, $post_title, $cv_name , $cover_name , $status)){
                    $redirect = $_POST['redirect_to'] ?? '/admin/applications';
                    header("Location: $redirect");
                    exit;
                }
            }catch(PDOException $e){
                echo "An error has occured <br> $e";
            }
        elseif($form_type === "accept"):
            try{
                $id = htmlentities($_POST['id']);
                status_application($db , $id , $status = "accepted");

                $redirect = $_POST['redirect_to'] ?? '/admin/applications';
                header("Location: $redirect");
                exit;
            }catch(PDOException $e){
                echo $e;
            }
        
        elseif($form_type === "pending"):
            try{
                $id = htmlentities($_POST['id']);
                status_application($db , $id , $status = "pending");
                $redirect = $_POST['redirect_to'] ?? '/admin/applications';
                header("Location: $redirect");
                exit;

            }catch(PDOException $e){
                echo $e;
            }

        elseif($form_type === "deny"):
            try{
                $id = htmlentities($_POST['id']);
                status_application($db , $id , $status = "rejected");
                $redirect = $_POST['redirect_to'] ?? '/admin/applications';
                header("Location: $redirect");
                exit;

            }catch(PDOException $e){
                echo $e;
            }

        endif;
            
        
    }


    function newapply($db , $user_id, $post_id, $post_title, $cv_name , $cover_name , $status){
        try{
            $table = "applications";
            // $sql_title = "SELECT title FROM posts WHERE $post_id";
            // $post_title = $db->query($sql_title);

            $sql = "INSERT INTO $table ( user_id , post_id , post_title , cv_filename , cover_note , status) VALUES (:user_id , :post_id, :post_title, :cv_filename , :cover_note , :status)";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':post_id', $post_id);
            $stmt->bindValue(':post_title', $post_title);
            $stmt->bindValue(':cv_filename', $cv_name);
            $stmt->bindValue(':cover_note', $cover_name);
            $stmt->bindValue(':status', $status);
            $stmt->execute();
            return true;

        
        }catch(PDOException $e)
        {
            echo "An error has occured $e";
            return false;
        }
    }

    function user_applied($db , $user_id){

        try{
            $post_ids=[];
            $table = "applications";
            $sql = "SELECT * FROM $table WHERE user_id = :user_id";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->execute();
            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);

            foreach($fetch as $row){
                $post_id = [
                    'post_id' => htmlentities($row['post_id']),
                ];
            
                $post_ids[]= $post_id;

            }
            return $post_ids;

        }catch(PDOException $e){
            echo "An error has occured $e ";
        }

    }

    function get_applications($db, $start , $row_per_page){
        try{
            if(isset($_GET['page-nr'])){
                $page = $_GET['page-nr'] - 1 ;
                $start = $page * $row_per_page;
            }

            $applications = [];
            $table = "applications";
            $sql = "SELECT * FROM $table LIMIT $start, $row_per_page";

            $stmt = $db->prepare($sql);
            $stmt->execute();
            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);


            foreach($fetch as $row){
                $application = [
                    'app_id' => htmlentities($row['id']),
                    'user_id' => htmlentities($row['user_id']),
                    'post_id' => htmlentities($row['post_id']),
                    'post_title' => htmlentities($row['post_title']),
                    'cv_filename' => htmlentities($row['cv_filename']),
                    'cover_note' => htmlentities($row['cover_note']),
                    'status' => htmlentities($row['status']),
                    'app_date' => htmlentities($row['applied_at'])

                    ];
                $applications[] = $application;
                
            }
            
            return $applications;
    
            

            
        }catch(PDOException $e){
            echo "An error has occured $e";
            return false;
        }

    }  

    function status_application($db, $app_id , $status) {
        try{
            $table = "applications";
            $sql = "UPDATE $table SET status = :status WHERE id = :id";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $app_id);
            $stmt->bindValue(':status', $status);
            $stmt->execute();
            return true;

        }catch(PDOException $e){
            echo $e;
            return false;

        }
    }

    function get_application_byid($db , $start , $row_per_page , $app_id){
        try{
            $applications = [];
            $table = "applications";
            $sql = "SELECT * FROM $table WHERE post_id = :app_id  LIMIT $start, $row_per_page";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':app_id',$app_id);
            $stmt->execute();

            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);

            if(empty($fetch)){
                exit;
            }
            else{
                foreach($fetch as $row){
                    $application = [
                        'app_id' => htmlentities($row['id']),
                        'user_id' => htmlentities($row['user_id']),
                        'post_id' => htmlentities($row['post_id']),
                        'post_title' => htmlentities($row['post_title']),
                        'cv_filename' => htmlentities($row['cv_filename']),
                        'cover_note' => htmlentities($row['cover_note']),
                        'status' => htmlentities($row['status']),
                        'app_date' => htmlentities($row['applied_at'])

                        ];
                    $applications[] = $application;
                    
                }
            }
            return $applications;

        }catch(PDOException $e){
            echo $e;
        }

    }

    function get_applications_foruser($db, $start , $row_per_page , $userid){
        try{
            $applications = [];
            $table = "applications";
            $sql = "SELECT * FROM $table  WHERE user_id = :userid LIMIT $start, $row_per_page";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':userid', $userid);
            $stmt->execute();
            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);

            if(empty($fetch)){
                exit;
            }
            else{
                foreach($fetch as $row){
                    $application = [
                        'post_id' => htmlentities($row['post_id']),
                        'post_title' => htmlentities($row['post_title']),
                        'cv_filename' => htmlentities($row['cv_filename']),
                        'cover_note' => htmlentities($row['cover_note']),
                        'status' => htmlentities($row['status']),
                        'app_date' => htmlentities($row['applied_at'])

                        ];
                    $applications[] = $application;
                    
                }
                
                return $applications;
    
            }

            
        }catch(PDOException $e){
            echo "An error has occured $e";
            return false;
        }

    }  

?>