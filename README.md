# renseignements-stage

## Description

Le projet renseignements-stage à pour but de rendre le processus de remplissage, de consultation et de validation de la fiche de stage plus rapide et plus numérique. Pour cela, une application web doit être développée avec les technologies web suivantes :
 - HTML
 - PHP (programmation orientée object interdite)
 - CSS
 - JS
 
## MCD

![image](https://github.com/MaitreRouge/renseignements-stage/assets/39885214/d362bd64-1cd4-48b1-86c9-ee3ab52168a2)

## Commandes a effectuer

### Instalation du projet git

#### 1. Ne pas créer de dossier !

Effectivement; Quand on va "cloner" le projet git, git créera un dossier par lui même du nom : "<projet>-main"
  
#### 2. Effectuer la commande ``git clone``
  
Pour avoir le code sur sa machine, il faut cloner le projet. Pour cela il faut connaire l'url du projet. Pour cela il faudt suivre la procédure suivante :
  
  ![image](https://github.com/MaitreRouge/renseignements-stage/assets/39885214/6b296354-c3c6-4a5f-afc3-c80d480ac22d)
  
Ensuite, il faut ouvrir un invité de commande (cmd) dans racine du projet.
(Par exemple, je veux que mon projet soit dans D:/xampp/htdocs; j'ouvre mon cmd sur D:/xampp/htdocs)
  
 Une fois à la racine; j'effectue la commande ``git clone <url-du-projet-que-jai-copié-avec-la-procédure-juste-au-dessus>``
   
Dans notre cas; ca donne: ``git clone https://github.com/MaitreRouge/renseignements-stage.git``
   
![image](https://github.com/MaitreRouge/renseignements-stage/assets/39885214/b89be39e-8331-4d9a-b50d-1e8c360bc073)
   
(Résultat de la manipulation)
  
Note que j'audrais du mettre plus haut mais si la commande "git" n'est pas reconnue, il faudra installer git (je vous laisse aller sur le net pour trouver ca)
   
#### 3. Ouvrir notre éditeur de code favori et lui donner le dossier que vient de créer git comme dossier dans lequel on doit travailler
   
Tout est dans le nom :)
   
Et voila ! Maintenant vous êtes prêt pour développer sur le projet git !
 
### Comment commit ?
   
#### 0. Note 
   
Il se peut que votre éditeur prenne en charge git et propose une interface graphique pour faire vos commits. Vu qu'il existe d'éditeurs que d'atomes dans l'univers (presque (je pense)), j'ai pas envie de tout détailler pour tous les éditeurs. Je vais donc donner la marche a suivre avec un invité de commande.
   
#### 1. Ouvrir un cmd
  
C'est pas trop compliqué (win + r puis tapez cmd (faudra aussi naviguer jusqu'au dossier de travail))
  
#### 2. Ajouter les fichiers modifiers à git
  
C'est simple c'est ``git add .``


Explication pour les nerds :
  
Enfait, git n'ajoute pas les fichiers qu'on vient de modifier, il faut donc les ajouter à "l'espace de travail" avant des les commit
  
#### 3. Fait un commit
  
Alors, c'est l'étape la plus crutiale et la plus critique (pour une fois c'est pas de l'humour)
  
La commande a effectuer pour que tous les fichiers dans l'espace de travail soient ajouté au commit est : ``git commit -m "message"``
 
TRÈS IMPORTANT : Il est très compliqué de revenir en arrière une fois qu'un commit a été fait donc il faut faire très attention quant au message du commit qu'on donne ! COnsidérez le comme irréversible !!!
 
TRÈS IMPORTANT 2 : Mettez des noms qui font sens pour qu'on s'y retrouve :)
 
 #### 4. Faire un git push (pour publier ses modifications)
 
 #### 5. ET SURTOUT FAIRE DES ``GIT PULL`` QUAND QUELQU'UN D'AUTRE A FAIT UNE MODIFICATION
   
 :)
   

  


   

   

