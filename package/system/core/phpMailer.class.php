<?php
/**
 *  Copyright (C) 2010 - 2020  <Robbyn Gerhardt>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package       Webpackages
 * @author        Robbyn Gerhardt
 * @copyright     Copyright (c) 2010 - 2020, Robbyn Gerhardt (http://www.robbyn-gerhardt.de/)
 * @license       http://opensource.org/licenses/MIT	MIT License
 * @link          http://webpackages.de
 * @since         Version 2020.0
 * @filesource
 */

namespace package\system\core;

use package\system\exceptions\phpMailerException;
use package\system\valueObjects\phpMailer\VOMailAddress;
use package\system\valueObjects\phpMailer\VOMailAttachment;
use package\system\valueObjects\phpMailer\VOPHPMailer;

class phpMailer extends initiator
{
	private $mail, $isConnected = false;

	/**
	 * phpMailer constructor.
	 *
	 * @param VOPHPMailer $parameter
	 *
	 * @throws phpMailerException
	 */
	public function __construct(VOPHPMailer $parameter)
	{
		if(!class_exists('PHPMailer'))
		{
			require 'PHPMailerAutoload.php';
		}

		parent::__construct();

		$this->mail = new \PHPMailer();

		if($parameter->is_smtp)
		{
			$this->mail->isSMTP();
		}

		if(!empty($parameter->host))
		{
			$this->mail->Host = $parameter->host;
		}

		if($parameter->is_smtp_auth)
		{
			$this->mail->SMTPAuth = true;
		}

		if(!empty($parameter->username))
		{
			$this->mail->Username = $parameter->username;
		}

		if(!empty($parameter->password))
		{
			$this->mail->Password = $parameter->password;
		}

		if(!empty($parameter->port))
		{
			$this->mail->Port = $parameter->port;
		}

		$this->isConnected	=	true;
	}

	/**
	 * Versenden einer E-Mail
	 *
	 * @param VOMailAddress      $from
	 * @param VOMailAddress[]    $to
	 * @param string             $subject
	 * @param string             $body
	 * @param string             $altBody
	 * @param VOMailAddress      $replyTo
	 * @param VOMailAddress[]    $ccTo
	 * @param VOMailAddress[]    $bccTo
	 * @param VOMailAttachment[] $attachments
	 * @param bool               $isHTML
	 *
	 * @return bool|String
	 * @throws phpMailerException
	 */
	public function send(VOMailAddress $from, $to = array(), $subject = '', $body = '', $altBody = '', VOMailAddress $replyTo = null, $ccTo = array(), $bccTo = array(), $attachments = array(), $isHTML = false)
	{
		if(!$this->isConnected)
		{
			throw new phpMailerException('Error: phpMailer is not connected');
		}

		if(empty($from->address))
		{
			throw new phpMailerException('Error: from is empty');
		}
		else if(empty($to))
		{
			throw new phpMailerException('Error: to is empty');
		}
		else if(empty($subject))
		{
			throw new phpMailerException('Error: subject is empty');
		}
		else if(empty($body))
		{
			throw new phpMailerException('Error: body is empty');
		}

		$this->mail->setFrom($from->address, $from->name);


		foreach($to as $t)
		{
			if(!$t instanceof VOMailAddress)
			{
				throw new phpMailerException('Error: to is not instance of VOMailAddress');
			}

			$this->mail->addAddress($t->address, $t->name);
		}

		if(!empty($replyTo->address))
		{
			$this->mail->addReplyTo($replyTo->address, $replyTo->name);
		}

		if(!empty($ccTo))
		{
			foreach($ccTo as $cc)
			{
				if(!$cc instanceof VOMailAddress)
				{
					throw new phpMailerException('Error: cc is not instance of VOMailAddress');
				}

				$this->mail->addCC($cc->address, $cc->name);
			}
		}

		if(!empty($bccTo))
		{
			foreach($bccTo as $bcc)
			{
				if(!$bcc instanceof VOMailAddress)
				{
					throw new phpMailerException('Error: bcc is not instance of VOMailAddress');
				}
				
				$this->mail->addBCC($bcc->address, $bcc->name);
			}
		}
		
		if(!empty($attachments))
		{
			foreach($attachments as $attachment)
			{
				if(!$attachment instanceof VOMailAttachment)
				{
					throw new phpMailerException('Error: attachment is not instance of VOMailAttachment');
				}
				
				$this->mail->addAttachment($attachment->path, $attachment->name, $attachment->encoding, $attachment->type, $attachment->disposition);
			}
		}

		if($isHTML)
		{
			$this->mail->isHTML(true);
		}
		else
		{
			$this->mail->isHTML(false);
		}

		$this->mail->Subject	=	$subject;
		$this->mail->Body		=	$body;

		if(!empty($altBody))
		{
			$this->mail->AltBody	=	$altBody;
		}

		if(!$this->mail->send())
		{
			return $this->mail->ErrorInfo;
		}

		return true;
	}
}
