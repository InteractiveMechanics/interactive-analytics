<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require 'db.php';
    require 'vendor/autoload.php';

    $app = new \Slim\App;

    $app->post('/projects/{id}/events', postNewProjectEvent);
    $app->run();


    /*  ------------------
         SAMPLE REQUEST
        ------------------
        {
        	"instance_id": null,
            "event_datetime": "0000-00-00 00:00:00"
        	"event_type": "click",
        	"event_x": 1200,
        	"event_y": 500,
        	"event_target": "body.fade-in.toolbar-tray-open.toolbar-fixed.toolbar-horizontal",
        	"event_value": null,
        	"event_path": "/",
        	"event_session": 00000000000000,
        	"screen_width": 1920,
        	"screen_height": 1080
        }
        ------------------
    */
    function postNewProjectEvent(Request $request, Response $response) {
        $id = $request->getAttribute('id');
        $req = json_decode($request->getBody());
        $vars = get_object_vars($req);
     
        $sql = '
            INSERT INTO 
                event (
                    `project_id`,
                    `instance_id`,
                    `event_type`,
                    `event_x`,
                    `event_y`,
                    `event_target`,
                    `event_value`,
                    `event_path`,
                    `event_session`
                ) 
            VALUES 
                (
                    :project_id, 
                    :instance_id, 
                    :event_type,
                    :event_x,
                    :event_y,
                    :event_target,
                    :event_value,
                    :event_path,
                    :event_session
                )
            ';
        try {
            $db = getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindParam('project_id', $id);
            $stmt->bindParam('instance_id', $vars['instance_id']);
            $stmt->bindParam('event_type', $vars['event_type']);
            $stmt->bindParam('event_x', $vars['event_x']);
            $stmt->bindParam('event_y', $vars['event_y']);
            $stmt->bindParam('event_target', $vars['event_target']);
            $stmt->bindParam('event_value', $vars['event_value']);
            $stmt->bindParam('event_path', $vars['event_path']);
            $stmt->bindParam('event_session', $vars['event_session']);
            $stmt->execute();
            
            $result = $db->lastInsertId();
            print_r($result);
            $db = null;
        } catch(PDOException $e) {
            echo json_encode($e->getMessage()); 
        }
    }    
?>