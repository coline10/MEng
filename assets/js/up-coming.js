/*
MEng Computer Science 2014
Gym Booking System
*/

$.pageManager = (function () {
	
	$.weekdays={0: "Sunday", 1: "Monday", 2: "Tuesday", 3: "Wednesday", 4: "Thursday", 5: "Friday", 6: "Saturday"};
	$.months={0: "January", 1: "February", 2: "March", 3: "April", 4: "May", 5: "June", 6: "July", 7: "August", 8: "September", 9: "October", 10: "November", 11: "December"};

	resize = function(){
		if($('.list')[0] != undefined){
			$(".list").css('height',$(window).height() - $('.list').eq(0).offset().top);
			if($('.classes')[0] != undefined){
				// Get Max Height //http://stackoverflow.com/questions/6060992/element-with-the-max-height-from-a-set-of-elements
				$maxHeight = Math.max.apply(null, $(".classes").map(function () { return ($(this).offset().top-$(this).siblings(".panel-heading").eq(0).height()+20);}).get());
				$(".classes").css('max-height',$(window).height() - $maxHeight);
			}
		}
	},
	
	retreive = function(){ $.get('index.php/updateClasses' , function(data){ $(".row.list").html(data); });},
	
	nextHour = function(){
		retreive(); // CHANGE TO ??:30
		setTimer();
		newTime();
	},
	
	newTime = function(){
		var d = new Date();
		$time = d.getHours();
		$time = $time+":00 - " + ($time+1)%24 + ":00";
		
		console.log("TIME: " + $time);
		if(d.getHours() == 0)
		{
			$date = $.weekdays[d.getDay()] + ", " + suffix(d.getDate()) + " " + $.months[d.getMonth()];
			console.log("DATE: " + $date);
		}
	},
	
	suffix = function(i) {
		var j = i % 10;
		if (j == 1 && i != 11) {
			return i + "st";
		}
		if (j == 2 && i != 12) {
			return i + "nd";
		}
		if (j == 3 && i != 13) {
			return i + "rd";
		}
		return i + "th";
	},
	
	attendee = function ($row) {

		if($row.hasClass('success')){$attend=0;} else{$attend=1;}
		$.post('index.php/member/updateAttendance', { pid:$row.attr('id'),cid: $row.closest("div.panel").attr('id'), at: $attend}, function (data) {
			$row.toggleClass('success');
		});	
	},
	
	setTimer = function(){
		//http://www.angelsystems.net/Beyond/Wizard/InfoTech/Quest.aspx?wizMode=View&FileID=27&FileTitle=Refresh+A+Page+Every+30+Minutes+On+Hour+Or+Half+An+Hour&FileCategory=JavaScript&HwizMode=SEARCH&wizCategoryID=204&wizKeywords=0&iCurrentListPage=1
		var now = new Date();
		var minutes = now.getMinutes();
		var seconds = now.getSeconds();
		setTimeout('nextHour()',(((60 - (minutes % 60) - ((seconds>0)?1:0)) * 60) + (60 - seconds)) * 1000);
		console.log((60 - (minutes % 60) - ((seconds>0)?1:0)) * 60);
	},
	
	uiControls = function() {
		$( window ).on("resize", function() {resize()});
		$(".classes td").on("dblclick", function(){attendee($(this).parent("tr"));});
		$(".list").on('selectstart', function (event) {event.preventDefault();});
		$(".dropdown-menu li").on('click',  function() { console.log($(this)); $('.'+$(this).attr('id')).toggle();});
		setTimer();
		//newTime();
		//retreive();
	},

	resize();
	uiControls();
})();
