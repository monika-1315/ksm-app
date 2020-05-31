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
// $I->waitForElement('#surname',30);
// $I->wait(2);
$I->see('Cieszymy się');
$I->wantTo('see other pages');
$I->click('Kalendarium');
$I->wait(1.5);
$I->click('Kontakt');
$I->waitForElement('#person', 30);
$I->see('@gmail.com');

