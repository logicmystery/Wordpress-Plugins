<?php
/**
 * @package ReviewRating
 * @version 0.1
 */
/*
Plugin Name:Review Rating
Plugin URI: http://dualcube.com/
Description: use this short-code:[review-ratings]
Author: 
Version: 0.1
Author URI: 
*/


function reviewRatingDisplay() {
$dataObjects = json_decode(get_option('reviewRating_Data'));
$averageRating = 0;
$totalRating = 0;
$counter = 0;
$ratingsArrayData = Array();
$counter2 = 1;
if(isset($dataObjects)) {
	 foreach($dataObjects as $dataKey => $dataValue) {
		$tempTitleData = get_post_custom_values($dataValue->title);
		$tempTitleDescription = get_post_custom_values($dataValue->description);
		$tempPointData = get_post_custom_values($dataValue->point);
		if(isset($tempPointData[0]) && isset($tempTitleData[0]) && isset($tempTitleDescription[0])){
				$ratingsArrayData[$dataKey]['rating'] = $tempPointData[0];
				$ratingsArrayData[$dataKey]['title'] = $tempTitleData[0];
				$ratingsArrayData[$dataKey]['description'] = $tempTitleDescription[0];
			}
	 }

$dataDisplay = '';


		foreach($ratingsArrayData as $rateData) {
			if(($rateData["rating"] > -1) && ($rateData["rating"] <= 10)) {
				$totalRating = $totalRating + $rateData["rating"];
				$counter++;
			}
		}
		if($counter>0)
		$averageRating = floor(($totalRating/$counter)*10);
$dataDisplay .= 
'
<link media="screen" type="text/css" href="'.plugins_url( "reviewRatings.css" , __FILE__ ).'" rel="stylesheet">
<div class ="reviewRatingBox">
	  <div class="reviewRatingTitle">GAMING ILLUSTRATED RATINGS FOR '.strtoupper(get_the_title()).'</div>';
		if($ratingsArrayData) { 
			$dataDisplay .=   '<div class="reviewRatingHead">
			<div class="reviewRatingHeadLeftBox ratingLeftBox">Rating</div>
			<div class="reviewRatingHeadRightBox ratingRightBox">Description</div>
			</div>
			<div class="clear"></div>';
			$ratingCounter = 0;
			foreach( $ratingsArrayData as $pointRatings):
			if($ratingCounter == 1) {
			  $dataDisplay .= '<div class="raviewRating raviewRatingOdd">';
			  $ratingCounter = -1;
			} else {
			  $dataDisplay .= '<div class="raviewRating">';
			}
			$dataDisplay .= '<div class="reviewRatingLeftBox ratingLeftBox">'.$pointRatings["rating"].'</div>
			<div class="reviewRatingRightBox ratingRightBox"><strong>'.$pointRatings["title"].'</strong><br />
			<span>'.$pointRatings["description"].'</span>
			</div>
			<div class="clear"></div>
			</div>';
			$ratingCounter++;
			endforeach; 
		} 
	 $dataDisplay .='
	  <div class="clear"></div>
	  <div class="raviewRatingTotal">
		<div class="reviewRatingTotalLeftBox ratingLeftBox">'.$averageRating.'%</div>
		<div class="reviewRatingTotalRightBox ratingRightBox">
		  <div class="ratingTotalScoreLabel">OVERALL SCORE</div>
		  <div class="ratingTotalScoreImage"><img src="'.plugins_url( "gi-review-graphic1.jpg" , __FILE__ ).'" width="242" height="67" /></div>
	    </div>
	  </div>
	  <div class="clear"></div>
</div>';

	} 
	return $dataDisplay;
}

function addReviewRatingMenu() {
	if(count($_POST['rating1']) > 0) {
			
		   delete_option('reviewRating_Data', json_encode($_POST));	
		   add_option('reviewRating_Data', json_encode($_POST));                    
		
    }		
	add_options_page('reviewRating Configuration', 'Review Rating Configuration', 'manage_options', 'rate-it', 'addReviewRatingOptions' );

}
function addReviewRatingOptions() {
GLOBAL $wpdb;
$counter = 0;
$reviewRatingvalues = $wpdb->get_col("SELECT meta_key FROM $wpdb->postmeta" );
$dataObjects = json_decode(get_option('reviewRating_Data'));
?>
<div class="wrap metabox-holder">
<h2>ReviewRating Configuration Panel</h2>
	<form method="post" action="">
		<div class="postbox">	
			<div title="Click to toggle" class="handlediv"><br></div>
			<h3 class="hndle">
				<span>List ReviewRating:</span>
				<a name="info"></a>
			</h3>
			<div class="inside" style="display:;">	
			  <div id="ratings">
			   <?php 
			   if(isset($dataObjects)){
				   foreach($dataObjects as $dataKey => $dataValue):
						$counter = $counter+1;
			   ?>
					<div class="ratingBlock">
						<table class="form-table">
							<tbody>
								<label class="headLabel" for="rating<?php echo $counter;?>"><?php echo ucfirst($dataKey); ?></label>
								<tr>
									<td><label for="rating<?php echo $counter;?>">Title</label></td>
									<td>
										<select name="rating<?php echo $counter;?>[title]" id='rating<?php echo $counter;?>Title' style="margin-left:5px;">
										  <option name = "lsoptions" value="">--Select--</option>
											<?php 
											foreach ($reviewRatingvalues as $list):?>
											<option name = "lsoptions" <?php if($list == $dataValue->title) echo "selected"; ?> value="<?php echo $list?>"/><?php echo $list?></option>";
											<?php
											endforeach;?>
										</select>
									</td>
								</tr>						
								<tr>
									<td><label for="rating<?php echo $counter;?>">Point</label>
									</td>
									<td>
										<select name="rating<?php echo $counter;?>[point]" id='rating<?php echo $counter;?>Point' style="margin-left:5px;">
										  <option name = "lsoptions" value="">--Select--</option>
											<?php 
											foreach ($reviewRatingvalues as $list):?>
											<option name = "lsoptions" <?php if($list == $dataValue->point ) echo "selected"; ?> value="<?php echo $list?>"/><?php echo $list?></option>";
											<?php
											endforeach;?>
										</select>
									</td>
								</tr>						
								<tr>
									<td>
										<label for="rating<?php echo $counter;?>">Description</label>
									</td>
									<td>
										<select name="rating<?php echo $counter;?>[description]" id='rating<?php echo $counter;?>Description' style="margin-left:5px;">
										  <option name = "lsoptions" value="">--Select--</option>
											<?php 
											foreach ($reviewRatingvalues as $list):?>
											<option name = "lsoptions" <?php if($list == $dataValue->description) echo "selected"; ?> value="<?php echo $list?>"/><?php echo $list?></option>";
											<?php
											endforeach;?>
										</select>
									</td>
								</tr>
								<tr><td></td>
									<td><span style="display: <?php if($counter > 1){ echo 'block';} else { echo 'none';} ?>; float: right; cursor: pointer;" class="removeRatingBlock">-Remove</span></td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php 
					endforeach;
				} else {
					$counter = $counter+1;
				?>	
					<div class="ratingBlock">
						<table class="form-table">
							<tbody>
								<label class="headLabel" for="rating<?php echo $counter;?>"><?php echo $dataKey; ?></label>
								<tr>
									<td><label for="rating<?php echo $counter;?>">Title</label></td>
									<td>
										<select name="rating<?php echo $counter;?>[title]" id='rating<?php echo $counter;?>Title' style="margin-left:5px;">
										  <option name = "lsoptions" value="">--Select--</option>
											<?php 
											foreach ($reviewRatingvalues as $list):?>
											<option name = "lsoptions" <?php if($list == $dataValue->title) echo "selected"; ?> value="<?php echo $list?>"/><?php echo $list?></option>";
											<?php
											endforeach;?>
										</select>
									</td>
								</tr>						
								<tr>
									<td><label for="rating<?php echo $counter;?>">Point</label>
									</td>
									<td>
										<select name="rating<?php echo $counter;?>[point]" id='rating<?php echo $counter;?>Point' style="margin-left:5px;">
										  <option name = "lsoptions" value="">--Select--</option>
											<?php 
											foreach ($reviewRatingvalues as $list):?>
											<option name = "lsoptions" <?php if($list == $dataValue->point ) echo "selected"; ?> value="<?php echo $list?>"/><?php echo $list?></option>";
											<?php
											endforeach;?>
										</select>
									</td>
								</tr>						
								<tr>
									<td>
										<label for="rating<?php echo $counter;?>">Description</label>
									</td>
									<td>
										<select name="rating<?php echo $counter;?>[description]" id='rating<?php echo $counter;?>Description' style="margin-left:5px;">
										  <option name = "lsoptions" value="">--Select--</option>
											<?php 
											foreach ($reviewRatingvalues as $list):?>
											<option name = "lsoptions" <?php if($list == $dataValue->description) echo "selected"; ?> value="<?php echo $list?>"/><?php echo $list?></option>";
											<?php
											endforeach;?>
										</select>
									</td>
								</tr>
								<tr><td></td>
									<td><span style="display: <?php if($counter > 1){ echo 'block';} else { echo 'none';} ?>; float: right; cursor: pointer;" class="removeRatingBlock">-Remove</span></td>
								</tr>
							</tbody>
						</table>
					</div>
				<?php } ?>
			  </div>
			  <span style="float: right; cursor: pointer;" id="addRating">+Add</span>
			  <script>
			    var ratingMappingCount = <?php echo $counter;?>;
				jQuery(document).ready(function() {
				  jQuery("#addRating").click(function() {
					ratingMappingCount++;
					var ratingBlock = jQuery('.ratingBlock:first').clone(true);
					ratingBlock.find('#rating1Title').attr('name', 'rating'+ratingMappingCount+'[title]').attr('id', 'rating'+ratingMappingCount+'Title').val('');
					ratingBlock.find('#rating1Description').attr('name', 'rating'+ratingMappingCount+'[description]').attr('id', 'rating'+ratingMappingCount+'Description').val('');
					ratingBlock.find('#rating1Point').attr('name', 'rating'+ratingMappingCount+'[point]').attr('id', 'rating'+ratingMappingCount+'Point').val('');
					ratingBlock.find('.headLabel').text('Rating'+ratingMappingCount+'#');
					ratingBlock.find('.removeRatingBlock').css('display', 'block');
					jQuery('#ratings').append(ratingBlock);
				  });
				  jQuery('.removeRatingBlock').click(function() {
					jQuery(this).closest('.ratingBlock').remove();
				  });
				});
			  </script>
			</div>
			<input type ='submit' Value = 'Save Settings'>
		</div>
	</form>
</div>
<?php 
}

function reviewRatingShortCodeManager(){
	$reviewRatingTabledata = reviewRatingDisplay2();
	return $reviewRatingTabledata;
}

add_action('admin_menu', 'addreviewRatingMenu');
add_shortcode( 'review-ratings', 'reviewRatingDisplay' );
?>
