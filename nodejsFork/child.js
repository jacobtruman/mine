var child = process.argv[2];
var logger = require('tru-logger');
var myLog = new logger(__dirname + "/log" + child + ".txt", true, false);

process.on('message', function(m) {
	//console.log(child + " :: " + JSON.stringify(m));
	if(m.source) {
		var msg = "Child " + child + " receiving message from child " + m.source;
		myLog.success(msg, true);
		process.send({ print: msg });
	}
});

var number = -1;

while(number < 0 || number == child) {
	number = Math.floor((Math.random() * 10));
}

var msg = "Child " + child + " sending message to child " + number;
myLog.info(msg, true);
process.send({ source: child, child: number, command:"kill" });