<?php 

$DIR = '/dev/shm/go-push/';

if(!is_dir($DIR)) {
    mkdir($DIR,0755, true);
}

echo shell_exec('cd '.$DIR.'; git clone https://github.com/lucaswdm/spun-go-push');

foreach(glob('/data/*/') as $dir)
{
    if(is_dir($dir)) {
        $DOMAIN = basename($dir);
        if(validateDomain($DOMAIN))
        {
            echo $dir . PHP_EOL;
            $SHELL = "rsync --exclude='.git/' -avz '".$DIR."spun-go-push/' '".$dir."go-push/'";
            echo $SHELL . PHP_EOL;
            system($SHELL);
        }        
    }
}

function validateDomain($domain_name)
{
    if(filter_var($domain_name, FILTER_VALIDATE_IP)) return false;
    if(strpos($domain_name, '.') === false) return false;
    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
}
