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
$I->waitForElement('#email',30);
$I->see('Cieszymy się');
$I->wantTo('see other pages');
$I->click('Kalendarium');
$I->wait(1);
$I->click('Kontakt');
$I->waitForElement('#person', 30);
$I->see('@gmail.com');

