<!doctype HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../assets/shared/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="../assets/shared/css/ie.css" />
    <link rel="stylesheet" type="text/css" href="../assets/shared/css/typography.css" />
    <link rel="stylesheet" type="text/css" href="../assets/public/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="../assets/shared/css/forms.css" />
    <title>Installation</title>
</head>

<body>
<div id="wrapper">
    <a name="top"></a>
    <div id="header">
        <h1>BackendPro Installation Process</h1>
    </div>

    <div id="content">
        <a name="top"></a>
<?php
    // Right lets get the system set up
    define('BASEPATH',dirname(dirname($_SERVER["PHP_SELF"])));
    $success = array();

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
    $success[] = "Connection made to `<b>".$_POST['database_name']."</b>` on `<b>".$_POST['database_host']."</b>`";

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
    $success[] = "Database schema created";

    // STEP 3 - GENERATE ENCRYPTION KEY
    // Base chars
    $base = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $max=strlen($base)-1;

    $encrypt_key='';
    mt_srand((double)microtime()*1000000);
    while (strlen($encrypt_key)<64)
        $encrypt_key.=$base{mt_rand(0,$max)};

    $success[] = "Encryption key generated `<b>".$encrypt_key."</b>`, please do not change this or loose it";
    // STEP 2 - GENERATE NEEDED CONFIG FILES
    // Fetch data from these files
    $files = array('config.dat','database.dat','recaptcha.dat');

    // Replacement rules
    $replace = array(
        array(  'base_url'=> 'http://' . $_SERVER['SERVER_NAME'] . BASEPATH . "/",
                'encryption_key' => $encrypt_key),
        array(  'database_host'=>$_POST['database_host'],
                'database_user'=>$_POST['database_user'],
                'database_password'=>$_POST['database_password'],
                'database_name'=>$_POST['database_name']),
        array(  'public_key'=>$_POST['public_key'],
                'private_key'=>$_POST['private_key'])
    );

    // Locations to copy files to when done
    $copy_to = array(
        '/system/application/config/config.php',
        '/system/application/config/database.php',
        '/modules/recaptcha/config/recaptcha.php'
    );

    // Lets get the contents of each of these files
    foreach($files as $key => $file)
    {
        $file = 'files/' . $file;
        $contents = file_get_contents($file);

        // Lets run our replacement
        foreach($replace[$key] as $rkey => $wvalue)
        {
            $contents = preg_replace('/{'.$rkey.'}/',$wvalue,$contents);
        }

        // Lets copy the files to there correct locations
        $fp = @fopen(".." . $copy_to[$key],'w+');
        if(!$fp)
        {
            die('Could not open file: '. $copy_to[$key]);
        }

        fwrite($fp,$contents);
        fclose($fp);
    }
    $success[] = "System config files updated and changes written to file system";

    // STEP 3 - CREATE A USER ACCOUNT FOR THIS USER
    // Append the salt to the password
    $password = $_POST['password'] . $encrypt_key;
    $password = sha1($password);

    $sql[] = "INSERT INTO `be_users` (`id` ,`username` ,`password` ,`email` ,`active` ,`group` ,`activation_key` ,`last_visit` ,`created` ,`modified`)VALUES ('1', '".$_POST['username']."', '".$password."', '".$_POST['email']."', '1', '2', NULL , NULL , NOW( ) , NULL);";
    $sql[] = "INSERT INTO `be_user_profiles` (`user_id`) VALUES ('1')";

    foreach($sql as $query)
    {
        mysql_query($query);
    }
    $success[] = "User account created with email `<b>".$_POST['email']."</b>` and password `<b>".$_POST['password']."</b>`";



    //
    //
    //  INSTALL FINISHED
    //
    foreach($success as $msg)
    {
        print "<font color='green'><b>SUCCESS</b></font> ".$msg."<br />";
    }
?>
    <p>Your system has been fully setup, please delete the <b>/install</b> directory
    otherwise other people will be able to reset your system setup.</p>

    <p>You may now use the system, <a href="../index.php">click here</a> to do so.</p>

    </div>

    <div id="footer">
        <a href="#top">Top</a><br />
        This site is powered by BackendPro 0.2<br />
        &copy; Copyright 2008 - Adam Price -  All rights Reserved
    </div>
</div>

</body>
</html>