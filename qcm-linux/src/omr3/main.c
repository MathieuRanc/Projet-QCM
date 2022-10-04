/*
==============================
mon algo pour scanner les qcm
==============================
*/
#include "image.h"

#define HAUTEUR_BANDE_VERTICALE 190
#define HAUTEUR_BANDE_HORIZONTALE 200
#define SEUIL_NOIRCIE 230
#define POURCENTAGE_NOIRCIE 0.6
#define SEUIL_CROIX 160
#define POURCENTAGE_CROIX 0.35

int main(int argc,char** argv)
{
/*
=========================
récupération des arguments
=========================
*/

	// l'image à traiter
	struct image *img;
	
	// récupération des arguments
	if(argc < 8)
	{
		fprintf(stderr,"image_in image_out data_out nb_bandes_verticales nb_bandes_horizontales seuil_base");
		fprintf(stderr,"image_in: quizz à analyser\n");
		fprintf(stderr,"image_out: image de sortie\n");
		fprintf(stderr,"data_out: fichier de sortie avec les marques reconnues\n");
		fprintf(stderr,"nb_bandes_verticales: nombre de bandes verticales à retrouver (si la feuille est vue normalement)\n");
		fprintf(stderr,"nb_bandes_horizontales: nombre de bandes horizontales à retrouver (si la feuille est vue normalement)\n");
		fprintf(stderr,"seuil_base: seuil entre 0 (fort) et 255 (faible) pour la reconnaissances des marques\n");
		fprintf(stderr,"position_repere: position du repère, hg->haut gauche, hd->haut droite, bg->bas gauche, bd->bas droite\n");
		exit(1);
	}
	char* image_in = argv[1];
	char* image_out = argv[2];
	char* data_out = argv[3];
	int nb_bandes_verticales = atoi(argv[4]);
	int nb_bandes_horizontales = atoi(argv[5]);
	int seuil_base = atoi(argv[6]);
	char* position_repere = argv[7];

	int repere_haut=0,repere_bas=0,repere_gauche=0,repere_droite=0;
	if(strcmp(position_repere,"hg") == 0){
		repere_haut = 1;
		repere_gauche = 1;
	}
	if(strcmp(position_repere,"hd") == 0){
		repere_haut = 1;
		repere_droite = 1;
	}
	if(strcmp(position_repere,"bg") == 0){
		repere_bas = 1;
		repere_gauche = 1;
	}
	if(strcmp(position_repere,"bd") == 0){
		repere_bas = 1;
		repere_droite = 1;
	}
	
/*
=========================
récupération de l'image	
=========================
*/
#if DEBUG
	printf("Ouverture de l'image %s\n",image_in);
#endif
   img  = openImage(image_in);
   if(img == NULL)
   {
   	fprintf(stderr,"fichier incorrect");
   	exit(1);
   }

#if DEBUG
   printf("largeur = %d px , hauteur = %d px\n",img->width,img->height);
#endif

/*
=========================
recherches des bandes
=========================
*/
#if DEBUG
   printf("Recherche des bandes\n");
#endif
   // analyse de la bande du haut
   // image à analyser, début de la zone en largeur à analyser, fin de la zone en largeur à analyser, début de la zone en hauteur à analyser, fin de la zone à analyser en hauteur, seuil de reconnaissance, nombre de pixels à retrouver, largeur d'une bande en pixels, nombre de bandes verticales, nombre de bandes horizontales
	ListeZone *liste_haut = rechercheBandes_largeur(img,0,img->width,0,HAUTEUR_BANDE_VERTICALE, seuil_base, 10,10,nb_bandes_verticales,nb_bandes_horizontales);
	// nombre de bandes obtenues
	int nb_haut = nb_zones(liste_haut);
#if DEBUG
	printf("bande haute %d\n",nb_haut);
	// affichage de la position des bandes
	printListeZone(liste_haut);
#endif

	// analyse de la bande du bas
	// image à analyser, début de la zone en largeur à analyser, fin de la zone en largeur à analyser, début de la zone en hauteur à analyser, fin de la zone à analyser en hauteur, seuil de reconnaissance, nombre de pixels à retrouver, largeur d'une bande en pixels, nombre de bandes verticales, nombre de bandes horizontales
	ListeZone *liste_bas = rechercheBandes_largeur(img,0,img->width,img->height-HAUTEUR_BANDE_VERTICALE,img->height, seuil_base, 25,10,nb_bandes_verticales,nb_bandes_horizontales);
	// nombre de bandes obtenues
#if DEBUG
	int nb_bas = nb_zones(liste_bas);
	printf("bande bas %d\n",nb_bas);
	// affichage de la position des bandes
	printListeZone(liste_bas);
#endif
	
	// analyse de la bande de gauche
	// image à analyser, début de la zone en largeur à analyser, fin de la zone en largeur à analyser, début de la zone en hauteur à analyser, fin de la zone à analyser en hauteur, seuil de reconnaissance, nombre de pixels à retrouver, hauteur d'une bande en pixels, nombre de bandes verticales, nombre de bandes horizontales
	ListeZone *liste_gauche = rechercheBandes_hauteur(img,0,HAUTEUR_BANDE_HORIZONTALE,0,img->height, seuil_base, 25,10,nb_bandes_verticales,nb_bandes_horizontales);
	// nombre de bandes obtenues
	int nb_gauche = nb_zones(liste_gauche);
#if DEBUG
	printf("bande gauche %d\n",nb_gauche);
	// affichage de la position des bandes
	printListeZone(liste_gauche);
#endif

	// analyse de la bande de droite
	// image à analyser, début de la zone en largeur à analyser, fin de la zone en largeur à analyser, début de la zone en hauteur à analyser, fin de la zone à analyser en hauteur, seuil de reconnaissance, nombre de pixels à retrouver, hauteur d'une bande en pixels, nombre de bandes verticales, nombre de bandes horizontales
	ListeZone *liste_droite = rechercheBandes_hauteur(img,img->width-HAUTEUR_BANDE_HORIZONTALE,img->width,0,img->height, seuil_base, 25,10,nb_bandes_verticales,nb_bandes_horizontales);
	// nombre de bandes obtenues
	int nb_droite = nb_zones(liste_droite);
#if DEBUG
	printf("bande droite %d\n",nb_droite);
	// affichage de la position des bandes
	printListeZone(liste_droite);
#endif
	
/*
=========================
détermination de la rotation
de l'image
suivant le repère qui est vu comme une bande
=========================
*/
	int rotation = -1;
// repère situé en haut à gauche si la feuille est prise normalement
	if(repere_haut == 1 && repere_gauche == 1)
	{
		// repère en haut à gauche
		if(nb_haut == nb_bandes_horizontales+1 && nb_gauche == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation ok\n");
	#endif
			rotation = 0;
		}
		else if(nb_haut == nb_bandes_verticales+1 && nb_droite == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 90° anti horaire\n");
	#endif
			rotationListeZone(img,1,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 90;
		}
		else if(nb_bas == nb_bandes_horizontales +1 && nb_droite == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation 180° anti horaire\n");
	#endif
			rotationListeZone(img,2,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 180;
		
		}
		else if(nb_bas == nb_bandes_verticales+1 && nb_gauche == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 270° anti horaire\n");
	#endif
			rotationListeZone(img,3,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 270;
		}
		// pas de repère
		else
		{
			fprintf(stderr,"Bandes incohérentes Haut Gauche\n");
		}
		if(rotation != -1)
		{
			removeZone(&liste_haut,0);
			nb_haut = nb_zones(liste_haut);
			removeZone(&liste_gauche,0);
			nb_gauche = nb_zones(liste_gauche);
		}
	}
// repère situé en haut à droite si la feuille est prise normalement
	else if(repere_haut == 1 && repere_droite == 1)
	{
		if(nb_haut == nb_bandes_horizontales+1 && nb_droite == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation ok\n");
	#endif
			rotation = 0;
		}
		else if(nb_bas == nb_bandes_verticales+1 && nb_droite == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 90° anti horaire\n");
	#endif
			rotationListeZone(img,1,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 90;
		}
		else if(nb_bas == nb_bandes_horizontales +1 && nb_gauche == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation 180° anti horaire\n");
	#endif
			rotationListeZone(img,2,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 180;
		
		}
		else if(nb_haut == nb_bandes_verticales+1 && nb_gauche == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 270° anti horaire\n");
	#endif
			rotationListeZone(img,3,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 270;
		}
		// pas de repère
		else
		{
			fprintf(stderr,"Bandes incohérentes Haut Droite\n");
		}
		if(rotation != -1)
		{
			nb_haut = nb_zones(liste_haut);
			removeZone(&liste_haut,nb_haut);
			nb_haut = nb_zones(liste_haut);
			removeZone(&liste_droite,0);
			nb_droite = nb_zones(liste_droite);
		}
	}
// repère situé en bas à droite si la feuille est prise normalement
	else if(repere_bas == 1 && repere_droite == 1)
	{
		if(nb_bas == nb_bandes_horizontales+1 && nb_droite == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation ok\n");
	#endif
			rotation = 0;
		}
		else if(nb_bas == nb_bandes_verticales+1 && nb_gauche == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 90° anti horaire\n");
	#endif
			rotationListeZone(img,1,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 90;
		}
		else if(nb_haut == nb_bandes_horizontales +1 && nb_gauche == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation 180° anti horaire\n");
	#endif
			rotationListeZone(img,2,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 180;
		
		}
		else if(nb_haut == nb_bandes_verticales+1 && nb_droite == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 270° anti horaire\n");
	#endif
			rotationListeZone(img,3,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 270;
		}
		// pas de repère
		else
		{
			fprintf(stderr,"Bandes incohérentes Bas Droite\n");
		}
		if(rotation != -1)
		{
			nb_bas = nb_zones(liste_bas);
			removeZone(&liste_bas,nb_bas);
			nb_bas = nb_zones(liste_bas);
			nb_droite = nb_zones(liste_droite);
			removeZone(&liste_droite,nb_droite);
			nb_droite = nb_zones(liste_droite);
		}
	}
	// repère situé en bas à gauche si la feuille est prise normalement
	else if(repere_bas == 1 && repere_gauche == 1)
	{
		if(nb_bas == nb_bandes_horizontales+1 && nb_gauche == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation ok\n");
	#endif
			rotation = 0;
		}
		else if(nb_haut == nb_bandes_verticales+1 && nb_gauche == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 90° anti horaire\n");
	#endif
			rotationListeZone(img,1,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 90;
		}
		else if(nb_haut == nb_bandes_horizontales +1 && nb_droite == nb_bandes_verticales+1)
		{
	#if DEBUG
			printf("Rotation 180° anti horaire\n");
	#endif
			rotationListeZone(img,2,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 180;
		
		}
		else if(nb_bas == nb_bandes_verticales+1 && nb_droite == nb_bandes_horizontales+1)
		{
	#if DEBUG
			printf("Rotation 270° anti horaire\n");
	#endif
			rotationListeZone(img,3,&liste_haut,&liste_droite,&liste_bas,&liste_gauche);
			rotation = 270;
		}
		// pas de repère
		else
		{
			fprintf(stderr,"Bandes incohérentes Bas Gauche\n");
		}
		if(rotation != -1)
		{
			removeZone(&liste_bas,0);
			nb_bas = nb_zones(liste_bas);
			nb_gauche = nb_zones(liste_gauche);
			removeZone(&liste_gauche,nb_gauche);
			nb_gauche = nb_zones(liste_gauche);
			
		}
	}
/*
=========================
si rotation, recherche des
marques
=========================
*/		
	if(rotation != -1)
	{
#if DEBUG
		printf("Analyse de l'image\n");
#endif
		// analyse de l'image avec les listes contenant les rotations à appliquer
		int **resultats = analyse(img,liste_haut,liste_droite,liste_bas,liste_gauche,rotation,SEUIL_NOIRCIE,POURCENTAGE_NOIRCIE,SEUIL_CROIX,POURCENTAGE_CROIX);
#if DEBUG
		printf("Tracer des droites de correction\n");
#endif
		tracerDroites(img,liste_haut,liste_droite,liste_bas,liste_gauche,rotation);
		nb_haut = nb_zones(liste_haut);
		nb_gauche = nb_zones(liste_gauche);
#if DEBUG
		printResultats(resultats,nb_haut,nb_gauche);
		printf("Sauvegarde des résultats\n");
#endif
		printResultatsFichier(resultats,nb_haut,nb_gauche,data_out);
		liberationResultats(resultats,nb_haut);
		free(resultats);
#if DEBUG
		printf("Rotation de l'image\n");
#endif
		struct image *img_rot = image_new_rotation(img,rotation);
#if DEBUG
		printf("Sauvegarde de l'image\n");
#endif
		image_save(img_rot,image_out);
		destroyImage(img_rot);
	}
/*
=========================
sinon abandon
=========================
*/	
	else
	{
		fprintf(stderr,"Abandon analyse\n");
	}

/*
=========================
destruction des données
=========================
*/		
	destroyListeZone(&liste_haut);
	destroyListeZone(&liste_bas);
	destroyListeZone(&liste_gauche);
	destroyListeZone(&liste_droite);
   destroyImage(img);
   if(rotation != -1)
		return 0;
	else
		return 1;
}
