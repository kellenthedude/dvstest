<div class="sidebar">
	<div class="header-info">
		<h2><?=$experiment['name']?></h2>
		<h3>Reactivity: <?=$experiment['react_name'] ?></h3>
		<form class="filters">
			<h5>Order by:</h5>
			<div>
				<input type="radio" name="order_by" id="added" checked="checked" value="added" />
				<label for="added">Added</label>
				<input type="radio" name="order_by" id="target" value="target" />
				<label for="target">Target</label>
				<input type="radio" name="order_by" id="channel" value="channel" />
				<label for="channel">Channel</label>
				<input type="radio" name="order_by" id="abundance" value="abundance" />
				<label for="abundance">Abundance</label>
				<input type="radio" name="order_by" id="importance" value="importance" />
				<label for="importance">Importance</label>
				
			</div>
		</form>
	</div>
	
	<div class="hide-inactive-wrap<? if($experiment['hide_inactive']) echo ' active'; ?>">
		<label for="hide-inactive">Hide Inactive</label>
		<input type="checkbox" name="hide-inactive" id="hide-inactive"<? if($experiment['hide_inactive']) echo ' checked="checked"'; ?> />
	</div>
	
	<div class="node-block"></div>
	
	<a class="add-node">Add New</a>
	<div class="clear"></div>
</div>

<div class="data-output-wrap">
	<h2>Output Table</h2>
	
	<div id="output-key-table"></div>
	<div id="output-table"></div>
	
	<div id="grand-total"><h3>Total Spillover: <span></span></h3></div>
	
	<div class="clear"></div>
</div>

<div class="clear"></div>

<div class="node-temp hidden">
	<div class="node-wrap" data-node-id="0">
		<h5 class="new-label">Add New Antibody</h5>
		<form id="antib_form" data-id="">
			<div class="input-wrap prod-wrap" data-reactivity="<?=$experiment['react_id']?>">
				<label for="prod_01">Target</label>
				<input name="prod_01" id="prod_01" data-reactivity="<?=$experiment['react_id']?>" class="prod_list" />
				<!--
				<select name="prod_01" id="prod_01" data-reactivity="<?=$experiment['react_id']?>" class="prod_list">
					
				</select>
			-->
			</div>
			
			<?
				$cur_labels = $this->product_model->get_labels();
				//echo "<pre>";
				//print_r($cur_prods);
				//echo "</pre>";
			?>
			
			<? if(count($cur_labels)>0) : ?>
			<div class="input-wrap label-wrap" data-reactivity="<?=$experiment['react_id']?>">
				<label for="prod_01">Channel</label>
				
				<select name="label_01" id="label_01" data-reactivity="<?=$experiment['react_id']?>" class="label_list">
					<option value="">- Select -</option>
				</select>
			</div>
			<? endif; ?>
			
			<div class="input-wrap abundance-wrap" data-reactivity="<?=$experiment['react_id']?>">
				<label for="prod_01">Abundance</label>
				<select name="abundance_01" id="abundance_01" data-reactivity="<?=$experiment['react_id']?>" class="abundance_list">
					<option value="L">Low</option>
					<option value="M">Medium</option>
					<option value="H">High</option>
				</select>
			</div>
			
			<div class="input-wrap important-wrap">
				<label for="important_01">!</label>
				<input type="checkbox" name="important_01" id="important_01" class="node-important" />
			</div>
	
			<div class="input-wrap active-wrap">
				<label for="active_01">Active</label>
				<input type="checkbox" name="active_01" id="active_01" class="node-activate" />
			</div>
			
			<div class="input-wrap custom-wrap">
				<label for="custom_01">Custom Channel</label>
				<input type="checkbox" name="custom_01" id="custom_01" class="node-custom" />
			</div>
			
			<input type="hidden" name="react_id" value="<?=$experiment['react_id']?>" />
			<input type="hidden" name="exp_id" value="<?=$experiment['id']?>" />
			
			<a class="cancel-add-node">Cancel</a>
			<a class="cancel-delete-node">Delete</a>
			
			<div class="clear"></div>
		</form>
	</div>
</div>

<script type="text/javascript">
	(function($) {
	  $(function() {
	  	
	  		var nodeCount = 1;
	  		var ADDING = false;
	  		var grand_total = 0;
	  		
	  		var expID = <?=$experiment['id']?>;
	  		
	  		var spillover_table = new Array();
	  		var spillover_values = new Array();
	  		var spillover_totals = new Array();
	  		var active_channels = new Array();
	  		var duplicate_channels = new Array();
	  		var important_channels = new Array();
	  		
	  		$.get('/spillover/get/', function(data) {
	  			var channel_list = $.parseJSON( data );
	  			$.each(channel_list, function(index, value) {
	  				var cur_id = value['tag_id'];
	  				spillover_table[cur_id] = new Array();
	  				$.each(value, function(index2, value2) {
	  					spillover_table[cur_id][index2] = value2;
	  				});
	  			});
			});
	  		
	  		
	  		<?
				$cur_prods = $this->product_model->get_antibody(null, $experiment['react_id']);
			?>
	  		var antibodyList = [
				<? foreach($cur_prods as $product): ?>
					"<?=$product['target']?>",
				<? endforeach; ?>
		    ];
	  		
	  		$('#hide-inactive').change(function () {
	  			$(this).parent().toggleClass('active');
	  				
	  			if($(this).parent().hasClass('active')) {
	  				$('.node-wrap').each(function () {
	  					if(!$(this).hasClass('active') && !$(this).hasClass('adding')) $(this).addClass('hidden');
	  				});
	  			} else {
	  				$('.node-wrap').each(function () {
	  					if(!$(this).hasClass('active') && !$(this).hasClass('adding')) $(this).removeClass('hidden');
	  				});
	  			}
	  			
	  			if($(this).is(':checked')) {
		  			//BUILD URL FOR SET
		  			var hide = true;
		  		} else {
		  			var hide = false;
		  		}
		  		var exp_id = $('input[name=exp_id]').val();
		  		
		  		var set_url = '/experiment/set_hide/'+exp_id+'/'+hide;
		  		
		  		$.get(set_url, function(data) {

				});
		  		
	  		});
	  		
	  		$('input[name=order_by]').change(function() {
	  			reloadNodes($(this).val());
	  		});
		  	
		  	$('body').on('change', '.node-custom', function() {
		  		var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		
		  		if($(wrap_id).hasClass('active') || !$(wrap_id).hasClass('channel-added')) {
		  			$(this).parent().toggleClass('active');
		  			if($(this).is(':checked')) {
		  				updateChannels(wrap_id, 'null', null, false);
		  			} else {
		  				//alert($(wrap_id).children('form').children('.prod-wrap').children('.prod_list').html());
		  				updateChannels(wrap_id, $(wrap_id).children('form').children('.prod-wrap').children('.prod_list').val(), null, false);
		  			}
		  		}
		  		
		  		/*
		  		if($(wrap_id).hasClass('active') || !$(wrap_id).hasClass('channel-added')) {
			  		update_node(wrap_id, false);
			  		if($(this).is(':checked')) {
					    $(wrap_id).children('form').children('.label-wrap').removeClass('hidden');
				  		$(wrap_id).children('form').children('.prod-wrap').addClass('hidden');
					} else {
					    $(wrap_id).children('form').children('.label-wrap').addClass('hidden');
				  		$(wrap_id).children('form').children('.prod-wrap').removeClass('hidden');
					} 
					
					
		  		}
		  		*/
		  	});
		  	
		  	$('body').on('change', '.abundance_list', function() {
		  		update_node("#"+$(this).parent().parent().parent().attr('id'), false);
		  	});
		  	
		  	$('body').on('keyup', '.prod_list', function() {
		  		var no_update = false;
		  		var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		
		  		//turn off custom switch
			  	if($(wrap_id).children('form').children('.custom-wrap').hasClass('active')) {
			  		$(wrap_id).children('form').children('.custom-wrap').removeClass('active');
			  		$(wrap_id).children('form').children('.custom-wrap').children('.node-custom').removeAttr('checked');
		  		}
		  		
		  		if($(this).val() != "") {
		  			if($(wrap_id).hasClass('adding')) {
		  				no_update = true;
		  			}
		  			updateChannels(wrap_id, $(this).val(), null, no_update);
		  			//update_node("#"+$(this).parent().parent().parent().attr('id'), false);
		  		}
		  	});
		  	
		  	$('body').on('autocompletechange', '.prod_list', function( event, ui ) {
		  		//alert('change');
		  		var no_update = false;
		  		var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		if($(wrap_id).hasClass('adding')) {
	  				no_update = true;
	  			}
		  		updateChannels(wrap_id, $(this).val(), null, no_update);
		  	});
		  	
		  	$('body').on('autocompleteselect', '.prod_list', function( event, ui ) {
		  		//alert(ui.item.value);
		  		var no_update = false;
		  		var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		if($(wrap_id).hasClass('adding')) {
	  				no_update = true;
	  			}
		  		updateChannels(wrap_id, ui.item.value, null, no_update);
		  	});
		  	
		  	$('body').on('click', '.ui-autocomplete', function() {
		  		//var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		//updateChannels(wrap_id, $(this).val(), null);
		  	});
		  	
		  	$('body').on('change', '.label_list', function() {
		  		var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		update_node("#"+$(this).parent().parent().parent().attr('id'), false);
		  	});
		  	
		  	$('body').on('change', '.node-important', function() {
		  		var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		update_node("#"+$(this).parent().parent().parent().attr('id'), false);
		  	});
		  	
		  	$('body').on('click', '.node-activate', function() {
		  		var wrap_id = "#"+$(this).parent().parent().parent().attr('id');
		  		update_node(wrap_id, true);
		  		$(wrap_id).toggleClass('active');
		  		
		  		if($(this).parent().hasClass('active')) {
		  			$(this).parent().children('label').html('Inactive');
		  			$(wrap_id).children('form').children('.abundance-wrap').children('.abundance_list').attr('disabled', 'disabled');
			  		$(wrap_id).children('form').children('.prod-wrap').children('.prod_list').attr('disabled', 'disabled');
			  		$(wrap_id).children('form').children('.label-wrap').children('.label_list').attr('disabled', 'disabled');
			  		$(wrap_id).children('form').children('.important-wrap').children('.node-important').attr('disabled', 'disabled');
			  		if($('#hide-inactive').is(':checked')) $(wrap_id).addClass('hidden');
		  		} else {
		  			$(this).parent().children('label').html('Active');
		  			$(wrap_id).children('form').children('.abundance-wrap').children('.abundance_list').removeAttr('disabled');
			  		$(wrap_id).children('form').children('.prod-wrap').children('.prod_list').removeAttr('disabled');
			  		$(wrap_id).children('form').children('.label-wrap').children('.label_list').removeAttr('disabled');
			  		$(wrap_id).children('form').children('.important-wrap').children('.node-important').removeAttr('disabled');
			  		if($('#hide-inactive').is(':checked')) $(wrap_id).removeClass('hidden');
		  		}
		  		
		  		if($(this).parent().children('label').hasClass('activate')) $(this).parent().children('label').removeClass('activate');
		  		if($(wrap_id).hasClass('adding')) $(wrap_id).removeClass('adding');
		  		
		  		$(this).parent().toggleClass('active');
		  		
		  		if(!$(wrap_id).hasClass('channel-added')) {
		  			$(wrap_id).addClass('channel-added');
		  			$('.add-node').removeClass('hidden');
		  			ADDING = false;
		  		}
		  		
		  		reloadNodes($('.filters input[name=order_by]:checked').val());
		  		//updateCalculation();
		  		
		  	});
		  	
		  	$('body').on('click', '.cancel-add-node', function() {
		  		var wrap_id = "#"+$(this).parent().parent().attr('id');
		  		//alert(wrap_id);
		  		$(wrap_id).remove();
		  		nodeCount--;
		  		adding = false;
		  				  		
		  		$('.add-node').removeClass('hidden');
		  	});
		  	
		  	$('body').on('mouseover', '.node-wrap.channel-added', function() {
		  		//$(this).addClass('show-remove');
		  		//$(this).children('form').children('.cancel-delete-node').slideDown("fast");
		  	});
		  	
		  	$('body').on('mouseout', '.node-wrap.channel-added', function() {
		  		//$(this).removeClass('show-remove');
		  		//$(this).children('form').children('.cancel-delete-node').hide();
		  	});
		  	
		  	$('body').on('click', '.cancel-delete-node', function() {
		  		var wrap_id = "#"+$(this).parent().parent().attr('id');
		  		var node_id = $(wrap_id).attr('data-node-id');
		  	});
		  	
		  	function update_node(wrap_id, set) {
		  		
		  		if($(wrap_id).attr('data-node-id') != 0 || set == true) {
		  			
		  			var reactivity = $(wrap_id+' input[name=react_id]').val();
		  			var exp_id = $(wrap_id+' input[name=exp_id]').val();
		  			var target = escape($(wrap_id+' .prod_list').val());
		  			//alert(target);
		  			if(target == "") {
		  				target = "null";
		  			}
		  			var label = escape($(wrap_id+' .label_list').val());
		  			var abundance = $(wrap_id+' .abundance_list').val();
	
			  		if($(wrap_id+' .node-activate').is(':checked')) {
			  			//BUILD URL FOR SET
			  			var active = true;
			  		} else {
			  			var active = false;
			  		}
			  		if($(wrap_id+' .node-custom').is(':checked')) {
			  			//BUILD URL FOR SET
			  			var custom = true;
			  		} else {
			  			var custom = false;
			  		}
			  		if($(wrap_id+' .node-important').is(':checked')) {
			  			//BUILD URL FOR SET
			  			var important = true;
			  		} else {
			  			var important = false;
			  		}
			  		var set_url = '/node/set/'+exp_id+'/'+reactivity+'/'+target+'/'+active+'/'+custom+'/'+label+'/'+abundance+'/'+important;
			  		
			  		if($(wrap_id).attr('data-node-id') != 0) {
			  			set_url += '/'+$(wrap_id).attr('data-node-id');
			  		}
			  		
			  		//alert(set_url);
			  		
			  		$.get(set_url, function(data) {
			  			//alert('s');
					  	if($(wrap_id).attr('data-node-id') == 0) {
			  				$(wrap_id).attr('data-node-id', data);
				  		}
					});

		  		}
		  		updateCalculation();
		  		return false;
		  	}
		  	
		  	function updateChannels(wrap_id, target, set_val, no_update) {
		  		
		  		target = escape(target);
		  		var set_url = "/product/get_labels_by_target/"+target;
		  		
		  		//alert(set_url);

	  			$.get(set_url, function(data) {
		  				$(wrap_id).children('form').children('.label-wrap').children('select').find('option').remove().end();
		  				JSON.parse(data, function (key, value) {
						    if(key == 'tag') {
						    	var option_txt = '<option ';
						    	if(set_val != null && set_val == value) {
						    		option_txt += 'selected="selected" ';
						    	}
						    	option_txt += 'value="'+value+'">'+value+'</option>';
						    	$(wrap_id).children('form').children('.label-wrap').children('select').append(option_txt);
						    }
						    return value;
						});
						if(!no_update) {
							update_node(wrap_id, true);
						}

				});
		  	}
		  	
		  	$('.add-node').click(function() {
		  		var node_temp = $('.node-temp');
		  		var wrap_id = "node-"+nodeCount;
		  		node_temp.children('.node-wrap').attr("id", wrap_id);
		  		node_temp.children('.node-wrap').css('display', 'none');
		  		
		  		$('.node-block').append(node_temp.html());
		  		$('#'+wrap_id).slideDown('fast');
		  		//$('.node-block').append(node_temp.html());
		  		
		  		$('.node-block').children('#'+wrap_id).addClass('adding');
		  		
		  		$('.node-block').children('#'+wrap_id).children('form').attr("id", "antib_form_"+nodeCount);
		  		$('.node-block').children('#'+wrap_id).children('form').attr("data-id", nodeCount);
		  		
		  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('label').attr("for", "active_"+nodeCount);
		  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('input').attr("name", "active_"+nodeCount);
		  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('input').attr("id", "active_"+nodeCount);
		  		
		  		$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('input').attr("id", "prod_"+nodeCount);
		  		$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('input').attr("name", "prod_"+nodeCount);
		  		$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('label').attr("for", "prod_"+nodeCount);
		  		$( "#prod_"+nodeCount ).autocomplete({
			      source: antibodyList
			    });
		  		
		  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('label').html('Add');
		  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('label').addClass('activate');
		  		
		  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('label').attr("for", "custom_"+nodeCount);
		  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('input').attr("name", "custom_"+nodeCount);
		  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('input').attr("id", "custom_"+nodeCount);
		  		
		  		$('.add-node').addClass('hidden');
		  		ADDING = true;
		  		
		  		node_temp.children('.node-wrap').removeAttr("id");
		  		nodeCount++;
		  		
		  		//reloadNodes($('input [name=order_by]').val());
		  	});
		  	
		  	
		  	function updateCalculation() {
		  		
		  		spillover_values = new Array();
		  		active_channels = new Array();
		  		important_channels = new Array();
		  		duplicate_channels = new Array();
		  		
		  		//update active channels
		  		$('.node-wrap.channel-added.active').each(function() {
		  			var target = escape($(this).children('form').children('.label-wrap').children('.label_list').val().replace(/[^\d.]/g, ""));
		  			if(active_channels.indexOf(target) != -1) {
		  				duplicate_channels.push(target);
		  			}
		  			active_channels.push(target);
		  			if($(this).children('form').children('.important-wrap').children('.node-important').is(":checked")) {
		  				important_channels.push(target);
		  			}
		  		});
		  		
				var outHTML = "<table><thead><tr>";
				var keyHTML = "<table>";
				keyHTML += "<thead><tr><td>Channel</td></thead>";
				for(var i = 139; i <= 176; i++) {
					var className = "";
					if(active_channels.indexOf(i.toString()) != -1) {
						className += "active";
					}
					if(important_channels.indexOf(i.toString()) != -1) {
						className += " important";
					}
					if(duplicate_channels.indexOf(i.toString()) != -1) {
						className += " duplicate";
					}
					outHTML += "<td class='"+className+"'>"+i+'</td>';
				}
				outHTML += "</tr></thead>";
				
				var c_count = 0;
				outHTML += '<tbody>';
				$('.node-wrap.channel-added.active').each(function() {
					
					outHTML += '<tr>';
					keyHTML += '<tbody><tr><td>'+$(this).children('form').children('.label-wrap').children('.label_list').val()+'</td></tr></tbody>';
					
					var target = escape($(this).children('form').children('.label-wrap').children('.label_list').val().replace(/[^\d.]/g, ""));
		  			var abundance_modifier;
		  			switch ($(this).children('form').children('.abundance-wrap').children('.abundance_list').val()) {
					  case 'L':
					    abundance_modifier = 0.1;
					    break;
					  case 'M':
					    abundance_modifier = 1;
					    break;
					  case 'H':
					    abundance_modifier = 10;
					    break;
					}
		  			
		  			spillover_values[c_count] = new Array();
		  			spillover_values[c_count][0] = target;
		  			//console.log(abundance_modifier);
		  			
		  			var count = 139;
		  			if(spillover_table[target]) {
			  			$.each(spillover_table[target], function(index, value) {
			  				if(value !== undefined) {
			  					var className = "";
								if(active_channels.indexOf(count.toString()) != -1) {
									className += "active";
								}
								if(important_channels.indexOf(count.toString()) != -1) {
									className += " important";
								}
								if(duplicate_channels.indexOf(count.toString()) != -1) {
									className += " duplicate";
								}
			  					if(value == 100) {
			  						var num = 100;
			  					} else {
			  						var num = value*abundance_modifier;
			  					}
			  					if(num % 1 != 0) {
			  						num = num.toFixed(3);
			  					}
			  					outHTML += '<td class="'+className+'">'+num+'</td>';
			  					spillover_values[c_count][index] = num;
			  					count++;
			  				}
			  			});
		  			}
		  			
					outHTML += '</tr>';
					
					//console.log(c_count);
					
					c_count++;
					
				});
				outHTML += '</tbody>';
				
				//console.log(spillover_values);
				
				//Totals
				outHTML += '<tfoot><tr>';
				keyHTML += '<tfoot><tr><td>Total</td></tr></tfoot>';
				spillover_totals = new Array();
				for(var i = 139; i <= 176; i++) {
					var className = "";
					if(active_channels.indexOf(i.toString()) != -1) {
						className += "active";
					}
					if(important_channels.indexOf(i.toString()) != -1) {
						className += " important";
					}
					if(duplicate_channels.indexOf(i.toString()) != -1) {
						className += " duplicate";
					}
					var cur_total = 0;
					$.each(spillover_values, function(index, value) {
						if(value) {
							//console.log(value);
							if(value[i] != '100') {
								cur_total += parseFloat(value[i]);
							}
						}
					});
					if(duplicate_channels.indexOf(i.toString()) != -1) {
						cur_total += 100;
					}
					spillover_totals[i] = cur_total;
					
  					if(cur_total % 1 != 0) {
  						cur_total = cur_total.toFixed(3);
  					}
  					outHTML += '<td class="'+className+'">'+cur_total+'</td>';
				}
				outHTML += '</tr></tfoot>';
				
				grand_total = 0;
				$.each(spillover_totals, function(index, value) {
					if(value && active_channels.indexOf(index.toString()) != -1) {
						grand_total += value;
					}
				});
				$('#grand-total h3 span').html(grand_total);
				if(duplicate_channels.length > 0) {
					var conflict_string = '<br /><span class="conflict">Conflict on ';
					$.each(duplicate_channels, function(index, value) {
						conflict_string += value+", ";
					});
					conflict_string = conflict_string.substring(0, conflict_string.length - 2);
					conflict_string += '</span>';
					$('#grand-total h3 span').append(conflict_string);
				}
				
				//console.log(spillover_totals);
				
				outHTML += '</table>';
				$('#output-table').html(outHTML);
				$('#output-key-table').html(keyHTML);
			}
			
			
			function reloadNodes(order) {
				console.log('/node/get_exp_nodes/'+expID+'/'+order);
				$.get('/node/get_exp_nodes/'+expID+'/'+order, function(data) {
					
		  			var node_list = $.parseJSON( data );
		  			
		  			$('.node-block').html('');
		  			nodeCount = 0;
		  			
		  			$.each(node_list, function(index, value) {
		  				var node_temp = $('.node-temp');
				  		node_temp.children('.node-wrap').attr("id", "node-"+nodeCount);
				  		var wrap_id = "node-"+nodeCount;
				  		
				  		$('.node-block').append(node_temp.html());
				  		$('.node-block').children('#'+wrap_id).children('form').attr("id", "antib_form_"+nodeCount);
				  		$('.node-block').children('#'+wrap_id).children('form').attr("data-id", nodeCount);
				  		
				  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('label').attr("for", "active_"+nodeCount);
				  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('input').attr("name", "active_"+nodeCount);
				  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('input').attr("id", "active_"+nodeCount);
				  		
				  		$('.node-block').children('#'+wrap_id).children('form').children('.important-wrap').children('label').attr("for", "important_"+nodeCount);
				  		$('.node-block').children('#'+wrap_id).children('form').children('.important-wrap').children('input').attr("name", "important_"+nodeCount);
				  		$('.node-block').children('#'+wrap_id).children('form').children('.important-wrap').children('input').attr("id", "important_"+nodeCount);
				  		
				  		$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('input').attr("id", "prod_"+nodeCount);
			  			$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('input').attr("name", "prod_"+nodeCount);
			  			$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('label').attr("for", "prod_"+nodeCount);
			  			$( "#prod_"+nodeCount ).autocomplete({
					      source: antibodyList
					    });
				  		
				  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('label').attr("for", "custom_"+nodeCount);
				  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('input').attr("name", "custom_"+nodeCount);
				  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('input').attr("id", "custom_"+nodeCount);
				  		
				  		$('#'+wrap_id).addClass('channel-added');
				  		
				  		if(value['custom'] == 1) {
				  			updateChannels('#'+wrap_id, 'null', value['label'], false);
				  		} else {
				  			updateChannels('#'+wrap_id, value['target'], value['label'], false);
				  		}
				  		
				  		$('#'+wrap_id).children('form').children().children('.prod_list').val(unescape(value['target']));
				  		$('#'+wrap_id).children('form').children().children('.abundance_list').val(value['abundance_factor']);
				  		$('#'+wrap_id).attr('data-node-id', value['id']);
				  		if(value['active'] == 1) {
				  			$('#'+wrap_id).children('form').children('.active-wrap').children('.node-activate').attr('checked', 'checked');
				  			$('#'+wrap_id).addClass('active');
				  			$('#'+wrap_id).children('form').children('.active-wrap').addClass('active');
				  		} else {
				  			$('#'+wrap_id).children('form').children('.active-wrap').children('label').html('Inactive');
				  			$('#'+wrap_id).children('form').children('.abundance-wrap').children('.abundance_list').attr('disabled', 'disabled');
				  			$('#'+wrap_id).children('form').children('.prod-wrap').children('.prod_list').attr('disabled', 'disabled');
				  			$('#'+wrap_id).children('form').children('.label-wrap').children('.label_list').attr('disabled', 'disabled');
				  			$('#'+wrap_id).children('form').children('.important-wrap').children('.node-important').attr('disabled', 'disabled');
				  			
				  			if($('#hide-inactive').is(':checked')) {
				  				$('#'+wrap_id).addClass('hidden');
				  			}
				  		}
				  		if(value['custom'] == 1) {
				  			$('#'+wrap_id).children('form').children('.custom-wrap').children('.node-custom').attr('checked', 'checked');
				  			$('#'+wrap_id).children('form').children('.custom-wrap').addClass('active');
				  		}
				  		if(value['important'] == 1) {
				  			$('#'+wrap_id).children('form').children('.important-wrap').children('.node-important').attr('checked', 'checked');
				  			$('#'+wrap_id).children('form').children('.important-wrap').addClass('active');
				  		}
				  		
				  		node_temp.children('.node-wrap').removeAttr("id");
				  		nodeCount++;
				  		
				  		if($('#'+wrap_id).hasClass('hidden') && value['active'] == 1) {
			  				$('#'+wrap_id).removeClass('hidden');
			  			}
		  			});
				});
			}
			
		  	
		  	<?php
		  	/*
				foreach($exp_nodes as $node) : ?>
					
					var node_temp = $('.node-temp');
			  		node_temp.children('.node-wrap').attr("id", "node-"+nodeCount);
			  		var wrap_id = "node-"+nodeCount;
			  		
			  		$('.node-block').append(node_temp.html());
			  		$('.node-block').children('#'+wrap_id).children('form').attr("id", "antib_form_"+nodeCount);
			  		$('.node-block').children('#'+wrap_id).children('form').attr("data-id", nodeCount);
			  		
			  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('label').attr("for", "active_"+nodeCount);
			  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('input').attr("name", "active_"+nodeCount);
			  		$('.node-block').children('#'+wrap_id).children('form').children('.active-wrap').children('input').attr("id", "active_"+nodeCount);
			  		
			  		$('.node-block').children('#'+wrap_id).children('form').children('.important-wrap').children('label').attr("for", "important_"+nodeCount);
			  		$('.node-block').children('#'+wrap_id).children('form').children('.important-wrap').children('input').attr("name", "important_"+nodeCount);
			  		$('.node-block').children('#'+wrap_id).children('form').children('.important-wrap').children('input').attr("id", "important_"+nodeCount);
			  		
			  		$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('input').attr("id", "prod_"+nodeCount);
		  			$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('input').attr("name", "prod_"+nodeCount);
		  			$('.node-block').children('#'+wrap_id).children('form').children('.prod-wrap').children('label').attr("for", "prod_"+nodeCount);
		  			$( "#prod_"+nodeCount ).autocomplete({
				      source: antibodyList
				    });
			  		
			  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('label').attr("for", "custom_"+nodeCount);
			  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('input').attr("name", "custom_"+nodeCount);
			  		$('.node-block').children('#'+wrap_id).children('form').children('.custom-wrap').children('input').attr("id", "custom_"+nodeCount);
			  		
			  		$('#'+wrap_id).addClass('channel-added');
			  		
			  		<? if($node['custom'] == 1) : ?>
			  			updateChannels('#'+wrap_id, 'null', '<?=$node['label'] ?>', false);
			  		<? else : ?>
			  			updateChannels('#'+wrap_id, '<?=$node['target'] ?>', '<?=$node['label'] ?>', false);
			  		<? endif; ?>
			  		
			  		$('#'+wrap_id).children('form').children().children('.prod_list').val(unescape('<?=$node['target'] ?>'));
			  		$('#'+wrap_id).children('form').children().children('.abundance_list').val('<?=$node['abundance_factor'] ?>');
			  		$('#'+wrap_id).attr('data-node-id', <?=$node['id']?>);
			  		<? if($node['active'] == 1) : ?>
			  			$('#'+wrap_id).children('form').children('.active-wrap').children('.node-activate').attr('checked', 'checked');
			  			$('#'+wrap_id).addClass('active');
			  			$('#'+wrap_id).children('form').children('.active-wrap').addClass('active');
			  		<? else : ?>
			  			$('#'+wrap_id).children('form').children('.active-wrap').children('label').html('Inactive');
			  			$('#'+wrap_id).children('form').children('.abundance-wrap').children('.abundance_list').attr('disabled', 'disabled');
			  			$('#'+wrap_id).children('form').children('.prod-wrap').children('.prod_list').attr('disabled', 'disabled');
			  			$('#'+wrap_id).children('form').children('.label-wrap').children('.label_list').attr('disabled', 'disabled');
			  			$('#'+wrap_id).children('form').children('.important-wrap').children('.node-important').attr('disabled', 'disabled');
			  			<? if($experiment['hide_inactive']) : ?>$('#'+wrap_id).addClass('hidden');<? endif; ?>
			  		<? endif; ?>
			  		<? if($node['custom'] == 1) : ?>
			  			$('#'+wrap_id).children('form').children('.custom-wrap').children('.node-custom').attr('checked', 'checked');
			  			$('#'+wrap_id).children('form').children('.custom-wrap').addClass('active');
			  			//$('#'+wrap_id).children('form').children('.label-wrap').removeClass('hidden');
			  			//$('#'+wrap_id).children('form').children('.prod-wrap').addClass('hidden');
			  		<? endif; ?>
			  		<? if($node['important'] == 1) : ?>
			  			$('#'+wrap_id).children('form').children('.important-wrap').children('.node-important').attr('checked', 'checked');
			  			$('#'+wrap_id).children('form').children('.important-wrap').addClass('active');
			  		<? endif; ?>
			  		
			  		node_temp.children('.node-wrap').removeAttr("id");
			  		nodeCount++;
					
					<?
					
				endforeach;
			*/
			?>
			
			reloadNodes($('.filters input[name=order_by]:checked').val());
			updateCalculation();
		  	
	  	});
	})(jQuery);
</script>

