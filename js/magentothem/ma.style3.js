/**
 * jqFancyTransitions - $jq plugin
 * @version: 1.8 (2010/06/13)
 * @requires $jq v1.2.2 or later 
 * @author Ivan Lazarevic
 * Examples and documentation at: http://www.workshop.rs/projects/jqfancytransitions
 
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
**/

(function($jq){var opts=new Array;var level=new Array;var img=new Array;var links=new Array;var titles=new Array;var order=new Array;var imgInc=new Array;var inc=new Array;var stripInt=new Array;var imgInt=new Array;$jq.fn.jqFancyTransitions=$jq.fn.jqfancytransitions=function(options){init=function(el){opts[el.id]=$jq.extend({},$jq.fn.jqFancyTransitions.defaults,options);img[el.id]=new Array();links[el.id]=new Array();titles[el.id]=new Array();order[el.id]=new Array();imgInc[el.id]=0;inc[el.id]=0;params=opts[el.id];if(params.effect=='zipper'){params.direction='alternate';params.position='alternate';}
if(params.effect=='wave'){params.direction='alternate';params.position='top';}
if(params.effect=='curtain'){params.direction='alternate';params.position='curtain';}
stripWidth=parseInt(params.width/params.strips);gap=params.width-stripWidth*params.strips;stripLeft=0;$jq.each($jq('#'+el.id+' img'),function(i,item){img[el.id][i]=$jq(item).attr('src');links[el.id][i]=$jq(item).next().attr('href');titles[el.id][i]=$jq(item).attr('alt')?$jq(item).attr('alt'):'';$jq(item).hide();});$jq('#'+el.id).css({'background-image':'url('+img[el.id][0]+')','width':params.width,'height':params.height,'position':'relative','background-position':'top left'});$jq('#'+el.id).append("<div class='ft-title' id='ft-title-"+el.id+"' style='position: absolute; bottom:0; left: 0; z-index: 1000; color: #fff; background-color: #000; '>"+titles[el.id][0]+"</div>");if(titles[el.id][imgInc[el.id]])
$jq('#ft-title-'+el.id).css('opacity',opts[el.id].titleOpacity);else
$jq('#ft-title-'+el.id).css('opacity',0);if(params.navigation){$jq.navigation(el);$jq('#ft-buttons-'+el.id).children().first().addClass('ft-button-'+el.id+'-active');}
odd=1;for(j=1;j<params.strips+1;j++){if(gap>0){tstripWidth=stripWidth+1;gap--;}else{tstripWidth=stripWidth;}
if(params.links)
$jq('#'+el.id).append("<a href='"+links[el.id][0]+"' class='ft-"+el.id+"' id='ft-"+el.id+j+"' style='width:"+tstripWidth+"px; height:"+params.height+"px; float: left; position: absolute;outline:none;'></a>");else
$jq('#'+el.id).append("<div class='ft-"+el.id+"' id='ft-"+el.id+j+"' style='width:"+tstripWidth+"px; height:"+params.height+"px; float: left; position: absolute;'></div>");$jq("#ft-"+el.id+j).css({'background-position':-stripLeft+'px top','left':stripLeft});stripLeft+=tstripWidth;if(params.position=='bottom')
$jq("#ft-"+el.id+j).css('bottom',0);if(j%2==0&&params.position=='alternate')
$jq("#ft-"+el.id+j).css('bottom',0);if(params.direction=='fountain'||params.direction=='fountainAlternate'){order[el.id][j-1]=parseInt(params.strips/2)-(parseInt(j/2)*odd);order[el.id][params.strips-1]=params.strips;odd*=-1;}else{order[el.id][j-1]=j;}}
$jq('.ft-'+el.id).mouseover(function(){opts[el.id].pause=true;});$jq('.ft-'+el.id).mouseout(function(){opts[el.id].pause=false;});$jq('#ft-title-'+el.id).mouseover(function(){opts[el.id].pause=true;});$jq('#ft-title-'+el.id).mouseout(function(){opts[el.id].pause=false;});clearInterval(imgInt[el.id]);imgInt[el.id]=setInterval(function(){$jq.transition(el)},params.delay+params.stripDelay*params.strips);};$jq.transition=function(el,direction){if(opts[el.id].pause==true)return;stripInt[el.id]=setInterval(function(){$jq.strips(order[el.id][inc[el.id]],el)},opts[el.id].stripDelay);$jq('#'+el.id).css({'background-image':'url('+img[el.id][imgInc[el.id]]+')'});if(typeof(direction)=="undefined")
imgInc[el.id]++;else
if(direction=='prev')
imgInc[el.id]--;else
imgInc[el.id]=direction;if(imgInc[el.id]==img[el.id].length){imgInc[el.id]=0;}
if(imgInc[el.id]==-1){imgInc[el.id]=img[el.id].length-1;}
if(titles[el.id][imgInc[el.id]]!=''){$jq('#ft-title-'+el.id).animate({opacity:0},opts[el.id].titleSpeed,function(){$jq(this).html(titles[el.id][imgInc[el.id]]).animate({opacity:opts[el.id].titleOpacity},opts[el.id].titleSpeed);});}else{$jq('#ft-title-'+el.id).animate({opacity:0},opts[el.id].titleSpeed);}
inc[el.id]=0;buttons=$jq('#ft-buttons-'+el.id).children();buttons.each(function(index){if(index==imgInc[el.id]){$jq(this).addClass('ft-button-'+el.id+'-active');}else{$jq(this).removeClass('ft-button-'+el.id+'-active');}});if(opts[el.id].direction=='random')
$jq.fisherYates(order[el.id]);if((opts[el.id].direction=='right'&&order[el.id][0]==1)||opts[el.id].direction=='alternate'||opts[el.id].direction=='fountainAlternate')
order[el.id].reverse();};$jq.strips=function(itemId,el){temp=opts[el.id].strips;if(inc[el.id]==temp){clearInterval(stripInt[el.id]);return;}
$jq('.ft-'+el.id).attr('href',links[el.id][imgInc[el.id]]);if(opts[el.id].position=='curtain'){currWidth=$jq('#ft-'+el.id+itemId).width();$jq('#ft-'+el.id+itemId).css({width:0,opacity:0,'background-image':'url('+img[el.id][imgInc[el.id]]+')'});$jq('#ft-'+el.id+itemId).animate({width:currWidth,opacity:1},1000);}else{$jq('#ft-'+el.id+itemId).css({height:0,opacity:0,'background-image':'url('+img[el.id][imgInc[el.id]]+')'});$jq('#ft-'+el.id+itemId).animate({height:opts[el.id].height,opacity:1},1000);}
inc[el.id]++;};$jq.navigation=function(el){$jq('#'+el.id).append("<a href='#' id='ft-prev-"+el.id+"' class='ft-prev'>prev</a>");$jq('#'+el.id).append("<a href='#' id='ft-next-"+el.id+"' class='ft-next'>next</a>");$jq('#ft-prev-'+el.id).css({'position':'absolute','top':params.height/2-15,'left':0,'z-index':1001,'line-height':'30px','opacity':0.7}).click(function(e){e.preventDefault();$jq.transition(el,'prev');clearInterval(imgInt[el.id]);imgInt[el.id]=setInterval(function(){$jq.transition(el)},params.delay+params.stripDelay*params.strips);});$jq('#ft-next-'+el.id).css({'position':'absolute','top':params.height/2-15,'right':0,'z-index':1001,'line-height':'30px','opacity':0.7}).click(function(e){e.preventDefault();$jq.transition(el);clearInterval(imgInt[el.id]);imgInt[el.id]=setInterval(function(){$jq.transition(el)},params.delay+params.stripDelay*params.strips);});$jq("<div id='ft-buttons-"+el.id+"'></div>").insertAfter($jq('#'+el.id));$jq('#ft-buttons-'+el.id).css({'text-align':'right','padding-top':5,'width':opts[el.id].width});for(k=1;k<img[el.id].length+1;k++){$jq('#ft-buttons-'+el.id).append("<a href='#' class='ft-button-"+el.id+"'>"+k+"</a>");}
$jq('.ft-button-'+el.id).css({'padding':5});$jq.each($jq('.ft-button-'+el.id),function(i,item){$jq(item).click(function(e){e.preventDefault();$jq.transition(el,i);clearInterval(imgInt[el.id]);imgInt[el.id]=setInterval(function(){$jq.transition(el)},params.delay+params.stripDelay*params.strips);})});}
$jq.fisherYates=function(arr){var i=arr.length;if(i==0)return false;while(--i){var j=Math.floor(Math.random()*(i+1));var tempi=arr[i];var tempj=arr[j];arr[i]=tempj;arr[j]=tempi;}}
this.each(function(){init(this);});};$jq.fn.jqFancyTransitions.defaults={width:500,height:332,strips:10,delay:5000,stripDelay:50,titleOpacity:0.7,titleSpeed:1000,position:'alternate',direction:'fountainAlternate',effect:'',navigation:false,links:false};})($jq);