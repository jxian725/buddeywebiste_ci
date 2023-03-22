<!DOCTYPE html>
<html>
<head>
	<title>Bootstrap Calendar Events Demo using Codeigniter</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/calendar/css/bootstrap.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/calendar/css/bootstrap-responsive.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/calendar/css/calendar.min.css">
	<script> var adminurl = '<?php echo $this->config->item('admin_url'); ?>'; </script>
</head>
<body>
<div class="container">

	<div class="page-header">
		<div class="pull-right form-inline">
			<div class="btn-group">
				<button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
				<button class="btn" data-calendar-nav="today">Today</button>
				<button class="btn btn-primary" data-calendar-nav="next">Next >></button>
			</div>
			<div class="btn-group">
				<button class="btn btn-warning" data-calendar-view="year">Year</button>
				<button class="btn btn-warning active" data-calendar-view="month">Month</button>
				<button class="btn btn-warning" data-calendar-view="week">Week</button>
				<button class="btn btn-warning" data-calendar-view="day">Day</button>
			</div>
		</div>

		<h3></h3>
	</div>

	<div class="row">
		<div class="span9">
			<div id="calendar"></div>
		</div>
		<div class="span3">
			<div class="row-fluid">
				<select id="first_day" class="span12">
					<option value="" selected="selected">First day of week is Sunday</option>
					<option value="1">First day of week is Monday</option>
				</select>
				<label class="checkbox">
					<input type="checkbox" value="#events-modal" id="events-in-modal"> Open events in modal window
				</label>
				<label class="checkbox">
					<input type="checkbox" id="format-12-hours"> 12 Hour format
				</label>
				<label class="checkbox">
					<input type="checkbox" id="show_wb" checked> Show week box
				</label>
				<label class="checkbox">
					<input type="checkbox" id="show_wbn" checked> Show week box number
				</label>
			</div>
		</div>
	</div>

	<div class="clearfix"></div>
	<br><br>

	<div class="modal hide fade" id="events-modal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3>Event</h3>
		</div>
		<div class="modal-body" style="height: 400px">
		</div>
		<div class="modal-footer">
			<a href="#" data-dismiss="modal" class="btn">Close</a>
		</div>
	</div>

	<script type="text/javascript" src="<?php echo base_url(); ?>plugins/calendar/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>plugins/calendar/js/underscore-min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>plugins/calendar/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>plugins/calendar/js/calendar.min.js"></script>
	<script>
		(function($) {
			"use strict";
			var options = {
				events_source: adminurl + 'buskerspod/load_data/<?= $pod_id; ?>',
				view: 'month',
				tmpl_path: adminurl + 'assets/tmpls/',
				tmpl_cache: false,
				//day: '2013-03-12',
				//modal: '#events-modal',
				onAfterViewLoad: function(view) {
					$('.page-header h3').text(this.getTitle());
					$('.btn-group button').removeClass('active');
					$('button[data-calendar-view="' + view + '"]').addClass('active');
				},
				classes: {
					months: {
						general: 'label'
					}
				}
			};

			var calendar = $('#calendar').calendar(options);

			$('.btn-group button[data-calendar-nav]').each(function(){
				var $this = $(this);
				$this.click(function() {
					calendar.navigate($this.data('calendar-nav'));
				});
			});

			$('.btn-group button[data-calendar-view]').each(function(){
				var $this = $(this);
				$this.click(function() {
					calendar.view($this.data('calendar-view'));
				});
			});

			$('#first_day').change(function(){
				var value = $(this).val();
				value = value.length ? parseInt(value) : null;
				calendar.setOptions({first_day: value});
				calendar.view();
			});

			$('#events-in-modal').change(function(){
				var val = $(this).is(':checked') ? $(this).val() : null;
				calendar.setOptions({modal: val});
			});
			$('#format-12-hours').change(function(){
				var val = $(this).is(':checked') ? true : false;
				calendar.setOptions({format12: val});
				calendar.view();
			});
			$('#show_wbn').change(function(){
				var val = $(this).is(':checked') ? true : false;
				calendar.setOptions({display_week_numbers: val});
				calendar.view();
			});
			$('#show_wb').change(function(){
				var val = $(this).is(':checked') ? true : false;
				calendar.setOptions({weekbox: val});
				calendar.view();
			});
			$('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
				//e.preventDefault();
				//e.stopPropagation();
			});
		}(jQuery));
	</script>
</div>
</body>
</html>