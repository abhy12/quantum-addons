<?php

/**
 * get all template content and file names
 *
 *
 * @param array $template_paths path of the templates
 * @param string $format format of the templates files without dot(.)
 * @return array multidimensional
 */
function quantum_addons_get_templates( $template_paths = [], $format )  {
   $templates = [];

   foreach( $template_paths as $path )  {
      ///template folder exist and readable
      if( file_exists( $path ) && is_dir( $path ) && is_readable( $path ) )  {

         ///select only specified file
         $template_files = array_filter( glob( $path."/*.$format*" ), 'is_file' );

         foreach( $template_files as $file )  {
            if( !is_readable( $file ) )  continue;

            $base_name = basename( $file, "." . $format );
            ///replace underscore(_) and spaces with dash(-)
            $file_name = trim( preg_replace( '/[_\s]/', '-', $base_name ) );

            while( key_exists( $file_name, $templates ) )  {
               $i = 2;
               $underscore_position = strpos( $file_name, "_" );
               if( $underscore_position )  {
                  $file_name = substr( $file_name, 0, $underscore_position );
               }

               $file_name += "_" . $i++;
            }

            $templates[$file_name] = quantum_addons_remove_html_comments( file_get_contents( $file ) );
         }
      }
   }

   return $templates;
}

function quantum_addons_remove_html_comments( $content = '' )  {
   return preg_replace( '/<!--(.|\s)*?-->/', '', $content );
}