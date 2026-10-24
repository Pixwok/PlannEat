# PlannEat

## Pourquoi PlannEat ?
**PlannEat** est une application web open source pour planifer ses repas, gérer ses recettes et optimiser ses courses. Ce projet part d'un constat personnel, quand on habite près d'un supermarché, on a tendance à multiplier les allers-retours pour acheter 3-4 choses. Quand on hésite quoi manger le soir, on mange souvent mal et pas forcément économique.

PlannEat vise à résoudre tous ces problèmes :
- **Perte de temps** : multiplier les courses, c'est perdre du temps chaque jour.
- **Budget non maîtrisé** : on achète au fil de l'eau, souvent plus que nécessaire.
- **Charge mentale** : trouver quoi manger chaque soir est épuisant.

PlannEat inverse la logique : **on planifie d'abord, on fait une fois de grosse course ensuite.**

## Fonctionnalités
### Gestions des recettes
- **Catalogue de recettes** : ajoutez vos recettes en quelques minutes (ingrédients, étapes, temps de préparation, portions).
- **Tags et catégories** : végétarien, rapide, comfort food, plat du dimanche…
- **Repas de saison** : chaque recette est associée à une ou plusieurs saisons ; l'app vous propose ce qui est de saison en ce moment.
- **Favoris et historique** : retrouvez vite vos valeurs sûres.
### Planification des repas
- **Générateur aléatoire** : générez un planning pour une période (semaine, quinzaine…) selon vos contraintes (nb de couverts, saison, tags, budget, temps).
- **Composition manuelle** : glissez-déposez vos recettes sur les jours de la semaine, en un clic depuis le catalogue.
- **Portions intelligentes** : indiquez combien de fois vous voulez manger un plat (ou le nombre de parts) ; les quantités d'ingrédients sont recalculées automatiquement.
### Liste de courses
- **Génération automatique** : à partir du planning, l'app agrège tous les ingrédients, fusionne les doublons et additionne les quantités par unité.
- **Export "note téléphone"** : partagez la liste vers Google Keep, Apple Notes, Reminders ou n'importe quelle app via un texte copiable / partage natif.

## Stack technique
| Couche | Techno |
| ------ | ------ |
| Backend | CodIgniter 4 |
| FrontEnd | VueJS |
| StyleCSS | Tailwind CSS |
| Base de données | SQLite |
| Environnement | Docker |

## Get Started
```
# Clone Repo
git clone https://github.com/Pixwok/PlannEat.git
cd PlannEat
```
Créer .env pour backend. Voici un exemple de .env minimal pour la backend CI4.
```
CI_ENVIRONMENT = development
database.default.hostname = localhost
database.default.database = planneat.db
database.default.DBDriver = SQLite3
```