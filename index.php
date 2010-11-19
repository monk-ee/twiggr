<?php


class twiggr {
	public $_twitter_name;
	public $_twitter_url = "http://twitter.com/";
	public $_curl_response;
	public $_dictionary = '[{"hey":["wassup","yo","sup"]},{"hi":["wassup","yo","sup"]},{"hello":["wassup","yo","sup"]},{"you":["yu"]},{"are":["r"]},{"be":["b"]},{"I":["this nigger","I","ai"]},{"am":["iz"]},{"is":["iz"]},{"perfect":["pufect"]},{"school":["skool"]},{"see":["c"]},{"thing":["thang"]},{"however":["howeva"]},{"ever":["eva"]},{"things":["thangs"]},{"have a":["gotta"]},{"getting":["getin"]},{"for":["fo"]},{"real":["gangsta"]},{"my":["ma"]},{"your":["yur"]},{"you\'re":["yur"]},{"leave":["bounce"]},{"were":["gotz"]},{"they":["dey"]},{"money":["scrilla","cabbage","fat scrill","marbles"]},{"cash":["scrilla","cabbage","fat scrill","marbles"]},{"dollars":["scrilla","cabbage","fat scrill","marbles"]},{"a":["uh"]},{"and":["un","an","n"]},{"coffee":["crack","green","cheese"]},{"tea":["dope","weedz"]},{"drink":["smoke"]},{"drinking":["smokin"]},{"kids":["lil gangstas"]},{"collect":["steal"]},{"with":["wiff","wit"]},{"today":["taday"]},{"bad":["whack"]},{"poor":["whack"]},{"nasty":["whack"]},{"cheap":["whack"]},{"not good":["whack"]},{"crappy":["whack"]},{"good":["da bomb","bangin","pimpin"]},{"great":["da bomb","bangin","pimpin"]},{"excellent":["da bomb","bangin","pimpin"]},{"this":["dis","tis"]},{"to":["ta"]},{"is":["iz"]},{"just":["jus"]},{"we were":["we\'s wuz"]},{"you are":["ur"]},{"shot":["smok\'d"]},{"killed":["smok\'d"]},{"of":["o"]},{"was":["wuz"]},{"police":["5-0","po-po"]},{"the":["tha","ta","da","d"]},{"ginger":["daywalka"]},{"redhead":["daywalka"]},{"friend":["bro","blood"]},{"mate":["bro","blood"]},{"guys":["bros","niggas","fools"]},{"girls":["bitches","hoes"]},{"gf":["baby momma","bitch","ho"]},{"girlfriend":["baby momma","bitch","ho"]},{"wife":["baby momma","bitch","ho"]},{"bf":["baby daddy","man","pimp"]},{"boyfirend":["baby daddy","man","pimp"]},{"true":["heezy"]},{"visit":["booty call"]},{"dollars":["bones"]},{"bucks":["bones"]},{"fat":["thick"]},{"son":["seed"]},{"daughter":["seed"]},{"child":["seed"]},{"tomorrow":["tmoz"]},{"wearing":["wearin"]},{"wear":["roc"]}]
';
	public $_logo_location = 'http://twiggr.com/resources/images/twitter_logo_header.png';
	public $_ebp_logo_location = 'http://twiggr.com/resources/images/ebp_logo.png';

	public function __construct() {
		$this->getTwitterName();
		$this->curlToTwitter();
		$this->swapOutTweets();
		$this->swapOutLogo();
		$this->swapOutTwitter();
		$this->swapOutAnalytics();
		$this->removePhishingBait();
		$this->swapFooter();

		$this->dumpOutPage();
		
	}
	public function getTwitterName() {
		if (!isset($_GET['q'])) {
			//@todo real page here but swap out logo  i think
			die("no name provided");
		}
		$this->_twitter_name = $_GET['q'];
	}
	public function curlToTwitter(){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$this->_twitter_url.$this->_twitter_name);
       		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       		curl_setopt($ch, CURLOPT_TIMEOUT, 36000);
			$this->_curl_response = curl_exec($ch);
			if (curl_errno($ch)) {
        		//print curl_error($ch);
				return false;
       		} else {
        		curl_close($ch);
				return true;
			}
	}
	public function swapOutTweets() {
		//<span class="entry-content">Banana Chips are awesome at first, but it's one of those foods that makes you feel ill when you consume a bit</span>
	 	$pattern = '/<span class="entry-content">(.*)<\/span>/';
		$this->_curl_response = preg_replace_callback($pattern, array('self', 'ebonize'),$this->_curl_response);
	}
	public function swapOutAnalytics() {
		// Twitters tracker UA-30775-6
		// Our tracker UA-319350-11
 		  $pattern = '/UA-30775-6/i';
		  $this->_curl_response = preg_replace($pattern, 'UA-319350-11', $this->_curl_response);
	}
	public function removePhishingBait() {
 		  $pattern = '/id="profilebox"/i';
		  $this->_curl_response = preg_replace($pattern, 'style="display:none;"', $this->_curl_response);
 		  $pattern = '/id="have_an_account"/i';
		  $this->_curl_response = preg_replace($pattern, 'style="display:none;"', $this->_curl_response);
	}
	public function swapFooter() {
	// $pattern = '/<div id="footer" (.*)<\/div>/';
	// $this->_curl_response = preg_replace($pattern, '<div id="footer" class="round" style="background-image:url('.$this->_ebp_logo_location.'); background-repeat:no-repeat; background-position: 10px center;">Digital Smartassery by <a href="http://www.explodingbox.com">EXPLODING BOX</a></div>', $this->_curl_response);
	
	// If i cant wildcard, i'll just fuck the rest of the div (at the end of that line vvvvv i have an unclosed div, which gets closed by the previous closing tag for the footer!
	$pattern = '/<div id="footer" class="round">/';
	$this->_curl_response = preg_replace($pattern, '<div id="footer" class="round" style="background-image:url('.$this->_ebp_logo_location.'); background-repeat:no-repeat; background-position: 10px center;">Digital Smartassery by <a href="http://www.explodingbox.com">EXPLODING BOX</a></div><div style="display:none;">', $this->_curl_response);


	}
	public function swapOutLogo() {
		  //<a href="http://twitter.com/" title="Twitter / Home" accesskey="1" id="logo">
          //<img alt="Twitter.com" src="http://a0.twimg.com/a/1290023717/images/twitter_logo_header.png" />
 		  // </a>
 		  $pattern = '/http:\/\/a0\.twimg.com\/a\/[0-9]+\/images\/twitter_logo_header.png/i';
		  $this->_curl_response = preg_replace($pattern, $this->_logo_location, $this->_curl_response);
	}
	public function swapOutTwitter() {
 		  $pattern = '/[\s]?Twitter\s/i';
		  $this->_curl_response = preg_replace($pattern, ' Twiggr ', $this->_curl_response);
 		  $pattern = '/\sTwitter/i';
		  $this->_curl_response = preg_replace($pattern, ' Twiggr', $this->_curl_response);
	}
	public function dumpOutPage() {
		echo $this->_curl_response;
	}
	public function ebonize($matches) {
		$dictionary = json_decode($this->_dictionary, true);
		$tweet = $matches[0];
		foreach($dictionary as $masterkey=>$mastervalue) {
			foreach($mastervalue as $key=>$value) {
				if(preg_match('/\b'.$key.'\b/i',$tweet)) {
					$randkey = array_rand($value);
					$tweet = preg_replace('/\b'.$key.'\b/i',$value[$randkey],$tweet);
				}
			}
		}
		return $tweet;
	}

}
$twggr = new twiggr();
?>
