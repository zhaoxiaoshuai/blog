var express = require('express');
var router = express.Router();

/* GET home page. */
router.get('/', function(req, res, next) {
  // res.render('index', { title: 'Express' });
  // res.end('12345');
  	var crypto = require('crypto');
	function sha1(str) {
	    var md5sum = crypto.createHash('sha1');
	    md5sum.update(str);
	    str = md5sum.digest('hex');
	    return str;
	}
	exports.tocked=function(req, res, next) {
	    //微信得到返回后会通过你的认证
	    var query = req.query;
	    var signature = query.signature;
	    var echostr = query.echostr;
	    var timestamp = query['timestamp'];
		var nonce = query.nonce;
		var oriArray = new Array();
		oriArray[0] = nonce;
		oriArray[1] = timestamp;
		oriArray[2] = "jief";//这里填写你的token
		oriArray.sort();
		var original = oriArray[0]+oriArray[1]+oriArray[2];
		console.log("Original Str:"+original);
		console.log("signature:"+signature);
		var scyptoString = sha1(original);
		if (signature == scyptoString) {
		    res.send(echostr);
		}
	}
});

module.exports = router;

