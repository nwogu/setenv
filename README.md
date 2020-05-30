# Set Env  
Simple PHP package to concurrently set environment variables on multiple servers 

## Usage  

* 1. Clone this repo and ```composer install```  
* 2. Specify your server variables as a json file. See ```server_objects_sample.json``` file 
* 3. Copy .env.sample to .env 
* 4. Run ```php setenv {server variable} {env}``` eg: ```php setenv backend-prod S3_REGION=eu-west```