<?php
// Text
$_['text_new_card']                     = '+ A�adir nueva tarjeta';
$_['text_loading']                      = 'Cargando... Por favor, espere...';
$_['text_card_details']                 = 'Detalles tarjeta';
$_['text_saved_card']                   = 'Utilizar la tarjeta guardada:';
$_['text_card_ends_in']                 = 'Pagar con la tarjeta existente %s que termina en XXXX XXXX XXXX %s';
$_['text_card_number']                  = 'Tarjeta n�mero:';
$_['text_card_expiry']                  = 'Expira (MM/YY):';
$_['text_card_cvc']                     = 'C�digo seguridad (CVC):';
$_['text_card_zip']                     = 'C�digo postal de la tarjeta:';
$_['text_card_save']                    = '�Guardar tarjeta para usos futuros?';
$_['text_trial']                        = '%s cada %s %s para %s pagos ';
$_['text_recurring']                    = '%s cada %s %s';
$_['text_length']                       = ' para %s pagos';
$_['text_cron_subject']                 = 'Resumen de trabajos de Square CRON';
$_['text_cron_message']                 = 'Aqu� est� una lista de todas las tareas de CRON realizadas por su extensi�n Square:';
$_['text_squareup_profile_suspended']   = ' Sus pagos recurrentes se han suspendido. Por favor, p�ngase en contacto con nosotros para m�s detalles.';
$_['text_squareup_trial_expired']       = ' Su per�odo de prueba ha caducado.';
$_['text_squareup_recurring_expired']   = ' Sus pagos recurrentes han caducado. Este fue tu �ltimo pago.';
$_['text_cron_summary_token_heading']   = 'Actualizaci�n del token de acceso:';
$_['text_cron_summary_token_updated']   = '�Token de acceso actualizado';
$_['text_cron_summary_error_heading']   = 'Errores transacci�n:';
$_['text_cron_summary_fail_heading']    = 'Transacciones fallidas (Perfiles suspendidos):';
$_['text_cron_summary_success_heading'] = 'Transacciones correctas:';
$_['text_cron_fail_charge']             = 'Perfil <strong>#%s</strong> no se pudo cargar con <strong>%s</strong>';
$_['text_cron_success_charge']          = 'Perfil <strong>#%s</strong> cargado con <strong>%s</strong>';
$_['text_card_placeholder']             = 'XXXX XXXX XXXX XXXX';
$_['text_cvv']                          = 'CVV';
$_['text_expiry']                       = 'MM/YY';
$_['text_default_squareup_name']        = 'Tarjeta Cr�dito/D�bito';
$_['text_token_issue_customer_error']   = 'Estamos experimentando una interrupci�n t�cnica en nuestro sistema de pago. Por favor, int�ntelo de nuevo m�s tarde.';
$_['text_token_revoked_subject']        = '�Su token de acceso se ha revocado!';
$_['text_token_revoked_message']        = "El acceso de la extensi�n de pago Square a su cuenta se ha revocado en el panel de Square. Debe verificar las credenciales de la aplicaci�n en la configuraci�n de la extensi�n y volver a conectarse.";
$_['text_token_expired_subject']        = '�Tu token de acceso ha caducado!';
$_['text_token_expired_message']        = "El token de acceso de la extensi�n de pago Square que lo conecta a su cuenta de Square ha expirado. Debe verificar las credenciales de la aplicaci�n y el trabajo CRON en la configuraci�n de la extensi�n y volver a conectarse.";

// Error
$_['error_browser_not_supported']       = 'Error: El sistema de pago no es compatible con su navegador web. Actualice o utilice otro.';
$_['error_card_invalid']                = 'Error: �Tarjeta no v�lida!';
$_['error_squareup_cron_token']         = 'Error: El token de acceso no se pudo actualizar. Conecte su extensi�n Square a trav�s del panel de administraci�n de OpenCart.';

// Warning
$_['warning_test_mode']                 = 'Advertencia: �El modo Sandbox est� habilitado! Las transacciones parecer�n pasar, pero no se realizar�n cargos.';

// Statuses
$_['squareup_status_comment_authorized']    = 'La transacci�n de la tarjeta se ha autorizado pero a�n no se ha capturado.';
$_['squareup_status_comment_captured']      = 'La transacci�n de la tarjeta fue autorizada y posteriormente capturada (i.e., completado).';
$_['squareup_status_comment_voided']        = 'La transacci�n de la tarjeta fue autorizada y posteriormente anulada (i.e., cancelado).   ';
$_['squareup_status_comment_failed']        = 'Error en la transacci�n de la tarjeta.';

// Override errors
$_['squareup_override_error_billing_address.country']       = 'El pa�s de direcci�n de pago no es v�lido. Modif�quela e int�ntelo de nuevo.';
$_['squareup_override_error_shipping_address.country']      = 'El pa�s de direcci�n de env�o no es v�lido. Modif�quela e int�ntelo de nuevo.';
$_['squareup_override_error_email_address']                 = 'La direcci�n de correo electr�nico de su cliente no es v�lida. Modif�quela e int�ntelo de nuevo.';
$_['squareup_override_error_phone_number']                  = 'El n�mero de tel�fono de su cliente no es v�lido. Modif�quela e int�ntelo de nuevo.';
$_['squareup_error_field']                                  = ' Campo: %s';