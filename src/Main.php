<?php

namespace Nwogu\Setenv;

use Nwogu\Setenv\Concretes\JsonReader;

class Main
{
    protected $server_objects;

    protected $config;

    protected $inputs;

    public function __construct()
    {
        $this->config           = $this->loadConfig();
        $this->server_objects   = $this->loadServerObjects();
    }

    protected function loadConfig()
    {
        $config = require __DIR__ . "/../config.php";

        return $config;
    }

    protected function loadServerObjects()
    {
        $config                     = $this->config;
        $path_to_server_objects     = $config['server_objects'];

        if (! file_exists($path_to_server_objects) ) {
            $this->error("path to server objects not found");
        }

        try {
            $server_objects_json = file_get_contents($path_to_server_objects);
            $server_objects = JsonReader::create()->read($server_objects_json);

        } catch (\Exception $exception) {
            $this->error("could not decode server objects json file");
        }

        return $server_objects;
    }

    protected function parseInputs()
    {
    
        $inputs                 = [];
        $inputs['server']       = $GLOBALS['argv'][1] ?? "";
        $inputs['env']          = $GLOBALS['argv'][2] ?? "";
    
        $this->validateInputs($inputs);
    
        return $this->inputs = $inputs;
    }

    protected function validateInputs(array $inputs)
    {
        $valid_server_variables = $this->getServerVariables();
    
        if (! in_array($inputs['server'], $valid_server_variables)) {
    
            $server_variable = $inputs['server'];
    
            $this->error("$server_variable is not an allowed server variable. Allowed "
                . "server variables are: " . PHP_EOL . 
                implode(",", array_unique($valid_server_variables)) . PHP_EOL
            );
        }

        if (empty($inputs['env'])) $this->error("env not specified");
    }

    protected function getServerVariables()
    {
        $server_object_keys         = array_keys($this->server_objects->toArray());
        $server_object_tags         = call_user_func_array(
                                        'array_merge', $this->server_objects->pluck('tags')
                                    );

        return array_merge($server_object_keys, $server_object_tags);
    }

    protected function error(string $message)
    {
        $this->display($message); exit(1);
    }

    protected function display($message)
    {
        echo $message . PHP_EOL;
    }

    protected function setEnv() 
    {
        $server             = $this->inputs['server'];
        $env                = $this->inputs['env'];

        $server_objects = array_filter([$this->server_objects->get($server)]) ?: 
                        $this->server_objects->where('tags', $server)->toArray();

        foreach ($server_objects as $server_object) {

            $this->display("setting env for server @ ip: $server_object->ip");

            $this->setEnvOnServer($server_object, $env);

            $this->display("setting env completed");
        }
    }

    protected function setEnvOnServer($server_object, $env)
    {
        $ssh        = $this->createSsh($server_object);

        $commands   = $this->getCommands($server_object, $env);

        display($ssh->exec($commands));

        $ssh->disconnect();
    }

    protected function createSsh($server_object)
    {
        $key            = new \Crypt_RSA();
        $ssh            = new \Net_SSH2($server_object->ip);
        $pem            = file_get_contents(
                            $server_object->pem ?: $this->config['pem']
                        );

        $key->loadKey($pem);

        if (! $ssh->login($server_object->user, $key)) {
            $this->error("Attempt to connect to server @ $server_object->ip failed" . PHP_EOL);
        }

        return $ssh;
    }

    protected function getCommands($server_object, $env)
    {
        $value          = $env;
        $key            = explode("=", $env)[0] . "=";

        /** cd into project root */
        $commands[]     = "cd $server_object->root";
        /** export env as bash variable */
        $commands[]     = "export env_key=$key && export env_val=$value";
        /** find and replace the environment variable and back up to .env.bak */
        $commands[]     = 'sed -i.bak "s~^${env_key}.*~${env_val}~g" .env';
        /** check if the env vars were replaced */
        $commands[]     = 'export is_present=$(cat .env | grep ^$env_key)';
        /** if the env variable was not set, append it to the end of file */
        $commands[]     = 'if [ -z $is_present ]; then echo $env_val >> .env; fi';

        return implode(" && ", $commands);
    }

    public static function run()
    {
        $main = new static;

        $main->parseInputs() && $main->setEnv();
    }
}