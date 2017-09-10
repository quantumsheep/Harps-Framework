# Harps
## Introduction

> Harps is a powerfull, fast and open-source PHP framework, its goal is to be  flexible and easy to use as possible.

## Installation

> To install Harps you will need composer and just do the command:
```composer create-project "harps/harps-skeleton" sitedirectory```

## How to use Harps?

> Well, if you haven't use the skeleton, you need to add those lines to your index.php :
```
require_once(__DIR__ . "/vendor/autoload.php");
Boot::Harps();```

After that you have two directory, "App" and "Config". In App there is all the Controllers, Models, Managers, Views and Ressources. And in Config there is the Parameters and the Route configurations.

And that's it, you can now use Harps! See the documentation for more informations.
