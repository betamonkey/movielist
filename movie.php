<?php
	require_once(dirname(__FILE__) . '/functions.php');

	$movie = Movie::getFromID($_REQUEST['id']);

	if ($movie === false) {
		include(dirname(__FILE__) . '/inc/header.php');
		echo 'No such Movie ID found.';
		include(dirname(__FILE__) . '/inc/footer.php');
		die();
	}

	$omdb = unserialize($movie->omdb);
	unset($omdb['Poster']);
	unset($omdb['Title']);
	unset($omdb['Imdbid']);
	$rowspan = count($omdb) + 3;

	$titleExtra = ' :: ' . $movie->name;

	include(dirname(__FILE__) . '/inc/header.php');
?>




<table id="moviedata"  class="table table-striped table-bordered table-condensed hideable">
	<tbody>
		<tr class="movie">
			<th class="title" colspan=3>
			<?php
				if (!empty($movie->name)) {
					echo $movie->name;
				} else {
					echo $movie->dirname;
					echo ' <span class="label label-important">Unknown</span>';
				}
			?>

			<div class="pull-right movieicons">
			<?=showMovieIcons($movie);?>
			</div>

			</th>
		</tr>
		<tr class="movie">
			<td class="fullposter" rowspan=<?=$rowspan?>>
			<ul class="thumbnails"><li><a href="#" class="thumbnail"><?php
				echo '<img src="', BASEDIR, '/poster/', $movie->id, '" alt="Poster" class="movieposter">';
			?></a></li></ul>
			</td>
		</tr>
		<tr>
			<th class="links">Links</th>
			<td class="links"><?php
				if (!empty($movie->imdbid) && $movie->imdbid != 'N/A') {
					echo '<a href="http://www.imdb.com/title/', $movie->imdbid, '/"><span class="label label-success">IMDB</span></a>';
				} else {
					echo '<span class="label label-important">IMDB</span>';
				}
			?>
			</td>
		</tr>
		<tr>
			<th class="directory">Local Directory</th>
			<td class="directory">
				<?=$movie->dir?>
				<span class="label label-success" data-clipboard-text="<?=htmlspecialchars($movie->dir)?>">Copy to Clipboard</span>
			</td>
		</tr>
		<?php
			foreach ($omdb as $key => $value) {
				$key = htmlspecialchars($key);
				echo '<tr>';
				echo '<th class="', strtolower($key), '">', $key, '</th>';
				echo '<td class="', strtolower($key), '">', htmlspecialchars($value), '</td>';
				echo '</tr>';
			}
		?>

<?php
	if (isset($config['plex']['servers']) && !empty($config['plex']['servers'])) {
		showAJAXPanel('Plex', BASEDIR . 'plex/' . $movie->id);
	}

	showAJAXPanel('Trailer', BASEDIR . 'trailer/' . $movie->id);
?>

	</tbody>
</table>

<?php /* var_dump($movie); */ ?>

<?php
	include(dirname(__FILE__) . '/inc/footer.php');
?>
