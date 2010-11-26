BACKENDPRO PRE-RELEASE INSTALL NOTES
------------------------------------

Until BackendPro gets into a state to allow the installer to be run. Installation
will require some manual steps. Please follow the steps below to get this pre-release
copy of BackendPro working.

1) In a directory on your website add the CodeIgniter 2.0 files. Lets say our websever root is at /home/www (or C:\www)

You need to copy the CI files so they are in the following structure

www
    application
    system
    user_guide
    index.php
    license.txt

2) Rename the CI user_guide folder to ci_user_guide. We only do this so the BackendPro user_guide dosn't overwrite it.

3) Copy the BackendPro files over the top. Your folder structure will then look like

www
    application
    system
    user_guide
    ci_user_guide
    assets
    install
    index.php
    license.txt

4) Open the install folder and run the SQL code in database.sql on a new DB.

5) Navigate to application/config/config.php.
    Set the BaseURL for your site
    Set the encryption key to 'backendpro' (without the '')
    Set sess_use_database to TRUE

6) Navigate to application/config/database.php
    Set the details up to connect to your database

7) You should be able to access the default controller. It will provide a very short welcome notice with some links.

8) To login to the admin area you will need
    Username = admin
    Password = password

    (If you have not entered the encryption key correctly then this will not work)

The system is setup. Please note that the GUI part of the system needing most work. A lot of the base code has been
done but there is still lots to do. DO NOT USE THIS TO CREATE YOUR SITES WITH IT IS FOR ONLY REFERENCE AND FUN..... for now