# Presentation du projet MESP

## Slide 1 - Titre

**MESP App - Portail de gestion et de verification de l'enseignement superieur prive**

Projet web developpe avec Laravel pour centraliser, administrer et verifier les informations liees aux institutions privees, filieres, etudiants, enseignants et accreditations.

**Script oral :**  
Ce projet est une plateforme web destinee a organiser les donnees de l'enseignement superieur prive et a fournir un portail public de verification fiable.

---

## Slide 2 - Contexte

Le secteur de l'enseignement superieur prive produit beaucoup de donnees importantes :

- etablissements autorises ;
- filieres et formations reconnues ;
- etudiants inscrits ;
- enseignants accredites ou affectes ;
- accreditations et arretes officiels ;
- calendriers academiques.

Sans plateforme centralisee, ces informations peuvent etre difficiles a verifier, a mettre a jour et a consulter.

**Script oral :**  
Le besoin principal est d'avoir une source unique, fiable et securisee pour suivre les institutions privees et permettre la verification publique de certaines informations.

---

## Slide 3 - Objectif du projet

L'objectif du projet est de creer une application web permettant :

- la gestion administrative des institutions privees ;
- le suivi des filieres autorisees ;
- l'enregistrement des etudiants et enseignants ;
- la gestion des accreditations ;
- l'import et l'export de donnees Excel ;
- la consultation publique des informations essentielles ;
- la verification d'un etudiant, d'un enseignant, d'une institution ou d'une formation.

**Script oral :**  
La plateforme sert a la fois les administrateurs, les institutions et le public. Elle combine gestion interne et transparence externe.

---

## Slide 4 - Utilisateurs cibles

**Administrateur / Ministere**

- gere les utilisateurs, roles et permissions ;
- supervise toutes les institutions ;
- importe et exporte les donnees ;
- consulte les journaux d'activite.

**Institution**

- consulte ou gere ses donnees selon ses droits ;
- suit ses filieres, etudiants, enseignants et calendriers.

**Public**

- consulte les institutions accreditees ;
- consulte les filieres autorisees ;
- verifie rapidement une information via le portail public.

**Script oral :**  
Le projet separe clairement les profils. Chaque utilisateur voit uniquement les fonctionnalites autorisees par son role.

---

## Slide 5 - Fonctionnalites principales

- Tableau de bord avec statistiques.
- Gestion des institutions.
- Gestion des filieres.
- Gestion des etudiants.
- Gestion des enseignants.
- Gestion des accreditations.
- Gestion du calendrier academique.
- Gestion des utilisateurs, roles et permissions.
- Import de fichiers CSV, XLS et XLSX.
- Export Excel des principales ressources.
- Portail public de consultation.
- Verification publique securisee par captcha.

**Script oral :**  
L'application couvre l'ensemble du cycle : saisie des donnees, controle, consultation, verification et extraction.

---

## Slide 6 - Portail public

Le portail public est accessible sans authentification. Il propose :

- une page d'accueil avec chiffres cles ;
- la liste des institutions accreditees ;
- la liste des formations autorisees ;
- la consultation des details d'une institution ;
- la consultation des details d'une filiere ;
- un formulaire de verification rapide ;
- des statistiques publiques.

**Script oral :**  
Cette partie rend la plateforme utile au public : etudiants, parents, employeurs ou organismes peuvent verifier les donnees officielles sans compte.

---

## Slide 7 - Verification publique

Le systeme permet de verifier :

- un etudiant par numero national ;
- un enseignant par numero national ;
- une institution par code ou nom ;
- une formation par code ou nom.

La verification utilise un captcha de session pour limiter les abus. Les resultats affichent uniquement les informations necessaires : statut, filiere, institution, niveau, annee, semestre ou validite.

**Script oral :**  
La verification est concue pour etre simple cote utilisateur, mais controlee cote application afin de proteger les donnees sensibles.

---

## Slide 8 - Espace admin

L'espace admin permet de gerer :

- les institutions ;
- les filieres ;
- les etudiants ;
- les enseignants ;
- les accreditations ;
- les calendriers academiques ;
- les utilisateurs ;
- les roles et permissions ;
- les imports et exports ;
- les journaux d'activite.

**Script oral :**  
L'administration centralise toutes les operations de gestion. Les permissions evitent qu'un utilisateur accede a des modules qui ne le concernent pas.

---

## Slide 9 - Modele de donnees

Le projet s'appuie sur plusieurs entites principales :

- **Institution** : nom, code, ville, statut, logo, coordonnees.
- **Filiere** : code, nom, niveau, duree, capacite, statut.
- **Etudiant** : identite, numero national chiffre, bac, contact.
- **Inscription** : filiere, annee universitaire, semestre, statut, QR code.
- **Enseignant** : identite, grade, specialite, numero d'accreditation.
- **Accreditation** : arrete, dates de validite, statut, type.
- **Calendrier academique** : semestres, examens, vacances.
- **Affectation enseignant** : enseignant, institution, filiere, volume horaire.

**Script oral :**  
Le modele de donnees reflete les relations du domaine : une institution possede des filieres, les etudiants sont inscrits dans des filieres, et les enseignants sont affectes a des filieres.

---

## Slide 10 - Securite

Le projet integre plusieurs mecanismes de securite :

- authentification des utilisateurs ;
- roles et permissions avec Spatie Laravel Permission ;
- acces conditionnel aux routes ;
- donnees sensibles masquees dans les modeles ;
- chiffrement du numero national des etudiants ;
- hash du numero de bac ;
- verification publique avec captcha ;
- journaux d'activite avec Spatie Activitylog ;
- suppression douce pour certaines donnees.

**Script oral :**  
La securite est importante car l'application manipule des informations administratives et personnelles. Le projet combine controle d'acces, chiffrement et tracabilite.

---

## Slide 11 - Technologies utilisees

**Backend**

- PHP 8.2
- Laravel 12
- Laravel Sanctum
- Spatie Permission
- Spatie Activitylog
- Maatwebsite Excel
- Simple QR Code

**Frontend**

- Blade
- Tailwind CSS
- Vite
- Vue 3, Pinia et Vue Router disponibles dans le projet

**Base de donnees**

- Migrations Laravel
- Relations Eloquent
- UUID pour les ressources principales

**Script oral :**  
Laravel fournit la structure principale : routes, controleurs, modeles, migrations et securite. Vite et Tailwind permettent une interface moderne.

---

## Slide 12 - Architecture generale

L'application suit une architecture Laravel classique :

- **Routes** : definition des URL publiques, authentifiees et admin.
- **Controllers** : traitement des actions utilisateur.
- **Models** : representation des donnees et relations.
- **Views Blade** : affichage des pages publiques et admin.
- **Imports/Exports** : traitement des fichiers Excel.
- **Middleware** : authentification, permissions et langue.
- **Migrations** : creation de la structure de base de donnees.

**Script oral :**  
Le projet est organise de maniere modulaire. Chaque partie a une responsabilite claire, ce qui facilite la maintenance et l'evolution.

---

## Slide 13 - Multilingue et accessibilite

Le projet contient une configuration de localisation et des fichiers de langue :

- francais ;
- arabe ;
- changement de langue via route dediee ;
- textes de l'interface centralises dans les fichiers `lang`.

**Script oral :**  
Le multilingue est un point important pour une plateforme institutionnelle. Il permet d'adapter l'interface a differents publics.

---

## Slide 14 - Imports et exports

L'application permet d'importer des donnees depuis :

- CSV ;
- TXT ;
- XLS ;
- XLSX.

Les imports disponibles concernent :

- etudiants ;
- institutions ;
- filieres ;
- enseignants.

Les exports Excel disponibles concernent :

- etudiants et inscriptions ;
- institutions ;
- filieres ;
- enseignants et affectations.

**Script oral :**  
Cette fonctionnalite facilite le passage depuis des fichiers administratifs existants vers une base de donnees centralisee.

---

## Slide 15 - Valeur ajoutee

Le projet apporte :

- une meilleure centralisation des donnees ;
- une verification rapide des informations ;
- plus de transparence pour le public ;
- une reduction des erreurs administratives ;
- un controle d'acces par role ;
- une tracabilite des actions ;
- une base evolutive pour ajouter de nouvelles fonctionnalites.

**Script oral :**  
La valeur du projet est a la fois administrative et publique : il simplifie la gestion interne tout en renforcant la confiance dans les informations publiees.

---

## Slide 16 - Limites actuelles et ameliorations possibles

Ameliorations possibles :

- ajouter plus de tests automatises ;
- finaliser ou enrichir certains journaux d'audit ;
- optimiser la recherche sur les champs chiffres ;
- ajouter une generation plus complete de QR codes ;
- ajouter des rapports statistiques avances ;
- creer une API mobile ;
- ajouter un systeme de notification ;
- ameliorer la gestion documentaire des arretes.

**Script oral :**  
Le projet dispose deja d'une base solide. Les prochaines evolutions peuvent porter sur l'automatisation, les statistiques et l'integration avec d'autres services.

---

## Slide 17 - Conclusion

MESP App est une plateforme web de gestion et de verification pour l'enseignement superieur prive.

Elle combine :

- administration des donnees ;
- securite ;
- roles et permissions ;
- portail public ;
- verification rapide ;
- import/export Excel ;
- architecture evolutive.

**Script oral :**  
En conclusion, ce projet repond a un besoin concret : disposer d'un systeme fiable pour gerer, suivre et verifier les informations officielles des institutions privees et de leurs formations.
