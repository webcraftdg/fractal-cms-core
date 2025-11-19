# Sujets Avancés

## Ajout d'un module à FractalCMS

Fractal-cms-core accept l'ajout de module sous certaine condition.

### Premier étape : Installer le module core

``
composer require webcraftdg\fractal-cms-core
``

Le module fractal-cms-core met à disposition une interface.

```
fractalCms\core\interfaces\FractalCmsCoreInterface
```

Votre Module.php doit implémenter cette interfaces et valoriser les fonctions nécessaires.

```php 


```


#### Les menus de votre module : getMenu()

Cette fonction est utiliser pas le Core afin d'amender le menu et permettre l'accès à vos implémentations. Cette fonction renvoie un tableau sous le format : 

```php
  [
        'title' => 'le titre du menu',
        'url' => 'votre url',
        'optionsClass' => [], // Les classes à ajouter
        'children' => [], // les sous menus si il y en a (même configuration)
    ];
```

### Les routes de votre module : getRoutes()

Cette fonction ajoute les routes à toutes les routes déjà présentent. Cette fonction renvoie un tableau sous le format :

* $coreId : Id du module Core dans votre application
* $contextId : Context Id de votre module

```php 
[
  $coreId.'/api/file/delete'=> $contextId.'/api/file/delete',
  .../...
]
```

### Les permissions de votre module : getPermissions()

Cette fonction permet d'ajouter les permissions qui permettrons le paramétrage complet des utilisateurs. Cette fonction renvoie un tableau sous le format (Rbac [yiiframework](https://www.yiiframework.com/)): 

* permission => titre

```php
[
    'PRODUCTS:' => 'produits',
    ../..
]
```

### Les informations de votre module : getInformations()

Cette fonction permet de récupérer des informations lié au module et l'afficher sur le Dashboard. Cette fonction renvoie un tableau sous le format : 

```php 
[
 'Nombre de produits actifs' => 10,
 ../..
]
```

### Le Nom de votre module : getName()

Cette fonction renvoie le nom de votre module (string)

### Le Context Id de votre modules : getContextId(), setContextId()

Ces fonctions permettent la valorisation et la récupération de l'Id technique du module au sein de l'application



[<- Précédent](05-content.md) | [Accueil](index.md)