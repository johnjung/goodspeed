<div id="browsepanel" style="float: right; padding: 0 20px 0 10px; width: 175px;">

<div>
<h2>Sort and Filter</h2>
<form>
<input type="hidden" name="browsetype" value="<?php echo $clean['browsetype']; ?>"/>
<p>
<label>Sort:</label><br/>
<?php if (array_key_exists('sort', $clean) && $clean['sort'] == 'countdsc') { ?>
<input class="radioinput" type="radio" name="sort" value="asc">Standard sort</input><br/>
<input class="radioinput" type="radio" name="sort" value="countdsc" checked="checked">By number of results</input>
<?php } else { ?>
<input class="radioinput" type="radio" name="sort" value="asc" checked="checked">Standard sort</input><br/>
<input class="radioinput" type="radio" name="sort" value="countdsc">By number of results</input>
<?php } ?>
<label>Filter:</label><br/>
<?php if (array_key_exists('show', $clean) && $clean['show'] == 'scanned') { ?>
<input class="checkboxinput" type="checkbox" name="show" value="scanned" checked="checked">Show scanned manuscripts only.</input>
<?php } else { ?>
<input class="checkboxinput" type="checkbox" name="show" value="scanned">Show scanned manuscripts only.</input>
<?php } ?>
</p>
<p><input type="submit" value="submit"/></p>
</form>
</div>

</div>

