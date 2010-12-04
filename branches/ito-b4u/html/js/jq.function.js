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
	$.fn.inputFile = function(params){
		var conf = $.extend({
			parentClass:'inpFile'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),p,v=$('<span class="txtBg" />'),b=$('<button><span><strong>Browse...</strong></span></button>');
			o.css({position:'absolute', opacity:0, width:'auto',zIndex:100});
			p=o.wrap('<div class="'+c.parentClass+'" />').parent();
			p.append(v).append(b);
			p.bind('mousemove', function(e){
				o.css({left:e.pageX-p.offset().left-(o.width()-20)});
				o.css({top:e.pageY-p.offset().top-(o.height()/2)});
			});
			o.bind('change', function(){v.html(o.attr('value'))})
		})
	};
	$.fn.changeCategory = function(params){
		var conf = $.extend({
			categorySelect:'select[name="category"]',
			categorySubSelect:'select[name="subcategory"]',
			categorySubLink:'get-subcategory.html'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),f=o.find(c.categorySelect),s=o.find(c.categorySubSelect);
			f.bind('change', function(){
				$.ajax({
					type:'get',
					dataType:'html',
					url:c.categorySubLink+'?id='+$(this).attr('value'),
					beforeSend:function(){s.html('<option>Loading...</option>')},
					success:function(data){s.html(data)},
					error:function(){s.html('data not load')}
				})
			})
		})
	};
	$.fn.mailAn = function(params){
		var conf = $.extend({
			tableBlock:'.unitMail',
			hoverClass:'unitHover',
			noMailClass:'unitNoMail'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),f=this;
			$.extend(f,{
				getBlock:function(){return o.find(c.tableBlock)},
				getRow:function(){return f.getBlock().find('tbody tr')},
				getDelete:function(){return o.find(c.deleteLink)},
				elSelect:function(){
					var a=[];
					f.getRow().each(function(){
						var i=$(this).find('input[type="checkbox"]');
						if(i.attr('checked')){a.push(i.attr('value'))}
					})
					o.find('input[name="itemSelect"]').attr('value', a.toString());
				}
			});
			if(f.getBlock().length){
				$('tbody tr:odd', f.getBlock()).addClass('unitOdd');
				$('form').submit(function(){f.elSelect()})
				f.getRow().each(function(){
					var it=$(this);
					if(!it.hasClass(c.noMailClass)){
						it.hover(function(){it.addClass(c.hoverClass)}, function(){it.removeClass(c.hoverClass)}
						).bind('click', function(){
							var l=it.find('a').attr('href');
							if(l!=undefined){
								document.location=it.find('a').attr('href');
							}else{
								alert('link not found');
							}
						});
						it.find('input[type="checkbox"]').bind('click', function(e){
							e.stopImmediatePropagation();
						});
					}
				})
			}
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
		$('.viewWBox').mailAn();
		$('.stationCarousel').carouselAn();
		$('input:file').inputFile();
		$('body').changeCategory();
	})
})(jQuery);
