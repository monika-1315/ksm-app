<?php
$I = new AcceptanceTester($scenario);
$I->am('guest'); // actor's role

$I->wantTo('view homepage'); // feature to test
$I->amOnPage('/');
$I->see('Witamy w aplikacji
    Katolickiego Stowarzyszenia Młodzieży
    Diecezji Legnickiej');
$I->see('Zaloguj się');

$I->wantTo('go to register page');
$I->click('Zarejestruj się');
$I->waitForElement('.card-header',30);
$I->waitForElement('#surname',30);
$I->see('Cieszymy się, że chcesz działać razem z nami!');

$I->wantTo('see upcoming events');
$I->click('Kalendarium');
$I->waitForElement('.activeTab');
$I->see('Wydarzenia:');
$I->see('W diecezji');

$I->wantTo('see the contact page');
$I->click('Kontakt');
$I->waitForElement('#person', 30);
$I->see('ksmdl.zarzad@gmail.com');
$I->see('ksmdl.sekretarz@gmail.com');
 
