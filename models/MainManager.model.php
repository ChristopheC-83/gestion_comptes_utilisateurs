<?php
require_once("Model.class.php");

// le mainManager gerent les données
//  recup ou transmission à la bdd

// on crée en parallele des entités.model
// qui ont leurs propres spécificités.
//class Livres, class Personnages...

abstract class MainManager extends Model
{
    
}
