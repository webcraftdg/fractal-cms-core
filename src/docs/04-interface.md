# Interface administrateur

## Se connecter

Afin d'accéder à l'interface administrateur, il faut se connecter en utilisant l'utilisateur créé avec la commande :

``
php yii.php fractalCms:admin/create
``

![Page de connexion](./images/login.png)

## Le header

Dans le header on peut trouver, la version de FractalCMS, le menu, les initiales de l'utilisateur connecté,
le bouton de déconnexion.

## Le menu

Ce menu est généré en fonction des droits accessible par l'utilisateur, l'utilisateur "administrateur" à tous les
droits.
Lors de la création d'un nouvel utilisateur les droits associés peuvent être paramétré.

[<- Précédent](03-configuration.md) | [Suivant ->](05-content.md)