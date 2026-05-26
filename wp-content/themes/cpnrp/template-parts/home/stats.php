<?php
/**
 * Homepage stats / counter band.
 * Values editable via Appearance → Customize → CPNRP Nastavení → Počítadlo.
 */

$stats = [
	[
		'value'  => (int) get_theme_mod( 'cpnrp_stat_1_value',  1520 ),
		'format' => get_theme_mod( 'cpnrp_stat_1_format', '1 520' ),
		'label'  => get_theme_mod( 'cpnrp_stat_1_label',  'Rodin, kterým pomáháme' ),
		'color'  => 'stat-value--teal-dark',
	],
	[
		'value'  => (int) get_theme_mod( 'cpnrp_stat_2_value',  124 ),
		'format' => get_theme_mod( 'cpnrp_stat_2_format', '124' ),
		'label'  => get_theme_mod( 'cpnrp_stat_2_label',  'Odborných konzultací' ),
		'color'  => 'stat-value--teal',
	],
	[
		'value'  => (int) get_theme_mod( 'cpnrp_stat_3_value',  850 ),
		'format' => get_theme_mod( 'cpnrp_stat_3_format', '850' ),
		'label'  => get_theme_mod( 'cpnrp_stat_3_label',  'Dětí v náhradní péči' ),
		'color'  => 'stat-value--red',
	],
	[
		'value'  => (int) get_theme_mod( 'cpnrp_stat_4_value',  15 ),
		'format' => get_theme_mod( 'cpnrp_stat_4_format', '15' ),
		'label'  => get_theme_mod( 'cpnrp_stat_4_label',  'Let zkušeností' ),
		'color'  => 'stat-value--gold',
	],
];
?>

<section class="home-stats" aria-label="<?php esc_attr_e( 'Naše výsledky', 'cpnrp' ); ?>">
	<div class="container">
		<div class="stats-grid">
			<?php foreach ( $stats as $stat ) : ?>
			<div class="stat-item">
				<p class="stat-value <?php echo esc_attr( $stat['color'] ); ?>"
				   data-count-target="<?php echo esc_attr( $stat['value'] ); ?>"
				   data-count-format="<?php echo esc_attr( $stat['format'] ); ?>">
					0
				</p>
				<p class="stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
