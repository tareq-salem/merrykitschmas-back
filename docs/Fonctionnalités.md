# Merry Kitschmas
## User stories
### US01 - Page d'accueil
- Routes Global

Fonctionnalites du **HEADER** :  
- Logo :
 - Au clic sur le logo on reviens sur la Homepage
- Panier : 
  - Au clic sur le panier on va vers la page panier
  - Au clic sur un produit de la page on actualise le badge du panier (+1 produit)
- Barre de recherche (bonus) :
  - Lancer une requete vers la BDD
  - Afficher l'autocompletion a l'entree de la recherche

- Grille de produits
 - Au 1er chargement de la page : on affiche les derniers produits
 - Si l'url Courante est changee on lance une nouvelle requete avec cette nouvel url
 
Le **FOOTER** comporte :
- lien vers le repo git

### US02
- Dropdown listes
 - Au clic on change l'url Courante '/produits?category=['Homme', 'Femmes', ect...]&orderBy=price&limit=20&start=480'

### US03
Fonctionnalites de la side menu 
  - Catégories / Sous-catégories / Thèmes / Options
    - Au clic on change l'url Courante '/produits?category=['Homme', 'Femmes', ect...]'

### US04
- Grille de produits
 - Au clic on recupere l'id du produit selectionné
 - On navigue vers produit/id
 - On requete 'produit/id'
 
### US05
- Grille de produit
 - Au clic sur le panier on passe l'id du produit a la methode addShoppingCart(id)
 - Puis on lance une requete post /produit/id

- Page produit
 - Au clic sur 'Ajouter au panier' on recupere l'id (product/id) de la page courante et on appel la methode addShoppingCart(id)
 - Puis on lance une requete post /produit/id
 
- Fonctionnalite 'proceder a l'achat' => A voir

### US06
Sur la page du produit, dans le formulaire commentaire
 - Au clic sur le bouton on transmet une requete post(product/id, commentaire)

### US07 - Feature Bonus
- A l'entree de texte on recupere le contenu entré
- Puis on envoie une requete get(/product?nom='recherche')