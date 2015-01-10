<?php

$cite = "Digital facsimile of the Bodleian First Folio of Shakespeare's plays, Arch. G c.7";

// extract a line or block quotes
function extract_quotation ($short, $start, $end) {

  $xml_str = open_file($short);

  $reader = new XMLReader();

  if (!$reader->open($xml_str)) {
    die("Failed to open First Folio");
  }
  $act = $scene = $line = 0;
  $lines = array();
  $title = '';
  while($reader->read()) {
  
  if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'title') {
      if ($reader->getAttribute('type')=='statement' && !$title) {
          $title = $reader->readString();
      }
  } 

  if ($reader->nodeType == XMLReader::ELEMENT && $reader->name == 'div') {
        $divtype = $reader->getAttribute('type');
        if ($divtype == 'act') {
          $act = $reader->getAttribute('n');
        }
        if ($divtype == 'scene') {
          $scene = $reader->getAttribute('n');
        }
      }

    // get the lines
    if ($reader->nodeType == XMLReader::ELEMENT && ($reader->name == 'l' || $reader->name == 'p')) {
       $line = $reader->getAttribute('n');
       $node = new SimpleXMLElement($reader->readOuterXML());
       if ($end && ($line >=  $start && $line <= $end)) {
         if ($node->choice) {
           $lines[] = array('type'=>$reader->name,'title'=>$title,'act'=>$act, 'scene'=> $scene, 'lineno'=> $line, 'text'=>$reader->readString(), 'orig'=>$node->choice->orig, 'corr'=>$node->choice->corr);
         } else {
           $lines[] = array('type'=>$reader->name,'title'=>$title,'act'=>$act, 'scene'=> $scene, 'lineno'=> $line, 'text'=>$reader->readString());
         }
       } else if ($start && !$end) {
           if ($line == $start) {
              if ($node->choice) {
                $lines[] = array('type'=>$reader->name,'title'=>$title,'act'=>$act, 'scene'=> $scene, 'lineno'=> $line, 'text'=>$reader->readString(), 'orig'=>$node->choice->orig, 'corr'=>$node->choice->corr);
              } else {
                $lines[] = array('type'=>$reader->name,'title'=>$title,'act'=>$act, 'scene'=> $scene, 'lineno'=> $line, 'text'=>$reader->readString());
              }
           }
       }
    }
  }
  $reader->close();

  return $lines;
}

/**
*  Convert the short code string into a valid URL
*
*  @param string
*  Text short code given by the shortcode link
*  @return string
*  Url of the XML file
*/
function open_file($code) { 
   return "http://firstfolio.bodleian.ox.ac.uk/download/xml/F-$code.xml";
}

?>
