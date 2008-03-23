<?php
    // Right lets get the system set up

    // STEP 1 -- DATABASE
    // Lets connect to the database
    $cn = @mysql_connect($_POST['database_host'],$_POST['database_user'],$_POST['database_password']);
    if (!$cn)
    {
        die('Could not connect: ' . mysql_error());
    }
    // Lets see if we can access the database provided
    if (! @mysql_select_db($_POST['database_name'],$cn))
    {
        die('Could not connect: ' . mysql_error());
    }
    
    // OK so far soo good, lets add the basic tables
    $fp = @fopen('schema.sql','r');
    if( !$fp)
    {
        die('Could not open mysql schema file');
    }
    
    $contents = fread($fp, filesize('schema.sql'));
    fclose($fp);
    
    // Lets get rid of comment lines
    $contents = preg_replace('/--(.)*/','',$contents);
    
    // Get rid of newlines
    $contents = preg_replace('/\n/','',$contents);
    
    // Turn each statement into an array item
    $contents = explode(';',$contents);
    
    // Lets loop through and run them
    foreach($contents as $sql)
    {
        if( $sql == '')
            continue;
            
        if( ! mysql_query($sql,$cn))
        {
            die('Query failed:' . mysql_error());
        }
    }

    // STEP 2 - GENERATE NEEDED CONFIG FILES
    
    
?>
