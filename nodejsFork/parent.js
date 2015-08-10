var cp = require('child_process');
var logger = require('tru-logger');
var fs = require('fs');

var file_name = __filename.substr(__dirname.length + 1);
var lock_file = __dirname + "/." + file_name;

console.log("starting");
init();

function init() {
	fs.exists(lock_file, function(exists) {
		console.log('1');
		if(exists) {
			console.log("Lock file '" + lock_file + "' exists");
			exit();
		} else {
			console.log('2');
			fs.writeFile(lock_file, process.pid, function(err) {
				if(err) {
					console.log(err);
				}
			});
		}
	});
	console.log('3');
}

function exit(cleanup) {
	if(cleanup) {
		fs.unlink(lock_file, function(err) {
			if(err) {
				console.log(err);
			}
		});
	}
	process.exit();
}

process.on("exit", function() {
	exit(true);
});

process.on("SIGINT", function() {
	exit(true);
});

process.on("uncaughtException", function() {
	exit(true);
});

var myLog = new logger(__dirname + "/log.txt", true);

var children = [];

for(var i = 0; i < 10; i++) {
	children[i] = forkChild(i);
}

function sendMessageToChild(index, msg) {
	var child = children[index];
	try {
		var m = {pid: child.pid};
	} catch(err) {
		console.log(index);
		console.log(child);
	}
	if(msg) {
		for(i in msg) {
			m[i] = msg[i];
		}
	}
	child.send(m);
}

function handleMessage(m) {
	//console.log('PARENT got message:', m);
	//console.log(m);
	/*if(m.child) {
		sendMessageToChild(m.child, m);
	}
	if(m.print) {
		myLog.add(m.print);
	}*/
	if(m.command !== undefined) {
		switch(m.command) {
			case "kill":
				if(m.child !== undefined) {
					console.log("Killing child '" + m.child + "'");
					children[m.child].kill();
				}
				break;
			default:
				console.log("Command not defined '" + m.command + "'");
		}
	}
}

function forkChild(i) {
	console.log("Forking child " + i);
	var child = cp.fork(__dirname + '/child.js', [i]);
	
	child.on('message', function(m) {
		handleMessage(m);
	});
	
	child.on('exit', function() {
		//console.log("Child " + i + " died");
		myLog.error("Child " + i + " died", true);
		children[i] = forkChild(i);
	});
	return child;
}

for(i in children) {
	sendMessageToChild(i);
}