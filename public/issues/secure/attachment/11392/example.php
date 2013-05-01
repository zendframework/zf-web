
		$mail = new Zend_Mail('UTF-8');
		$mail->addTo('example@example.com');

		$body = $this->view->fetch('booking/email.tpl');

		if (function_exists('tidy_repair_string'))
		{
			$xhtml = tidy_repair_string($body, array(
			'output-xhtml' => true,
			'show-body-only' => true,
			'doctype' => 'strict',
			'drop-font-tags' => true,
			'drop-proprietary-attributes' => true,
			'lower-literals' => true,
			'quote-ampersand' => true,
			'wrap' => 0), 'utf8');
		}
		else
		{
			$xhtml = $body;
		}

		$mail->setBodyHtml($xhtml);

		$dom = new DOMDocument(null, 'UTF-8');
		$dom->loadHTML($xhtml);

		$images = $dom->getElementsByTagName('img');

		for ($i = 0; $i < $images->length; $i++)
		{
			$img = $images->item($i);
			
			
			$url = $img->getAttribute('src');

			$image_http = new Zend_Http_Client($url);
			$image_content = $image_http->request()->getBody();
			$response = $image_http->getLastResponse();

			$pathinfo = pathinfo($url);

			$mime_type = $response->getHeader('Content-type');
			

			$mime = new Techi_Mime_Part($image_content);
			$mime->location = $url;
			$mime->type        = $mime_type;//.";\n\tname=\"".$pathinfo['basename']."\"";
			$mime->disposition = Zend_Mime::DISPOSITION_INLINE;
			$mime->encoding    = Zend_Mime::ENCODING_BASE64;
			$mime->filename    = $pathinfo['basename'];

			$mail->addAttachment($mime);
		}

		$mail->send();