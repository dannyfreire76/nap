<?php
// BME WMS
// Page: Supplement Facts page
// Path/File: /sup_facts/index.php
// Version: 1.8
// Build: 1801
// Date: 01-24-2007

header('Content-type: text/html; charset=utf-8');
include '../includes/main1.php';
$line_hgt = 950;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Learn How Dream Boost Works | <?php echo $website_title; ?></title>
<?php
include '../includes/meta1.php';
?>
<link rel="stylesheet" type="text/css" media="screen" href="/includes/reset.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/core.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/site_styles.css">
<link rel="stylesheet" type="text/css" media="screen" href="/includes/wmsform.css">
<script type="text/javascript" src="/includes/js_funcs1.js"></script>
<script type="text/javascript">
	$(function() {//on doc ready
		var arr1 = new Image();
		arr1.src = '<?=$current_base?>images/callout_left.gif';

		var arr2 = new Image();
		arr2.src = '<?=$current_base?>images/callout_right.gif';

		$('a[@bubble_pop]').each(function(){
			var $showThis = $( $(this).attr('bubble_pop') );
			var $theTrigger = $(this);
			$theTrigger.hover(
				function () {
					var trigWidth = $theTrigger.width();
					var popDims = findPos( $theTrigger.get(0) );
                    var atRightEdge = false;
					var showLPos =  popDims[0];

                    if ( showLPos + $showThis.width() > $('body').width() ) {
                        atRightEdge = true;
                    }

                    var arrLPos = popDims[0] + trigWidth - 5;

                    if ( atRightEdge ) {
                        arrLPos = popDims[0];
                    }

					$('#call_arrow')
						.css('left', arrLPos + 'px')
						.css('top', (popDims[1] - $theTrigger.height() - 24)+'px');

					if ( atRightEdge ) {
						showLPos = popDims[0] - $showThis.width() + trigWidth;
						$('#call_arrow').attr('src', '<?=$current_base?>images/callout_left.gif');
					}
					else {
						$('#call_arrow').attr('src', '<?=$current_base?>images/callout_right.gif');
					}

					$showThis
						.css('left',showLPos+'px')
						.css('top', (popDims[1] - $showThis.height() - $theTrigger.height() - 26)+'px');

					$('#call_arrow').show()
					$showThis.show();
				  },
				function () {
					$showThis.hide();
					$('#call_arrow').hide()
				}
			);
		})
	});
</script>

<style type="text/css">
	.callout {
		background-color: #FFFFFF !important;
		border: 2px solid #000080 !important;
		padding: 8px !important;
		width: 500px !important;
	}

    .bubble_link {
        font-size: 14px;
    }

</style>
</head>
<body>

<?php
include '../includes/head1.php';
?>


<table border="0" width="95%">

<tr><td><h2>Supplement Facts</h2></td></tr><!--

<tr><td align="left" class="style4">Supplement Facts</td></tr>
<tr><td>&#160;</td></tr>
<tr><td align="left" class="style3">Directions for Proper Use</td></tr>

<tr><td align="left" class="style2">Adults take 1-2 tablets before bed.</td></tr>
 -->

<table border="0">
<tr><td align="left" class="style3">Serving Size: 1 Tablet</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td><td align="center" class="style3">Amount per Serving</td><td align="center" class="style3">% Daily Value</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#BComp" class="bubble_link">B-1 Thiamin (as Thiamin Mononitrate)</a></td><td align="center" class="style2">1.4 mg</td><td align="center" class="style2">100%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#BComp" class="bubble_link">B-2 Riboflavin</a></td><td align="center" class="style2">10 mg</td><td align="center" class="style2">590%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#BComp" class="bubble_link">B-3 Niacin (as Niacinamide)</a></td><td align="center" class="style2">15 mg</td><td align="center" class="style2">75%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#BComp" class="bubble_link">B-5 Pantothenic Acid</a></td><td align="center" class="style2">15 mg</td><td align="center" class="style2">150%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#BComp" class="bubble_link">B-6 Pyrodixine</a></td><td align="center" class="style2">10 mg</td><td align="center" class="style2">500%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#BComp" class="bubble_link">B-9 Folic Acid</a></td><td align="center" class="style2">200 mcg</td><td align="center" class="style2">50%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#BComp" class="bubble_link">B-12 (as Cyanocobalabin)</a></td><td align="center" class="style2">100 mcg</td><td align="center" class="style2">1610%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#VitC" class="bubble_link">Vitamin C (as Ascorbic Acid)</a></td><td align="center" class="style2">10 mg</td><td align="center" class="style2">16.67%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#VitD" class="bubble_link">Vitamin D</a></td><td align="center" class="style2">2 mcg</td><td align="center" class="style2">20%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#CALC" class="bubble_link">Calcium</a></td><td align="center" class="style2">60 mg</td><td align="center" class="style2">6%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#MAG" class="bubble_link">Magnesium</a></td><td align="center" class="style2">30 mg</td><td align="center" class="style2">7.5%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#ZINC" class="bubble_link">Zinc</a></td><td align="center" class="style2">3 mg</td><td align="center" class="style2">45%</td></tr>
<tr><td align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#MELA" class="bubble_link">Melatonin</a></td><td align="center" class="style2">500 mcg</td><td align="center" class="style2">*</td></tr>
<tr><td align="left" class="style2">Proprietary Blend</td><td align="center" class="style2">665 mg</td><td align="center" class="style2">*</td></tr>
<tr><td colspan="3" align="left" class="style2"><a href="javascript:void(0)" bubble_pop="#CT" class="bubble_link">Calea&nbsp;Ternifolia&nbsp;(Calea&nbsp;Z.)</a>, <a href="javascript:void(0)" bubble_pop="#ME" class="bubble_link">Mugwort&nbsp;Extract</a>, <a href="javascript:void(0)" bubble_pop="#WLE" class="bubble_link">Wild Lettuce Extract</a>, <a href="javascript:void(0)" bubble_pop="#PF" class="bubble_link">Passionflower&nbsp;Extract</a>, <a href="javascript:void(0)" bubble_pop="#L5" class="bubble_link">L-5-Hydroxytryptaphan&nbsp;(5-HTP)</a>, <a href="javascript:void(0)" bubble_pop="#GT" class="bubble_link">Green&nbsp;Tea&nbsp;Extract</a>, <a href="javascript:void(0)" bubble_pop="#DMAE" class="bubble_link">Dimethylaminoethanol&nbsp;Powder&nbsp;(DMAE)</a>, <a href="javascript:void(0)" bubble_pop="#VIN" class="bubble_link">Vinpocetine</a>.</td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td colspan="3" align="left" class="style2">* Daily Value not established</td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td colspan="3" align="left" class="style2">Other Ingredients: Magnesium Stearate, Silicon Dioxide, Dicalcium Phosphate, Croscamellose Sodium</td></tr>
<tr><td colspan="3">&nbsp;</td></tr>
<tr><td colspan="3" align="left" class="style2">This product is free from yeast, wheat, milk or milk derivatives, lactose, sugar, preservatives, artificial color, and artificial flavor.</td></tr>
</table>
</td></tr>

<tr><td>&nbsp;</td></tr>
</table>

<div class="callout no_display absolute" id="WLE">
	<b>Herb: Wild Lettuce Extract (Lactuca virosa)</b>
	<br />
	Wild Lettuce is a popular ingredient in some sleep tonics, and has been used historically to aid sleep because of its mild sedative effect. In fact, it is so well known for this, that it is sometimes called Lettuce Opium (Note: it contains NO opiates or any illegal substances; the term is due strictly to its well-known effects).
	<p class="italic bold">
		Use in Dream Boost: To assist in the relaxation of the body and to promote healthy sleep.
	</p>
</div>

<div class="callout no_display absolute" id="PF">
	<b>Herb: Passionflower (Passiflora incarnate)</b>
	<br />
	Used traditionally among Native Americans in North America, passionflower has a long history of use to treat insomnia, anxiety, epilepsy, hysteria, and pain management. Variations of the species can be found throughout the world and is linked to most cultures as a traditional remedy for sleep. More recently, scientific research has discovered that passionflower contains trace amounts of beta-carboline harmala alkaloids which have beneficial anti-depressive properties.
	<p class="italic bold">
		Use in Dream Boost: To relax the body and help maintain continuous sleep.
	</p>
</div>

<div class="callout no_display absolute" id="ZINC">
	<b>Zinc</b>
	<br />
	Zinc is used by the body in hundreds of enzymes that impact or regulate many bodily functions. Like Vitamin B complex, many people do not consume sufficient zinc in their diets. Zinc may increase REM sleep as well.
	<p class="italic bold">
		Use in Dream Boost: To work together with other vitamins and minerals to provide the components necessary for healthy sleep and healthy living.
	</p>
</div>

<div class="callout no_display absolute" id="MAG">
	<b>Magnesium</b>
	<br />
	Magnesium is a vital component of a healthy human diet. Recent studies have shown that magnesium has been effective to relax the body and can help induce sleep. Magnesium deficiency may be responsible for nervousness that prevents sleep.
	<p class="italic bold">
		Use in Dream Boost: To work together with other vitamins and minerals to provide the components necessary for healthy sleep and healthy living.
	</p>
</div>

<div class="callout no_display absolute" id="CALC">
	<b>Calcium</b>
	<br />
	Calcium is an important component of a healthy diet. Calcium is essential for the normal growth and maintenance of bones and teeth, and calcium requirements must be met throughout life. Calcium has a sedative effect on the body and has shown to even help relax the body. Calcium deficiency in the body causes restlessness and wakefulness. Vitamin D is needed to absorb calcium properly.
	<p class="italic bold">
		Use in Dream Boost: To work together with other vitamins and minerals to provide the components necessary for healthy sleep and healthy living.
	</p>
</div>

<div class="callout no_display absolute" id="VitD">
	<b>Vitamin D</b>
	<br />
	Vitamin D plays an important role in the maintenance of organ systems and the absorption of calcium throughout the body. Proper levels of Vitamin D helps with food absorption, proper bone formation, and enhanced immune system functioning.
	<p class="italic bold">
		Use in Dream Boost: To work together with other vitamins and minerals to provide the components necessary for healthy living.
	</p>
</div>

<div class="callout no_display absolute" id="VitC">
	<b>Vitamin C</b>
	<br />
	Vitamin C is an essential nutrient needed for health living. It is the most widely taken dietary supplement in the world.  It optimizes the effectiveness of 5-HTP by supporting its conversion to serotonin.  Vitamin C also plays a role in relation and stress reduction.
	<p class="italic bold">
		Use in Dream Boost: To work together with other vitamins and minerals to provide the components necessary for healthy sleep and healthy living.
	</p>
</div>

<div class="callout no_display absolute" id="BComp">
	<b>B-Complex</b>
	<br />
	The B-vitamins are eight, water-soluble vitamins that play important roles in cell metabolism. The B-vitamins work together to deliver a number of health benefits to the body including enhancing immune and nervous system function, promoting cell growth, easing stress, helping with depression, and preventing cardiovascular disease. All B-vitamins are water soluble and are dispersed throughout the body. Most of the B-vitamins must be replenished daily, since any excess is excreted in the urine. Vitamin B-12 supplementation, in particular, produces good results in the treatment of sleep-wake rhythm disorders, presumably the result of improved melatonin secretion.
	<p class="italic bold">
		Use in Dream Boost: To work together with other vitamins and minerals to provide the components necessary for healthy sleep and healthy living.
	</p>
</div>

<div class="callout no_display absolute" id="CT">
	<b>Herb: Calea Zacatechichi (Calea ternifolia)</b>
	<br />
	Calea ternifolia, also know as calea zacatechichi and as the "dream herb,"  is a plant originally used by the indigenous Chontal Indians of the Mexican state of Oaxaca for oneiromancy (a form of divination based on dreams). It has been scientifically demonstrated that extracts of this plant increase reaction times and the frequency and/or recollection of dreams. Calea ternifolia activates the memory continuity facilitator in the brain which prevents the mental jumping around most people experience while dreaming. In addition to its use in dream study, it is also being used by health care providers in different Latin and South American countries, including hospitals in El Salvador, as a medicine against malaria.
	<p class="italic bold">
		Use in Dream Boost: To assist in the relaxation of the body and increase coherent dreaming.
	</p>
</div>

<div class="callout no_display absolute" id="GT">
	<b>Herb: Green Tea Extract (Camellia sinensis)</b>
	<br />
	Green tea originates from China and has been used in many cultures from Japan to the Middle East. Over the last few decades, green tea has begun to be subjected to many scientific and medical studies to determine the extent of its long-purported health benefits, with some evidence suggesting regular green tea drinkers may have lower chances of heart disease and developing certain types of cancer. Green tea is high in polyphenols (antioxidants) and contains a small amount of caffeine. Green tea also contains L-theanine, a unique amino acid that crosses the blood-brain barrier to increase dopamine and GABA levels in the brain, and inhibits the stimulatory properties of caffeine.
	<p class="italic bold">
		Use in Dream Boost: The catalyst that fuels and binds all the other ingredients together.
	</p>
</div>

<div class="callout no_display absolute" id="ME">
	<b>Herb: Mugwort Extract (Artemisia vulgaris)</b>
	<br />
	Mugwort has been used for a variety of medicinal purposes for centuries, and is known to cause a dreamy state of consciousness. Mugwort is known to increase the vividness of normal dreaming and induce lucid dreams, as well as aiding dream recall upon waking.
	<p class="italic bold">
		Use in Dream Boost: To assist in the relaxation of the body and increase coherent dreaming.
	</p>
</div>

<div class="callout no_display absolute" id="L5">
	<b>Brain Fuel: L-5-HTP (5-hydroxytryptophan)</b>
	<br />
	5-Hydroxytryptophan or 5-HTP is a naturally-occurring amino acid and is a precursor to the neurotransmitter serotonin. 5-HTP flows easily through the blood-brain barrier where it converts into serotonin, the most important initiator of sleep and a necessary component of good overall sleep quality. 5-HTP has been reported to optimizing sleep cycles by simultaneously increase REM and deep sleep stages without increasing total sleep time.
	<p class="italic bold">
		Use in Dream Boost: To assist in initial drowsiness, help maintain continuous sleep, and increase dreaming ability, vividness, and recall.
	</p>
</div>

<div class="callout no_display absolute" id="DMAE">
	<b>Brain Fuel: DMAE (2-dimethylaminoethanol)</b>
	<br />
	DMAE, found naturally in fishes like sardines and anchovies, has been reported to produce nootropic ("smart brain") effects including improved human cognitive abilities and functions. DMAE also works with the B Vitamins to increases the acetylcholine (a neurotransmitter) in the brain by flowing easily through the blood-brain barrier where it is converted into choline. During REM sleep, choline is added to the B-5 vitamin to make the acetylcholine needed for healthy sleep and dreams.
	<p class="italic bold">
		Use in Dream Boost: To promote healthy and productive sleep cycles and increase dreaming ability, vividness, and recall.
	</p>
</div>

<div class="callout no_display absolute" id="MELA">
	<b>Brain Fuel: Melatonin</b>
	<br />
	Melatonin is a natural hormone that regulates sleep in humans by causing drowsiness and maintaining healthty sleep cycles. Melatonin is primary produced by the pineal gland, located in the brain, and regulates our natural cycles of sleeping when it's dark and waking when it's light. Since peak human production of Melatonin is at around 18 years of age followed by a slow, steady decline, melatonin has become a popular supplement for use by adults in treating sleep related issues. Unfortunately, most supplements contain large amounts of melatonin, proving 3-30 times the amount needed for healthy and productive sleep, and can produce adverse effects. Dream Boost contains 500 mcg (0.5 mg) per tablet, the ideal amount for safe and proper use.
	<p class="italic bold">
		Use in Dream Boost: To assist in initial drowsiness and help maintain continuous sleep.
	</p>
</div>

<div class="callout no_display absolute" id="VIN">
	<b>Vinpocetine (Vinca minor L.)</b>
	<br />
	Vinpocetine is a derivative of vincamine, which is extracted from the periwinkle plant. Vinpocetine is reported to increase cerebral bloodflow and can be used to aid memory and improve mental function. Currently in Eastern Europe, vinpocetine is a popular treatment for cerebrovascular disorders and age-related memory impairment.
	<p class="italic bold">
		Use in Dream Boost: To increase dreaming ability, vividness, and recall.
	</p>
</div>

<img src="<?=$current_base?>images/callout_right.gif" class="absolute no_display" id="call_arrow" />

<?php
include '../includes/foot1.php';
mysql_close($dbh);
?>
