# renseignements-stage

## Description

Le projet renseignements-stage à pour but de rendre le processus de remplissage, de consultation et de validation de la fiche de stage plus rapide et plus numérique. Pour cela, une application web doit être développée avec les technologies web suivantes :
 - HTML
 - PHP (programmation orientée object interdite)
 - CSS
 - JS
 
## Fonctionnalités
### En tant qu'utilisateur :
- Compte utilisateur : 	Créer / Modifier
- Compte utilisateur : 	Modifier un mot de passe
- Stage :		Créer 		
- Stage :		Modifier			(accessible pour les stages ayant un statut "Stage en cours de création")
- Stage :		Changer le statut		(uniquement de "Stage en cours de création" à "Stage en cours d'approbation")
- Stage :		Générer un fichier PDF	
- Entreprise :		Créer / Modifier 		(uniquement pour l'entreprise en lien avec le stage)
- Contact :		Créer / Modifier 		(uniquement les contacts en lien avec l'entreprise du stage)

### En tant qu'enseignant :
- Stage :		Consulter			(tous les stages sauf statut "Stage en cours de création")
- Stage :		Approuver 			(de "Stage en cours d'approbation" à "Stage approuvé")
- Stage :		Ne pas approuver un stage	(de "Stage en cours d'approbation" à "Stage en cours de création)
- Stage :		Générer un fichier PDF		(tous les stages sauf statut "Stage en cours de création")

### En tant que RS :
- Stage :		Affecter à un enseignant	(de "Stage approuvé" => "Stage affecté")
- Stage :		Terminer			(de "Stage affecté" à "Stage terminé")
- Stage :		Annuler				(de tous les statut à "Stage annulé")
- Stage :		Générer un fichier PDF
- Entreprise :		Créer / Modifier 		
- Contact :		Créer / Modifier 		

### WORKFLOW d'un stage :
- Stage en cours de création
- Stage en cours d'approbation	(Note : par un enseignant)
- Stage approuvé
- Stage affecté
- Stage terminé
- Stage annulé

## Modèle Conceptuel de Données

![image](https://github.com/MaitreRouge/renseignements-stage/assets/39885214/d362bd64-1cd4-48b1-86c9-ee3ab52168a2)

## Commandes à effectuer

### Installation du projet git

#### 1. Ne pas créer de dossier !

Effectivement, quand on va "cloner" le projet git, git créera un dossier par lui même du nom : "<projet>-main"
  
#### 2. Effectuer la commande ``git clone``
  
Pour avoir le code sur sa machine, il faut cloner le projet. Pour cela il faut connaire l'url du projet. Pour cela il faut suivre la procédure suivante :
  
  ![image](https://github.com/MaitreRouge/renseignements-stage/assets/39885214/6b296354-c3c6-4a5f-afc3-c80d480ac22d)
  
Ensuite, il faut ouvrir une invite de commande (cmd) dans racine du projet.
(Par exemple, je veux que mon projet soit dans D:/xampp/htdocs; j'ouvre mon cmd sur D:/xampp/htdocs)
  
Une fois à la racine, j'effectue la commande ``git clone <url-du-projet-que-jai-copié-avec-la-procédure-juste-au-dessus>``
   
Dans notre cas; ca donne : ``git clone https://github.com/MaitreRouge/renseignements-stage.git``
   
![image](https://github.com/MaitreRouge/renseignements-stage/assets/39885214/b89be39e-8331-4d9a-b50d-1e8c360bc073)
   
(Résultat de la manipulation)
  
Note que j'aurais du mettre plus haut mais si la commande "git" n'est pas reconnue, il faudra installer git (je vous laisse aller sur le net pour trouver ça)
   
#### 3. Ouvrir notre éditeur de code favori et lui donner le dossier que vient de créer git comme dossier dans lequel on doit travailler
   
Tout est dans le nom :)
   
Et voila ! Maintenant vous êtes prêt pour développer sur le projet git !
 
### Comment commit ?
   
#### 0. Note 
   
Il se peut que votre éditeur prenne en charge git et propose une interface graphique pour faire vos commits. Vu qu'il existe autant d'éditeurs que d'atomes dans l'univers (presque (je pense)), j'ai pas envie de tout détailler pour tous les éditeurs. Je vais donc donner la marche a suivre avec une invite de commande.
   
#### 1. Ouvrir un cmd
  
C'est pas trop compliqué (win + r puis tapez cmd (faudra aussi naviguer jusqu'au dossier de travail))
  
#### 2. Ajouter les fichiers modifiers à git
  
C'est simple c'est ``git add .``


Explication pour les nerds :
  
En fait, git n'ajoute pas les fichiers qu'on vient de modifier, il faut donc les ajouter à "l'espace de travail" avant de les committer
  
#### 3. Fait un commit
  
Alors, c'est l'étape la plus crutiale et la plus critique (pour une fois c'est pas de l'humour)
  
La commande à effectuer pour que tous les fichiers dans l'espace de travail soient ajoutés au commit est : ``git commit -m "message"``
 
TRÈS IMPORTANT : Il est très compliqué de revenir en arrière une fois qu'un commit a été fait donc il faut faire très attention quant au message du commit qu'on donne ! Considérez le comme irréversible !!!
 
TRÈS IMPORTANT 2 : Mettez des noms qui font sens pour qu'on s'y retrouve :)
 
 #### 4. Faire un git push (pour publier ses modifications)
 
 #### 5. ET SURTOUT FAIRE DES ``GIT PULL`` QUAND QUELQU'UN D'AUTRE A FAIT UNE MODIFICATION
   
 :)
   

  


   

   
