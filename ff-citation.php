<?php
/**
 * Plugin Name: First Folio Citation
 * Plugin URI: 
 * Description: Shortcode to cite the Bodleian first folio data in a post
 * Version: 0.0.1
 * Author: Iain Emsley
 * Author URI: http://www.austgate.co.uk
 * License: GPL2
 */

/*  Copyright 2014  Iain Emsley  (email : iain_emsley@austgate.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include 'ffparse.php';
include 'ff-format.php';

//add quotation
add_shortcode( 'ffcite', 'ffquote_shortcode');

/**
*  Function to add the ffcite shortcode into Wordpress
*
*  @param Array $atts
*  Array of parameters
*/
function ffquote_shortcode($atts) {
  extract(
    shortcode_atts(
      array(
        'id' => '',
        'start' => '',
        'end' => '',
      ),
      $atts)
  );
 $folio = new ffparse();
 $fmt = new format();
 $quote = $folio->extract_quotation ($atts['id'], $atts['start'], $atts['end']);
 return $fmt->format_citation(sizeof($quote), $quote,$atts['id']);
}
