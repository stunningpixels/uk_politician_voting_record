<?

/*

SCRAPER
USING https://github.com/paquettg/php-html-parser

*/

echo "Politician Voting Record Scraper\n";

require "vendor/autoload.php";
use PHPHtmlParser\Dom;


$dom = new Dom;
$dom->loadFromUrl('http://www.publicwhip.org.uk/division.php?date=2015-01-20&number=133&display=allpossible');
$votes = $dom->find('#votetable tr');
$results = array();
$header = null;

foreach ($votes as $key => $vote){
  if($header === null) {
    $header[0] = $vote->find("")[0]->text;
    $header[1] = $vote->find("")[1]->text;
    $header[2] = $vote->find("")[2]->text;
    $header[3] = $vote->find("")[3]->text;
  }else {
    $results[$key] = array(
      'name'           => $vote->find("")[0]->find("")[0]->text,
      'constituency'   => $vote->find("")[1]->find("")[0]->text,
      'party'          => $vote->find("")[2]->text,
      'vote'           => $vote->find("")[3]->text,
    );
  }
}

foreach($results as $result) {
  scraperwiki::save_sqlite(array('name'), array(
    'name'          => $result['name'], 
    'constituency'  => $result['constituency'],
    'party'  => $result['party'],
    'vote'  => $result['vote'],
  ));
}