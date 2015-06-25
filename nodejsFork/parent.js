var cp = require('child_process');

var children = [];

for(var i = 0; i < 10; i++) {
	children[i] = forkChild(i);
}

function sendMessageToChild(child, index, array) {
	child.send({ index: index });
}

function forkChild(i) {
	var child = cp.fork(__dirname + '/child.js', [i]);
	
	child.on('message', function(m) {
	  //console.log('PARENT got message:', m);
		console.log("Got message from child: " + m.index);
	});
	
	child.on('exit', function() {
		console.log("Child " + i + " died");
		children[i] = forkChild(i);
	});
	return child;
}

children.forEach(sendMessageToChild);