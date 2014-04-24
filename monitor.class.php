<?php

if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}


class plugin_monitor {
}

class plugin_monitor_forum extends plugin_monitor {


		function do_post_request($url, $data, $optional_headers = null)
			{
			     $params = array('http' => array(
			        'method' => 'POST',
			        'content' => $data
			         ));
			     if ($optional_headers !== null) {
			        $params['http']['header'] = $optional_headers;
			     }
			     $ctx = stream_context_create($params);
			     $fp = @fopen($url, 'rb', false, $ctx);
			     if (!$fp) {
			        throw new Exception("Problem with $url, $php_errormsg");
			     }
			     $response = @stream_get_contents($fp);
			     if ($response === false) {
			        throw new Exception("Problem reading data from $url, $php_errormsg");
			     }
			     return $response;
			}

		function post_checkreply_message($param) {
			global $_G;
			$web_root=$_G['siteurl'];
			if(substr($web_root,-1)!='/'){
				$web_root=$web_root.'/';
			}
			$sitename=$_G['setting'][bbname];
			$pid=$param ['param'] [2][pid];
			$tid=$param ['param'] [2][tid];
			$thread=C::t("forum_thread")->fetch($tid);
			$post=C::t("forum_post")->fetch('',$pid);
			$url=$web_root."forum.php?mod=viewthread&tid=$tid";
			$data = array(
				"author"=>$post[author],
				"message"=>$post[message],
				"title"=>$thread[subject],
				"sitename"=>$sitename,
				"clientip"=>$_G['clientip'],
				"time"=>time(),
				"url"=>$url,);
			if ($param ['param'] [0] == "post_reply_succeed" or $param ['param'] [0] == "post_newthread_succeed") {
				$postdata = http_build_query($data);
				$this->do_post_request("http://localhost:8080/test", $postdata);
			}
		}
}
		


	

?>