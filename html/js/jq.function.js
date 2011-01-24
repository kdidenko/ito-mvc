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
			wS:100,
			hS:100,
			classOverlay:'overlayPopup',
			transparentOverlay:.6
		}, params);
		return this.each(function(){
			var o=$(this), f=this, c=conf, l=o.attr('href'), v=$('<div />').addClass(c.classOverlay), k=$('<div />').addClass('viewBox'), ki=$('<div />').addClass('bodyBox'), tc=$('<div />').addClass('cT').append($('<em />').addClass('cL'), $('<em />').addClass('cR')), bc=$('<div />').addClass('cB').append($('<em />').addClass('cL'), $('<em />').addClass('cR'));
			$.extend(f, {
				createL:function(){
					v.css({position:'absolute', top:0, left:0, right:0, bottom:0, width:'100%', zIndex:100, opacity:c.transparentOverlay});
					k.css({zIndex:200});
					k.append(
						tc, $('<div />').addClass('cL').append($('<div />').addClass('cR').append(ki)), bc
					)
					ki.html('1111')
					$('body').append(v).append(k);
				},


				sizeV:function(){
					if($(window).height()<$(document).height()){
						v.css({height:$(document).height()+'px'});
					}else{
						v.css({height:'100%'});
						if(ie6){$('html,body').css('height','100%')}
					}
				}
			});
			o.bind('click', function(){
				f.createL();
				
				return false;
			})
		})
	};
	$.fn.lightAn = function(params){
		var conf = $.extend({
			// animation when appears
	        appearEffect:"fadeIn",
	        overlaySpeed:300,
	        lightboxSpeed:"fast",
	        // animation when dissapears
	        disappearEffect: "fadeOut",
	        overlayDisappearSpeed: 300,
	        lightboxDisappearSpeed: "fast",
	        // close
	        closeSelector: ".itemClose",
	        closeClick:true,
	        closeEsc:true,
	        // behavior
	        destroyOnClose:false,
	        // callbacks
	        onLoad:function(){},
	        onClose:function(){},
	        // style
	        classOverlay:'overlayPopup',
	        zIndex: 999,
	        centered: true,
	        modalCSS:{top:'40px'},
	        overlayCSS:{background:'#000', opacity:.6}
		}, params);
		return this.each(function(){
			var c=conf, o=$(this), v=$('<div />').addClass(c.classOverlay), f=this,
				i=$('<iframe />').attr('id', 'lbIframe'), ie6=($.browser.msie && $.browser.version < 7),
				urlPopup=o.attr('href');
			$.extend(f,{
				setOPosition:function(){
	                var s = o[0].style;
	                if ((o.height()+80>=$(window).height())&&(o.css('position')!='absolute'||ie6)){
	                    var topOffset = $(document).scrollTop() + 40;
	                    o.css({position:'absolute', top:topOffset+'px', marginTop:0})
	                    if(ie6){s.removeExpression('top')}
	                }else if(o.height()+ 80<$(window).height()){
	                    if(ie6){
	                        s.position='absolute';
	                        if(c.centered){
	                            s.setExpression('top', '(document.documentElement.clientHeight||document.body.clientHeight)/2-(this.offsetHeight/2)+(blah=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop)+"px"')
	                            s.marginTop=0;
	                        }else{
	                            var top=(c.modalCSS&&c.modalCSS.top)?parseInt(c.modalCSS.top):0;
	                            s.setExpression('top', '((blah=document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop)+'+top+')+"px"')
	                        }
	                    }else{
	                        if(c.centered){
	                            o.css({position:'fixed', top:'50%', marginTop:(o.outerHeight()/2)*-1})
	                        }else{
	                            o.css({position:'fixed'}).css(c.modalCSS);
	                        }
	                    }
	                }
	            },
	            setVHeight:function(){
	                if($(window).height()<$(document).height()){
	                    v.css({height:$(document).height()+'px'});
	                }else{
	                    v.css({height:'100%'});
	                    if(ie6){$('html,body').css('height','100%')}
	                }
	            },
	            removeModal:function(removeO){
	                o[c.disappearEffect](c.lightboxDisappearSpeed, function(){
	                    if(removeO){f.removeV()}
	                    c.destroyOnClose?o.remove():o.hide()
	                    o.find(c.closeSelector).unbind('click');
	                    o.unbind('close');
	                    o.unbind('resize');
	                    $(window).unbind('scroll', f.setOPosition());
	                    $(window).unbind('resize', f.setOPosition());
	                });
	            },
	            removeV:function(){
	                v.fadeOut(c.overlayDisappearSpeed, function(){
	                    $(window).unbind('resize', f.setVHeight());
	                    v.remove();
	                    v.unbind('click');
	                    c.onClose();
	                })
	            },
	            observeEscapePress:function(e){
	                if((e.keyCode==27||(e.DOM_VK_ESCAPE == 27&&e.which==0))&&c.closeEsc)f.removeModal(true);
	            }
			});
			if(v.length>0){
				f.removeModal();
            }else{
                v=$('<div />').addClass(c.classOverlay).css({display:'none'});
            }
			i=(i.length>0)?i:$('<iframe />').attr('id', 'lbIframe').css({
				zIndex:'+(c.zIndex+1)+',
				display:'none',
				border:'none',
				margin:0,
				padding:0,
				position:'absolute',
				width:'100%',
				height:'100%',
				top:0,
				left:0
			});
			if(ie6){
                var src=/^https/i.test(window.location.href||'')?'javascript:false':'about:blank';
                $i.attr('src', src); $('body').append(i);
            }
            $('body').append(o).append(v);
            
            f.setOPosition();
            o.css({left:'50%',marginLeft:(o.outerWidth()/2)*-1, zIndex:(c.zIndex + 3)});
            f.setVHeight();
            v.css({position:'absolute', width:'100%', top:0, left:0, right:0, bottom: 0, zIndex: (c.zIndex + 2) })
            	.css(c.overlayCSS);
            if(v.is(":hidden")){
                v.fadeIn(c.overlaySpeed, function(){
                    o[c.appearEffect](c.lightboxSpeed, function(){f.setVHeight(); c.onLoad()});
                });
            }else{
                o[c.appearEffect](c.lightboxSpeed, function(){f.setVHeight(); c.onLoad()});
            }
            $(window).bind('resize', function(){
            	f.setVHeight(); f.setOPosition()
            }).bind('scroll', function(){
            	f.setOPosition()
            }).bind('keydown', function(e){
            	f.observeEscapePress(e)
            });
            
            o.find(c.closeSelector).click(function(){f.removeModal(true); return false});
            v.click(function(){if(c.closeClick){f.removeModal(true); return false}});

   
            o.bind('close', function(){f.removeModal(true)});
            o.bind('resize', f.setOPosition);
		});
	};
	$.fn.popupAn = function(params){
		var conf = $.extend({}, params);
		return this.each(function(){
			var c=conf, o=$(this), f=this, urlPopup=o.attr('href');
			$.extend(f,{
				loadPopup:function(){
					o.bind('click', function(e){
						$.ajax({url:urlPopup, type:'get',
							success:function(data){
								$('.viewWBox', data).modal({
									closeHTML:"<a href='#' title='Close' class='itemClose'>Close</a>"
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
		/*$.fn.popupAn = function(params){
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
		});*/
	};
	/*$.fn.rtfAn = function(params){
		var conf = $.extend({controlIcon:'bold italic underline | color highlight removeformat | bullets numbering | alignleft center alignright justify | undo redo | link unlink | source'}, params);
		return this.each(function(){
			var c=conf,o=$(this);
			o.cleditor({width:o.innerWidth()-2, height:o.innerHeight()-2, controls:c.controlIcon});
		})
	};*/
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
	});
	$(window).load(function(){$('input:file').fileAn()})
})(jQuery);


/*(function($) {
	var tmp, loading, overlay, wrap, outer, content, close, title, nav_left, nav_right,
		selectedIndex=0, selectedOpts={}, selectedArray=[], currentIndex = 0, currentOpts = {}, currentArray = [],

		ajaxLoader = null, imgPreloader = new Image(), imgRegExp = /\.(jpg|gif|png|bmp|jpeg)(.*)?$/i, swfRegExp = /[^\.]\.(swf)\s*$/i,

		loadingTimer, loadingFrame = 1,

		titleHeight = 0, titleStr = '', start_pos, final_pos, busy = false, fx = $.extend($('<div/>')[0], { prop: 0 }),

		isIE6 = $.browser.msie && $.browser.version < 7 && !window.XMLHttpRequest,


		_abort = function() {
			loading.hide();

			imgPreloader.onerror = imgPreloader.onload = null;

			if (ajaxLoader) {
				ajaxLoader.abort();
			}

			tmp.empty();
		},

		_error = function() {
			if (false === selectedOpts.onError(selectedArray, selectedIndex, selectedOpts)) {
				loading.hide();
				busy = false;
				return;
			}

			selectedOpts.titleShow = false;

			selectedOpts.width = 'auto';
			selectedOpts.height = 'auto';

			tmp.html( '<p id="fancybox-error">The requested content cannot be loaded.<br />Please try again later.</p>' );

			_process_inline();
		},

		_start = function() {
			var obj = selectedArray[ selectedIndex ],
				href, 
				type, 
				title,
				str,
				emb,
				ret;

			_abort();

			selectedOpts = $.extend({}, $.fn.fancybox.defaults, (typeof $(obj).data('fancybox') == 'undefined' ? selectedOpts : $(obj).data('fancybox')));

			ret = selectedOpts.onStart(selectedArray, selectedIndex, selectedOpts);

			if (ret === false) {
				busy = false;
				return;
			} else if (typeof ret == 'object') {
				selectedOpts = $.extend(selectedOpts, ret);
			}

			title = selectedOpts.title || (obj.nodeName ? $(obj).attr('title') : obj.title) || '';

			if (obj.nodeName && !selectedOpts.orig) {
				selectedOpts.orig = $(obj).children("img:first").length ? $(obj).children("img:first") : $(obj);
			}

			if (title === '' && selectedOpts.orig && selectedOpts.titleFromAlt) {
				title = selectedOpts.orig.attr('alt');
			}

			href = selectedOpts.href || (obj.nodeName ? $(obj).attr('href') : obj.href) || null;

			if ((/^(?:javascript)/i).test(href) || href == '#') {
				href = null;
			}

			if (selectedOpts.type) {
				type = selectedOpts.type;

				if (!href) {
					href = selectedOpts.content;
				}

			} else if (selectedOpts.content) {
				type = 'html';

			} else if (href) {
				if (href.match(imgRegExp)) {
					type = 'image';

				} else if (href.match(swfRegExp)) {
					type = 'swf';

				} else if ($(obj).hasClass("iframe")) {
					type = 'iframe';

				} else if (href.indexOf("#") === 0) {
					type = 'inline';

				} else {
					type = 'ajax';
				}
			}

			if (!type) {
				_error();
				return;
			}

			if (type == 'inline') {
				obj	= href.substr(href.indexOf("#"));
				type = $(obj).length > 0 ? 'inline' : 'ajax';
			}

			selectedOpts.type = type;
			selectedOpts.href = href;
			selectedOpts.title = title;

			if (selectedOpts.autoDimensions) {
				if (selectedOpts.type == 'html' || selectedOpts.type == 'inline' || selectedOpts.type == 'ajax') {
					selectedOpts.width = 'auto';
					selectedOpts.height = 'auto';
				} else {
					selectedOpts.autoDimensions = false;	
				}
			}

			if (selectedOpts.modal) {
				selectedOpts.overlayShow = true;
				selectedOpts.hideOnOverlayClick = false;
				selectedOpts.hideOnContentClick = false;
				selectedOpts.enableEscapeButton = false;
				selectedOpts.showCloseButton = false;
			}

			selectedOpts.padding = parseInt(selectedOpts.padding, 10);
			selectedOpts.margin = parseInt(selectedOpts.margin, 10);

			tmp.css('padding', (selectedOpts.padding + selectedOpts.margin));

			$('.fancybox-inline-tmp').unbind('fancybox-cancel').bind('fancybox-change', function() {
				$(this).replaceWith(content.children());				
			});

			switch (type) {
				case 'html' :
					tmp.html( selectedOpts.content );
					_process_inline();
				break;

				case 'inline' :
					if ( $(obj).parent().is('#fancybox-content') === true) {
						busy = false;
						return;
					}

					$('<div class="fancybox-inline-tmp" />')
						.hide()
						.insertBefore( $(obj) )
						.bind('fancybox-cleanup', function() {
							$(this).replaceWith(content.children());
						}).bind('fancybox-cancel', function() {
							$(this).replaceWith(tmp.children());
						});

					$(obj).appendTo(tmp);

					_process_inline();
				break;

				case 'image':
					busy = false;

					$.fancybox.showActivity();

					imgPreloader = new Image();

					imgPreloader.onerror = function() {
						_error();
					};

					imgPreloader.onload = function() {
						busy = true;

						imgPreloader.onerror = imgPreloader.onload = null;

						_process_image();
					};

					imgPreloader.src = href;
				break;

				case 'swf':
					selectedOpts.scrolling = 'no';

					str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="' + selectedOpts.width + '" height="' + selectedOpts.height + '"><param name="movie" value="' + href + '"></param>';
					emb = '';

					$.each(selectedOpts.swf, function(name, val) {
						str += '<param name="' + name + '" value="' + val + '"></param>';
						emb += ' ' + name + '="' + val + '"';
					});

					str += '<embed src="' + href + '" type="application/x-shockwave-flash" width="' + selectedOpts.width + '" height="' + selectedOpts.height + '"' + emb + '></embed></object>';

					tmp.html(str);

					_process_inline();
				break;

				case 'ajax':
					busy = false;

					$.fancybox.showActivity();

					selectedOpts.ajax.win = selectedOpts.ajax.success;

					ajaxLoader = $.ajax($.extend({}, selectedOpts.ajax, {
						url	: href,
						data : selectedOpts.ajax.data || {},
						error : function(XMLHttpRequest, textStatus, errorThrown) {
							if ( XMLHttpRequest.status > 0 ) {
								_error();
							}
						},
						success : function(data, textStatus, XMLHttpRequest) {
							var o = typeof XMLHttpRequest == 'object' ? XMLHttpRequest : ajaxLoader;
							if (o.status == 200) {
								if ( typeof selectedOpts.ajax.win == 'function' ) {
									ret = selectedOpts.ajax.win(href, data, textStatus, XMLHttpRequest);

									if (ret === false) {
										loading.hide();
										return;
									} else if (typeof ret == 'string' || typeof ret == 'object') {
										data = ret;
									}
								}

								tmp.html( data );
								_process_inline();
							}
						}
					}));

				break;

				case 'iframe':
					_show();
				break;
			}
		},

		_process_inline = function() {
			var
				w = selectedOpts.width,
				h = selectedOpts.height;

			if (w.toString().indexOf('%') > -1) {
				w = parseInt( ($(window).width() - (selectedOpts.margin * 2)) * parseFloat(w) / 100, 10) + 'px';

			} else {
				w = w == 'auto' ? 'auto' : w + 'px';	
			}

			if (h.toString().indexOf('%') > -1) {
				h = parseInt( ($(window).height() - (selectedOpts.margin * 2)) * parseFloat(h) / 100, 10) + 'px';

			} else {
				h = h == 'auto' ? 'auto' : h + 'px';	
			}

			tmp.wrapInner('<div style="width:' + w + ';height:' + h + ';overflow: ' + (selectedOpts.scrolling == 'auto' ? 'auto' : (selectedOpts.scrolling == 'yes' ? 'scroll' : 'hidden')) + ';position:relative;"></div>');

			selectedOpts.width = tmp.width();
			selectedOpts.height = tmp.height();

			_show();
		},

		_process_image = function() {
			selectedOpts.width = imgPreloader.width;
			selectedOpts.height = imgPreloader.height;

			$("<img />").attr({
				'id' : 'fancybox-img',
				'src' : imgPreloader.src,
				'alt' : selectedOpts.title
			}).appendTo( tmp );

			_show();
		},

		_show = function() {
			var pos, equal;

			loading.hide();

			if (wrap.is(":visible") && false === currentOpts.onCleanup(currentArray, currentIndex, currentOpts)) {
				$.event.trigger('fancybox-cancel');

				busy = false;
				return;
			}

			busy = true;

			$(content.add( overlay )).unbind();

			$(window).unbind("resize.fb scroll.fb");
			$(document).unbind('keydown.fb');

			if (wrap.is(":visible") && currentOpts.titlePosition !== 'outside') {
				wrap.css('height', wrap.height());
			}

			currentArray = selectedArray;
			currentIndex = selectedIndex;
			currentOpts = selectedOpts;

			if (currentOpts.overlayShow) {
				overlay.css({
					'background-color' : currentOpts.overlayColor,
					'opacity' : currentOpts.overlayOpacity,
					'cursor' : currentOpts.hideOnOverlayClick ? 'pointer' : 'auto',
					'height' : $(document).height()
				});

				if (!overlay.is(':visible')) {
					if (isIE6) {
						$('select:not(#fancybox-tmp select)').filter(function() {
							return this.style.visibility !== 'hidden';
						}).css({'visibility' : 'hidden'}).one('fancybox-cleanup', function() {
							this.style.visibility = 'inherit';
						});
					}

					overlay.show();
				}
			} else {
				overlay.hide();
			}

			final_pos = _get_zoom_to();

			_process_title();

			if (wrap.is(":visible")) {
				$( close.add( nav_left ).add( nav_right ) ).hide();

				pos = wrap.position(),

				start_pos = {
					top	 : pos.top,
					left : pos.left,
					width : wrap.width(),
					height : wrap.height()
				};

				equal = (start_pos.width == final_pos.width && start_pos.height == final_pos.height);

				content.fadeTo(currentOpts.changeFade, 0.3, function() {
					var finish_resizing = function() {
						content.html( tmp.contents() ).fadeTo(currentOpts.changeFade, 1, _finish);
					};

					$.event.trigger('fancybox-change');

					content
						.empty()
						.removeAttr('filter')
						.css({
							'border-width' : currentOpts.padding,
							'width'	: final_pos.width - currentOpts.padding * 2,
							'height' : selectedOpts.autoDimensions ? 'auto' : final_pos.height - titleHeight - currentOpts.padding * 2
						});

					if (equal) {
						finish_resizing();

					} else {
						fx.prop = 0;

						$(fx).animate({prop: 1}, {
							 duration : currentOpts.changeSpeed,
							 easing : currentOpts.easingChange,
							 step : _draw,
							 complete : finish_resizing
						});
					}
				});

				return;
			}

			wrap.removeAttr("style");

			content.css('border-width', currentOpts.padding);

			if (currentOpts.transitionIn == 'elastic') {
				start_pos = _get_zoom_from();

				content.html( tmp.contents() );

				wrap.show();

				if (currentOpts.opacity) {
					final_pos.opacity = 0;
				}

				fx.prop = 0;

				$(fx).animate({prop: 1}, {
					 duration : currentOpts.speedIn,
					 easing : currentOpts.easingIn,
					 step : _draw,
					 complete : _finish
				});

				return;
			}

			if (currentOpts.titlePosition == 'inside' && titleHeight > 0) {	
				title.show();	
			}

			content
				.css({
					'width' : final_pos.width - currentOpts.padding * 2,
					'height' : selectedOpts.autoDimensions ? 'auto' : final_pos.height - titleHeight - currentOpts.padding * 2
				})
				.html( tmp.contents() );

			wrap
				.css(final_pos)
				.fadeIn( currentOpts.transitionIn == 'none' ? 0 : currentOpts.speedIn, _finish );
		},

		_format_title = function(title) {
			if (title && title.length) {
				if (currentOpts.titlePosition == 'float') {
					return '<table id="fancybox-title-float-wrap" cellpadding="0" cellspacing="0"><tr><td id="fancybox-title-float-left"></td><td id="fancybox-title-float-main">' + title + '</td><td id="fancybox-title-float-right"></td></tr></table>';
				}

				return '<div id="fancybox-title-' + currentOpts.titlePosition + '">' + title + '</div>';
			}

			return false;
		},

		_process_title = function() {
			titleStr = currentOpts.title || '';
			titleHeight = 0;

			title
				.empty()
				.removeAttr('style')
				.removeClass();

			if (currentOpts.titleShow === false) {
				title.hide();
				return;
			}

			titleStr = $.isFunction(currentOpts.titleFormat) ? currentOpts.titleFormat(titleStr, currentArray, currentIndex, currentOpts) : _format_title(titleStr);

			if (!titleStr || titleStr === '') {
				title.hide();
				return;
			}

			title
				.addClass('fancybox-title-' + currentOpts.titlePosition)
				.html( titleStr )
				.appendTo( 'body' )
				.show();

			switch (currentOpts.titlePosition) {
				case 'inside':
					title
						.css({
							'width' : final_pos.width - (currentOpts.padding * 2),
							'marginLeft' : currentOpts.padding,
							'marginRight' : currentOpts.padding
						});

					titleHeight = title.outerHeight(true);

					title.appendTo( outer );

					final_pos.height += titleHeight;
				break;

				case 'over':
					title
						.css({
							'marginLeft' : currentOpts.padding,
							'width'	: final_pos.width - (currentOpts.padding * 2),
							'bottom' : currentOpts.padding
						})
						.appendTo( outer );
				break;

				case 'float':
					title
						.css('left', parseInt((title.width() - final_pos.width - 40)/ 2, 10) * -1)
						.appendTo( wrap );
				break;

				default:
					title
						.css({
							'width' : final_pos.width - (currentOpts.padding * 2),
							'paddingLeft' : currentOpts.padding,
							'paddingRight' : currentOpts.padding
						})
						.appendTo( wrap );
				break;
			}

			title.hide();
		},

		_set_navigation = function() {
			if (currentOpts.enableEscapeButton || currentOpts.enableKeyboardNav) {
				$(document).bind('keydown.fb', function(e) {
					if (e.keyCode == 27 && currentOpts.enableEscapeButton) {
						e.preventDefault();
						$.fancybox.close();

					} else if ((e.keyCode == 37 || e.keyCode == 39) && currentOpts.enableKeyboardNav && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'SELECT') {
						e.preventDefault();
						$.fancybox[ e.keyCode == 37 ? 'prev' : 'next']();
					}
				});
			}

			if (!currentOpts.showNavArrows) { 
				nav_left.hide();
				nav_right.hide();
				return;
			}

			if ((currentOpts.cyclic && currentArray.length > 1) || currentIndex !== 0) {
				nav_left.show();
			}

			if ((currentOpts.cyclic && currentArray.length > 1) || currentIndex != (currentArray.length -1)) {
				nav_right.show();
			}
		},

		_finish = function () {
			if (!$.support.opacity) {
				content.get(0).style.removeAttribute('filter');
				wrap.get(0).style.removeAttribute('filter');
			}

			if (selectedOpts.autoDimensions) {
				content.css('height', 'auto');
			}

			wrap.css('height', 'auto');

			if (titleStr && titleStr.length) {
				title.show();
			}

			if (currentOpts.showCloseButton) {
				close.show();
			}

			_set_navigation();
	
			if (currentOpts.hideOnContentClick)	{
				content.bind('click', $.fancybox.close);
			}

			if (currentOpts.hideOnOverlayClick)	{
				overlay.bind('click', $.fancybox.close);
			}

			$(window).bind("resize.fb", $.fancybox.resize);

			if (currentOpts.centerOnScroll) {
				$(window).bind("scroll.fb", $.fancybox.center);
			}

			if (currentOpts.type == 'iframe') {
				$('<iframe id="fancybox-frame" name="fancybox-frame' + new Date().getTime() + '" frameborder="0" hspace="0" ' + ($.browser.msie ? 'allowtransparency="true""' : '') + ' scrolling="' + selectedOpts.scrolling + '" src="' + currentOpts.href + '"></iframe>').appendTo(content);
			}

			wrap.show();

			busy = false;

			$.fancybox.center();

			currentOpts.onComplete(currentArray, currentIndex, currentOpts);

			_preload_images();
		},

		_preload_images = function() {
			var href, 
				objNext;

			if ((currentArray.length -1) > currentIndex) {
				href = currentArray[ currentIndex + 1 ].href;

				if (typeof href !== 'undefined' && href.match(imgRegExp)) {
					objNext = new Image();
					objNext.src = href;
				}
			}

			if (currentIndex > 0) {
				href = currentArray[ currentIndex - 1 ].href;

				if (typeof href !== 'undefined' && href.match(imgRegExp)) {
					objNext = new Image();
					objNext.src = href;
				}
			}
		},

		_draw = function(pos) {
			var dim = {
				width : parseInt(start_pos.width + (final_pos.width - start_pos.width) * pos, 10),
				height : parseInt(start_pos.height + (final_pos.height - start_pos.height) * pos, 10),

				top : parseInt(start_pos.top + (final_pos.top - start_pos.top) * pos, 10),
				left : parseInt(start_pos.left + (final_pos.left - start_pos.left) * pos, 10)
			};

			if (typeof final_pos.opacity !== 'undefined') {
				dim.opacity = pos < 0.5 ? 0.5 : pos;
			}

			wrap.css(dim);

			content.css({
				'width' : dim.width - currentOpts.padding * 2,
				'height' : dim.height - (titleHeight * pos) - currentOpts.padding * 2
			});
		},

		_get_viewport = function() {
			return [
				$(window).width() - (currentOpts.margin * 2),
				$(window).height() - (currentOpts.margin * 2),
				$(document).scrollLeft() + currentOpts.margin,
				$(document).scrollTop() + currentOpts.margin
			];
		},

		_get_zoom_to = function () {
			var view = _get_viewport(),
				to = {},
				resize = currentOpts.autoScale,
				double_padding = currentOpts.padding * 2,
				ratio;

			if (currentOpts.width.toString().indexOf('%') > -1) {
				to.width = parseInt((view[0] * parseFloat(currentOpts.width)) / 100, 10);
			} else {
				to.width = currentOpts.width + double_padding;
			}

			if (currentOpts.height.toString().indexOf('%') > -1) {
				to.height = parseInt((view[1] * parseFloat(currentOpts.height)) / 100, 10);
			} else {
				to.height = currentOpts.height + double_padding;
			}

			if (resize && (to.width > view[0] || to.height > view[1])) {
				if (selectedOpts.type == 'image' || selectedOpts.type == 'swf') {
					ratio = (currentOpts.width ) / (currentOpts.height );

					if ((to.width ) > view[0]) {
						to.width = view[0];
						to.height = parseInt(((to.width - double_padding) / ratio) + double_padding, 10);
					}

					if ((to.height) > view[1]) {
						to.height = view[1];
						to.width = parseInt(((to.height - double_padding) * ratio) + double_padding, 10);
					}

				} else {
					to.width = Math.min(to.width, view[0]);
					to.height = Math.min(to.height, view[1]);
				}
			}

			to.top = parseInt(Math.max(view[3] - 20, view[3] + ((view[1] - to.height - 40) * 0.5)), 10);
			to.left = parseInt(Math.max(view[2] - 20, view[2] + ((view[0] - to.width - 40) * 0.5)), 10);

			return to;
		},

		_get_obj_pos = function(obj) {
			var pos = obj.offset();

			pos.top += parseInt( obj.css('paddingTop'), 10 ) || 0;
			pos.left += parseInt( obj.css('paddingLeft'), 10 ) || 0;

			pos.top += parseInt( obj.css('border-top-width'), 10 ) || 0;
			pos.left += parseInt( obj.css('border-left-width'), 10 ) || 0;

			pos.width = obj.width();
			pos.height = obj.height();

			return pos;
		},

		_get_zoom_from = function() {
			var orig = selectedOpts.orig ? $(selectedOpts.orig) : false,
				from = {},
				pos,
				view;

			if (orig && orig.length) {
				pos = _get_obj_pos(orig);

				from = {
					width : pos.width + (currentOpts.padding * 2),
					height : pos.height + (currentOpts.padding * 2),
					top	: pos.top - currentOpts.padding - 20,
					left : pos.left - currentOpts.padding - 20
				};

			} else {
				view = _get_viewport();

				from = {
					width : currentOpts.padding * 2,
					height : currentOpts.padding * 2,
					top	: parseInt(view[3] + view[1] * 0.5, 10),
					left : parseInt(view[2] + view[0] * 0.5, 10)
				};
			}

			return from;
		},

		_animate_loading = function() {
			if (!loading.is(':visible')){
				clearInterval(loadingTimer);
				return;
			}

			$('div', loading).css('top', (loadingFrame * -40) + 'px');

			loadingFrame = (loadingFrame + 1) % 12;
		};


	$.fn.fancybox = function(options) {
		if (!$(this).length) {
			return this;
		}

		$(this)
			.data('fancybox', $.extend({}, options, ($.metadata ? $(this).metadata() : {})))
			.unbind('click.fb')
			.bind('click.fb', function(e) {
				e.preventDefault();

				if (busy) {
					return;
				}

				busy = true;

				$(this).blur();

				selectedArray = [];
				selectedIndex = 0;

				var rel = $(this).attr('rel') || '';

				if (!rel || rel == '' || rel === 'nofollow') {
					selectedArray.push(this);

				} else {
					selectedArray = $("a[rel=" + rel + "], area[rel=" + rel + "]");
					selectedIndex = selectedArray.index( this );
				}

				_start();

				return;
			});

		return this;
	};

	$.fancybox = function(obj) {
		var opts;

		if (busy) {
			return;
		}

		busy = true;
		opts = typeof arguments[1] !== 'undefined' ? arguments[1] : {};

		selectedArray = [];
		selectedIndex = parseInt(opts.index, 10) || 0;

		if ($.isArray(obj)) {
			for (var i = 0, j = obj.length; i < j; i++) {
				if (typeof obj[i] == 'object') {
					$(obj[i]).data('fancybox', $.extend({}, opts, obj[i]));
				} else {
					obj[i] = $({}).data('fancybox', $.extend({content : obj[i]}, opts));
				}
			}

			selectedArray = jQuery.merge(selectedArray, obj);

		} else {
			if (typeof obj == 'object') {
				$(obj).data('fancybox', $.extend({}, opts, obj));
			} else {
				obj = $({}).data('fancybox', $.extend({content : obj}, opts));
			}

			selectedArray.push(obj);
		}

		if (selectedIndex > selectedArray.length || selectedIndex < 0) {
			selectedIndex = 0;
		}

		_start();
	};

	$.fancybox.showActivity = function() {
		clearInterval(loadingTimer);

		loading.show();
		loadingTimer = setInterval(_animate_loading, 66);
	};

	$.fancybox.hideActivity = function() {
		loading.hide();
	};

	$.fancybox.next = function() {
		return $.fancybox.pos( currentIndex + 1);
	};

	$.fancybox.prev = function() {
		return $.fancybox.pos( currentIndex - 1);
	};

	$.fancybox.pos = function(pos) {
		if (busy) {
			return;
		}

		pos = parseInt(pos);

		selectedArray = currentArray;

		if (pos > -1 && pos < currentArray.length) {
			selectedIndex = pos;
			_start();

		} else if (currentOpts.cyclic && currentArray.length > 1) {
			selectedIndex = pos >= currentArray.length ? 0 : currentArray.length - 1;
			_start();
		}

		return;
	};

	$.fancybox.cancel = function() {
		if (busy) {
			return;
		}

		busy = true;

		$.event.trigger('fancybox-cancel');

		_abort();

		selectedOpts.onCancel(selectedArray, selectedIndex, selectedOpts);

		busy = false;
	};

	// Note: within an iframe use - parent.$.fancybox.close();
	$.fancybox.close = function() {
		if (busy || wrap.is(':hidden')) {
			return;
		}

		busy = true;

		if (currentOpts && false === currentOpts.onCleanup(currentArray, currentIndex, currentOpts)) {
			busy = false;
			return;
		}

		_abort();

		$(close.add( nav_left ).add( nav_right )).hide();

		$(content.add( overlay )).unbind();

		$(window).unbind("resize.fb scroll.fb");
		$(document).unbind('keydown.fb');

		content.find('iframe').attr('src', isIE6 && /^https/i.test(window.location.href || '') ? 'javascript:void(false)' : 'about:blank');

		if (currentOpts.titlePosition !== 'inside') {
			title.empty();
		}

		wrap.stop();

		function _cleanup() {
			overlay.fadeOut('fast');

			title.empty().hide();
			wrap.hide();

			$.event.trigger('fancybox-cleanup');

			content.empty();

			currentOpts.onClosed(currentArray, currentIndex, currentOpts);

			currentArray = selectedOpts	= [];
			currentIndex = selectedIndex = 0;
			currentOpts = selectedOpts	= {};

			busy = false;
		}

		if (currentOpts.transitionOut == 'elastic') {
			start_pos = _get_zoom_from();

			var pos = wrap.position();

			final_pos = {
				top	 : pos.top ,
				left : pos.left,
				width :	wrap.width(),
				height : wrap.height()
			};

			if (currentOpts.opacity) {
				final_pos.opacity = 1;
			}

			title.empty().hide();

			fx.prop = 1;

			$(fx).animate({ prop: 0 }, {
				 duration : currentOpts.speedOut,
				 easing : currentOpts.easingOut,
				 step : _draw,
				 complete : _cleanup
			});

		} else {
			wrap.fadeOut( currentOpts.transitionOut == 'none' ? 0 : currentOpts.speedOut, _cleanup);
		}
	};

	$.fancybox.resize = function() {
		if (overlay.is(':visible')) {
			overlay.css('height', $(document).height());
		}

		$.fancybox.center(true);
	};

	$.fancybox.center = function() {
		var view, align;

		if (busy) {
			return;	
		}

		align = arguments[0] === true ? 1 : 0;
		view = _get_viewport();

		if (!align && (wrap.width() > view[0] || wrap.height() > view[1])) {
			return;	
		}

		wrap
			.stop()
			.animate({
				'top' : parseInt(Math.max(view[3] - 20, view[3] + ((view[1] - content.height() - 40) * 0.5) - currentOpts.padding)),
				'left' : parseInt(Math.max(view[2] - 20, view[2] + ((view[0] - content.width() - 40) * 0.5) - currentOpts.padding))
			}, typeof arguments[0] == 'number' ? arguments[0] : 200);
	};

	$.fancybox.init = function() {
		if ($("#fancybox-wrap").length) {
			return;
		}

		$('body').append(
			tmp	= $('<div id="fancybox-tmp"></div>'),
			loading	= $('<div id="fancybox-loading"><div></div></div>'),
			overlay	= $('<div id="fancybox-overlay"></div>'),
			wrap = $('<div id="fancybox-wrap"></div>')
		);

		outer = $('<div id="fancybox-outer"></div>')
			.append('<div class="fancybox-bg" id="fancybox-bg-n"></div><div class="fancybox-bg" id="fancybox-bg-ne"></div><div class="fancybox-bg" id="fancybox-bg-e"></div><div class="fancybox-bg" id="fancybox-bg-se"></div><div class="fancybox-bg" id="fancybox-bg-s"></div><div class="fancybox-bg" id="fancybox-bg-sw"></div><div class="fancybox-bg" id="fancybox-bg-w"></div><div class="fancybox-bg" id="fancybox-bg-nw"></div>')
			.appendTo( wrap );

		outer.append(
			content = $('<div id="fancybox-content"></div>'),
			close = $('<a id="fancybox-close"></a>'),
			title = $('<div id="fancybox-title"></div>'),

			nav_left = $('<a href="javascript:;" id="fancybox-left"><span class="fancy-ico" id="fancybox-left-ico"></span></a>'),
			nav_right = $('<a href="javascript:;" id="fancybox-right"><span class="fancy-ico" id="fancybox-right-ico"></span></a>')
		);

		close.click($.fancybox.close);
		loading.click($.fancybox.cancel);

		nav_left.click(function(e) {
			e.preventDefault();
			$.fancybox.prev();
		});

		nav_right.click(function(e) {
			e.preventDefault();
			$.fancybox.next();
		});

		if ($.fn.mousewheel) {
			wrap.bind('mousewheel.fb', function(e, delta) {
				if (busy) {
					e.preventDefault();

				} else if ($(e.target).get(0).clientHeight == 0 || $(e.target).get(0).scrollHeight === $(e.target).get(0).clientHeight) {
					e.preventDefault();
					$.fancybox[ delta > 0 ? 'prev' : 'next']();
				}
			});
		}

		if (!$.support.opacity) {
			wrap.addClass('fancybox-ie');
		}

		if (isIE6) {
			loading.addClass('fancybox-ie6');
			wrap.addClass('fancybox-ie6');

			$('<iframe id="fancybox-hide-sel-frame" src="' + (/^https/i.test(window.location.href || '') ? 'javascript:void(false)' : 'about:blank' ) + '" scrolling="no" border="0" frameborder="0" tabindex="-1"></iframe>').prependTo(outer);
		}
	};

	$.fn.fancybox.defaults = {
		padding : 10,
		margin : 40,
		opacity : false,
		modal : false,
		cyclic : false,
		scrolling : 'auto',	// 'auto', 'yes' or 'no'

		width : 560,
		height : 340,

		autoScale : true,
		autoDimensions : true,
		centerOnScroll : false,

		ajax : {},
		swf : { wmode: 'transparent' },

		hideOnOverlayClick : true,
		hideOnContentClick : false,

		overlayShow : true,
		overlayOpacity : 0.7,
		overlayColor : '#777',

		titleShow : true,
		titlePosition : 'float', // 'float', 'outside', 'inside' or 'over'
		titleFormat : null,
		titleFromAlt : false,

		transitionIn : 'fade', // 'elastic', 'fade' or 'none'
		transitionOut : 'fade', // 'elastic', 'fade' or 'none'

		speedIn : 300,
		speedOut : 300,

		changeSpeed : 300,
		changeFade : 'fast',

		easingIn : 'swing',
		easingOut : 'swing',

		showCloseButton	 : true,
		showNavArrows : true,
		enableEscapeButton : true,
		enableKeyboardNav : true,

		onStart : function(){},
		onCancel : function(){},
		onComplete : function(){},
		onCleanup : function(){},
		onClosed : function(){},
		onError : function(){}
	};

	$(document).ready(function() {
		$.fancybox.init();
	});

})(jQuery);*/