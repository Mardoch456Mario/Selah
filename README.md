# Selah — site vitrine WordPress

Site WordPress de présentation pour [Selah](https://selah-ebon.vercel.app/), le showroom de mode où
**ton personnage essaie pour toi les pièces des créateurs de Cotonou**.

Il reprend l'identité de l'application : direction « noir sur blanc » (encre `#0b0b0c`, blanc,
fil safran `#c9922a` en rappel du kanvô), police Hanken Grotesk, boutons en pilule.

![Page d'accueil](wp-content/themes/selah/screenshot.png)

## Ce que contient le dépôt

```
wp-content/themes/selah/        Thème « Selah » (thème par blocs, modifiable dans l'éditeur)
  theme.json                    Couleurs, typographie, espacements, boutons
  templates/ parts/             Modèles de pages, en-tête, pied de page
  patterns/                     Sections et pages prêtes à l'emploi (compositions « Selah »)
  inc/installation.php          Création des pages en un clic
  assets/                       Police Hanken Grotesk, logo, illustrations
wp-content/plugins/selah-core/  Extension « Selah Core » : formulaire de demande d'accès
scripts/                        Installation (WP-CLI) et fabrication des .zip
docker-compose.yml              WordPress local avec Docker
```

### Les pages du site

| Page | Adresse | Contenu |
|---|---|---|
| Accueil | `/` | Ouverture, comment ça marche, pourquoi Selah, appel aux créateurs, questions, formulaire |
| Créateurs | `/createurs/` | Pour les ateliers et marques de Cotonou, étapes pour rejoindre, formulaire « créateur » |
| À propos | `/a-propos/` | L'idée de Selah, les créateurs, l'identité « noir sur blanc » |
| Questions fréquentes | `/questions-frequentes/` | Questions en accordéon |
| Demander un accès | `/demande-acces/` | Formulaire de demande de code |
| Confidentialité | `/confidentialite/` | Usage des données du formulaire |
| Journal | `/journal/` | Articles (actualités) |

Les boutons « J'ai un code », « Ouvrir Selah »… mènent à l'application.

## Mettre le site en ligne chez un hébergeur

1. **Fabriquer les deux archives** (ou compresser vous-même les dossiers `selah` et `selah-core`) :
   ```bash
   ./scripts/empaqueter.sh     # crée dist/selah.zip et dist/selah-core.zip
   ```
2. Installer WordPress chez l'hébergeur (installation en un clic chez la plupart d'entre eux).
3. **Apparence › Thèmes › Ajouter un thème › Téléverser** : `selah.zip`, puis **Activer**.
4. **Extensions › Ajouter › Téléverser** : `selah-core.zip`, puis **Activer**.
5. Cliquer sur **« Créer les pages du site »** dans le bandeau qui apparaît. Les pages, la page
   d'accueil, le journal et les adresses lisibles sont réglés automatiquement.
6. **Réglages › Général** : titre « Selah », langue « Français », fuseau horaire « Porto-Novo ».
7. **Réglages › Selah** : e-mail qui reçoit les demandes, et adresse de l'application si elle change.

## Essayer en local

Avec Docker :

```bash
docker compose up -d
WP="docker compose run --rm -T cli wp" ADMIN_PASSWORD=choisissez-un-mot-de-passe ./scripts/installer.sh
```

Puis ouvrir <http://localhost:8080> (administration : `/wp-admin`, identifiant `admin`).

Avec un WordPress déjà installé et [WP-CLI](https://wp-cli.org/) :

```bash
WP="wp --path=/chemin/vers/wordpress" SITE_URL=http://mon-site.local ./scripts/installer.sh
```

Le script installe le français, active le thème et l'extension, règle le fuseau horaire de Cotonou,
crée les pages et active les adresses lisibles. Il peut être relancé sans risque.

## Modifier le site

- **Textes des pages** : *Pages*, puis ouvrir la page dans l'éditeur.
- **Page d'accueil, en-tête, pied de page** : *Apparence › Éditeur › Modèles › Page d'accueil*
  (ou *Compositions › En-tête / Pied de page*).
- **Couleurs et typographie** : *Apparence › Éditeur › Styles*.
- **Ajouter une section** : dans l'éditeur, bouton « + » › *Compositions* › catégorie **Selah**
  (ouverture, comment ça marche, appel aux créateurs, questions, formulaire, bandeau kanvô…).
- **Lien vers l'application** : donner l'adresse `#selah-app` à un bouton ou à un lien ; le site le
  remplace par l'adresse réglée dans *Réglages › Selah*.
- **Formulaire** : le code court `[selah_demande_acces]` l'affiche n'importe où ;
  `[selah_demande_acces profil="createur"]` présélectionne « Je suis créateur·rice ».

## Les demandes d'accès

Chaque envoi du formulaire arrive dans le menu **Demandes d'accès** de l'administration
(avec une pastille pour les nouvelles) et par e-mail.

- Fiche de suivi : étape (nouvelle, contactée, code envoyé, close) et notes internes.
- **Exporter en CSV** (bouton au-dessus de la liste) pour envoyer les codes par lot.
- Protection contre les robots : champ piège, délai minimal, 5 demandes par heure et par connexion.
  Pas de nonce, pour que le formulaire fonctionne même sur des pages mises en cache.
- Données personnelles : les outils *Exporter / Effacer les données personnelles* de WordPress
  prennent en charge ces demandes.

Certains hébergeurs n'envoient pas les e-mails de WordPress : dans ce cas, installer une
extension SMTP (par exemple *WP Mail SMTP*). Les demandes restent de toute façon visibles dans
l'administration.

## À relire avant la mise en ligne

- **Les textes** ont été écrits à partir de la description publique de Selah (l'application est
  en démonstration privée). Relisez-les, en particulier les questions fréquentes et les étapes
  pour les créateurs.
- **La page Confidentialité** est un point de départ : faites-la valider, surtout si vous
  ajoutez une mesure d'audience (le site n'en a pas aujourd'hui).
- **Les illustrations** (téléphone, portant) peuvent être remplacées par de vraies captures de
  l'application et des photos des pièces.

## Détails techniques

- WordPress 6.6 ou plus récent (testé sur 7.1.2), PHP 7.4 ou plus récent.
- Thème par blocs, sans constructeur de pages ni dépendance externe : la police est hébergée
  dans le thème (licence SIL OFL, voir `assets/fonts/hanken-grotesk/OFL.txt`), aucun appel à
  un service tiers.
- Code conforme aux règles *WordPress-Extra* (PHP_CodeSniffer).
