<?php


class Uou_Atmf_Ajax_Frontend_Request {




    public function __construct(){

        add_action( "wp_ajax_nopriv_atmf_do_filter", array ( $this, 'atmf_do_filter' ) );
        add_action( "wp_ajax_atmf_do_filter",array( $this,'atmf_do_filter'));
    }



    public function atmf_do_filter(){

        $filter = $_POST['filter'];
        $post_type = $filter['post_type'];


        if(isset( $filter['sort_meta'] ) ){
            $sort_meta = $filter['sort_meta'];
        }

        // taxonomy query building

        $tax_query = array();
        $build_array = array();




        if( isset( $filter['alltaxonomies'] ) ){

            foreach ( $filter['alltaxonomies'] as $key => $terms_id) {

                $taxonomy_terms = array();

                if( is_array($terms_id) ){

                    foreach ($terms_id as $term_key => $term_value) {

                        if($term_value == 'true'){
                            $taxonomy_terms[] = $term_key;
                        }
                    }

                    if( !empty($taxonomy_terms) ){

                        $build_array['taxonomy'] = $key;
                        $build_array['field'] = 'id';
                        $build_array['terms'] = $taxonomy_terms;
                        $tax_query[] = $build_array;
                    }

                }else{


                        $build_array['taxonomy'] = $key;
                        $build_array['field'] = 'id';
                        $build_array['terms'] = $terms_id;
                        $tax_query[] = $build_array;
                }
            }
        }




        // Meta query building

        $meta_query = array();
        $build = array();



        if( isset($filter['metadata'] ) ){

            foreach ( $filter['metadata'] as $meta_key => $metas_id) {



                $meta_keys = array();

				
				
				if($meta_key == "an_finalizare_constructie")
			{
				$arr1 = array();
						
				 if( is_array($metas_id) && isset( $metas_id['1977'] ) && $metas_id['1977'] == "true" )	
			 {
				
						for($t=1920;$t<=1977;$t++)
						$arr1[] = $t;
						
			 }
			  if( is_array($metas_id) && isset( $metas_id['2000'] ) && $metas_id['2000'] == "true" )	
			 {
				
						for($t=1978;$t<=2000;$t++)
						$arr1[] = $t;
						
			 }
			  if( is_array($metas_id) && isset( $metas_id['2015'] ) && $metas_id['2015'] == "true" )	
			 {
				
						for($t=2001;$t<=2020;$t++)
						$arr1[] = $t;
						
			 }
			 
						$build['value'] = $arr1;
						$build['key'] = "an_finalizare_constructie";
                        $build['compare'] = 'IN';
						$build['type'] = 'numeric';
				        $meta_query[] = $build;
						
			 
			 continue;
			}
			if($meta_key == "localizare_cartier")
			{
				$s = $metas_id;
			$arr1 = unserialize(stripslashes($s)); 
				 
						$build['value'] = $arr1;
						$build['key'] = "localizare_cartier";
                        $build['compare'] = 'IN';
						$build['type'] = 'numeric';
				        $meta_query[] = $build;
			


			continue;
			}	
				
				
				
				
				
				
				
                    // for range 
                    if( is_array($metas_id) && isset( $metas_id['start'] ) ){

                        $build['value'] = array( $metas_id['start'] , $metas_id['end']);
                        $build['key'] = $meta_key;
                        $build['type'] = 'numeric';
                        $build['compare'] = 'BETWEEN';

                        $meta_query[] = $build;



                    }
					
                    // check with true value 
                    if( is_array($metas_id) ){

                        foreach( $metas_id as $m_key => $m_value ) {

                            if( $m_value == 'true' ){

                                $meta_keys[] = $m_key;

                            }
                        }


                        if( !empty($meta_keys) ){

                            $build['key'] = $meta_key;
                            $build['compare'] = 'IN';
                            $build['value'] = $meta_keys;
                            $meta_query[] = $build;

                        }

                    }


                    if(!is_array($metas_id)){


                            $build['key'] = $meta_key;
                            $build['compare'] = 'IN';
                            $build['value'] = $metas_id;
                            $meta_query[] = $build;

                    }



			

            } // end of foreach metadata

        }


        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => -1,
            'tax_query'      => $tax_query ,
            'meta_query'     => $meta_query
        );

//print_r($args);



        $posts = get_posts($args);


        $result =array();
global $wpdb;
        foreach($posts as $key=>$post){
			
            $data = array();
            $data['post_title'] = $post->post_title;
			
			$b1 = get_post_meta($post->ID, 'bucuresti', true);
			
				if($b1 == 1)
				{
					$str3="";
					if((get_post_meta($post->ID, 'localizare_cartier',true)))
					{
				$str2 = get_post_meta($post->ID, 'localizare_cartier', true);
				
					$str3 = "";
					foreach($str2 as $str)
					{
					
					$s1 = $wpdb->get_var("select name from wp_terms where term_id = '".$str."'");	
					$str3.= $s1." ";
					}
					$str3 = trim($str3);
					}
					
					$str4 = "";
					if(get_post_meta($post->ID, 'localizare_sector', true))
					{
					$str4 = get_post_meta($post->ID, 'localizare_sector', true);
					$s4 = $wpdb->get_var("select name from wp_terms where term_id = '".$str4."'");		
					}
					$s2 = $wpdb->get_var("select name from wp_terms where term_id = '".$str."'");
					$bur = "Bucuresti, ".$s4. " ".$str3;
					
				}
				else
				{
					
					
					if(get_post_meta($post->ID, 'localizare_localitate', true))
					{
					$str4 = get_post_meta($post->ID, 'localizare_localitate', true);
					$s4 = $wpdb->get_var("select name from wp_terms where term_id = '".$str4."'");		
					}
					if(get_post_meta($post->ID, 'localizare_judet', true))
					{
					$str3 = get_post_meta($post->ID, 'localizare_judet', true);
					$s3 = $wpdb->get_var("select name from wp_terms where term_id = '".$str3."'");		
					}
					$bur = $s4." ". $s3;
					
				}
			
                $data['bucuresti'] = $bur;
				
			
		
            $data['post_content'] = $post->post_content;
            $data['post_permalink'] = get_the_permalink($post->ID);
            $data['post_date'] = $post->post_date;
            $data['comment_count'] = $post->comment_count;
            $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large');
            if($large_image_url) {
                $data['post_thumbnail'] =  $large_image_url[0];
            } else {
                $data['post_thumbnail'] =  'http://placehold.it/400x400';
            }


            //@ added in version 1.2.0
            // for sorting facility

            if( isset( $sort_meta ) && !empty($sort_meta) ){
                foreach( $sort_meta as $sort_key => $sort_value ){

                    $label = $sort_value['label'];
                    if( !isset( $data[$label] ) ){
                        $data[$label] = get_post_meta( $post->ID , $label , true );
                    }



                }
            }

             //end of sorting data




            $result[] = $data;
        }


        wp_send_json( $result );

        wp_die();
    }

}


new Uou_Atmf_Ajax_Frontend_Request();


