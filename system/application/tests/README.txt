BackendPro Unit Tests Readme
=======================================

1)  All directories used in require statements must use a BASEPATH of
    "system/application"

2)  Any new test directories created for a module or otherwise must be added to
    BackendProTestSuite.xml. This must start with the full path:
    "/opt/cruisecontrol/project/backendpro/source/"
    
3)  When PHPunit is run it must be done so in the ROOT folder. I.E the folder
    which contains system/assets/user_guide etc.
    
4)  A test class must be called ClassNameTest.php no spaces or underscores and
    camel case should be used.
    
5)  All test classes should first require the Base_test_code.php file. This 
    provides basic function definitions for the CI system, e.g. log_message()
    
6)  If a super-object is required (For example Model/Controller) then create a
    file in a sub-folder "mocks" and store he class file there.
    
7)  NO TEST SHOULD DEPEND ON ANOTHER TO RUN FIRST OR IN A CERTAIN ORDER. This is
    a feature of phpunit but it should not be used.