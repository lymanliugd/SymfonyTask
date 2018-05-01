# SymfonyTask

Including two parts.
1.Symfony/console files in the folder Command. Copy this folder to "src\AppBundle\", command line likes this:
        php bin/console cars:query [--mpg=n,m,k] [--origin=n,m,k] ...
        
2.A simple php file can be executed in Terminal such as PHP CLI, command line likes this:
        php console.php cars:query [--mpg=n,m,k] [--origin=n,m,k] ...
