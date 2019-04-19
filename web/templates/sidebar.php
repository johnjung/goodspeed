<div id="sidebar" style="float: right; padding: 0 20px 0 10px; width: 220px;">

<div class="panel">
<h2>Search the Collection</h2>
<form action="/search/index.php">
<p><input class="textinput" type="text" name="search[0]"/></p>
<p><input type="submit" value="Search"/></p>
<p><a href="/search/advancedsearch.html" id="advancedsearchlink">Advanced Search</a></p>
</form>
</div>

<div id="advancedsearchoverlay">
<a href="#" id="advancedsearchclose" style="position: absolute; right: 8px; top: 8px;"<img src="/images/close.gif"/></a>
<h2>Advanced Search</h2>

<form action="/search/index.php" method="get">

<table>
<tr>
<td class="label"/>
<td class="input">
<p><input name="search[0]" type="text"/></p>
</td>

<td class="label"><p>in</p></td>

<td class="input">
<p>
<select name="searchfield[0]">
<option value="searchall">Entire Record</option>
<option value="searchtitle">Title or Type of Text</option>
<option value="searchcommonname">Common Names of Manuscripts</option>
<option value="searchlocation">Places of Origin or Association</option>
<option value="searchdate">Dates of Origin</option>
<option value="searchlanguage">Languages</option>
<option value="searchpersonalname">Names of Individuals or Organizations</option>
<option value="searchmaterial">Materials of Construction</option>
<option value="searchminiature">Imagery</option>
<option value="searchminiaturekeyword">Imagery Keyword</option>
<option value="searchbiblebks">Books of the Bible</option>
</select>
</p>
</td>
</tr>

<tr>
<td><p>and</p></td>

<td>
<p><input name="search[1]" type="text"/></p>
</td>

<td><p>in</p></td>

<td>
<p>
<select name="searchfield[1]">
<option value="searchall">Entire Record</option>
<option value="searchtitle">Title or Type of Text</option>
<option value="searchcommonname">Common Names of Manuscripts</option>
<option value="searchlocation">Places of Origin or Association</option>
<option value="searchdate">Dates of Origin</option>
<option value="searchlanguage">Languages</option>
<option value="searchpersonalname">Names of Individuals or Organizations</option>
<option value="searchmaterial">Materials of Construction</option>
<option value="searchminiature">Imagery</option>
<option value="searchminiaturekeyword">Imagery Keyword</option>
<option value="searchbiblebks">Books of the Bible</option>
</select>
</p>
</td>
</tr>

<tr>
<td><p>and</p></td>

<td>
<p><input name="search[2]" type="text"/></p>
</td>

<td><p>in</p></td> 

<td>
<p>
<select name="searchfield[2]">
<option value="searchall">Entire Record</option>
<option value="searchtitle">Title or Type of Text</option>
<option value="searchcommonname">Common Names of Manuscripts</option>
<option value="searchlocation">Places of Origin or Association</option>
<option value="searchdate">Dates of Origin</option>
<option value="searchlanguage">Languages</option>
<option value="searchpersonalname">Names of Individuals or Organizations</option>
<option value="searchmaterial">Materials of Construction</option>
<option value="searchminiature">Imagery</option>
<option value="searchminiaturekeyword">Imagery Keyword</option>
<option value="searchbiblebks">Books of the Bible</option>
</select>
</p>
</td>
</tr>

<tr>
<td/>
<td/>
<td/>
<td>
<p><input class="submit" type="submit" value="Search"/></p>
</td>
</tr>
</table>

</form>
</div>

<div class="panel">
<h2><a href="/browse/index.php">Browse the Collection</a></h2>
<ul>
<li><a href="/list/index.php?list=listscanned">All Digitized Manuscripts</a></li>
<li><a href="/browse/index.php?browsetype=browsetitle&amp;sort=asc">Title or Type of Text</a></li>
<li><a href="/browse/index.php?browsetype=browsecommonname&amp;sort=asc">Common Names of Manuscripts</a></li>
<li><a href="/browse/index.php?browsetype=browselocation&amp;sort=asc">Places of Origin or Association</a></li>
<li><a href="/browse/index.php?browsetype=browsedate&amp;sort=asc">Dates of Origin</a></li>
<li><a href="/browse/index.php?browsetype=browselanguage&amp;sort=asc">Languages</a></li>
<li><a
href="/browse/index.php?browsetype=browsepersonalname&amp;sort=asc">Names
of Individuals or Organizations</a></li>
<li><a href="/browse/index.php?browsetype=browsematerial&amp;sort=asc">Materials of Construction</a></li>
<li><a href="/browse/index.php?browsetype=browseminiature&amp;sort=asc">Imagery</a></li>
<li><a href="/browse/index.php?browsetype=browseminiaturekeyword&amp;sort=asc">Imagery Keyword</a></li>
<li><a href="/browse/index.php?browsetype=browsebiblebks&amp;sort=asc">Books of the Bible</a></li>
</ul>
</div>

<div class="panel">
<h2><a href="/list/index.php?list=listscanned">Recently Digitized
Manuscripts</a></h2>
<p style="margin: 0 0 5px 0;">as of <?php print $gms->getNewestScanDate(); ?>:</p>
<ul>
<?php foreach(array_slice($gms->getScannedDocList(True), 0, 3) as $d) { ?>
<li><a href="/ms/index.php?list=listscanned&amp;doc=<?php print $d; ?>">Ms. <?php print ltrim($d, '0'); ?>: <?php print $gms->getPopularTitle($d); ?></a></li>
<?php } ?>
</ul>
<p style="text-align: right;"><a
href="/list/index.php?list=listscanned">all digitized
manuscripts...</a></p>
</div>
</div>
