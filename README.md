# SymfonyTask

Including two parts.

1. Symfony/console files are in the folder Command. Copy this folder to "src\AppBundle\", command line likes this:

        php bin/console cars:query [--mpg=n,m,k] [--origin=n,m,k] ...
        
2. A simple php file can be executed in Terminal such as PHP CLI, command line likes this:

        php console.php cars:query [--mpg=n,m,k] [--origin=n,m,k] ...
        
3. If command line contains any additional options - perform the search and display corresponding car names. Example output:

 MPG:
  15.0 - 16
  16.0 - 13
  

Origin:
  US - 254
  Japan - 79
  Europe - 73
  

Search result:
  AMC Ambassador DPL
  AMC Matador
  AMC Matador (sw)
  AMC Rebel SST
  Buick Skylark 320
  Chevrolet Bel Air
  Chevrolet Chevelle Malibu Classic
  Chevrolet Monte Carlo
  Chevrolet Monte Carlo S
  Chevrolet Nova
  Chevrolet Nova Custom
  Chevrolete Chevelle Malibu
  Dodge Challenger SE
  Dodge Coronet Brougham
  Dodge Coronet Custom
  Dodge Dart Custom
  Ford Galaxie 500
  Ford Gran Torino
  Ford Maverick
  Ford Thunderbird
  Mercury Cougar Brougham
  Mercury Monarch
  Plymouth Fury III
  Plymouth Grand Fury
  Plymouth Satellite Custom
  Pontiac Catalina
  Pontiac Grand Prix
  Pontiac Grand Prix LJ 

4. It will search and display the cars' name without repeat，using the command line likes this(no options):

        php bin/console cars:query   (symfony files or simple php file)  
