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
	$.fn.fileAn = function(params){
		var conf = $.extend({parentClass:'inpFile'}, params);
		return this.each(function(){
			var c=conf,o=$(this),p=$('<div />').addClass(c.parentClass),v=$('<span />').addClass('txtBg'),b=$('<button><span><strong>Browse...</strong></span></button>');
			o.before(p).css({position:'absolute', opacity:0, width:'auto', zIndex:100});
			p.append(o).append(v).append(b);
			p.bind('mousemove', function(e){
				o.css({left:e.pageX-p.offset().left-(o.width()-20)});
				o.css({top:e.pageY-p.offset().top-(o.height()/2)});
			});
			o.bind('change', function(){v.html(o.attr('value'))})
		})
	};
	$.fn.categoryAn = function(params){
		var conf = $.extend({categorySelect:'select.gCategory', categorySubSelect:'select.gSubCategory', categorySubLink:'get-subcategory.html'}, params);
		return this.each(function(){
			var c=conf,o=$(this),f=this,k=o.find(c.categorySelect),s=o.find(c.categorySubSelect);
			$.extend(f,{
				loadBlock:function(i){
					$.ajax({
						type:'get', dataType:'html', url:c.categorySubLink+'?id='+i.attr('value'),
						beforeSend:function(){s.html('<option>Loading...</option>')},
						success:function(data){s.html(data)}, error:function(){s.html('data not load')}
					})
				}
			});
			k.bind('change', function(){f.loadBlock(k)})
		})
	};
	$.fn.planAn = function(){
		return this.each(function(){
			var o=$(this),i=0;
			$('td', o).bind('click', function(){
				var k=$(this); v=k.index();
				if(i!=v){
					$('tr td:nth-child('+(i+1)+')', o).removeClass('itemChecked');
					$('tr td:nth-child('+(v+1)+')', o).addClass('itemChecked').find('input[type="radio"]').attr('checked', true);
					i=v;
				}
			}).find('input[type="radio"]:checked').click();
		})
	};
	$.fn.notificationAn = function(params){
		var conf = $.extend({
			sBlock:'#unitNotif',
			linkNew:'.viewIList .itemNewB',
			linkDelete:'.linkDelete a',
			maxItems:5,
			cBlock:'.unitNotif',
			nBlock:'.unitNotifEmpty'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),f=this,s=null,m=0,v=0;
			$.extend(f,{
				getAdd:function(){return $(c.linkNew, o)},
				getEmpty:function(){
					if(m>0){$(c.nBlock, o).addClass('hideElement')}else{$(c.nBlock, o).removeClass('hideElement')}
				},
				getBlock:function(){s=$(c.sBlock, o).remove().children()},
				getDelete:function(i){$(c.linkDelete, i).bind('click', function(){i.remove(); m--; f.getEmpty(); return false})},
				getPhpBlock:function(){
					m=$(c.cBlock, o).size(); v=$(c.cBlock, o).size();
					$(c.cBlock, o).each(function(){
						f.getDelete($(this));
					})
				},
				newBlock:function(i){
					var b=s.clone();
					$('input, select', b).each(function(){
						$(this).attr('name', $(this).attr('name')+'['+i+']');
						$(this).attr('id', $(this).attr('id')+i);
					});
					$('label', b).each(function(){
						$(this).attr('for', $(this).attr('for')+i);
					});
					f.getDelete(b);
					b.categoryAn(); return b;
				}
			});
			f.getBlock();
			if(s.length){
				f.getPhpBlock();
				f.getEmpty();
				f.getAdd().bind('click', function(){
					if(m<c.maxItems){m++; v++; $(c.nBlock, o).after(f.newBlock(v)); f.getEmpty()}
					return false;
				})
			}
		})
	};
	$.fn.dataAn = function(params){
		var conf = $.extend({
			tableBlock:'table',
			hoverClass:'unitHover',
			noDataRow:'.unitNoData',
			dataClass:'unitData',
			linkWide:'.linkTr'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),f=this,v=[];
			$.extend(f,{
				getBlock:function(){return o.find(c.tableBlock)},
				getRow:function(){return $('tbody tr:not('+c.noDataRow+')', f.getBlock())},
				getHidden:function(){return $('input[name="itemSelect"]', o)}
			});
			if(f.getBlock().length){
				$('tbody tr:odd', f.getBlock()).addClass('unitOdd');
				f.getRow().each(function(){var i=$(this);
					i.hover(function(){i.addClass(c.hoverClass)}, function(){i.removeClass(c.hoverClass)})
					if($(c.linkWide, i).length){
						i.addClass(c.dataClass);
						var l=$(c.linkWide, i).attr('href');
						$(c.linkWide, i).replaceWith($(c.linkWide, i).html());
						i.bind('click', function(){document.location=l})
						$('input[type="checkbox"]', i).bind('click', function(e){var h=$(this);
							if(h.attr('checked')){v.push(h.attr('value')); i.addClass('unitChecked')}else{v.splice($.inArray(h.attr('value'), v), 1); i.removeClass('unitChecked')}
							f.getHidden().attr('value', v.toString()); e.stopImmediatePropagation();
						});
					}
				})
				
				
				
			}
		})
	};
	$.fn.rtfAn = function(params){
		var conf = $.extend({controlIcon:'bold italic underline | color highlight removeformat | bullets numbering | alignleft center alignright justify | undo redo | link unlink'}, params);
		return this.each(function(){
			var c=conf,o=$(this);
			o.cleditor({width:o.innerWidth()-2, height:o.innerHeight()-2, controls:c.controlIcon});
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
		$('.wrapRAdmin .viewWBox').dataAn();
		$('.stationCarousel').carouselAn();
		$('.viewRBox .unitSearch, .unitWBlock .unitEqColumn, .unitWBlock .unitNotif').categoryAn();
		$('.areaTxt').rtfAn();
		$('.bodyBox').notificationAn();
		$('.viewPlan').planAn();
	});
	$(window).load(function(){$('input:file').fileAn()})
})(jQuery);
