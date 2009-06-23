
/**
   Namespace for all out code
 */
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

	for (var i=0; i<ResourceTracker.type.length; i++) {
	    var t = ResourceTracker.type[i];
	    t.fillColor = (new ResourceTracker.color(t.color)).blend(2.5).toHex();
	}
    },
   
    /**
       Constructor for a Usage object
     */
    Usage: function(data) {
	this.start = Date.fromString(data.start);
	this.stop = Date.fromString(data.stop);
	this.usage = data.usage;
	this.typeId = data.type_id;
	this.color = data.color;
	
    },

    color: function(hex) {
	hex = (hex.charAt(0)=="#") ? hex.substring(1,7):hex;
	this[0] = parseInt(hex.substring(0,2),16);
	this[1] = parseInt(hex.substring(2,4),16);
	this[2] = parseInt(hex.substring(4,6),16);

	this.toHex = function () {
	    var res = "#";
	    var str = "0123456789abcdef";
	    for(var i=0; i<3; i++) {
		var v = this[i];
		res += str.charAt(Math.floor(v/16)) + str.charAt(v%16);
	    }
	    return res;
	}

	this.blend = function (amount) {
	    if (amount < 1.0) {
		for(var i=0; i<3; i++) {
		    this[i] = Math.floor(Math.min(255, amount*this[i]));
		}	    
	    }
	    else {
		for(var i=0; i<3; i++) {
		    this[i] = 255 - Math.floor((255 - this[i])/amount);
		}	    
	    }
	    return this;
	}
    },

    /**
       Perform all plotting
     */
    plotAll: function() {
	for( var i=0; i<ResourceTracker.data.length; i++) {
	    ResourceTracker.plot(ResourceTracker.data[i]);
	}
    },

    getAvailability: function(resource, date, type) {
	var usg = resource;
	var res = 0;
	var tm = date.getTime();
	
	for (var i=0; i<usg.length; i++) 
	{

	    if(usg[i].start.getTime() <= tm &&
	       usg[i].stop.getTime() >= tm &&
	       usg[i].typeId == type)
	    {

		res += usg[i].usage;
	    }
	    
	}
	
	return res;
    },

    plot: function(resource) {

	var allData = [];

	for (var i=0; i<ResourceTracker.type.length; i++) {
	    var type = ResourceTracker.type[i];
	    var data = [[ResourceTracker.start.getTime(), 0]];
	    var yMax = 100;
	    var d2;
	    for(var d=new Date(ResourceTracker.start);
		d.getTime() <= ResourceTracker.stop.getTime();
		d.addDaysZero(1)) {
		d2 = (new Date(d)).addDaysZero(1);
		
		var availability = ResourceTracker.getAvailability(resource.resourceUsage, d, type.id);var pre=0;
                if(i > 0 ) {
		    pre = allData[allData.length-1].data[data.length][1];
		    availability += pre;
		}
		yMax = (availability>yMax)?availability:yMax;
		data.push([d.getTime(), availability, pre]);
		data.push([d2.getTime(), availability, pre]);
	    }
	    data.push([d2.getTime(), 0]);
	    
	    allData.push({
                        data: data,
			color: type.color,
			/*			bars: {
			    show:true,
			    fill:true,
			    barWidth:3600*24*1000,
			    }*/
			lines: {
			    show:true,
			    fill:true/*,
				       fillColor : type.fillColor*/
			}
		});
	}

	ResourceTracker.ggg = allData;
	
	$.plot($("#plot_" + resource.id), 
	       allData.reverse(),
	       {
		   xaxis: {
		       mode: "time"
		   }, 
		       /*		   lines: 
		   {
		       show:true, 
		       fill:true
		       }, */
		   yaxis: {
		       min:0, 
		       max:yMax
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