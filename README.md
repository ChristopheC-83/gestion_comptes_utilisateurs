Visiteur => voit le site
Utitlisateur => a un compte qu'il peut gérer
Administrateur => a les clés du chateau

en POO, les fonctions sont :
public => accesibles de partout
protected => accessible à l'intérieur de la classe ou d'une classe enfant.
private => seulement à l'intérieur de la classe.

Une classe abstraite ne peut pas être instanciée.
Elle sert de 'support"/modèle à d'autres classes.

Pour acoir un mdp crypté, on affiche
"echo password_hash("test", PASSWORD_DEFAULT);"
(test ou autre...)
et hop, on peut renseigner notre bd avec le mot de passe hashé !