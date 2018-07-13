/**
 * ClanTemplates Source Code
 * =============================================================================
 *
 * Author	Kalle Sommer Nielsen <kalle@php.net>
 * version	1.0
 * Copyright	Zesix Interactive 2006+
 *
 * =============================================================================
 */


/**
 * Configuration array
 */
var $members = [
		{
			'title':	'Total Qontrol.Agressions', 
			'rank':		'founder', 
			'extra': 	'Jos Vredeveld - 18 years old, The Netherlands',
			'images':
			{
				'full':		'./images/users/agressions.jpg', 
				'thumbnail':	'./images/users/agressions_thumbnail.jpg'
			}
		}, 
		{
			'title':	'Total Qontrol.User2', 
			'rank':		'Rank #2', 
			'extra': 	'Jos Vredeveld - 18 years old, The Netherlands', 
			'images':
			{
				'full':		'./images/users/agressions.jpg', 
				'thumbnail':	'./images/users/agressions_thumbnail.jpg'
			}
		}, 
		{
			'title':	'Total Qontrol.User3', 
			'rank':		'Rank #3', 
			'extra': 	'Jos Vredeveld - 18 years old, The Netherlands', 
			'images':
			{
				'full':		'./images/users/agressions.jpg', 
				'thumbnail':	'./images/users/agressions_thumbnail.jpg'
			}
		}, 
		{
			'title':	'Total Qontrol.User4', 
			'rank':		'Rank #4', 
			'extra': 	'Jos Vredeveld - 18 years old, The Netherlands', 
			'images':
			{
				'full':		'./images/users/agressions.jpg', 
				'thumbnail':	'./images/users/agressions_thumbnail.jpg'
			}
		}];


/**
 * Roster slideshow class
 */
var $roster = 
{
	'prefix':		'', 

	'current':		0, 
	'total':		0, 
	'members':		[], 

	init: function($prefix, $members)
	{
		this.prefix	= $prefix + '_';
		this.members 	= $members;
		this.total	= $members.length - 1;
	}, 

	render: function()
	{
		var active = this.getCurrent();

		this.setImg('previous', 	this.getPrevious().images.thumbnail);
		this.setImg('next', 		this.getNext().images.thumbnail);
		this.setImg('current_image', 	active.images.full);

		this.set('current_info_title', 	active.title);
		this.set('current_info_rank', 	active.rank);
		this.set('current_info_extra', 	active.extra);
	}, 

	next: function()
	{
		if(this.current == this.total)
		{
			this.current = 0;
		}
		else
		{
			++this.current;
		}

		this.render();
	}, 

	previous: function()
	{
		if(this.current == 0)
		{
			this.current = this.total;
		}
		else
		{
			--this.current;
		}

		this.render();
	}, 

	fetch: function(id)
	{
		return(document.getElementById(this.prefix + id));
	}, 

	set: function(id, value)
	{
		this.fetch(id).innerHTML = value;
	}, 

	setImg: function(id, image)
	{
		this.set(id, '<img src="' + image + '" alt="" />');
	}, 

	getCurrent: function()
	{
		return(this.members[this.current]);
	}, 

	getNext: function()
	{
		return(this.members[(this.current == this.total ? 0 : this.current + 1)]);
	}, 

	getPrevious: function()
	{
		return(this.members[(this.current == 0 ? this.total : this.current - 1)]);
	}
};