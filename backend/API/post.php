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
                        'postid' => htmlentities($row['id']),
                        'adminid' => htmlentities($row['admin_id']),
                        'title' => htmlentities($row['title']),
                        'company' => htmlentities($row['company']),
                        'location' => htmlentities($row['location']),
                        'type' => htmlentities($row['type']),
                        'description' => htmlentities($row['description']),
                        'salary' => ($row['salary'] === null) ?  "No Salary Info" : htmlentities($row['salary']),
                        'place'=> ($row['place'] === null) ?  "No Work place Info" : htmlentities($row['place']),
                        'status' => htmlentities($row['is_active'])
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

?>