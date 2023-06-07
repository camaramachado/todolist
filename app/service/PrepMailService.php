<?php

require_once 'init.php'; //loads the framework structure classes (app e lib)

/**
 * PrepMailService
 *
 * Service to config and send mails
 * @package service
 * @author Ricardo Câmara (camaramachado@gmail.com)
 * @version 1.0
 */
class PrepMailService
{        
     
    /**
     * Method send
     * send mail in HTML format for multiple recipients
     * @param $recipients array containing the recipients' emails
     * @param $subject string
     * @param $title string message title in the body of the email
     * @param $subtitle string message subtitle in the body of the email
     * @param $message string message in the body of the email
     * @returns bool
     */
    public static function send(iterable $recipients, string $subject, string $title, string $subtitle, string $message): bool
    {                
        try       
        {                                               
            //check if the emails are valid
            foreach($recipients as $recipient)
            {
				if(!filter_var($recipient, FILTER_VALIDATE_EMAIL))
	        		{ 
	        			throw new Exception('E-mail inválido: ' . $recipient);
	        		}
			}
        
            $template = 'app/resources/mail_template.html';

            if( !is_file($template) )
	        {
	            throw new Exception('Não foi possível ler o template de email');
	        }
	            
			$content = file_get_contents($template);			        			
			$content = str_replace('[TITLE]', $title, $content);			
			$content = str_replace('[SUBTITLE]', $subtitle, $content);			
			$content = str_replace('[MESSAGE]', $message, $content);

            MailService::send($recipients, $subject, $content, 'html');
            return true;
        }
        catch(Exception $e)        
        {
            new TMessage('error', 'Não foi possível enviar e-mail');
            //new TMessage('error', 'Não foi possível enviar e-mail: ' . $e->getMessage());
            //TToast::show('warning', 'Não foi possível enviar e-mail: ' . $e->getMessage(), 'top right', 'far:check-circle' );
            //throw new Exception('Não foi possível enviar e-mail. ' . $e->ErrorInfo);
            return false;
        }
    }
    
        
}