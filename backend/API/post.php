<?php 
    include_once 'connect.php';
    header("Access-Control-Allow-Origin: http://localhost:5173");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Methods: POST, OPTIONS");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0); 
    }
    
    date_default_timezone_set('Europe/Paris');
    header("Content-Type: application/json");

    $data = json_decode(file_get_contents("php://input"), true);
    session_start();


    switch($data['action']){
        case 'get':
            $start = $data['start'] ?? null;
            $rows_per_page = $data['rows_per_page'] ?? null;
        
            if($start !== null && $rows_per_page !== null){
                getpost($db, $start, $rows_per_page);
            }else{
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Pagination values are required",
                    "pagination" => $start . $rows_per_page
                ]);
            }
            break;

        case 'create':        
            try{
                createPost($db, $_SESSION['user_id'], $data['title'], $data['company'], $data['location'], $data['type'], $data['description'], $data['salary'], $data['place']);
            }catch(Exception $e){
                http_response_code(400);
                echo json_encode([
                    "success" => false,
                    "message" => "Some infos are required"
                ]);
            }
            break;
    }



    function getpost($db, $start, $rows_per_page){
        try{   
            $sql = "SELECT * FROM  posts  LIMIT $start , $rows_per_page";

            $stmt = $db->prepare($sql);
            $stmt->execute();
            $fetch = $stmt->fetchall(PDO::FETCH_ASSOC);

            if(!empty($fetch)){
                $jobs=[];
                foreach($fetch as $row){
                    $job = [
                        'postid' => $row['id'],
                        'adminid' => $row['admin_id'],
                        'title' => $row['title'],
                        'company' => $row['company'],
                        'location' => $row['location'],
                        'type' => $row['type'],
                        'description' => $row['description'],
                        'salary' => $row['salary'] === null ?  "No Salary Info" : ($row['salary']),
                        'place'=> $row['place'] === null ?  "No Work place Info" : ($row['place']),
                        'status' => ($row['is_active'])
                    ];
                
                    $jobs[]= $job;
    
                }                
                http_response_code(200);
                echo json_encode([  "success" => true, 
                                    "message" => "Offers found !", 
                                    "data" => $jobs ]);
            }else{
                http_response_code(200);
                echo json_encode([  "success" => true, 
                                    "message" => "No offers found !", 
                                    "data" => []]);
            }


        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Request Issued !"]);
        }
    }

    function createPost($db, $adminid, $title, $company, $location, $type, $desciption, $salary, $place){
        try{
                $table = "posts" ; 
                $sql = "INSERT INTO "  . $table . "(admin_id, title, company, location,type,description, salary, is_active) VALUES (:admin_id , :title, :company , :location, :type , :description , :salary, :is_active)";

                $stmt = $db->prepare($sql);
                $stmt->bindValue(':admin_id', $adminid);
                $stmt->bindValue(':title', $title);
                $stmt->bindValue(':company', $company);
                $stmt->bindValue(':location', $location);
                $stmt->bindValue(':type', $type);
                $stmt->bindValue(':description', $desciption);
                $stmt->bindValue(':salary', $salary);
                $stmt->bindValue(':is_active', "1");

                $stmt->execute();

                http_response_code(200);
                echo json_encode([  "success" => true, "message" => "Post Created Successfully !"]);
        }catch(PDOException $e){
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Request Issued !"]);
        }
    }

?>