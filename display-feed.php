<?php
// usage [zk-instagram-feed username="username" number="9" size="320"]

add_shortcode('zk-instagram-feed', 'zkif_display_instagram_feed');
function zkif_display_instagram_feed($atts){
	// Attributes
	extract( 
		shortcode_atts(
			array(
				'username' => 'zellersamuel',
				'number' => '9',
				'size'	=> "320"
			),
			$atts
		) 
	);
	
	ob_start()
	?>
	<style>
		.zk-insta{
			display: grid;
			grid-template-columns: 1fr 1fr 1fr;
			grid-gap: 10px;
		}
		.zk-insta a{
			display: block;
			text-decoration: none;
			color: transparent;
		}
		@media (max-width: 850px){
			.zk-insta{
				grid-template-columns: 1fr 1fr;

			}
		}
		
		@media (max-width: 450px){
			.zk-insta{
				grid-template-columns: 1fr;

			}
		}
		
	</style>
	<div class="zk-insta">
		<?php
		$number_of_posts = (int) $number;
		$cache_duration = 2; // Cache duration in hour
		$insta_posts=zkif_get_instagram_feed($username, $cache_duration);

		for($i=0; $i<$number_of_posts; $i++): ?>
			<a class="zk-insta__item" href="<?php echo $insta_posts[$i]['link']; ?>">
				<img src="<?php echo $insta_posts[$i][$size]; ?>">
			</a>
		<?php
		endfor;
		?>
	</div
	<?php
		return ob_get_clean();
}

