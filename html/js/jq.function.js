if(jQuery) (function($){
	$.fn.carouselAn = function(params){
		var conf = $.extend({
			clipBlock:'.itemClip',
			moveBlock:'ul',
			itemBlock:'li',
			playDuration:30,
			sizeScroll:1
		}, params);
		return this.each(function(){
			var c=conf, o=$(this), f=this, tO=null, currentPosition=0;
			$.extend(f,{
				getClip:function(){return o.find(c.clipBlock)},
				getMoveBlock:function(){return f.getClip().find(c.moveBlock)},
				getAllItem:function(){return f.getMoveBlock().children(c.itemBlock)},
				getWidth:function(){return f.getAllItem().size()*f.getMoveBlock().find(c.itemBlock).outerWidth(true)},
				itemTransparent:function(){
					f.getAllItem().each(function(){
						$(this).hover(
							function(){$(this).animate({opacity:1}, 200)},
							function(){$(this).animate({opacity:0.5}, 100)}
						).css({opacity:0.5})
					})
				},
				moveItem:function(){
					if(currentPosition<=-(f.getWidth()/2)){currentPosition=0}
					f.getMoveBlock().css({left:currentPosition-=c.sizeScroll});
				},
				autoRestart:function(){clearTimeout(tO);
					tO=setTimeout(function(){f.moveItem(); f.autoRestart()}, c.playDuration);
				},
				autoPause:function(){clearInterval(tO)}
			});
			f.getAllItem().clone().appendTo(f.getMoveBlock());
			f.getClip()
				.bind('mouseenter',function(){f.autoPause()})
				.bind('mouseleave',function(){f.autoRestart()});
			f.itemTransparent();
			f.autoRestart();
		})
	};
	$.fn.popupAn = function(params){
		var conf = $.extend({}, params);
		return this.each(function(){
			var c=conf, o=$(this), f=this, urlPopup=o.attr('href').replace(/([^.]*)\.(.*)/, "$1-popup.$2");
			$.extend(f,{
				showPopup:function(d){
					$('.viewPopup form').bind('submit', function(e){
						$.ajax({
							url:urlPopup, type:'post',
							dataType:'html',
							data:$(this).serialize(),
							success:function(data, i, z){
								if($(data).find('.itemWarning')){
									$('.viewPopup form').append($(data).find('.itemWarning'));
									f.closePopup();
									
								}else{
									
									f.closePopup();
								}
							},
							complete:function(data){
								alert(data.status);
                                        if(data.status=="302"){
                                                alert(data.getAllResponseHeaders());
                                                location.href=data.getResponseHeader("Location");
                                                return false;
                                        } 
							}
						})
						return false
						
						
						
						
					})
				},
				closePopup:function(){
					$.modal.close();
				},
				loadPopup:function(){
					o.bind('click', function(e){
						
						$.ajax({url:urlPopup, type:'get',
							success:function(data){
								$(data).modal({
									closeHTML: "<a href='#' title='Close' class='itemClose'>Close</a>",
									onShow:f.showPopup,
									onClose:f.closePopup
								})
							},
							error:function(data){alert(data)}
						});
						return false;
					})
				}
			});
			f.loadPopup();
		});
	};
	$(document).ready(function(){
		//$('.userNav a').popupAn();
		$('.stationCarousel').carouselAn();
		$('.unitWBlock table tbody').each(function(){$(this).find('tr:odd').addClass('unitOdd')})
		//$('.unitWBlock table tbody tr:odd').addClass('unitOdd');
		//alert($('table tbody tr').size())
	})
})(jQuery);
