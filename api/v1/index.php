<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;
    use Aws\DynamoDb\DynamoDbClient;

    date_default_timezone_set('America/New_York');

    require 'vendor/autoload.php';
    require 'aws/aws-autoloader.php';

    $client = new DynamoDbClient([
        'region'  => 'us-east-1',
        'version' => 'latest',
        'credentials' => [
            'key'    => 'AKIAIZ7563FPRHUAZSOQ',
            'secret' => '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',
        ]
    ]);

    $app = new \Slim\App;
    $app->post('/events', postNewEvent);
    $app->run();


    /*  ------------------
         SAMPLE JSON REQUEST FOR postNewEvent
        ------------------
        {
        	"ProjectName": "ABC",
            "EventTimestamp": "1234567890",
        	"InstanceID": "1",
        	"EventType": "ABC",
        	"EventX": "253",
            "EventY": "405",
        	"EventTarget": "body.fade-in.toolbar-tray-open.toolbar-fixed.toolbar-horizontal",
        	"EventDataValue": null,
            ...
            "SessionID": "1240323523",
        	"SessionScreenWidth": "1920",
        	"SessionScreenHeight": "1080"
        }
        ------------------
    */
    function postNewEvent(Request $request, Response $response) {
        global $client;
        $req = json_decode($request->getBody());
        $vars = get_object_vars($req);
     
        $putarray = array();

        foreach ($vars as $key => $value) {
            if (!empty($value)){
                $putarray[$key] = DynamoPutRequestItemGenerator($value);
            }
        }
        
        $response = $client->putItem(array(
            'TableName' => 'AnalyticsEvents', 
            'Item' => $putarray
        ));
    }
    function DynamoPutRequestItemGenerator($value) {
        return array('S' => strval($value));
    }
?>