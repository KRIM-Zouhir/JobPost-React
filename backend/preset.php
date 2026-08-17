<?php 
    include 'connect.php';


    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $form_type = $_POST['form_type'];
        
        if($form_type == "form_preset"){
            try{
                $presetname = htmlentities($_POST['presetname']);
                $userid = htmlentities($_POST['userid']);
                $cv_name = "test.pdf";
                $cover_name = null;
                $max_files_size = 10 * 1024 * 1024;
                
                $preset_id = add_preset($db, $userid, $presetname, $cv_name , $cover_name);

                echo "test $preset_id";
                
                $post_folder_path = "uploads/presets_" . $userid . "/name_" . $preset_id . "/";

                if(!is_dir($post_folder_path)){
                    mkdir($post_folder_path , 0777 , true);
                }

                if((isset($_FILES['cvfilename'])) && $_FILES['cvfilename']['error']== 0){
                    if($_FILES['cvfilename']['size'] > 0 && $_FILES['cvfilename']['size'] < $max_files_size){
                        $tmp = $_FILES['cvfilename']['tmp_name'];
                        $cv_name = "CV.pdf";

                        move_uploaded_file($tmp, $post_folder_path . $cv_name);
                    }
                } 

                if((isset($_FILES['coverfilename'])) && $_FILES['coverfilename']['error']== 0){
                    if($_FILES['coverfilename']['size']>0 && $_FILES['coverfilename']['size'] < $max_files_size){
                        $tmp = $_FILES['coverfilename']['tmp_name'];
                        $cover_name = "Cover.pdf";
    
                        move_uploaded_file($tmp, $post_folder_path . $cover_name);
                    }else{
                        echo "error";
                    }
                }


                if($cv_name !== null OR $cover_name !== null){
                    edit_preset($db, $preset_id, $presetname, $cv_name, $cover_name);
                    header("Location: /presets");
                    exit;
                }


                    
            }catch(PDOException $e){
                echo $e;
            }
        }
        
        elseif($form_type == "form_edit"){
            try{
                $presetid = htmlentities($_POST['presetid']);
                $presetname = htmlentities($_POST['presetname']);
                $userid = htmlentities($_POST['userid']);
                
                $cv_namenew = null;
                $cover_namenew = null;
                $max_files_size = 10 * 1024 * 1024;
                $post_folder_path = "uploads/presets_" . $userid . "/name_" . $presetid . "/";

                if(!is_dir($post_folder_path)){
                    mkdir($post_folder_path , 0777 , true);
                }

                if(isset($_FILES['cv_namenew']) && $_FILES['cv_namenew']['error'] == 0){
                    if($_FILES['cv_namenew']['size'] > 0 && $_FILES['cv_namenew']['size'] < $max_files_size){
                        $tmp = $_FILES['cv_namenew']['tmp_name'];
                        $cv_namenew = "CV.pdf";

                        move_uploaded_file($tmp, $post_folder_path . $cv_namenew);
                    } 
                }else{
                    $cv_namenew = "CV.pdf";
                }

                if((isset($_FILES['cover_namenew'])) && $_FILES['cover_namenew']['error']== 0){
                    if($_FILES['cover_namenew']['size'] > 0 && $_FILES['cover_namenew']['size'] < $max_files_size){
                        $tmp = $_FILES['cover_namenew']['tmp_name'];
                        $cover_namenew = "Cover.pdf";
    
                        move_uploaded_file($tmp, $post_folder_path . $cover_namenew);
                    }
                }else{
                    $cover_namenew = "Cover.pdf";
                }
                if(edit_preset($db, $presetid, $presetname, $cv_namenew , $cover_namenew)){
                    header("Location: /presets");
                    exit;              
                }


            }catch(PDOException $e){
                echo $e;
            }
        }
        
        elseif($form_type == "form_delete"){
            try{
                $presetid = htmlentities($_POST['presetid']);
                if (!isset($_POST['checkbox_delete'])) {
                    die("You must confirm the deletion.");
                }else{
                    if(delete_preset($db , $presetid)){
                        header("Location: /presets");
                        exit;
                    }

                }


            }catch(PDOException $e){
                echo $e;
            }
        }
    }

    function add_preset($db, $user_id , $presetname, $cv_filename , $cover_filename){
        try{
            $sql = "INSERT INTO presets (user_id, preset_name, cv_filename, cover_filename) VALUES (:user_id,:preset_name , :cv_filename, :cover_filename)";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id);
            $stmt->bindValue(':preset_name', $presetname);
            $stmt->bindValue(':cv_filename', $cv_filename);
            $stmt->bindValue(':cover_filename', $cover_filename);
            $stmt->execute();
            
            $preset_id = $db->lastInsertId();
            return $preset_id;
            
            // return ['status' => true, 'presetid'=> $db->lastInsertedId()];


        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    function edit_preset($db, $presetid , $presetname, $cv_filename , $cover_filename){
        try{

            $sql = "UPDATE presets  SET preset_name = :preset_name , cv_filename = :cv_filename, cover_filename = :cover_filename WHERE id = :presetid";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':presetid', $presetid);
            $stmt->bindValue(':preset_name', $presetname);
            $stmt->bindValue(':cv_filename', $cv_filename);
            $stmt->bindValue(':cover_filename', $cover_filename);
            $stmt->execute();
            
            return true;



        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    function delete_preset($db, $presetid){
        try{
            $sql = "DELETE FROM presets WHERE id = :presetid";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':presetid', $presetid);
            $stmt->execute();
            
            return true;


        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }
    
    function get_presets($db , $user_id ,$start ,$row_per_page){
        try{
            $presets = [];
            $sql = "SELECT * FROM presets WHERE user_id = :userid LIMIT $start, $row_per_page";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':userid', $user_id);
            $stmt->execute();

            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);

            foreach($fetch as $row){
                $preset = [
                    'id' => htmlentities($row['id']),
                    'user_id' => htmlentities($row['user_id']),
                    'preset_name' => htmlentities($row['preset_name']),
                    'cv_name' => $row['cv_filename'],
                    'cover_name' => $row['cover_filename'],
                ];

                $presets[] = $preset ;
            }
            return $presets;

        }catch(PDOException $e){
            echo $e;
            return false;
        }
    }

    function apply_preset($presetid, $userid, $post_id){
        try{
            $preset_folder = "uploads/presets_" . $userid . "/name_" . $presetid . "/";
            $cv_path = $preset_folder . "CV.pdf";
            $cover_path = $preset_folder . "Cover.pdf";

            $post_folder_path = "uploads/" . "post_" . $post_id . "/";

            if(!is_dir($post_folder_path)){
                mkdir($post_folder_path , 0755 , true);
            }


            if(!file_exists($cv_path)){
                echo "CV file does not exist.";
                return false;
            }else{
                $cv_name = "CV" . "_" . bin2hex(random_bytes(10)) . "_" . $post_id . ".pdf";
                copy($cv_path, $post_folder_path . $cv_name);
            }

            if(file_exists($cover_path)){
                $cover_name = "Cover" . "_" . bin2hex(random_bytes(10)) . "_" . $post_id . ".pdf";
                copy($cover_path, $post_folder_path . $cover_name);
            }

            return [$cv_name, $cover_name];


        }catch(Exception $e){
            echo $e;
            return false;
        }
    }

?>