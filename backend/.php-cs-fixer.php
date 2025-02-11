<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use Gordinskiy\LineLengthChecker\Rules\LineLengthLimit;  

$finder = Finder::create()
->in(__DIR__ . '/app') 
->name('*.php')
->notName('*.blade.php')
->ignoreDotFiles(true)
->ignoreVCS(true);

return (new Config())
->registerCustomFixers([  
    new LineLengthLimit(),
])
->setRules([  
    '@PSR12' => true, 
    'Gordinskiy/line_length_limit' => ['max_length' => 120],  
])
// recherche sur fichiers PHP
->setFinder($finder);  
