if(jQuery)(function($){
	$.fn.carouselAn = function(params){
		var conf = $.extend({
			clipBlock:'.itemClip',
			moveBlock:'ul',
			itemBlock:'li',
			playDuration:30,
			sizeScroll:2
		}, params);
		return this.each(function(){
			var c=conf, o=$(this), f=this, tO=null, currentPosition=0, s=0, tmp=null;
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
					if(currentPosition<=-tmp){currentPosition=0}
					f.getMoveBlock().css({left:currentPosition-=c.sizeScroll});
				},
				autoRestart:function(){clearTimeout(tO);
					tO=setTimeout(function(){f.moveItem(); f.autoRestart()}, c.playDuration);
				},
				autoPause:function(){clearInterval(tO)}
			});
			s=Math.ceil(f.getClip().width()/f.getWidth());
			tmp=f.getWidth();
			for(i=0; i<=s; i++){
				f.getAllItem().clone().appendTo(f.getMoveBlock());
			};
			f.getClip()
				.bind('mouseenter',function(){f.autoPause()})
				.bind('mouseleave',function(){f.autoRestart()});
			f.itemTransparent();
			f.autoRestart();
		})
	};
	$.fn.inputEmptyAn = function(params){
		var conf = $.extend({
			classEmpty:'inpEmpty'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),t=o.attr('title');
			if(t!=null){
				if(o.val()==''){o.val(t)};
				o.addClass(c.classEmpty)
				o.blur(function(){
					if(o.val()==''){o.val(t); o.addClass(c.classEmpty)}
				});
				o.focus(function(){
					if(o.val()==t){o.val(''); o.removeClass(c.classEmpty)}
				});
				o.parents('form').submit(function(){
					if((o.val()==t)||(o.val()=='')){return false}
				});
			};

		})
	};
	$.fn.checkedInputAn = function(params){
		var conf = $.extend({
			headerBlock:'dt',
			textSelect:'Select all',
			textDeSelect:'Deselect all',
			contentBlock:'dd input[type=checkbox]'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),h=$(c.headerBlock, o),
				s=$('<li><a href="#" title="'+c.textSelect+'">'+c.textSelect+'</a></li>'),
				d=$('<li><a href="#" title="'+c.textDeSelect+'">'+c.textDeSelect+'</a></li>');
			if(h.html() != null){
				h.append($('<ul />').append(s, d))
				s.bind('click', function(){
					$(c.contentBlock, o).attr('checked', true);
					return false;
				})
				d.bind('click', function(){
					$(c.contentBlock, o).attr('checked', false);
					return false;
				})
			}
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
						beforeSend:function(){s.html('<option>'+$.localisationAn('Loading')+'...</option>')},
						success:function(data){s.html(data)}, error:function(){s.html('<option>'+$.localisationAn('Data not loading')+'...</option>')}
					})
				}
			});
			if(k.length && s.length){k.bind('change', function(){f.loadBlock(k)})}
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
			sBlock:'.unitNtHide',
			linkNew:'.viewIList .itemNewB',
			linkDelete:'.linkDelete a',
			maxItems:5,
			cBlock:'.unitNt',
			nBlock:'.unitNtEmpty'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),f=this,s=null,m=0,v=0;
			$.extend(f,{
				getAdd:function(){return $(c.linkNew, o)},
				getEmpty:function(){
					if(m>0){$(c.nBlock, o).addClass('hideElement')}else{$(c.nBlock, o).removeClass('hideElement')}
				},
				getHBlock:function(){s=$(c.sBlock, o).remove().children()},
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
			f.getHBlock();
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
			eBlock:'.unitCategoryEdit',
			nBlock:'.unitCategoryNew',
			tableBlock:'table.viewList',
			hoverClass:'unitHover',
			addCategory:'.itemNewC a',
			noDataRow:'.unitNoData',
			dataClass:'unitData',
			linkWide:'.linkTr'
		}, params);
		return this.each(function(){
			var c=conf,o=$(this),f=this,v=[],e=null,n=null,t=null;
			$.extend(f,{
				getBlock:function(){return o.find(c.tableBlock)},
				getRow:function(){return $('tbody tr:not('+c.noDataRow+')', f.getBlock())},
				getHidden:function(){return $('input[name="itemSelect"]', o)},
				getHBlock:function(){e=$(c.eBlock, o).remove()},
				getNBlock:function(){n=$(c.nBlock, o).remove()},
				bindCategory:function(k, h, r){if(t!=null){t.removeClass('hideElement')}; t=r.before(h); k.remove()}
			});
			if(f.getBlock().length){
				f.getHBlock(); f.getNBlock();
				$(c.addCategory, o).bind('click', function(){
					f.bindCategory(e, n, f.getRow().eq(0)); return false
				})
				$('tbody tr:odd', f.getBlock()).addClass('unitOdd');
				f.getRow().each(function(){var i=$(this);
					i.hover(function(){i.addClass(c.hoverClass)}, function(){i.removeClass(c.hoverClass)})
					if($(c.linkWide, i).length){
						i.addClass(c.dataClass);
						if($('a'+c.linkWide, i).length){
							var l=$(c.linkWide, i); l.replaceWith(l.text());
							i.bind('click', function(){document.location=l.attr('href')});
						}
						if($('span'+c.linkWide, i).length  ){
							var l=$(c.linkWide, i); l.replaceWith(l.text());
							i.bind('click', function(){
								f.bindCategory(n, e, i.addClass('hideElement'));
								$('input[name="itemCategory"]', e).attr('value', l.text());
								$('input[name="idCategory"]', e).attr('value', l.attr('title'));
							});
						};
						$('input[type="checkbox"]', i).bind('click', function(e){var h=$(this);
							if(h.attr('checked')){v.push(h.attr('value')); i.addClass('unitChecked')}else{v.splice($.inArray(h.attr('value'), v), 1); i.removeClass('unitChecked')}
							f.getHidden().attr('value', v.toString()); e.stopImmediatePropagation();
						});
						$('a', i).bind('click', function(e){e.stopImmediatePropagation()})
					}
				})
			}
		})
	};
	$.fn.rtfAn = function(params){
		var conf = $.extend({controlIcon:'bold italic underline | color highlight removeformat | bullets numbering | alignleft center alignright justify | undo redo | link unlink | source'}, params);
		return this.each(function(){
			var c=conf,o=$(this);
			o.cleditor({width:o.innerWidth()-2, height:o.innerHeight()-2, controls:c.controlIcon});
		})
	};
	$.fn.fotoAn = function(params){
		var conf = $.extend({
			moveBlock:'ul',
			itemBlock:'li',
			//prevPage:'itemPrev',
			//nextPage:'itemNext',
			viewItem:'unitSingle',
			timeAnimate:300
		}, params);
		return this.each(function(){
			var c=conf, o=$(this),
				f=this,
				/*prev=$('<span />').addClass(c.prevPage),
				next=$('<span />').addClass(c.nextPage),*/
				view=$('<div />').addClass(c.viewItem),
				fake=$('<div />').addClass('unitFake'),
				innerSpan=$('<span />').css({opacity:0.7});
			$.extend(f,{
				getAllItem:function(){return f.getMoveBlock().children(c.itemBlock)},
				getMoveBlock:function(){return o.find(c.moveBlock)},
				getCurrent:function(i){return f.getAllItem().eq(i)},
				fireCallback:function(n){if($.isFunction(n)){n.call(this)}},
				changeItem:function(currentItem){
					f.getAllItem().removeClass('itemActive');
					f.getCurrent(currentItem).addClass('itemActive');
					fake.removeClass('hideElement').animate({opacity:1}, c.timeAnimate, function(){
						$('<img />').attr('src', f.getCurrent(currentItem).find('a').attr('href'))
					    	.load(function(){
					    		view.empty().append($(this));
					    		fake.height($(this).height());
					    		view.animate({height:$(this).height()}, c.timeAnimate);
					    		fake.animate({opacity:0}, c.timeAnimate, function(){fake.addClass('hideElement')})
					    });
					})
				}
			});
			o.prepend(fake).prepend(view);
			if(f.getAllItem().length<=1){
				f.getMoveBlock().addClass('hideElement');
			}/*else{
				o.prepend(prev).append(next);
			}*/;
			f.getAllItem().each(function(i){
				$('a', this).bind('click', function(e){
					f.changeItem(i);
					e.preventDefault();
				})
			}).filter(':first').find('a').click();
		})
	};
	$.fn.historyAn = function(params){
		var conf = $.extend({
			headBlock:'.unitOrder',
			contentBlock:'.unitHistory',
			hideClass:'hideElement',
			linkBlock:'.itemHistory a'
		}, params);
		return this.each(function(){
			var o=$(this),c=conf,h=$(c.headBlock, o),w=$(c.contentBlock, o);
			if(w.length==1){
				w.addClass(c.hideClass);
				$(c.linkBlock, h).bind('click', function(e){
					w.removeClass(c.hideClass);
					$('html, body').scrollTop(w.offset().top);
					e.preventDefault();
				});
				$(c.linkBlock, w).bind('click', function(e){
					w.addClass(c.hideClass);
					e.preventDefault();
				})
			}
		})
	};
	$.fn.optionAn = function(params){
		var conf = $.extend({
			headBlock:'.itemFilter a',
			contentBlock:'.unitFilter',
			hideClass:'hideElement'
		}, params);
		return this.each(function(){
			var o=$(this),c=conf,h=$(c.headBlock, o),w=$(c.contentBlock, o),a=false;
			if(w.length==1){
				w.addClass(c.hideClass);
				$(h).bind('click', function(e){
					if(a==true){
						h.parent().removeClass('itemFilterOpen'); a=false;
						w.slideUp('slow', function(){w.addClass(c.hideClass)});
					}else{
						h.parent().addClass('itemFilterOpen'); a=true;
						w.slideDown('slow', function(){w.removeClass(c.hideClass)});
					}
					e.preventDefault();
				});
			}
		})
	};
	$.fn.tabsAn = function(params){
		var conf = $.extend({
			headBlock:'.tabsHead',
			contentBlock:'.tabsContent',
			timeAnimate:300
		}, params);
		return this.each(function(){
			var o=$(this),f=this,c=conf,h=$(c.headBlock, o),w=$(c.contentBlock, o),v=$('<div />').addClass('tabsHide');
			$.extend(f,{
				getAllItem:function(){return $('li', h)},
				getCurrent:function(i){return f.getAllItem().eq(i)},
				loadItem:function(currentItem){
					f.getAllItem().removeClass('itemActive');
					f.getCurrent(currentItem).addClass('itemActive');
					//alert(f.getCurrent(currentItem).find('a').attr('href'));
					$.ajax({
						type:'GET',
						url:f.getCurrent(currentItem).find('a').attr('href'),
						beforeSend:function(){
							w.height(w.height()).html(v);
						},
						success:function(d){
							v.html(d);
							w.animate({height:v.children().outerHeight()}, c.timeAnimate, function(){
								w.html(d).css({height:'auto'});
							});
						}
					})
				}
			});
			if(w.length && h.length){
				f.getAllItem().each(function(i){
					$('a', this).bind('click', function(e){
						f.loadItem(i);
						e.preventDefault();
					})
				}).filter(':first').find('a').click();
			}
		})
	};
	$.fn.lpAn = function(params){
		var conf = $.extend({
			autoReload:true
		}, params);
		return this.each(function(){
			var o=$(this),f=this,c=conf,g=false;
			$.extend(f,{
				reloadPage:function(){if(c.autoReload && g){
					parent.location.reload(true);
				}}
			});
			o.lightBoxAn({
				onComplete:function(){
					var a=arguments.callee,i=$('.wrapLightBox');
					$('button[class="itemHide"]',i).bind('click', function(){
						$.lightBoxAn.close();
						return false;
					});
					$('button[class="itemBack"]',i).bind('click', function(){
						$.lightBoxAn.close();
						return false;
					})
					if($('button[class="itemBack"]',i).length){
						g=true;
						setTimeout(function(){$.lightBoxAn.close()},3000);
					};
					$('form',i).submit(function(){
						var l=$(this);
						$.lightBoxAn.showActivity();
						$.post(
							l.attr('action'),
							l.serialize(),
							function(d){$.lightBoxAn({content:d, onComplete:a, onClosed:function(){f.reloadPage()}})}
						);
						return false;

					});
				}
			})
		})
	};
	$(document).ready(function(){
		$('.wrapRAdmin .viewBoxA').dataAn();
		$('.unitBSearch').optionAn();
		$('.unitDRight').historyAn();
		$('.wrapCarousel').carouselAn();
		$('.unitDLeft .unitImg').fotoAn();
		$('.viewBoxB .unitSearch, .viewBoxA .unitEqColumn, .viewBoxA .unitNt, .wrapLSearch, .wrapSSearch').categoryAn();
		$('.areaTxt').rtfAn();
		$('.bodyBox').notificationAn();
		$('.viewPlan').planAn();
		$('.inpDate').datePicker();
		$('.viewNtList').checkedInputAn();
		$('.unitBPagination select').bind('change', function(){$(this).parents('form').submit()})
		$('.unitYellow input:text').inputEmptyAn();
		$('.wrapWSearch').tabsAn();
		$(".viewEHead .itemMail a").lpAn({autoReload:false});
	});
	$(window).load(function(){$('input:file').fileAn()})
})(jQuery);
	