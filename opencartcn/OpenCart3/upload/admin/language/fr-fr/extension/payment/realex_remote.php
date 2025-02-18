<?php

// Heading
$_['heading_title']						= 'Realex Remote';

// Text
$_['text_extension']					= 'Extensions';
$_['text_success']						= 'Félicitations, vous avez modifié le module de paiement Realex avec succès !';
$_['text_edit']							= 'Éditer le module de paiement Realex Remote';
$_['text_card_type']					= 'Type carte';
$_['text_enabled']						= 'Validé';
$_['text_use_default']					= 'Utiliser par défaut';
$_['text_merchant_id']					= 'Identifiant du marchand';
$_['text_subaccount']					= 'Sous-compte';
$_['text_secret']						= 'Mot secret';
$_['text_card_visa']					= 'Visa';
$_['text_card_master']					= 'Mastercard';
$_['text_card_amex']					= 'American Express';
$_['text_card_switch']					= 'Switch/Maestro';
$_['text_card_laser']					= 'Laser';
$_['text_card_diners']					= 'Diners';
$_['text_capture_ok']					= 'La capture a été réalisé avec succès';
$_['text_capture_ok_order']				= 'La capture a été réalisé avec succès, l’état de la commande est passé à « réglée »';
$_['text_rebate_ok']					= 'Le remboursement a été réalisé avec succès';
$_['text_rebate_ok_order']				= 'Le remboursement a été réalisé avec succès, l’état de la commande est passé à « remboursée »';
$_['text_void_ok']						= 'L’annulation a été réalisé avec succès, l’état de la commande est passé à « annulée »';
$_['text_settle_auto']					= 'Auto';
$_['text_settle_delayed']				= 'Différé';
$_['text_settle_multi']					= 'Multi';
$_['text_ip_message']					= 'Vous devez fournir l’adresse IP de votre serveur dans votre gestionnaire de compte Realex avant d’utiliser le mode réel';
$_['text_payment_info']					= 'Information de paiement';
$_['text_capture_status']				= 'Paiement capturé';
$_['text_void_status']					= 'Paiement annulé';
$_['text_rebate_status']				= 'Paiement remboursé';
$_['text_order_ref']					= 'Référence commande';
$_['text_order_total']					= 'Total autorisé';
$_['text_total_captured']				= 'Total capturé';
$_['text_transactions']					= 'Transactions';
$_['text_confirm_void']					= 'Êtes-vous sûr de vouloir annuler le paiement ?';
$_['text_confirm_capture']				= 'Êtes-vous sûr de vouloir capturer le paiement ?';
$_['text_confirm_rebate']				= 'Êtes-vous sûr de vouloir rembourser le paiement ?';
$_['text_realex_remote']				= '<a target="_BLANK" href="http://www.realexpayments.co.uk/partner-refer?id=opencart"><img src="view/image/payment/realex.png" alt="Realex" title="Realex" style="border: 1px solid #EEEEEE;" /></a>';

// Column
$_['text_column_amount']				= 'Montant';
$_['text_column_type']					= 'Type';
$_['text_column_date_added']			= 'Créé';

// Entry
$_['entry_merchant_id']					= 'Identifiant marchand';
$_['entry_secret']						= 'Mot secret';
$_['entry_rebate_password']				= 'Mot de passe pour le remboursement';
$_['entry_total']						= 'Total';
$_['entry_sort_order']					= 'Classement';
$_['entry_geo_zone']					= 'Zone géographique';
$_['entry_status']						= 'État';
$_['entry_debug']						= 'Journal de débogage';
$_['entry_auto_settle']					= 'Type de réglement';
$_['entry_tss_check']					= 'Contrôles TSS';
$_['entry_card_data_status']			= 'Enregistrement des informations de la carte';
$_['entry_3d']							= 'Valider 3D secure';
$_['entry_liability_shift']				= 'Accepter les scénarios de non-responsabilité';
$_['entry_status_success_settled']		= 'Succès - réglé';
$_['entry_status_success_unsettled']	= 'Succès - non réglé';
$_['entry_status_decline']				= 'Refusé';
$_['entry_status_decline_pending']		= 'Refusé - déconnecté';
$_['entry_status_decline_stolen']		= 'Refusé - Carte perdue ou volée';
$_['entry_status_decline_bank']			= 'Refusé - Erreur banque';
$_['entry_status_void']					= 'Annulé';
$_['entry_status_rebate']				= 'Remboursé';

// Help
$_['help_total']						= 'Le montant total que la commande doit atteindre avant que ce mode de paiement devienne actif.';
$_['help_card_select']					= 'Demander à l’utilisateur de choisir son type de carte avant qu’il ne soit redirigé';
$_['help_notification']					= 'Vous devez fournir cette URL à Realex pour obtenir des notifications de paiement';
$_['help_debug']						= 'Autoriser le débogage permettra dé~crire des données sensibles dans un journal. Vous devez toujours désactiver sauf indication contraire.';
$_['help_liability']					= 'Accepter la responsabilité signifie que vous devrez toujours accepter les paiements même quand un utilisateur ne parviendra pas à utiliser le 3D secure.';
$_['help_card_data_status']				= 'Mémoriser les 4 derniers chiffres de la carte, la date d’expiration, le nom, le type de carte et les informations de la banque émetrice.';

// Tab
$_['tab_api']							= 'Information sur l’API';
$_['tab_account']						= 'Comptes';
$_['tab_order_status']					= 'État de la commande';
$_['tab_payment']						= 'Paramètres de paiement';

// Button
$_['button_capture']					= 'Capturer';
$_['button_rebate']						= 'Rembourser';
$_['button_void']						= 'Annuler';

// Error
$_['error_merchant_id']					= 'L’identifiant marchand est requis !';
$_['error_secret']						= 'Le mot secret est requis';