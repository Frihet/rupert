


var ResourceTracker = {

    initData: function() {
	/*
	  addDays does not play well with daylight savings time since
	  it adds exactly 24 hours of time. This function will always
	  return a date with zeroed time and the specified numer of
	  days away.
	*/
	Date.prototype['addDaysZero'] = function(num) {
	    this.zeroTime();
	    this.setTime(this.getTime() + (num*86400000) + (36000000));
	    this.zeroTime();
	    return this;
	}

	for( var i=0; i<ResourceTracker.data.length; i++) {
	    ResourceTracker.data[i].resourceUsage = [];
	    for( var j=0; j<ResourceTracker.data[i]._resource_usage.length; j++) {
		ResourceTracker.data[i].resourceUsage[j] = new ResourceTracker.Usage(ResourceTracker.data[i]._resource_usage[j]);
	    }
	}
    },
   
    Usage: function(data) {
	this.start = Date.fromString(data.start);
	this.stop = Date.fromString(data.stop);
	this.usage = data.usage;
	this.diff = this.stop.getTime()-this.start.getTime();
	
    },

    plotAll: function() {
	for( var i=0; i<ResourceTracker.data.length; i++) {
	    ResourceTracker.plot(ResourceTracker.data[i]);
	}
    },

    getAvailability: function(resource, date) {
	var usg = resource;
	var res = 0;
	var interval = 9999999999999999;
	var tm = date.getTime();
	
	for (var i=0; i<usg.length; i++) 
	{

	    if(usg[i].start.getTime() <= tm &&
	       usg[i].stop.getTime() >= tm &&
	       usg[i].diff < interval) 
	    {
		res = usg[i].usage;
		interval = usg[i].diff;
	    }
	    
	}
	
	return res;
    },

    plot: function(resource) {
	var data = [[ResourceTracker.start.getTime(), 0]];

	var d2;
	for(var d=new Date(ResourceTracker.start);
	    d.getTime() <= ResourceTracker.stop.getTime();
	    d.addDaysZero(1)) {
	    d2 = (new Date(d)).addDaysZero(1);
	    
	    var availability = ResourceTracker.getAvailability(resource.resourceUsage, d);
	    data.push([d.getTime(), availability]);
	    data.push([d2.getTime(), availability]);
	}
	data.push([d2.getTime(), 0]);
	
	$.plot($("#plot_" + resource.id), 
	       [{
		       data:data,
		       color: "#ff7f5f"
		   }], 
	       {
		   xaxis: {
		       mode: "time"
		   }, 
		   lines: 
		   {
		       show:true, 
		       fill:true
		   }, 
		   yaxis: {
		       min:0, 
		       max:100
		   },
		   grid: {
		       backgroundColor: "#ffffff",
			   markings: ResourceTracker.weekendAreas
		   }
	       }
	       );
	
    },
    // helper for returning the weekends in a period
    weekendAreas: function(axes) {
        var markings = [];
        var d = new Date(axes.xaxis.min);
        // go to the first Saturday
        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);
        var i = d.getTime();
        do {
            // when we don't set yaxis the rectangle automatically
            // extends to infinity upwards and downwards
            markings.push({
			      xaxis: { 
				  from: i, 
				  to: i + 2 * 24 * 60 * 60 * 1000 
			      }
			  });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }
    

};