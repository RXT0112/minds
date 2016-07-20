<?php
namespace Minds\Core\Queue\Runners;

use Minds\Core\Data;
use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Notification\Settings;
use Minds\Entities\User;
use Surge;

/**
 * Push notifications runner
 */

class Push implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setExchange("mindsqueue", "direct")
               ->setQueue("Push")
               ->receive(function ($data) {
                   echo "[push]: Received a push notification \n";

                   $data = $data->getData();
                   $keyspace = $data['keyspace'];

                   //for multisite support.
                   global $CONFIG;
                   $CONFIG->cassandra->keyspace = $keyspace;

                   try {
                      $config = new Surge\Config([
                        'Apple' => [
                          'cert'=> '/var/secure/apns-production.pem'
                          //'sandbox'=>true,
                          //'cert'=> '/var/secure/apns.pem'
                        ],
                        'Google' => [
                          'api_key' => 'AIzaSyCp0LVJLY7SzTlxPqVn2-2zWZXQKb1MscQ'
                        ]
                      ]);

                      $type = $data['type'];

                      //get notification settings for this user
                      $toggles = (new Settings\PushSettings())
                        ->setUserGuid($data['user_guid'])
                        ->getToggles();
                      if($type && !isset($toggles[$type]) || $toggles[$type] === false){
                          echo "[push]: {$data['user_guid']} has disabled $type notifications \n";
                          return false;
                      }

                      $user = new user($data['user_guid'], false);

                      if (!$user->surge_token) {
                        echo "[push]: $user->username hasn't configured push yet.. not sending \n";
                        return false;
                      }

                      if(!$data['message']){
                          return false;
                      }

                      $message = Surge\Messages\Factory::build($user->surge_token)
                          ->setTitle($data['message'])
                          ->setMessage($data['message'])
                          ->setURI(isset($data['uri']) ? $data['uri'] : 'chat')
                          ->setSound(isset($data['sound']) ? $data['sound'] : 'default')
                          ->setJsonObject($data['json']);

                      Surge\Surge::send($message, $config);

                      echo "[push]: delivered $user->guid \n";
                   } catch (\Exception $e) {
                      echo "Failed to send push notification \n";
                   }
               });
        $this->run();
    }
}