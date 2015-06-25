var child = process.argv[2];

process.on('message', function(m) {
  //console.log('CHILD got message:', m);
	console.log("I am child " + child);
});

process.send({ index: child });