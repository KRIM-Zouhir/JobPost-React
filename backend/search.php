<?php 
    include 'connect.php';


    function calcul_search($db , $table , $rows_per_page , $column , $search_value ){
        try{
            $sql = "SELECT * FROM $table WHERE $column LIKE :search_value";
            
            $records = $db->prepare($sql);
            $records->bindValue(':search_value', $search_value);
            $records->execute();
            $nr_rows = $records->rowCount();
            $pages = ceil($nr_rows/$rows_per_page);

            return $pages;

        } catch(PDOException $e){
            echo $e;
        }
    }

    function calcul_search_offers($db, $userid , $rows_per_page , $column , $search_value ){
        try{
            $sql = "SELECT * FROM applications WHERE user_id = :userid AND $column LIKE :search_value";
            
            $records = $db->prepare($sql);
            $records->bindValue(':search_value', $search_value);
            $records->bindValue(':userid', $userid);
            $records->execute();
            $nr_rows = $records->rowCount();
            $pages = ceil($nr_rows/$rows_per_page);

            return $pages;

        } catch(PDOException $e){
            echo $e;
        }
    }


    function search_posts($db, $search_value, $start , $rows_per_page){
        try{
            if(isset($_GET['page-nr'])){
                $page = $_GET['page-nr'] - 1 ;
                $start = $page * $rows_per_page;
            }

            $jobs = [];
            $table = "posts";
            $sql = "SELECT * FROM $table WHERE title LIKE :search_value LIMIT $start , $rows_per_page";

            $stmt= $db->prepare($sql);
            $stmt->bindValue(':search_value', $search_value );
            $stmt->execute();

            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);

            foreach($fetch as $row){
                $job = [
                    'postid' => htmlentities($row['id']),
                    'admin_id' => htmlentities($row['admin_id']),
                    'title' => htmlentities($row['title']),
                    'company' => htmlentities($row['company']),
                    'location' => htmlentities($row['location']),
                    'type' => htmlentities($row['type']),
                    'description' => htmlentities($row['description']),
                    'salary' => ($row['salary'] === null) ?  "No Salary Info" : htmlentities($row['salary']),
                    'place'=> ($row['place'] === null) ?  "No Work place Info" : htmlentities($row['place']),
                    'is_active' => htmlentities($row['is_active'])
                ];
                $jobs[] = $job;

            }

            return $jobs;
        

        }catch(PDOException $e){
            echo $e;
            return false;
        }
        
    }

    function search_applications($db, $search_value, $start , $rows_per_page){
        try{
            if(isset($_GET['page-nr'])){
                $page = $_GET['page-nr'] - 1 ;
                $start = $page * $rows_per_page;
            }

            $applications = [];
            $table = "applications";
            $sql = "SELECT * FROM $table WHERE post_title LIKE :search_value LIMIT $start , $rows_per_page";

            $stmt= $db->prepare($sql);
            $stmt->bindValue(':search_value', $search_value );
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
            echo $e;
            return false;
        }
        
    }
    
    function search_users($db, $search_value, $start , $rows_per_page){
        try{
            if(isset($_GET['page-nr'])){
                $page = $_GET['page-nr'] - 1 ;
                $start = $page * $rows_per_page;
            }

            $users = [];
            $table = "users";
            $sql = "SELECT * FROM $table WHERE username LIKE :search_value LIMIT $start , $rows_per_page";

            $stmt= $db->prepare($sql);
            $stmt->bindValue(':search_value', $search_value );
            $stmt->execute();

            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);
            

            foreach($fetch as $row){
                $user =[
                    'user_id' => htmlentities($row['id']),
                    'user_name' => htmlentities($row['username']),
                    'user_email' => htmlentities($row['email']),
                    'user_role' => htmlentities($row['role']),
                    'user_date' => htmlentities($row['created_at'])
                ];

                $users[] = $user;
            }

            return $users;
        
        }catch(PDOException $e){
            echo $e;
            return false;
        }
        
    }

    function search_offers($db,$userid, $search_value,  $start , $rows_per_page){
        try{
            if(isset($_GET['page-nr'])){
                $page = $_GET['page-nr'] - 1 ;
                $start = $page * $rows_per_page;
            }

            $applications = [];
            $sql = "SELECT * FROM applications WHERE user_id = :userid AND post_title LIKE :search_value LIMIT $start , $rows_per_page";

            $stmt= $db->prepare($sql);
            $stmt->bindValue(':userid', $userid );
            $stmt->bindValue(':search_value', $search_value );
            $stmt->execute();

            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);
            

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
        
        }catch(PDOException $e){
            echo $e;
            return false;
        }
        
    }



    

?>