<?php

	global $data;
	
	if (preg_match('/^\d+$/', $request[1]))
	{
		$request[1] = intval($request[1]);
	
		if ($request[1] == 0) redirect_to('/news/1');
		$data['start'] = ($request[1] - 1) * 8;
		$data['count'] = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM news"));
		$data['limit'] = (int)((($data['count'] = $data['count'][0]) - 1) / 8) + 1;
		if (($data['count'] - 1) < $data['start'] && $data['count'] > 0) redirect_to('/news/'.$data['limit']);
		$data['result'] = mysql_query("SELECT news.id, news.title, news.content, news.added_on, news.edited_on, users.username FROM news, users WHERE news.removed_on = '0000-00-00 00:00:00' AND users.id = news.user_id ORDER BY news.added_on DESC LIMIT ".$data['start'].", 8");
		while ($tmp = mysql_fetch_assoc($data['result'])) $data['items'][] = $tmp;

		include $_SERVER['DOCUMENT_ROOT'].'/views/news/all.view.php';
	}
	else if ($request[1] == 'one' && isset($request[2]) && preg_match('/^\d+$/', $request[2]))
	{
		$request[2] = intval($request[2]);
		
		if ($data['item'] = mysql_fetch_sssoc($data['result'] = mysql_query("SELECT news.id, news.title, news.content, news.added_on, news.edited_on, news.removed_on, users.username FROM news, users WHERE news.id = ".$request[2]." AND users.id = news.user_id"))) include $_SERVER['DOCUMENT_ROOT'].'/views/notfound.view.php';
		else include $_SERVER['DOCUMENT_ROOT'].'/views/news/one.view.php';
	}
	else include $_SERVER['DOCUMENT_ROOT'].'/views/notfound.view.php';
