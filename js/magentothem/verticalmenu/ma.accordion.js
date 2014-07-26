$jq(document).ready(function(){			
	$jq('#ma-accordion ul.level0').before('<span class="head"><a href="javascript:void(0)"></a></span>');			
	$jq('#ma-accordion li.level0.active').addClass('selected');
	// applying the settings			
	$jq("#ma-accordion  li  span").click(function(){
		if(false == $jq(this).next('ul').is(':visible')) {
			$jq('#ma-accordion ul').slideUp(300);
		}
		$jq(this).next('.level0').slideToggle(300);
		
		if($jq(this).parent().hasClass('selected')) {
			$jq(this).parent().addClass('unselected');
		}
		
		$jq('#ma-accordion li.selected').each(function() {
				$jq(this).removeClass('selected');
		});
		if(!$jq(this).parent().hasClass('unselected')) {
			$jq(this).parent().addClass('selected');
		}
		$jq('#ma-accordion li.unselected').each(function() {
				$jq(this).removeClass('unselected');
		});
	});
});