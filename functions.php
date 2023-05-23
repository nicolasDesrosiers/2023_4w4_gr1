<?php
/**
* L'ensemble des fonctions du thème
*/
function enfiler_css() {
wp_enqueue_style('4w4-gr1-principal', // id
        get_template_directory_uri() . '/style.css', // adresse url de style.css
        array(), // définir les dépendances
        filemtime(get_template_directory() . '/style.css'), // le calcul de la version du fichier css
        'all'); // media

wp_enqueue_style("google_font",
                "https://fonts.googleapis.com/css2?family=Righteous&display=swap",
                false);       
}  

add_action( 'wp_enqueue_scripts', 'enfiler_css' );  

/* -------------------------------------- Enregitrement des menus */
function enregistre_menus(){
        register_nav_menus( array(
        'menu_entete' => 'Menu entete',
        'menu_sidebar'  => 'Menu sidebar',
        ) );
}
add_action( 'after_setup_theme', 'enregistre_menus', 0 );

/* -------------------------------------- add_theme_suport */

add_theme_support( 'title-tag' );
add_theme_support( 'custom-logo', array(
    'height' => 150,
    'width'  => 150
) );
add_theme_support('custom-background'); // permet d'utiliser limage arriere plan dans personnalisé (WP)

add_theme_support( 'post-thumbnails' ); // images-mise-en-avant qui peuvent être dimensionnée en plusieurs grandeur.

set_post_thumbnail_size( 150, 150, true ); // 150 pixels wide by 150 pixels tall, true = crop mode

/**
 * Modifie la requete principale de Wordpress avant qu'elle soit exécuté
 * le hook « pre_get_posts » se manifeste juste avant d'exécuter la requête principal
 * Dépendant de la condition initiale on peut filtrer un type particulier de requête
 * Dans ce cas çi nous filtrons la requête de la page d'accueil
 * @param WP_query  $query la requête principal de WP
 */
function cidweb_modifie_requete_principal( $query ) {
        if (    $query->is_home() // si page d'accueil
                && $query->is_main_query() // si requête principale
                && ! is_admin() ) { // si pas dans le tableau de bord
          // $query->set permet de modifier la requête principale               
          $query->set( 'category_name', 'acceuil' ); // filtre les articles de categorie «4w4» slug
          $query->set( 'orderby', 'title' ); // trier selon le champ title
          $query->set( 'order', 'ASC' ); // trier en ordre ascendant
          }
         }
         add_action( 'pre_get_posts', 'cidweb_modifie_requete_principal' );
         
/**
 * Permet de personnalisé chacun des titre du menu cours
 * @param $title : titre du menu à modifier
 *         $item : la structure «li» du menu
 *         $args : objet décrivant l'ensemble des menu de notre site
 *         $depth : Niveau de profondeur du menu (on a retirer ici)     
 */

function perso_menu_item_title($title, $item, $args) {
// Remplacer 'nom_de_votre_menu' par l'identifiant de votre menu
if($args->menu == 'cours') { // on filtre uniquement le menu «cours»
        // Modifier la longueur du titre en fonction de nos besoins
        $sigle = substr($title,4,3);
        $title = substr($title, 7);
        $title = "<code>" .$sigle. "</code>"  ; 
}
if($args->menu == 'note-4w4') { // on filtre uniquement le menu «note-4w4»
        // Modifier la longueur du titre en fonction de nos besoins
        $title = substr($title, 1);
        $title = "<code>" .$title. "</code>"  ; 
}
return $title;
}
add_filter('nav_menu_item_title', 'perso_menu_item_title', 10, 3);    

/**
 * Ajouter la description et l'image mise en avant a chacun des choix du menu évènement 
 */
function add_menu_description_and_thumbnail( $item_output, $item, $depth, $args ) {
        if ( 'evenement'  == $args->menu ||
             'activiter' == $args->menu|| 
             'bloc-archives' == $args->menu ||
             'atelier' == $args->menu) {
            $post_thumbnail_id = get_post_thumbnail_id( $item->object_id );
            if ( $post_thumbnail_id ) {
                $post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'meduim' );
                $item_output = str_replace( '">' . $args->link_before . $item->title, '">' . $args->link_before . '<span class="title">' . $item->title . '</span><span class="description">' . $item->description . '</span><img src="' . esc_url( $post_thumbnail_url[0] ) . '" class="menu-thumbnail" />', $item_output );
            } else {
                $item_output = str_replace( '">' . $args->link_before . $item->title, '">' . $args->link_before . '<span class="title">' . $item->title . '</span><span class="description">' . $item->description . '</span><figure class="img__archive"></figure>', $item_output );
            }
        }
        return $item_output;
    }
    add_filter( 'walker_nav_menu_start_el', 'add_menu_description_and_thumbnail', 10, 4 );

    


/** ------------------------------------------------------------- Enregistrement des Widget */

// Enregistrer le sidebar
function enregistrer_sidebar() {
        register_sidebar( array(
            'name' => __( 'Footer 1', 'nom-de-mon-theme' ),
            'id' => 'footer_1',
            'description' => __( 'Une zone de widget pour afficher des widgets dans le pied de page.', 'nom-de-mon-theme' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ) );

        register_sidebar( array(
                'name' => __( 'Footer 2', 'nom-de-mon-theme' ),
                'id' => 'footer_2',
                'description' => __( 'Une zone de widget pour afficher des widgets dans le pied de page.', 'nom-de-mon-theme' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            ) );

        register_sidebar( array(
                'name' => __( 'Footer 3', 'nom-de-mon-theme' ),
                'id' => 'footer_3',
                'description' => __( 'Une zone de widget pour afficher des widgets dans le pied de page.', 'nom-de-mon-theme' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            ) );

        register_sidebar( array(
                'name' => __( 'entete 1', 'nom-de-mon-theme' ),
                'id' => 'entete_1',
                'description' => __( 'Une zone de widget pour afficher des widgets dans la page acceuil.', 'nom-de-mon-theme' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            ) );    
            
           
    }
    add_action( 'widgets_init', 'enregistrer_sidebar' );