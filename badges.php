<?php

/**
*
* Path to csv file
* eg camptix-export-2016-03-04.csv
*/
$filename = 'output_3_4.csv';

/**
*
*  Used in a method which checks if the person is any of the following:
* attendee
* speaker
* volunteer
* organizer
* undead deity
*
* Used below to echo the correct term by checking ID.
*/
$corporeal_entity_type = '';

/**
 *
 * The url for a gravatar fallback image;
 * This is used if the person does not have an existing gravatar image k
 */
$fallback_wapuu = urlencode( 'https://2016.lancasterpa.wordcamp.org/files/2016/03/fallback.jpg' );

/**
 *
 * The url for the front badge image
 * These are split into two columns with column-count.
 */
$badge_front = 'https://2016.lancasterpa.wordcamp.org/files/2016/03/front.jpg';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>WordCamp Lancaster Badge Demo</title>
	<style>
		@font-face {
    		font-family: 'montserratregular';
    		src: url('font/montserrat-regular-webfont.eot');
    		src: url('font/montserrat-regular-webfont.eot?#iefix') format('embedded-opentype'),
         url('font/montserrat-regular-webfont.woff2') format('woff2'),
         url('font/montserrat-regular-webfont.woff') format('woff'),
         url('font/montserrat-regular-webfont.ttf') format('truetype'),
         url('font/montserrat-regular-webfont.svg#montserratregular') format('svg');
    		font-weight: normal;
    		font-style: normal;
		}
		html,
		body,
		body:before,
		body:after {
			float: none;
			margin: 0;
			padding: 0;
		}
		* {
			box-sizing: border-box;
		}
		main {
			column-count: 2;
			display: block;
			margin: 0 auto;
			width: 8.5in;
		}
		article {
			font-family: 'montserratregular', sans-serif;
			width: 4.25in;
			height: 11in;
			background: url('<?= $badge_front; ?>') no-repeat center center;
			background-size: contain;
			float: left;
			display: block;
			margin: 0 auto;
			text-align: center;
			page-break-before: always;
			page-break-inside: avoid;
         -webkit-column-break-inside: avoid;
         break-inside: avoid-column;
		}

		figure {
			position: absolute;
			margin: 9.2in 0 0 0.53in;
			padding: 0;
			text-align: left;
			background: transparent;
			height: 2in;
			width: 3.21in;
			padding: .25in .25in .25in 0;
		}

		figcaption {
			float: left;
			padding-left: .1in;
		}

		img {
			float: left;
			padding: 0;
			width: .82in;
			height: .82in;
			border-right: 6px solid #f8cd00;
		}

		small {
			text-transform: uppercase;
			font-size: 10px;
		}

		hr {
			margin: 0;
			width: 2in;
		}

		.name,
		.twitter {
			margin: 0;
		}

		.name {
			font-size: .164in;
		}

		.attendee-1190 .name {
			font-size: .15in;
		}

		.twitter {
			color: #6986b5;
		}
	</style>
</head>
<body>
	<main>
	<!-- Make sure to use Firefox to print these badges.
	Some other browsers (like Chrome) don't respect some
	CSS properties that we use to specify
	where page breaks should be. -->
	<?php

	// IDs of all volunteers
	// One not listed, as they're a speaker as well,
	// so deferred to having them listed there
	$volunteers = array( '1117',
								'1118',
								'1119',
								'1157',
								'1121',
								'1471',
								'743',
								'206'
							);

	// IDs of all organizers
	$organizers = array( '1225',
								'1221',
								'1224',
								'1106',
								'1261',
								'1113'
							);

	// IDs of all speakers
	// Note: missing the following: Casey, Cameron
	$speakers 	= array( '1262',
								'891',
								'889',
								'783',
								'1306',
								'892',
								'893',
								'1450',
								'285',
								'1063',
								'954',
								'1011',
								'963',
								'888',
								'689',
								'386',
								'955',
								'1402',
								'1162',
								'1529'

							);

	// Keynote panel speakers. Missing Cameron, How Carson
	$keynote_panel_speakers = array(
								'1098',
								'1110',
								'1218'
							);

	if ( $data = fopen( $filename, 'r' ) ) {
		// Snag the first row as column headers
		$cols = fgetcsv( $data );

		while ( ( $row = fgetcsv( $data ) ) ) {
			// Change it to an associative array!
			$row = array_combine( $cols, $row );

			if ( ! empty( $row['Your twitter handle (if you have one)'] ) ) {
				$row['Your twitter handle (if you have one)'] = '@' . ltrim( $row['Your twitter handle (if you have one)'], '@' );
			}

			// What type of entity are they?
			if ( in_array( $row['Attendee ID'], $volunteers ) ) {
				// We haz volunteers
				$corporeal_entity_type = 'volunteer';
			} elseif ( in_array( $row['Attendee ID'], $organizers ) ) {
				// Oh snap it's us
				$corporeal_entity_type = 'organizer';
			} elseif ( in_array( $row['Attendee ID'], $speakers ) ) {
				// Our wonderful speakers
				$corporeal_entity_type = 'speaker';
			} elseif ( in_array( $row['Attendee ID'], $keynote_panel_speakers ) ) {
				// Our wonderful speakers
				$corporeal_entity_type = 'keynote_panel_speaker';
			} elseif ( $row['Attendee ID'] === '1160' ) {
				// It's our photographer!
				$corporeal_entity_type = 'photographer';
			} else {
				// Our meat and potatoes
				$corporeal_entity_type = 'attendee';
			}

			?>
			<article class="<?= 'attendee-' . $row['Attendee ID']; ?>">
				<figure>
					<img src="http://2.gravatar.com/avatar/<?= md5( strtolower( trim( $row['E-mail Address'] ) ) ) ?>?s=500&d=<?=$fallback_wapuu; ?>" />
					<figcaption>
					<? #TODO: switch() to determine if person is attendee, speaker, or volunteer ?>
						<small><?= str_replace('_',' ', $corporeal_entity_type); ?></small>
						<hr />
						<h3 class="name"><?= $row['First Name'] ?> <?= $row['Last Name'] ?></h3>
						<h4 class="twitter"><?= $row['Your twitter handle (if you have one)'] ?></h4>
					</figcaption>
				</figure>
			</article>
			<?php
		}

		// Clean up!
		fclose( $data );
	}
	?>
	</main>
</body>
</html>
