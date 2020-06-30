<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 25.07.17
 * Time: 14:12
 */
class AT_Import_AC_DB
{
    public function __construct ()
    {
        // tables
        global $wpdb;
        $this->table_amateurs = $wpdb->prefix . 'import_ac_amateurs';
        $this->table_media    = $wpdb->prefix . 'import_ac_media';

        // initiate cronjob func
        add_action( 'at_import_ac_generate_amateur_db', array( &$this, 'generateAmateurDB' ) );

        // initiate cronjob for zipAreas
        if ( get_option( 'at_ac_crawl' ) == '1' ) {
            for ( $i = 1; $i < 10; $i++ ) {
                if ( ! wp_next_scheduled( 'at_import_ac_generate_amateur_db', array( $i ) ) ) {
                    wp_schedule_event( time(), 'weekly', 'at_import_ac_generate_amateur_db', array( $i ) );
                }
            }
        }
    }

    public function generateAmateurDB ( $crawl_zipArea = '1' )
    {
        global $wpdb;

        $filter = array(
            'zipArea' => $crawl_zipArea,
            'limit'   => 700
        );

        $crawler  = new AT_Import_AC_Crawler();
        $amateurs = $crawler->jsonAmateurs( $filter );

        if ( $amateurs ) {
            at_error_log( 'Started cronjob for zipArea ' . $crawl_zipArea . ' (AC, generateAmateurDB)' );

            foreach ( $amateurs as $data ) {
                $setcard = $data['setcard'];

                $uid              = intval( $setcard['id'] );
                $nickname         = ( isset( $setcard['nickname'] ) ? $setcard['nickname'] : '' );
                $url              = ( isset( $setcard['url'] ) ? $setcard['url'] : '' );
                $age              = ( isset( $setcard['age'] ) ? intval( $setcard['age'] ) : 0 );
                $body             = ( isset( $setcard['body'] ) ? $setcard['body'] : '' );
                $bodyhair         = ( isset( $setcard['bodyhair'] ) ? $setcard['bodyhair'] : '' );
                $brasize          = ( isset( $setcard['brasize'] ) ? $setcard['brasize'] : '' );
                $country          = ( isset( $setcard['country'] ) ? $setcard['country'] : '' );
                $ethnic           = ( isset( $setcard['ethnic'] ) ? $setcard['ethnic'] : '' );
                $eyecolor         = ( isset( $setcard['eyecolor'] ) ? $setcard['eyecolor'] : '' );
                $gender           = ( isset( $setcard['gender'] ) ? $setcard['gender'] : '' );
                $haircolor        = ( isset( $setcard['haircolor'] ) ? $setcard['haircolor'] : '' );
                $hairlength       = ( isset( $setcard['hairlength'] ) ? $setcard['hairlength'] : '' );
                $marital          = ( isset( $setcard['marital'] ) ? $setcard['marital'] : '' );
                $piercings        = ( isset( $setcard['piercings'] ) ? $setcard['piercings'] : '' );
                $pubichair        = ( isset( $setcard['pubichair'] ) ? $setcard['pubichair'] : '' );
                $tatoos           = ( isset( $setcard['tatoos'] ) ? $setcard['tatoos'] : '' );
                $zipArea          = ( isset( $setcard['zipArea'] ) ? str_replace( '\'', '', $setcard['zipArea'] ) : '' );
                $glasses          = ( isset( $setcard['glasses'] ) ? intval( $setcard['glasses'] ) : 0 );
                $livedating       = ( isset( $setcard['livedating'] ) ? intval( $setcard['livedating'] ) : 0 );
                $livecam          = ( isset( $setcard['livecam'] ) ? intval( $setcard['livecam'] ) : 0 );
                $smoking          = ( isset( $setcard['smoking'] ) ? intval( $setcard['livecam'] ) : 0 );
                $weblog           = ( isset( $setcard['weblog'] ) ? intval( $setcard['weblog'] ) : 0 );
                $children         = ( isset( $setcard['children'] ) ? intval( $setcard['children'] ) : 0 );
                $height           = ( isset( $setcard['height'] ) ? intval( $setcard['height'] ) : 0 );
                $weight           = ( isset( $setcard['weight'] ) ? intval( $setcard['weight'] ) : 0 );
                $experiences      = ( isset( $setcard['experiences'] ) ? $setcard['experiences'] : array() );
                $lookingfor       = ( isset( $setcard['lookingfor'] ) ? $setcard['lookingfor'] : array() );
                $preferences      = ( isset( $setcard['preferences'] ) ? $setcard['preferences'] : array() );
                $languages        = ( isset( $setcard['languages'] ) ? $setcard['languages'] : array() );
                $tags             = ( isset( $setcard['tags'] ) ? $setcard['tags'] : array() );
                $description      = ( isset( $setcard['description']['18']['de'] ) ? $setcard['description']['18']['de'] : '' );
                $description_sc   = ( isset( $setcard['description']['16']['de'] ) ? $setcard['description']['16']['de'] : '' );
                $aboutMe          = ( isset( $setcard['aboutMe']['18']['de'] ) ? $setcard['aboutMe']['18']['de'] : '' );
                $aboutMe_sc       = ( isset( $setcard['aboutMe']['18']['de'] ) ? $setcard['aboutMe']['18']['de'] : '' );
                $othersAboutMe    = ( isset( $setcard['othersAboutMe']['18']['de'] ) ? $setcard['othersAboutMe']['18']['de'] : '' );
                $othersAboutMe_sc = ( isset( $setcard['othersAboutMe']['16']['de'] ) ? $setcard['othersAboutMe']['16']['de'] : '' );
                $fantasies        = ( isset( $setcard['fantasies']['18']['de'] ) ? $setcard['fantasies']['18']['de'] : '' );
                $fantasies_sc     = ( isset( $setcard['fantasies']['16']['de'] ) ? $setcard['fantasies']['16']['de'] : '' );
                $image_small      = ( isset( $setcard['thumbs']['18']['small'] ) ? $setcard['thumbs']['18']['small'] : '' );
                $image_medium     = ( isset( $setcard['thumbs']['18']['medium'] ) ? $setcard['thumbs']['18']['medium'] : '' );
                $image_large      = ( isset( $setcard['thumbs']['18']['large'] ) ? $setcard['thumbs']['18']['large'] : '' );
                $image_sc_small   = ( isset( $setcard['thumbs']['16']['small'] ) ? $setcard['thumbs']['16']['small'] : '' );
                $image_sc_medium  = ( isset( $setcard['thumbs']['16']['medium'] ) ? $setcard['thumbs']['16']['medium'] : '' );
                $image_sc_large   = ( isset( $setcard['thumbs']['16']['large'] ) ? $setcard['thumbs']['16']['large'] : '' );

                $wpdb->query(
                    "
					REPLACE into {$this->table_amateurs}
					(	
						uid,
						nickname, 
						url, 
						age, 
						body, 
						bodyhair, 
						brasize, 
						country, 
						ethnic, 
						eyecolor, 
						gender, 
						haircolor, 
						hairlength, 
						marital, 
						piercings, 
						pubichair, 
						tattoos, 
						zipArea, 
						glasses, 
						livedating, 
						livecam, 
						smoking, 
						weblog, 
						children, 
						height, 
						weight, 
						experiences, 
						lookingfor, 
						preferences, 
						languages, 
						tags, 
						description, 
						description_sc, 
						aboutMe, 
						aboutMe_sc, 
						othersAboutMe, 
						othersAboutMe_sc, 
						fantasies, 
						fantasies_sc, 
						image_small, 
						image_medium, 
						image_large, 
						image_sc_small, 
						image_sc_medium, 
						image_sc_large
					  )
					VALUES 
					(
						{$uid},
						'" . esc_sql( $nickname ) . "',
						'{$url}', 
						{$age}, 
						'{$body}', 
						'{$bodyhair}', 
						'{$brasize}', 
						'{$country}', 
						'{$ethnic}', 
						'{$eyecolor}', 
						'{$gender}', 
						'{$haircolor}', 
						'{$hairlength}', 
						'{$marital}', 
						'{$piercings}', 
						'{$pubichair}', 
						'{$tatoos}', 
						'" . esc_sql( $zipArea ) . "', 
						{$glasses}, 
						{$livedating}, 
						{$livecam}, 
						{$smoking}, 
						{$weblog}, 
						{$children}, 
						{$height}, 
						{$weight}, 
						'" . json_encode( $experiences ) . "', 
						'" . json_encode( $lookingfor ) . "', 
						'" . json_encode( $preferences ) . "', 
						'" . json_encode( $languages ) . "', 
						'" . json_encode( $tags ) . "', 
						'" . esc_sql( $description ) . "', 
						'" . esc_sql( $description_sc ) . "', 
						'" . esc_sql( $aboutMe ) . "', 
						'" . esc_sql( $aboutMe_sc ) . "', 
						'" . esc_sql( $othersAboutMe ) . "', 
						'" . esc_sql( $othersAboutMe_sc ) . "', 
						'{$fantasies}', 
						'{$fantasies_sc}', 
						'{$image_small}', 
						'{$image_medium}', 
						'{$image_large}', 
						'{$image_sc_small}', 
						'{$image_sc_medium}', 
						'{$image_sc_large}'
					)
					"
                );
            }

            at_error_log( 'Stopped cronjob for zipArea ' . $crawl_zipArea . ' (AC, generateAmateurDB), imported/updated ' . count( $amateurs ) . ' amateurs.' );
        }
    }
}

$AT_Import_AC_DB = new AT_Import_AC_DB();