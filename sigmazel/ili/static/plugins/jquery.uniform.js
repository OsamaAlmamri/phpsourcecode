(function($) {
  $.uniform = {
    options: {
      selectClass:   'selector',
			radioClass: 'radio',
			checkboxClass: 'checker',
			fileClass: 'uploader',
			filenameClass: 'filename',
			fileBtnClass: 'action',
			fileDefaultText: 'No file selected',
			fileBtnText: 'Choose File',
			checkedClass: 'checked',
      focusClass: 'focus',
			disabledClass: 'disabled',
			activeClass: 'active',
			hoverClass: 'hover',
			useID: true,
			idPrefix: 'uniform',
			resetSelector: false
    },
    elements: []
  };

	if($.browser.msie && $.browser.version < 7){
		$.selectOpacity = false;
	}else{
		$.selectOpacity = true;
	}

  $.fn.uniform = function(options) {
    
		options = $.extend($.uniform.options, options);

    var el = this;
    //code for specifying a reset button
    if(options.resetSelector != false){
      $(options.resetSelector).mouseup(function(){
        function resetThis(){
          $.uniform.update(el);
        }
        setTimeout(resetThis, 10);
      });
    }
    
		function doSelect(elem){
			
			var divTag = $('<div />'),
	  			spanTag = $('<span />');
		
			divTag.addClass(options.selectClass);

			if(options.useID){
				divTag.attr("id", options.idPrefix+"-"+elem.attr("id"));
			}

			spanTag.html(elem.children(":selected").text());
			
			elem.css('opacity', 0);
			elem.wrap(divTag);
			elem.before(spanTag);
			
			//redefine variables
			divTag = elem.parent("div");
			spanTag = elem.siblings("span");
			
			elem.change(function() {
       	spanTag.text(elem.children(":selected").text());
     	})
     	.focus(function() {
      	divTag.addClass(options.focusClass);
     	})
     	.blur(function() {
      	divTag.removeClass(options.focusClass);
     	})
			.mousedown(function() {
				divTag.addClass(options.activeClass);
			})
			.mouseup(function() {
				divTag.removeClass(options.activeClass);
			})
			.hover(function() {
				divTag.addClass(options.hoverClass);
			}, function() {
				divTag.removeClass(options.hoverClass);
			})
			.keypress(function(){
			  spanTag.text(elem.children(":selected").text());
			});
			
			//handle disabled state
			if($(elem).attr("disabled")){
				//box is checked by default, check our box
				divTag.addClass(options.disabledClass);	
			}
			
			storeElement(elem);
			
		}
		
		function doCheckbox(elem){
			
			var divTag = $('<div />'),
	  			spanTag = $('<span />');
			
			divTag.addClass(options.checkboxClass);
			
			//assign the id of the element
			if(options.useID){
				divTag.attr("id", options.idPrefix+"-"+elem.attr("id"));
			}
			
			//wrap with the proper elements
			$(elem).wrap(divTag);
			$(elem).wrap(spanTag);
			
			//redefine variables
			spanTag = elem.parent();
			divTag = spanTag.parent();

			//hide normal input and add focus classes
			$(elem)
			.css("opacity", 0)
			.focus(function(){
				
				divTag.addClass(options.focusClass);
			})
			.blur(function(){
				
				divTag.removeClass(options.focusClass);
			})
			.click(function(){
				
				if(!$(elem).attr("checked")){	
					//box was just unchecked, uncheck span
					spanTag.removeClass(options.checkedClass);	
				}else{
					//box was just checked, check span.
					spanTag.addClass(options.checkedClass);
				}
			})
			.mousedown(function() {
				divTag.addClass(options.activeClass);
			})
			.mouseup(function() {
				divTag.removeClass(options.activeClass);
			})
			.hover(function() {
				divTag.addClass(options.hoverClass);
			}, function() {
				divTag.removeClass(options.hoverClass);
			});

			//handle defaults
			if($(elem).attr("checked")){
				//box is checked by default, check our box
				spanTag.addClass(options.checkedClass);	
			}
			
			//handle disabled state
			if($(elem).attr("disabled")){
				//box is checked by default, check our box
				divTag.addClass(options.disabledClass);	
			}
			
			storeElement(elem);
			
		}
		
		function doRadio(elem){
			
			var divTag = $('<div />'),
	  			spanTag = $('<span />');
			
			divTag.addClass(options.radioClass);
			
			if(options.useID){
				divTag.attr("id", options.idPrefix+"-"+elem.attr("id"));
			}
			
			//wrap with the proper elements
			$(elem).wrap(divTag);
			$(elem).wrap(spanTag);

			//redefine variables
			spanTag = elem.parent();
			divTag = spanTag.parent();

			//hide normal input and add focus classes
			$(elem)
			.css("opacity", 0)
			.focus(function(){
				divTag.addClass(options.focusClass);
			})
			.blur(function(){
				divTag.removeClass(options.focusClass);
			})
			.click(function(){
				if(!$(elem).attr("checked")){
					//box was just unchecked, uncheck span
					spanTag.removeClass(options.checkedClass);	
				}else{
					//box was just checked, check span
					$("."+options.radioClass + " span."+options.checkedClass + ":has([name='" + $(elem).attr('name') + "'])").removeClass(options.checkedClass);
					spanTag.addClass(options.checkedClass);
				}
			})
			.mousedown(function() {
			  if(!$(elem).is(":disabled")){
			    divTag.addClass(options.activeClass);
			  }
			})
			.mouseup(function() {
				divTag.removeClass(options.activeClass);
			})
			.hover(function() {
				divTag.addClass(options.hoverClass);
			}, function() {
				divTag.removeClass(options.hoverClass);
			});

			//handle defaults
			if($(elem).attr("checked")){
				//box is checked by default, check span
				spanTag.addClass(options.checkedClass);	
			}
			//handle disabled state
			if($(elem).attr("disabled")){
				//box is checked by default, check our box
				divTag.addClass(options.disabledClass);	
			}
			
			storeElement(elem);
			
		}
		
		function doFile(elem){
		  //sanitize input
		  $el = $(elem);
		  
			var divTag = $('<div />'),
	  			filenameTag = $('<span>'+options.fileDefaultText+'</span>'),
	  			btnTag = $('<span>'+options.fileBtnText+'</span>');
			
			divTag.addClass(options.fileClass);
			filenameTag.addClass(options.filenameClass);
			btnTag.addClass(options.fileBtnClass);
			
			if(options.useID){
				divTag.attr("id", options.idPrefix+"-"+$el.attr("id"));
			}
			
			//wrap with the proper elements
			$el.wrap(divTag);
			$el.after(btnTag);
      $el.after(filenameTag);
      
			//redefine variables
			divTag = $el.closest("div");
			filenameTag = $el.siblings("."+options.filenameClass);
			btnTag = $el.siblings("."+options.fileBtnClass);
			
			//set the size
		  if(!$el.attr("size")){
		    var divWidth = divTag.width();
		    //$el.css("width", divWidth);
		    $el.attr("size", divWidth/10);
		  }
		  
		  //actions
		  $el
			.css("opacity", 0)
			.focus(function(){
				divTag.addClass(options.focusClass);
			})
			.blur(function(){
				divTag.removeClass(options.focusClass);
			})
			.change(function(){
			  var filename = $el.val();
			  filename = filename.split("/");
			  filename = filename[(filename.length-1)];
        filenameTag.text($el.val());
			})
			.mousedown(function() {
			  if(!$(elem).is(":disabled")){
			    divTag.addClass(options.activeClass);
			  }
			})
			.mouseup(function() {
				divTag.removeClass(options.activeClass);
			})
			.hover(function() {
				divTag.addClass(options.hoverClass);
			}, function() {
				divTag.removeClass(options.hoverClass);
			});

		  //handle defaults
		  if($el.attr("disabled")){
				//box is checked by default, check our box
				divTag.addClass(options.disabledClass);	
			}
			
			storeElement(elem);
			
		}
		
		function storeElement(elem){
		  //store this element in our global array
		  elem = $(elem).get();
		  if(elem.length > 1){
		    $.each(elem, function(i, val){
		      $.uniform.elements.push(val);
		    });
		  }else{
		    $.uniform.elements.push(elem);
		  }
		}
		
		$.uniform.update = function(elem){
		  if(elem == undefined){
		    elem = $($.uniform.elements);
		  }
		  //sanitize input
		  elem = $(elem);
		  
		  elem.each(function(){
		    //do to each item in the selector
		    //function to reset all classes
        $e = $(this);
			  
  			if($e.is("select")){
  				//element is a select
  				spanTag = $e.siblings("span");
  				divTag = $e.parent("div");
  				
  				divTag.removeClass(options.hoverClass+" "+options.focusClass+" "+options.activeClass);
  				
  				//reset current selected text
  				spanTag.html($e.children(":selected").text());
  				
  				if($e.is(":disabled")){
  					divTag.addClass(options.disabledClass);
  				}else{
  				  divTag.removeClass(options.disabledClass);
  				}

  			}else if($e.is(":checkbox")){
  				//element is a checkbox
  				spanTag = $e.closest("span");
  				divTag = $e.closest("div");
  				
				  divTag.removeClass(options.hoverClass+" "+options.focusClass+" "+options.activeClass);
				  spanTag.removeClass(options.checkedClass);
				  
  				if($e.is(":checked")){
				    spanTag.addClass(options.checkedClass);
  				}
  				if($e.is(":disabled")){
				    divTag.addClass(options.disabledClass);
  				}else{
				    divTag.removeClass(options.disabledClass);
  				}

  			}else if($e.is(":radio")){
  				//element is a radio
  				spanTag = $e.closest("span");
  				divTag = $e.closest("div");
  				
  				divTag.removeClass(options.hoverClass+" "+options.focusClass+" "+options.activeClass);
  				spanTag.removeClass(options.checkedClass);
  				
  				if($e.is(":checked")){
				    spanTag.addClass(options.checkedClass);
  				}
  				
  				if($e.is(":disabled")){
				    divTag.addClass(options.disabledClass);
  				}else{
				    divTag.removeClass(options.disabledClass);
  				}
  			}else if($e.is(":file")){
  			  divTag = $e.parent("div");
  			  filenameTag = $e.siblings(options.filenameClass);
  			  btnTag = $e.siblings(options.fileBtnClass);
  			  
  			  divTag.removeClass(options.hoverClass+" "+options.focusClass+" "+options.activeClass);
  			  
  			  filenameTag.text($e.val());
  			  
  			  if($e.is(":disabled")){
				    divTag.addClass(options.disabledClass);
  				}else{
				    divTag.removeClass(options.disabledClass);
  				}
  			}
		  });
		}
		
    return this.each(function() {
			if($.selectOpacity){
				var elem = $(this);

				if(elem.is("select")){
					//element is a select
					if(elem.attr("multiple") != true){
					  //element is not a multi-select
					  doSelect(elem);
					}
				}else if(elem.is(":checkbox")){
					//element is a checkbox
					doCheckbox(elem);
				}else if(elem.is(":radio")){
					//element is a radio
					doRadio(elem);
				}else if(elem.is(":file")){
				  //element is a file upload
				  doFile(elem);
				}
				
			}
    });
  };
})(jQuery);