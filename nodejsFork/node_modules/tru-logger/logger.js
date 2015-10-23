var colors = require('colors');

colors.setTheme({
	error: 'red',
	info: 'cyan',
	warning: 'yellow',
	success: 'green',
	chat: 'magenta',
	add: 'grey'
});

Object.defineProperty(global, '__stack', {
	get: function() {
		var orig = Error.prepareStackTrace;
		Error.prepareStackTrace = function(_, stack) {
			return stack;
		};
		var err = new Error;
		Error.captureStackTrace(err, arguments.callee);
		var stack = err.stack;
		Error.prepareStackTrace = orig;
		return stack;
	}
});

Object.defineProperty(global, '__line', {
	get: function() {
		return __stack[1].getLineNumber();
	}
});

Object.defineProperty(global, '__function', {
	get: function() {
		return __stack[1].getFunctionName();
	}
});


function logger(logFile, date, print){
	this.logFile = logFile;
	if(date !== undefined) {
		this.date = date;
	} else {
		this.date = false;
	}

	if(print !== undefined) {
		this.print = print;
	} else {
		this.print = true;
	}
}

var p = logger.prototype;
var fs = require('fs');

p.error = function(msg) {
	var func = this.cleanFunctionName(__function);
	msg = this.buildMsg(func.toUpperCase() + ": " + msg.toString());
	this.append(msg, func);
}

p.info = function(msg) {
	var func = this.cleanFunctionName(__function);
	msg = this.buildMsg(func.toUpperCase() + ": " + msg.toString());
	this.append(msg, func);
}

p.warning = function(msg) {
	var func = this.cleanFunctionName(__function);
	msg = this.buildMsg(func.toUpperCase() + ": " + msg.toString());
	this.append(msg, func);
}

p.success = function(msg) {
	var func = this.cleanFunctionName(__function);
	msg = this.buildMsg(func.toUpperCase() + ": " + msg.toString());
	this.append(msg, func);
}

p.chat = function(msg) {
	var func = this.cleanFunctionName(__function);
	msg = this.buildMsg(func.toUpperCase() + ": " + msg.toString());
	this.append(msg, func);
}

p.add = function(msg) {
	var func = this.cleanFunctionName(__function);
	msg = this.buildMsg(msg.toString());
	this.append(msg, func);
}

p.append = function(msg, type) {
	if(this.print) {
		if(type === undefined) {
			type = "add";
		}
		console.log(colors[type](msg));
	}
	fs.appendFile(this.logFile, msg+"\n", function (err) {
		if (err) throw err;
	});
}

p.buildMsg = function(msg) {
	if (this.date) {
		var date = new Date();
		msg = date.toJSON() + " :: " + msg.toString();
	}
	return msg;
}

p.cleanFunctionName = function(func) {
	return func.substring(func.indexOf(".") + 1);
}

module.exports = logger;