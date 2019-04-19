<?php
function __autoload($classname) {
	require_once(realpath(dirname(__FILE__)) . "/includes/$classname.php");
}

$gms = new GMS(new Cleaner());
$navigation = new Navigation(new Cleaner());
?>
<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<title>The Goodspeed Manuscript Collection : About The Digital Project</title>
	<link href="css/goodspeed.css" type="text/css" rel="stylesheet"/>
	<link href="css/goodspeed-print.css" type="text/css" rel="stylesheet" media="print"/>
	<?php include "templates/head.tmpl.php"; ?>
</head>
<body>

<!-- CONTENT -->
<div id="content">

<!-- HEADER -->
<?php include "templates/header.tmpl.php"; ?>

<!-- NAVIGATION -->
<div class="navigation">
<p id='breadcrumbs'><a href='/'>Home</a> &gt; About The Digital
Project</p>
</div>

<!-- SIDEBAR -->
<?php include "templates/sidebar.php"; ?>

<!-- MAIN COLUMN -->
<div id="maincolumn">

<h1>About The Digital Project</h1>

<ol>
<li><a href="#digitizing">Digitizing the manuscripts</a></li>
<li><a href="#metadata">Metadata</a></li>
<li><a href="#interface">Interface</a></li>
</ol>

<h2 id="digitizing">Digitizing the manuscripts</h2>

<p><a href="images/gms-0142setup-006.jpg"><img src="images/gms-0142setup-006-thumb.jpg" style="border: 1px
solid #888; float: right;
margin: 5px 0 20px 20px; padding: 1px"/></a>
All manuscripts were assessed for condition and ability to be
digitally photographed by a staff team, including the Preservation
Manager in Special Collections, the Head of Conservation, the project
manager and the photographer. The assessment was an opportunity to
record the condition prior to imaging and to discuss handling issues.
Each manuscript required customization of a cradle that holds the
manuscript in place and in focus during photography.  To reduce stress
on the manuscript, handling was minimized by photographing all recto
pages first and then all verso pages. Manuscripts were photographed
using a Betterlight Super 8-K scanning back camera with HID lamps.
Capture was cover to cover, at 600 spi and tiff files were saved as
24-bit tiff files in Adobe RGB 1998 color space. Equipment was
calibrated prior to each manuscript being scanned. Two manuscripts were
photographed prior to the grant period for a pilot study, using a Kodak
DCS Pro 14n Digital Camera.</p>

<h2 id="metadata">Metadata</h2>

<p>File level technical metadata is extracted using JHOVE at the point of
archiving. Structural metadata is recorded in a Microsoft Access database in
order to correlate folios or pages to digital objects. To further aid
discovery, a wide range of the manuscripts' physical features and the content
of both text and image are also captured. These elements encompass heraldic
devices, colophons, inscriptions, names of scribes and artists, writings such
as hypotheses and prologues, lectionary readings, saints' names, and the titles
of miniatures.</p>

<p>In encoding the manuscript descriptions, the metadata specialist utilized
the <a class="printablelink" href="http://www.tei-c.org/release/doc/tei-p5-doc/html/MS.html"> Text
Encoding Initiative's TEI P5: Guidelines for Electronic Text Encoding and
Interchange: Manuscript Description.</a> The <a class="printablelink"
href="https://www1.columbia.edu/sec/cu/libraries/bts/digital_scriptorium/technical/ds-xml/description_dtd/tei_extension.html">TEI
DTD</a> used is available from the Digital Scriptorium of Columbia University,
although to accommodate specific project needs, it was first edited and
extended with local tags.</p>

<p>The process of editing included equating Decoration with the seven
recognized components of the TEI Manuscript Description guidelines which are
Identifier, Heading, Content, Physical Description, History, Additional, and
Manuscript Part. Initially a subset of Physical Description, the new component
Decoration has been repositioned subsequent to Content, not only to emphasize
intellectual substance, but also to augment visibility by providing links from
the record, through encoding, to the digitized imagery of the Web display. New
local tags appended to the DTD are chiefly those needed to describe the content
of miniatures, and the structure of canon tables, headpieces, and initials.</p>

<p>Place names have been checked against the <a class="printablelink"
href="http://www.getty.edu/research/conducting_research/vocabularies/tgn/">
Getty Thesaurus of Geographic Names</a>.  The titles of miniatures are
taken mainly from the <a class="printablelink" href="http://ica.princeton.edu/">Index of
Christian Art</a>, Princeton University. The controlled vocabulary for
subjects and personal names is drawn primarily from the <a class="printablelink"
href="http://www.getty.edu/research/conducting_research/vocabularies/aat/">Getty
Art and Architecture Thesaurus</a>, the <a class="printablelink"
href="http://authorities.loc.gov/cgi-bin/Pwebrecon.cgi?Search_Arg=&amp;Search_Code=NHED_&amp;PID=A_2T1vEOFGtcD2WOx7DJoxWaYP&amp;SEQ=20080929111708&amp;CNT=100&amp;HIST=1">Library of Congress Authority Name File</a>, and the 
<a class="printablelink" href="http://authorities.loc.gov/cgi-bin/Pwebrecon.cgi?DB=local&amp;PAGE=First">
Library of Congress Subject Headings</a>. the primary
system of Armenian alphabet transliteration followed is ALA-LC.</p>

<h3>Select list of resources and printed works used to create Descriptive Records</h3>

<ul>
<li>Kurt Aland, <em>Kurzgefasste Liste der griechischen Handschriften des Neuen Testaments</em> (Berlin; New York: W. de Gruyter, 1994).</li>

<li>Kenneth W. Clark, <em>A Descriptive Catalogue of Greek New Testament Manuscripts in America</em> (Chicago: University of Chicago Press, 1937).</li>

<li><a class="printablelink" href="http://www.getty.edu/research/conducting_research/provenance_index/resources.html">Getty Provenance Research Resources</a></li>

<li><em>Greek-English New Testament: Greek text Novum Testamentum Graece, in the tradition of Eberhard Nestle and Erwin Nestle</em>, edited by Kurt Aland, et al. (Stuttgart: Deutsche Bibelgesellschaft, 1992).</li>

<li>Frederick G. Holweck, <em>A biographical dictionary of the saints: with a general introduction on hagiology.</em> (St. Louis, Mo.; London: B. Herder Book Co., 1924).</li>

<li>Neil R. Ker, <em>Medieval Manuscripts in British Libraries</em>, 5 v. (Oxford, Eng.: Clarendon Press, c1969-2002).</li>

<li>Gregory A. Pass, <em>Descriptive cataloging of Ancient, Medieval, Renaissance, and Early Modern manuscripts</em> (Chicago: Association of College and Research Libraries, 2002).</li>

<li>Lilian M. C. Randall, <em>Medieval and Renaissance Manuscripts in the Walters Art Gallery</em> (Baltimore; London: Johns Hopkins Press, 1989).</li>

<li><a class="printablelink" href="http://library.osu.edu/sites/users/russell.363/RBMS%20Thesauri/index.htm">
RBMS Controlled Vocabularies for Use in Rare Book and Special Collections Cataloging</a></li>

<li>Avedis K. Sanjian, <em>A catalogue of Medieval Armenian Manuscripts in the United States</em> (Berkeley; London: University of California Press, 1976).</li>

<li><em>The SBL handbook of style: For ancient Near Eastern, Biblical, and early Christian Studies</em>, edited by Patrick H Alexander (Peabody, Mass.: Hendrickson Publishers, 1999).</li>

<li>
<a class="printablelink" href="http://sceti.library.upenn.edu/sdm/">Lawrence J. Schoenberg Database of Manuscripts</a></li>
</ul>

<h2 id="interface">Interface</h2>

<p>To support close examination of the manuscripts, each page image is
presented using Zoomify, a software which allows for fast zooming and
panning of the high resolution files over the web. The detailed
bibliographic and structural metadata is used to provide a multi-tiered
access to the manuscripts in the collection, allowing researchers to
delve into the content of the materials in interesting ways.
Researchers can explore the collection using features at the manuscript
level, such as language, date, and place or origin. Using the detailed
structural metadata which has been compiled, the interface also provides
access to significant features within the manuscripts, such as books of
the bible or miniatures of a particular scene, allowing researchers to
find all of the pages across the collection that match such
criteria.</p>

<p>Search results of this type link the researcher directly into the
exact page of each of the manuscripts where the content occurs.  This
allows researchers to compare similar textual and visual content across
the manuscripts in the collection even though the manuscripts have not
been transcribed into full text.</p>

<p>Please direct questions or comments to <a
href="mailto:goodspeed@lib.uchicago.edu">goodspeed@lib.uchicago.edu</a>.</p>

</div><!--/TEXT-->

<div id="footer"></div><!--/FOOTER-->

</div><!--/CONTENT-->

</body>
</html>

