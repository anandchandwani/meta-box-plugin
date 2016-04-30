<?php
/*Plugin Name:contributor*/


/**
 * Register meta box(es).
 */
function wpdocs_register_meta_boxes() {
    add_meta_box( 'meta-box-id', __( 'contributors', 'textdomain' ), 'wpdocs_my_display_callback', 'post' );
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );
 
/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function wpdocs_my_display_callback( $post ) {
global $post;
		$args = array(
	'blog_id'      => $GLOBALS['blog_id'],
	'role'         => 'author',
	'meta_key'     => '',
	'meta_value'   => '',
	'meta_compare' => '',
	'meta_query'   => array(),
	'date_query'   => array(),        
	'include'      => array(),
	'exclude'      => array(),
	'orderby'      => 'login',
	'order'        => 'ASC',
	'offset'       => '',
	'search'       => '',
	'number'       => '',
	'count_total'  => false,
	'fields'       => 'all',
	'who'          => ''
 ); 
 $authors=get_users( $args );
  $values = get_post_meta( $post->ID,'check',true );

    // We'll use this nonce field later on when saving.
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );

 foreach ($authors as $auth ):
 	$checked = '';
 	if(is_array($values) and in_array($auth->ID,$values))
 		$checked = 'checked="checked"';
 	?>

	 <input type="checkbox" name="check[]" value="<?php echo $auth->ID; ?>" <?php echo $checked; ?>><?php echo $auth->display_name; ?></input><br><br>

<?php endforeach;
}
 
/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function wpdocs_save_meta_box( $post_id ) {
    // Save logic goes here. Don't forget to include nonce checks!
     if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
     
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
  
         
    // This is purely my personal preference for saving check-boxes
       
    $chk = isset( $_POST['check'] ) && is_array($_POST['check']) ? $_POST['check'] : 'off';
    update_post_meta( $post_id, "check",$chk );


}
add_action( 'save_post', 'wpdocs_save_meta_box' );
add_filter( 'the_author', 'show_contributor' ); 
 
 function show_contributor( $content ) { 

 global $post;

$result=get_post_meta($post->ID,'check',true);
  if(is_array($result))
  
  {
        $authors='';
        $authors.="<div class='wrap'>";
        $authors.="<h2>Contributor</h2>";
    foreach ($result as $author)
    {
    
         $authors.="<div class='wra'>";
         $authors_display_name=get_the_author_meta( 'display_name', $author );

         $ID=get_the_author_meta( 'ID', $author );
         get_the_author_meta( 'email',$author);
         $url=get_author_posts_url($ID);
         $authors.=get_avatar("hiteshchandwani08@gmail.com");
         $authors.="<div><a href='$url' style='color:rebeccapurple'>".$authors_display_name."</a><div><br>";
         $authors.="</div>";

     } 

       $authors.="</div>";
 }
     return $authors;
		

 }



?>